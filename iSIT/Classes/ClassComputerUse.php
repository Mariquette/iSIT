<?php
/**
 *  Class ComputerUse
 *  - pre model
 */    

/*
 --
-- Table structure for table `computer_uses`
--

DROP TABLE IF EXISTS `computer_uses`;
CREATE TABLE IF NOT EXISTS `computer_uses` (
		`dbid` int(11) NOT NULL AUTO_INCREMENT,
		`id` int(5) NOT NULL,
		`computer_id` int(5) NOT NULL,
		`person_id` int(5) NOT NULL,
		`poznamka` text COLLATE utf8_czech_ci NOT NULL,
		PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=301 ;

*/


class ComputerUse
{

  private static $rep;    // staticka repository
   
  private $id;
  private $computer_id;  
  private $person_id;
  private $poznamka;
   
  private $id_err;
  private $computer_id_err;
  private $person_id_err;
  private $poznamka_err;
    
  static function get_folder()
  {
    return "_computer_uses";
  }
  static function get_db_name()
  {
    return "computer_uses";
  }
    
  static function get_id_index(){ return 0; }
  static function get_computer_id_index(){ return 1; }
  static function get_person_id_index(){ return 2; }  
  static function get_poznamka_index(){ return 3; }
  
  static function get_all_index_names($separator=",")
  {
    return substr("'ID'".$separator."'COMPUTER_ID'".$separator."'PERSON_ID'".$separator."'POZNAMKA'",0,-count($separator));
  }    
  
  // konstruktor prazdny a pretizeny polem parametru
  public function __construct($array = false)
  {
  
    if(self::$rep === NULL)
    {
      self::$rep = new Repository("./");
    }
  
    $this->id_err="";
    $this->computer_id_err="";
    $this->person_id_err="";
    $this->poznamka_err="";
       
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
        
    die("ComputerUse->__construct(\$array=false): Nespravny vstupni prarametr \$array!");        

  }


//  ------------------
//  ---- SETTERS -----
//  ------------------

    public function set_id($id)
    {
      $this->id = $id;
    }
    public function set_computer_id($computer_id)
    {
      $this->computer_id = $computer_id;
    }
    public function set_person_id($person_id)
    {
      $this->person_id = $person_id;
    }
    public function set_poznamka($poznamka)
    {
      $this->poznamka = $poznamka;
    }

//  ------------------
//  ---- GETTERS -----
//  ------------------

    public function get_id()
    {
      return $this->id;
    }

    public function get_computer_id()
    {
      return $this->computer_id;
    }
    
