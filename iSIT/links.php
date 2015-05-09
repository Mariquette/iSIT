<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "links.php");

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
    $submenu = new Menu(FileName,"submenu");
    $submenu->add_item(new SimpleLink("seznam",FileName."?list"));
    if($_auth==Util::iSIT_AUTH_RW)$submenu->add_item(new SimpleLink("create",FileName."?create"));
    $submenu->add_item(new SimpleLink("detail list",FileName."?detail_list"));
  $menu->set_submenu($submenu);
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
  
    $menu->set_submenu_file_name(FileName."?list");
    if($link = $rep->get_link($_GET["disable"]))
    {
      $link->set_aktivni(!$link->get_aktivni());
      if($link->is_valid())
      {
        $rep->save_link($link);
        $obsah_html .= Views::link_detail_list($_rw, $rep->get_every_link());
        
      }        
      else
      {
        $obsah_html .= Views::link_edit($link);
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
    if($link = $rep->get_link($_GET["edit"]))
    {
      $obsah_html .= Views::link_edit($link);
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
    $link = new Link($_POST["link"]);
    
    if($link->is_valid())
    {
      if($first_load) 
      {
        $rep->save_link($link);
      }      
      $obsah_html .= Views::link_detail($_rw, $link);
    }        
    else
    {
      $obsah_html .= Views::link_edit($link);
    }    
    goto OUTPUT;
  }
  
  /* --- CREATE --- */
  if(isset($_GET["create"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_submenu_file_name(FileName."?create");
    $link = new Link();
    $link->set_id($rep->get_new_link_id());
    $obsah_html .= Views::link_create($link, false);
    goto OUTPUT;
  }
  if(isset($_POST["create"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_submenu_file_name(FileName."?create");
    $link = new Link($_POST["link"]);
    
    if($link->is_valid())
    {
      if($first_load)
      {
        if(!$rep->add_link($link))
        {
          $obsah_html .= Views::err("Nepodařilo se uložit záznam!");
          $obsah_html .= Views::link_create($link);
          goto OUTPUT;
        }      
      }                   
      $obsah_html .= Views::link_detail($_rw, $link);
    }        
    else
    {
      $obsah_html .= Views::link_create($link);
    }
    goto OUTPUT;    
  }
  
  /* --- DELETE --- */
  if(isset($_GET["delete"]))
  {
    if($_auth!=Util::iSIT_AUTH_RW){ $obsah_html .= Views::auth_err_rw_only(); goto OUTPUT;  }

    $menu->set_submenu_file_name(FileName."?delete");
    if($link = $rep->get_link($_GET["delete"]))
    {
      $obsah_html .= Views::delete($link->get_id(),FileName);
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
    if($link = $rep->get_link($_POST["delete"]))
    {
      if(!$rep->del_obj($link))
      {
        $obsah_html .= Views::err("Nepodařilo se odstranit záznam.");
        $obsah_html .= Views::link_detail($_rw, $link->get_id());
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
    if($link = $rep->get_link($_GET["detail"]))
    {
      $obsah_html .= Views::link_detail($_rw, $link);
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
    $menu->set_submenu_file_name(FileName."?detail_list");
    $obsah_html .= Views::link_detail_list($_rw, $rep->get_every_link());
    goto OUTPUT;      
  }
  
  /* --- LIST --- */
  $menu->set_submenu_file_name(FileName."?list");
  $obsah_html .= Views::link_list($rep->get_all_link());
  
  
  /* --- //// --- */
  
  OUTPUT:

    if(Util::get_auth()!=Util::iSIT_AUTH_NO_LOGED)
    {
      $obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
    }

    echo $stranka->get_html($obsah_html, $menu->get_html());
   

?>