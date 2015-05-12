<?php

class Views
{                                           

//  * ********************************************************************** *
//  *                            PRINTERS                                     *
//  * ********************************************************************** *

  //  LIST
  static function printer_list($list, $nadpis = "Seznam")
  {
    return PrinterViews::_list($list, $nadpis);
  }
  // DETAIL
  static function printer_detail($rw, $printer)
  {
    return PrinterViews::_detail($rw, $printer);
  }
  // CREATE
  static function printer_create($printer, $valid_test = true)
  {
    return PrinterViews::_create($printer, $valid_test);
  }
  // EDIT
  static function printer_edit($printer, $valid_test = true)
  {
    return PrinterViews::_edit($printer, $valid_test);
  }
  //  ADD USER
  static function add_printer_user($printer, $persons)
  {
    return PrinterViews::_add_user($printer, $persons);
  }

//  * ********************************************************************** *
//  *                            COMPUTERS                                     *
//  * ********************************************************************** *

  // LIST
  static function computer_list($list, $nadpis = "Seznam")
  {
    return ComputerViews::_list($list, $nadpis);
  }
  // DETAIL
  static function computer_detail($rw, $computer)
  {
    return ComputerViews::_detail($rw, $computer);
  }
  //  CREATE
  static function computer_create($computer, $valid_test = true)
  {
    return ComputerViews::_create($computer, $valid_test);
  }
  // EDIT
  static function computer_edit($computer, $valid_test = true)
  {
    return ComputerViews::_edit($computer, $valid_test);
  }
  //  ADD USER
  static function add_computer_user($computer, $persons)
  {
  	return ComputerViews::_add_user($computer, $persons);
  }
  
//  * ********************************************************************** *
//  *                            PERSONS                                     *
//  * ********************************************************************** *

  // LIST
  static function person_list($list, $nadpis = "Seznam")
  {
    return PersonViews::_list($list, $nadpis);
  }
  // DETAIL
  static function person_detail($rw, $person)
  {
    return PersonViews::_detail($rw, $person);
  }
  // CREATE
  static function person_create($person, $valid_test = true)
  {
    return PersonViews::_create($person, $valid_test);
  }
  //  EDIT
  static function person_edit($person, $valid_test = true)
  {
    return PersonViews::_edit($person, $valid_test);
  }
  
//  * ********************************************************************** *
//  *                            EVENTS                                      *
//  * ********************************************************************** *

  // LIST
  static function event_list($list, $nadpis="Event List")
  {
    return EventViews::_list($list, $nadpis);
  }
  // DETAIL LIST
  static function event_detail_list($rw, $list)
  {
    return EventViews::_detail_list($rw, $list);
  }
  // DETAIL
  static function event_detail($rw, $event)
  {
    return EventViews::_detail($rw, $event);
  }
  // CREATE
  static function event_create($event, $valid_test = true)
  {
    return EventViews::_create($event, $valid_test);
  }
  // EDIT
  static function event_edit($event, $valid_test = true)
  {
    return EventViews::_edit($event, $valid_test);
  }
  // DELETE
  static function event_delete($event)
  {
    return EventViews::_delete($event);
  }

//  * ********************************************************************** *
//  *                            LINKS                                      *
//  * ********************************************************************** *

  //  LIST
  static function link_list($list)
  {
    return LinkViews::_list($list);
  }
  //  DETAIL LIST
  static function link_detail_list($rw, $list)
  {
    return LinkViews::_detail_list($rw, $list);
  }
  //  DETAIL
  static function link_detail($rw, $link)
  {
    return LinkViews::_detail($rw, $link);
  }
  // CREATE
  static function link_create($link, $valid_test = true)
  {
    return LinkViews::_create($link, $valid_test);
  }
  // EDIT
  static function link_edit($link, $valid_test = true)
  {
    return LinkViews::_edit($link, $valid_test);
  }
  //  DELETE
  static function link_delete($link)
  {
    return LinkViews::_delete($link);
  }

//  * ********************************************************************** *
//  *                            COMMENT                                      *
//  * ********************************************************************** *
  
