<?php
class WidgetCotacoes extends WP_Widget
{
    public function __construct()
    {
        return $this->WidgetCotacoes();
    }
    
  function WidgetCotacoes()
  {
    global $wpdb;
    
    parent::WP_Widget(false, $name = 'Cotações', array('classname' => 'cotacoes', 'description' => 'Cotações do euro, dolar e da bolsa de valores'));
  }
  
  function widget($args, $instance)
  {
    $tpl = file_get_contents(dirname(__FILE__) . '/widget.tpl.php');
    
    // Dolar
    $cotacao_dolar  = Cotacoes::dolar();
    $data_dolar     = '';

    foreach ($cotacao_dolar as $cota) {
      $dolar .= '<tr>';
      $dolar .= '<td class="tipo">'.$cota->tipo.'</td>';
      $dolar .= '<td class="'.(substr($cota->var, 0, 1) == '-' ? 'negativo' : 'positivo').'">'.$cota->var.'  %</td>';
      $dolar .= '<td>R$ '.$cota->compra.'</td>';
      $dolar .= '<td>R$ '.$cota->venda.'</td>';
      $dolar .= '</tr>';
      
      if ($data_dolar == '') $data_dolar = $cota->data;
    }
    
    $tpl_vars['dolar'] = $dolar;
    
    $data_dolar = new DateTime($data_dolar);
    $tpl_vars['data_dolar'] = $data_dolar->format('d/m/Y \à\s H:i');
    
    // Bolsa
    $cotacao_bolsa = Cotacoes::bolsa();
    
    foreach ($cotacao_bolsa as $cota) {
      if ($cota->code == 'data') continue;
      
      if ($cota->tipo == '+') $tipo = 'positivo'; else if ($cota->tipo == '-') $tipo = 'negativo';
      $flag = get_bloginfo('url').'/wp-content/themes/financeone/imagens/flags/'.$cota->flag.'.gif';
      
      $bolsa .= '<tr>';
      $bolsa .= '<td class="flag"><img src="'.$flag.'" alt="'.$cota->flag.'" />';
      $bolsa .= '<td class="tipo">'.$cota->code.'</td>';
      $bolsa .= '<td class="'.$tipo.'">'.$cota->var.'</td>';
      $bolsa .= '<td>'.substr($cota->pontos, 0, -3).'</td>';
      $bolsa .= '</tr>';
    }
    
    $tpl_vars['bolsa'] = $bolsa;
    
    $data_bolsa = new DateTime($cotacao_bolsa[0]->data);
    $tpl_vars['data_bolsa'] = $data_bolsa->format('H:i');
    
    // Poupança
    $cotacao_poupanca = Cotacoes::poupanca();

    foreach ($cotacao_poupanca as $cota) {
      $data = new DateTime($cota->data);
      $poupanca .= '<tr>';
      $poupanca .= '<td class="tipo">'.$data->format('d/m/Y').'</td>';
      $poupanca .= '<td>R$ '.$cota->rend.'</td>';
      $poupanca .= '</tr>';
    }

    $tpl_vars['poupanca'] = $poupanca;
    
    // aplicando variáveis ao template
    foreach ($tpl_vars as $key => $value) {
      $tpl = str_replace('{'.$key.'}', $value, $tpl);
    }
    
    
    
    echo $before_widget;
    echo $tpl;
    echo $after_widget;
  }
}
  
add_action('widgets_init', create_function('', 'return register_widget("WidgetCotacoes");'));
?>
