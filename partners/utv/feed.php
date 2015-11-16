<?php
$send_response = "{'response': 'unauthorized'}";
if (isset($_GET['customer']) && isset($_GET['q'])) {
  header("Content-Type: text/plain");
  $expireAge = 5*60;
  header("Cache-Control: max-age=$expireAge");
  header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expireAge) . " GMT");

  $mylink = mysql_pconnect('mysql_master', 'inagist', 'inagist');
  if ($mylink) {
    $db = mysql_select_db("inagist", $mylink);
    $customer = mysql_real_escape_string($_GET['customer']);
    $key = mysql_real_escape_string($_GET['q']);
    $query = "select hval from key_value_store where customer = '$customer' and hkey = '$key'";
    $result = mysql_query($query);
    if ($result){
      while ($row = mysql_fetch_assoc($result)){
        echo($row['hval']."\r\n");
      }
    }
    flush();
    mysql_free_result($result);
    mysql_close($mylink);
  }
} else {
  session_set_cookie_params(30 * 60 * 60, '/', '.inagist.com');
  session_start();
  if (isset($_SESSION['user_id'])) {
    header("Content-Type: text/json");
    $mylink = mysql_pconnect('mysql_master', 'inagist', 'inagist');
    if ($mylink) {
      $userid = $_SESSION['user_id'];
      $db = mysql_select_db('inagist', $mylink);
      $result = mysql_query("select customer from partner_user_mapping where userid = '$userid' and enabled = true");
      $customer = null;
      if ($result){ 
        $user_properties = mysql_fetch_assoc($result);
        $customer = $user_properties['customer'];
        mysql_free_result($result);
      }
      if (isset($customer)){
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
          $key = $_GET['key'];
          if (isset($key)){
            $key = mysql_real_escape_string($key);
            $query = "select id, hval from key_value_store where customer = '$customer' and hkey = '$key'";
          } else
            $query = "select distinct hkey as hkey from key_value_store where customer = '$customer'";
          $result = mysql_query($query);
          if ($result){
            $response = array();
            while ($row = mysql_fetch_assoc($result)){
              $response[] = $row;
            }
            $send_response = json_encode($response, true);
          } else {
            $send_response =  "{'error':'fetch error ".mysql_error()."'}";
          }
          mysql_free_result($result);
        } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
          $id = $_REQUEST['id'];
          $key = $_REQUEST['key'];
          if (isset($key)){
            $key = mysql_real_escape_string($key);
            if (isset($id)){
              $id = mysql_real_escape_string($id);
              $query = "delete from key_value_store where customer = '$customer' and hkey = '$key' and id = $id";
            } else
              $query = "delete from key_value_store where customer = '$customer' and hkey = '$key'";
            $result = mysql_query($query);
            if ($result){
              $send_response =  "{'result':'success'}";
            } else {
              header ("HTTP/1.1 404 Error");
              $send_response = "{'error':'fetch error ".mysql_error()."'}";
            }
            mysql_free_result($result);
          }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT'){
          $body = file_get_contents("php://input");
          $request_body = json_decode($body, true);
          $insert_values = array();
          foreach ($request_body as $key => $value){
            $key = mysql_real_escape_string($key);
            if (is_array($value)){
              foreach ($value as $val){
                $val = mysql_real_escape_string($val);
                $insert_values[] = "('$customer', '$key', '$val')";
              }
            } else {
              $value = mysql_real_escape_string($value);
              $insert_values[] = "('$customer', '$key', '$value')";
            }
          }
          $values = implode(",", $insert_values);
          $result = mysql_query("insert into key_value_store (customer, hkey, hval) values $values");
          if ($result){
            $send_response = "{'result':'success'}";
          } else {
            header ("HTTP/1.1 404 Error");
            echo ($body. " - " . $values);
          }
          mysql_free_result($result);
        }
      } else {
        header ("HTTP/1.1 401 Unauthorized");
      }
      mysql_close($mylink);
    }
    if (isset($_REQUEST['callback']))
      echo ($_REQUEST['callback']."(".$send_response.")");
    else
      echo ($send_response);
    flush();
  } else {
    header ("HTTP/1.1 401 Unauthorized");
  }
}
?>
