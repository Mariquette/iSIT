<?php

  define("DirRoot", "./");
  define("DirName", "");
  define("FileName", "about.php");

  function __autoload($class_name) 
  {
    if(!is_file(DirRoot."Classes/Class".$class_name.'.php')) die("Nelze načíst třídu $class_name! (".DirRoot."Classes/Class$class_name.php)");
    include DirRoot."Classes/Class".$class_name.'.php';
  }
  
  session_start();
  Util::filtruj_vstup();

  $rep = new Repository("./");  
  $stranka = new Sablona(FileName);

  /* --- MAIN --- */
  $obsah_html = "";
  $menu_html = "";
  
  $menu = new Menu(FileName);
  $menu->add_item(new SimpleLink("Home","./index.php"));
  $menu->add_item(new SimpleLink("Notes","./events.php"));
  $menu->add_item(new SimpleLink("Locations","./locations.php"));
  $menu->add_item(new SimpleLink("Persons","./persons.php"));
  $menu->add_item(new SimpleLink("Computers","./computers.php"));
  $menu->add_item(new SimpleLink("Printers","./printers.php"));
  $menu->add_item(new SimpleLink("Links","./links.php"));
  $menu->add_item(new SimpleLink("|",""));
  $menu->add_item(new SimpleLink("Utils","./utils.php"));
  $menu->add_item(new SimpleLink("|",""));  
  $menu->add_item(new SimpleLink("About","./about.php"));
  $menu_html.=$menu->get_html();

  // autentifikace
  $_auth = Util::get_auth();
  
  /* --- //// --- */
  OUTPUT:
    
    
    $obsah_html .= '
    
    <div class="informace">
      <h3>iSIT plánované rozšíření</h3>
      <ul>
        <li>
          <h4>General</h4>
          <ul>
            <li>vyresit chovani aplikace pri odpojenych externich zdrojich (cache?)|aktualizace pouze na pozadani?</li>
            <li>podchytit stav kdy je poda vyrazovak ke kontrole, ke schvaleni, kdy byl schvalen - pri dohledavani nenavrhnutych to usnadni praci</li>
          </ul>
        </li>
        <li>
          <h4>Computers</h4>
          <ul>
            <li>plánování záloh pro Li, tisknutelný kalendář záloh</li>
            <li>přehled office: porovnání s rozdělovníkem</li>
            <li>získání informace o BPCS sessions z c:\Program Files\...\Private\</li>
            <li>získání informace o naplánovaném zálohování ...\all users\...</li>
            <li>přidání požadavku hw/sw .pdf</li>
          </ul>        
        </li>
        
        <li>
          <h4>Persons</h4>
          <ul>
            <li>přehled o svěřených a používaných zařízeních</li>
          </ul>                
        </li>

        <li>
          <h4>Printers</h4>
          <ul>
            <li>nestandardni chovani pri dlohe prodleve vytvareni noveho zaznamu - skoci jako ze je ok, ale neulozi se</li>
            <li>přidání požadavku hw/sw .pdf</li>
          </ul>                
        </li>        

        <li>        
          <h4>Notes</h4>
          <ul>
            <li>prehlednejsi zobrazeni udalosti</li>
            <li>vylepsit moznosti zapisu strukturovaneho textu</li>
          </ul>                
        </li>
                
        <li>
          <h4>Utils</h4>
          <ul>
            <li>export</li>
          </ul>        
        </li>
      </ul>
      
      
      <hr>
      
      <h3>iSIT v3.2 (open)</h3>
      <ul>
        <li>
          <h4>General</h4>
          <ul>
            <li>presun dat do databaze MySQL</li>
            <li>nacitani dat z LDAP umoznuje filtr podle jmena kontejneru</li>
          </ul>
        </li>
        <li>
          <h4>Computers</h4>
          <ul>
            <li>optimalizace ComputerView::_list()</li>
          </ul>        
        </li>
        <li>
          <h4>Printers</h4>
          <ul>
            <li>implementace seznamu device generovaneho z bpcs (7x1 5:05)</li>          
            <li>optimalizace PrinterView::_list()</li>
          </ul>                
        </li>        
        <li>
          <h4>Persons</h4>
          <ul>
            <li>optimalizace PersonsView::_list()</li>
          </ul>                
        </li>        
        <li>
          <h4>Utils</h4>
          <ul>
            <li>majetek info: moznost generovat navrh na vyrazeni</li>
            <li>export: db_models, db_models records, db_models records (sql like)</li>
          </ul>                
        </li>        
      </ul>      
      <hr>
      
      <h3>iSIT v3.1 (closed 2013-10-10)</h3>
      <ul>
        <li>
          <h4>Global</h4>
          <ul>
            <li>základní ověření přístupu (user/passw)</li>
            <li>základní rozdělení přístupových práv (read-only|read-write)</li>
          </ul>                
        </li>
        <li>
          <h4>Computers</h4>
          <ul>
            <li>moznost tisku návrhu na vyřazení</li>
            <li>vylepšený přehled nezařazených PC</li>
            <li>řazení pohledu seznam podle data pořízení, modelu</li>
            <li>vylepšení plánování zálohování pro HK (tabulka standardních záloh, seznam nestandardních záloh, seznam pc, které nemají naplánovanou zálohu)</li>
          </ul>        
        </li>
        <li>
          <h4>Printers</h4>
          <ul>
            <li>moznost tisku návrhu na vyřazení</li>
            <li>řazení pohledu seznam podle data pořízení, modelu</li>
          </ul>                
        </li>        
        <li>
          <h4>Persons</h4>
          <ul>
            <li>přidání požadavku hw/sw ve formátu .pdf</li>
          </ul>                
        </li>        
        <li>
          <h4>Utils</h4>
          <ul>
            <li>přehled požadavků hw/sw ve formátu .pdf</li>
          </ul>                
        </li>        

      </ul>
      
      <hr>

      <h3>iSIT v3.0</h3>
      <ul>
        <li>
          <h4>Global</h4>
          <ul>
            <li>optimalizace přístupu k datovým položkám</li>
            <li>rozdělení Views</li>
            <li>unifikace modelových tříd</li>
          </ul>                
        </li>
        <li>
          <h4>Computers</h4>
          <ul>
            <li>přehled a plánování zálohování počítačů (přidat/odebrat počítač z rozvrhu)</li>
            <li>poznámky (přidat/odebrat)</li>
          </ul>        
        </li>
        <li>
          <h4>Persons</h4>
          <ul>
            <li>poznámky (přidat/odebrat)</li>
          </ul>                
        </li>
        <li>
          <h4>Printers</h4>
          <ul>
            <li>seznam uživatelů (přidat / odebrat uživatele)</li>
            <li>dynamické doplnění BPCS device</li>
            <li>kontrola využití přidělených device (nepoužité device, více device ukazuje na jednu IP)</li>
            <li>poznámky (přidat/odebrat)</li>
            <li>upload dodák, vyřazovák</li>
          </ul>                
        </li>
        <li>
          <h4>Notes</h4>
          <ul>
            <li>detailní a obecný seznam událostí</li>
          </ul>                
        </li>
        <li>
          <h4>(new) Links</h4>
          <ul>
            <li>jednoduché vkládání odkazů s popisem</li>
          </ul>                
        </li>        
        <li>
          <h4>(new) Utils</h4>
          <ul>
            <li>import z .CSV pro Event, Person, Computer, BackupSchedule, Printer, PrinterUse, Link</li>
          </ul>                
        </li>        
      </ul>
      <hr>

      <h3>iSIT v2.0</h3>
      <ul>
        <li>
          <h4>Computers</h4>
          <ul>
            <li>možnost připojit dodací list (.pdf)</li>
            <li>možnost připojit vyřazovací protokol (.pdf) -> automatický přesun do vyřazených</li>
            <li>vylepšený přehled zálohovaných a nezálohovaných PC</li>
            <li>přehled vyřazených počítačů (zůstává část informací z majetku)</li>
            <li>přehled office (zastaralé MS nebo Free Office, nepřítomnost FreeOffice, verze MS a Free Office) - plánuje se porovnání s rozdělovníkem</li>
          </ul>        
        </li>
        
        <li>
          <h4>Persons</h4>
          <ul>
            <li>kontrola přítomnosti osobního čísla v doménovém účtu</li>
            <li>možnost vložení osobního čísla do domény</li>
          </ul>                
        </li>        

        <li>
          <h4>(new) NetDevices</h4>
          <ul>
            <li>přehled rezervací na DHCP serverech</li>
          </ul>                
        </li>        

      </ul>
      <hr>

      <h3>iSIT v1.0</h3>
      <ul>
        <li>
          <h4>Computers</h4>
          <ul>
            <li>persistentní informace sériové, evidenční číslo, model, datum pořízení</li>
            <li>kombinuje data z Landesku, majetku, domény a personálního systému</li>
            <li>online doplnění jména počítače z domény</li>
            <li>podrobnější informace z databáze majetku (cena požízení, zůstatková cena, zodpovědná osoba)</li>
            <li>rychlý odkaz na informace o záruce (pouze Dell)</li>
            <li>rychlý odkaz na zdroj driveru(pouze Dell)</li>
            <li>identifikace počítačů nezařazených do iSIT (pomocník při párování: jméno PC, sériové číslo, inventární číslo)</li>
            <li>identifikace počítačů nezařazených do Landesku</li>
            <li>identifikace počítačů vyřazených z majetku</li>
            <li>přehled zálohovaných PC s možností trigrování (velikost zálohy, datum poslední zálohy, počet souborů)</li>
            <li>přehled nezálohovaných PC</li>
            <li>inventura (pc po odejitých zaměstnancích, špatně pojmenované PC, vyřazené PC, špatně přidělené PC)</li>
          </ul>        
        </li>
        
        <li>
          <h4>Persons</h4>
          <ul>
            <li>persistentní informace celé jméno, login, osobní číslo, pobočka</li>
            <li>kombinuje data domény a personálního systému</li>
            <li>identifikace doménových účtů, které nejsou v iSIT (doplnění inforamcí z personálního systému)</li>
            <li>identifikace zastaralých doménových účtů</li>
            <li>identifikace odejitých zaměstnanců</li>
          </ul>                
        </li>
        
        <li>
          <h4>Printers</h4>
          <ul>
            <li>persistentní informace model tiskárny, IP adresa, MAC adresa, sériové číslo, datum pořízení</li>
            <li>kombinuje data z WebJetAdminu, DHCP serveru, majetku a personálního systému</li>
            <li>dodatečné informace z majetku (cena pořízení, zůstatková cena, zodpovědná osoba)</li>
            <li>v plánu možnost přidávání několika uživatelů (užitečné při výměně tiskárny)</li>
            <li>identifikace zařízení nezařazených do iSIT (DHCP, WebJetAdmin)</li>
            <li>přehled rezervovaných IP adres na DHCP serverech</li>
          </ul>                
        </li>

        <li>
          <h4>Notes</h4>
          <ul>
            <li>vytvareni, editace, planovani</li>
            <li>moznost vkladat odkazy</li>
          </ul>                
        </li>

      </ul>
      <hr>

      <h2>iSIT příklady užití</h2>
      <ul>
        <li>
          Kontrola počítačů, které nejsou v Landesku.
        </li>
        
        <li>
          Kontrola starých doménových účtů uživatelů a počítačů.
        </li>

        <li>
          Kontrola zálohování počítačů.
        </li>        

        <li>
          Kontrola nově přidaných počítačů a uživatelů.
        </li>        

        <li>
          Kontrola, kdo, který počítač skutečně používá.
        </li>        

        <li>
          Kontrola SW vybavenosti (Office). 
        </li>        

        <li>
          Přehled o počtech a stáří počítačů a tiskáren.
        </li>        

        <li>
          Přehled důležitých síťových zařízení (rezervace DHCP).
        </li>        

      </ul>
      <hr>
    
