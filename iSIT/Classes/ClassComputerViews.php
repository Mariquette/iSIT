<?php

class ComputerViews
{                                           

  private static $show_timer=false;

//  * ********************************************************************** *
//  *                            COMPUTERS                                     *
//  * ********************************************************************** *

  // ----------------------
  // --- COMPUTERS LIST ---
  // ----------------------  

  static function _list($list, $nadpis = "Seznam")
  {
    $timer = new Timer();
    $timer2 = new Timer();
    $timer->start();
    $timer2->start();
    
    $rep = new Repository("./");  
    $timer2->stop();
    if(self::$show_timer)$timer2->echo_time("ComputerView->_list()[rep]: ");      
    
    $html = '<h2 class="main">'.$nadpis.'</h2>';

    if(is_array($list) AND (count($list)>0))
    {  
      $html.='<table cellpadding="5">
                <tr>
                  <th><span class="popis"></span></th>
                  <th><span class="popis">Id</span></th>
                  <th><a href="./computers.php?sort=model" class="popis">Model</a></th>
                  <th><span class="popis">Location</span></th>
                  <th><span class="popis">Stag</span></th>
                  <th><span class="popis">EvNum</span></th>
                  <th><span class="popis">PC Name</span></th>
                  <th><span class="popis">TeamViewer</span></th>
                  <th><a href="./computers.php?sort=datum_porizeni" class="popis">Datum pořízení</a></th>
                  <th><span class="popis">Users</span></th>
      			  <th><span class="popis">Documents</span></th>
      			  <th></th>
                </tr>
      ';  
      
      $i=0;
      foreach($list as $computer)
      {
        $pc_name = "-";
        $err_log = array();
        
        if(Util::is_instance_of($computer,"Computer"))
        {
        	$users = "";
        	foreach($computer->get_all_uses() as $use)
        	{
        		$users.=$use->get_person_login().", ";
        	}
        	if($users != "") $users = substr($users, 0, -2);
        	 
          $dodak = "";
          if($computer->is_dodak())
          {                         
            $dodak = '<a href="./__pdf/dodaky/'.$computer->get_id().'_Computer.pdf" target="_blank">dodací list</a>';
          }
          $vyrazovak = "";
          if($computer->is_vyrazovak())
          {                         
            $vyrazovak = ' <a href="./__pdf/vyrazovaky/'.$computer->get_id().'_Computer.pdf" target="_blank">návrh na vyřazení</a>';
          }
          
          $class = ""; 
          if(count($err_log)>0) $class = 'class="lightblue"';
          if(count($err_log)>1) $class = 'class="lightorange"';
          if(count($err_log)>2) $class = 'class="lightred"';
          $timer2->start();
          
          $html .= '<tr '.$class.' class="computer">
                      <td><a class="hodnota" href="./computers.php?detail='.$computer->get_id().'">Detail</a></td>
                      <td><span class="hodnota">'.$computer->get_id().'</span></td>
                      <td><span class="hodnota">'.$computer->get_model(20).'</span></td>
                      <td><span class="hodnota">'.$computer->get_string_location().'</span></td>
                      <td><span class="hodnota">'.$computer->get_seriove_cislo().'</span></td>
                      <td><span class="hodnota">'.$computer->get_evidencni_cislo().'</span></td>
                      <td><span class="hodnota">'.$computer->get_pc_name(20).'</span></td>
                      <td><span class="hodnota">'.$computer->get_teamviewer().'</span></td>
                      <td><span class="hodnota">'.$computer->get_datum_porizeni(20).'</span></td>
                      <td><span class="hodnota">'.$users.'</span></td>
                      <td>'.$dodak.$vyrazovak.'</td>
                      <td>';
          
          $timer2->start();
          $err_line="";
          foreach($err_log as $_err)
          {
            $err_line.=$_err.", ";
          }
          $html.= substr($err_line,0,-2);
          
          $timer2->stop();
          if(self::$show_timer)$timer2->echo_time("ComputerView->_list()[foreach][".$i++."][testy]: ");      

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
    if(self::$show_timer)$timer->echo_time("ComputerView->_list(): ");      
    
    return $html;
  }

  

  // ----------------------
  // --- COMPUTERS DETAIL ---
  // ----------------------
  static function _detail($rw, $computer)
  {
    if(Util::is_instance_of($computer,"Computer") == false) return "<h3 class=\"err\">Views::computer_detail(\$computer): promenna \$computer musi byt instanci tridy Computer!</h3>";
    $computer->set_obrk_folder("../computers/img/");    
    $html = '
      <fieldset class="computer">
        <legend class="computer"><a href="./computers.php?detail='.$computer->get_id().'">Detail</a></legend> 
        <div class="information">
          <span class="popis">*Id:</span><span class="hodnota">'.$computer->get_id().'</span>
        </div>
        <div class="information">
          <span class="popis">*Model:</span><span class="hodnota">'.$computer->get_model().'</span>            
        </div>
        <div class="information">
          <span class="popis">Location:</span><span class="hodnota">'.$computer->get_string_location().'</span>
        </div>
        <div class="information">
          <span class="popis">*Sériové číslo:</span><span class="hodnota">'.$computer->get_seriove_cislo().'</span>
        </div>
        <div class="information">
          <span class="popis">*Evidenční číslo:</span><span class="hodnota">'.$computer->get_evidencni_cislo().'</span>            
        </div>
        <div class="information">
          <span class="popis">PC Name:</span><span class="hodnota">'.$computer->get_pc_name().'</span>            
        </div>
        <div class="information">
          <span class="popis">TeamViewer:</span><span class="hodnota">'.$computer->get_teamviewer().'</span>            
        </div>        
        <div class="information">
          <span class="popis">Datum pořízení:</span><span class="hodnota">'.$computer->get_datum_porizeni().'</span>
        </div>
        <div class="information">
          <span class="popis">Aktivní:</span><span class="hodnota">'.$computer->get_aktivni("Ano","Ne").'</span>
        </div>
        
    ';
    
    $html.='<p class="no_print">';
    if($rw) $html.= '                      
              <a class="hodnota" href="./computers.php?edit='.$computer->get_id().'">Edit</a> | 
              <a class="hodnota" href="./computers.php?delete='.$computer->get_id().'">Delete</a> |
              ';
    $html.='               
              <a class="hodnota" href="./computers.php?list">Back to List</a>
            </p>
        </fieldset>';

  $html.='        
        <p>
          <ul>
        ';  
                        
    if(!$computer->is_dodak())
    {
      if($rw) $html.= '<li class="hidden no_print"><a class="hodnota" href="./computers.php?add_dodak='.$computer->get_id().'">nahrát dodací list</a></li>';                         
    }
    if($computer->is_dodak())
    {                         
      $html.= '<li class="hidden no_print"><a href="./__pdf/dodaky/'.$computer->get_id().'_Computer.pdf" target="_blank" title="Dodací list ve formátu .pdf"><img class="icon" src="./icon-pdf.jpg" alt="dodací list, formát pdf"></a></li>';
    }

    if(!$computer->is_vyrazovak())
    {
      if($rw) $html.='<li class="hidden no_print"><a class="hodnota" href="./computers.php?add_vyrazovak='.$computer->get_id().'">nahrát návrh na vyřazení</a></li>';                         
    }
    if($computer->is_vyrazovak())
    {                         
      $html.= '<li class="hidden no_print"><a href="./__pdf/vyrazovaky/'.$computer->get_id().'_Computer.pdf" target="_blank" title="Návrh na vyřazení ve formátu .pdf"><img class="icon" src="./icon-pdf.jpg" alt="návrh na vyřazení, formát pdf"></a></li>';
    }
        
     $html.="
          </ul> 
        </p>
     ";
          
     $html.='
      <p class="no_print">
        <ul class="no_print">
        </ul>
      </p>     

        <p>
          <h3 class="info">Poznámky</h3>
          <ul>';                  
          foreach($computer->get_all_comments() as $obj)
          {
            $html.='<li>'.$obj->get_poznamka();
            if($rw) $html.=' <a class="remove no_print" href="./computers.php?remove_comment='.$obj->get_id().'" title="remove comment">x</a>';
            $html.="</li>";
          }        
        
  if($rw) $html.='<li class="hidden no_print"><a class="hodnota" href="./computers.php?add_comment='.$computer->get_id().'"> ... Add Comment ...</a></li>';
          $html.= '</ul>
        </p>';  

          $html.='<p>
          <h3 class="info">Uživatelé</h3>
          <ul>';
          foreach($computer->get_all_uses() as $use)
          {
          	$html.='<li>'.$use->get_person_full_name();
          	if($rw) $html.=' <a class="remove no_print" href="./computers.php?remove_user='.$use->get_id().'" title="remove user">x</a>';
          	$html.="</li>";
          }
          
          if($rw) $html.='  <li class="hidden no_print"><a class="hodnota" href="./computers.php?add_user='.$computer->get_id().'"> ... Add User ...</a></li>';
          $html.='</ul>
      </p>';
          
    return $html;
  }
  
  // ----------------------
  // --- COMPUTERS CREATE ---
  // ----------------------
  static function _create($computer, $valid_test = true)
  {
    if(Util::is_instance_of($computer,"Computer") == false) return "<h3 class=\"err\">Views::computer_create(\$computer): promenna \$computer musi byt instanci tridy Computer!</h3>";
    $computer->set_obrk_folder("../computers/img/");    
    
    if($valid_test)
    {
      $valid_test = $computer->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
    
    $html = '
      <fieldset class="computer'.$valid_test.'">
        <legend class="computer">Create</legend> 
        <form action="./computers.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">*Id:</span><span class="hodnota">'.$computer->get_id().'</span>
            <span class="err">'.$computer->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">*Model:</span>
            <input type="text" maxlength="100" size="40" name="computer['.Computer::get_model_index().']" value="'.$computer->get_model().'">
            <span class="err">'.$computer->get_model_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Location:</span>
            <input type="text" maxlength="10" size="4" name="computer['.Computer::get_location_index().']" value="'.$computer->get_location().'">
            <span class="err">'.$computer->get_location_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">*Sériové číslo:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_seriove_cislo_index().']" value="'.$computer->get_seriove_cislo().'">
            <span class="err">'.$computer->get_seriove_cislo_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">*Evidenční číslo:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_evidencni_cislo_index().']" value="'.$computer->get_evidencni_cislo().'">
            <span class="err">'.$computer->get_evidencni_cislo_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">PC Name:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_pc_name_index().']" value="'.$computer->get_pc_name().'">
            <span class="err">'.$computer->get_pc_name_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">TeamViewer:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_teamviewer_index().']" value="'.$computer->get_teamviewer().'">
            <span class="err">'.$computer->get_teamviewer_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">Datum pořízení:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_datum_porizeni_index().']" value="'.$computer->get_datum_porizeni().'">
            <span class="err">'.$computer->get_datum_porizeni_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="hidden" name="computer['.Computer::get_aktivni_index().']" value="0">
            <input type="checkbox" name="computer['.Computer::get_aktivni_index().']" '.$computer->get_aktivni("checked","").'>            
            <span class="err">'.$computer->get_aktivni_err().'</span>
          </div>    
          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="./computers.php?list">Back to List</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="computer['.Computer::get_id_index().']" value="'.$computer->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
      </fieldset> 
    ';  
    return $html;
  }

  // --------------------
  // --- COMPUTERS EDIT ---
  // --------------------
  static function _edit($computer, $valid_test = true)
  {
    if(Util::is_instance_of($computer,"Computer") == false) return "<h3 class=\"err\">Views::computer_edit(\$computer): promenna \$computer musi byt instanci tridy Computer!</h3>";
    if($valid_test)
    {
      $valid_test = $computer->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
    
    $computer->set_obrk_folder("../computers/img/");    
    $html = '
      <fieldset class="computer'.$valid_test.'">
        <legend class="computer">Edit</legend> 
        <form action="./computers.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">*Id:</span><span class="hodnota">'.$computer->get_id().'</span>
            <span class="err">'.$computer->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">*Model:</span>
            <input type="text" maxlength="100" size="40" name="computer['.Computer::get_model_index().']" value="'.$computer->get_model().'">
            <span class="err">'.$computer->get_model_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Location:</span>
            <input type="text" maxlength="10" size="4" name="computer['.Computer::get_location_index().']" value="'.$computer->get_location().'">
            <span class="err">'.$computer->get_location_err().'</span>
          </div>          
          <div class="editable">
            <span class="popis">*Sériové číslo:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_seriove_cislo_index().']" value="'.$computer->get_seriove_cislo().'">
            <span class="err">'.$computer->get_seriove_cislo_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">*Evidenční číslo:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_evidencni_cislo_index().']" value="'.$computer->get_evidencni_cislo().'">
            <span class="err">'.$computer->get_evidencni_cislo_err().'</span>
          </div>          
          <div class="editable">
            <span class="popis">PC Name:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_pc_name_index().']" value="'.$computer->get_pc_name().'">
            <span class="err">'.$computer->get_pc_name_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">TeamViewer:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_teamviewer_index().']" value="'.$computer->get_teamviewer().'">
            <span class="err">'.$computer->get_teamviewer_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">Datum pořízení:</span>
            <input type="text" maxlength="20" size="20" name="computer['.Computer::get_datum_porizeni_index().']" value="'.$computer->get_datum_porizeni().'">
            <span class="err">'.$computer->get_datum_porizeni_err().'</span>
          </div>          
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="hidden" name="computer['.Computer::get_aktivni_index().']" value="0">
            <input type="checkbox" name="computer['.Computer::get_aktivni_index().']" '.$computer->get_aktivni("checked","").'>            
            <span class="err">'.$computer->get_aktivni_err().'</span>
          </div>';                     
                    
