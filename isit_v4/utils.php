<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "utils.php");

  function __autoload($class_name) 
  {
    if(!is_file(DirRoot."Classes/Class".$class_name.'.php')) die("Nelze načíst třídu $class_name! (".DirRoot."Classes/Class$class_name.php)");
    include DirRoot."Classes/Class".$class_name.'.php';
  }
  
  session_start();
  Util::filtruj_vstup();

  $rep = new Repository("./");  
  $stranka = new Sablona(FileName);

  $first_load = Util::check_token(Util::get_token()); // opakovane odeslani formulare?
  Util::create_token();

  /* --- MAIN --- */
  
  $obsah_html = "";
  $menu_html = "";
  
  $menu = new Menu(FileName);
  $menu->add_item(new SimpleLink("Home","./index.php"));
  $menu->add_item(new SimpleLink("Notes","./events.php"));
  $menu->add_item(new SimpleLink("Persons","./persons.php"));
  $menu->add_item(new SimpleLink("Computers","./computers.php"));
  $menu->add_item(new SimpleLink("Printers","./printers.php"));
  $menu->add_item(new SimpleLink("Links","./links.php"));    
  $menu->add_item(new SimpleLink("|",""));
  $menu->add_item(new SimpleLink("Utils","./utils.php"));
  $menu->add_item(new SimpleLink("|",""));  
  $menu->add_item(new SimpleLink("About","./about.php"));
  $menu_html.=$menu->get_html();

  // autentifikace
  $_auth = Util::get_auth();
  if(($_auth!=Util::iSIT_AUTH_RW)AND($_auth!=Util::iSIT_AUTH_R))
  {
    $obsah_html .= Views::auth_err();
    goto OUTPUT;      
  }



  /* --- MAJETEK NA VYRAZENI --- */
  if(isset($_GET["majetek_na_vyrazeni"]))
  {
    $all_majetek = $rep->get_ferona_miklab_zarizeni();
    $all_printers = $rep->get_all_printer();
    $all_computers = $rep->get_all_computer();
    $printers = array();
    $computers = array();
    
    foreach($all_printers as $printer)
    {
      if(($printer->is_vyrazovak()==true)AND(isset($all_majetek[$printer->get_evidencni_cislo()]))) $printers[$printer->get_evidencni_cislo()]=$printer;
    }
    
    foreach($all_computers as $computer)
    {
      if(($computer->is_vyrazovak()==true)AND(isset($all_majetek[$computer->get_evidencni_cislo()]))) $computers[$computer->get_evidencni_cislo()]=$computer;
    }
  
    $obsah_html .= Views::majetek_na_vyrazeni($printers, $computers);
    
    goto OUTPUT;      
  }

  /* --- VYRAZOVAK --- */
  if(isset($_GET["navrh_na_vyrazeni"]))
  {
    $ferona_miklab = $rep->get_ferona_miklab_zarizeni();
    if(isset($ferona_miklab[$_GET["navrh_na_vyrazeni"]]))
    {      
      $zarizeni=$ferona_miklab[$_GET["navrh_na_vyrazeni"]];
      if(isset($_GET["no_cena"])) $zarizeni->set_cena_porizeni("");
      
      $obsah_html = Views::vyrazovak($zarizeni, "Doubrav", date("d.m. Y"));
    }
    else
    {
      $obsah_html = Views::vyrazovak(new MiklabZarizeni(), "Doubrav", date("d.m. Y"));
    }
    echo $stranka->get_void_html($obsah_html,"styl_vyrazovak.css");
    goto END;      
  }

  /* --- MAJETEK INFO --- */
  if(isset($_GET["majetek_info"]))
  {
    $obsah_html .= Views::majetek_info_input_form();
    
    goto OUTPUT;      
  }
  if(isset($_POST["majetek_info"]))
  {

    $obsah_html .= Views::majetek_info_input_form();
    
    $obsah_html .= "<h2>Výsledky hledání pro: ".$_POST["majetek_info"]."</h2><hr>";
    $obsah_html .="<h3>Majetek Ferona, a.s.</h3>";

      $ferona_miklab = $rep->get_ferona_miklab_zarizeni();
      if(isset($ferona_miklab[$_POST["majetek_info"]]))
      {
        $obsah_html .= "<p>".$ferona_miklab[$_POST["majetek_info"]]->to_html_string("")."</p>";

        $miklab = $ferona_miklab[$_POST["majetek_info"]];
        if($miklab)
        {
          $obsah_html.= '<p><a target="_blank" class="hodnota" href="'.FileName.'?navrh_na_vyrazeni='.$_POST["majetek_info"].'">návrh na vyřazení</a> | <a target="_blank" class="hodnota" href="'.FileName.'?navrh_na_vyrazeni='.$_POST["majetek_info"].'&no_cena">návrh na vyřazení bez pořizovací ceny</a></p>';  
        }        
      }
      else
      {
        $obsah_html .="<p><b>not found</b></p>";
      }
      $obsah_html.="<hr>";
    
      $obsah_html .="<h3>iSIT Computers Active</h3>";    
      $isit_pc = $rep->get_all_computer("evidencni_cislo");    
      if(isset($isit_pc[$_POST["majetek_info"]]))
      {
        $computer = $isit_pc[$_POST["majetek_info"]];
        $obsah_html .= "<p>".$computer->to_html_string().'<a href="./computers.php?detail='.$computer->get_id().'">detail</a>'."</p>";
      }
      else
      {
        $obsah_html .="<p><b>not found</b></p>";
      }
      $obsah_html.="<hr>";
      
      $obsah_html .="<h3>iSIT Printers Active</h3>";
      $isit_print = $rep->get_all_printer("evidencni_cislo");
      if(isset($isit_print[$_POST["majetek_info"]]))
      {
        $printer = $isit_print[$_POST["majetek_info"]];
        $obsah_html .= "<p>".$printer->to_html_string().'<a href="./printers.php?detail='.$printer->get_id().'">detail</a>'."</p>";
      }
      else
      {
        $obsah_html .="<p><b>not found</b></p>";
      }
      $obsah_html.="<hr>";
    
      $obsah_html .="<h3>iSIT Computers Disabled</h3>";
      $isit_pc_dis = $rep->get_all_disabled_computer("evidencni_cislo");
      if(isset($isit_pc_dis[$_POST["majetek_info"]]))
      {
        $obsah_html .= "<p>".$isit_pc_dis[$_POST["majetek_info"]]->to_html_string()."</p>";
      }
      else
      {
        $obsah_html .="<p><b>not found</b></p>";
      }
      $obsah_html.="<hr>";

      $obsah_html .="<h3>iSIT Printers Disabled</h3>";
      $isit_print_dis = $rep->get_all_disabled_printer("evidencni_cislo");
      if(isset($isit_print_dis[$_POST["majetek_info"]]))
      {
        $obsah_html .= "<p>".$isit_print_dis[$_POST["majetek_info"]]->to_html_string()."</p>";
      }
      else
      {
        $obsah_html .="<p><b>not found</b></p>";
      }
      $obsah_html.="<hr>";
      
    goto OUTPUT;      
  }

  /* --- COMMENT LIST --- */
  if(isset($_GET["comment_list"]))
  {
  	/*
    $all_comments = $rep->get_all_comment();
    $comments_by_device_folder = array();
    $comments_by_device_folder_sorted = array();
    foreach($all_comments as $obj)
    {
      $comments_by_device_folder[$obj->get_device_folder()][$obj->get_device_id()][]=$obj;
    }
    
    foreach($comments_by_device_folder as $device_folder => $comments_by_device_id)
    {
      foreach($comments_by_device_id as $comments)
      {
        foreach($comments as $comment)
        {
          $comments_by_device_folder_sorted[$device_folder][] = $comment;
        }
      }
    }
    
    foreach($comments_by_device_folder_sorted as $device_folder => $comments)
    {
      $obsah_html .= Views::comment_list($comments,"Comments for $device_folder (".count($comments).")");
    }
    */
    $tables = $rep->get_isit_tables();    
    foreach($tables as $table)
    {
    	if($table->is_enabled_comments() == false) continue;
    	    	
			$objs = $rep->get_all_objects_with_comment_from_table($table);
			$obsah_html .= "<h2>".$table->get_name()."(".count($objs).")</h2><br>";
			
			foreach ($objs as $obj)
			{
				$obsah_html .= "<p>".'<a href="./'.$obj->get_db_name().'.php?detail='.$obj->get_id().'">'.$obj->get_info()."</a><br><ul>";
				foreach($obj->get_all_comments() as $poznamka)
				{
					$obsah_html .= "<li>".$poznamka->get_poznamka()."</li>";
				}
				$obsah_html .="</ul></p>";
			}
		}
    
	  goto OUTPUT;      
  }

  /* --- REQUIREMENT LIST --- */
  if(isset($_GET["requirement_list"]))
  {
    $all_requirements = $rep->get_all_requirement();
    $requirements_by_device_folder = array();
    foreach($all_requirements as $obj)
    {
      $requirements_by_device_folder[$obj->get_obj_folder()][]=$obj;
    }
    foreach($requirements_by_device_folder as $device_folder => $requirements)
    {
      $obsah_html .= Views::requirement_list($requirements,"Requirements for $device_folder (".count($requirements).")");
    }
    
    goto OUTPUT;      
  }

  /* --- IMPORT --- */
  if(isset($_GET["import"]))
  {
    $menu->set_submenu_file_name(FileName."?import");
    $type = "";
    $nadpis = "";
    if($_GET["import"]=="events")
    {
      $type = "events";
      $nadpis = "Import Events from CSV";
    }
    elseif($_GET["import"]=="persons")
    {
      $type = "persons";
      $nadpis = "Import Persons from CSV";
    }
    elseif($_GET["import"]=="computers")
    {
      $type = "computers";
      $nadpis = "Import Computers from CSV";
    }
    elseif($_GET["import"]=="printers")
    {
      $type = "printers";
      $nadpis = "Import Printers from CSV";
    }
    elseif($_GET["import"]=="links")
    {
      $type = "links";
      $nadpis = "Import Links from CSV";
    }
    elseif($_GET["import"]=="backup_schedules")
    {
      $type = "backup_schedules";
      $nadpis = "Import Backup Schedules form CSV";
    }
    elseif($_GET["import"]=="printer_uses")
    {
      $type = "printer_uses";
      $nadpis = "Import Printer Uses from CSV";
    }
    else
    {
      $obsah_html.=Views::err("Neznámá hodnota parametru \$_GET[\"import\"]=\"".$_GET["import"]."\".");
      goto OUTPUT;
    }
    
    $obsah_html .= Views::import($type,$nadpis);
    goto OUTPUT;      
  }  
  
  if(isset($_POST["import"]))
  {
    $menu->set_submenu_file_name(FileName."?import");
    
    $objekt = null;
    
    if($_POST["import"]=="events")
    {
      $objekt = new Event();
      $nadpis = "Imported Events from CSV";
    }
    elseif($_POST["import"]=="persons")
    {
      $objekt = new Person();
      $nadpis = "Imported Persons from CSV";
    }
    elseif($_POST["import"]=="computers")
    {
      $objekt = new Computer();
      $nadpis = "Imported Computers from CSV";
    }
    elseif($_POST["import"]=="printers")
    {
      $objekt = new Printer();
      $nadpis = "Imported Printers from CSV";
    }
    elseif($_POST["import"]=="links")
    {
      $objekt = new Link();
      $nadpis = "Imported Links from CSV";
    }
    elseif($_POST["import"]=="backup_schedules")
    {
      $objekt = new BackupSchedule();
      $nadpis = "Imported Backup Schedules form CSV";
    }
    elseif($_POST["import"]=="printer_uses")
    {
      $objekt = new PrinterUse();
      $nadpis = "Imported Printer Uses from CSV";
    }
    else
    {
      $obsah_html.=Views::err("Neznámá hodnota parametru \$_POST[\"import\"]=\"".$_POST["import"]."\".");
      goto OUTPUT;
    }

    if($first_load)
    {
      if(isset($_FILES["import_file"]))
      {
        if($_FILES["import_file"]["name"]=="")
        {
          $obsah_html .= Views::err("Nebyl vybrán žádný soubor!");          
        }
        elseif(!Test::is_file_suffix(strtolower($_FILES["import_file"]["name"]),"csv"))
        {
          $obsah_html .= Views::err("Lze zpracovat pouze soubory typu \".csv\"!");          
        }
        elseif(false)//$_FILES["import_file"]["type"]!="application/vnd.ms-excel")
        {
          $obsah_html .= Views::err("Nepodporovaný formát souboru! (\"".$_FILES["import_file"]["type"]."\")");          
        }
        else
        {
          if(($add_count=$rep->import_from_csv($objekt))>0)
          {
            $obsah_html .= Views::informace("Successfully added $add_count records.",$nadpis);        
          }
          else
          {
            $obsah_html .= Views::err("Import souboru se nezdařil!");
          }
        }
      }
      else
      {
        $obsah_html .= Views::err("Soubor pro import nebyl korektně předán!");
      }      
    }
    
    goto OUTPUT;
  }  
  
  /* --- EXPORT --- */
  if(isset($_GET["export"]))
  {
    $menu->set_submenu_file_name(FileName."?export");
    $type = "";
    $nadpis = "";
    $db_models = array();
    $db_models["computer"] = "Computer";
    $db_models["printer"] = "Printer";
    $db_models["person"] = "Person";
    $db_models["event"] = "Event";
    $db_models["link"] = "Link";
    $db_models["requirement"] = "Requirement";
    $db_models["comment"] = "Comment";
    $db_models["backup_schedule"] = "BackupSchedule";
    $db_models["printer_use"] = "PrinterUse";
    
    if($_GET["export"]=="db_models")
    {
      foreach($db_models as $db_model)
      {
        $obsah_html .= "<h2>$db_model:</h2>".$db_model::get_all_index_names("<br>")."<hr>";
      }
    }
    elseif($_GET["export"]=="db_models_records_sql")
    {
      foreach($db_models as $func => $db_model)
      {
        $index_names = strtolower($db_model::get_all_index_names(","));
        $index_names = str_replace(" ","_",$index_names);
        $index_names = str_replace(",",", ",$index_names);
        $index_names = str_replace("'","",$index_names);
        $obsah_html .= "<h2>$db_model"."s ($index_names):</h2>";
        $get_all = "get_every_$func";
        foreach($rep->$get_all() as $obj)
        {
          $prvky = $index_names;
          $values = $obj->to_array();
          $sql_values = "";
          foreach($values as $value)
          {
            $value = str_replace("<","&#60;", $value);
            $value = str_replace(">","&#62;", $value);
            $sql_values .= "'$value',";
          }
          $sql_values = substr($sql_values,0,-1);
          $obsah_html .= "INSERT INTO ".strtolower($db_model)."s ($prvky) VALUES (".$sql_values.");<br>";
        }
      }
    }
    else
    {
      $obsah_html.=Views::err("Neznámá hodnota parametru \$_GET[\"export\"]=\"".$_GET["export"]."\".");
      goto OUTPUT;
    }    
    
    goto OUTPUT;      
  }  
    
  
  
  /* --- NO PARAM --- */
  $obsah_html.='   
    <div class="utils">
      <h3>Utils</h3>
      <ul>
        <!-- <li>
          <h4>General</h4>
          <ul>
            <li><a href="./utils.php?majetek_info">Majetek Info</a></li>
            <li><a href="./utils.php?majetek_na_vyrazeni">Majetek na vyřazení</a></li>
          </ul>        
        </li> -->
        <li>
          <h4>Comment</h4>
          <ul>
            <li><a href="./utils.php?comment_list">Comment List</a></li>
          </ul>        
        </li>
        <li>
          <h4>Requirement</h4>
          <ul>
            <li><a href="./utils.php?requirement_list">Requirement List</a></li>
          </ul>        
        </li>
        <!-- <li>
          <h4>Import:</h4>
          <ul>
            <li><a href="./utils.php?import=events">Event</a></li>
            <li><a href="./utils.php?import=persons">Person</a></li>
            <li><a href="./utils.php?import=computers">Computer</a></li>
            <li><a href="./utils.php?import=backup_schedules">Computer Backup Schedule</a></li>
            <li><a href="./utils.php?import=printers">Printer</a></li>
            <li><a href="./utils.php?import=printer_uses">Printer Use</a></li>
            <li><a href="./utils.php?import=links">Link</a></li>
          </ul>        
        </li>
        <li>
          <h4>Exports</h4>
          <ul>
            <li><a href="./utils.php?export=db_models">DB Models</a></li>
            <li><a href="./utils.php?export=db_models_records">DB Models Records</a>(all)</li>
            <li><a href="./utils.php?export=db_models_records_sql">DB Models Records (SQL like)</a>(all)</li>
          </ul>        
        </li> -->
      </ul>    
    </div>';   
  
  /* --- //// --- */
  
  OUTPUT:

    if(Util::get_auth()!=Util::iSIT_AUTH_NO_LOGED)
    {
      $obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
    }

    echo $stranka->get_html($obsah_html, $menu->get_html());
    
  END:    
   

?>