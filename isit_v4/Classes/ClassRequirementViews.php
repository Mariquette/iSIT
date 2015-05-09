<?php

class RequirementViews
{                                           

//  * ********************************************************************** *
//  *                            REQUIREMENT                                      *
//  * ********************************************************************** *

  
  // ----------------------
  // --- REQUIREMENT LIST ---
  // ----------------------  

  static function _list($list,$nadpis = "Seznam")
  {
    $rep = new Repository("./");
        
    $html = '<h2 class="main">'.$nadpis.'</h2>';

    if(is_array($list) AND (count($list)>0))
    {  
      $html.='<table cellpadding="5">
                <tr>
                  <th><span class="popis"></span></th>
                  <th><span class="popis">Id</span></th>
                  <th><span class="popis">obj_folder</span></th>
                  <th><span class="popis">obj_id</span></th>
                  <th><span class="popis">Poznamka</span></th>
                  <th></th>
                  <th>file</th>
                </tr>
      ';  
      $db_valid="";
      foreach($list as $obj)
      {
              
        if(Util::is_instance_of($obj,"Requirement"))
        { 
        
          $dev_inf = "";
          
          if($obj->get_obj_folder()=="_computers")
          {
            if($pc = $rep->get_computer($obj->get_obj_id()))
            {
              $dev_inf = '<a href="./computers.php?detail='.$obj->get_obj_id().'">'.$pc->get_ldap_name("no_name").'</a>, '.$pc->get_seriove_cislo().', '.$pc->get_evidencni_cislo();
            }
            else
            {
              $dev_inf = "!! rep_get_err !!";
            }            
          }
          if($obj->get_obj_folder()=="_printers")
          {
            if($print = $rep->get_printer($obj->get_obj_id()))
            {
              $dev_inf = '<a href="./printers.php?detail='.$obj->get_obj_id().'">'.$print->get_model().'</a>, '.$print->get_ip().', '.$print->get_evidencni_cislo();
            }
            else
            {
              $dev_inf = "!! rep_get_err !!";
            }            
          }
          if($obj->get_obj_folder()=="_persons")
          {
            if($pers = $rep->get_person($obj->get_obj_id()))
            {
              $dev_inf = '<a href="./persons.php?detail='.$obj->get_obj_id().'">'.$pers->get_full_name().'</a>, '.$pers->get_login().', '.$pers->get_osobni_cislo();
            }
            else
            {
              $dev_inf = "!! rep_get_err !!";
            }                      
          }
        
          $html .= '<tr class="requirement'.$db_valid.'">
                      <td></td>
                      <td><span class="hodnota">'.$obj->get_id().'</span></td>
                      <td><span class="hodnota">'.$obj->get_obj_folder().'</span></td>
                      <td><span class="hodnota">'.$obj->get_obj_id().'</span></td>
                      <td><span class="hodnota">'.$obj->get_poznamka().'</span></td>
                      <td>'.$dev_inf.'</td>
                      <td><a href="./__pdf/pozadavky/'.$obj->get_id().'_Requirement.pdf" target="_blank" title="Požadavek HW/SW fe formátu .pdf"><img class="icon" src="./icon-pdf.jpg" alt="požadavek HW/SW, formát pdf"></a></td>
                      '
                      
                      ;
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
  // --- REQUIREMENT CREATE ---
  // --------------------
  static function _create($requirement, $script, $valid_test=true)
  {
    if(Util::is_instance_of($requirement,"Requirement") == false) return "<h3 class=\"err\">Views::requirement_create(\$requirement,\$script): promenna \$requirement musi byt instanci tridy Requirement!</h3>";

    if($valid_test)
    {
      $valid_test = $requirement->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }

    $html = '
      <fieldset class="requirement'.$valid_test.'">
        <legend class="requirement">Create</legend> 
        <form action="./'.$script.'" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$requirement->get_id().'</span>
            <span class="err">'.$requirement->get_id_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Object Id:</span><span class="hodnota">'.$requirement->get_obj_id().'</span>
            <span class="err">'.$requirement->get_obj_id_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Object Folder:</span><span class="hodnota">'.$requirement->get_obj_folder().'</span>
            <span class="err">'.$requirement->get_obj_folder_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Poznámka:</span>
            <input type="text" maxlength="500" size="70" name="requirement['.Requirement::get_poznamka_index().']" value="'.$requirement->get_poznamka().'">
            <span class="err">'.$requirement->get_poznamka_err().'</span>
          </div>
          
          
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="checkbox" name="requirement['.Requirement::get_aktivni_index().']" '.$requirement->get_aktivni("checked","").'>    
            <span class="err">'.$requirement->get_aktivni_err().'</span>        
          </div>    
          
          <div class="editable">          
            <input type="file" name="upload_file">
            <input type="hidden" name="folder" value="pozadavky">
          </div>

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="'.$script.'?detail='.$requirement->get_obj_id().'">Back to Detail</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="requirement['.Requirement::get_id_index().']" value="'.$requirement->get_id().'">
            <input type="hidden" name="requirement['.Requirement::get_obj_id_index().']" value="'.$requirement->get_obj_id().'">
            <input type="hidden" name="requirement['.Requirement::get_obj_folder_index().']" value="'.$requirement->get_obj_folder().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>

      </fieldset> 
    ';  
    return $html;
  }

  // ------------------
  // --- REQUIREMENT EDIT ---
  // ------------------
  static function _edit($requirement, $script, $valid_test = true)
  {
    if(Util::is_instance_of($requirement,"Requirement") == false) return "<h3 class=\"err\">Views::requirement_edit(\$requirement): promenna \$requirement musi byt instanci tridy Requirement!</h3>";
    if($valid_test)
    {
      $valid_test = $requirement->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
        
    $html = '
      <fieldset class="requirement'.$valid_test.'">
        <legend class="requirement">Edit</legend> 
        <form action="'.$script.'" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$requirement->get_id().'</span>
            <span class="err">'.$requirement->get_id_err().'</span> 
          </div>
        
          <div class="editable">
            <span class="popis">Device Id:</span><span class="hodnota">'.$requirement->get_obj_id().'</span>
            <span class="err">'.$requirement->get_obj_id_err().'</span>
          </div>
          <div class="editable">
            <span class="popis">Device Folder:</span><span class="hodnota">'.$requirement->get_obj_folder().'</span>
            <span class="err">'.$requirement->get_obj_folder_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Poznámka:</span>
            <input type="text" maxlength="500" size="70" name="requirement['.Requirement::get_poznamka_index().']" value="'.$requirement->get_poznamka().'">
            <span class="err">'.$requirement->get_poznamka_err().'</span>
          </div>
          
          <div class="editable">
            <span class="popis">Aktivni:</span>
            <input type="checkbox" name="requirement['.Requirement::get_aktivni_index().']" '.$requirement->get_aktivni("checked","").'>
            <span class="err">'.$requirement->get_aktivni_err().'</span>             
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="requirement['.Requirement::get_id_index().']" value="'.$requirement->get_id().'">
            <input type="hidden" name="requirement['.Requirement::get_obj_id_index().']" value="'.$requirement->get_obj_id().'">
            <input type="hidden" name="requirement['.Requirement::get_obj_folder_index().']" value="'.$requirement->get_obj_folder().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
        <p>
          <a class="hodnota" href="'.$script.'?detail='.$requirement->get_obj_id().'">Back to Detail</a> |  
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }

  // --------------
  // --- DELETE ---
  // --------------
  static function _delete($requirement)
  {
    if(Util::is_instance_of($requirement,"Requirement") == false) return "<h3 class=\"err\">Views::requirement_delete(\$requirement): promenna \$requirement musi byt instanci tridy Requirement!</h3>";
    return '
        <h2 class="main">Delete</h2>
        <div class="delete">
          <p>
            <b>Opravdu chcete trvale odstranit poznámku č.'.$requirement->get_id().'?</b>
          </p>
          
          <form action="./requirements.php" enctype="multipart/form-data" method="post">
            <input type="hidden" name="delete" value="'.$requirement->get_id().'">
            <input type="submit" value="Delete">
          </form>
          <p>
            <a class="hodnota" href="./requirement.php?edit='.$requirement->get_id().'">Back to Edit</a> | 
            <a class="hodnota" href="./requirement.php?list">Back to List</a>
          </p>        
        </div>
    ';
  }
  
} // End Class

?>