  //  LIST
  static function comment_list($list, $nadpis)
  {
    return CommentViews::_list($list, $nadpis);
  }
  //  CRETAE
  static function comment_create($comment, $script, $valid_test=true)
  {
    return CommentViews::_create($comment, $script, $valid_test);
  }
  //  EDIT
  static function comment_edit($comment, $script, $valid_test = true)
  {
    return CommentViews::_edit($coment, $script, $valid_test);
  }
  //  DELETE
  static function comment_delete($comment)
  {
    return CommentViews::_delete($comment);
  }
  
//  * ********************************************************************** *
//  *                            REQUIREMENT                                 *
//  * ********************************************************************** *
  
  //  LIST
  static function requirement_list($list, $nadpis)
  {
    return RequirementViews::_list($list, $nadpis);
  }
  //  CRETAE
  static function requirement_create($requirement, $script, $valid_test=true)
  {
    return RequirementViews::_create($requirement, $script, $valid_test);
  }
  //  EDIT
  static function requirement_edit($requirement, $script, $valid_test = true)
  {
    return RequirementViews::_edit($coment, $script, $valid_test);
  }
  //  DELETE
  static function requirement_delete($requirement)
  {
    return RequirementViews::_delete($comment);
  }

//  * ********************************************************************** *
//  *                            OBECNE                                      *
//  * ********************************************************************** *


  // --------------
  // --- IMPORT ---
  // --------------
  static function import($type, $nadpis="Import")
  {
    return '
        <h2 class="main">'.$nadpis.'</h2>
        <div class="import">
          <ul>
            <li>Akceptovány jsou poze sobory .csv, kde je jako odělovač použit středník ";".
            <li>Soubor s diakritikou musí být vytvořen ve znakvé stránce UTF-8</li>
            <li>První řádek importovaného soboru je ignorován.</li>
            <li>Importovaný zánam přepisuje existující záznam.</li>
          </ul>
          
          <form action="./utils.php" enctype="multipart/form-data" method="post">
            <input type="file" name="import_file">
            <input type="hidden" name="import" value="'.$type.'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
            <input type="submit" value="Import">
          </form>

          <p>
            <a class="hodnota" href="./utils.php">Back to Utils</a>
          </p>        
        </div>
    ';
  }

  // --------------
  // --- DELETE ---
  // --------------
  static function delete($id, $script)
  {
    return '
        <h2 class="main">Delete</h2>
        <div class="delete">
          <p>
            <b>Opravdu chcete trvale záznam odstranit?</b>
          </p>
          
          <form action="./'.$script.'" enctype="multipart/form-data" method="post">
            <input type="hidden" name="delete" value="'.$id.'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
            <input type="submit" value="Delete">
          </form>
          <p>
            <a class="hodnota" href="./'.$script.'?detail='.$id.'">Back to Detail</a> | 
            <a class="hodnota" href="./'.$script.'?list">Back to List</a>
          </p>        
        </div>
    ';
  }

  // ---------------
  // --- DELETED ---
  // ---------------
  static function deleted($script)
  {
    return '<div class="informace">Záznam byl úspěšně odstraněn. Pokračujte <a href="./'.$script.'">zde</a>.</div>'; 
  }

  // -----------------------------
  // --- PRIHLASOVACI FORMULAR ---
  // -----------------------------
  
