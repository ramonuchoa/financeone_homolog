<?php
/**
*   @Class Logger
*   @author Luã de Souza
*/
class Bot_Logger
{
  private static $instance = array();
  
  // Object properties
  public $logs = array();
  private $name = null;
  
  public function __construct($name)
  {
    $this->name = $name;
    $this->log('Log iniciado em ' . date('d/m/Y H:i:s'));
  }
  
  public function log($message)
  {
	
	echo $message . "\r\n";
    $this->logs[] = utf8_decode($message);
  }
  
  public function close()
  {
    // $db = new Bot_Mysql();
    // $db->saveLog($this->name, $this->logs);
  }
  
  public static function getInstance($name)
  {
    if (!array_key_exists($name, self::$instance)) {
      self::$instance[$name] = new Bot_Logger($name);
    }
    
    return self::$instance[$name];
  }
}

?>
