<?php

class EventViews
{                                           
  
//  * ********************************************************************** *
//  *                            EVENTS                                      *
//  * ********************************************************************** *

  // -------------------
  // --- EVENTS LIST ---
  // -------------------  

  static function _list($list, $nadpis="Event List")
  {
    $html = '<h2 class="main">'.$nadpis.'</h2>';

    if(is_array($list) AND (count($list)>0))
    {    
      foreach($list as $item)
      {
        if(Util::is_instance_of($item,"Event"))
        {
          if(!$item->get_aktivni()) continue;
          //<span class="popis">... '.substr(Util::decode_link($item->get_text()),0,60).' ...</span>
          $html .= '<div class="event_list">
                      <a class="nadpis" href="./events.php?detail='.$item->get_id().'">'.$item->get_nadpis(40).'</a><br>
                      <ul><li class="hidden"><span class="popis">'.Util::decode_link($item->get_text()).'</span></li></ul>
                    </div>';
//                      <div class="event_text">'.Util::decode_link($item->get_text()).'</div>

        }
      }
    }
    else
    {
      $html.=Views::informace("Seznam je prázdný.");
    }
    return $html;
  }

  // --------------------------
  // --- DETAIL EVENTS LIST ---
  // --------------------------  

  static function _detail_list($rw, $list)
  {
    $html = '<h2 class="main">Detail Seznam</h2>';

    if(is_array($list) AND (count($list)>0))
    {    
      foreach($list as $item)
      {
        if(Util::is_instance_of($item,"Event"))
        {
          $html .= '<fieldset class="event_list_item'.$item->get_aktivni("","_disable").'">
                      <legend>Event č.'.$item->get_id().'</legend>
                      <span class="popis">Id:</span><span class="hodnota">'.$item->get_id().'</span><br>
                      <span class="popis">Nadpis:</span><span class="hodnota">'.$item->get_nadpis(40).'</span><br>
                      <span class="popis">Text:</span>
                      <div class="event_text">'.Util::decode_link($item->get_text()).'</div>

                      <span class="popis">Zobrazit od:</span><span class="hodnota">'.Util::timestamp_to_date($item->get_zobrazit_od()).'</span>
                      <span class="popis">do</span><span class="hodnota">'.Util::timestamp_to_date($item->get_zobrazit_do()).'</span><br>

                      <span class="popis">Zobrazit na webu:</span><span class="hodnota">'.$item->get_aktivni("Ano","Ne").'</span><br>
          ';
          $html .= '  
                      <p>
                        <a class="hodnota" href="./events.php?detail='.$item->get_id().'">Detail</a>
          ';
          
          if($rw) $html .= '  
                         | <a class="hodnota" href="./events.php?disable='.$item->get_id().'">'.$item->get_aktivni("Disable","Enable").'</a>
          ';
          $html .= '  
                      </p>
          ';
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

  // --------------------
  // --- EVENT DETAIL ---
  // --------------------
  static function _detail($rw, $event)
  {
    if(Util::is_instance_of($event,"Event") == false) return "<h3 class=\"err\">Views::event_detail(\$event): promenna \$event musi byt instanci tridy Event!</h3>";
    $html = '
    
      <fieldset class="event">
        <legend class="event">Detail</legend> 
        <div class="information">
          <span class="popis">Id:</span><span class="hodnota">'.$event->get_id().'</span>
        </div>
      
        <div class="information">
          <span class="popis">Nadpis:</span><br><span class="hodnota">'.$event->get_nadpis().'</span>
        </div>
        
        <div class="information">          
          <span class="popis">Text:</span>
          <div class="event_text">
            '.Util::decode_link($event->get_text()).'
          </div>
        </div>
        
        <div class="information">
          <span class="popis">Zobrazit Od:</span>
          <span class="hodnota">'.Util::timestamp_to_date($event->get_zobrazit_od()).'</span>
        </div>

        <div class="information">
          <span class="popis">Zobrazit Do:</span>
          <span class="hodnota">'.Util::timestamp_to_date($event->get_zobrazit_do()).'</span>
        </div>

        <div class="information">
          <span class="popis">Aktivní:</span>            
          <span class="hodnota">'.$event->get_aktivni("Ano","Ne").'</span>            
        </div>    
  ';
  
  $html.="<p>";
  if($rw) $html.='        
          <a class="hodnota" href="./events.php?edit='.$event->get_id().'">Edit</a> |  
          <a class="hodnota" href="./events.php?delete='.$event->get_id().'">Delete</a> | ';
           
  $html.=' <a class="hodnota" href="./events.php?list">Back to List</a>
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }
  
  // --------------------
  // --- EVENT CREATE ---
  // --------------------
  static function _create($event, $valid_test = true)
  {
    if(Util::is_instance_of($event,"Event") == false) return "<h3 class=\"err\">Views::event_create(\$event): promenna \$event musi byt instanci tridy Event!</h3>";

    if($valid_test)
    {
      $valid_test = $event->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }

    $html = '
      <fieldset class="event'.$valid_test.'">
        <legend class="event">Create</legend> 
        <form action="./events.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$event->get_id().'</span>
            <span class="err">'.$event->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Nadpis:</span>
            <input type="text" maxlength="100" size="57" name="event['.Event::get_nadpis_index().']" value="'.$event->get_nadpis().'">
            <span class="err">'.$event->get_nadpis_err().'</span>
          </div>
          
          <div class="editable">          
            <span class="popis">Text:</span>
            <textarea   class="event_text" cols="50" rows="10" wrap="soft" name="event['.Event::get_text_index().']">
              '.$event->get_text().'
            </textarea>
            <span class="err">'.$event->get_text_err().'</span>
          </div>
          
          <div class="editable">
            <span class="popis">Zobrazit Od:</span>
            <input type="text" maxlength="10" size="10" name="event['.Event::get_zobrazit_od_index().']" value="'.Util::timestamp_to_date($event->get_zobrazit_od()).'">
            <span class="err">'.$event->get_zobrazit_od_err().'</span>
          </div>

          <div class="editable">
            <span class="popis">Zobrazit Do:</span>
            <input type="text" maxlength="10" size="10" name="event['.Event::get_zobrazit_do_index().']" value="'.Util::timestamp_to_date($event->get_zobrazit_do()).'">
            <span class="err">'.$event->get_zobrazit_do_err().'</span>
          </div>

          <div class="editable">
            <span class="popis">Aktviní:</span>
            <input type="hidden" name="event['.Event::get_aktivni_index().']" value="0">
            <input type="checkbox" name="event['.Event::get_aktivni_index().']" '.$event->get_aktivni("checked","").'>    
            <span class="err">'.$event->get_aktivni_err().'</span>        
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="./events.php?list">Back to List</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="event['.Event::get_id_index().']" value="'.$event->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
      </fieldset> 
    ';  
    return $html;
  }

  // ------------------
  // --- EVENT EDIT ---
  // ------------------
  static function _edit($event, $valid_test = true)
  {
    if(Util::is_instance_of($event,"Event") == false) return "<h3 class=\"err\">Views::event_edit(\$event): promenna \$event musi byt instanci tridy Event!</h3>";
    if($valid_test)
    {
      $valid_test = $event->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
        
    $html = '
      <fieldset class="event'.$valid_test.'">
        <legend class="event">Edit</legend> 
        <form action="./events.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$event->get_id().'</span>
            <span class="err">'.$event->get_id_err().'</span> 
          </div>
        
          <div class="editable">
            <span class="popis">Nadpis:</span>
            <input type="text" maxlength="100" size="57" name="event['.Event::get_nadpis_index().']" value="'.$event->get_nadpis().'">
            <span class="err">'.$event->get_nadpis_err().'</span> 
          </div>
          
          <div class="editable">          
            <span class="popis">Text:</span>
            <textarea class="event_text" cols="50" rows="10" wrap="soft" name="event['.Event::get_text_index().']">
              '.$event->get_text().'
            </textarea>
            <span class="err">'.$event->get_text_err().'</span> 
          </div>
          
          <div class="editable">
            <span class="popis">Zobrazit Od:</span>
            <input type="text" maxlength="10" size="10" name="event['.Event::get_zobrazit_od_index().']" value="'.Util::timestamp_to_date($event->get_zobrazit_od()).'">
            <span class="err">'.$event->get_zobrazit_od_err().'</span> 
          </div>

          <div class="editable">
            <span class="popis">Zobrazit Do:</span>
            <input type="text" maxlength="10" size="10" name="event['.Event::get_zobrazit_do_index().']" value="'.Util::timestamp_to_date($event->get_zobrazit_do()).'">
            <span class="err">'.$event->get_zobrazit_do_err().'</span> 
          </div>

          <div class="editable">
            <span class="popis">Aktivní:</span>
            <input type="hidden" name="event['.Event::get_aktivni_index().']" value="0">
            <input type="checkbox" name="event['.Event::get_aktivni_index().']" '.$event->get_aktivni("checked","").'>
            <span class="err">'.$event->get_aktivni_err().'</span>             
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="event['.Event::get_id_index().']" value="'.$event->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
        <p>
          <a class="hodnota" href="./events.php?detail='.$event->get_id().'">Back to Detail</a> |  
          <a class="hodnota" href="./events.php?list">Back to List</a>
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }

  // --------------
  // --- DELETE ---
  // --------------
  static function _delete($event)
  {
    if(Util::is_instance_of($event,"Event") == false) return "<h3 class=\"err\">Views::event_delete(\$event): promenna \$event musi byt instanci tridy Event!</h3>";
    return '
        <h2 class="main">Delete</h2>
        <div class="delete">
          <p>
            <b>Opravdu chcete trvale odstranit událost č.'.$event->get_id().'?</b>
          </p>
          
          <form action="./events.php" enctype="multipart/form-data" method="post">
            <input type="hidden" name="delete" value="'.$event->get_id().'">
            <input type="submit" value="Delete">
          </form>
          <p>
            <a class="hodnota" href="./event.php?edit='.$event->get_id().'">Back to Edit</a> | 
            <a class="hodnota" href="./event.php?list">Back to List</a>
          </p>        
        </div>
    ';
  }
} // End Class

?>
