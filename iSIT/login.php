<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "login.php");

  function __autoload($class_name) 
  {
    if(!is_file(DirRoot."Classes/Class".$class_name.'.php')) die("Nelze načíst třídu $class_name! (".DirRoot."Classes/Class$class_name.php)");
    include DirRoot."Classes/Class".$class_name.'.php';
  }
  
  session_start();
  Util::filtruj_vstup();

  $rep = new Repository("./");  
  $stranka = new Sablona(FileName);

  // autentifikace 0=RW, 1=R, 2=reject
  $_auth = Util::get_auth();

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
  
  /* --- //// --- */

  /* --- LOGOUT --- */
  if(isset($_GET["logout"]))
  {
    $_auth = Util::logout();
  }  

  /* --- LOGIN --- */
  if(isset($_POST["log_in"])AND(isset($_POST["heslo"])))
  {
    $_auth = Util::login($_POST["log_in"],$_POST["heslo"]);

    if($_auth == Util::iSIT_AUTH_NO_AUTH)
    {    
      $obsah_html.=Views::login_form($_POST["log_in"],1);    
      goto OUTPUT;      
    }   
  }  
  
  if(($_auth == Util::iSIT_AUTH_NO_LOGED)AND(!isset($_GET["logout"])))
  {    
    $obsah_html.=Views::login_form("");    
    goto OUTPUT;      
  }
  
  if(($_auth == Util::iSIT_AUTH_NO_LOGED)AND(isset($_GET["logout"])))
  {
  	$obsah_html.='<div class="informace">Successfully loged out,<br><br> by :)</div>';
  	goto OUTPUT;
  }
  
  if((($_auth == Util::iSIT_AUTH_R)OR($_auth == Util::iSIT_AUTH_RW))AND(!isset($_POST["log_in"])))
  {
    // zobraz moznost odhlaseni
    $obsah_html.=Views::logout_form();
    goto OUTPUT;      
  }
  
  if((($_auth == Util::iSIT_AUTH_R)OR($_auth == Util::iSIT_AUTH_RW))AND(isset($_POST["log_in"])))
  {
  	$obsah_html .=
  	'<div class="informace">
    <ul>
      <li>Databáze <a href="./events.php">Notes</a> aktuálně obsahuje '.count($rep->get_all_event()).' záznamů.</li>
      <li>Databáze <a href="./persons.php">Persons</a> aktuálně obsahuje '.count($rep->get_all_person()).' záznamů.</li>
      <li>Databáze <a href="./computers.php">Computers</a> aktuálně obsahuje '.count($rep->get_all_computer()).' záznamů.</li>
      <li>Databáze <a href="./printers.php">Printers</a> aktuálně obsahuje '.count($rep->get_all_printer()).' záznamů.</li>
      <li>Databáze <a href="./links.php">Links</a> aktuálně obsahuje '.count($rep->get_all_link()).' záznamů.</li>
    </ul>
  	
    </div>';
  	
  	$obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
  	goto OUTPUT;
  }

  OUTPUT:
            
    $obsah_html .=     
    '<div class="informace">
    
    </div>'; 
    
    echo $stranka->get_html($obsah_html, $menu->get_html());
   

?>