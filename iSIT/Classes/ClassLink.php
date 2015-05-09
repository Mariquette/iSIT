<?php
/**
 *  Class Link
 *  - model
 */    

class Link
{

  private static $rep;    // staticka repository

  private $id;
  private $addr;
  private $popis;
  private $aktivni;
  private $_name;

  private $id_err;
  private $addr_err;
  private $popis_err;
  private $aktivni_err;
  private $name_err;
    
  // *** STATIC ***

  static function get_folder()
  {
    return "_links";
  }
  
  static function get_db_name()
  {
    return "links";
  }

  static function get_id_index()
  {
    return 0;
  }
  static function get_addr_index()
  {
    return 1;
  }
  static function get_popis_index()
  {
    return 2;
  }
  static function get_aktivni_index()
  {
    return 3;
  }
  static function get_name_index()
  {
    return 4;
  }
  
  static function get_all_index_names($separator=",")
  {
    return "'ID'".$separator."'ADDR'".$separator."'POPIS'".$separator."'AKTIVNI'".$separator."'_NAME'";
  }    

  // *** PUBLIC ***

  // konstruktor prazdny a pretizeny polem parametru
  public function __construct($array = false)
  {

    if(self::$rep === NULL)
    {
      self::$rep = new Repository("./");
    }
  
    $this->id_err = "";
    $this->addr_err = "";
    $this->popis_err = "";
    $this->aktivni_err = "";
    $this->name_err = "";

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
        
    die("Link->__construct(\$array=false): Nespravny vstupni prarametr \$array!");        
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
  public function set_popis($popis)
  {
    $this->popis = $popis;
  }
  public function set_addr($addr)
  {
    $this->addr = $addr;
  }  
  public function set_name($value)
  {
    $this->_name = $value;
  }  

//  ------------------
//  ---- GETTERS -----
//  ------------------
  
  public function get_id()
  {
    return $this->id;
  }
  public function get_name($znaku=0)
  {
    $popis = $this->_name;
    if($znaku > 0)
    {
      if(strlen($popis)>$znaku)
      {
        $popis = substr($popis,0,$znaku);
        $popis .= "...";
      }
    }
    return $popis;
  }
  public function get_popis($znaku=0)
  {    
    $popis = $this->popis;
    if($znaku > 0)
    {
      if(strlen($popis)>$znaku)
      {
        $popis = substr($popis,0,$znaku);
        $popis .= "...";
      }
    }
    return $popis;
  }
  public function get_addr()
  {
    return $this->addr;
  }
  public function get_aktivni($ano="1",$ne="0")
  {
    if($this->aktivni) return $ano;
    return $ne;
  }

  public function get_id_err(){ return $this->id_err; }
  public function get_addr_err(){ return $this->addr_err; }
  public function get_popis_err(){ return $this->popis_err; }
  public function get_aktivni_err(){ return $this->aktivni_err; }
  public function get_name_err(){ return $this->name_err; }

//  ------------------
//  ---- GENERAL -----
//  ------------------
 
  // inicializuje hodnoty objektu na zakladni
  public function clear()
  {
    $this->id = 0;                                  
    $this->addr = "";
    $this->_name = "";
    $this->popis = "";
    $this->aktivni = false;
    return true;
  }
  
  // nastavi parametry objektu podle polozek v poli  
  public function load($array)
  {
    if((is_array($array)) AND (count($array)==5))
    {
      $this->set_id($array[Link::get_id_index()]);
      $this->set_name($array[Link::get_name_index()]);      
      $this->set_popis($array[Link::get_popis_index()]);          
      $this->set_addr($array[Link::get_addr_index()]);      
      $this->set_aktivni($array[Link::get_aktivni_index()]);      
      return true;     
    }    
    return false;
  }

  // vraci pole polozek objektu  
  public function to_array()
  {
      $array = false;
      
      $array[Link::get_id_index()] = $this->id;
      $array[Link::get_addr_index()] = $this->addr;
      $array[Link::get_popis_index()] = $this->popis;
      $array[Link::get_aktivni_index()] = ($this->aktivni != false ? 1 : 0);
      $array[Link::get_name_index()] = $this->_name;
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
            name => ".$this->_name." <br>
            popis => ".$this->popis." <br>
            addr => ".$this->addr."(".Util::timestamp_to_date($this->addr).") <br>
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
    if(!Test::is_text($this->_name,100))
    {
      $return = $false;
      $this->name_err = "Parametr \"Name\" musí být vyplněn a má omezenou délku na 100 znaků.";
    } 

    if(!Test::is_text($this->popis,500))
    {
      $return = $false;
      $this->popis_err = "Parametr \"Popis\" musí být vyplněn a má omezenou délku na 500 znaků.";
    } 
                    
    if(!Test::is_text($this->addr,500))
    {
      $return = $false;
      $this->addr_err = "Parametr \"Addr\" musí být vyplněn a má omezenou délku na 500 znaků.";
    } 

    if(!Test::is_bool($this->aktivni))
    {
      $return = $false;
      $this->aktivni_err = "Povolené hodnoty parametru \"Zobrazit na webu\" jsou [0,1,true,false].";
    } 

    return $return;
  }

//  ------------------
//  ---- SPECIAL -----
//  ------------------
 

} // End Class

?>
