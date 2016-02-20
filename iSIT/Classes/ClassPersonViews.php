<?php

class PersonViews
{                                           

  private static $show_timer=false;

//  * ********************************************************************** *
//  *                            PERSONS                                     *
//  * ********************************************************************** *

  // --------------------
  // --- PERSONS LIST ---
  // --------------------  

  static function _list($list, $nadpis = "Seznam")
  {
    $timer = new Timer();    
    $timer2 = new Timer();

    $timer2->stop();
    if(self::$show_timer)$timer2->echo_time("PersonView->_list()[rep]: ");      
  
    $html = '<h2 class="main">'.$nadpis.'</h2>';

    if(is_array($list) AND (count($list)>0))
    {  
      $html.='<table cellpadding="5">
                <tr>
                  <th><span class="popis"></span></th>
                  <th><a href="./persons.php?sort=location" class="popis">location</a></th>
                  <th><span class="popis">Jméno</span></th>
                  <th><a href="./persons.php?sort=login" class="popis">login</a></th>
                  <th><a href="./persons.php?sort=osobni_cislo" class="popis">os. cis.</a></th>
                  <th><span class="popis"></span></th>
                  <th><span class="popis"></span></th>
                </tr>
      ';  
      foreach($list as $person)
      {
        $timer2->start();
        
        $err_info = array();
  
        $person->set_obrk_folder("../persons/img/");    
        if(Util::is_instance_of($person,"Person"))
        {
          
          $timer2->stop();
          if(self::$show_timer)$timer2->echo_time("PersonView->_list()[testy]: ");      
          
          $html .= '<tr class="person">
                      <td><a class="hodnota" href="./persons.php?detail='.$person->get_id().'">Detail</a></td>
                      <td><span class="hodnota">'.$person->get_string_location().'</span></td>
                      <td><span class="hodnota">'.$person->get_full_name(20).'</span></td>
                      <td><span class="hodnota">'.$person->get_login(20).'</span></td>
                      <td><span class="hodnota">'.$person->get_osobni_cislo().'</span></td>
                      <td>                      
                      ';

          if(count($err_info)>0)
          {
            $html.="";
            foreach($err_info as $chyba)
            {
              $html.="!$chyba<br>";
            } 
            $html.="";          
          }                      
                      
          $html.='
                      </td>
                      <td>
                      ';
          $html.='
                      </td>
                    </tr>
                ';          
        }  
      }
      $html.="</table>  ";
    }
    else
    {
      $html.=Views::informace("Seznam je prázdný.");
    }
    
    $timer->stop();
    if(self::$show_timer)$timer->echo_time("PersonView->_list(): ");      
    
    return $html;
  }

  // ----------------------
  // --- PERSONS DETAIL ---
  // ----------------------
  static function _detail($rw, $person)
  {
    if(Util::is_instance_of($person,"Person") == false) return "<h3 class=\"err\">Views::person_detail(\$person): promenna \$person musi byt instanci tridy Person!</h3>";
    $person->set_obrk_folder("../persons/img/");    
    $html = '
      <fieldset class="person">
        <legend class="person">Detail</legend> 
        <div class="information">
          <span class="popis">Id:</span><span class="hodnota">'.$person->get_id().'</span>
        </div>
        <div class="information">
          <span class="popis">Osobní číslo:</span><span class="hodnota">'.$person->get_osobni_cislo().'</span>
        </div>
        <div class="information">
          <span class="popis">Pobočka:</span><span class="hodnota">'.$person->get_string_location().'</span>
        </div>
        <div class="information">
          <span class="popis">Celé jméno:</span><span class="hodnota">'.$person->get_full_name().'</span>            
        </div>
        <div class="information">
          <span class="popis">login:</span><span class="hodnota">'.$person->get_login().'</span>            
        </div>
        <div class="information">
          <span class="popis">Aktivní:</span><span class="hodnota">'.$person->get_aktivni("Ano","Ne").'</span>
        </div>
    ';
    $html.="<p>";
    
    if($rw) $html.= '                      
              <a class="hodnota" href="./persons.php?edit='.$person->get_id().'">Edit</a> | 
              <a class="hodnota" href="./persons.php?delete='.$person->get_id().'">Delete</a> | '; 
    $html.='  <a class="hodnota" href="./persons.php?list">Back to List</a>
            </p>
        </fieldset>
        <p>
          <ul>
        
        ';
     
     $html.="
          </ul> 
        </p>
     ";   
     
     $html.='
        <p>
          <h3 class="info">Poznámky</h3>
          <ul>';                  
          foreach($person->get_all_comments() as $obj)
          {
            $html.='<li>'.$obj->get_poznamka();
            if($rw) $html.=' <a class="remove" href="./persons.php?remove_comment='.$obj->get_id().'" title="remove comment">x</a>';
            $html.="</li>";
          }        
        
  if($rw) $html.='    <li class="hidden"><a class="hodnota" href="./persons.php?add_comment='.$person->get_id().'"> ... Add Comment ...</a></li>';
          
          $html.='</ul>
        </p>';  
     
     $html.='
        <p>
          <h3 class="info">Požadavky</h3>
          <ul>';                  
          foreach($person->get_all_requirements() as $obj)
          {
            $html.='<li>'.$obj->get_poznamka().'<br><a href="./__pdf/pozadavky/'.$obj->get_id().'_Requirement.pdf" target="_blank" title="Požadavek HW/SW fe formátu .pdf"><img class="icon" src="./icon-pdf.jpg" alt="požadavek HW/SW, formát pdf"></a></li>';
          }        
        
  if($rw) $html.='<li class="hidden"><a class="hodnota" href="./persons.php?add_requirement='.$person->get_id().'"> ... Add Requirement ...</a></li>';
          $html.='</ul>
        </p>';  

/*
        $devices = $person->get_all_devices();
        
$html.= '<p>
          <h3 class="info">Devices ('.count($devices).')</h3>
          <ul>';                  
          foreach($devices as $obj)
          {
            $html.='<li>'.$obj->get_evidencni_cislo().", ".$obj->get_model().'</li>';
          }        
        
          $html.='</ul>
        </p>';  
*/
    return $html;
  }
  
