<?php

class PrinterViews
{                                           

  private static $show_timer=false;
  
  // ------------------------
  // --- PRINTERS LIST ---
  // ------------------------  

  static function _list($list, $nadpis = "Seznam")
  {
  	
    $timer = new Timer();
    $timer2 = new Timer();
    
    $timer2->start();    

    $timer2->stop();
    if(self::$show_timer)$timer2->echo_time("PrinterView->_list()[bpcs]: ");      
    
    $html = '<h2 class="main">'.$nadpis.'</h2>';

   	//$html.= "count(list) = ".count($list)."<br>";

    if(is_array($list) AND (count($list)>0))
    {  
      $html.='<table cellpadding="5">
                <tr>
                  <th><span class="popis"></span></th>
                  <th><span class="popis">Id</span></th>
                  <th><a href="./printers.php?sort=model" class="popis">Model</a></th>
                  <th><span class="popis">IP Address</span></th>
                  <th><span class="popis">BPCS Device</span></th>
                  <th><span class="popis">MAC Address</span></th>
                  <th><span class="popis">SerNum</span></th>
                  <th><span class="popis">EvNum</span></th>
                  <th><a href="./printers.php?sort=datum_porizeni" class="popis">Datum pořízení</a></th>
                  <th><span class="popis">Documents</span></th>
                  <th><span class="popis">Users</span></th>
                  <th></th>
                </tr>
      ';  

      foreach($list as $printer)
      {
        $err_log = array();

        if(Util::is_instance_of($printer, "Printer"))
        {

          $dodak = "";
          if($printer->is_dodak())
          {                         
            $dodak = '<a href="./__pdf/dodaky/'.$printer->get_id().'_Printer.pdf" target="_blank">dodací list</a>';
          }
          $vyrazovak = "";
          if($printer->is_vyrazovak())
          {                         
            $vyrazovak = ' <a href="./__pdf/vyrazovaky/'.$printer->get_id().'_Printer.pdf" target="_blank">návrh na vyřazení</a>';
          }
          $timer2->start();
                    
          $users = "";        

          foreach($printer->get_all_uses() as $use)
          {
            $users.=$use->get_person_login().", ";
          }           
          if($users != "") $users = substr($users, 0, -2);          

          $timer2->stop();
          if(self::$show_timer)$timer2->echo_time("PrinterView->_list()[users]: ");      
                      
          $class = ""; 
          if(count($err_log)>0) $class = 'class="lightblue"';
          if(count($err_log)>1) $class = 'class="lightorange"';

          $html .= '<tr '.$class.' class="printer">
                      <td><a class="hodnota" href="./printers.php?detail='.$printer->get_id().'">Detail</a></td>
                      <td><span class="hodnota">'.$printer->get_id().'</span></td>
                      <td><span class="hodnota">'.$printer->get_model(20).'</span></td>
                      <td><span class="hodnota">'.$printer->get_ip("").'</span></td>
                      <td><span class="hodnota">'.'</span></td>
                      <td><span class="hodnota">'.Util::to_friendly_mac($printer->get_mac(""),"").'</span></td>
                      <td><span class="hodnota">'.$printer->get_seriove_cislo().'</span></td>
                      <td><span class="hodnota">'.$printer->get_evidencni_cislo().'</span></td>
                      <td><span class="hodnota">'.$printer->get_datum_porizeni(20).'</span></td>
                      <td>'.$dodak.$vyrazovak.'</td>
                      <td><span class="hodnota">'.$users.'</span></td>
                      <td>';

          $timer2->start();
                    
          $err_line="";
          foreach($err_log as $_err)
          {
            $err_line.=$_err.", ";
          }
          $html.= substr($err_line,0,-2);
          $timer2->stop();
          if(self::$show_timer)$timer2->echo_time("PrinterView->_list()[testy]: ");      

          $html.='    </td> 
                    </tr>
                ';
        }  
      }
      $html.="</table> <hr>";
    }
    else
    {
      $html.=Views::informace("Seznam je prázdný.");
    }
    
    $timer->stop();
    if(self::$show_timer)$timer->echo_time("PrinterView->_list(): ");      

    return $html;
  }

