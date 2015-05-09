<?php

class Util
{
  // konstanty
  const iSIT_AUTH_RW = "0";
  const iSIT_AUTH_R = "1";
  const iSIT_AUTH_BAD_LOGIN = "100";
  const iSIT_AUTH_BAD_PASSW = "200";
  const iSIT_AUTH_NO_AUTH = "300";
  const iSIT_AUTH_NO_LOGED = "400";
  const iSIT_AUTH_IS_LOGED = "1000";




  // kdyz uz je uzivatel prihlasen, pouzije udaje ze SESSION
  // kdyz ne, tak se pokusi ziskat jmeno z browseru a...
  static function get_auth()
  {  
    if(isset($_SESSION["isit_auth"]["auth"]))
    {
      return $_SESSION["isit_auth"]["auth"];
    }    
    return Util::iSIT_AUTH_NO_LOGED;
  }

  static function get_auth_name()
  {  
    if(isset($_SESSION["isit_auth"]["name"]))
    {
      return $_SESSION["isit_auth"]["name"];
    }    
    return "err:no loged";
  }
  
  static function login($name, $passw)
  {      
    $rep = new Repository("./");    
    $auth = $rep->get_isit_auth($name, $passw);
    
    if(($auth == Util::iSIT_AUTH_R)OR($auth == Util::iSIT_AUTH_RW))
    {
      $_SESSION["isit_auth"]["auth"]=$auth;
      $_SESSION["isit_auth"]["name"]=$name;
    }    
    return $auth;
  }
  
  static function logout()
  {      
    $_SESSION["isit_auth"]["auth"]=Util::iSIT_AUTH_NO_LOGED;
    $_SESSION["isit_auth"]["name"]="";
    return Util::iSIT_AUTH_NO_LOGED;
  }
    
  // prevede string na mama pismena, vcetne ceske diakritiky
  static function str_to_lower($string)
  {
    $string = strtolower($string);

    $string = str_replace("Á","á", $string);
    $string = str_replace("Č","č", $string);
    $string = str_replace("Ď","ď", $string);
    $string = str_replace("Ě","ě", $string);
    $string = str_replace("É","é", $string);
    $string = str_replace("Í","í", $string);
    $string = str_replace("Ň","ň", $string);
    $string = str_replace("Ó","ó", $string);
    $string = str_replace("Ř","ř", $string);
    $string = str_replace("Š","š", $string);
    $string = str_replace("Ť","ť", $string);
    $string = str_replace("Ů","ů", $string);
    $string = str_replace("Ú","ú", $string);
    $string = str_replace("Ý","ý", $string);
    $string = str_replace("Ž","ž", $string);


/*    
    $string = str_replace("Á","a", $string);
    $string = str_replace("Č","c", $string);
    $string = str_replace("Ď","d", $string);
    $string = str_replace("Ě","e", $string);
    $string = str_replace("É","e", $string);
    $string = str_replace("Í","i", $string);
    $string = str_replace("Ň","n", $string);
    $string = str_replace("Ó","o", $string);
    $string = str_replace("Ř","r", $string);
    $string = str_replace("Š","s", $string);
    $string = str_replace("Ť","t", $string);
    $string = str_replace("Ů","u", $string);
    $string = str_replace("Ú","u", $string);
    $string = str_replace("Ý","y", $string);
    $string = str_replace("Ž","z", $string);

    $string = str_replace("á","a", $string);
    $string = str_replace("č","c", $string);
    $string = str_replace("ď","d", $string);
    $string = str_replace("ě","e", $string);
    $string = str_replace("é","e", $string);
    $string = str_replace("í","i", $string);
    $string = str_replace("ň","n", $string);
    $string = str_replace("ó","o", $string);
    $string = str_replace("ř","r", $string);
    $string = str_replace("š","s", $string);
    $string = str_replace("ť","t", $string);
    $string = str_replace("ů","u", $string);
    $string = str_replace("ú","u", $string);
    $string = str_replace("ý","y", $string);
    $string = str_replace("ž","z", $string);
*/
    return $string;    
  }

  // mac adresu prevede do tvaru XX:XX:XX:XX:XX:XX
  static function to_friendly_mac($mac, $oddelovac=":")
  {
    if(Test::is_mac_address($mac))
    {
      $mac = Util::to_real_mac($mac);
      $mac = strtoupper($mac);
      return  $mac[0].$mac[1].$oddelovac.$mac[2].$mac[3].$oddelovac.$mac[4].$mac[5].$oddelovac.$mac[6].$mac[7].$oddelovac.$mac[8].$mac[9].$oddelovac.$mac[10].$mac[11];
    }
    return $mac;
    die("Util::to_friendly_mac(\$mac): parametr \$mac neobsahuje platnou MAC adresu: \"$mac\".");
  }

  // v mac adrese vypusti oddelovace odelovac 
  static function to_real_mac($mac)
  {
    $mac = trim($mac);
    if(Test::is_mac_address($mac))
    {
      $mac = str_replace(" ","", $mac);
      $mac = str_replace(":","", $mac);
      $mac = str_replace("-","", $mac);
      $mac = strtolower($mac);
      return $mac;
    }
    die("Util::to_real_mac(\$mac): parametr \$mac neobsahuje platnou MAC adresu: \"$mac\".");
  }

  // prevede datum ve formatu dd/mm/rrrr (mozne oddelovace "/", ".", "-") na timestamp
  static function date_to_timestamp($datum,$oddelovac="/")
  {
    $date = str_replace(" ","", $datum);
    //$date = explode('[/.-]', $date);
    $date = explode($oddelovac, $date);
    if(is_array($date) and (count($date)>=3))
    {
      if(list($den,$mesic,$rok)=$date)
      {
        return mktime(0,0,0,$mesic,$den,$rok);
      }
    }
    return false;
  }  
  
