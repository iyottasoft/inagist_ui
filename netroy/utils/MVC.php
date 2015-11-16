<?php

Class MVC{

  public static function renderView($view,$data=null,$write=false){
    global $display_mode;
    $viewbase = "views/";
    $viewfile = "$view.php";
    
    if($write && !file_exists($viewbase.$viewfile)) {
      header("Content-type: application/json");
      echo json_encode($data);
      return;
    }

    ob_start();
    ob_clean();
    if(is_array($data)) extract($data,EXTR_OVERWRITE);

    if($display_mode == "mobile" && file_exists($viewbase."mobile/$viewfile")) $viewfile = "mobile/$viewfile";
    include($viewbase.$viewfile);
    $out = ob_get_clean();
    if($write!=false){
    echo $out;
    }
    return $out;
  }

  private static $includes=null;    
  public static function importNS($NS="",$basepath=null){
    global $web_root;
    if($NS=="") return;
    //if($basepath==null) $basepath=$base["path"];
    
    if(self::$includes==null) self::$includes = array_unique(explode(PATH_SEPARATOR,get_include_path()));
    $path = $web_root.DIRECTORY_SEPARATOR.str_replace(".",DIRECTORY_SEPARATOR,$NS);
    self::$includes[] = $path;
    set_include_path(implode(PATH_SEPARATOR,array_unique(self::$includes)));
  }
}

?>
