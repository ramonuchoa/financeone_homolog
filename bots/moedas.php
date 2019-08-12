<?php
ini_set('user_agent','Mozilla/5.0(X11; Ubuntu; Linux x86_64; rv:58.0)'); 
require_once('library/mysql.php');
require_once('library/util.php');
require_once('library/logger.php');

$db       = new Bot_MySQL();
$logger   = Bot_Logger::getInstance('moedas');
$hoje =  date_create(date("Y-m-d"));
$fechamento = date_format(date_sub($hoje, date_interval_create_from_date_string('1 day')), 'Ymd');

$arquivo = "${fechamento}.csv";
// $data = '20171116';
$url      = "http://www4.bcb.gov.br/Download/fechamento/" . $arquivo;
$contents = Bot_Util::get_url($url, $logger);
$data = date('Y-m-d');
// $data = '2017-11-16';

foreach (explode("\n", $contents) as $line) {
  $line = str_replace(',', '.', $line);
  $cols = explode(';', $line);
  if (trim($cols[0]) != '') {
    $moeda  = $cols[3];
    $venda  = Bot_Util::strip_moeda($cols[5]);
    $compra = Bot_Util::strip_moeda($cols[4]);
    
    if (isset($moeda) && isset($venda) && isset($compra)) {
      $valor = $db->moedaByDate($moeda, $data);      
      if (!$valor) {
        $logger->log('Inserindo moeda ' . $moeda . ' com data ' . $data . ' e valores ' . $venda . ' - '. $compra);
        $db->insertMoeda($moeda, $data, $venda, $compra);
      } else {        
        $logger->log('Atualizando moeda ' . $moeda . ' com data ' . $data . ' e valores ' . $venda . ' - '. $compra);
        $db->updateMoeda($moeda, $data, $venda, $compra);
      }
    }
  }
}

$logger->close();
?>