  // --------------------------
  // --- PRINTERS DETAIL ---
  // --------------------------
  static function _detail($rw, $printer)
  {
    if(Util::is_instance_of($printer,"Printer") == false) return "<h3 class=\"err\">Views::printer_detail(\$printer): promenna \$printer musi byt instanci tridy Printer!</h3>";
    $printer->set_obrk_folder("../printers/img/");
    $err_htm = array();
        
    $html = '
      <fieldset class="printer">
        <legend class="printer">Detail</legend> 
        <div class="information">
          <span class="popis">Id:</span><span class="hodnota">'.$printer->get_id().'</span>
        </div>
        <div class="information">
          <span class="popis">Model:</span><span class="hodnota">'.$printer->get_model().'</span>            
        </div>
        <div class="information">
          <span class="popis">IP Address:</span><span class="hodnota"><a href="http://'.$printer->get_ip().'">'.$printer->get_ip("").'</a></span>            
        </div>
        <div class="information">
          <span class="popis">MAC Address:</span><span class="hodnota">'.Util::to_friendly_mac($printer->get_mac("")).'</span>            
        </div>
        <div class="information">
          <span class="popis">Sériové číslo:</span><span class="hodnota">'.$printer->get_seriove_cislo().'</span>
        </div>
        <div class="information">
          <span class="popis">Evidenční číslo:</span><span class="hodnota">'.$printer->get_evidencni_cislo().'</span>            
        </div>
        <div class="information">
          <span class="popis">Datum pořízení:</span><span class="hodnota">'.$printer->get_datum_porizeni().'</span>
        </div>
        <div class="information">
          <span class="popis">Aktivní:</span><span class="hodnota">'.$printer->get_aktivni("Ano","Ne").'</span>
        </div>          
        
    ';
    $html.='<p class="no_print">';
    if($rw) $html.= '                      
              <a class="hodnota" href="./printers.php?edit='.$printer->get_id().'">Edit</a> | 
              <a class="hodnota" href="./printers.php?delete='.$printer->get_id().'">Delete</a> | ';
    
    $html.='               
              <a class="hodnota" href="./printers.php?list">Back to List</a>
            </p>
        </fieldset>
        ';

    $html.="<p><ul>";    
    
    if(!$printer->is_dodak())
    {
      if($rw) $html.= '<li class="hidden no_print"><a class="hodnota" href="./printers.php?add_dodak='.$printer->get_id().'">nahrát dodací list</a></li>';                         
    }
    if($printer->is_dodak())
    {                         
      $html.= '<li class="hidden no_print"><a href="./__pdf/dodaky/'.$printer->get_id().'_Printer.pdf" target="_blank" title="Dodací list ve formátu .pdf"><img class="icon" src="./icon-pdf.jpg" alt="dodací list, formát pdf"></a></li>';
    }

    if(!$printer->is_vyrazovak())
    {
      if($rw) $html.='<li class="hidden no_print"><a class="hodnota" href="./printers.php?add_vyrazovak='.$printer->get_id().'">nahrát návrh na vyřazení</a></li>';                         
    }
    if($printer->is_vyrazovak())
    {                         
      $html.= '<li class="hidden no_print"><a href="./__pdf/vyrazovaky/'.$printer->get_id().'_Printer.pdf" target="_blank" title="Návrh na vyřazení .pdf"><img class="icon" src="./icon-pdf.jpg" alt="návrh na vyřazení, formát pdf"></a></li>';
    }
    $html.="</ul></p>";    
    $html.= '
        <p>
          <h3 class="info">Poznámky</h3>
          <ul>';                  
          foreach($printer->get_all_comments() as $obj)
          {
            $html.='<li>'.$obj->get_poznamka();
            if($rw) $html.=' <a class="remove no_print" href="./printers.php?remove_comment='.$obj->get_id().'" title="remove comment">x</a>';
            $html.="</li>";
          }        
        
  if($rw) $html.='    <li class="hidden no_print"><a class="hodnota" href="./printers.php?add_comment='.$printer->get_id().'"> ... Add Comment ...</a></li>';
$html.='  </ul>
        </p>';  

$html.='<p>
          <h3 class="info">Uživatelé</h3>
          <ul>';                  
          foreach($printer->get_all_uses() as $use)
          {
            $html.='<li>'.$use->get_person_full_name();
            if($rw) $html.=' <a class="remove no_print" href="./printers.php?remove_user='.$use->get_id().'" title="remove user">x</a>';
            $html.="</li>";
          }        
        
  if($rw) $html.='  <li class="hidden no_print"><a class="hodnota" href="./printers.php?add_user='.$printer->get_id().'"> ... Add User ...</a></li>';
  $html.='</ul>
      </p>';  

    return $html;
  }
  