  static function login_form($log_in, $err = 0)
  {
    $html = '<h2 class="main">Přihlášení uživatele</h2>';
  
    if($err > 0)
    {
      switch ($err) 
      {
        case 1:
          $html.= '<h3 class="main">Neplatné heslo, nebo uživatelské jméno.</h3>';
          break;
        case 2:
          $html.= '<h3 class="main">Účet je zablokován.</h3>';
          break;
      }
    }
                                                        
    $html.='
      <fieldset class="log_in">
        <legend class="log_in">User</legend> 
        <form action="./login.php" enctype="multipart/form-data" method="post">

          <div class="editable">
            <span class="popis">Jméno:</span>
            <input type="text" maxlength="20" size="20" name="log_in" value="'.$log_in.'">
          </div>

          <div class="editable">
            <span class="popis">Heslo:</span>
            <input type="password" maxlength="20" size="20" name="heslo" value="">
          </div>

          <div class="tlacitka">
            <input type="hidden" name="token" value="'.Util::get_token().'">
            <input type="submit" name="prihlasit" value="Přihlásit">                              
          </div>            

        </form>
      </fieldset> 
    ';  
    
    return $html;
  }

  // -------------------
  // --- LOGOUT FORM ---
  // -------------------
  
  static function logout_form()
  {
    $html = '<h2 class="main">Odhlášení uživatele</h2>';
  
    $html.='
      <fieldset class="log_in">
        <legend class="log_in">User</legend> 

          <div class="editable">
            <span class="popis">Jméno:</span>
            <span class="hodnota">'.Util::get_auth_name().'</span>
          </div>

          <div class="tlacitka">
            <span class="popis"><a href="./login.php?logout">odhlásit</a></span>
          </div>            

      </fieldset> 
    ';  
    
    return $html;
  }

  // ------------------------
  // --- AUTH ERR RW ONLY ---
  // ------------------------

  static function auth_err_rw_only()
  {
    return '<div class="err"><p>Access denied! (RW only)</p></div>';
  }

  // ----------------
  // --- AUTH ERR ---
  // ----------------

  static function auth_err($text="")
  {
    if($text!="") $text = "<p>$text</p>";
    return '<div class="err"><p>Platnost vašeho přihlášení vypršela, nebo nemáte dostatečné oprávnění k zobrazení požadovaného obshau.</p><br>
            <p>Přihlásit se můžete <a href="login.php">zde</a>.</p>
            '.$text.'</div>';
  }

  // -----------
  // --- ERR ---
  // -----------

  static function err($text, $link="")
  {
    if($link!="") $link = " Pokračujte <a href=\"$link\">zde</a>.";
    return '<div class="err">'.$text.$link.'</div>';
  }

  // -----------------
  // --- INFORMACE ---
  // -----------------
  static function informace($text, $nadpis="")
  {
    if($nadpis!="") $nadpis='<h2 class="main">'.$nadpis."</h2>";
    return $nadpis.'<div class="informace">'.$text.'</div>'; 
  }

  // -----------------------
  // --- UPLOAD PDF FILE ---
  // -----------------------
  
  static function upload_pdf_file($objekt, $folder, $script, $nadpis="Upload PDF File")
  {
    $html = ' <h2>'.$nadpis.'</h2> 
              <div>             
                <form action="'.$script.'" enctype="multipart/form-data" method="post">
                  <input type="hidden" name="add_pdf"  value="'.$objekt->get_id().'">
                  <input type="file" name="upload_file">
                  <input type="hidden" name="folder" value="'.$folder.'">
                  <input type="hidden" name="token" value="'.Util::get_token().'">
                  <input type="submit" value="Upload File">
                </form>
              </div>
    ';
    return $html;
      
  }

  /****************************************************************************/
  
