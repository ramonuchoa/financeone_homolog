<?php
error_reporting (E_ALL ^ E_NOTICE);
require dirname(__FILE__).'/mysql.php';
$pdo = conexao();

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<Module>
	<ModulePrefs 
		title="FinanceOne: Currency Converter" 
		directory_title="Currency Converter / Conversor de Moedas / Convertidor de Divisas" 
		title_url="http://www.financeone.com.br/?prv=gadget"
		author="FinanceOne.com.br"
		author_email="info@financeone.com.br"
                                screenshot="http://www.financeone.com.br/gadget/screenshot.gif"
thumbnail="http://www.financeone.com.br/gadget/thumb.gif"
description="FinanceOne is a Currency Converter tool that you can perform exchange rate calculations for over 200 currencies/countries, including Dollar, Euro, Yen, Pound, Yuan, Peso and others.. / Serviço de Conversão de Moedas para realizar cálculos de câmbio entre mais de 200 países, incluindo Dolar, Euro, Iene, Libra, Yuan Chines, Peso, Real entre outros.. / "
		author_affiliation=""
		author_location="Brazil"
		height="180"
	>
                                <Locale messages="http://www.financeone.com.br/gadget/ALL_ALL.php"/>
                                <Locale lang="pt" messages="http://www.financeone.com.br/gadget/pt_ALL.php"/>
                                <Locale lang="pt-BR" messages="http://www.financeone.com.br/gadget/pt-BR_ALL.php"/>
		<Require feature="minimessage"/>
		<Require feature="setprefs" />
                                <Require feature="settitle" />
	</ModulePrefs>
  	<UserPref name="qtd" default_value="1" datatype="hidden"/>
  	<UserPref name="deIndex" default_value="0" datatype="hidden"/>
  	<UserPref name="paraIndex" default_value="11" datatype="hidden"/>
<Content type="html">
<![CDATA[
<style type="text/css">
<!--
#financeone {
	font-family: Arial;
	font-size: 11px;
	text-align: center;
}
#financeone #copy {
	padding: 5px;
}
#financeone #copy A{
	
	color: navy;
	font-family: Arial;
	font-size: 8px;
	text-align: right;
}
#financeone #de, #para{
	padding: 5px;
	text-align: center;
}
#financeone #quanto {
	padding: 4px;
	text-align: center;
	border-bottom: 1px #A0A0A0 dotted;
	border-top: 1px #A0A0A0 dotted;
}
#financeone #envia {
	padding: 4px;
	border-top: 1px #A0A0A0 dotted;
	text-align: center;
}
#financeone INPUT{  
	font-family: Verdana; 
	font-size: 11px; 
	color: #575757; 
	background-color: #EAEAEA; 
	font-weight: normal; 
	text-align: left; vertical-align: middle; 
	margin: 0px; padding: 0px; 
	border-style: inset; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px
}
#financeone SELECT {  
                width: 250px;
	font-family: Verdana; 
	font-size: 10px; 
	color: #575757; 
	background-color: #EAEAEA; 
	font-weight: normal; 
	text-align: left; vertical-align: middle; 
	margin: 0px; padding: 0px; 
	border-style: inset; border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px
}
#financeone .botao {
	background-image : url("http://www.financeone.com.br/gadget/botao.gif"); 
	font-weight : bold; 
	font-family: Arial; 
	font-size: 11px; 
	height: 20px; width: 135px; 
	border: 0px #666666 solid; color: #333333; 
	background-color: c2c2c2;
	text-align: center;
}
-->
</style>

