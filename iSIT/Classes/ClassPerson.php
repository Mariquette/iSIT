<?php
/**
 *  Class Person
 *  - model
 */    
 
class Person
{

  private static $rep;    // staticka repository
   
  private $id;
  private $full_name;
  private $osobni_cislo;
  private $aktivni;
  private $pobocka;
  private $login;
   
  private $id_err;
  private $full_name_err;
  private $osobni_cislo_err;
  private $aktivni_err;
  private $pobocka_err;
  private $login_err;
    
  static function get_folder()
  {
    return "_persons";
  }    
  static function get_db_name()
  {
    return "persons";
  }    
  static function get_id_index()
  {
    return 0;
  }
  static function get_full_name_index()
  {
    return 1;
  }
  static function get_osobni_cislo_index()
  {
    return 2;
  }
  static function get_aktivni_index()
  {
    return 3;
  }
  static function get_pobocka_index()
  {
    return 4;
  }
  static function get_login_index()
  {
    return 5;
  }
  
	static function get_attribs()
  {
  	return array("id", "full_name", "osobni_cislo", "aktivni", "pobocka", "login");	
	}

  static function attribs_to_string($text="")
  {
  	if($text != "") $text = $text.".";
  	$atributy = "";
  	foreach(Person::get_attribs() as $atribut)
  	{
			$atributy .= $text.$atribut.", ";
		}
		
		return substr($atributy, 0, -2);			
	}

  static function get_all_index_names($separator=",")
  {
    return "'ID'".$separator."'FULL_NAME'".$separator."'OSOBNI_CISLO'".$separator."'AKTIVNI'".$separator."'POBOCKA'".$separator."'LOGIN'";
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
    $this->full_name_err="";
    $this->osobni_cislo_err="";
    $this->aktivni_err="";
    $this->pobocka_err="";
    $this->login_err="";
        
    if($array == false)
    {
      // clear person
      return $this->clear();
    }
    
    if($this->load($array))
    {
      // pole -> person
      return true;
    }
    
    // no constructor
    die("Persons::__constructor(): Nenalezen konstruktor pro dane parametry.<br>");        
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
  public function set_login($value)
  {
    $this->login = trim(strtolower($value));
  }
  public function set_osobni_cislo($value)
  {
    $this->osobni_cislo = $value;
  }
  public function set_full_name($value)
  {
    $this->full_name = $value;
  }
  public function set_pobocka($value)
  {
    $this->pobocka = $value;
  }
  // nastavuje adresar, kde se hledaji obrk1, obrk2 a miniatury
  public function set_obrk_folder($folder)
  {
    $this->obrk_folder = $folder;
  }

//  ------------------
//  ---- GETTERS -----
//  ------------------

  public function get_id()
  {
    return $this->id;
  }
  public function get_full_name($znaku=0)
  {
    $text = $this->full_name;
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
  public function get_osobni_cislo()
  {
    return $this->osobni_cislo;
  }
  public function get_aktivni($ano="1",$ne="0")
  {
    if($this->aktivni) return $ano;
    return $ne;
  }
  public function get_pobocka()
  {
    return $this->pobocka;
  }
  public function get_login($znaku=0)
  {
    $text = $this->login;
    if($znaku > 0)
    {
      if(strlen($text)>$znaku)
      {
        $text = substr($text,0,$znaku);
        $text .= "...";
      }
    }
    return trim(strtolower($text));
  }
  
  public function get_id_err()
  {
    return $this->id_err;
  }
  public function get_full_name_err()
  {
    return $this->full_name_err;
  }
  public function get_osobni_cislo_err()
  {
    return $this->osobni_cislo_err;
  }
  public function get_aktivni_err()
  {
    return $this->aktivni_err;
  }
  public function get_pobocka_err()
  {
    return $this->pobocka_err;
  }
  public function get_login_err($znaku=0)
  {
    return $this->login_err;
  }

//  ------------------
//  ---- GENERAL -----
//  ------------------
  
  // inicializuje hodnoty objektu na zakladni
  public function clear()
  {
    $this->id = 0;
    $this->full_name = "";
    $this->osobni_cislo = 0;
    $this->aktivni = false;
    $this->pobocka = 0;
    $this->login = "";
    
    $this->obrazek_1 = "";
    $this->obrazek_2 = "";
    $this->obrk_folder = "./";
    
    return true;
  }
  
  // nastavi parametry objektu podle polozek v poli  
  public function load($array)
  {
    if((is_array($array)) AND (count($array)==6))
    {
      $this->set_id($array[Person::get_id_index()]);
      $this->set_full_name($array[Person::get_full_name_index()]);
      $this->set_osobni_cislo($array[Person::get_osobni_cislo_index()]);
      $this->set_aktivni($array[Person::get_aktivni_index()]);
      $this->set_pobocka($array[Person::get_pobocka_index()]);
      $this->set_login($array[Person::get_login_index()]);
      
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
      $array[Person::get_id_index()] = $this->id;
      $array[Person::get_full_name_index()] = $this->full_name;
      $array[Person::get_osobni_cislo_index()] = $this->osobni_cislo;
      $array[Person::get_aktivni_index()] = ($this->aktivni != false ? 1 : 0);
      $array[Person::get_pobocka_index()] = $this->pobocka;
      $array[Person::get_login_index()] = $this->login;
      return $array;
  }

  // vraci string formatovany pro rychle zobrazeni polozek objektu
  public function to_html_string()
  {
    return "id => ".$this->id." <br>
            full_name => ".$this->full_name." <br>
            osobni_cislo => ".$this->osobni_cislo." <br>
            pobocka => ".$this->pobocka." <br>
            aktivni => ".$this->aktivni." <br>
            login => ".$this->login." <br>
            
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
    if(!Test::is_text($this->full_name,100))
    {
      $return = $false;
      $this->full_name_err = "Parametr \"Full Name\" musí být vyplněn a má omezenou délku textu na 100 znaků.";
    } 
    if(!Test::is_number($this->pobocka))
    {
      $return = $false;
      $this->pobocka_err = "Povolené znaky pro parametr \"Pobočka\" jsou číslice 0-9.";
    } 
    if(!Test::is_number($this->osobni_cislo))
    {
      $return = $false;
      $this->osobni_cislo_err = "Povolené znaky pro parametr \"Osobní číslo\" jsou číslice 0-9.";
    } 
    if(!Test::is_bool($this->aktivni))
    {
      $return = $false;
      $this->aktivni_err = "Povolené hodnoty parametru \"Aktivni\" jsou [0,1,true,false].";
    } 
    if(!Test::is_text($this->login,20))
    {
      $return = $false;
      $this->login_err = "Parametr \"Login\" musí být vyplněn a má omezenou délku textu na 20 znaků.";
    } 

    return $return;
  }

	public function get_info($attribs = array())
	{
		$info = "";
		if(empty($attribs))
		{
			$attribs = array("full_name", "osobni_cislo", "login");
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
  
  public function get_all_comments()
  {
    $rep = self::$rep;
    return $rep->get_all_comment_by_device($this);
  }
  
  public function get_all_requirements()
  {
    $rep = self::$rep;
    return $rep->get_all_requirement_by_device($this);
  }
  
  public function get_all_devices()
  {
    $rep = self::$rep;
    $all_computers = $rep->get_all_computer("evidencni_cislo");
    $all_printers = $rep->get_all_printer("evidencni_cislo");
    $computers = array();
    $printers = array();
    
    return array_merge($computers, $printers);
  }
  
  public function get_string_location()
  {
  	$rep = self::$rep;
  	if($loc = $rep->get_location($this->get_pobocka()))
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
