<?php
/**
 *  Class Requirement
 *  - pre model
 */    
 
class Requirement
{

  private static $rep;    // staticka repository
   
  private $id;
  private $obj_id;  
  private $obj_folder;
  private $poznamka;
  private $aktivni;
   
  private $id_err;
  private $obj_id_err;
  private $obj_folder_err;
  private $poznamka_err;
  private $aktivni_err;
    
  static function get_folder()
  {
    return "_requirements";
  }
  static function get_db_name()
  {
    return "requirements";
  }
    
  static function get_id_index(){ return 0; }
  static function get_obj_id_index(){ return 1; }
  static function get_obj_folder_index(){ return 2; }  
  static function get_poznamka_index(){ return 3; }
  static function get_aktivni_index(){ return 4; }
  
  static function get_all_index_names($separator=",")
  {
    return "'ID'".$separator."'OBJ_ID'".$separator."'OBJ_FOLDER'".$separator."'POZNAMKA'".$separator."'AKTIVNI'";
  }    
  
  // konstruktor prazdny a pretizeny polem parametru
  public function __construct($array = false)
  {
  
    if(self::$rep === NULL)
    {
      self::$rep = new Repository("./");
    }
  
    $this->id_err="";
    $this->obj_id_err="";
    $this->obj_folder_err="";
    $this->poznamka_err="";
    $this->aktivni_err="";
       
    if($array == false)
    {
      // clear link
      if($this->clear()) return true;
    }        
    if(is_array($array))
    {
      // obj from array
      if($this->load($array)) return true;
    }
        
    die("Requirement->__construct(\$array=false): Nespravny vstupni prarametr \$array!");        

  }


//  ------------------
//  ---- SETTERS -----
//  ------------------

    public function set_id($id)
    {
      $this->id = $id;
    }
    public function set_obj_id($obj_id)
    {
      $this->obj_id = $obj_id;
    }
    public function set_obj_folder($obj_folder)
    {
      $this->obj_folder = trim(strtolower($obj_folder));
    }
    public function set_poznamka($poznamka)
    {
      $this->poznamka = $poznamka;
    }
    public function set_aktivni($value)
    {
      $this->aktivni = $value;
    }

//  ------------------
//  ---- GETTERS -----
//  ------------------

    public function get_id()
    {
      return $this->id;
    }

    public function get_obj_id()
    {
      return $this->obj_id;
    }
    
    public function get_obj_folder()
    {
      return $this->obj_folder;
    }
    
    public function get_poznamka($znaku=0)
    {
      $text = $this->poznamka;
      if($znaku > 0)
      {
        if(strlen($text)>$znaku)
        {
          $text = substr($text,0,$znaku);
          $text .= "...";
        }
      }
      return $text;
    }    
    public function get_aktivni($ano="1",$ne="0")
    {
      if($this->aktivni) return $ano;
      return $ne;
    }
  
  // -- GET ERR -- //

    public function get_id_err(){ return $this->id_err; }
    public function get_obj_id_err(){ return $this->obj_id_err; }
    public function get_obj_folder_err(){ return $this->obj_folder_err; }
    public function get_poznamka_err(){ return $this->poznamka_err; }
    public function get_aktivni_err(){return $this->aktivni_err;}
  
//  ------------------
//  ---- GENERAL -----
//  ------------------

  public function clear()
  {
    $this->id = 0;
    $this->obj_id = "";
    $this->poznamka = "";
    $this->obj_folder = "";
    $this->aktivni = true;
    return true;
  }

  public function load($array)
  {
    if(!is_array($array)) die("Requirement::load(\$array): parametr \$array musi byt typu pole.<br>");
    if(count($array)!=5) die("Requirement::load(\$array): nespravny pocet prvku pole(".count($array).").<br>");
    
    if(!isset($array[Requirement::get_id_index()])) die("Requirement::load(\$array): v poli \$array neexistuje klic \"".$array[Requirement::get_id_index()]."\".<br>");
    $this->set_id($array[Requirement::get_id_index()]);

    if(!isset($array[Requirement::get_obj_id_index()])) die("Requirement::load(\$array): v poli \$array neexistuje klic \"".$array[Requirement::get_obj_id_index()]."\".<br>");
    $this->set_obj_id($array[Requirement::get_obj_id_index()]);

    if(!isset($array[Requirement::get_obj_folder_index()])) die("Requirement::load(\$array): v poli \$array neexistuje klic \"".$array[Requirement::get_obj_folder_index()]."\".<br>");
    $this->set_obj_folder($array[Requirement::get_obj_folder_index()]);

    if(!isset($array[Requirement::get_poznamka_index()])) die("Requirement::load(\$array): v poli \$array neexistuje klic \"".$array[Requirement::get_poznamka_index()]."\".<br>");
    $this->set_poznamka($array[Requirement::get_poznamka_index()]);

    if(!isset($array[Requirement::get_aktivni_index()])) die("Requirement::load(\$array): v poli \$array neexistuje klic \"".$array[Requirement::get_aktivni_index()]."\".<br>");
    $this->set_aktivni($array[Requirement::get_aktivni_index()]);
    
    return true;     
  }
  
