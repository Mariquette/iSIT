<?php

/**
 *  Class Repository
 *  - pristup k databazim objektu
 */    


class Repository
{
  private static $rep;
  
  private static $imgDir;
  private static $pdfDir;
  private static $fileDir;
  private static $tmpDir;
  
  private $timer;
  private $show_timer;
  
  public function __construct($dir_root)
  {
    $this->timer = new Timer();
    $this->show_timer = false;
    if(self::$rep === NULL)
    {
      self::$rep = true;
          
      self::$imgDir = "img/";
      self::$pdfDir = $dir_root."__pdf/";
      self::$fileDir = "file/";
      self::$tmpDir = $dir_root."__tmp/";
    }
    $this->cid = $this->connect_isit_db_orig();
  }
  
  function __destruct() 
	{
  	//mysql_close($this->connect_isit_db());   
  }

// ==================================================================================================================================================
// ==================================================================================================================================================       

  /* ================== ISIT DB ================= */
  
    public function isit_get_one($sql, $class)
    {
    
      $conn = $this->connect_isit_db();
      if(!$conn) 
      {
        return false; //array(); //die( print_r(splsrv_errors(),true));
      }
  
      $result = mysql_query($sql, $conn);
      
      if(!$result)
      {
        echo "Repository->isit_get_all(): mysql_query($sql):false<br>";
        return false; //array();
      }
      
      $data = array();
      while($row = mysql_fetch_row($result))
      {
        $data[] = $row;
      }
      
      mysql_free_result($result);    
      // mysql_close($conn);   
      
      if (isset($data[0])) return new $class($data[0]);
      
      return false;
                
    }
    
    public function isit_get_all($sql, $class, $key=0)
    {
      $conn = $this->connect_isit_db();
      if(!$conn) 
      {
        return array(); //die( print_r(splsrv_errors(),true));
      }
  
      $result = mysql_query($sql, $conn);
      
      if(!$result)
      {
        echo "Repository->isit_get_all(): mysql_query($sql):false<br>";
        return array();
      }
      
      $data = array();
      while($row = mysql_fetch_row($result)) //mysql_fetch_assoc($result))
      {
        $data[$row[$key]] = new $class($row);
      }
      
      mysql_free_result($result);    
      // mysql_close($conn);   
      
      return $data;          
    }
  
    public function isit_query($sql)
    {
      $conn = $this->connect_isit_db();
      if(!$conn) 
      {
        return false; //array(); //die( print_r(splsrv_errors(),true));
      }
          
      $result = mysql_query($sql, $conn);
      if(!$result) echo "<br>$sql<br>";
      //$result = mysql_affected_rows();
          
      // mysql_close($conn);   
      
      return $result; //$result;  
    }
    
    public function isit_save($obj)
    {    

        $prvky = explode(";", str_replace("'", "", strtolower($obj::get_all_index_names(";"))));
        $values = $obj->to_array();
        
        $sql_values = "";
        foreach($values as $key => $value)
        {
          $sql_values .= $prvky[$key]." = '$value', ";
        }
        $sql_values = substr($sql_values,0,-2);
        
        $sql = "UPDATE ".$obj::get_db_name()." SET $sql_values WHERE id = ".$obj->get_id()." ";
        
        return $this->isit_query($sql);
    }
    
    public function isit_add($obj)
    {
        $prvky = str_replace("'", "", strtolower($obj::get_all_index_names(", ")));
        $values = $obj->to_array();
        $sql_values = "";
        foreach($values as $value)
        {
          $sql_values .= "'$value', ";
        }
        $sql_values = substr($sql_values,0,-2);
        $sql = "INSERT INTO ".$obj::get_db_name()." ($prvky) VALUES (".$sql_values.")";
        
        return $this->isit_query($sql);
    }
    
    public function isit_remove($obj)
    {
      $sql = "DELETE FROM ".$obj->get_db_name()." WHERE id = ".$obj->get_id()." ";
      return $this->isit_query($sql);
    }


		public function get_isit_tables()
		{
			$tables = array();
			$tables[] = new ISIT_Table("Comments", "comments", "Comment");
			$tables[] = new ISIT_Table("Computers","computers", "Computer", true);		
			$tables[] = new ISIT_Table("Events", "events", "Event");
			$tables[] = new ISIT_Table("Links", "links", "Link");
			$tables[] = new ISIT_Table("Persons", "persons","Person", true);
			$tables[] = new ISIT_Table("Printers", "printers", "Printer", true);
			$tables[] = new ISIT_Table("Printer Uses", "printer_uses", "PrinterUse");
			$tables[] = new ISIT_Table("Requierements", "requirements", "Requirement");
			
			return $tables;
		}

		public function get_all_objects_from_table($table)
		{
			$class = $table->get_obj_class();
    	$sql = "SELECT ".$class::get_attribs()." FROM ".$table->get_db_name()." WHERE aktivni = 1";      
    	return $this->isit_get_all($sql,$table->get_obj_class());			
		}

		public function get_all_objects_with_comment_from_table($table)
		{
			$class = $table->get_obj_class();
			//$sql = "select * from computers inner join comments on computers.id = comments.device_id where device_folder like \"_computers\"order by device_id"; 				 
    	$sql = "SELECT ".$class::attribs_to_string($table->get_db_name())." FROM ".$table->get_db_name()." INNER JOIN comments ON ".$table->get_db_name().".id = comments.device_id WHERE (device_folder LIKE \"_".$table->get_db_name()."\") AND (".$table->get_db_name().".aktivni = 1)";
    	return $this->isit_get_all($sql,$table->get_obj_class());			                                                                          
		}
    
  /* ============================================ */
  
  
// ***************
// **** LINKS ****
// ***************

