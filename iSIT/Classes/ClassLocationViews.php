<?php

class LocationViews
{                                           

	private static $show_timer=false;
	
//  * ********************************************************************** *
//  *                            LOCATIONS                                      *
//  * ********************************************************************** *

  // ----------------------
  // --- LOCATIONS LIST ---
  // ----------------------  

static function _list($list, $nadpis = "Seznam")
  {
    $timer = new Timer();    
    $timer2 = new Timer();

    $timer2->stop();
    if(self::$show_timer)$timer2->echo_time("LocationView->_list()[rep]: ");      
  
    $html = '<h2 class="main">'.$nadpis.'</h2>';

    if(is_array($list) AND (count($list)>0))
    {  
      $html.='<table cellpadding="5">
                <tr>
                  <th><span class="popis"></span></th>
                  <th><span class="popis">Name</span></th>
                  <th><span class="popis">Adresa</span></th>
                  <th><span class="popis">Popis</span></th>
                  <th><span class="popis"></span></th>
                  <th><span class="popis"></span></th>
      		</tr>
      ';  
      foreach($list as $location)
      {
        $timer2->start();
        
        $err_info = array();
  
        if(Util::is_instance_of($location,"Location"))
        {
          
          $timer2->stop();
          if(self::$show_timer)$timer2->echo_time("LocationView->_list()[testy]: ");      
          
          $html .= '<tr class="location">
                      <td><a class="hodnota" href="./locations.php?detail='.$location->get_id().'">Detail</a></td>
                      <td><span class="hodnota">'.$location->get_name().'</span></td>
                      <td><span class="hodnota">'.$location->get_addr(15).'</span></td>
                      <td><span class="hodnota">'.$location->get_popis(20).'</span></td>
                      <td><span class="hodnota">'.'</span></td>
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
    if(self::$show_timer)$timer->echo_time("LocationView->_list(): ");      
    
    return $html;
  }
	

  // --------------------------
  // --- DETAIL LOCATIONS LIST ---
  // --------------------------  

  static function _detail_list($rw, $list)
  {
    $html = '<h2 class="main">Detail List</h2>';

    if(is_array($list) AND (count($list)>0))
    {    
      foreach($list as $item)
      {
        if(Util::is_instance_of($item,"Location"))
        {
          $html .= '<fieldset class="location_list_item'.$item->get_aktivni("","_disable").'">
                      <legend>Location č.'.$item->get_id().'</legend>
                      <span class="popis">Id:</span><span class="hodnota">'.$item->get_id().'</span><br>
                      <span class="popis">Name:</span><span class="hodnota">'.$item->get_name(15).'</span><br>
                      <span class="popis">Popis:</span>
                      <div class="location_popis">'.Util::decode_link($item->get_popis()).'</div>
                      <span class="popis">Aktivní:</span><span class="hodnota">'.$item->get_aktivni("Ano","Ne").'</span><br>
          ';
          $html .= '  
                      <p>
                        <a class="hodnota" href="./locations.php?detail='.$item->get_id().'">Detail</a>';
          if($rw) $html.='                         
                         | <a class="hodnota" href="./locations.php?disable='.$item->get_id().'">'.$item->get_aktivni("Disable","Enable").'</a>';
          $html.="</p>";                        
        }
        $html.="</fieldset>";  
      }
    }
    else
    {
      $html.=Views::informace("Seznam je prázdný.");
    }
    return $html;
  }

  // -----------------------
  // --- LOCATION DETAIL ---
  // -----------------------
  static function _detail($rw, $location)
  {
    if(Util::is_instance_of($location,"Location") == false) return "<h3 class=\"err\">Views::location_detail(\$location): promenna \$location musi byt instanci tridy Location!</h3>";
    $html = '
    
      <fieldset class="location">
        <legend class="location">Detail</legend> 
        <div class="editable">
          <span class="popis">Id:</span><span class="hodnota">'.$location->get_id().'</span>
        </div>
      
        <div class="editable">
          <span class="popis">Name:</span><br><span class="hodnota">'.$location->get_name().'</span>
        </div>
        
        <div class="editable">
          <span class="popis">Addr:</span><br><span class="hodnota">'.$location->get_addr().'</span>
        </div>

        <div class="editable">          
          <span class="popis">Popis:</span>
          <div class="location_popis">
            '.Util::decode_link($location->get_popis()).'
          </div>
        </div>

        <div class="editable">
          <span class="popis">Aktivní:</span>            
          <span class="hodnota">'.$location->get_aktivni("Ano","Ne").'</span>            
        </div>    

        <p>';
        if($rw) $html.='  
          <a class="hodnota" href="./locations.php?edit='.$location->get_id().'">Edit</a> |  
          <a class="hodnota" href="./locations.php?delete='.$location->get_id().'">Delete</a> | ';
        $html.='            
          <a class="hodnota" href="./locations.php?list">Back to List</a>
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }
  
  // ------------------------
  // --- LIOCATION CREATE ---
  // ------------------------
  static function _create($location, $valid_test = true)
  {
    if(Util::is_instance_of($location,"Location") == false) return "<h3 class=\"err\">Views::location_create(\$location): promenna \$location musi byt instanci tridy Location!</h3>";

    if($valid_test)
    {
      $valid_test = $location->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }

    $html = '
      <fieldset class="location'.$valid_test.'">
        <legend class="location">Create</legend> 
        <form action="./locations.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$location->get_id().'</span>
            <span class="err">'.$location->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Name:</span>
            <input type="text" maxlength="100" size="57" name="location['.Location::get_name_index().']" value="'.$location->get_name().'">
            <span class="err">'.$location->get_name_err().'</span>
          </div>
          
          <div class="editable">
            <span class="popis">Address:</span>
            <input type="text" maxlength="500" size="57" name="location['.Location::get_addr_index().']" value="'.$location->get_addr().'">
            <span class="err">'.$location->get_addr_err().'</span>
          </div>

          <div class="editable">          
            <span class="popis">Popis:</span>
            <textarea   class="location_popis" cols="50" rows="10" wrap="soft" name="location['.Location::get_popis_index().']">
              '.$location->get_popis().'
            </textarea>
            <span class="err">'.$location->get_popis_err().'</span>
          </div>
          
          <div class="editable">
            <span class="popis">Aktivní:</span>
            <input type="hidden" name="location['.Location::get_aktivni_index().']" value="0">
            <input type="checkbox" name="location['.Location::get_aktivni_index().']" '.$location->get_aktivni("checked","").'>    
            <span class="err">'.$location->get_aktivni_err().'</span>        
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="./locations.php?list">Back to List</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="location['.Location::get_id_index().']" value="'.$location->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
      </fieldset> 
    ';  
    return $html;
  }

  // ---------------------
  // --- LOCATION EDIT ---
  // ---------------------
  static function _edit($location, $valid_test = true)
  {
    if(Util::is_instance_of($location,"Location") == false) return "<h3 class=\"err\">Views::location_edit(\$location): promenna \$location musi byt instanci tridy Location!</h3>";
    if($valid_test)
    {
      $valid_test = $location->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
        
    $html = '
      <fieldset class="location'.$valid_test.'">
        <legend class="location">Edit</legend> 
        <form action="./locations.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$location->get_id().'</span>
            <span class="err">'.$location->get_id_err().'</span> 
          </div>
        
          <div class="editable">
            <span class="popis">Name:</span>
            <input type="text" maxlength="100" size="57" name="location['.Location::get_name_index().']" value="'.$location->get_name().'">
            <span class="err">'.$location->get_name_err().'</span> 
          </div>
          
          <div class="editable">
            <span class="popis">Addr:</span>
            <input type="text" maxlength="100" size="57" name="location['.Location::get_addr_index().']" value="'.$location->get_addr().'">
            <span class="err">'.$location->get_addr_err().'</span> 
          </div>

          <div class="editable">          
            <span class="popis">Popis:</span>
            <textarea class="location_popis" cols="50" rows="10" wrap="soft" name="location['.Location::get_popis_index().']">
              '.$location->get_popis().'
            </textarea>
            <span class="err">'.$location->get_popis_err().'</span> 
          </div>
          
          <div class="editable">
            <span class="popis">Aktivní:</span>
            <input type="hidden" name="location['.Location::get_aktivni_index().']" value="0">
            <input type="checkbox" name="location['.Location::get_aktivni_index().']" '.$location->get_aktivni("checked","").'>
            <span class="err">'.$location->get_aktivni_err().'</span>             
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="location['.Location::get_id_index().']" value="'.$location->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
        <p>
          <a class="hodnota" href="./locations.php?detail='.$location->get_id().'">Back to Detail</a> |  
          <a class="hodnota" href="./locations.php?list">Back to List</a>
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }

  // -----------------------
  // --- LOCATION DELETE ---
  // -----------------------
  static function _delete($location)
  {
    if(Util::is_instance_of($location,"Location") == false) return "<h3 class=\"err\">Views::location_delete(\$location): promenna \$location musi byt instanci tridy Location!</h3>";
    return '
        <h2 class="main">Delete</h2>
        <div class="delete">
          <p>
            <b>Opravdu chcete trvale odstranit odkaz č.'.$location->get_id().'?</b>
          </p>
          
          <form action="./locations.php" enctype="multipart/form-data" method="post">
            <input type="hidden" name="delete" value="'.$location->get_id().'">
            <input type="submit" value="Delete">
          </form>
          <p>
            <a class="hodnota" href="./location.php?edit='.$location->get_id().'">Back to Edit</a> | 
            <a class="hodnota" href="./location.php?list">Back to List</a>
          </p>        
        </div>
    ';
  }
} // End Class

?>
