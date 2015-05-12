<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "computers.php");

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
  $menu->add_item(new SimpleLink("Persons","./persons.php"));
  $menu->add_item(new SimpleLink("Computers","./computers.php"));
    $submenu = new Menu(FileName,"submenu");
    $submenu->add_item(new SimpleLink("seznam",FileName."?list"));
    if($_auth==Util::iSIT_AUTH_RW)$submenu->add_item(new SimpleLink("create",FileName."?create"));
    $submenu->add_item(new SimpleLink("|",""));
    $submenu->add_item(new SimpleLink("vyřazené",FileName."?vyrazene"));
  $menu->set_submenu($submenu);
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

  /* --- ADD_VYRAZOVAK --- */
  if(isset($_GET["add_vyrazovak"]))
  {    
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    if(!($computer = $rep->get_computer($_GET["add_vyrazovak"])))
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["add_vyrazovak"]." nelze načíst!", FileName);
      goto OUTPUT;
    }      

    $obsah_html .= Views::upload_pdf_file($computer,"vyrazovaky",FileName, "Upload PDF File Vyřazovák");
    goto OUTPUT;
  }  

  /* --- ADD_DODAK --- */
  if(isset($_GET["add_dodak"]))
  {    
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    if(!($computer = $rep->get_computer($_GET["add_dodak"])))
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["add_dodak"]." nelze načíst!", FileName);
      goto OUTPUT;
    }      

    $obsah_html .= Views::upload_pdf_file($computer,"dodaky",FileName, "Upload PDF File Dodák");
    goto OUTPUT;
  }  

  /* --- ADD_PDF --- */
  if(isset($_POST["add_pdf"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?detail");
    
    if(!($computer = $rep->get_computer($_POST["add_pdf"])))
    {
      $obsah_html .= Views::err("Záznam s id=".$_POST["add_pdf"]." nelze načíst!", FileName);
      goto OUTPUT;
    }      

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
          if(!$rep->add_pdf($computer,$_POST["folder"]))
          {
            $obsah_html .= Views::err("Upload PDF souboru se nezdařil!");
          }
        }
      }
      else
      {
        $obsah_html .= Views::err("Nebyl předán PDF soubor!");
      }      
    }
    $obsah_html .= Views::computer_detail($_rw, $computer);
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
        $obsah_html .= Views::computer_detail($_rw, $rep->get_computer($comment->get_device_id()));
      }
      else
      {
        $obsah_html .= Views::computer_detail($_rw, $rep->get_computer($comment->get_device_id()));
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
    if($computer = $rep->get_computer($_GET["add_comment"]))
    {
      $comment = new Comment();
      $comment->set_device_id($computer->get_id());
      $comment->set_device_folder($computer->get_folder());
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
      $obsah_html .= Views::computer_detail($_rw, $rep->get_computer($comment->get_device_id()));
    }        
    else
    {
      $obsah_html .= Views::err("Záznam nelze vytvořit.".$comment->get_all_err());
      $obsah_html .= Views::computer_detail($_rw, $rep->get_computer($comment->get_device_id()));
    }    
    goto OUTPUT;
  }

  /* --- REMOVE USER --- */
  if(isset($_GET["remove_user"]))
  {
  	if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
  	$menu->set_submenu_file_name(FileName."?detail");
  
  	if($computer_use = $rep->get_computer_use($_GET["remove_user"]))
  	{
  		if(!$rep->del_obj($computer_use))
  		{
  			$obsah_html .= Views::err("Nepodařilo se odstranit záznam.");
  			$obsah_html .= Views::computer_detail($_rw, $rep->get_computer($computer_use->get_computer_id()));
  		}
  		else
  		{
  			$obsah_html .= Views::computer_detail($_rw, $rep->get_computer($computer_use->get_computer_id()));
  		}
  	}
  	else
  	{
  		$obsah_html .= Views::err("Záznam s id=".$_GET["remove_user"]." nelze načíst!", FileName);
  	}
  	goto OUTPUT;
  }
  
  /* --- ADD USER --- */
  if(isset($_GET["add_user"]))
  {
  	if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
  	$menu->set_submenu_file_name(FileName."?detail");
  	if($computer = $rep->get_computer($_GET["add_user"]))
  	{
  		$persons = $rep->get_all_person("login");
  		$array = array();
  		$persons_by_login = array();
  		if (is_array($persons))
  		{
  			foreach($persons as $p)
  			{
  				$array[] = $p->get_login();
  			}
  			sort($array, SORT_LOCALE_STRING);
  
  			foreach($array as $login)
  			{
  				$persons_by_login[] = $persons[$login];
  			}
  		}
  		$obsah_html .= Views::add_computer_user($computer, $persons_by_login);
  	}
  	else
  	{
  		$obsah_html .= Views::err("Záznam s id=".$_GET["add_user"]." nelze načíst!", FileName);
  	}
  	goto OUTPUT;
  }
  if(isset($_POST["computer_use"]))
  {
  	if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
  	$menu->set_submenu_file_name(FileName."?detail");
  	$computer_use = new ComputerUse($_POST["computer_use"]);
  	$computer_use->set_id($rep->get_new_computer_use_id());
  
  	if($computer_use->is_valid())
  	{
  		if($first_load)
  		{
  			$rep->add_computer_use($computer_use);
  		}
  		$obsah_html .= Views::computer_detail($_rw, $rep->get_computer($computer_use->get_computer_id()));
  	}
  	else
  	{
  		$obsah_html .= Views::err("Záznam nelze vytvořit.".$computer_use->get_all_err());
  		$obsah_html .= Views::computer_detail($_rw, $rep->get_computer($computer_use->get_computer_id()));
  	}
  	goto OUTPUT;
  }
  
  
  /* --- DISABLE --- */
  if(isset($_GET["disable"]))
  {
  
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?list");
    if($computer = $rep->get_computer($_GET["disable"]))
    {
      $computer->set_aktivni(!$computer->get_aktivni());
      if($computer->is_valid())
      {
        $rep->save_computer($computer);
        $obsah_html .= Views::computer_list($rep->get_all_computer());
      }        
      else
      {
        $obsah_html .= Views::computer_edit($computer);
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
    if($computer = $rep->get_computer($_GET["edit"]))
    {
      $obsah_html .= Views::computer_edit($computer);
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
    $computer = new Computer($_POST["computer"]);
    
    if($computer->is_valid())
    {
      if($first_load) 
      {
        $rep->save_computer($computer);
      }      
      $obsah_html .= Views::computer_detail($_rw, $computer);
    }        
    else
    {
      $obsah_html .= Views::computer_edit($computer);
    }    
    goto OUTPUT;
  }
  
  /* --- CREATE --- */
  if(isset($_GET["create"]))
  {  
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_submenu_file_name(FileName."?create");
    $computer = new Computer();
    $computer->set_id($rep->get_new_computer_id());
    $obsah_html .= Views::computer_create($computer, false);
    goto OUTPUT;
  }
  if(isset($_POST["create"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_submenu_file_name(FileName."?create");
    $computer = new Computer($_POST["computer"]);
    
    if($computer->is_valid())
    {
      if($first_load)
      {
        if($_POST["create"]=="overview")
        {
          $obsah_html .= Views::computer_create($computer);
          goto OUTPUT;
        }
      
        if(!$rep->add_computer($computer))
        {
          $obsah_html .= Views::err("Nepodařilo se uložit záznam!");
          $obsah_html .= Views::computer_create($computer);
          goto OUTPUT;
        }      
      }                   
      $obsah_html .= Views::computer_detail($_rw, $computer);
    }        
    else
    {
      $obsah_html .= Views::computer_create($computer);
    }
    goto OUTPUT;    
  }
  
  /* --- DELETE --- */
  if(isset($_GET["delete"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_submenu_file_name(FileName."?delete");
    if($computer = $rep->get_computer($_GET["delete"]))
    {
      $obsah_html .= Views::delete($computer->get_id(),FileName);
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
    if($computer = $rep->get_computer($_POST["delete"]))
    {
      if(!$rep->del_obj($computer))
      {
        $obsah_html .= Views::err("Nepodařilo se odstranit záznam.");
        $obsah_html .= Views::computer_detail($_rw, $computer->get_id());
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
    if($computer = $rep->get_computer($_GET["detail"]))
    {
      $obsah_html .= Views::computer_detail($_rw, $computer);
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["detail"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }
     
	/* --- VYRAZENE --- */
  if(isset($_GET["vyrazene"]))
  {
    $menu->set_submenu_file_name(FileName."?vyrazene");
      
    $all = $rep->get_all_disabled_computer();
  
    $obsah_html .= "<h2>Vyřazené počítače</h2>";
    
    $obsah_html .= Views::computer_list($all, "AllComputers (".count($all).")");  
                
    goto OUTPUT;
  }  
  
  /* --- LIST --- */
  $menu->set_submenu_file_name(FileName."?list");
 
  $timer = new Timer();
  
  $all = $rep->get_all_computer();  
  $all_temp = array();
  $all_sorted = array();
  
  if(isset($_GET["sort"]))
  {
    if($_GET["sort"]=="model")
    {
      foreach($all as $obj)
      {
        if($obj->get_model()=="")
        {
          $all_temp["_no_value"][]=$obj;
        }
        else
        {
          $all_temp[$obj->get_model()][] = $obj;
        }
      }
    }
    if($_GET["sort"]=="datum_porizeni")
    {
      foreach($all as $obj)
      {
        if($obj->get_datum_porizeni()=="")
        {
          $all_temp["_no_value"][]=$obj;
        }
        else
        {
          $all_temp[Util::date_to_timestamp($obj->get_datum_porizeni())][] = $obj;
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

  $obsah_html .= "<h2>Aktivní počítače</h2>";
  
  $obsah_html .= Views::computer_list($all, "AllComputers (".count($all).")");  
  
  $timer->stop();
  //$timer->echo_time("computers.php?list: ");
    
  OUTPUT:

    if(Util::get_auth()!=Util::iSIT_AUTH_NO_LOGED)
    {
      $obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
    }

    echo $stranka->get_html($obsah_html, $menu->get_html());
    
   

?>