  public function save_link($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_link($obj)
  {
    return $this->isit_add($obj);
  }  
    
  public function get_new_link_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_link())
    {
      foreach($pole as $item)
      {
        if($item->get_id()>$new_id)$new_id = $item->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_link()
  {
    $sql = "SELECT id, addr, popis, aktivni, _name FROM links";
         
    return $this->isit_get_all($sql,"Link");
  }

  public function get_all_link()
  {
    $sql = "SELECT id, addr, popis, aktivni, _name FROM links WHERE aktivni = 1";
         
    return $this->isit_get_all($sql,"Link");
  }

  
  public function get_link($id)
  {
    $sql = "SELECT id, addr, popis, aktivni, _name FROM links WHERE id = $id";
         
    return $this->isit_get_one($sql,"Link");
  }
  
// ****************
// **** EVENTS ****
// ****************

  public function save_event($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_event($obj)
  {
    return $this->isit_add($obj);
  }  
    
  public function get_new_event_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_event())
    {
      foreach($pole as $item)
      {
        if($item->get_id()>$new_id)$new_id = $item->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_event()
  {
    $sql = "SELECT id, zobrazit_od, zobrazit_do, text, aktivni, nadpis FROM events";
         
    return $this->isit_get_all($sql,"Event");
  }

  public function get_all_event()
  {
    $sql = "SELECT id, zobrazit_od, zobrazit_do, text, aktivni, nadpis FROM events WHERE aktivni = 1";
         
    return $this->isit_get_all($sql,"Event");
  }
  
  
  public function get_event($id)
  {
    $sql = "SELECT id, zobrazit_od, zobrazit_do, text, aktivni, nadpis FROM events WHERE id = $id";
         
    return $this->isit_get_one($sql,"Event");
  }

// *****************
// **** PERSONS ****
// *****************

  public function save_person($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_person($obj)
  {
    return $this->isit_add($obj);
  }  
  
  public function get_new_person_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_person())
    {
      foreach($pole as $person)
      {
        if($person->get_id()>$new_id)$new_id = $person->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_person($key = "login")
  {
    $sql = "SELECT id, full_name, osobni_cislo, aktivni, pobocka, login FROM persons";
        
    if($key == "login") return $this->isit_get_all($sql,"Person",Person::get_login_index());
    if($key == "id") return $this->isit_get_all($sql,"Person",Person::get_id_index());
    if($key == "osobni_cislo") return $this->isit_get_all($sql,"Person",Person::get_osobni_cislo_index());
    
    return $this->isit_get_all($sql,"Person"); 
  }

  public function get_all_person($key = "login")
  {
    $sql = "SELECT id, full_name, osobni_cislo, aktivni, pobocka, login FROM persons WHERE aktivni = 1";
        
    if($key == "login") return $this->isit_get_all($sql,"Person",Person::get_login_index());
    if($key == "id") return $this->isit_get_all($sql,"Person",Person::get_id_index());
    if($key == "osobni_cislo") return $this->isit_get_all($sql,"Person",Person::get_osobni_cislo_index());
    
    return $this->isit_get_all($sql,"Person"); 
  }

  public function get_all_disabled_person($key = "login")
  {
    $sql = "SELECT id, full_name, osobni_cislo, aktivni, pobocka, login FROM persons WHERE aktivni = 0";
        
    if($key == "login") return $this->isit_get_all($sql,"Person",Person::get_login_index());
    if($key == "id") return $this->isit_get_all($sql,"Person",Person::get_id_index());
    if($key == "osobni_cislo") return $this->isit_get_all($sql,"Person",Person::get_osobni_cislo_index());
    
    return $this->isit_get_all($sql,"Person"); 
  }

  public function get_person($id)
  {
    $sql = "SELECT id, full_name, osobni_cislo, aktivni, pobocka, login FROM persons WHERE id = $id";
            
    return $this->isit_get_one($sql,"Person"); 
  }

  public function get_person_by_osobni_cislo($osobni_cislo)
  {
    $sql = "SELECT id, full_name, osobni_cislo, aktivni, pobocka, login FROM persons WHERE osobni_cislo LIKE '$osobni_cislo'";
            
    return $this->isit_get_one($sql,"Person"); 
  }

  public function is_person_login($login)
  {
    $sql = "SELECT id, full_name, osobni_cislo, aktivni, pobocka, login FROM persons WHERE login LIKE '".strtolower($login)."'";
            
    if($this->isit_get_one($sql,"Person")) return true;
    
    return false;
  }
 
// *******************
// **** COMPUTERS ****
// *******************

  public function save_computer($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_computer($obj)
  {
    return $this->isit_add($obj);
  }  
  
  public function get_new_computer_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_computer())
    {
      foreach($pole as $computer)
      {
        if($computer->get_id()>$new_id)$new_id = $computer->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_computer($key = "seriove_cislo")
  {
  
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, pc_name, teamviewer, location FROM computers";
        
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Computer",Computer::get_seriove_cislo_index());
    if($key == "id") return $this->isit_get_all($sql,"Computer",Computer::get_id_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Computer",Computer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Computer");
  }
 
  public function get_all_computer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, pc_name, teamviewer, location FROM computers WHERE aktivni = 1";
        
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Computer",Computer::get_seriove_cislo_index());
    if($key == "id") return $this->isit_get_all($sql,"Computer",Computer::get_id_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Computer",Computer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Computer");
  }

  public function get_all_disabled_computer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, pc_name, teamviewer, location FROM computers WHERE aktivni = 0";
        
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Computer",Computer::get_seriove_cislo_index());
    if($key == "id") return $this->isit_get_all($sql,"Computer",Computer::get_id_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Computer",Computer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Computer");
  }
  
  public function get_computer($id)
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, pc_name, teamviewer, location FROM computers WHERE id = $id";
    
    return $this->isit_get_one($sql,"Computer");
   }

// *****************
// **** COMMENT ****
// *****************

  public function save_comment($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_comment($obj)
  {
    return $this->isit_add($obj);
  }  
    
  public function get_new_comment_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_comment())
    {
      foreach($pole as $item)
      {
        if($item->get_id()>$new_id)$new_id = $item->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_comment()
  {
    $sql = "SELECT id, device_id, device_folder, poznamka, aktivni FROM comments";
     
    return $this->isit_get_all($sql,"Comment"); 
  }

  public function get_all_comment()
  {
    $sql = "SELECT id, device_id, device_folder, poznamka, aktivni FROM comments WHERE aktivni = 1";
     
    return $this->isit_get_all($sql,"Comment"); 
  }
  
  public function get_all_comment_by_device($obj)
  {
    $sql = "SELECT id, device_id, device_folder, poznamka, aktivni FROM comments WHERE device_folder LIKE '".$obj->get_folder()."' AND device_id = ".$obj->get_id()."";
     
    return $this->isit_get_all($sql,"Comment"); 
  }
  
  public function get_comment($id)
  {
    $sql = "SELECT id, device_id, device_folder, poznamka, aktivni FROM comments WHERE id = $id";
     
    return $this->isit_get_one($sql,"Comment"); 
  }

// *********************
// **** REQUIREMENT ****
// *********************

  public function save_requirement($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_requirement($obj)
  {
    return $this->isit_add($obj);
  }  
    
  public function get_new_requirement_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_requirement())
    {
      foreach($pole as $item)
      {
        if($item->get_id()>$new_id)$new_id = $item->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_requirement()
  { 
    $sql = "SELECT id, obj_id, obj_folder, poznamka, aktivni FROM requirements";
            
    return $this->isit_get_all($sql,"Requirement"); 
  }

  public function get_all_requirement()
  {
    $sql = "SELECT id, obj_id, obj_folder, poznamka, aktivni FROM requirements WHERE aktivni = 1";
            
    return $this->isit_get_all($sql,"Requirement"); 
  }
  
  public function get_all_requirement_by_device($obj)
  {
    $sql = "SELECT id, obj_id, obj_folder, poznamka, aktivni FROM requirements WHERE obj_folder LIKE '".$obj->get_folder()."' AND obj_id = ".$obj->get_id()." ";
            
    return $this->isit_get_all($sql,"Requirement"); 
  }


  public function get_requirement($id)
  {
    $sql = "SELECT id, obj_id, obj_folder, poznamka, aktivni FROM requirements WHERE id = $id";
            
    return $this->isit_get_one($sql,"Requirement"); 
  }

// *********************
// **** PRINTER USE ****
// *********************

  public function save_printer_use($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_printer_use($obj)
  {
    return $this->isit_add($obj);
  }  
    
  public function get_new_printer_use_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_printer_use())
    {
      foreach($pole as $item)
      {
        if($item->get_id()>$new_id)$new_id = $item->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_printer_use()
  {
    $sql = "SELECT id, printer_id, person_id, poznamka FROM printer_uses";
            
    return $this->isit_get_all($sql,"PrinterUse"); 
  }

  public function get_all_printer_use()
  {
    $sql = "SELECT id, printer_id, person_id, poznamka FROM printer_uses WHERE aktivni = 1";
            
    return $this->isit_get_all($sql,"PrinterUse");  
  }
  
  public function get_all_printer_use_by_printer_id($printer_id)
  {
    $sql = "SELECT id, printer_id, person_id, poznamka FROM printer_uses WHERE printer_id = $printer_id";
            
    return $this->isit_get_all($sql,"PrinterUse"); 
  }

  public function get_printer_use($id)
  {
    $sql = "SELECT id, printer_id, person_id, poznamka FROM printer_uses WHERE id = $id";
            
    return $this->isit_get_one($sql,"PrinterUse");  
  }
  
// *******************
// **** PRINTERS ****
// *******************

  public function save_printer($obj)
  {
    return $this->isit_save($obj);
  }  
    
  public function add_printer($obj)
  {
    return $this->isit_add($obj);
  }  
  
  public function get_new_printer_id()
  {   
    $new_id=0; 
    if($pole = $this->get_every_printer())
    {
      foreach($pole as $printer)
      {
        if($printer->get_id()>$new_id)$new_id = $printer->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_printer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers";
        
    if($key == "id") return $this->isit_get_all($sql,"Printer",Printer::get_id_index());
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_seriove_cislo_index());
    if($key == "mac") return $this->isit_get_all($sql,"Printer",Printer::get_mac_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Printer");
  }

  public function get_all_printer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers WHERE aktivni = 1";
        
    if($key == "id") return $this->isit_get_all($sql,"Printer",Printer::get_id_index());
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_seriove_cislo_index());
    if($key == "mac") return $this->isit_get_all($sql,"Printer",Printer::get_mac_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Printer");
  }

  public function get_all_disabled_printer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers WHERE aktivni = 1";
        
    if($key == "id") return $this->isit_get_all($sql,"Printer",Printer::get_id_index());
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_seriove_cislo_index());
    if($key == "mac") return $this->isit_get_all($sql,"Printer",Printer::get_mac_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Printer");
  }
  
  
  public function get_all_hradec_printer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers WHERE evidencni_cislo LIKE '500%'";
        
    if($key == "id") return $this->isit_get_all($sql,"Printer",Printer::get_id_index());
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_seriove_cislo_index());
    if($key == "mac") return $this->isit_get_all($sql,"Printer",Printer::get_mac_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Printer");
  }

  public function get_all_ssc_printer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers WHERE evidencni_cislo LIKE '400%'";
        
    if($key == "id") return $this->isit_get_all($sql,"Printer",Printer::get_id_index());
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_seriove_cislo_index());
    if($key == "mac") return $this->isit_get_all($sql,"Printer",Printer::get_mac_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Printer");
  }

  public function get_all_liberec_printer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers WHERE evidencni_cislo LIKE '100%' OR evidencni_cislo LIKE '150%'";
        
    if($key == "id") return $this->isit_get_all($sql,"Printer",Printer::get_id_index());
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_seriove_cislo_index());
    if($key == "mac") return $this->isit_get_all($sql,"Printer",Printer::get_mac_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Printer");
  }

  public function get_all_nezarazene_printer($key = "seriove_cislo")
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers WHERE evidencni_cislo NOT LIKE '100%' AND evidencni_cislo NOT LIKE '150%' AND evidencni_cislo NOT LIKE '400%' AND evidencni_cislo NOT LIKE '500%'";
        
    if($key == "id") return $this->isit_get_all($sql,"Printer",Printer::get_id_index());
    if($key == "seriove_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_seriove_cislo_index());
    if($key == "mac") return $this->isit_get_all($sql,"Printer",Printer::get_mac_index());
    if($key == "evidencni_cislo") return $this->isit_get_all($sql,"Printer",Printer::get_evidencni_cislo_index());
    
    return $this->isit_get_all($sql,"Printer");
  }
  
  public function get_printer($id)
  {
    $sql = "SELECT id, model, seriove_cislo, aktivni, datum_porizeni, evidencni_cislo, ip, _mac FROM printers WHERE id = $id";
    return $this->isit_get_one($sql,"Printer");
  }


// ****************
// **** MIKLAB ****
// ****************

  public function get_all_miklab_person()
  {
    $this->timer->start();
    // klic: osobni_cislo
    $conn = $this->connect_miklab();
    if(!$conn) 
    {
      //echo "Repository->get_all_miklab_person(): connect_miklab(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $sql = "SELECT * from dbo.Zamestnanci ORDER BY STR_ORG";
    $stmt = sqlsrv_query($conn, $sql);
    
    if(!$stmt)
    {
      echo "Repository->get_all_miklab_person(): sqlsrv_query():false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $data = array();
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }
    sqlsrv_free_stmt($stmt);
    $miklab_persons = array();
    if(is_array($data))
    {
      foreach($data as $item)
      {
        // echo iconv("windows-1250", "utf-8//IGNORE", $item["DAT_NAR"]->format("d/m/Y"))." ".iconv("windows-1250", "utf-8//IGNORE", $item["PRIJMENI"]." ")." ".iconv("windows-1250", "utf-8//IGNORE", $item["JMENO"])."<br>"; 
        $miklab_person = new MiklabPerson();
        $miklab_person->set_osobni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["OSCIS"]));
        $miklab_person->set_prijmeni(iconv("windows-1250", "utf-8//TRANSLIT", $item["PRIJMENI"]));  
        $miklab_person->set_jmeno(iconv("windows-1250", "utf-8//IGNORE", $item["JMENO"]));
        $miklab_person->set_nazev_funkce(iconv("windows-1250", "utf-8//IGNORE", $item["NAZEV_FUNKCE"]));  
        $miklab_person->set_str_org(iconv("windows-1250", "utf-8//IGNORE", $item["STR_ORG"]));  
        $miklab_person->set_pobocka(iconv("windows-1250", "utf-8//IGNORE", $item["POBOCKA"]));  
        $miklab_person->set_lokalita(iconv("windows-1250", "utf-8//IGNORE", $item["LOKALITA"]));
        $miklab_persons[$miklab_person->get_osobni_cislo()] = $miklab_person;  
      }
    }
    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->get_all_miklab_person(): ");  
    return $miklab_persons;
  }

  public function get_miklab_person_by_osobni_cislo($osobni_cislo)
  {
    $this->timer->start();
    
    // klic: osobni_cislo
    $conn = $this->connect_miklab();
    if(!$conn) 
    {
      //echo "Repository->get_miklab_person_by_osobni_cislo(): connect_miklab(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $sql = "SELECT * from dbo.Zamestnanci WHERE OSCIS=$osobni_cislo";
    $stmt = sqlsrv_query($conn, $sql);
    if(!$stmt) 
    {
      echo "Repository->get_milab_person_by_osobni_cislo(): sqlsrv_query():false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $data = array();
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }
    sqlsrv_free_stmt($stmt);
    
    if(count($data)>1) die("Repository::get_miklab_person_by_osobni_cislo(\$osobni_cislo): Nalezeno vice zaznamu.");
    if(count($data)<1) return false;
    
    foreach($data as $item)
    {
      // echo iconv("windows-1250", "utf-8//IGNORE", $item["DAT_NAR"]->format("d/m/Y"))." ".iconv("windows-1250", "utf-8//IGNORE", $item["PRIJMENI"]." ")." ".iconv("windows-1250", "utf-8//IGNORE", $item["JMENO"])."<br>"; 
      $miklab_person = new MiklabPerson();
      $miklab_person->set_osobni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["OSCIS"]));
      $miklab_person->set_prijmeni(iconv("windows-1250", "utf-8//TRANSLIT", $item["PRIJMENI"]));  
      $miklab_person->set_jmeno(iconv("windows-1250", "utf-8//IGNORE", $item["JMENO"]));
      $miklab_person->set_nazev_funkce(iconv("windows-1250", "utf-8//IGNORE", $item["NAZEV_FUNKCE"]));  
      $miklab_person->set_str_org(iconv("windows-1250", "utf-8//IGNORE", $item["STR_ORG"]));  
      $miklab_person->set_pobocka(iconv("windows-1250", "utf-8//IGNORE", $item["POBOCKA"]));  
      $miklab_person->set_lokalita(iconv("windows-1250", "utf-8//IGNORE", $item["LOKALITA"]));
    }

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->get_miklab_person_by_osobni_cislo(): ");      
    return $miklab_person;
  }

  public function get_ferona_miklab_zarizeni()
  {
    $this->timer->start();
    
    // key: evidencni_cislo,
    $conn = $this->connect_miklab();
    if(!$conn) 
    {
      //echo "Repository->get_ferona_miklab_zarizeni(): connect_miklab(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
         
    $sql = "SELECT * from dbo.Majetek ORDER BY InvCis";    
    $stmt = sqlsrv_query($conn, $sql);    
    
    if(!$stmt) 
    {
      echo "Repository->get_all_miklab_zarizeni(): sqlsrv_query():false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $data = array();    
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }
    sqlsrv_free_stmt($stmt);
    $miklab = array();
    if(is_array($data))
    {
      foreach($data as $item)
      {
        $zarizeni = new MiklabZarizeni();
        $zarizeni->set_osobni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["OsCislo"]));
        $zarizeni->set_nazev(iconv("windows-1250", "utf-8//TRANSLIT", $item["Nazev"]));  
        
        $zarizeni->set_inventarni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["InvCis"]));
        //echo iconv("windows-1250", "utf-8//IGNORE", $item["InvCis"])."<br>";
        $zarizeni->set_porizeno(iconv("windows-1250", "utf-8//TRANSLIT", $item["Porizeno"]->format('d/m/Y')));  
        $zarizeni->set_cena_porizeni(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaPor"]));  
        $zarizeni->set_cena_zustatkova(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaZust"]));  
        $miklab[$zarizeni->get_inventarni_cislo()] = $zarizeni;  
      }
    }

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->get_ferona_miklab_zarizeni(): ");      
    return $miklab;
  }
    
  public function get_all_miklab_zarizeni($key="evidencni_cislo")
  {
    $this->timer->start();
    
    $t = new Timer();
    
    // key: evidencni_cislo,
    $conn = $this->connect_miklab();     
    if(!$conn) 
    {
      //echo "Repository->get_all_miklab_zarizeni(): connect_miklab(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    //$sql = "SELECT * from dbo.Majetek WHERE ((InvCis>=400000000) AND (InvCis<600000000)) OR ((InvCis>=150000000) AND (InvCis<160000000)) OR ((InvCis>=900000000) AND (InvCis<901000000)) ORDER BY Id";    
    $sql = "SELECT * from dbo.Majetek ORDER BY Id";
    
    $stmt = sqlsrv_query($conn, $sql);    
       
    if(!$stmt) 
    {
      echo "Repository->get_all_miklab_zarizeni(): sqlsrv_query():false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $data = array();    
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }
    sqlsrv_free_stmt($stmt);
    $miklab = array();
    if(is_array($data))
    {
      foreach($data as $item)
      {
        $zarizeni = new MiklabZarizeni();
        $zarizeni->set_osobni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["OsCislo"]));
        $zarizeni->set_nazev(iconv("windows-1250", "utf-8//TRANSLIT", $item["Nazev"]));  
        
        $zarizeni->set_inventarni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["InvCis"]));
        //echo iconv("windows-1250", "utf-8//IGNORE", $item["InvCis"])."<br>";
        $zarizeni->set_porizeno(iconv("windows-1250", "utf-8//TRANSLIT", $item["Porizeno"]->format('d/m/Y')));  
        $zarizeni->set_cena_porizeni(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaPor"]));  
        $zarizeni->set_cena_zustatkova(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaZust"]));  
        $miklab[$zarizeni->get_inventarni_cislo()] = $zarizeni;  
      }
    }
   
    if($key == "evidencni_cislo")
    {
      $this->timer->stop();
      if($this->show_timer)$this->timer->echo_time("Repository->get_all_miklab_zarizeni(evidencni_cislo): ");      
          
      return $miklab;
    }
    elseif($key == "osobni_cislo")
    {
      $majetek_by_osobni_cislo = array();
      foreach($miklab as $zarizeni)
      {
        $majetek_by_osobni_cislo[$zarizeni->get_osobni_cislo()][] = $zarizeni;
      }

      $this->timer->stop();
      if($this->show_timer)$this->timer->echo_time("Repository->get_all_miklab_zarizeni(osobni_cislo): ");      
      
      return $majetek_by_osobni_cislo;
    }
    else
    {
      $this->timer->stop();
      if($this->show_timer)$this->timer->echo_time("Repository->get_all_miklab_zarizeni(): ");      
    
      return $miklab;
    }
  }

  public function find_miklab_zarizeni_by_osobni_cislo($osobni_cislo)
  {
    // key: evidencni_cislo
    $this->timer->start();
    
    $conn = $this->connect_miklab();
    if(!$conn) 
    {
      //echo "Repository->find_miklab_zarizeni_by_osobni_cislo(): connect_miklab(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $sql = "SELECT * from dbo.Majetek WHERE OsCislo = $osobni_cislo ORDER BY Id";
    $stmt = sqlsrv_query($conn, $sql);
    
    if(!$stmt) 
    {
      echo "Repository->find_miklab_zarizeni_by_osobni_cislo(): sqlsrv_query():false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
        
    $data = array();    
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }    
    sqlsrv_free_stmt($stmt);
    $miklab = array();  
    foreach($data as $item)
    {    
      $zarizeni = new MiklabZarizeni();
      $zarizeni->set_osobni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["OsCislo"]));
      $zarizeni->set_nazev(iconv("windows-1250", "utf-8//TRANSLIT", $item["Nazev"]));  
      $zarizeni->set_inventarni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["InvCis"]));
      $zarizeni->set_porizeno(iconv("windows-1250", "utf-8//TRANSLIT", $item["Porizeno"]->format('d/m/Y')));  
      $zarizeni->set_cena_porizeni(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaPor"]));  
      $zarizeni->set_cena_zustatkova(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaZust"]));  
      $miklab[$zarizeni->get_inventarni_cislo()] = $zarizeni;  
    }

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->find_miklab_zarizeni_by_osobni_cislo(): ");      
    
    return $miklab;
  }

  public function get_miklab_zarizeni_by_evidencni_cislo($evidencni_cislo)
  {
    // key: evidencni_cislo
    $this->timer->start();
    
    $conn = $this->connect_miklab();
    if(!$conn) 
    {
      //echo "Repository->get_miklab_zarizeni_by_evidencni_cislo(): connect_miklab(): false<br>";
      return false;
    }

    $sql = "SELECT * from dbo.Majetek WHERE InvCis = $evidencni_cislo";
    $stmt = sqlsrv_query($conn, $sql);
    
    if(!$stmt) 
    {
      //echo "Repository->get_miklab_zarizeni_by_evidencni_cislo(): sqlsrv_query():false<br>";
      return false;
    }

    $data = array();    
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }    
    sqlsrv_free_stmt($stmt);
      
    foreach($data as $item)
    {    
      $zarizeni = new MiklabZarizeni();
      $zarizeni->set_osobni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["OsCislo"]));
      $zarizeni->set_nazev(iconv("windows-1250", "utf-8//TRANSLIT", $item["Nazev"]));  
      $zarizeni->set_inventarni_cislo(iconv("windows-1250", "utf-8//IGNORE", $item["InvCis"]));
      $zarizeni->set_porizeno(iconv("windows-1250", "utf-8//TRANSLIT", $item["Porizeno"]->format('d/m/Y')));  
      $zarizeni->set_cena_porizeni(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaPor"]));  
      $zarizeni->set_cena_zustatkova(iconv("windows-1250", "utf-8//TRANSLIT", $item["CenaZust"]));  

      $this->timer->stop();
      if($this->show_timer)$this->timer->echo_time("Repository->get_miklab_zarizeni_by_evidencni_cislo(): ");      
      
      return $zarizeni;  
    }
    return false;
  }
  


// *****************
// **** LANDESK ****
// *****************

  public function get_landesk_computer_by_name($name)
  {
    $this->timer->start();
    
    $landesk = $this->get_all_landesk_computer("name");
    if(isset($landesk[$name]))
    {
      $this->timer->stop();
      if($this->show_timer)$this->timer->echo_time("Repository->get_landesk_computer_by_name(): ");      
    
      return $landesk[$name];
    }
    return false;
  }
  private function get_landesk_software($key, $typ)
  {
    $this->timer->start();
    
    $conn = $this->connect_landesk();
    if(!$conn) 
    {
      //echo "Repository->get_landesk_software(): connect_landesk(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    // AppSoftwareSuites: Computer_Idn, AppSoftwareSuites_Idn, SuiteName, Version, Publisher, ProductID, RegCompany, RegOwner, InstallDate
    
    if($typ == "depricated")
    {
      $sql = "SELECT A.SuiteName, A.Version, A.AppSoftwareSuites_Idn, B.SerialNum FROM AppSoftwareSuites A JOIN dbo.CompSystem B ON A.Computer_Idn = B.Computer_Idn 
              
              WHERE A.SuiteName LIKE 'Microsoft Office XP Professional'   
              OR    A.SuiteName LIKE 'Microsoft Office XP Standard'
              
              OR    A.SuiteName LIKE 'Microsoft Office 2000 Premium'
              OR    A.SuiteName LIKE 'Microsoft Office 2000 SR-1 Premium'
              OR    A.SuiteName LIKE 'Microsoft Office 2000 SR-1 Standard'
              OR    A.SuiteName LIKE 'Microsoft Office 2000 Standard'
              
              OR    A.SuiteName LIKE 'OpenOffice.org 1.%'
              OR    A.SuiteName LIKE 'OpenOffice.org 2.%'
              OR    A.SuiteName LIKE 'OpenOffice.org 3.%'
              
              ";
    }
    elseif($typ == "ms_office")
    {
      $sql = "SELECT A.SuiteName, A.Version, A.AppSoftwareSuites_Idn, B.SerialNum FROM AppSoftwareSuites A JOIN dbo.CompSystem B ON A.Computer_Idn = B.Computer_Idn 
              
              WHERE A.SuiteName LIKE 'Microsoft Office Standard Edition 2003'   
              OR    A.SuiteName LIKE 'Microsoft Office Professional Edition 2003'
              
              OR    A.SuiteName LIKE 'Microsoft Office Standard 2007'
              OR    A.SuiteName LIKE 'Microsoft Office Small Business 2007'
              OR    A.SuiteName LIKE 'Microsoft Office Professional Plus 2007'
              
              OR    A.SuiteName LIKE 'Microsoft Office Professional Plus 2010'
              OR    A.SuiteName LIKE 'Microsoft Office 2010'
              
              ";
    }
    elseif($typ == "free_office")
    {
      $sql = "SELECT A.SuiteName, A.Version, A.AppSoftwareSuites_Idn, B.SerialNum FROM AppSoftwareSuites A JOIN dbo.CompSystem B ON A.Computer_Idn = B.Computer_Idn 
              WHERE (     A.SuiteName LIKE 'LibreOffice 3.%'   
              OR          A.SuiteName LIKE 'LibreOffice 4.%'  )
              
              AND   A.SuiteName NOT LIKE '%help pack%'
              ";
    }
    else 
    {
      // nespravny pozadavek
      echo "Repository->get_landesk_computer_by_name(): Nespravny pozadavek.<br>";
      return array();
    }
      
    $stmt = sqlsrv_query($conn, $sql);
    if(!$stmt) return array(); //die( "Repository::get_landesk_software(\$key, \$typ): DB Err");
    
    $data = array();
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }    
    sqlsrv_free_stmt($stmt);
    
    $software = array();    
    $computers = $this->get_all_computer("seriove_cislo");
    
    foreach($data as $value)
    {
      $sw = new LandeskSoftware();
      $sw->set_name($value["SuiteName"]);
      $sw->set_version($value["Version"]);
      $sw->set_software_id($value["AppSoftwareSuites_Idn"]);
      $sw->set_stag($value["SerialNum"]);
      
      if(!isset($computers[$sw->get_stag()])) continue;
    
      if($key=="stag") $software[$sw->get_stag()][] = $sw;
      if($key=="") $software[] = $sw;
    }   
    
    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->get_landesk_software(): ");      
     
    return $software;
  }

  public function get_all_landesk_depricated_office($key = "")
  {    
    return $this->get_landesk_software($key, "depricated");
  }

  public function get_all_landesk_free_office($key = "")
  {
    return $this->get_landesk_software($key, "free_office");
  }

  public function get_all_landesk_ms_office($key = "")
  {
    return $this->get_landesk_software($key, "ms_office");
  }

  public function get_all_landesk_computer($key="name")
  {
    $this->timer->start();
    
    // klic: name 
    $conn = $this->connect_landesk();
    if(!$conn) 
    {
      //echo "Repository->get_landesk_computer(): connect_landesk(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    $sql = "SELECT A.Computer_Idn, A.ChassisType, A.Model, A.SerialNum, A.AssetTag, B.DeviceName FROM dbo.CompSystem A JOIN dbo.Computer B ON A.Computer_Idn = B.Computer_Idn";

    $stmt = sqlsrv_query($conn, $sql);
    
    if(!$stmt) 
    {
      echo "Repository->get_all_landesk_computer(): sqlsrv_query():false<br>";
      return array(); //die( "Repository::get_all_landesk_computer(): DB Err");
    }
    
    $data = array();
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }
    sqlsrv_free_stmt($stmt);
    $computers = array();
    if(is_array($data))
    {
      foreach($data as $item)
      {
        //foreach($item as $klic=>$hodnota){echo "$klic=>$hodnota<br>";}echo "<hr>";
        $pc = new LandeskComputer();
        $pc->set_name($item["DeviceName"]);
        $pc->set_chassis($item["ChassisType"]);
        $pc->set_model($item["Model"]);
        $pc->set_stag($item["SerialNum"]);
        $pc->set_asset($item["AssetTag"]);
        
        if($key == "name")$computers[$pc->get_name()] = $pc;
        if($key == "stag")$computers[$pc->get_stag()] = $pc;
        // echo iconv("windows-1250", "utf-8//IGNORE", $item["DAT_NAR"]->format("d/m/Y"))." ".iconv("windows-1250", "utf-8//IGNORE", $item["PRIJMENI"]." ")." ".iconv("windows-1250", "utf-8//IGNORE", $item["JMENO"])."<br>"; 
      }
    }  

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->get_all_landesk_computer(): ");      
    
    return $computers;
  }

  public function get_landesk_computer_by_stag($stag)
  {
    $this->timer->start();
    // klic: name 
    $conn = $this->connect_landesk();
    if(!$conn) 
    {
      //echo "Repository->get_landesk_computer_by_stag(): connect_landesk(): false<br>";
      return array(); //die( print_r(splsrv_errors(),true));
    }
    
    $sql = "SELECT A.Computer_Idn, A.ChassisType, A.Model, A.SerialNum, A.AssetTag, B.DeviceName, B.DisplayName FROM dbo.CompSystem A JOIN dbo.Computer B ON A.Computer_Idn = B.Computer_Idn WHERE A.SerialNum LIKE '$stag'";

    $stmt = sqlsrv_query($conn, $sql);
    
    if(!$stmt)
    {
      echo "Repository->get_landesk_computer_by_stag(): sqlsrv_query():false<br>";
      return array(); //die( "Repository::get_all_landesk_computer(): DB Err");
    }
    
    $data = array();
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      $data[] = $row;
    }
    sqlsrv_free_stmt($stmt);

    if(is_array($data))
    {
      foreach($data as $item)
      {
        //foreach($item as $klic=>$hodnota){echo "$klic=>$hodnota<br>";}echo "<hr>";
        $pc = new LandeskComputer();
        $pc->set_name($item["DisplayName"]); //DisplayName, DeviceName
        $pc->set_chassis($item["ChassisType"]);
        $pc->set_model($item["Model"]);
        $pc->set_stag($item["SerialNum"]);
        $pc->set_asset($item["AssetTag"]);
        
        // echo iconv("windows-1250", "utf-8//IGNORE", $item["DAT_NAR"]->format("d/m/Y"))." ".iconv("windows-1250", "utf-8//IGNORE", $item["PRIJMENI"]." ")." ".iconv("windows-1250", "utf-8//IGNORE", $item["JMENO"])."<br>";
        
        $this->timer->stop();
        if($this->show_timer)$this->timer->echo_time("Repository->get_landesk_computer_by_stag(): ");      
        
        return $pc; 
      }
    }  
    return false;
  }

// *************/********
// **** WEBJET ADMIN ****
// **********************