<h2>iSIT Rules</h2>
      <ul>
        <li>jmeno pocitace je odvozeno od uzivatelskeho jmena
          <ul>
            <li>dulezite pri inventure (parovani pocitac - uzivatel)</li>
            <li>pr. k5doubrav | doubrav</li>
            <li>pr. h5tomasovai | tomasovai</li>
          </ul>
        </li>
        
        <li>login domenoveho uctu je odvozen od prijmeni zamestnance, v pripade duplicity jsou pouzita pismena z krestniho jmena popripade cislice
          <ul>
            <li>dulezite pri prohledavani domeny proti personalnimu systemu / prirazovani neznamych pocitacu</li>
            <li>pr. Doubrav Lukas -> doubrav, doubravl, doubravl3</li>
            <li>spatne: Doubrav Lukas -> doubrav.l</li>
          </ul>
        </li>

        <li>sitove tiskarny maji nastavenu pevnou IP a soucasne maji rezervaci MAC na DHCP
          <ul>
            <li>pri vypadku dhcp se nemusi tiskarna restartovat</li>
            <li>prehled o dulezitych sitovych zarizenich</li>
          </ul>  
        </li>

        <li>dulezita sitova zarizeni maji rezervaci na DHCP
          <ul>
            <li>notebooky, vahy, switche, prevodniky (terminal), tiskarny</li>
          </ul>
        </li>        

        <li>
          adresare pro zalohu pomoci Symantec Backup Exec jsou nazvany ServiceTagem pocitace
        </li>        

        <li>
          ucty pocitacu a uzivatelu maji v kolonce description nazev pobocky (vyuziva prohledavani novych zarizeni)
        </li>        

        <li>
          v Landesku nesmi byt vice zaznamu o jednom pocitaci (ruzne nazvy, stejny STag)
        </li>        

      </ul>
      <hr>    

     
    </div>'; 

    if(Util::get_auth()!=Util::iSIT_AUTH_NO_LOGED)
    {
      $obsah_html.='<a class="odhlasit" href= "login.php">Odhlásit</a>';
    }    
    echo $stranka->get_html($obsah_html, $menu->get_html());
   

?>

 <select name="cars">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="fiat">Fiat</option>
  <option value="audi">Audi</option>
</select>