  static function vyrazovak($majetek, $vystavil, $datum)
  {
    //$nic = '<font color="white" >x</font>';
    $nic = '&nbsp;';
    $vystavil = $nic;
    $html = '
  <body>

    <div class="main">
      <h1>NÁVRH NA VYŘAZENÍ MAJETKU</h1>  
      <hr align=left>
      
      <table id="t1" cellspacing=0 cellpadding=0 border=1>
        <tr>
          <td class="sloupec_0" rowspan=3>Důvod vyřazení<span class="upper_index">*)</span> :</td>
          <td class="label">technicky zastaralé</td>
          <td class="zaskrtavatko">'.$nic.'</td>
          <td class="vycpavka" rowspan=3>'.$nic.'</td>
          <td class="jine">jiné (uveďte)</td>
          <td class="zaskrtavatko">'.$nic.'</td>
        </tr>
        <tr>
          <td class="label">neopravitelné</td>
          <td class="zaskrtavatko">'.$nic.'</td>
          <td class="td_4">'.$nic.'</td>
          <td class="td_5">'.$nic.'</td>
        </tr>
        <tr>
          <td class="label">nepoužívané</td>
          <td class="zaskrtavatko">'.$nic.'</td>
          <td class="td_1">'.$nic.'</td>
          <td class="td_3">'.$nic.'</td>
        </tr>
      </table>
    
      <table id="t2" cellspacing=0 cellpadding=0 border=1>
        <tr>
          <td class="sloupec_0" rowspan=2>Způsob vyřazení<span class="upper_index">*)</span> :</td>
          <td class="label">odprodej</td>
          <td class="zaskrtavatko">'.$nic.'</td>
          <td class="vycpavka" rowspan=2>'.$nic.'</td>
          <td class="jine">jiné (uveďte)</td>
          <td class="zaskrtavatko">'.$nic.'</td>
        </tr>
        <tr>
          <td class="label">šrotace</td>
          <td class="zaskrtavatko">'.$nic.'</td>
          <td class="td_1">'.$nic.'</td>
          <td class="td_2">'.$nic.'</td>
        </tr>
      </table>
      <span>*) Zaškrtněte správné políčko</span>
    
      <table id="t3" cellspacing=0 cellpadding=0 border=1>
        <tr>
          <td class="sloupec_1">Inventární číslo:</td>
          <td class="sloupec_2">'.$majetek->get_inventarni_cislo().'</td>
        </tr>
        <tr>
          <td class="sloupec_1">Název:</td>
          <td class="sloupec_2">'.$majetek->get_nazev().'</td>        
        </tr>
        <tr>
          <td class="sloupec_1">Rok výroby:</td>
          <td class="sloupec_2">'.substr($majetek->get_porizeno(),-4,4).'</td>  
        </tr>
        <tr>
          <td class="sloupec_1">Pořizovací cena:</td>
          <td class="sloupec_2">'.$majetek->get_cena_porizeni().',-</td>  
        </tr>
        <tr>
          <td class="sloupec_1">Zůstatková cena:</td>
          <td class="sloupec_2">'.$majetek->get_cena_zustatkova().',-</td>  
        </tr>
      </table>
      
      <table id="t4" cellspacing=0 cellpadding=0 border=1>
        <tr>
          <td class="sloupec_1">'.$nic.'</td>
          <td class="sloupec_3">Funkce</td>
          <td class="sloupec_3">Jméno</td>
          <td class="sloupec_3">Datum</td>
          <td class="sloupec_3">Podpis</td>
        </tr>
        <tr>
          <td class="sloupec_1">Vystavil:</td>
          <td class="sloupec_3">CSIIT</td>
          <td class="sloupec_3">'.$vystavil.'</td>
          <td class="sloupec_3">'.$datum.'</td>
          <td>'.$nic.'</td>
        </tr>
        <tr>
          <td class="sloupec_1">Kontroloval:</td>
          <td class="sloupec_3">VO CSIIT</td>
          <td class="sloupec_3">Ing. Krabáč</td>
          <td>'.$nic.'</td>
          <td>'.$nic.'</td>
        </tr>
        <tr>
          <td class="sloupec_1">Schválil:</td>
          <td class="sloupec_3">'.$nic.'</td>
          <td>'.$nic.'</td>
          <td>'.$nic.'</td>
          <td>'.$nic.'</td>
        </tr>
      </table>
      
      <span class="fas">F.AS-10-042-R00</span>
  </div>  
  </body>
    ';
    
    return $html;
  }

} // End ClassViews

?>