$html.= '
         <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="computer['.Computer::get_id_index().']" value="'.$computer->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>

        <p>
          <a class="hodnota" href="./computers.php?detail='.$computer->get_id().'">Back to Detail</a> | 
          <a class="hodnota" href="./computers.php?list">Back to List</a>
        </p>
      </fieldset> 
    ';  
    return $html;
  }

  
  // ----------------
  // --- ADD USER ---
  // ----------------
  
  static function _add_user($computer, $persons)
  {
  	if(Util::is_instance_of($computer,"Computer") == false) return "<h3 class=\"err\">Views::add_computer_user(\$computer \$persons): promenna \$computer musi byt instanci tridy Computer!</h3>";
  
  	$html = '
      <fieldset class="computer">
        <legend class="computer">Add User</legend>
  			<form action="./computers.php" enctype="multipart/form-data" method="post">

        <div class="information">
          <span class="popis">*Model:</span><span class="hodnota">'.$computer->get_model().'</span>            
        </div>
        <div class="information">
          <span class="popis">*Sériové číslo:</span><span class="hodnota">'.$computer->get_seriove_cislo().'</span>
        </div>
        <div class="information">
          <span class="popis">*Evidenční číslo:</span><span class="hodnota">'.$computer->get_evidencni_cislo().'</span>            
        </div>
        <div class="information">
          <span class="popis">PC Name:</span><span class="hodnota">'.$computer->get_pc_name().'</span>            
        </div>
        <div class="information">
          <span class="popis">TeamViewer:</span><span class="hodnota">'.$computer->get_teamviewer().'</span>            
        </div>        
        <div class="information">
          <span class="popis">Datum pořízení:</span><span class="hodnota">'.$computer->get_datum_porizeni().'</span>
        </div>
  			
        <div class="editable">
        <select name="computer_use['.ComputerUse::get_person_id_index().']">';
  
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
            <a class="hodnota" href="./computers.php?detail='.$computer->get_id().'">Back to Detail</a>
            <input type="hidden" name="computer_use['.ComputerUse::get_computer_id_index().']" value="'.$computer->get_id().'">
            <input type="hidden" name="computer_use['.ComputerUse::get_id_index().']" value="">
            <input type="hidden" name="computer_use['.ComputerUse::get_poznamka_index().']" value="">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>
        </form>
      </fieldset>
    ';
  	return $html;
  }
  
  
} // End Class

?>
