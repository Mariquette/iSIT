<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "locations.php");

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
  
    $menu = new Menu(FileName,"submenu");
    $menu->add_item(new SimpleLink("seznam",FileName."?list"));
    if($_auth==Util::iSIT_AUTH_RW)$menu->add_item(new SimpleLink("create",FileName."?create"));
    $menu->add_item(new SimpleLink("detail list",FileName."?detail_list"));
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

  /* --- DISABLE --- */
  if(isset($_GET["disable"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }
  
    $menu->set_file_name(FileName."?list");
    if($location = $rep->get_location($_GET["disable"]))
    {
      $location->set_aktivni(!$location->get_aktivni());
      if($location->is_valid())
      {
        $rep->save_location($location);
        $obsah_html .= Views::location_detail_list($_rw, $rep->get_every_location());
        
      }        
      else
      {
        $obsah_html .= Views::location_edit($location);
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

    $menu->set_file_name(FileName."?edit");
    if($location = $rep->get_location($_GET["edit"]))
    {
      $obsah_html .= Views::location_edit($location);
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

    $menu->set_file_name(FileName."?edit");
    $location = new Location($_POST["location"]);
    
    if($location->is_valid())
    {
      if($first_load) 
      {
        $rep->save_location($location);
      }      
      $obsah_html .= Views::location_detail($_rw, $location);
    }        
    else
    {
      $obsah_html .= Views::location_edit($location);
    }    
    goto OUTPUT;
  }
  
  /* --- CREATE --- */
  if(isset($_GET["create"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_file_name(FileName."?create");
    $location = new Location();
    $location->set_id($rep->get_new_location_id());
    $obsah_html .= Views::location_create($location, false);
    goto OUTPUT;
  }
  if(isset($_POST["create"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_file_name(FileName."?create");
    $location = new Location($_POST["location"]);
    
    if($location->is_valid())
    {
      if($first_load)
      {
        if(!$rep->add_location($location))
        {
          $obsah_html .= Views::err("Nepodařilo se uložit záznam!");
          $obsah_html .= Views::location_create($location);
          goto OUTPUT;
        }      
      }                   
      $obsah_html .= Views::location_detail($_rw, $location);
    }        
    else
    {
      $obsah_html .= Views::location_create($location);
    }
    goto OUTPUT;    
  }
  
  /* --- DELETE --- */
  if(isset($_GET["delete"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_file_name(FileName."?delete");
    if($location = $rep->get_location($_GET["delete"]))
    {
      $obsah_html .= Views::delete($location->get_id(),FileName);
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

    $menu->set_file_name(FileName."?delete");
    if(!$first_load)
    {
      $obsah_html .= Views::deleted(FileName);
      goto OUTPUT;
    }
    if($location = $rep->get_location($_POST["delete"]))
    {
      if(!$rep->del_obj($location))
      {
        $obsah_html .= Views::err("Nepodařilo se odstranit záznam.");
        $obsah_html .= Views::location_detail($_rw, $location->get_id());
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
    $menu->set_file_name(FileName."?list");
    //$stranka->set_hedears ...
    //$obsah_html .= rep get all evnets to csv;
    goto OUTPUT;      
  }  
      
  /* --- DETAIL --- */
  if(isset($_GET["detail"]))
  {
    $menu->set_file_name(FileName."?detail");
    if($location = $rep->get_location($_GET["detail"]))
    {
      $obsah_html .= Views::location_detail($_rw, $location);
    }
    else
    {
      $obsah_html .= Views::err("Záznam s id=".$_GET["detail"]." nelze načíst!", FileName);
    }
    goto OUTPUT;      
  }

  /* --- DETAIL-LIST --- */
  if(isset($_GET["detail_list"]))
  {
    $menu->set_file_name(FileName."?detail_list");
    $obsah_html .= Views::location_detail_list($_rw, $rep->get_every_location());
    goto OUTPUT;      
  }
  
  /* --- LIST --- */
  $menu->set_file_name(FileName."?list");
  $obsah_html .= Views::location_list($rep->get_all_location());
  
  
  /* --- //// --- */
  
  OUTPUT:

    if(Util::get_auth()!=Util::iSIT_AUTH_NO_LOGED)
    {
      $obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
    }

    echo $stranka->get_html($obsah_html, $menu->get_html());
   

?>