<? if($lang!="pt-BR") { 
    $sql = "_en";
	$txt_ordenar = "Order by:";
	$txt_pais = "country";
	$txt_moeda = "currency";
	$txt_codigo = "international code";
	$txt_quero = "I want to convert the value of:";
	$txt_valoraser = "(value to be converted)";
	$txt_de = "...from the currency:";
	$txt_origem = "(origin currency)";
	$txt_para = "...to the currency:";
	$txt_destino = "(destination currency)";
	$txt_submit = "Convert Now!";
	$txt_carrega = "Loading..";
	$txt_outros = "(Other countries..)";
	$txt_titulo = "FinanceOne: Currency Converter";
} else {
	$sql = "";
	$txt_ordenar = "Ordenar por:";
	$txt_pais = "país";
	$txt_moeda = "moeda";
	$txt_codigo = "código internacional";
	$txt_quero = "Eu quero converter o valor de:";
	$txt_valoraser = "(valor a ser convertido)";
	$txt_de = "...da moeda:";
	$txt_origem = "(moeda de origem)";
	$txt_para = "...para a moeda:";
	$txt_destino = "(moeda de destino)";
	$txt_submit = "Realizar Conversão";
	$txt_carrega = "Carregando..";
	$txt_outros = "(Listar outros países..)";
	$txt_titulo = "FinanceOne: Conversor de Moedas";
}
if(!$quanto) {
	$quanto = 1;
}
?>


<div id="financeone">
<form align="center" method="POST" action="http://www.financeone.com.br/conversores.php" name="conversor" target="_blank">
	<input type="hidden" name="como" value="ok">
	<input type="hidden" name="prv" value="gadget">

<div id="quanto">
__MSG_quero__ <input type="text" name="quanto" size="5" class="caixa" value="<?php echo $quanto ?>">
</div>
<div id="de">
__MSG_de__ __MSG_origem__<br>
	<select name="paisf" class="caixa" onChange="verifica()">
		<option value="XXX">__MSG_carrega__</option>
	</select>
</div>
<div id="para">
__MSG_para__ __MSG_destino__<br>
	<select name="paist" class="caixa" onChange="verifica()">
		<option value="XXX">__MSG_carrega__</option>
	</select>
</div>	
<div id="envia">
	<input type="submit" class="botao" name="enviar" onClick="return go()" value="__MSG_submit__">
</div>
<div id="">
<a href="http://www.financeone.com.br/?prv=gadget" target="_blank"">powered by FinanceOne.com.br</a>
</div>
        </form>
</div>
<script>
_IG_RegisterOnloadHandler(Load);_IG_SetTitle("__MSG_titulo__");
var combo = new Array();
<?
	$i = 0;
	while ($i <= 26) {
		print "combo[$i] = new Option(\"__MSG_p" . $i . "__\",\"__MSG_p" . $i . "v__\");\n";
		$i++;
	}
?>

var msg = null;
var prefs = new _IG_Prefs(__MODULE_ID__);

function verifica() {
	if( _gel("paist").selectedIndex == _gel("paist").length-1 || _gel("paisf").selectedIndex == _gel("paisf").length-1 )
	{
		document.location.href = 'http://www.financeone.com.br/conversores.php?prv=gadget'; 
	}
}

function go() {
	if( _gel("paist").selectedIndex == _gel("paisf").selectedIndex ) 
	{
		if(msg == null) {
			msg = new _IG_MiniMessage();
			msg.createDismissibleMessage("Selecione moedas diferentes!");
		}		
		return false;			
	} else {
		msg = null;
		prefs.set("qtd",_gel("quanto").value);
		prefs.set("deIndex",_gel("paisf").selectedIndex);
		prefs.set("paraIndex",_gel("paist").selectedIndex);
		return true;
	}
}

function preencher(campo) {
	for ( m = campo.options.length-1; m>0; m--) {
		campo.options[m] = null;
	}
	for (i=0;i<combo.length;i++) {
		campo.options[i]=new Option(combo[i].text,combo[i].value);
	}
	campo.options[0].selected=true;
}
function Load() {
	preencher(document.conversor.paisf);
	preencher(document.conversor.paist);
	_gel("paisf").selectedIndex = prefs.getString("deIndex");
	_gel("paist").selectedIndex = prefs.getString("paraIndex");
	_gel("quanto").value = prefs.getString("qtd");
}
</script>
]]>
</Content>
</Module>
