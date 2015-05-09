<?php
/**
 *  Class Event
 *  - model
 */    

class Event
{

  private static $rep;    // staticka repository

  private $id;
  private $zobrazit_od;
  private $zobrazit_do;
  private $text;
  private $aktivni;
  private $nadpis;

  private $id_err;
  private $zobrazit_od_err;
  private $zobrazit_do_err;
  private $text_err;
  private $aktivni_err;
  private $nadpis_err;
  
  // *** STATIC ***
  
  static function get_folder()
  {
    return "_events";
  }
  static function get_db_name()
  {
    return "events";
  }
  static function get_id_index()
  {
    return 0;
  }
  static function get_zobrazit_od_index()
  {
    return 1;
  }
  static function get_zobrazit_do_index()
  {
    return 2;
  }
  static function get_text_index()
  {
    return 3;
  }
  static function get_aktivni_index()
  {
    return 4;
  }
  static function get_nadpis_index()
  {
    return 5;
  }

  static function get_all_index_names($separator=",")
  {
    return "'ID'".$separator."'ZOBRAZIT_OD'".$separator."'ZOBRAZIT_DO'".$separator."'TEXT'".$separator."'AKTIVNI'".$separator."'NADPIS'";
  }    

  // *** PUBLIC ***

  // konstruktor prazdny a pretizeny polem parametru
  public function __construct($array = false)
  {
    if(self::$rep === NULL)
    {
      self::$rep = new Repository("./");
    }
  
    $ok = false;

    $this->id_err = "";
    $this->zobrazit_od_err = "";
    $this->zobrazit_do_err = "";
    $this->text_err = "";
    $this->aktivni_err = "";
    $this->nadpis_err = "";

    if($array == false)
    {
      // clear event
      return $this->clear();
    }
    
    if($this->load($array))
    {
      // pole -> event
      return true;
    }
    
    // no constructor
    die("Event->__constructor(\$array = false): Nenalezen konstruktor pro dane parametry!");        
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
  public function set_text($text)
  {
    $this->text = $text;
  }
  public function set_nadpis($value)
  {
    $this->nadpis = $value;
  }
  // implementuje prevod citelneho data na linux format  
  public function set_zobrazit_od($datum)
  {
      if(Test::is_date($datum,"/"))
      {
        $this->zobrazit_od = Util::date_to_timestamp($datum);
      }
      else
      {
        $this->zobrazit_od = $datum;
      }      
  }
  // implementuje prevod citelneho data na linux format  
  public function set_zobrazit_do($datum)
  {
      if(Test::is_date($datum,"/"))
      {
        $this->zobrazit_do = Util::date_to_timestamp($datum);
      }
      else
      {
        $this->zobrazit_do = $datum;
      }
  }  
  
//  ------------------
//  ---- GETTERS -----
//  ------------------

  public function get_id()
  {
    return $this->id;
  }
  public function get_kategorie()
  {
    return $this->kategorie;
  }
  public function get_poradi()
  {
    return $this->poradi;
  }
  public function get_nadpis($znaku=0)
  {
    $text = $this->nadpis;
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
  public function get_text($znaku=0)
  {    
    $text = $this->text;
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
  public function get_zobrazit_od()
  {
    return $this->zobrazit_od;
  }
  public function get_zobrazit_do()
  {
    return $this->zobrazit_do;
  }

  // vraci zda zobrazit ci ne na webu 
  public function get_aktivni($ano="1",$ne="0")
  {
    if($this->aktivni) return $ano;
    return $ne;
  }

  public function get_id_err(){ return $this->id_err; }
  public function get_zobrazit_od_err(){ return $this->zobrazit_od_err; }
  public function get_zobrazit_do_err(){ return $this->zobrazit_do_err; }
  public function get_text_err(){ return $this->text_err; }
  public function get_aktivni_err(){ return $this->aktivni_err; }
  public function get_nadpis_err(){ return $this->nadpis_err; }

//  ------------------
//  ---- GENERAL -----
//  ------------------

  // inicializuje hodnoty objektu na zakladni
  public function clear()
  {
    $this->id = 0;                                  
    $this->set_zobrazit_od(Util::date_to_timestamp(date("d/m/Y")));
    $this->set_zobrazit_do(Util::date_to_timestamp(date("d/m/Y"))+2678400);  // + 30dni
    $this->nadpis = "";
    $this->text = "";
    $this->aktivni = false;
    return true;
  }
  
  // nastavi parametry objektu podle polozek v poli  
  public function load($array)
  {
    if((is_array($array)) AND (count($array)>=6))
    {
      $this->set_id($array[Event::get_id_index()]);
      $this->set_nadpis($array[Event::get_nadpis_index()]);
      $this->set_text($array[Event::get_text_index()]);
      $this->set_zobrazit_od($array[Event::get_zobrazit_od_index()]);
      $this->set_zobrazit_do($array[Event::get_zobrazit_do_index()]);
      $this->set_aktivni($array[Event::get_aktivni_index()]);
      return true;     
    }    
    return false;
  }

  // vraci pole polozek objektu  
  public function to_array()
  {
      $array = false;
      $array[Event::get_id_index()] = $this->id;
      $array[Event::get_zobrazit_od_index()] = $this->zobrazit_od;
      $array[Event::get_zobrazit_do_index()] = $this->zobrazit_do;
      $array[Event::get_text_index()] = $this->text;
      $array[Event::get_aktivni_index()] = ($this->aktivni != false ? 1 : 0);
      $array[Event::get_nadpis_index()] = $this->nadpis;
      return $array;
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
  
  // vraci string formatovany pro rychle zobrazeni polozek objektu
  public function to_html_string()
  {
    return "id => ".$this->id." <br>
            nadpis => ".$this->nadpis." <br>
            text => ".$this->text." <br>
            zobrazit_od => ".$this->zobrazit_od." (".Util::timestamp_to_date($this->zobrazit_od).") <br>
            zobrazit_do => ".$this->zobrazit_do."(".Util::timestamp_to_date($this->zobrazit_do).") <br>
            aktivni => ".$this->aktivni." <br>
            ";
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
    if(!Test::is_text($this->nadpis,100))
    {
      $return = $false;
      $this->nadpis_err = "Parametr \"Nadpis\" musí být vyplněn a má omezenou délku textu na 100 znaků.";
    } 

    if(!Test::is_text($this->text,500))
    {
      $return = $false;
      $this->text_err = "Parametr \"Text\" musí být vyplněn a má omezenou délku textu na 500 znaků.";
    } 
    if(!Test::is_timestamp($this->zobrazit_od))
    {
      $return = $false;
      $this->zobrazit_od_err = "Parametr \"Zobrazit Od\" musí být platné datum ve formátu den/měsíc/rok.";
    } 
    if(!Test::is_timestamp($this->zobrazit_do))
    {
      $return = $false;
      $this->zobrazit_do_err = "Parametr \"Zobrazit Do\" musí být platné datum ve formátu den/měsíc/rok.";
    } 
    if(!Test::is_bool($this->aktivni))
    {
      $return = $false;
      $this->aktivni_err = "Povolené hodnoty parametru \"Aktivni\" jsou [0,1,true,false].";
    } 
    return $return;
  }

  // *** PRIVATE ***


} // End Class

?>
