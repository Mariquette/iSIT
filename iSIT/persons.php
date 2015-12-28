<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "persons.php");

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

  // autentifikace
  $_auth = Util::get_auth();
  $_rw = false;
  if($_auth==Util::iSIT_AUTH_RW) $_rw=true;

  /* --- MAIN --- */
  
  $obsah_html = "";
  $menu_html = "";
  
  $menu = new Menu(FileName);
  $menu->add_item(new SimpleLink("Home","./index.php"));
  $menu->add_item(new SimpleLink("Notes","./events.php"));
  $menu->add_item(new SimpleLink("Locations","./locations.php"));
  $menu->add_item(new SimpleLink("Persons","./persons.php"));
  $submenu = new Menu(FileName,"submenu");
  $submenu->add_item(new SimpleLink("seznam",FileName."?list"));
  if($_auth==Util::iSIT_AUTH_RW)$submenu->add_item(new SimpleLink("create",FileName."?create"));
  $menu->set_submenu($submenu);  
  $menu->add_item(new SimpleLink("Computers","./computers.php"));
  $menu->add_item(new SimpleLink("Printers","./printers.php"));
  $menu->add_item(new SimpleLink("Links","./links.php"));
  $menu->add_item(new SimpleLink("|",""));
  $menu->add_item(new SimpleLink("Utils","./utils.php"));
  $menu->add_item(new SimpleLink("|",""));  
  $menu->add_item(new SimpleLink("About","./about.php"));
  $menu_html.=$menu->get_html();  

  // autentifikace
  if(($_auth!=Util::iSIT_AUTH_RW)AND($_auth!=Util::iSIT_AUTH_R))
  {
    $obsah_html .= Views::auth_err();
    goto OUTPUT;      
  }

  /***********/
  /* RW ONLY */
  /***********/

  /* --- REMOVE REQUIREMENT --- */
  if(isset($_GET["remove_requirement"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?detail");
    
    if($requirement = $rep->get_requirement($_GET["remove_requirement"]))
    {
      if(!$rep->del_obj($requirement))
      {
        $obsah_html .= Views::err("Nepodařilo se odstranit záznam.");
        $obsah_html .= Views::person_detail($_rw, $rep->get_person($requirement->get_obj_id()));
      }
      else
      {
        $obsah_html .= Views::person_detail($_rw, $rep->get_person($requirement->get_obj_id()));
      }
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["remove_requirement"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }

  /* --- ADD RQUIREMENT --- */
  if(isset($_GET["add_requirement"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?detail");
    if($person = $rep->get_person($_GET["add_requirement"]))
    {
      $requirement = new Requirement();
      $requirement->set_obj_id($person->get_id());
      $requirement->set_obj_folder($person->get_folder());
      $obsah_html .= Views::requirement_create($requirement, FileName, false);
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["add_requirement"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }  
  if(isset($_POST["requirement"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?detail");
    $requirement = new Requirement($_POST["requirement"]);
    $requirement->set_id($rep->get_new_requirement_id());
    
    //echo $requirement->to_html_string();
    if($requirement->is_valid())
    {
      if($first_load)
      {
        if(isset($_FILES["upload_file"]))
        {
          if($_FILES["upload_file"]["name"]=="")
          {
            $obsah_html .= Views::err("Nebyl vybrán žádný soubor!");          
          }
          else
          {
            if(!$rep->add_pdf($requirement,$_POST["folder"]))
            {
              $obsah_html .= Views::err("Upload PDF souboru se nezdařil!");
            }
            else
            {
              $rep->add_requirement($requirement);
            }
          }
        }
        else
        {
          $obsah_html .= Views::err("Nebyl předán PDF soubor!");
        }      
      }
      $obsah_html .= Views::person_detail($_rw, $rep->get_person($requirement->get_obj_id()));
    }        
    else
    {
      $obsah_html .= Views::err("Záznam nelze vytvořit.".$requirement->get_all_err());
      $obsah_html .= Views::person_detail($_rw, $rep->get_person($requirement->get_obj_id()));
    }    
    goto OUTPUT;
  }

  /* --- REMOVE COMMENT --- */
  if(isset($_GET["remove_comment"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?detail");
    
    if($comment = $rep->get_comment($_GET["remove_comment"]))
    {
      if(!$rep->del_obj($comment))
      {
        $obsah_html .= Views::err("Nepodařilo se odstranit záznam.");
        $obsah_html .= Views::person_detail($_rw, $rep->get_person($comment->get_device_id()));
      }
      else
      {
        $obsah_html .= Views::person_detail($_rw, $rep->get_person($comment->get_device_id()));
      }
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["remove_comment"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }

  /* --- ADD COMMENT --- */
  if(isset($_GET["add_comment"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?detail");
    if($person = $rep->get_person($_GET["add_comment"]))
    {
      $comment = new Comment();
      $comment->set_device_id($person->get_id());
      $comment->set_device_folder($person->get_folder());
      $obsah_html .= Views::comment_create($comment, FileName, false);
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["add_comment"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }  
  if(isset($_POST["comment"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?detail");
    $comment = new Comment($_POST["comment"]);
    $comment->set_id($rep->get_new_comment_id());
    
    //echo $comment->to_html_string();
    if($comment->is_valid())
    {
      if($first_load) 
      {      
        $rep->add_comment($comment);
      }      
      $obsah_html .= Views::person_detail($_rw, $rep->get_person($comment->get_device_id()));
    }        
    else
    {
      $obsah_html .= Views::err("Záznam nelze vytvořit.".$comment->get_all_err());
      $obsah_html .= Views::person_detail($_rw, $rep->get_person($comment->get_device_id()));
    }    
    goto OUTPUT;
  }

  /* --- DISABLE --- */
  if(isset($_GET["disable"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?list");
    if($person = $rep->get_person($_GET["disable"]))
    {
      $person->set_aktivni(!$person->get_aktivni());
      if($person->is_valid())
      {
        $rep->save_person($person);
        $obsah_html .= Views::person_list($rep->get_all_person());
      }        
      else
      {
        $obsah_html .= Views::person_edit($person);
      }    
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["disable"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }  

  /* --- EDIT --- */
  if(isset($_GET["edit"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?edit");
    if($person = $rep->get_person($_GET["edit"]))
    {
      $obsah_html .= Views::person_edit($person);
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["edit"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }
  if(isset($_POST["edit"]))
  {                       
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?edit");
    $person = new Person($_POST["person"]);
    
    if($person->is_valid())
    {
      if($first_load) 
      {
        $rep->save_person($person);
      }      
      $obsah_html .= Views::person_detail($_rw, $person);
    }        
    else
    {
      $obsah_html .= Views::person_edit($person);
    }    
    goto OUTPUT;
  }
  
  /* --- CREATE --- */
  if(isset($_GET["create"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?create");
    $person = new Person();
    $person->set_id($rep->get_new_person_id());
    $obsah_html .= Views::person_create($person, false);
    goto OUTPUT;
  }
  if(isset($_POST["create"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?create");
    $person = new Person($_POST["person"]);
    
    if($person->is_valid())
    {
      if($first_load)
      {
        if($_POST["create"]=="overview")
        {
          $obsah_html .= Views::person_create($person);
          goto OUTPUT;
        }
      
        if(!$rep->add_person($person))
        {
          $obsah_html .= Views::err("Nepodařilo se uložit záznam!");
          $obsah_html .= Views::person_create($person);
          goto OUTPUT;
        }      
      }                   
      $obsah_html .= Views::person_detail($_rw, $person);
    }        
    else
    {
      $obsah_html .= Views::person_create($person);
    }
    goto OUTPUT;    
  }
  
  /* --- DELETE --- */
  if(isset($_GET["delete"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?delete");
    if($person = $rep->get_person($_GET["delete"]))
    {
      $obsah_html .= Views::delete($person->get_id(),FileName);
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["delete"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }
  if(isset($_POST["delete"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?delete");
    if(!$first_load)
    {
      $obsah_html .= Views::deleted(FileName);
      goto OUTPUT;
    }
    if($person = $rep->get_person($_POST["delete"]))
    {
      if(!$rep->del_obj($person))
      {
        $obsah_html .= Views::err("Nepodařilo se odstranit záznam.");
        $obsah_html .= Views::person_detail($_rw, $person->get_id());
      }
      else
      {
        $obsah_html .= Views::deleted(FileName);
      }
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_POST["delete"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }
    
  /******************/
  /* READ AND WRITE */
  /******************/
  
  /* --- EXPORT --- */
  if(isset($_GET["export"]))
  {
    $menu->set_submenu_file_name(FileName."?list");
    //$stranka->set_hedears ...
    //$obsah_html .= rep get all evnets to csv;
    goto OUTPUT;      
  }  
  
  /* --- DETAIL --- */
  if(isset($_GET["detail"]))
  {
    $menu->set_submenu_file_name(FileName."?detail");
    if($person = $rep->get_person($_GET["detail"]))
    {
      $obsah_html .= Views::person_detail($_rw, $person);
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["detail"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }
  
  /* --- LIST --- */
  $menu->set_submenu_file_name(FileName."?list");
  
  $timer = new Timer();
    
  $all = $rep->get_all_person();
  
  //===
  $all_temp = array();
  $all_sorted = array();
  
  if(isset($_GET["sort"]))
  {
  	if($_GET["sort"]=="location")
  	{
  		foreach($all as $obj)
  		{
  			if($obj->get_pobocka()=="")
  			{
  				$all_temp["_no_value"][]=$obj;
  			}
  			else
  			{
  				$all_temp[$obj->get_pobocka()][] = $obj;
  			}
  		}
  	}
  	
  	if($_GET["sort"]=="login")
  	{
  		foreach($all as $obj)
  		{
  			if($obj->get_login()=="")
  			{
  				$all_temp["_no_value"][]=$obj;
  			}
  			else
  			{
  				$all_temp[$obj->get_login()][] = $obj;
  			}
  		}
  	}
  	
  	if($_GET["sort"]=="osobni_cislo")
  	{
  		foreach($all as $obj)
  		{
  			if($obj->get_osobni_cislo()=="")
  			{
  				$all_temp["_no_value"][]=$obj;
  			}
  			else
  			{
  				$all_temp[$obj->get_osobni_cislo()][] = $obj;
  			}
  		}
  	}
  
  }
  
  ksort($all_temp);
  foreach($all_temp as $pole)
  {
  	foreach($pole as $obj)
  	{
  		$all_sorted[] = $obj;
  	}
  }
  
  if(count($all_sorted)>0)  $all = $all_sorted;
  
  //===
  
  $obsah_html .= Views::person_list($all, "AllPersons (".count($all).")");  

  $timer->stop();
  //$timer->echo_time("persons.php?list: ");
  
  OUTPUT:

    if(Util::get_auth()!=Util::iSIT_AUTH_NO_LOGED)
    {
      $obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
    }

    echo $stranka->get_html($obsah_html, $menu->get_html());
   

?>