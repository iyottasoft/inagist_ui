<?php

  class Admin extends Controller{
    public function Index($params=null){
      $menu = MVC::renderView(
        "Admin/menu",
        array(
          "menu"=>array(
            "portals"=>"portal",
            "images"=>"image",
            "cassandra"=>"cassandra",
            "archives"=>"archive",
            "logs"=>"log",
            "analytics"=>"analytic"
          ),
          "selected"=>""
        )
      );
      $header = MVC::renderView("Admin/header",array("user"=>"admin"));

      $arr = array("title"=>"admin : inagist","header"=>$header,"menu"=>$menu,"content"=>"Welcome","template"=>"template");
      if(isset($params["notice"])) $arr["notice"]=$params["notice"];
      return $arr;
    }

    public function Login($params=null){
      $params["notice"] = "login successful";
      return $this->Index($params);      
    }

    public function Logout($params=null){
      $params["notice"] = "logout successful";
      return $this->Index($params);
    }
  }

?>
