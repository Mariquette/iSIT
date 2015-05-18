<?php
/**
 *  Class Printer
 *  - model
 */    
class Printer
{

  private static $rep;    // staticka repository
   
  private $id;
  private $model;
  private $seriove_cislo;
  private $aktivni;
  private $datum_porizeni;
  private $evidencni_cislo;
  private $ip;
  private $_mac;
   
  private $id_err;
  private $model_err;
  private $seriove_cislo_err;
  private $aktivni_err;
  private $datum_porizeni_err;
  private $evidencni_cislo_err;
  private $ip_err;
  private $mac_err;

// -----------------------------------------------------------------------------   
    

  static function get_folder()
  {
    return "_printers";
  }

  static function get_db_name()
  {
    return "printers";
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
  static function get_ip_index()
  {
    return 6;
  }
  static function get_mac_index()
  {
    return 7;
  }

	static function get_attribs()
  {
  	return array("id", "model", "seriove_cislo", "aktivni", "datum_porizeni", "evidencni_cislo", "ip", "_mac");	
	}

  static function attribs_to_string($text="")
  {
  	if($text != "") $text = $text.".";
  	$atributy = "";
  	foreach(Printer::get_attribs() as $atribut)
  	{
			$atributy .= $text.$atribut.", ";
		}
		
		return substr($atributy, 0, -2);			
	}
  
  static function get_all_index_names($separator=",")
  {
    return "'ID'".$separator."'MODEL'".$separator."'SERIOVE_CISLO'".$separator."'AKTIVNI'".$separator."'DATUM_PORIZENI'".$separator."'EVIDENCNI_CISLO'".$separator."'IP'".$separator."'_MAC'";
  }    
  
// -----------------------------------------------------------------------------    

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
    $this->ip_err="";
    $this->mac_err="";
        
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
    die("Printers::__constructor(): Nenalezen konstruktor pro dane parametry.<br>");        
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
  // nastavuje adresar, kde se hledaji obrk1, obrk2 a miniatury
  public function set_obrk_folder($folder)
  {
    $this->obrk_folder = $folder;
  }

  public function set_ip($ip)
  {
    $this->ip = trim($ip);
  }
  public function set_mac($mac)
  {
    $this->_mac = trim($mac);
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
  public function set_datum_porizeni($value)
  {
    $this->datum_porizeni = $value;
  }
      
//  ------------------
//  ---- GETTERS -----
//  ------------------

  /*
  public function get_folder()
  {
    return self::get_folder();
  }
  */
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
        $text = substr($text,0,$znaku);
        $text .= "...";
      }
    }
    return $text;
  }
  public function get_seriove_cislo()
  {
    return $this->seriove_cislo;
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
        $text = substr($text,0,$znaku);
        $text .= "...";
      }
    }
    return strtolower($text);
  }  
  public function get_ip($null = "0.0.0.0")
  {
    if($this->ip == "0.0.0.0") return $null;
    return $this->ip;
  }
  public function get_mac($null = "000000000000")
  {
    $text = $this->_mac;    
    if($this->_mac == "000000000000") $text = $null; 
    return strtolower($text);
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
  public function get_ip_err()
  {
    return $this->ip_err;
  }
  public function get_mac_err()
  {
    return $this->mac_err;
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
    $this->ip = "0.0.0.0";
    $this->_mac = "000000000000";
        
    $this->obrazek_1 = "";
    $this->obrazek_2 = "";
    $this->obrk_folder = "./";
    
    return true;
  }
  
  // nastavi parametry objektu podle polozek v poli  
  public function load($array)
  {
    if((is_array($array)) AND (count($array)==8))
    {
      $this->set_id($array[Printer::get_id_index()]);
      $this->set_model($array[Printer::get_model_index()]);
      $this->set_seriove_cislo($array[Printer::get_seriove_cislo_index()]);
      $this->set_aktivni($array[Printer::get_aktivni_index()]);
      $this->set_datum_porizeni($array[Printer::get_datum_porizeni_index()]);
      $this->set_evidencni_cislo($array[Printer::get_evidencni_cislo_index()]);
      $this->set_ip($array[Printer::get_ip_index()]);
      $this->set_mac(Util::to_real_mac($array[Printer::get_mac_index()]));
      
      $this->obrazek_1 = "";
      $this->obrazek_2 = "";       
      return true;     
    }    
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
      $array[Printer::get_id_index()] = $this->id;
      $array[Printer::get_model_index()] = $this->model;
      $array[Printer::get_seriove_cislo_index()] = $this->seriove_cislo;
      $array[Printer::get_aktivni_index()] = ($this->aktivni != false ? 1 : 0);
      $array[Printer::get_datum_porizeni_index()] = $this->datum_porizeni;
      $array[Printer::get_evidencni_cislo_index()] = $this->evidencni_cislo;
      $array[Printer::get_ip_index()] = $this->ip;
      $array[Printer::get_mac_index()] = Util::to_real_mac($this->_mac);
      
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
            ip => ".$this->ip." <br>
            mac => <b>".$this->_mac."</b> <br>
            
            obrazek_1 => ".$this->obrazek_1." <br>
            obrazek_2 => ".$this->obrazek_2." <br>    
            ";
  }
  
  public function is_enable($true=true,$false=false)
  {
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

    if(!Test::is_mac_address($this->_mac))
    {
      $return = $false;
      $this->mac_err = "Parametr \"MAC\" musí být vyplněn skutečnou MAC adresou (délka 12 znaků, 0-9, a-f).";
    } 
    
    if(!Test::is_ip_address($this->ip))
    {
      $return = $false;
      $this->ip_err = "Parametr \"IP\" musí být vyplněn skutečnou IP adresou (xxx.xxx.xxx.xxx, 0>=xxx<=255).";
    } 
    
    $rep = self::$rep;
    
    $net_dev_ev = $rep->get_all_printer("evidencni_cislo");

    if(isset($net_dev_ev[$this->evidencni_cislo]))
    {
      if($net_dev_ev[$this->evidencni_cislo]->get_seriove_cislo() != $this->seriove_cislo)
      {
        if($net_dev_ev[$this->evidencni_cislo]->get_id()!=$this->get_id())
        {
          $return = $false;
          $this->evidencni_cislo_err = "Parametr \"Evidenční číslo\" je již přiřazeno k seriovému číslu \"".$net_dev_ev[$this->evidencni_cislo]->get_seriove_cislo()."\".";
        }
      }
    }

    return $return;
  }
  
	public function get_info($attribs = array())
	{
		$info = "";
		if(empty($attribs))
		{
			$attribs = array("model", "evidencni_cislo", "ip");
		}
		foreach ($attribs as $atribut)
		{
			if($atribut == "_mac") $atribut = "mac";
			$metoda = "get_$atribut";
			$info .= $this->$metoda().", ";
		}
		return substr($info, 0, -2);		 
	}  
//  ------------------
//  ---- SPECIAL -----
//  ------------------
     
  public function get_all_uses()
  {
    $rep = self::$rep;
    return $rep->get_all_printer_use_by_printer_id($this->get_id());
  }

  public function get_all_comments()
  {
    $rep = self::$rep;
    return $rep->get_all_comment_by_device($this);
  }

  // kdyz existuje soubor id_Printer.pdf v adresari /__pdf/vyrazovaky/ vraci true
  public function is_vyrazovak($true=true,$false=false)
  {
    $rep = self::$rep;
    if($rep->is_pdf($this,"vyrazovaky")) return true;
    return false;
  }

  // kdyz existuje soubor id_Printer.pdf v adresari /__pdf/dodaky/ vraci true
  public function is_dodak($true=true,$false=false)
  {
    $rep = self::$rep;
    if($rep->is_pdf($this,"dodaky")) return true;
    return false;
  }

  public function get_string_location()
  {
  	$rep = self::$rep;
  	return $rep->get_location($this->get_location());
  }
  /****************************** PRIVATE ***********************************/     


} // End Class

?>
