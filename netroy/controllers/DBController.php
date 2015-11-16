<?php
  abstract class DBController extends Controller{

    protected $table;
    protected $fields;
    
    public function Index($params=null){
      return $this->Listing($params);
    }

    public function Listing($params=null){
      // Get the DB object
      $db = new DB();

      // pagination stuff // TODO: fix this dude
      $s = 0;
      $c = 15;
      
      // SQL_CALC_FOUND_ROWS abandoned in favour of select count(id)
      $sql = "select ".$this->fields." from ".$this->table;
      $sql.= " limit $s,$c";
      $data = $db->query($sql);

      $sql = "select count(id) as c from".$this->table;
      $total = $db->query($sql);
      $total = $total[0]["c"];

      return array("results"=>$data,"total"=>$total);
    }

    public function Get($params=null){
      return array();
    }

    public function Update($params=null){
      return array();
    }

    public function Delete($params=null){
      return array();
    }
  }
?>
