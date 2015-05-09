<?php

class Sablona
{

  private $dir_root;
  private $dir_name;
  private $file_name;
  private $last_change;
  private $title;
  
  public function __construct($file_name, $dir_root = "./", $dir_name = "")
  {
  
    if(!File_Exists($file_name)) die("Sablona->__construct: Soubor $file_name neesistuje!");
    
    $this->dir_root = $dir_root;
    $this->dir_name = $dir_name;
    $this->file_name = $file_name;
    $this->last_change = date("d. m. Y G:i", filemtime($this->file_name));
    //$this->title = "iSiT by BQ ".$this->file_name."(".$this->last_change.")";
    $this->title = "iSiT [".substr($this->file_name,-(strlen($this->file_name)),strlen($this->file_name)-4)."]";
  }
  
  public function get_html($obsah = "", $menu = "", $analytic = "")
  {
    return '
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
        <html>

          <head>
            <meta http-equiv="Content-Language" content="cs">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            
            <meta http-equiv="cache-control" content="no-cache">
            <meta http-equiv="pragma" content="no-cache">
            <meta http-equiv="expires" content="0">
            
            <link rel="stylesheet" type="text/css" media="all" href="'.$this->dir_root.'styl.css">
            <link rel="stylesheet" type="text/css" media="print" href="'.$this->dir_root.'styl_printer.css">
            <title>'.$this->title.'</title>
          </head>
          
          <body class="vse">
            <h1 class="main">iSiT(v3.2) by BQ</h1>
              <div class="main_menu">
                '.$menu.'      
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
      $styl_css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->dir_root.$css.'">';
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
