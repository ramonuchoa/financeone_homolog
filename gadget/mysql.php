<?php
function conexao()
{
  try {
    $pdo = new PDO("mysql:host=192.168.67.15;dbname=financeo_f101", 'financeo', 'financeone');
    
    return $pdo;
  } catch (Exception $e) {
    //echo '<!--', $e->getMessage() ,'-->';
	exit;
    return false;
  }
}
?>
