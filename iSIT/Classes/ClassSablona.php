<?php

class Sablona
{

  private $dir_root;
  private $dir_name;
  private $file_name;
  private $last_change;
  private $title;
  private $rep;
  
  public function __construct($file_name, $dir_root = "./", $dir_name = "")
  {
  
    if(!File_Exists($file_name)) die("Sablona->__construct: Soubor $file_name neesistuje!");
    
    $this->dir_root = $dir_root;
    $this->dir_name = $dir_name;
    $this->file_name = $file_name;
    $this->last_change = date("d. m. Y G:i", filemtime($this->file_name));
    //$this->title = "iSiT by BQ ".$this->file_name."(".$this->last_change.")";
    $this->title = "iSiT [".substr($this->file_name,-(strlen($this->file_name)),strlen($this->file_name)-4)."]";
    $this->rep = new Repository("./");
    
  }
  
  public function get_html($obsah = "", $submenu = "", $analytic = "")
  {

  $menu = new Menu($this->file_name);
  $menu->add_item(new SimpleLink("Home","./index.php"));
  $menu->add_item(new SimpleLink("Notes","./events.php"));
  $menu->add_item(new SimpleLink("Locations","./locations.php"));
  $menu->add_item(new SimpleLink("Persons","./persons.php"));
  $menu->add_item(new SimpleLink("Computers","./computers.php"));
  $menu->add_item(new SimpleLink("Printers","./printers.php"));
  $menu->add_item(new SimpleLink("Links","./links.php"));  
  $menu->add_item(new SimpleLink("|",""));
  $menu->add_item(new SimpleLink("Utils","./utils.php"));
  $menu->add_item(new SimpleLink("|",""));  
  $menu->add_item(new SimpleLink("About","./about.php"));
  

    return '
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
        <html>

          <head>
            <meta http-equiv="Content-Language" content="cs">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            
            <meta http-equiv="cache-control" content="no-cache">
            <meta http-equiv="pragma" content="no-cache">
            <meta http-equiv="expires" content="0">
            
            <link rel="stylesheet" type="text/css" media="all" href="'.$this->dir_root.'/style/styl.css">
            <link rel="stylesheet" type="text/css" media="print" href="'.$this->dir_root.'/style/styl_printer.css">
            <title>'.$this->title.'</title>
          </head>
          
          <body class="vse">
            <h1 class="main">iSiT(v4.2 db:'.$this->rep->get_isit_db().') by BQ</h1>
              <div class="main_menu">
                '.$menu->get_html().$submenu.'      
              </div>
              <div class="main_obsah">
                '.$obsah.'
                '.$analytic.'
              </div>
        </body>
      </html>
    ';
  }

  public function get_void_html($obsah, $css="")
  {
    if($css == "")
    {
      $styl_css = "";
    }
    else
    {
      $styl_css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->dir_root.'/style/'.$css.'">';
    }
    
    return '
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
        <html>

          <head>
            <meta http-equiv="Content-Language" content="cs">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            
            <meta http-equiv="cache-control" content="no-cache">
            <meta http-equiv="pragma" content="no-cache">
            <meta http-equiv="expires" content="0">
            '.$styl_css.'            
            <title>'.$this->title.'</title>
          </head>
          '.$obsah.'          
      </html>
    ';
  }

} // end Class

?>
