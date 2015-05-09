<?php
/**
 *  Class Timer
 *  - model
 *  - pro mereni doby trvani nekterych operaci 
 */    
 
class Timer
{
             
  private $start_time;
  private $end_time;  
  private $time;   
    
    // konstruktor
    public function __construct()
    {    
      $this->start_time = microtime(true);
      $this->end_time = 0;
      $this->time = 0;
    }
  
  
    public function start()
    {
      $this->start_time = microtime(true);
      $this->end_time = 0;
      $this->time = 0;
    }
    
    public function stop()
    {
      $this->end_time = microtime(true);
      $this->time = $this->end_time - $this->start_time;
      $this->start_time = 0;
      $this->end_time = 0;
    }
    
    public function get_time($text="")
    {
      return $text.$this->time;
    }

    public function echo_time($text="")
    {
      echo $text.number_format($this->time,3,".","")."<br>";
      return $text.(float)$this->time."<br>";
    }


} // End Class

?>