  public function webjet_get_printer($soubor, $key)
  {
    //  \\vmhmc\HPWJAExport
    // key: mac, ip     
    $this->timer->start();   
    $radky = array();
    
    $dir = "\\\\vmhmc\\HPWJAExport";
    $link = opendir($dir);
    
    if(!$link)die("Repository::webjet_get_all_printer(): Nelze otevrit adresar \"$dir\".");
        
    $isoubor_date=0;
    $final_uri="";
    while($isoubor=readdir($link))
    {
      $uri=$dir."\\".$isoubor;
      if($isoubor_date>filemtime($uri)) continue;
      if((is_file($uri))AND(strtolower(ltrim(strstr($isoubor,"."),"."))=="csv")AND(strpos(strtolower($isoubor),strtolower($soubor))!==false))
      {
        $isoubor_date=filemtime($uri);
        $final_uri=$uri;
      }
    }
    
    //$uri=$dir."\\".$soubor;
    $final_uri;
    //echo "Repository::webjet_get_all_printer(): newest file: $final_uri<br>";
    if(!is_file($final_uri))die("Repository::webjet_get_all_printer(): Soubor \"$final_uri\" neexistuje.");
    
    if(!$soubor_link = fopen($final_uri,"r"))die("Repository::webjet_get_all_printer(): Soubor \"$final_uri\" nelze otevrit pro cteni.");
        
    $nacteno = fread($soubor_link,filesize($final_uri));
    fclose($soubor_link);
    
    $radky = explode("\n",$nacteno);
    
    //echo count($radky);
    
    $prvni = true;
    $pole = array();
    $printers = array();
    foreach($radky as $radek)
    { 
      if($radek=="") break;
      $pole = explode(",",str_replace("\"","",$radek));
      
      /*
      foreach($pole as $key => $value)
      {
        echo "[$key] = $value <br>";
      }
      */

      if(!$prvni)
      {
        $p = new WebjetPrinter();
        $p->set_ip($pole[2]);
        $p->set_name($pole[0]);
        $p->set_mac($pole[4]);
        $p->set_serial($pole[3]);
        if($key=="mac") $printers[$p->get_mac()] = $p;
        if($key=="ip") $printers[$p->get_ip()] = $p;
      }
      $prvni = false;
    }

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->webjet_get_printer(): ");      
                     
    return $printers; 
  }  

