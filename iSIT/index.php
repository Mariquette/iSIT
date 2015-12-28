<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "index.php");

  function __autoload($class_name) 
  {
    if(!is_file(DirRoot."Classes/Class".$class_name.'.php')) die("Nelze načíst třídu $class_name! (".DirRoot."Classes/Class$class_name.php)");
    include DirRoot."Classes/Class".$class_name.'.php';
  }
  
  session_start();
  Util::filtruj_vstup();

  $rep = new Repository("./");  
  $stranka = new Sablona(FileName);

  /* --- MAIN --- */
  $obsah_html = "";
  $menu_html = "";
  
  $menu = new Menu(FileName);
  $menu->add_item(new SimpleLink("Home","./index.php"));
  $menu->add_item(new SimpleLink("Notes","./events.php"));
  $menu->add_item(new SimpleLink("Locations","./locations.php"));
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
  
  /* --- //// --- */
  OUTPUT:
    
//    echo "Hradec persons (".count($rep->get_all_hradec_person()).")<br>";
//    echo "Hradec computers (".count($rep->get_all_hradec_computer()).")<br>";
//    echo "Hradec ldap computers (".count($rep->get_all_hradec_ldap_computer()).")<br>";
    
    
    /*
    $rep->export_to_csv($rep->get_all_computer(),"2013-04-08-computers.csv");
    $rep->export_to_csv($rep->get_all_disabled_computer(),"2013-04-08-computers.csv");
    $rep->export_to_csv($rep->get_all_event(),"2013-04-08-events.csv");
    $rep->export_to_csv($rep->get_all_person(),"2013-04-08-persons.csv");
    $rep->export_to_csv($rep->get_all_printer(),"2013-04-08-printers.csv");    
    $rep->export_to_csv($rep->get_all_printer_use(),"2013-04-08-printer_uses.csv");
    $rep->export_to_csv($rep->get_all_link(),"2013-04-08-links.csv");
    $rep->export_to_csv($rep->get_all_backup_schedule(),"2013-04-08-backup_schedules.csv");
    */
        
  if($_auth == Util::iSIT_AUTH_NO_LOGED)
  {
  	$obsah_html .=Views::login_form("");
  }
  
  if(($_auth == Util::iSIT_AUTH_R)OR($_auth == Util::iSIT_AUTH_RW))
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
  	
  }
  
    /*$obsah_html .=     
    '<div class="informace">
    <ul>
      <li>Databáze <a href="./events.php">Notes</a> aktuálně obsahuje '.count($rep->get_all_event()).' záznamů.</li>
      <li>Databáze <a href="./persons.php">Persons</a> aktuálně obsahuje '.count($rep->get_all_person()).' záznamů.</li>
      <li>Databáze <a href="./computers.php">Computers</a> aktuálně obsahuje '.count($rep->get_all_computer()).' záznamů.</li>
      <li>Databáze <a href="./printers.php">Printers</a> aktuálně obsahuje '.count($rep->get_all_printer()).' záznamů.</li>
      <li>Databáze <a href="./links.php">Links</a> aktuálně obsahuje '.count($rep->get_all_link()).' záznamů.</li>
    </ul>
    
    </div>'; 
    
	*/
  
    /*
    foreach ($rep->bpcs_get_all_g_device_v2() as $obj)
    {
      echo $obj->to_html_string();
    }
    */

  /*
    if(Util::get_auth()!=Util::iSIT_AUTH_NO_LOGED)
    {
      $obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
    }
    else
    {
      $obsah_html.='<a class="prihlasit" href= "login.php">Přihlásit</a>';
    }
    */
    echo $stranka->get_html($obsah_html, $menu->get_html());
   
?>