<?php

class LinkViews
{                                           

//  * ********************************************************************** *
//  *                            LINKS                                      *
//  * ********************************************************************** *

  // -------------------
  // --- LINKS LIST ---
  // -------------------  

  static function _list($list)
  {
    $html = '<h2 class="main">Link List</h2>';

    if(is_array($list) AND (count($list)>0))
    {    
      foreach($list as $item)
      {
        if(Util::is_instance_of($item,"Link"))
        {
          if(!$item->get_aktivni()) continue;
          // (<a class="no_decoration" href="./links.php?detail='.$item->get_id().'"> ...detail... </a>)
          if(trim($item->get_popis())=="")
					{
						$popis = "";
					}
					else
					{
						$popis= '<span class="popis">(i) '.$item->get_popis().'</span>';
					}
          $html .= '<div>
                      <span class="popis"><a href="'.$item->get_addr().'" target="_blank">'.$item->get_name(40).'</a></span><br>
                      '.$popis.'
                    </div>';
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
  // --- DETAIL LINKS LIST ---
  // --------------------------  

  static function _detail_list($rw, $list)
  {
    $html = '<h2 class="main">Detail List</h2>';

    if(is_array($list) AND (count($list)>0))
    {    
      foreach($list as $item)
      {
        if(Util::is_instance_of($item,"Link"))
        {
          $html .= '<fieldset class="link_list_item'.$item->get_aktivni("","_disable").'">
                      <legend>Link č.'.$item->get_id().'</legend>
                      <span class="popis">Id:</span><span class="hodnota">'.$item->get_id().'</span><br>
                      <span class="popis">Name:</span><span class="hodnota">'.$item->get_name(40).'</span><br>
                      <span class="popis">Popis:</span>
                      <div class="link_popis">'.Util::decode_link($item->get_popis()).'</div>
                      <span class="popis">Aktivní:</span><span class="hodnota">'.$item->get_aktivni("Ano","Ne").'</span><br>
          ';
          $html .= '  
                      <p>
                        <a class="hodnota" href="./links.php?detail='.$item->get_id().'">Detail</a>';
          if($rw) $html.='                         
                         | <a class="hodnota" href="./links.php?disable='.$item->get_id().'">'.$item->get_aktivni("Disable","Enable").'</a>';
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

  // --------------------
  // --- LINK DETAIL ---
  // --------------------
  static function _detail($rw, $link)
  {
    if(Util::is_instance_of($link,"Link") == false) return "<h3 class=\"err\">Views::link_detail(\$link): promenna \$link musi byt instanci tridy Link!</h3>";
    $html = '
    
      <fieldset class="link">
        <legend class="link">Detail</legend> 
        <div class="editable">
          <span class="popis">Id:</span><span class="hodnota">'.$link->get_id().'</span>
        </div>
      
        <div class="editable">
          <span class="popis">Name:</span><br><span class="hodnota">'.$link->get_name().'</span>
        </div>
        
        <div class="editable">
          <span class="popis">Addr:</span><br><span class="hodnota">'.$link->get_addr().'</span>
        </div>

        <div class="editable">          
          <span class="popis">Popis:</span>
          <div class="link_popis">
            '.Util::decode_link($link->get_popis()).'
          </div>
        </div>

        <div class="editable">
          <span class="popis">Aktivní:</span>            
          <span class="hodnota">'.$link->get_aktivni("Ano","Ne").'</span>            
        </div>    

        <p>';
        if($rw) $html.='  
          <a class="hodnota" href="./links.php?edit='.$link->get_id().'">Edit</a> |  
          <a class="hodnota" href="./links.php?delete='.$link->get_id().'">Delete</a> | ';
        $html.='            
          <a class="hodnota" href="./links.php?list">Back to List</a>
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }
  
  // --------------------
  // --- LINK CREATE ---
  // --------------------
  static function _create($link, $valid_test = true)
  {
    if(Util::is_instance_of($link,"Link") == false) return "<h3 class=\"err\">Views::link_create(\$link): promenna \$link musi byt instanci tridy Link!</h3>";

    if($valid_test)
    {
      $valid_test = $link->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }

    $html = '
      <fieldset class="link'.$valid_test.'">
        <legend class="link">Create</legend> 
        <form action="./links.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$link->get_id().'</span>
            <span class="err">'.$link->get_id_err().'</span>
          </div>
        
          <div class="editable">
            <span class="popis">Name:</span>
            <input type="text" maxlength="100" size="57" name="link['.Link::get_name_index().']" value="'.$link->get_name().'">
            <span class="err">'.$link->get_name_err().'</span>
          </div>
          
          <div class="editable">
            <span class="popis">Address:</span>
            <input type="text" maxlength="500" size="57" name="link['.Link::get_addr_index().']" value="'.$link->get_addr().'">
            <span class="err">'.$link->get_addr_err().'</span>
          </div>

          <div class="editable">          
            <span class="popis">Popis:</span>
            <textarea   class="link_popis" cols="50" rows="10" wrap="soft" name="link['.Link::get_popis_index().']">
              '.$link->get_popis().'
            </textarea>
            <span class="err">'.$link->get_popis_err().'</span>
          </div>
          
          <div class="editable">
            <span class="popis">Aktivní:</span>
            <input type="hidden" name="link['.Link::get_aktivni_index().']" value="0">
            <input type="checkbox" name="link['.Link::get_aktivni_index().']" '.$link->get_aktivni("checked","").'>    
            <span class="err">'.$link->get_aktivni_err().'</span>        
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <a class="hodnota" href="./links.php?list">Back to List</a>
            <input type="hidden" name="create" value="">
            <input type="hidden" name="link['.Link::get_id_index().']" value="'.$link->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
      </fieldset> 
    ';  
    return $html;
  }

  // ------------------
  // --- LINK EDIT ---
  // ------------------
  static function _edit($link, $valid_test = true)
  {
    if(Util::is_instance_of($link,"Link") == false) return "<h3 class=\"err\">Views::link_edit(\$link): promenna \$link musi byt instanci tridy Link!</h3>";
    if($valid_test)
    {
      $valid_test = $link->is_valid("","_err");
    }
    else
    {
      $valid_test = "";
    }
        
    $html = '
      <fieldset class="link'.$valid_test.'">
        <legend class="link">Edit</legend> 
        <form action="./links.php" enctype="multipart/form-data" method="post">
          <div class="editable">
            <span class="popis">Id:</span><span class="hodnota">'.$link->get_id().'</span>
            <span class="err">'.$link->get_id_err().'</span> 
          </div>
        
          <div class="editable">
            <span class="popis">Name:</span>
            <input type="text" maxlength="100" size="57" name="link['.Link::get_name_index().']" value="'.$link->get_name().'">
            <span class="err">'.$link->get_name_err().'</span> 
          </div>
          
          <div class="editable">
            <span class="popis">Addr:</span>
            <input type="text" maxlength="100" size="57" name="link['.Link::get_addr_index().']" value="'.$link->get_addr().'">
            <span class="err">'.$link->get_addr_err().'</span> 
          </div>

          <div class="editable">          
            <span class="popis">Popis:</span>
            <textarea class="link_popis" cols="50" rows="10" wrap="soft" name="link['.Link::get_popis_index().']">
              '.$link->get_popis().'
            </textarea>
            <span class="err">'.$link->get_popis_err().'</span> 
          </div>
          
          <div class="editable">
            <span class="popis">Aktivní:</span>
            <input type="hidden" name="link['.Link::get_aktivni_index().']" value="0">
            <input type="checkbox" name="link['.Link::get_aktivni_index().']" '.$link->get_aktivni("checked","").'>
            <span class="err">'.$link->get_aktivni_err().'</span>             
          </div>    

          <div class="tlacitka">
            <input type="submit" name="save" value="Uložit">
            <input type="hidden" name="edit" value="">
            <input type="hidden" name="link['.Link::get_id_index().']" value="'.$link->get_id().'">
            <input type="hidden" name="token" value="'.Util::get_token().'">
          </div>            
        </form>
        <p>
          <a class="hodnota" href="./links.php?detail='.$link->get_id().'">Back to Detail</a> |  
          <a class="hodnota" href="./links.php?list">Back to List</a>
        </p>
        
      </fieldset> 
    ';  
    return $html;
  }

  // --------------
  // --- LINK DELETE ---
  // --------------
  static function _delete($link)
  {
    if(Util::is_instance_of($link,"Link") == false) return "<h3 class=\"err\">Views::link_delete(\$link): promenna \$link musi byt instanci tridy Link!</h3>";
    return '
        <h2 class="main">Delete</h2>
        <div class="delete">
          <p>
            <b>Opravdu chcete trvale odstranit odkaz č.'.$link->get_id().'?</b>
          </p>
          
          <form action="./links.php" enctype="multipart/form-data" method="post">
            <input type="hidden" name="delete" value="'.$link->get_id().'">
            <input type="submit" value="Delete">
          </form>
          <p>
            <a class="hodnota" href="./link.php?edit='.$link->get_id().'">Back to Edit</a> | 
            <a class="hodnota" href="./link.php?list">Back to List</a>
          </p>        
        </div>
    ';
  }
} // End Class

?>