  public function webjet_get_all_hradec_printer($key="mac")
  {
    // key: mac, ip
    
    return $this->webjet_get_printer("webjet_hradec_en", $key);
  }

  public function webjet_get_all_liberec_printer($key="mac")
  {
    // key: mac, ip
    return $this->webjet_get_printer("webjet_liberec_en", $key);
  }

  public function webjet_get_all_printer()
  {
    $this->timer->start();
    // key: ip
    // jeden notebook na ruznych pobockach: stejna mac ruzna ip
    $hradec = $this->webjet_get_all_hradec_printer("ip");
    $liberec = $this->webjet_get_all_liberec_printer("ip");
    $all = array();
    foreach($hradec as $key => $value)
    {
      $all[$key] = $value;
    }    
    foreach($liberec as $key => $value)
    {
      $all[$key] = $value;
    }    
    
    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->webjet_get_all_printer(): ");      
    
    return $all;
  }


// **************
// **** BPCS ****
// **************

  public function bpcs_get_all_g_device()
  {         
    //  \\nashk\log\GRDEVD.TXT
    // key: ip
    $this->timer->start();
            
    $radky = array();
    $soubor = "GRDEVD.TXT";
    $dir = "\\\\nashk\\log";
    $link = opendir($dir);
    
    if(!$link)die("Repository::bpcs_get_all_g_device(): Nelze otevrit adresar \"$dir\".");
    
    $uri=$dir."\\".$soubor;
    
    if(!is_file($uri))die("Repository::bpcs_get_all_g_device(): Soubor \"$uri\" neexistuje.");
    
    if(!$soubor_link = fopen($uri,"r"))die("Repository::bpcs_get_all_g_device(): Soubor \"$uri\" nelze otevrit pro cteni.");
        
    $nacteno = fread($soubor_link,filesize($uri));
    fclose($soubor_link);
    
    $radky = explode("\n",$nacteno);
    
    //echo count($radky);
    
    $pole = array();
    $g_devices = array();
    foreach($radky as $radek)
    { 
      if($radek=="") break;
      $pole = explode(" ",str_replace("\"","",$radek));

      /*
      foreach($pole as $key => $value)
      {
        echo "[$key] = $value <br>";
      }
      */
      
      if(isset($pole[3]))
      {
        $g = new GDevice();
        $g->set_ip(trim($pole[3]));
        $g->set_name(trim($pole[0]));
        
        $g_devices[$g->get_name()] = $g;
        // $g_devices[$g->get_ip()] = $g;
        // echo "[".$g->get_ip()."]:".$g->get_name()."<br>";
        // $g_devices[$g->get_name()] = $g->get_ip();
      }
    }
    
    //echo "count:".count($g_devices)."<br>";    

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->bpcs_get_all_g_device(): ");      
                     
    return $g_devices; 
  }

