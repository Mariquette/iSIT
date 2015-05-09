<?php

class Test
{ 

  // test pripony souboru, case sensitive
  static function is_file_suffix($file, $suffix)
  {
    if(ltrim(strstr($file,"."),".") == $suffix) 
    {
      return true;
    }
    
    return false;    
  }
  
  // libovolny pocet cislic 0-9
  static function is_number($cislo)
  {
    $cislo = str_replace(" ","",$cislo);
    $atom = '/^[0-9]*$/i';
    return preg_match($atom, $cislo);
  }
  
  static function is_bool($bool)
  {
    if($bool == true) return true;
    if($bool == false) return true;
    if($bool == 1) return true;
    if($bool == 0) return true;
    if($bool == "1") return true;
    if($bool == "0") return true;
    
    return false;
  }
  
  static function is_text($text, $length=0)
  {
    if($text=="") return false;
    if($length>0)
    {
      if(strlen($text)>$length) return false;
    }
    
    return true;
  }
  
  static function is_year($year)
  {
    if((Test::is_number($year)) AND ($year>1900) AND ($year<2100))
    {
      return true;
    }
    return false;
  }

  static function is_date($date, $oddelovac="/")
  {  
    if(Util::date_to_timestamp($date,$oddelovac)>0)
    {
      return true;
    }
    return false;
  }

  static function is_timestamp($timestamp)
  {
    if($timestamp <= 0) return false;
    
    return Test::is_number($timestamp);    
  }
  
  static function is_date_older_then($date1, $date2, $days=0)
  {
    if(!Test::is_date($date1)) die("Util::is_date_older(\$date1, \$date2, \$days=0): \$date1 must be date(d/m/Y).");
    if(!Test::is_date($date1)) die("Util::is_date_older(\$date1, \$date2, \$days=0): \$date2 must be date(d/m/Y).");
    $d1 = Util::date_to_timestamp($date1);
    $d2 = Util::date_to_timestamp($date2);
    
    $days = $days * 24 *60 * 60;
    
    if(($d1 + $days) < $d2) return true;
    
    return false; 
        
  }
  
  // 12 znaku 0-9, a-f, A-F
  static function is_mac_address($mac)
  {
    $mac = strtolower($mac);
    $mac = str_replace(" ","",$mac);
    $mac = str_replace("-","",$mac);
    $mac = str_replace(":","",$mac);
    if(strlen($mac)!=12) return false;
    
    $atom = '/^[0-9a-f]*$/i';
    return preg_match($atom, $mac);
  }

  // xxx.xxx.xxx.xxx (xxx: 0-255)
  static function is_ip_address($ip)
  {
    $ip = explode(".", $ip);    
    if(!is_array($ip)) return false;
    if(count($ip)!=4) return false;
    foreach($ip as $segment)
    {
      if(Test::is_number($segment)==false) return false;
      if($segment > 255) return false;
      if($segment < 0) return false;
    }    
    return true;
  }
  

} // End Class

?>