  // vraci retezec pseudo xml string sestaveny z parametru objektu
  public function to_dat_string($separator="</>")
  {
    $string = "";
    if(is_array($pole = $this->to_array()))
    {    
      for($i=0; $i<count($pole); $i++)
      {
        $string .= $pole[$i].$separator;
      }    
    }      
    $string = substr($string, 0, -(strlen($separator)));
    //echo "Requirement§->to_dat_string():$string<br>";
    return $string; 
  }
  
  // vraci pole polozek objektu  
  public function to_array()
  {
    $array[Requirement::get_id_index()] = $this->id;
    $array[Requirement::get_obj_id_index()] = $this->obj_id;
    $array[Requirement::get_obj_folder_index()] = $this->obj_folder;
    $array[Requirement::get_poznamka_index()] = $this->poznamka;
    $array[Requirement::get_aktivni_index()] = ($this->aktivni != false ? 1 : 0); //$this->aktivni;
    //echo "count array requirement=".count($array);
    return $array;
  }

  // vraci string formatovany pro rychle zobrazeni polozek objektu
  public function to_html_string()
  {
    return "id => ".$this->id." <br>
            obj_id => ".$this->obj_id." <br>
            obj_folder => ".$this->obj_folder." <br>
            poznamka => ".$this->poznamka." <br>
            aktivni => ".$this->aktivni." <br>
            <hr>";
  }

  public function is_enable($true=true,$false=false)
  {
    $rep = self::$rep;
    //return true;
    //if($rep->is_pdf($this,"vyrazovaky")) return false;
  
    if($this->aktivni == true) return $true;
    return $false;
  }
  
  // zjisti zda vsechny parametry splnuji kriteria
  public function is_valid($true=true, $false=false)
  {
    $return = $true;
    if(!Test::is_number($this->id))
    {
      $return = $false;
      $this->id_err = "Povolené znaky pro parametr \"id\" jsou číslice 0-9.";
    } 
    if(!Test::is_number($this->obj_id))
    {
      $return = $false;
      $this->obj_id_err = "Povolené znaky pro parametr \"obj_id\" jsou číslice 0-9.";
    } 
    if((!Test::is_text($this->obj_folder,100))OR($this->obj_folder==""))
    {
      $return = $false;
      $this->obj_folder_err = "Parametr \"obj_folder\" má omezenou délku textu na 100 znaků.";
    } 
    if((!Test::is_text($this->poznamka,500))AND($this->poznamka!=""))
    {
      $return = $false;
      $this->poznamka_err = "Parametr \"poznamka\" má omezenou délku textu na 500 znaků.";
    } 
    if(!Test::is_bool($this->aktivni))
    {
      $return = $false;
      $this->aktivni_err = "Povolené hodnoty parametru \"Aktivni\" jsou [0,1,true,false].";
    } 
        
    $rep = self::$rep;
    
    // existuje obj_folder   
    if(!$rep->is_device_folder($this->get_obj_folder()))
    {
      $return = $false;
      $this->obj_folder_err = "Parametr \"obj_folder\" = \"".$this->get_obj_folder()."\" neexistuje.";
    }

    // existuje zarizeni   
    if($rep->is_obj($this->get_obj_folder(),$this->get_obj_id())==false)
    {
      $return = $false;
      $this->obj_id_err = "Parametr \"obj_id\" obsahuje neplatné ID \"".$this->get_obj_id()."\".";
    }
    
    return $return;
  }
    
//  ------------------
//  ---- SPECIAL -----
//  ------------------


  public function get_all_err()
  {
    $out = "<ul class=\"err\">";    
      if($this->get_id_err()!="") $out .= "<li>".$this->get_id_err()."</li>";
      if($this->get_obj_id_err()!="") $out .="<li>".$this->get_obj_id_err()."</li>";
      if($this->get_obj_folder_err()!="") $out .="<li>".$this->get_obj_folder_err()."</li>";
      if($this->get_poznamka_err()!="") $out .="<li>".$this->get_poznamka_err()."</li>";
      if($this->get_aktivni_err()!="") $out .="<li>".$this->get_aktivni_err()."</li>";
    $out .= "</ul>";
    return $out;
  }
  /****************************** PRIVATE ***********************************/     


} // End Class

?>
