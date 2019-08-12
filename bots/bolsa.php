<?php
ini_set('user_agent','Mozilla/5.0(X11; Ubuntu; Linux x86_64; rv:58.0)'); 
require_once('library/mysql.php');
require_once('library/util.php');
require_once('library/logger.php');
require_once('library/Zend/Dom/Query.php');

$db       = new Bot_MySQL();
$logger   = Bot_Logger::getInstance('bolsa');
$url      = 'http://economia.terra.com.br/mercados/indices/';
$contents = Bot_Util::get_url($url, $logger);
$dom      = new Zend_Dom_Query($contents);

$table_lines  = $dom->query('#CuerpoModulo > table tr');
$dados        = array();

foreach ($table_lines as $line) {
  $dados[] = utf8_decode($line->nodeValue);
}

$dados = array_map(array('Bot_Util', 'strip_bolsa'), array_slice($dados, 2));

$bolsa['IBovespa']['pontos']    = $dados[0][1];
$bolsa['IBovespa']['var']       = $dados[0][3];
$bolsa['IBovespa']['hora']      = $dados[0][4];

$bolsa['Dow Jones']['pontos']   = $dados[11][1];
$bolsa['Dow Jones']['var']      = $dados[11][3];
$bolsa['Dow Jones']['hora']     = $dados[11][4];

$bolsa['Nasdaq']['pontos']      = $dados[12][1];
$bolsa['Nasdaq']['var']         = $dados[12][3];
$bolsa['Nasdaq']['hora']        = $dados[12][4];

$bolsa['Merval']['pontos']      = $dados[18][1];
$bolsa['Merval']['var']         = $dados[18][3];
$bolsa['Merval']['hora']        = $dados[18][4];

$bolsa['IPC']['pontos']         = $dados[22][1];
$bolsa['IPC']['var']            = $dados[22][3];
$bolsa['IPC']['hora']           = $dados[22][4];

$bolsa['IPSA']['pontos']        = $dados[30][1];
$bolsa['IPSA']['var']           = $dados[30][3];
$bolsa['IPSA']['hora']          = $dados[30][4];

$bolsa['Nikkei']['pontos']      = $dados[37][1];
$bolsa['Nikkei']['var']         = $dados[37][3];
$bolsa['Nikkei']['hora']        = $dados[37][4];

$logger->log('Dados capturados: ' . serialize($bolsa));

$db->bolsa($bolsa);

$logger->close();
?>