  // ----------------------
  // --- PERSONS CREATE ---
  // ----------------------
  static function _create($person, $valid_test = true)
  {
    if(Util::is_instance_of($person,"Person") == false) return "<h3 class=\"err\">Views::person_create(\$person): promenna \$person musi byt instanci tridy Person!</h3>";
    $person->set_obrk_folder("../persons/img/");    
    
    if($valid_test)
    {
      $valid_test = $person->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
    
    $html = '
      <fieldset class="person'.$valid_test.'">
        <legend class="person">Create</legend> 
        <form action="./persons.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$person->get_id().'</span>
            <span class="err">'.$person->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Osobní číslo:</span>
            <input type="text" maxlength="20" size="20" name="person['.Person::get_osobni_cislo_index().']" value="'.$person->get_osobni_cislo().'">
            <span class="err">'.$person->get_osobni_cislo_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">Celé jméno:</span>
            <input type="text" maxlength="100" size="40" name="person['.Person::get_full_name_index().']" value="'.$person->get_full_name().'">
            <span class="err">'.$person->get_full_name_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Login:</span>
            <input type="text" maxlength="100" size="40" name="person['.Person::get_login_index().']" value="'.$person->get_login().'">
            <span class="err">'.$person->get_login_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Pobočka:</span>
            
        	<select name="person['.Person::get_pobocka_index().']">';
  
    		$locations = $person->get_all_location(); 
    		$list = '   <option value="" selected>... location ... ('.count($locations).')</option>';
  			foreach($locations as $obj)
  			{
  				$list .= '<option value="'.$obj->get_id().'">'.$obj->get_name().'</option>';
  			}
    		$html.=$list;
    		$html.= ' </select>
          </div>
              		          		
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="hidden" name="person['.Person::get_aktivni_index().']" value="0">
            <input type="checkbox" name="person['.Person::get_aktivni_index().']" '.$person->get_aktivni("checked","").'>            
            <span class="err">'.$person->get_aktivni_err().'</span>
          </div>    
          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="./persons.php?list">Back to List</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="person['.Person::get_id_index().']" value="'.$person->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
      </fieldset> 
    ';  
    return $html;
  }

  // --------------------
  // --- PERSONS EDIT ---
  // --------------------
  static function _edit($person, $valid_test = true)
  {
    if(Util::is_instance_of($person,"Person") == false) return "<h3 class=\"err\">Views::person_edit(\$person): promenna \$person musi byt instanci tridy Person!</h3>";
    if($valid_test)
    {
      $valid_test = $person->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
    
    $person->set_obrk_folder("../persons/img/");    
        
    $html = '
      <fieldset class="person'.$valid_test.'">
        <legend class="person">Edit</legend> 
        <form action="./persons.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$person->get_id().'</span>
            <span class="err">'.$person->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Osobní číslo:</span>
            <input type="text" maxlength="20" size="20" name="person['.Person::get_osobni_cislo_index().']" value="'.$person->get_osobni_cislo().'">
            <span class="err">'.$person->get_osobni_cislo_err().'</span>
          </div>                  
          <div class="editable">
            <span class="popis">Celé jméno:</span>
            <input type="text" maxlength="100" size="40" name="person['.Person::get_full_name_index().']" value="'.$person->get_full_name().'">
            <span class="err">'.$person->get_full_name_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Login:</span>
            <input type="text" maxlength="100" size="40" name="person['.Person::get_login_index().']" value="'.$person->get_login().'">
            <span class="err">'.$person->get_login_err().'</span>
          </div>          

		<div class="editable">
            <span class="popis">Pobočka:</span>
            
        	<select name="person['.Person::get_pobocka_index().']">';
  
    		$locations = $person->get_all_location(); 
    		$list = '';
  			foreach($locations as $obj)
  			{
  				if($obj->get_id()==$person->get_pobocka())
  				{ 
  					$list .= '<option selected value="'.$obj->get_id().'">'.$obj->get_name().'</option>';
  				}
  				else 
  				{
  					$list .= '<option value="'.$obj->get_id().'">'.$obj->get_name().'</option>';
  				}
  				
  			}
    		$html.=$list;
    		$html.= ' </select>
        </div>
    				            		
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="hidden" name="person['.Person::get_aktivni_index().']" value="0">
            <input type="checkbox" name="person['.Person::get_aktivni_index().']" '.$person->get_aktivni("checked","").'>            
            <span class="err">'.$person->get_aktivni_err().'</span>
          </div>';                     
                    
$html.= '
         <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="person['.Person::get_id_index().']" value="'.$person->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>

        <p>
          <a class="hodnota" href="./persons.php?detail='.$person->get_id().'">Back to Detail</a> | 
          <a class="hodnota" href="./persons.php?list">Back to List</a>
        </p>
      </fieldset> 
    ';  
    return $html;
  }  
} // End Class

?>
