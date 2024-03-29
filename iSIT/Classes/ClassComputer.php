<?php
/**
 *  Class Computer
 *  - model
 */    

class Computer
{

  private static $rep;    // staticka repository
     
  private $id;
  private $model;
  private $seriove_cislo;
  private $aktivni;
  private $datum_porizeni;
  private $evidencni_cislo;
  private $pc_name;
  private $teamviewer;
  private $location;
   
  private $id_err;
  private $model_err;
  private $seriove_cislo_err;
  private $aktivni_err;
  private $datum_porizeni_err;
  private $evidencni_cislo_err;
  private $pc_name_err;
  private $teamviewer_err;
  private $location_err;
  
  
  
  static function get_attribs()
  {
    return array("id", "model", "seriove_cislo", "aktivni", "datum_porizeni", "evidencni_cislo", "pc_name", "teamviewer", "location");
  }        
  static function get_folder()
  {
    return "_computers";
  }    
  static function get_db_name()
  {
    return "computers";
  }    
  static function get_id_index()
  {
    return 0;
  }
  static function get_model_index()
  {
    return 1;
  }
  static function get_seriove_cislo_index()
  {
    return 2;
  }
  static function get_aktivni_index()
  {
    return 3;
  }
  static function get_datum_porizeni_index()
  {
    return 4;
  }
  static function get_evidencni_cislo_index()
  {
    return 5;
  }
  static function get_pc_name_index()
  {
    return 6;
  }
  static function get_teamviewer_index()
  {
    return 7;
  }
  static function get_location_index()
  {
    return 8;
  }
  
  static function attribs_to_string($text="")
  {
  	if($text != "") $text = $text.".";
  	$atributy = "";
  	foreach(Computer::get_attribs() as $atribut)
  	{
			$atributy .= $text.$atribut.", ";
		}
		
		return substr($atributy, 0, -2);			
	}
  
	static function get_all_index_names($separator=",")
  {
    return "'ID'".$separator."'MODEL'".$separator."'SERIOVE_CISLO'".$separator."'AKTIVNI'".$separator."'DATUM_PORIZENI'".$separator."'EVIDENCNI_CISLO'".$separator."'PC_NAME'".$separator."'TEAMVIEWER'".$separator."'LOCATION'";
  }    

  // konstruktor prazdny a pretizeny polem parametru  
  public function __construct($array = false)
  {
    
    if(self::$rep === NULL)
    {
      self::$rep = new Repository("./");
    }
    
    $ok = false;
    
    $this->obrk_folder = "./";
    $this->id_err="";
    $this->model_err="";
    $this->seriove_cislo_err="";
    $this->aktivni_err="";
    $this->datum_porizeni_err="";
    $this->evidencni_cislo_err="";
    $this->pc_name_err="";
    $this->teamviewer_err="";
    $this->location_err="";
        
    if($array == false)
    {
      // clear computer
      return $this->clear();
    }
    
    if($this->load($array))
    {
      // pole -> computer
      return true;
    }
    
    // no constructor
    die("Computers::__constructor(): 1 Nenalezen konstruktor pro dane parametry.<br>");        
  }
  
//  ------------------
//  ---- SETTERS -----
//  ------------------

  public function set_id($id)
  {
    $this->id = $id;
  }
  public function set_aktivni($value)
  {
    $this->aktivni = $value;
  }
  public function set_model($value)
  {
    $this->model = $value;
  }
  public function set_seriove_cislo($value)
  {
    $this->seriove_cislo = trim($value);
  }
  public function set_evidencni_cislo($value)
  {
    $this->evidencni_cislo = trim($value);
  }
  public function set_pc_name($value)
  {
    $this->pc_name = trim($value);
  }
  public function set_teamviewer($value)
  {
    $this->teamviewer = trim($value);
  }
  public function set_location($value)
  {
    $this->location = trim($value);
  }
  
  // nastavuje adresar, kde se hledaji obrk1, obrk2 a miniatury
  public function set_obrk_folder($folder)
  {
    $this->obrk_folder = $folder;
  }
  public function set_datum_porizeni($string_date)
  {
    $this->datum_porizeni = $string_date;
  } 
  
//  ------------------
//  ---- GETTERS -----
//  ------------------

