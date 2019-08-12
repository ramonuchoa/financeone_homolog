<?php
echo "<?phpxml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<?php
require dirname(__FILE__).'/mysql.php';
$pdo = conexao();

$row  = "";
$nome = "";

$nomes  = array();
$paises = array();

$sql = "SELECT DISTINCT MN.code, MN.pais, MN.nome FROM moedas_nome MN inner join moedas_dado MV on MN.code = MV.code order by MN.para DESC, MN.pais ASC LIMIT 0,25";


$moedas = $pdo->query($sql)->fetchAll();

if ($moedas) :
	$tm = count($moedas);
	$i  = 0;
	
	foreach ($moedas as $moeda) {
	  $_code      = $moeda['code'];
		$_moeda     = $moeda['nome'];
		$_pais      = $moeda['pais'];
		$paises[$i] = $_pais;
		
		$_moeda     = strtolower($_moeda);
		$_moeda     = ucwords($_moeda);
		$_pais      = strtolower($_pais);
		$_pais      = ucwords($_pais);
		$nomes[$i]  = "$_pais, $_moeda ($_code)";
		$i++;
	}
	
	asort ($nomes);
	reset ($nomes);
?>
<messagebundle>
  <msg name="p0">Brasil, Real (BRL)</msg> 
  <msg name="p0v">Brasil</msg> 
<?php
	$i = 1;
	foreach ($nomes as $key => $val) {
?>
  <msg name="p<?php echo $i ?>"><?php echo $val ?></msg> 
  <msg name="p<?php echo $i ?>v"><?php echo $paises[$key] ?></msg> 
<?php
		$i++;
	}
?>
  <msg name="p<?php echo $i ?>">(Other countries...)</msg> 
  <msg name="p<?php echo $i ?>v">XXX</msg> 
<?php
endif;
?>
  <msg name="titulo">FinanceOne: Currency Converter</msg> 
  <msg name="quero">I want to convert the value of:</msg> 
  <msg name="valoraser">(value to be converted)</msg> 
  <msg name="de">...from the currency:</msg> 
  <msg name="origem">(origin currency)</msg> 
  <msg name="para">...to the currency:</msg> 
  <msg name="destino">(destination currency)</msg> 
  <msg name="submit">Convert Now!</msg> 
  <msg name="carrega">Loading..</msg> 
</messagebundle>