  public function bpcs_get_all_g_device_old()
  {
    //  \\nashk\log\Gr_dev_HK_Li.csv
    // key: ip
    $this->timer->start();
            
    $radky = array();
    $soubor = "Gr_dev_HK_Li.csv";
    $dir = "\\\\nashk\\log";
    $link = opendir($dir);
    
    if(!$link)die("Repository::bpcs_get_all_g_device(): Nelze otevrit adresar \"$dir\".");
    
    $uri=$dir."\\".$soubor;
    
    if(!is_file($uri))die("Repository::bpcs_get_all_g_device(): Soubor \"$uri\" neexistuje.");
    
    if(!$soubor_link = fopen($uri,"r"))die("Repository::bpcs_get_all_g_device(): Soubor \"$uri\" nelze otevrit pro cteni.");
        
    $nacteno = fread($soubor_link,filesize($uri));
    fclose($soubor_link);
    
    $radky = explode("\n",$nacteno);
    
    //echo count($radky);
    
    $pole = array();
    $g_devices = array();
    foreach($radky as $radek)
    { 
      if($radek=="") break;
      $pole = explode(";",str_replace("\"","",$radek));
      /*
      foreach($pole as $key => $value)
      {
        echo "[$key] = $value <br>";
      }
      */
      $g = new GDevice();
      $g->set_ip(trim($pole[1]));
      $g->set_name(trim($pole[0]));
      
      $g_devices[$g->get_name()] = $g;
      // $g_devices[$g->get_ip()] = $g;
      // echo "[".$g->get_ip()."]:".$g->get_name()."<br>";
      // $g_devices[$g->get_name()] = $g->get_ip();
    }
    
    //echo "count:".count($g_devices)."<br>";    

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->bpcs_get_all_g_device(): ");      
                     
    return $g_devices; 
  }
  
  public function bpcs_get_g_device($ip)
  {
    $this->timer->start();
    
    $g_devices = $this->bpcs_get_all_g_device();
    $g_device = "";
    /*
    if(isset($g_devices[$ip]))
    {
      $g_device = $g_devices[$ip]->get_name();
    } 
    */
    foreach($g_devices as $g)
    {
      if($g->get_ip() == $ip)
      {
        $g_device = $g->get_name();
        break;
      }
    }

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->bpcs_get_g_device(): ");      

    return $g_device;
  }

// *****************
// **** SBACKUP ****
// *****************

  public function get_all_sbackup()
  {
    $all = array();
    
    $nasli = $this->get_nas_sbackup("nasli");
    foreach ($nasli as $key=>$folder)
    {
      $all[$key] = $folder;
    }
    
    $nashk = $this->get_nas_sbackup("nashk");
    foreach ($nashk as $key=>$folder)
    {
      $all[$key] = $folder;
    }
    
    return $all;
  }
  
  public function get_nas_sbackup($nas="nashk")
  {               
    $this->timer->start();
    
    // key: stag    
    // vstup jmeno nasky
    // vystup pole of Folders
      
    // nashk_sbackup_folders_size.csv
    
    $log_soubor = "\\\\nashk\\log\\".$nas."_sbackup_folders_size.csv";
     
    $dir = "\\\\$nas\\sbackup";
    $GB = 1024 * 1024 *1024;
  
    
    if(!is_file($log_soubor))die("Repository::get_all_sbackup(\$nas): Soubor \"$log_soubor\" neexistuje.");
    if(!$soubor_link = fopen($log_soubor,"r"))die("Repository::get_all_sbackup(\$nas): Soubor \"$log_soubor\" nelze otevrit pro cteni.");

    $nacteno = fread($soubor_link,filesize($log_soubor));
    fclose($soubor_link);    
    $radky = explode("\n",$nacteno);
    $pole = array();
    $folders = array();
    foreach($radky as $radek)
    { 
      $pole = explode(";",$radek);
      if(count($pole)<3) continue;
      $f = new Folder();
      $f->set_nazev($pole[0]);
      $f->set_pocet_souboru($pole[1]);
      $f->set_velikost(round($pole[2]/$GB,1));
      $f->set_posledni_zmena(date("d/m/Y", filemtime($dir."\\".$f->get_nazev())));      
      $folders[$f->get_nazev()] = $f;
      // echo $f->to_html_string(); 
    }

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->get_nas_sbackup(): ");      

    return $folders;
  }    
  
  public function get_sbackup($computer)
  {        
    // key: stag
    // vstup computer
    // vystup folder      
        
    if(Util::is_instance_of($computer,"Computer") == false) return "<h3 class=\"err\">Repository::get_sbackup(\$computer): promenna \$computer musi byt instanci tridy Computer!</h3>";

    $sbackup = $this->get_all_sbackup();
        
    if(isset($sbackup[$computer->get_seriove_cislo()]))
    {
      return $sbackup[$computer->get_seriove_cislo()]; 
    }

    return false;
  }

  public function get_all_sbackup_schedule()
  {
    $all = array();
    foreach($this->get_hradec_sbackup_schedule() as $schedule)
    {
      if(isset($all[$schedule->get_stag()])) die("Repository::get_all_sbackup_schedule(): duplicitni zaznam pro STag: ".$schedule->get_stag().".");
      $all[$schedule->get_stag()]=$schedule;      
    }
    
    return $all;
  }
  
  public function get_sbackup_schedule_by_stag($stag)
  {
    $schedules = $this->get_all_sbackup_schedule();
    if(isset($schedules[$stag])) return $schedules[$stag];
    
    return false;
  }
  