  // prevede timestamp do formatu datum
  static function timestamp_to_date($stamp, $style = "dd/mm/rrrr")
  {
    $date = false;
    if($style == "dd/mm/rrrr") $date = date("d/m/Y", $stamp);
    if($style == "d.m. rrrr") $date = date("j.n. Y", $stamp);    
    return $date;
  }

  // text odkaz@(adresa) prevede na <a href="adresa">odkaz</a>
  static function decode_link($text, $class="")
  {        
    if($class !="") $class = "class = \"$class\"";
    $vystup ="";
    $pole = explode(" ",$text);
    
    foreach($pole as $value)
    {
      if(stripos($value, "@(")!=false)
      {
        $odkaz = substr($value,0,stripos($value,"@("));
        $adresa = substr($value,stripos($value,"@(")+2,strlen($value)-strlen($odkaz)-2);
        $p2 = explode(")",$odkaz);
        $zbytek = "";
        $zbytek = substr($adresa,stripos($adresa,")")+1);
        $adresa = substr($adresa,0,stripos($adresa,")"));
        $value = "<a href=\"$adresa\">$odkaz</a>$zbytek"; 
      }
      $vystup.=$value." ";
    }  
    
    return $vystup;
  }
  // zjisti zda je promena $obj objekta a zda je instanci tridy $class_name
  static function is_instance_of($obj, $class_name)
  {
    if(!is_object($obj)) return false;
    if(get_class($obj) != $class_name) return false;
    return true;    
  }  
  // zjisti zda jsou shodne nazvy souboru $odkaz1 a $odkaz2 shodne
  static function je_aktivni($odkaz1, $odkaz2)
  {
    if(($odkaz1 == "")OR($odkaz2 == "")) return false;
        
    $odkaz1 = explode("/", $odkaz1);
    $odkaz2 = explode("/", $odkaz2);
    
    if(end($odkaz1) == end($odkaz2)) return true;
    
    return false;
  }
  // odstrani nezbezpecne znaky z promenne $input
  static function filtr_input($input)
  {
      $input = str_replace("|","",$input);
      $input = str_replace("\\","",$input);
      $input = str_replace("\"","",$input);
      $input = str_replace("'","",$input);
      $input = str_replace("$","",$input);
      $input = str_replace("~","",$input);
      //$input = str_replace(">","(větší než)",$input);
      //$input = str_replace("<","(menší než)",$input);
      return $input;
  }
  // odstrani nebezpecne znaky z $_POST a $_GET
  static function filtruj_vstup()
  {
    foreach ($_GET as $key => $val)
    {
      $_GET[$key]=Util::filtr_input($val);
    } 
  
    foreach ($_POST as $key => $val)
    {
      $_POST[$key]=Util::filtr_input($val);
    } 
  }

  static function get_login()
  {
    if(!isset($_SESSION["jmeno"]))
    {
      return "";
    }    
    
    return $_SESSION["jmeno"];    
  } 

  static function get_token()
  {
    if(!isset($_SESSION["token"]))
    {
      return false;
    }    
    
    return $_SESSION["token"];    
  } 

  static function create_token()
  {
    $token = rand ( 1000000 , 9999999 );
    $_SESSION["token"]= $token;
    return $token;
  } 
  
  static function check_token($token)
  {
    if(!isset($_POST["token"])) return true;
         
    if($_POST["token"] == $token)
    {
      return true;
    }
    
    return false;
  }

  static function prihlasen()
  {
    if(isset($_SESSION["prihlasen"]))
    {
      if($_SESSION["prihlasen"]==true)
      {
        return true;
      }
    }
    return false;
  }

  static function log_in($login="SuperAdmin")
  {
    $_SESSION["prihlasen"]=true;
    $_SESSION["id"]=999;
    $_SESSION["opravneni"]=1;
    $_SESSION["jmeno"]=$login;
    //Util::create_token(); 
  }
    
  static function log_out()
  {
    $_SESSION["prihlasen"]=false;
    $_SESSION["id"]="";
    $_SESSION["opravneni"]="";
    $_SESSION["jmeno"]="";          
    if(isset($_SESSION["prihlasen"])) session_destroy();
  }


  
  // mozna prevest na private
  static function resize_image($uri_src, $uri_dest, $max_res)
  {    
    $imageResized = false;
    $originalImage = $uri_src;
    if(is_file($originalImage))
    {
      if(list($width, $height) = getimagesize($originalImage))
      {
        if($width>$height)
        {
          $wscale = $width / $max_res;
          $newWidth = $max_res;
          $newHeight = round($height/$wscale);
        }
        else
        {
          $hscale = $height / $max_res;
          $newHeight = $max_res;
          $newWidth = round($width/$hscale);
        }
        $imageResized = imagecreatetruecolor($newWidth, $newHeight);
        $imageTmp     = imagecreatefromjpeg ($originalImage);
        imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
      }
      else
      {
        echo "Util::resize_image(): Nepodařilo se načíst obrázek $originalImage<br>";
      }
    }
    else
    {
      echo "Util::resize_image(): Nepodařilo se načíst obrázek $originalImage<br>";
    }
    if($imageResized!=false)
    {
      //echo "Vytvářím soubor ".$uri_dest."... ";
      imagejpeg($imageResized, $uri_dest, 80);
      //echo "   ...hotovo<br>";
    }
    else
    {
      echo "Util::resize_image(): Nebyl předán obrázek!<br>";
    }
  }    
  
} // end Class

?>
