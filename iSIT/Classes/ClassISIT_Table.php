<?php

/* trida pro popis tablulek v databazi isitu
	zatim se pouzije pro hledani tabulek s moznosti pridat poznamku
*/

class ISIT_Table
{
  
  public function __construct($name, $db_name, $obj_class, $comments = false)
  {
    $this->set_name($name);
    $this->set_db_name($db_name);
    $this->set_obj_class($obj_class);
    $this->set_comments($comments);
    
  }

  public function set_name($name)
  {
    if($name == "") die("ISIT_Table->set_name(\$name): Parametr \$name nesmi byt prazdny!");
    $this->name = $name;
  }
  public function set_db_name($name)
  {
    if($name == "") die("ISIT_Table->set_name(\$db_name): Parametr \$db_name nesmi byt prazdny!");
    $this->db_name = $name;
  }
  public function set_obj_class($obj_class)
  {
    if($obj_class == "") die("ISIT_Table->set_obj_class(\$obj_class): Parametr \$obj_class nesmi byt prazdny!");
    $this->obj_class = $obj_class;
  }

  public function set_comments($comments)
  {
    $this->comments = $comments;
  }
  
  public function get_name()
  {
    return $this->name;
  }
  public function get_db_name()
  {
    return $this->db_name;
  }
  public function get_obj_class()
  {
    return $this->obj_class;
  }
  
  public function is_enabled_comments()
  {
    if($this->comments == false) return false;
    return true;
  }
    
} // end Class

?>