  public function get_hradec_sbackup_schedule()
  {
    //  \\nashk\log\sbackup_hradec_schedule.csv
    // key: stag
    
    $this->timer->start();
    
    $radky = array();
    $soubor = "sbackup_hradec_schedule.csv";
    $dir = "\\\\nashk\\log";
    $link = opendir($dir);
    
    if(!$link)die("Repository::get_hradec_sbackup_schedule(): Nelze otevrit adresar \"$dir\".");
    
    $uri=$dir."\\".$soubor;
    
    if(!is_file($uri))die("Repository::get_hradec_sbackup_schedule(): Soubor \"$uri\" neexistuje.");
    
    if(!$soubor_link = fopen($uri,"r"))die("Repository::get_hradec_sbackup_schedule(): Soubor \"$uri\" nelze otevrit pro cteni.");
        
    $nacteno = fread($soubor_link,filesize($uri));
    fclose($soubor_link);
    
    $radky = explode("\n",$nacteno);
    
    //echo count($radky);
    
    $pole = array();
    $schedule = array();
    foreach($radky as $radek)
    { 
      if($radek=="") break;
      $pole = explode(";",str_replace("\"","",$radek));

/*      
      foreach($pole as $key => $value)
      {
        echo "[$key] = $value <br>";
      }
*/      
      $s = new SBackupSchedule();
      
      $s->set_stag(trim($pole[0]));
      $s->set_day1(trim($pole[1]));
      $s->set_day2(trim($pole[2]));
      $s->set_hour(trim($pole[3]));
      $s->set_min(trim($pole[4]));
      $s->set_poznamka(trim($pole[5]));
      
      if(isset($schedule[$s->get_stag()])) die("Repository::get_hradec_sbackup_schedule(): duplicitni zaznam pro STag: ".$schedule->get_stag().".");      
      $schedule[$s->get_stag()] = $s;
    }
    
    //echo "count:".count($g_devices)."<br>";     
    
    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->get_hradec_sbackup_schedule(): ");      
                    
    return $schedule;     
  }


// **************
// **** DHCP ****
// **************
  
  public function dhcp_get_reservation($soubor = "", $key="mac")
  {                           
    // key: mac, ip
    // $soubor = "fewczhk5_dhcp_reservations.csv"   
    
    $this->timer->start();
       
    $radky = array();
    
    $dir = "\\\\nashk\\log";
    $link = opendir($dir);
    
    if(!$link)die("Repository::webjet_get_all_printer(): Nelze otevrit adresar \"$dir\".");
    
    $uri=$dir."\\".$soubor;
    
    if(!is_file($uri))die("Repository::dhcp_get_all_reservation(): Soubor \"$uri\" neexistuje.");
    
    if(!$soubor_link = fopen($uri,"r"))die("Repository::dhcp_get_all_reservation(): Soubor \"$uri\" nelze otevrit pro cteni.");
        
    $nacteno = fread($soubor_link,filesize($uri));
    fclose($soubor_link);
    
    $radky = explode("\n",$nacteno);
    
    //echo count($radky);
    
    $prvni = true;
    $pole = array();
    $net_devices = array();
    foreach($radky as $radek)
    { 
      if($radek=="") break;
      $pole = explode(";",str_replace("\"","",$radek));
      
      /*
      foreach($pole as $key => $value)
      {
        echo "[$key] = $value <br>";
      }
      */

      if(!$prvni)
      {
        $p = new DhcpReservation();
        $p->set_ip($pole[0]);
        $p->set_mac(trim(Util::str_to_lower($pole[1])));
        $p->set_name(trim(Util::str_to_lower($pole[2])));
        $p->set_description(trim(Util::str_to_lower($pole[3])));
        if($key=="mac") $net_devices[$p->get_mac()] = $p;
        if($key=="ip") $net_devices[$p->get_ip()] = $p;
      }
      $prvni = false;
    }

    $this->timer->stop();
    if($this->show_timer)$this->timer->echo_time("Repository->dhcp_get_reservation(): ");      
                     
    return $net_devices; 
  }

  public function dhcp_get_all_hradec_reservation($key="mac")
  {
    // key: mac, ip
    return $this->dhcp_get_reservation("fewczhk5_dhcp_reservations.csv", $key);
  }

  public function dhcp_get_all_liberec_reservation($key="mac")
  {
    // key: mac, ip
    return $this->dhcp_get_reservation("fewczli5_dhcp_reservations.csv", $key);
  }

  public function dhcp_get_all_uas_reservation($key="mac")
  {
    // key: mac, ip
    return $this->dhcp_get_reservation("fewczhav9_dhcp_reservations.csv", $key);
  }

  public function dhcp_get_all_reservation()
  {
    // key: ip
    // jeden notebook na ruznych pobockach: stejna mac ruzna ip
    $hradec = $this->dhcp_get_all_hradec_reservation("ip");
    $liberec = $this->dhcp_get_all_liberec_reservation("ip");
    $uas = $this->dhcp_get_all_uas_reservation("ip");
    $all = array();
    foreach($hradec as $key => $value)
    {
      $all[$key] = $value;
    }    
    foreach($liberec as $key => $value)
    {
      $all[$key] = $value;
    }    
    foreach($uas as $key => $value)
    {
      $all[$key] = $value;
    }    
    return $all;
  }

  /* ************************** */
  /* ******* NAS ************** */
  /* ************************** */
  
 
  public function get_all_nas()
  {
    $all_nas = array();   
    
    $nas0 = new Nas();    
    $nas0->set_id(0);
    $nas0->set_name("NASHK");
    $nas0->set_backup_folder("SBackup"); 
    
    $nas1 = new Nas();
    $nas1->set_id(1);
    $nas1->set_name("NASLI");
    $nas1->set_backup_folder("SBackup");
     
    $all_nas[] = $nas0;
    $all_nas[] = $nas1;
    
    return $all_nas;
  }
  
  public function get_nas($id)
  { 
    $all_nas = $this->get_all_nas();
    foreach($all_nas as $nas)
    {
      if($nas->get_id()==$id) return $nas;
    }
    
    return false;
  }
    
  /* ************************** */
  /* ******* DEVICES*********** */
  /* ************************** */

  public function is_device_folder($device_folder)
  {
    $folders = array();
    
    $folders[] = Computer::get_folder();
    $folders[] = Printer::get_folder();
    $folders[] = Person::get_folder();
    
    foreach($folders as $folder)
    {
      if($folder==$device_folder) return true;
    }    
    
    return false;
  }
  
  public function get_all_device_from_folder($device_folder)
  {
    if($this->is_device_folder()==false) return false;
    
    if($device_folder == Printer::get_folder())
    {
      return $this->get_all_printer("id");
    }
    
    if($device_folder == Computer::get_folder())
    {
      return $this->get_all_computer("id");
    }
    
    return false;
  }

  /* ************************** */
  /* ******* ISIT AUTH ******** */
  /* ************************** */

  public function get_isit_auth($user, $passw)
  {
    //if(($user=="isit_rw")AND($passw=="readwrite")) return Util::iSIT_AUTH_RW; // RW
    if(($user=="admin")AND($passw=="isit")) return Util::iSIT_AUTH_RW; // RW
    if(($user=="isit_rw")AND($passw=="rw.")) return Util::iSIT_AUTH_RW; // RW
    if(($user=="isit_r")AND($passw=="read")) return Util::iSIT_AUTH_R; // R
    if(($user=="gajda")AND($passw=="read")) return Util::iSIT_AUTH_R; // R
    if(($user=="ir")AND($passw=="albert")) return Util::iSIT_AUTH_RW; // RW
    return Util::iSIT_AUTH_NO_AUTH; // rejected
  }
  
  /* **************************** */
  /* ******* GET ALL SCN ******** */
  /* **************************** */

  public function get_all_scn_vrom($dir)
  { 
    //  \\\\ferona.cz\\update\\auditpro
    
    $err_source = "Repository::get_all_scn()";
    $all_scn = array();
    $i_dir = $dir; //
    
    if(!file_exists($i_dir)) die($err_source." neexistuje adresar \"$i_dir\", je nutne jej vytvorit!");
    if(!is_dir($i_dir)) die($err_source." \"$i_dir\" neni adresar! Nelze pokracovat.");

    $link=opendir($i_dir);
    if(!$link) die($err_source." adresar \"$i_dir\" nelze pripojit pro cteni.");
        
    while($soubor=readdir($link))
    {      
      $uri=$i_dir."\\".$soubor;
//      echo "test URI:$uri<br>";
      if((is_file($uri))AND(strtolower(ltrim(strstr($soubor,"."),"."))=="scn"))
      { 
        $all_scn[] = $soubor;
      }
    }          
    return $all_scn;
  }

  public function get_all_scn()
  { 
    $scn = $this->get_all_scn_vrom("\\\\ferona.cz\\update\\auditpro");    
    $scn2 = $this->get_all_scn_vrom("\\\\ferona.cz\\update\\auditpro\\SCN");
    return array_merge((array)$scn, (array)$scn2);
  }

  /* ----------------- */
  /* ---- GENERAL ---- */
  /* ----------------- */
  
  public function is_obj($folder_name, $id)
  {
    if($this->get_obj($folder_name, $id)) return true;
    return false;
  }

  public function get_obj($folder_name, $id)
  {
    if($folder_name == Event::get_folder())
    {
      return $this->get_event($id);
    } 
    if($folder_name == Person::get_folder())
    {
      return $this->get_person($id);
    } 
    if($folder_name == Computer::get_folder())
    {
      return $this->get_computer($id);
    } 
    if($folder_name == Printer::get_folder())
    {
      return $this->get_printer($id);
    } 
    if($folder_name == NetDevice::get_folder())
    {
      return $this->get_net_device($id);
    } 
    if($folder_name == PrinterUse::get_folder())
    {
      return $this->get_printer_use($id);
    } 
    if($folder_name == Link::get_folder())
    {
      return $this->get_link($id);
    } 
    if($folder_name == BackupSchedule::get_folder())
    {
      return $this->get_backup_schedule($id);
    } 
    if($folder_name == Comment::get_folder())
    {
      return $this->get_comment($id);
    } 
    if($folder_name == Requirement::get_folder())
    {
      return $this->get_requirement($id);
    } 

    die ("Repository::get_obj(\$folder_name, \$id): Adresar:$folder_name neni implementovan!");            
  }


