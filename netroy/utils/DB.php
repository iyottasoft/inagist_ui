<?php
Class DB{
  private static $conn = null;

  public function __construct(){
    if(self::$conn == null){
      global $sql_host,$sql_user,$sql_password,$sql_db;
      self::$conn = mysql_connect($sql_host,$sql_user,$sql_password);
    }
    mysql_select_db($sql_db,self::$conn);    
  }

  public function query($sql,$renderer=null){
    if($sql=="") return null;
    $result = mysql_query($sql,self::$conn);
    if(mysql_error()) return mysql_error();
    if(mysql_num_rows($result)==0) return 0;

    $data = array();
    while($row = mysql_fetch_assoc($result)){
      if(function_exists($renderer)) $row = call_user_func($renderer,&$row);
      if(isset($row["id"])) $data[$row["id"]] = $row;
      else $data[] = $row;
    }
    return $data;
  }

  public function update($sql){
    if($sql=="") return null;
    $result = mysql_query($sql,self::$conn);
    if(mysql_error()) return mysql_error();
    return true;
  }

  public function insert($sql){
    if($sql=="") return null;
    $result = mysql_query($sql,self::$conn);
    if(mysql_error()) return mysql_error();
    return mysql_insert_id();
  }

  public function delete($sql){
    if($sql=="") return null;
    $result = mysql_query($sql,self::$conn);
    if(mysql_error()) return mysql_error();
    return mysql_affected_rows();
  }
}
?>
