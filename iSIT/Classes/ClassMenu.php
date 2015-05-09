<?php

class Menu
{
  
  private $items;
  private $item_styl;
  private $file_name;
  private $menu_styl;
  private $submenu;
                            
  public function __construct($file_name, $menu_styl="menu")
  {
    $this->set_file_name($file_name);
    $this->submenu = false;
    $this->menu_styl = $menu_styl;
  }

  public function set_file_name($file_name)
  {
    $this->file_name = $file_name;             
  }
  public function set_submenu_file_name($file_name)
  {
    if($this->submenu)
    {
      $this->submenu->set_file_name($file_name);
    }
  }
  
  public function set_submenu($submenu)
  {
    if(Util::is_instance_of($submenu,"Menu") == false) die("Menu->set_submenu(\$submenu): parametr \$submenu musi byt instanci tridy Menu!");
    $this->submenu = $submenu;
  }
  
  public function add_item($item, $item_styl="nevyplneno")
  {
    if($item_styl == "nevyplneno") $item_styl = $this->menu_styl;
    if(Util::is_instance_of($item,"SimpleLink") == false) die("Menu->add_item(\$item): parametr \$item musi byt instanci tridy SimpleLink!");
    if(is_array($this->items))
    {
      foreach($this->items as $i)
      {
        if($i->is_empty())continue;
        if($i->get_name() == $item->get_name()) die("Menu->add_item(\$item): odkaz musi mit jedinecny nazev!(".$i->get_name().")"); 
      }
      /*
      foreach($this->items as $i)
      {
        if(!$i->is_valid()) die("Menu->add_item(\$item): \$item->addr obsahuje adresu na neexistujici soubor!(".$i->get_addr().")"); 
      }
      */
    }
    $this->items[] = $item;
    $this->item_styl[$item->get_name()] = $item_styl;
  } 
    
  public function get_html()
  {
  
    $html = '<div class="'.$this->menu_styl.'">';
    if($this->submenu) $html = '<div class="'.$this->menu_styl.'_sub">';
            
    foreach($this->items as $item)
    {
      $aktivni = "";
      if(Util::je_aktivni($item->get_addr(),$this->file_name)) $aktivni = "Aktivni";
      
      if($item->is_empty())
      {
        $html.='<span class="'.$this->item_styl[$item->get_name()].'">'.$item->get_name().'</span> ';
      }
      else
      {
        $html.='<a class="'.$this->item_styl[$item->get_name()].$aktivni.'" href="'.$item->get_addr().'">'.$item->get_name().'</a> ';      
      }                     
    }
    
    if($this->submenu)
    {
      $html.=$this->submenu->get_html();
    }
    
    $html.= '</div>';
    
    return $html;
  }

} // end Class

?>