  public function add_obj($obj)
  {
    return $this->isit_add($obj);
  }
    
  public function del_obj($obj)
  {
    return $this->isit_remove($obj);
  }
  
  public function import_from_csv($obj)
  {
    // prvni radek je ignorovan, ma obsahovat nazvy sloupcu

    if(is_uploaded_file($_FILES["import_file"]["tmp_name"])!=false)
    {
      $uri = $_FILES["import_file"]["tmp_name"];
    }
    else
    {
      // nepovedl se uplolad
      die("Repository::import_from_csv(\$obj): Import souboru ".$_FILES["import_file"]["tmp_name"]." se nezdaril!");
    }


    if(!is_file($uri))die("Repository::import_from_csv(\$obj): Soubor \$uri=\"$uri\" neexistuje.");
    if(!$soubor_link = fopen($uri,"r"))die("Repository::import_from_csv(\$obj): Soubor \$uri=\"$uri\" nelze otevrit pro cteni.");
    $nacteno = fread($soubor_link,filesize($uri));
    fclose($soubor_link);    
    $radky = explode("\n",$nacteno);
    $prvni = true;
    $pole = array();    
    $count = 0;
    foreach($radky as $radek)
    { 
      if($radek=="") break;
      $pole = explode(";",str_replace("\"","",$radek));
      
      /*
      foreach($pole as $key => $value)
      {
        echo "[$key] = $value <br>";
      }
      */

      if(!$prvni)
      {
        //echo "test, ";
        $obj->load($pole);
        $this->add_obj($obj);
        $count++;
        //echo $obj->to_html_string();        
      }
      else
      {
        $prvni = false;
      }
    }
    return $count;
  }  

  public function export_to_csv($array, $soubor="./export.csv")
  {
  
    $output="";
    $last_obj_class="";
    foreach($array as $obj)
    {
      if((Util::is_instance_of($obj,"Event"))   OR
        (Util::is_instance_of($obj,"Person"))  OR
        (Util::is_instance_of($obj,"Computer"))  OR
        (Util::is_instance_of($obj,"Printer")) OR
        (Util::is_instance_of($obj,"NetDevice")) OR
        (Util::is_instance_of($obj,"PrinterUse"))  OR
        (Util::is_instance_of($obj,"Link"))  OR
        (Util::is_instance_of($obj,"BackupSchedule")) OR
        (Util::is_instance_of($obj,"PrinterUse")))
      {
        $actual_obj_class=get_class($obj);
        if($actual_obj_class!=$last_obj_class)
        {
          $output .= $obj->get_all_index_names(";")."\n";
        }
        $output .=$obj->to_dat_string(";")."\n";
        $last_obj_class = $actual_obj_class;
      }
      else
      {
        die ("Repository::export_to_cvs(\$objekt): Neznama trida objektu \$objekt!");
      }
    }

    if(!File_Exists($soubor))
    { //echo "Otviram soubor $soubor pro zapis<br>";
      $soubor_link=fopen("$soubor","w");
      //echo "soubor otevren<br>";
      if(fwrite($soubor_link,$output))
      {// echo "Byl vytvořen soubor <b><a href=\"$soubor\">$soubor</b></a><br>";
        fclose($soubor_link);
      }
      else
      {
        fclose($soubor_link);
        die("ClassRepository::export_to_csv(\$array, \$soubor): Data nelze zapsat do souboru \"$soubor\".");
      }    
    }
    else
    {
      fclose($soubor_link);
      die("ClassRepository::export_to_csv(\$array, \$soubor): Soubor \"$soubor\" jiz existuje, nebyl prepsan.");
    }                        
  }
  
  public function add_pdf($objekt, $folder="")
  {
      
    $pdf_dir = self::$pdfDir.$folder."/";

    $name = "upload_file";
    if(!is_dir($pdf_dir))
    {
      die ("Repository::add_pdf(\$objekt,\$folder):adresar $pdf_dir neexistuje!");
    }
            
    if(Test::is_file_suffix(strtolower($_FILES["upload_file"]["name"]),"pdf"))
    {
      if(is_uploaded_file($_FILES[$name]["tmp_name"])!=false)
      {
        if(move_uploaded_file($_FILES[$name]["tmp_name"], $pdf_dir.$objekt->get_id()."_".get_class($objekt).".pdf") >= 1)
        { 
          return true;
        }
        else
        {
          // nepovedl se presun do tempu
          die ("Repository::add_pdf(\$objekt,\$folder): Presun ".$pdf_dir.$objekt->get_id().get_class($objekt).".pdf"." se nezdaril!");
        }
      }
      else
      {
        // nepovedl se uplolad
        die("Repository::add_pdf(\$objekt,\$folder): Upload souboru ".$_FILES[$name]["tmp_name"]." se nezdaril!");
      }
    }
    else
    {
      // neni soubor .pdf
      die("Repository::add_pdf(\$objekt,\$folder): Soubor ".$_FILES[$name]["tmp_name"]." nema koncovnku .pdf!");
    }
    return false;
  }    
 
  public function is_pdf($objekt, $folder="")
  {
    if((Util::is_instance_of($objekt,"Computer"))OR(Util::is_instance_of($objekt,"Printer"))OR(Util::is_instance_of($objekt,"Person"))OR(Util::is_instance_of($objekt,"Requirement")))
    {
    
    } 
    else
    {
      die ("Repository::is_pdf(\$objekt, \$folder): Neznama trida objektu \$objekt!");
    }
    
        
    $pdf_dir = self::$pdfDir.$folder."/";

    if(!is_dir($pdf_dir))
    {
      die ("Repository::is_pdf(\$objekt,\$folder):adresar \"$pdf_dir\" neexistuje!");
    }

    if(is_file($pdf_dir.$objekt->get_id()."_".get_class($objekt).".pdf"))
    {
      return true;
    }    
    return false;
  }
    
  /* --------------------- */
  /* ---- CONNECTIONS ---- */
  /* --------------------- */


	private function connect_isit_db()
	{
		return $this->cid;
	}
  
	private function connect_isit_db_orig()
  {
    $connectionId;  // identifikator spojeni s db
    $dbSrv = 'localhost';//':/webdev/mysql/mysqld.sock';
    $dbName = 'isit_v3-duhovka'; 
    $dbUserName = 'isit_rw';
    $dbPasswd = '';

    if($connectionId = mysql_connect($dbSrv, $dbUserName,$dbPasswd))
    {
      mysql_set_charset('utf8',$connectionId);
      $id_db = mysql_select_db($dbName); //připojí se k databázi dpimage_cz
      if (!$id_db)
      {
        die ("Repository::connect_isit_db(): Nepodarilo se pripojit databazi!");
      }
    }
    else
    {
      die ("Repository::connect_isit_db(): Nepodarilo se navazat spojeni se serverem!");
    }
      
    return $connectionId;    
  }
      
  private function connect_db()
  {
    $connectionId;  // identifikator spojeni s db
    $dbSrv = 'localhost';//':/webdev/mysql/mysqld.sock';
    $dbName = 'sprava_webu'; 
    $dbUserName = 'sprava_webu';
    $dbPasswd = 'heslo';

    if($connectionId = mysql_connect($dbSrv, $dbUserName,$dbPasswd))
    {
      $id_db = mysql_select_db($dbName); //připojí se k databázi dpimage_cz
      if (!$id_db)
      {
        die ("Repository::connect(): Nepodarilo se pripojit databazi!");
      }
    }
    else
    {
      die ("Repository::connect(): Nepodarilo se navazat spojeni se serverem!");
    }
      
    return $connectionId;    
  }

  private function close_db($connectionId)
  {
    //mysql_close($connectionId);
  }  

  private function connect_miklab()
  {
    $this->timer->start();
    
    $serverName = "festor1";  //" serverName\instanceName ";
    $connection = array("Database"=>"miklab", "LoginTimeout"=>5);  //array( "Database"=>"dbName");
    $conn = sqlsrv_connect( $serverName, $connection);
    
    if($conn) 
    {
      $this->timer->stop();
      if($this->show_timer)$this->timer->echo_time("Repository->connect_miklab(): ");      
      return $conn;
    }
    else
    {
      //die("Repository->connect_miklab(festor,miklab) ERR:".print_r(sqlsrv_errors(), true));
      echo "Repository->connect_miklab(festor,miklab): sqlsrv_connect():false; ERR:".print_r(sqlsrv_errors(), true)."<br>";
      return false;
    }  
  }

  private function connect_landesk()
  {
    $this->timer->start();
    
    $serverName = "festor1.ferona.cz";  //" serverName\instanceName ";
    //old// $connection = array("Database"=>"landesk9", "UID"=>"lookld", "PWD"=>"vidiv9", "LoginTimeout"=>5);  //array( "Database"=>"dbName");
    $connection = array("Database"=>"landesk95", "UID"=>"lookld", "PWD"=>"vidiv9", "LoginTimeout"=>2);  //array( "Database"=>"dbName");
    $conn = sqlsrv_connect( $serverName, $connection);
    
    if($conn) 
    {
      $this->timer->stop();
      if($this->show_timer)$this->timer->echo_time("Repository->connect_landesk(): ");      
      return $conn;
    }
    else
    {
      //die( "Repository->connect_landesk(festor1.ferona.cz,landesk9): ERR, ".print_r(sqlsrv_errors(), true));
      echo "Repository->connect_landesk(festor1.ferona.cz,landesk9): sqlsrv_connect():err, ".print_r(sqlsrv_errors(), true)."<br>";
      return false;
    }  
  }
  /* -------------- */
  /* ---- LDAP ---- */
  /* -------------- */

