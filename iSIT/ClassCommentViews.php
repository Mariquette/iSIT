<?php

class CommentViews
{                                           

//  * ********************************************************************** *
//  *                            COMMENT                                      *
//  * ********************************************************************** *

  
  // ----------------------
  // --- COMMENT LIST ---
  // ----------------------  

  static function _list($list,$nadpis = "Seznam")
  {
    $rep = new Repository("./");
        
    $html = '<h2 class="main">'.$nadpis.'</h2>';

    if(is_array($list) AND (count($list)>0))
    {  
      $html.='<table cellpadding="5" cellspacing="0">
                <tr>
                  <th></th>
                  <th><span class="popis">Poznamka</span></th>
                </tr>
      ';  
      $db_valid="";
      $device_id_temp=0;
      $td_class="";
      foreach($list as $obj)
      {
              
        if(Util::is_instance_of($obj,"Comment"))
        { 
        
          $dev_inf = "";
          
          if($obj->get_device_folder()=="_computers")
          {
            if($pc = $rep->get_computer($obj->get_device_id()))
            {
              $dev_inf = '<a href="./computers.php?detail='.$obj->get_device_id().'">'.$pc->get_ldap_name("no_name").'</a>, '.$pc->get_seriove_cislo().', '.$pc->get_evidencni_cislo();
            }
            else
            {
              $dev_inf = "!! rep_get_err !!";
            }            
          }
          if($obj->get_device_folder()=="_printers")
          {
            if($print = $rep->get_printer($obj->get_device_id()))
            {
              $dev_inf = '<a href="./printers.php?detail='.$obj->get_device_id().'">'.$print->get_model().'</a>, '.$print->get_ip().', '.$print->get_evidencni_cislo();
            }
            else
            {
              $dev_inf = "!! rep_get_err !!";
            }            
          }
          if($obj->get_device_folder()=="_persons")
          {
            if($pers = $rep->get_person($obj->get_device_id()))
            {
              $dev_inf = '<a href="./persons.php?detail='.$obj->get_device_id().'">'.$pers->get_full_name().'</a>, '.$pers->get_login().', '.$pers->get_osobni_cislo();
            }
            else
            {
              $dev_inf = "!! rep_get_err !!";
            }                      
          }
        
          
          
          if($device_id_temp != $obj->get_device_id())
          {
            $device_id_temp=$obj->get_device_id();
            if($td_class=="")
            {
              $td_class="shadow";
            }
            else
            {
              $td_class="";
            }            
          }
          else
          {
            $dev_inf="";
          }
          
          //$td_class=$device_id_temp."?".$obj->get_device_id();
          
          $html .= '<tr class="comment'.$db_valid.'">
                      <td class="'.$td_class.'">'.$dev_inf.'</td>
                      <td class="'.$td_class.'"><span class="hodnota">'.$obj->get_poznamka().'</span></td>';
          $html.='  </tr>';
        }  
      }
      $html.="</table> <hr>";
    }
    else
    {
      $html.=Views::informace("Seznam je prázdný.");
    }
    return $html;
  }

  // --------------------
  // --- COMMENT CREATE ---
  // --------------------
  static function _create($comment, $script, $valid_test=true)
  {
    if(Util::is_instance_of($comment,"Comment") == false) return "<h3 class=\"err\">Views::comment_create(\$comment,\$script): promenna \$comment musi byt instanci tridy Comment!</h3>";

    if($valid_test)
    {
      $valid_test = $comment->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }

    $html = '
      <fieldset class="comment'.$valid_test.'">
        <legend class="comment">Create</legend> 
        <form action="./'.$script.'" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$comment->get_id().'</span>
            <span class="err">'.$comment->get_id_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Device Id:</span><span class="hodnota">'.$comment->get_device_id().'</span>
            <span class="err">'.$comment->get_device_id_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Device Folder:</span><span class="hodnota">'.$comment->get_device_folder().'</span>
            <span class="err">'.$comment->get_device_folder_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Poznámka:</span>
            <input type="text" maxlength="500" size="70" name="comment['.Comment::get_poznamka_index().']" value="'.$comment->get_poznamka().'">
            <span class="err">'.$comment->get_poznamka_err().'</span>
          </div>
          
          
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="checkbox" name="comment['.Comment::get_aktivni_index().']" '.$comment->get_aktivni("checked","").'>    
            <span class="err">'.$comment->get_aktivni_err().'</span>        
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="'.$script.'?detail='.$comment->get_device_id().'">Back to Detail</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="comment['.Comment::get_id_index().']" value="'.$comment->get_id().'">
            <input type="hidden" name="comment['.Comment::get_device_id_index().']" value="'.$comment->get_device_id().'">
            <input type="hidden" name="comment['.Comment::get_device_folder_index().']" value="'.$comment->get_device_folder().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>

      </fieldset> 
    ';  
    return $html;
  }

  // ------------------
  // --- COMMENT EDIT ---
  // ------------------
  static function _edit($comment, $script, $valid_test = true)
  {
    if(Util::is_instance_of($comment,"Comment") == false) return "<h3 class=\"err\">Views::comment_edit(\$comment): promenna \$comment musi byt instanci tridy Comment!</h3>";
    if($valid_test)
    {
      $valid_test = $comment->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
        
    $html = '
      <fieldset class="comment'.$valid_test.'">
        <legend class="comment">Edit</legend> 
        <form action="'.$script.'" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$comment->get_id().'</span>
            <span class="err">'.$comment->get_id_err().'</span> 
          </div>
        
          <div class="editable">
            <span class="popis">Device Id:</span><span class="hodnota">'.$comment->get_device_id().'</span>
            <span class="err">'.$comment->get_device_id_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Device Folder:</span><span class="hodnota">'.$comment->get_device_folder().'</span>
            <span class="err">'.$comment->get_device_folder_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Poznámka:</span>
            <input type="text" maxlength="500" size="70" name="comment['.Comment::get_poznamka_index().']" value="'.$comment->get_poznamka().'">
            <span class="err">'.$comment->get_poznamka_err().'</span>
          </div>
          
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="checkbox" name="comment['.Comment::get_aktivni_index().']" '.$comment->get_aktivni("checked","").'>
            <span class="err">'.$comment->get_aktivni_err().'</span>             
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="comment['.Comment::get_id_index().']" value="'.$comment->get_id().'">
            <input type="hidden" name="comment['.Comment::get_device_id_index().']" value="'.$comment->get_device_id().'">
            <input type="hidden" name="comment['.Comment::get_device_folder_index().']" value="'.$comment->get_device_folder().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
        <p>
          <a class="hodnota" href="'.$script.'?detail='.$comment->get_device_id().'">Back to Detail</a> |  
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }

  // --------------
  // --- DELETE ---
  // --------------
  static function _delete($comment)
  {
    if(Util::is_instance_of($comment,"Comment") == false) return "<h3 class=\"err\">Views::comment_delete(\$comment): promenna \$comment musi byt instanci tridy Comment!</h3>";
    return '
        <h2 class="main">Delete</h2>
        <div class="delete">
          <p>
            <b>Opravdu chcete trvale odstranit poznámku č.'.$comment->get_id().'?</b>
          </p>
          
          <form action="./comments.php" enctype="multipart/form-data" method="post">
            <input type="hidden" name="delete" value="'.$comment->get_id().'">
            <input type="submit" value="Delete">
          </form>
          <p>
            <a class="hodnota" href="./comment.php?edit='.$comment->get_id().'">Back to Edit</a> | 
            <a class="hodnota" href="./comment.php?list">Back to List</a>
          </p>        
        </div>
    ';
  }
  
} // End Class

?>