  // --------------------------
  // --- PRINTERS CREATE ---
  // --------------------------
  static function _create($printer, $valid_test = true)
  {
    if(Util::is_instance_of($printer,"Printer") == false) return "<h3 class=\"err\">Views::printer_create(\$printer): promenna \$printer musi byt instanci tridy Printer!</h3>";
    $printer->set_obrk_folder("../printers/img/");    
    
    if($valid_test)
    {
      $valid_test = $printer->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
        
    $html = '
      <fieldset class="printer'.$valid_test.'">
        <legend class="printer">Create</legend> 
        <form action="./printers.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$printer->get_id().'</span>
            <span class="err">'.$printer->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Model:</span>
            <input type="text" maxlength="100" size="40" name="printer['.Printer::get_model_index().']" value="'.$printer->get_model().'">
            <span class="err">'.$printer->get_model_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">IP Address:</span>
            <input type="text" maxlength="15" size="15" name="printer['.Printer::get_ip_index().']" value="'.$printer->get_ip().'">
            <span class="err">'.$printer->get_ip_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">MAC Address:</span>
            <input type="text" maxlength="17" size="20" name="printer['.Printer::get_mac_index().']" value="'.Util::to_friendly_mac($printer->get_mac()).'">
            <span class="err">'.$printer->get_mac_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Sériové číslo:</span>
            <input type="text" maxlength="20" size="20" name="printer['.Printer::get_seriove_cislo_index().']" value="'.$printer->get_seriove_cislo().'">
            <span class="err">'.$printer->get_seriove_cislo_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">Evidenční číslo:</span>
            <input type="text" maxlength="20" size="20" name="printer['.Printer::get_evidencni_cislo_index().']" value="'.$printer->get_evidencni_cislo().'">
            <span class="err">'.$printer->get_evidencni_cislo_err().'</span>
          </div>          
          <div class="editable">
            <span class="popis">Datum pořízení:</span>
            <input type="text" maxlength="20" size="20" name="printer['.Printer::get_datum_porizeni_index().']" value="'.$printer->get_datum_porizeni().'">
            <span class="err">'.$printer->get_datum_porizeni_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="hidden" name="printer['.Printer::get_aktivni_index().']" value="0">
            <input type="checkbox" name="printer['.Printer::get_aktivni_index().']" '.$printer->get_aktivni("checked","").'>            
            <span class="err">'.$printer->get_aktivni_err().'</span>
          </div>    
          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="./printers.php?list">Back to List</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="printer['.Printer::get_id_index().']" value="'.$printer->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
      </fieldset> 
    ';  
    
    return $html;
  }

  // ------------------------
  // --- PRINTERS EDIT ---
  // ------------------------
  static function _edit($printer, $valid_test = true)
  {
    if(Util::is_instance_of($printer,"Printer") == false) return "<h3 class=\"err\">Views::printer_edit(\$printer): promenna \$printer musi byt instanci tridy Printer!</h3>";
    if($valid_test)
    {
      $valid_test = $printer->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
    
    $printer->set_obrk_folder("../printers/img/");    
    $html = '
      <fieldset class="printer'.$valid_test.'">
        <legend class="printer">Edit</legend> 
        <form action="./printers.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$printer->get_id().'</span>
            <span class="err">'.$printer->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Model:</span>
            <input type="text" maxlength="100" size="40" name="printer['.Printer::get_model_index().']" value="'.$printer->get_model().'">
            <span class="err">'.$printer->get_model_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">IP Address:</span>
            <input type="text" maxlength="15" size="15" name="printer['.Printer::get_ip_index().']" value="'.$printer->get_ip().'">
            <span class="err">'.$printer->get_ip_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">MAC Address:</span>
            <input type="text" maxlength="17" size="20" name="printer['.Printer::get_mac_index().']" value="'.Util::to_friendly_mac($printer->get_mac()).'">
            <span class="err">'.$printer->get_mac_err().'</span>
          </div>          
          <div class="editable">
            <span class="popis">Sériové číslo:</span>
            <input type="text" maxlength="20" size="20" name="printer['.Printer::get_seriove_cislo_index().']" value="'.$printer->get_seriove_cislo().'">
            <span class="err">'.$printer->get_seriove_cislo_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">Evidenční číslo:</span>
            <input type="text" maxlength="20" size="20" name="printer['.Printer::get_evidencni_cislo_index().']" value="'.$printer->get_evidencni_cislo().'">
            <span class="err">'.$printer->get_evidencni_cislo_err().'</span>
          </div>          
          <div class="editable">
            <span class="popis">Datum pořízení:</span>
            <input type="text" maxlength="20" size="20" name="printer['.Printer::get_datum_porizeni_index().']" value="'.$printer->get_datum_porizeni().'">
            <span class="err">'.$printer->get_datum_porizeni_err().'</span>
          </div>          
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="hidden" name="printer['.Printer::get_aktivni_index().']" value="0">
            <input type="checkbox" name="printer['.Printer::get_aktivni_index().']" '.$printer->get_aktivni("checked","").'>            
            <span class="err">'.$printer->get_aktivni_err().'</span>
          </div>';                     
                    
$html.= '
         <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="printer['.Printer::get_id_index().']" value="'.$printer->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>

        <p>
          <a class="hodnota" href="./printers.php?detail='.$printer->get_id().'">Back to Detail</a> | 
          <a class="hodnota" href="./printers.php?list">Back to List</a>
        </p>
      </fieldset> 
    ';  
    return $html;
  }

  // ----------------
  // --- ADD USER ---
  // ----------------
  
  static function _add_user($printer, $persons)
  {
    if(Util::is_instance_of($printer,"Printer") == false) return "<h3 class=\"err\">Views::add_printer_user(\$printer \$persons): promenna \$printer musi byt instanci tridy Printer!</h3>";
    
    $html = '
      <fieldset class="printer">
        <legend class="printer">Add User</legend> 
        <form action="./printers.php" enctype="multipart/form-data" method="post">

        <div class="information">
          <span class="popis">Model:</span><span class="hodnota">'.$printer->get_model().'</span>            
        </div>
        <div class="information">
          <span class="popis">IP Address:</span><span class="hodnota"><a href="http://'.$printer->get_ip().'">'.$printer->get_ip().'</a></span>            
        </div>
        <div class="information">
          <span class="popis">MAC Address:</span><span class="hodnota">'.Util::to_friendly_mac($printer->get_mac().":").'</span>            
        </div>
        <div class="information">
          <span class="popis">Sériové číslo:</span><span class="hodnota">'.$printer->get_seriove_cislo().'</span>
        </div>
        <div class="information">
          <span class="popis">Evidenční číslo:</span><span class="hodnota">'.$printer->get_evidencni_cislo().'</span>            
        </div>
        <div class="information">
          <span class="popis">Datum pořízení:</span><span class="hodnota">'.$printer->get_datum_porizeni().'</span>
        </div>
        <div class="information">
          <span class="popis">Aktivní:</span><span class="hodnota">'.$printer->get_aktivni("Ano","Ne").'</span>
        </div>

                      <div class="editable">
                        <select name="printer_use['.PrinterUse::get_person_id_index().']">';
          
      $persons_list = '   <option value="" selected>... Select Person ... ('.count($persons).')</option>';                                  
      
      foreach($persons as $p)
      {
        $persons_list .= '<option value="'.$p->get_id().'">'.$p->get_login().", ".$p->get_full_name().", ".$p->get_osobni_cislo().'</option>';       
      }              
                          
      $html.=$persons_list;
      
      $html.= ' </select>
        
        </div>

          <div class="tlacitka">
            <input type="submit" name="save" value="Add User">
            <a class="hodnota" href="./printers.php?detail='.$printer->get_id().'">Back to Detail</a> 
            <input type="hidden" name="printer_use['.PrinterUse::get_printer_id_index().']" value="'.$printer->get_id().'">
            <input type="hidden" name="printer_use['.PrinterUse::get_id_index().']" value="">
            <input type="hidden" name="printer_use['.PrinterUse::get_poznamka_index().']" value="">
            <input type="hidden" name="token" value="'.Util::get_token().'">            
          </div>            
        </form>
      </fieldset> 
    ';  
    return $html;
  }


} // End Class

?>