  public function ldap_query_ferona($filter)
  {    
    return RepositoryLdap::ldap_query_ferona($filter);
  } 

  public function get_all_ldap_computer()
  {
    return RepositoryLdap::get_all_ldap_computer($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
//    return array_merge( RepositoryLdap::get_all_ldap_computer($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(operatingSystemVersion=5*)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))")),
//                        RepositoryLdap::get_all_ldap_computer($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(!(operatingSystemVersion=5*))(!(userAccountControl:1.2.840.113556.1.4.803:=2)))")));
  }
  
  public function get_all_hradec_ldap_computer()
  {
    return RepositoryLdap::get_all_hradec_ldap_computer($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))")); //($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(description=Hradec*)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }
  
  public function get_all_liberec_ldap_computer()
  {
    return RepositoryLdap::get_all_liberec_ldap_computer($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))")); //($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(description=Liberec*)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }

  public function get_all_uas_ldap_computer()
  {
    return RepositoryLdap::get_all_uas_ldap_computer($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }

  public function get_all_millenium_ldap_computer()
  {
    return RepositoryLdap::get_all_millenium_ldap_computer($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }

  public function get_ldap_computer_by_name($name)
  {
    return RepositoryLdap::get_ldap_computer_by_name($this->ldap_query_ferona("(&(objectCategory=computer)(objectClass=user)(cn=$name)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"), $name);
  }

  public function get_all_ldap_person()
  {
    return RepositoryLdap::get_all_ldap_person($this->ldap_query_ferona("(&(objectCategory=person)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }

  public function get_all_hradec_ldap_person()
  {
    return RepositoryLdap::get_all_hradec_ldap_person($this->ldap_query_ferona("(&(objectCategory=person)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))")); //($this->ldap_query_ferona("(&(objectCategory=person)(objectClass=user)(description=Hradec*)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }

  public function get_all_liberec_ldap_person()
  {
    return RepositoryLdap::get_all_liberec_ldap_person($this->ldap_query_ferona("(&(objectCategory=person)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))")); //($this->ldap_query_ferona("(&(objectCategory=person)(objectClass=user)(description=Liberec*)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }

  public function get_all_uas_ldap_person()
  {
    return RepositoryLdap::get_all_uas_ldap_person($this->ldap_query_ferona("(&(objectCategory=person)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }
  
  public function get_all_millenium_ldap_person()
  {
    return RepositoryLdap::get_all_millenium_ldap_person($this->ldap_query_ferona("(&(objectCategory=person)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"));
  }

  public function set_ldap_person_osobni_cislo($person)
  {
    return RepositoryLdap::set_ldap_person_osobni_cislo($person);
  }  


  public function get_ldap_person_by_login($login)
  {
    return RepositoryLdap::get_ldap_person_by_login($login);
  }  

// *********************
// **** NET DEVICES ****
// *********************

  public function save_net_device($net_device)
  {
    if(Util::is_instance_of($net_device,"NetDevice") == false) die ("Repository::save_net_device(\$net_device): promenna \$net_device musi byt instanci tridy NetDevice!");
    if(!$this->get_net_device($net_device->get_id())) die ("Repository::save_net_device(\$net_device): NetDevice s id=".$net_device->get_id()." neexistuje!");
    $fileName = $net_device->get_id().".dat";        
    if(self::$net_devices->saveItem($fileName,$net_device->to_dat_string())==false) die ("Repository::save_net_device(\$net_device): Nepodarilo se ulozit soubor $fileName s obsahem \"".$net_device->to_dat_string()."\"!");
    
    return true;
  }  
    
  public function add_net_device($net_device)
  {
    if(Util::is_instance_of($net_device,"NetDevice") == false) die ("Repository::add_net_device(\$net_device): promenna \$net_device musi byt instanci tridy NetDevice!");
    if($this->get_net_device($net_device->get_id())) die ("Repository::add_net_device(\$net_device): NetDevice s id=".$net_device->get_id()." jiz existuje!");    
    $fileName = $net_device->get_id().".dat";
    if(self::$net_devices->createItem($fileName, $net_device->to_dat_string())==false) die ("Repository::add_net_device(\$net_device): Nepodarilo se vytvorit soubor $fileName s obsahem \"".$net_device->to_dat_string()."\"!");
    
    return true;
  }  
  
  public function get_new_net_device_id()
  {   
    $new_id=0; 
    if($pole = $this->get_all_net_device())
    {
      foreach($pole as $net_device)
      {
        if($net_device->get_id()>$new_id)$new_id = $net_device->get_id();
      }
      return $new_id + 1;  
    }
    return $new_id;
  }
  
  public function get_every_net_device($key = "seriove_cislo")
  {
    // key: seriove_cislo
    $net_devices = array();
    $pole = self::$net_devices->select(NetDevice::get_id_index(),"!=","");
    if(is_array($pole))
    {
      foreach($pole as $item)
      {
        $pc = new NetDevice($item);
        if($key == "seriove_cislo") $net_devices[$pc->get_seriove_cislo()] = $pc;
        if($key == "evidencni_cislo") 
        {
          if($pc->get_evidencni_cislo() != "vyrazen") $net_devices[$pc->get_evidencni_cislo()] = $pc;
        }
      }
      return $net_devices;
    }
    return array();
  }

  public function get_all_net_device($key = "seriove_cislo")
  {
    // key: seriove_cislo
    $net_devices = array();
    $pole = self::$net_devices->select(NetDevice::get_id_index(),"!=","");
    if(is_array($pole))
    {
      foreach($pole as $item)
      {
        $pc = new NetDevice($item);
        if($key == "seriove_cislo") $net_devices[$pc->get_seriove_cislo()] = $pc;
        if($key == "evidencni_cislo") 
        {
          if($pc->get_evidencni_cislo() != "vyrazen") $net_devices[$pc->get_evidencni_cislo()] = $pc;
        }
      }
      return $net_devices;
    }
    return array();
  }
 
  public function get_all_hradec_net_device($key = "seriove_cislo")
  {
    // key: seriove_cislo
    $net_devices = array();
    $pole = self::$net_devices->select(NetDevice::get_id_index(),"!=","");
    if(is_array($pole))
    {
      foreach($pole as $item)
      {
        $pc = new NetDevice($item);
        $pobocka = (int)($pc->get_evidencni_cislo()/1000000);
        //echo "ev_num: (int)(".$pc->get_evidencni_cislo()."/1000000) = ".$pobocka."<br>";
        if($pobocka != 500) continue; 
                
        if($key == "seriove_cislo") 
        {
          $net_devices[$pc->get_seriove_cislo()] = $pc;
        }
        
        if($key == "evidencni_cislo") 
        {
          if($pc->get_evidencni_cislo() != "vyrazen") $net_devices[$pc->get_evidencni_cislo()] = $pc;
        }
      }
      return $net_devices;
    }
    return array();
  }

  public function get_all_ssc_net_device($key = "seriove_cislo")
  {
    // key: seriove_cislo
    $net_devices = array();
    $pole = self::$net_devices->select(NetDevice::get_id_index(),"!=","");
    if(is_array($pole))
    {
      foreach($pole as $item)
      {
        $pc = new NetDevice($item);
        $pobocka = (int)($pc->get_evidencni_cislo()/1000000);
        //echo "ev_num: (int)(".$pc->get_evidencni_cislo()."/1000000) = ".$pobocka."<br>";
        if($pobocka != 400) continue; 
                
        if($key == "seriove_cislo") 
        {
          $net_devices[$pc->get_seriove_cislo()] = $pc;
        }
        
        if($key == "evidencni_cislo") 
        {
          if($pc->get_evidencni_cislo() != "vyrazen") $net_devices[$pc->get_evidencni_cislo()] = $pc;
        }
      }
      return $net_devices;
    }
    return array();
  }

  public function get_all_liberec_net_device($key = "seriove_cislo")
  {
    // key: seriove_cislo
    $net_devices = array();
    $pole = self::$net_devices->select(NetDevice::get_id_index(),"!=","");
    if(is_array($pole))
    {
      foreach($pole as $item)
      {
        $pc = new NetDevice($item);
        $pobocka = (int)($pc->get_evidencni_cislo()/1000000);
        //echo "ev_num: (int)(".$pc->get_evidencni_cislo()."/1000000) = ".$pobocka."<br>";
        if($pobocka != 150) continue; 
                
        if($key == "seriove_cislo") 
        {
          $net_devices[$pc->get_seriove_cislo()] = $pc;
        }
        
        if($key == "evidencni_cislo") 
        {
          if($pc->get_evidencni_cislo() != "vyrazen") $net_devices[$pc->get_evidencni_cislo()] = $pc;
        }
      }
      return $net_devices;
    }
    return array();
  }

  public function get_all_nezarazene_net_device($key = "seriove_cislo")
  {
    // key: seriove_cislo
    $net_devices = array();
    $pole = self::$net_devices->select(NetDevice::get_id_index(),"!=","");
    if(is_array($pole))
    {
      foreach($pole as $item)
      {
        $pc = new NetDevice($item);
        $pobocka = (int)($pc->get_evidencni_cislo()/1000000);
        //echo "ev_num: (int)(".$pc->get_evidencni_cislo()."/1000000) = ".$pobocka."<br>";
        if($pobocka == 500) continue; 
        if($pobocka == 400) continue;
        if($pobocka == 150) continue;
                
        if($key == "seriove_cislo") 
        {
          $net_devices[$pc->get_seriove_cislo()] = $pc;
        }
        
        if($key == "evidencni_cislo") 
        {
          if($pc->get_evidencni_cislo() != "vyrazen") $net_devices[$pc->get_evidencni_cislo()] = $pc;
        }
      }
      return $net_devices;
    }
    return array();
  }
  
  public function get_net_device($id)
  {
    if($item = self::$net_devices->get_item($id))
    {
      return new NetDevice($item);
    }
    return false;
  }
  
} // end Class
?>