  public function get_id()
  {
    return $this->id;
  }
  public function get_model($znaku=0)
  {
    $text = $this->model;
    if($znaku > 0)
    {
      if(strlen($text)>$znaku)
      {
        $text = substr($text,0,$znaku-2);
        $text .= "...";
      }
    }
    return $text;
  }
  public function get_seriove_cislo($znaku=0)
  {
    $text = strtoupper($this->seriove_cislo);
    if($znaku > 0)
    {
      if(strlen($text)>$znaku)
      {
        $text = substr($text,0,$znaku-2);
        $text .= "...";
      }
    }
    return strtoupper($text);
  }
  public function get_aktivni($ano="1",$ne="0")
  {
    if($this->aktivni) return $ano;
    return $ne;
  }
  public function get_datum_porizeni()
  {
    return $this->datum_porizeni;
  }
  public function get_evidencni_cislo($znaku=0)
  {
    $text = $this->evidencni_cislo;
    if($znaku > 0)
    {
      if(strlen($text)>$znaku)
      {
        $text = substr($text,0,$znaku-2);
        $text .= "...";
      }
    }
    return strtolower($text);
  }
  public function get_pc_name()
  {
    return $this->pc_name;
  }
  public function get_teamviewer()
  {
    return $this->teamviewer;
  }
  public function get_location()
  {
  	if($this->location == "") return 0;
    return $this->location;
  }
  
  public function get_id_err()
  {
    return $this->id_err;
  }
  public function get_model_err()
  {
    return $this->model_err;
  }
  public function get_seriove_cislo_err()
  {
    return $this->seriove_cislo_err;
  }
  public function get_aktivni_err()
  {
    return $this->aktivni_err;
  }
  public function get_datum_porizeni_err()
  {
    return $this->datum_porizeni_err;
  }
  public function get_evidencni_cislo_err($znaku=0)
  {
    return $this->evidencni_cislo_err;
  }
  public function get_pc_name_err()
  {
    return $this->pc_name_err;
  }
  public function get_teamviewer_err()
  {
    return $this->teamviewer_err;
  }
  public function get_location_err()
  {
    return $this->location_err;
  }

//  ------------------
//  ---- GENERAL -----
//  ------------------
  
  // inicializuje hodnoty objektu na zakladni
  public function clear()
  {
    $this->id = 0;
    $this->model = "";
    $this->seriove_cislo = 0;
    $this->aktivni = false;
    $this->datum_porizeni = 0;
    $this->evidencni_cislo = "";
    $this->pc_name = "";
    $this->teamviewer = "";
    $this->location = "";
    
    $this->obrazek_1 = "";
    $this->obrazek_2 = "";
    $this->obrk_folder = "./";
    
    return true;
  }
  
  // nastavi parametry objektu podle polozek v poli  
  public function load($array)
  {
    if((is_array($array)) AND (count($array)==9))
    {
      $this->set_id($array[Computer::get_id_index()]);
      $this->set_model($array[Computer::get_model_index()]);
      $this->set_seriove_cislo($array[Computer::get_seriove_cislo_index()]);
      $this->set_aktivni($array[Computer::get_aktivni_index()]);
      $this->set_datum_porizeni($array[Computer::get_datum_porizeni_index()]);
      $this->set_evidencni_cislo($array[Computer::get_evidencni_cislo_index()]);
      $this->set_pc_name($array[Computer::get_pc_name_index()]);
      $this->set_teamviewer($array[Computer::get_teamviewer_index()]);
      $this->set_location($array[Computer::get_location_index()]);
      
      $this->obrazek_1 = "";
      $this->obrazek_2 = "";       
      return true;     
    }    
  	//echo "ClassComputer->load: (".count($array).") ".print_r($array);
    return false;
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
    return substr($string, 0, -(strlen($separator))); 
  }
  
  // vraci pole polozek objektu  
  public function to_array()
  {
      $array[Computer::get_id_index()] = $this->id;
      $array[Computer::get_model_index()] = $this->model;
      $array[Computer::get_seriove_cislo_index()] = $this->seriove_cislo;
      $array[Computer::get_aktivni_index()] = ($this->aktivni != false ? 1 : 0);
      $array[Computer::get_datum_porizeni_index()] = $this->datum_porizeni;
      $array[Computer::get_evidencni_cislo_index()] = $this->evidencni_cislo;
      $array[Computer::get_pc_name_index()] = $this->pc_name;
      $array[Computer::get_teamviewer_index()] = $this->teamviewer;
      $array[Computer::get_location_index()] = $this->location;
      return $array;
  }

  // vraci string formatovany pro rychle zobrazeni polozek objektu
  public function to_html_string()
  {
    return "id => ".$this->id." <br>
            model => ".$this->model." <br>
            seriove_cislo => ".$this->seriove_cislo." <br>
            datum_porizeni => ".$this->datum_porizeni." <br>
            aktivni => ".$this->aktivni." <br>
            evidencni_cislo => ".$this->evidencni_cislo." <br>
            pc_name => ".$this->pc_name." <br>
            teamviewer => ".$this->teamviewer." <br>
            location => ".$this->location." <br>
            
            obrazek_1 => ".$this->obrazek_1." <br>
            obrazek_2 => ".$this->obrazek_2." <br>   
            ";
  }
  