    public function get_person_id()
    {
      return $this->person_id;
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
  
  // -- GET ERR -- //

    public function get_id_err(){ return $this->id_err; }
    public function get_computer_id_err(){ return $this->computer_id_err; }
    public function get_person_id_err(){ return $this->person_id_err; }
    public function get_poznamka_err(){ return $this->poznamka_err; }
  
//  ------------------
//  ---- GENERAL -----
//  ------------------

  public function clear()
  {
    $this->id = 0;
    $this->computer_id = "";
    $this->poznamka = "";
    $this->person_id = "";
    return true;
  }

  public function load($array)
  {
    if(!is_array($array)) die("ComputerUse::load(\$array): parametr \$array musi byt typu pole.<br>");
    if(count($array)!=4) die("ComputerUse::load(\$array): nespravny pocet prvku pole.<br>");
    
    if(!isset($array[ComputerUse::get_id_index()])) die("ComputerUse::load(\$array): v poli \$array neexistuje klic \"".$array[ComputerUse::get_id_index()]."\".<br>");
    $this->set_id($array[ComputerUse::get_id_index()]);

    if(!isset($array[ComputerUse::get_computer_id_index()])) die("ComputerUse::load(\$array): v poli \$array neexistuje klic \"".$array[ComputerUse::get_computer_id_index()]."\".<br>");
    $this->set_computer_id($array[ComputerUse::get_computer_id_index()]);

    if(!isset($array[ComputerUse::get_person_id_index()])) die("ComputerUse::load(\$array): v poli \$array neexistuje klic \"".$array[ComputerUse::get_person_id_index()]."\".<br>");
    $this->set_person_id($array[ComputerUse::get_person_id_index()]);

    if(!isset($array[ComputerUse::get_poznamka_index()])) die("ComputerUse::load(\$array): v poli \$array neexistuje klic \"".$array[ComputerUse::get_poznamka_index()]."\".<br>");
    $this->set_poznamka($array[ComputerUse::get_poznamka_index()]);
    
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
    return substr($string, 0, -(strlen($separator))); 
  }
  
  // vraci pole polozek objektu  
  public function to_array()
  {
    $array[ComputerUse::get_id_index()] = $this->id;
    $array[ComputerUse::get_computer_id_index()] = $this->computer_id;
    $array[ComputerUse::get_person_id_index()] = $this->person_id;
    $array[ComputerUse::get_poznamka_index()] = $this->poznamka;
    return $array;
  }

  // vraci string formatovany pro rychle zobrazeni polozek objektu
  public function to_html_string()
  {
    return "id => ".$this->id." <br>
            computer_id => ".$this->computer_id." <br>
            person_id => ".$this->person_id." <br>
            poznamka => ".$this->poznamka." <br>
            <hr>";
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
    if(!Test::is_number($this->computer_id))
    {
      $return = $false;
      $this->computer_id_err = "Povolené znaky pro parametr \"computer_id\" jsou číslice 0-9.";
    } 
    if(!Test::is_number($this->person_id))
    {
      $return = $false;
      $this->person_id_err = "Povolené znaky pro parametr \"person_id\" jsou číslice 0-9.";
    } 
    if((!Test::is_text($this->poznamka,100))AND($this->poznamka!=""))
    {
      $return = $false;
      $this->poznamka_err = "Parametr \"poznamka\" má omezenou délku textu na 100 znaků.";
    } 
        
    $rep = self::$rep;
    
    // existuje tiskarna   
    $computers = $rep->get_all_computer("id");    
    if(!isset($computers[$this->computer_id]))
    {
      $return = $false;
      $this->computer_id_err = "Parametr \"computer_id\" obsahuje neplatné ID \"".$this->get_computer_id()."\".";
    }

    // existuje uzivatel   
    $persons = $rep->get_all_person("id");
    if(!isset($persons[$this->get_person_id()]))
    {
      $return = $false;
      $this->person_id_err = "Parametr \"person_id\" obsahuje neplatné ID \"".$this->get_person_id()."\".";
    }

    // duplicity
    
    $computer_uses = $rep->get_all_computer_use_by_computer_id($this->get_computer_id());
    foreach($computer_uses as $computer_use)
    {
      if($computer_use->get_person_id() == $this->get_person_id())
      {
        $return = $false;
        $this->id_err = "Duplicitní záznam k záznamu id=".$computer_use->get_id().". (computer_id:".$this->get_computer_id().", person_id:".$this->get_person_id().")";
        break;
      }
    }
    
    return $return;
  }
    
//  ------------------
//  ---- SPECIAL -----
//  ------------------

  public function get_person_login($count=0)
  {
  	$rep = self::$rep;
  	if($person = $rep->get_person($this->person_id))
  	{
  		return $person->get_login($count);
  	}
  	return "not found";
  }
  
  public function get_person_full_name($count=0)
  {
  	$rep = self::$rep;
  	if($person = $rep->get_person($this->person_id))
  	{
  		return $person->get_full_name($count);
  	}
  	return "not found";
  }
  
  public function get_computer_pc_name($count=0)
  {
    $rep = self::$rep;
    if($computer = $rep->get_computer($this->computer_id))
    {
      return $computer->get_pc_name($count);
    }
    return "not found";
  }
    
  public function get_computer_teamviewer()
  {
    $rep = self::$rep;
    if($computer = $rep->get_computer($this->computer_id))
    {
      return $computer->get_teamviewer();
    }
    return "not found";
  }

  public function get_all_err()
  {
    $out = "<ul class=\"err\">";    
      if($this->get_id_err()!="") $out .= "<li>".$this->get_id_err()."</li>";
      if($this->get_computer_id_err()!="") $out .="<li>".$this->get_computer_id_err()."</li>";
      if($this->get_person_id_err()!="") $out .="<li>".$this->get_person_id_err()."</li>";
      if($this->get_poznamka_err()!="") $out .="<li>".$this->get_poznamka_err()."</li>";
    $out .= "</ul>";
    return $out;
  }
  /****************************** PRIVATE ***********************************/     


} // End Class

?>
