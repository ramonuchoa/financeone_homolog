<?php
class WidgetConversor extends WP_Widget
{
  function WidgetConversor()
  {
    global $wpdb;
    
    parent::WP_Widget(false, $name = 'Conversor de Moedas', array('classname' => 'conversor', 'description' => 'Conversor de moedas do site'));
  }
  
  function widget($args, $instance)
  {
    $tpl = file_get_contents(dirname(__FILE__) . '/widget.tpl.php');
    
    // config
    $tpl_vars['template_url'] = get_bloginfo('template_url');
    $tpl_vars['action']       = get_bloginfo('siteurl').'/moedas/conversor-de-moedas';
    $tpl_vars['url']          = get_bloginfo('siteurl');
    $tpl_vars['valor']        = 1;
    
    $moedas_de    = conv_get_moedas(25, 'de');
    $moedas_para  = conv_get_moedas(25, 'para');
    $options_de   = array();
    $options_para = array();
    
    foreach ($moedas_de as $moeda) {
      $options_de[strtoupper($moeda->pais)] = ucwords(strtolower($moeda->pais)).', '.ucwords(strtolower($moeda->nome)).' ('.$moeda->code.')';
    }
    
    foreach ($moedas_para as $moeda) {
      $options_para[strtoupper($moeda->pais)] = ucwords(strtolower($moeda->pais)).', '.ucwords(strtolower($moeda->nome)).' ('.$moeda->code.')';
    }
    
    $tpl_vars['origem']   = conv_build_select('conv_from', $options_de, 'BRASIL');
    $tpl_vars['destino']  = conv_build_select('conv_to', $options_para, 'ESTADOS UNIDOS');
    
    // aplicando variáveis ao template
    foreach ($tpl_vars as $key => $value) {
      $tpl = str_replace('{'.$key.'}', $value, $tpl);
    }
    
	  echo '<div class="widgetcontainer conversor clearfix">
	  <h2 class="widgettitle">Conversor de Moedas</h2><div class="widgetcontent">';
    echo $tpl;
	  echo '</div></div></div>';
  }
}

add_action('widgets_init', create_function('', 'return register_widget("WidgetConversor");'));
?>