  // kdyz existuje vyrazovak, vraci false, kdyz je enable false, vraci false
  public function is_enable($true=true,$false=false)
  {
    $rep = self::$rep;  
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
      $this->id_err = "Povolené znaky pro parametr \"Id\" jsou číslice 0-9.";
    } 
    if(!Test::is_text($this->model,100))
    {
      $return = $false;
      $this->model_err = "Parametr \"Model\" musí být vyplněn a má omezenou délku textu na 100 znaků.";
    } 
    if(!Test::is_text($this->datum_porizeni,20))
    {
      $return = $false;
      $this->datum_porizeni_err = "Parametr \"Datum pořízení\" musí být vyplněn a má omezenou délku textu na 20 znaků.";
    } 
    if(!Test::is_text($this->seriove_cislo,20))
    {
      $return = $false;
      $this->seriove_cislo_err = "Parametr \"Sériové číslo\" musí být vyplněn a má omezenou délku textu na 20 znaků.";
    } 
    if(!Test::is_bool($this->aktivni))
    {
      $return = $false;
      $this->aktivni_err = "Povolené hodnoty parametru \"Aktivni\" jsou [0,1,true,false].";
    } 
    if(!Test::is_text($this->evidencni_cislo,20))
    {
      $return = $false;
      $this->evidencni_cislo_err = "Parametr \"Evidenční číslo\" musí být vyplněn a má omezenou délku textu na 20 znaků.";
    } 

    if(($this->pc_name != "")&&(!Test::is_text($this->pc_name,20)))
    {
      $return = $false;
      $this->pc_name_err = "Parametr \"Jméno počítače\" má omezenou délku textu na 20 znaků.";
    } 
    if(($this->teamviewer != "")&&(!Test::is_number($this->teamviewer)))
    {
      $return = $false;
      $this->teamviewer_err = "Parametr \"TeamViewer\" musí být číslo (znaky 0-9).";
    } 
    if(($this->location != "")&&(!Test::is_number($this->location)))
    {
      $return = $false;
      $this->location_err = "Parametr \"Location\" musí být číslo (znaky 0-9).";
    } 
    
    $rep = self::$rep;
    
    $cmps = $rep->get_all_computer("evidencni_cislo");
    foreach($cmps as $key => $value)
    {
      //echo "\$cmps[$key]<br>";
    }
    if(isset($cmps[$this->evidencni_cislo]))
    {
      if(($cmps[$this->evidencni_cislo]->get_seriove_cislo() != $this->seriove_cislo)&&($cmps[$this->evidencni_cislo]->get_id() != $this->get_id()))
      {
        $return = $false;
        $this->evidencni_cislo_err = "Parametr \"Evidenční číslo\" je již přiřazeno k seriovému čslu \"".$cmps[$this->evidencni_cislo]->get_seriove_cislo()."\".";
      }
    }

    return $return;
  }
  
  public function get_info($attribs = array())
	{
		$info = "";
		if(empty($attribs))
		{
			$attribs = array("pc_name", "model", "seriove_cislo", "evidencni_cislo", "teamviewer");
		}
		foreach ($attribs as $atribut)
		{			
			$metoda = "get_$atribut";
			$info .= $this->$metoda().", ";
		}
		return substr($info, 0, -2);		 
	}
  
//  ------------------
//  ---- SPECIAL -----
//  ------------------
  
  // kdyz existuje soubor id_Computer.pdf v adresari /__pdf/vyrazovaky/ vraci true
  public function is_vyrazovak($true=true,$false=false)
  {
    $rep = self::$rep;
    if($rep->is_pdf($this,"vyrazovaky")) return true;
    return false;
  }

  // kdyz existuje soubor id_Computer.pdf v adresari /__pdf/dodaky/ vraci true
  public function is_dodak($true=true,$false=false)
  {
    $rep = self::$rep;
    if($rep->is_pdf($this,"dodaky")) return true;
    return false;
  }
  
  public function get_all_comments()
  {
    $rep = self::$rep;
    return $rep->get_all_comment_by_device($this);
  }
  
  public function get_all_uses()
  {
    $rep = self::$rep;
    return $rep->get_all_computer_use_by_computer_id($this->get_id());
  }
  

  public function get_string_location()
  {
  	$rep = self::$rep;
  	if($loc = $rep->get_location($this->get_location()))
  	{
  		return $loc->get_name();	
  	}
  	return "";
  }
  
  public function get_all_location()
  {
  	$rep = self::$rep;
  	return $rep->get_all_location();
  }
  
  /****************************** PRIVATE ***********************************/     


} // End Class

?>
