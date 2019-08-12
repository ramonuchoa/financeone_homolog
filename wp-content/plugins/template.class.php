<?php
/**
* Classe para uso de templates nos plugins
*
* @package Template
* @author Luã de Souza
*/

class Template
{
  private $tpl;
  private $tpl_vars;
  
  public function __construct($arquivo)
  {
    $this->tpl      = file_get_contents($arquivo);
    $this->tpl_vars = array();
  }
  
  public function addVar($name, $value)
  {
    if (is_array($value)) {
      foreach ($value as $k => $v) {
        $this->tpl_vars[$k] = $v;
      }
    } else {
      $this->tpl_vars[$name] = $value;
    }
  }
  
  public function render()
  {
    foreach ($this->tpl_vars as $key => $value) {
      $this->tpl = str_replace('{'.$key.'}', $value, $this->tpl);
    }
    
    return $this->tpl;
  }
}

?>