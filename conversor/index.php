<?php
require dirname(__FILE__).'/mysql.php';

$key = isset($_GET['key']) ? $_GET['key'] : null;
if (!conv_key_exists($key)) {
  die('Chave de uso inválida.');
}

$template_url = 'http://www.financeone.com.br/wp-content/themes/arras-theme';
$action       = '../moedas/conversor-de-moedas';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt_BR" lang="pt_BR">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Conversor de Moedas - FinanceOne</title>
	
	<link rel="stylesheet" href="conversor.css" type="text/css" media="screen, projector" />
	
	<script type="text/javascript" charset="utf-8">
    function mascara(el)
    {
      var s = new String(el.value);
    	s = s.replace(/[^0-9,.]/g,'');
    	el.value = s;
    }
	</script>
</head>
<body>
<div id="conversor">
  <h1>Conversor de Moedas</h1>
  <div id="form-conversor">
    <form action="<?php echo $action ?>" method="post" target="_blank">
    <p>
      <label for="conv_from">Da moeda:</label>
      <select id="conv_from" name="conv_from"><option selected="selected" value="BRASIL">Brasil, Real (BRL)</option><option value="ESTADOS UNIDOS">Estados Unidos, Dolar (USD)</option><option value="EURO">Euro, Euro (EUR)</option><option value="ARGENTINA">Argentina, Peso (ARS)</option><option value="PORTUGAL">Portugal, Euro (EUR)</option><option value="JAPAO">Japao, Iene (JPY)</option><option value="REINO UNIDO">Reino Unido, Libra (GBP)</option><option value="CHILE">Chile, Peso (CLP)</option><option value="ALEMANHA">Alemanha, Euro (EUR)</option><option value="CANADA">Canada, Dolar (CAD)</option><option value="AUSTRALIA">Australia, Dolar (AUD)</option><option value="MEXICO">Mexico, Peso (MXN)</option><option value="SUICA">Suica, Franco (CHF)</option><option value="URUGUAI">Uruguai, Peso (UYU)</option><option value="PARAGUAI">Paraguai, Guarani (PYG)</option><option value="COMUNIDADE EUROPEIA">Comunidade Europeia, Euro (EUR)</option><option value="AFRICA DO SUL">Africa Do Sul, Rand (ZAR)</option><option value="BOLIVIA">Bolivia, Boliviano (BOB)</option><option value="VENEZUELA">Venezuela, Bolivar (VEB)</option><option value="PERU">Peru, Novo Sol (PEN)</option><option value="ITALIA">Italia, Euro (EUR)</option><option value="FRANCA">Franca, Euro (EUR)</option><option value="CHINA">China, Iuan Renmimbi (CNY)</option><option value="COLOMBIA">Colombia, Peso (COP)</option><option value="NOVA ZELANDIA">Nova Zelandia, Dolar (NZD)</option></select>
    </p>
    <p>
      <label for="conv_to">Para a moeda:</label>
      <select id="conv_to" name="conv_to"><option value="BRASIL">Brasil, Real (BRL)</option><option selected="selected" value="ESTADOS UNIDOS">Estados Unidos, Dolar (USD)</option><option value="EURO">Euro, Euro (EUR)</option><option value="ARGENTINA">Argentina, Peso (ARS)</option><option value="PORTUGAL">Portugal, Euro (EUR)</option><option value="JAPAO">Japao, Iene (JPY)</option><option value="REINO UNIDO">Reino Unido, Libra (GBP)</option><option value="CHILE">Chile, Peso (CLP)</option><option value="ALEMANHA">Alemanha, Euro (EUR)</option><option value="CANADA">Canada, Dolar (CAD)</option><option value="AUSTRALIA">Australia, Dolar (AUD)</option><option value="MEXICO">Mexico, Peso (MXN)</option><option value="SUICA">Suica, Franco (CHF)</option><option value="URUGUAI">Uruguai, Peso (UYU)</option><option value="PARAGUAI">Paraguai, Guarani (PYG)</option><option value="COMUNIDADE EUROPEIA">Comunidade Europeia, Euro (EUR)</option><option value="AFRICA DO SUL">Africa Do Sul, Rand (ZAR)</option><option value="BOLIVIA">Bolivia, Boliviano (BOB)</option><option value="VENEZUELA">Venezuela, Bolivar (VEB)</option><option value="PERU">Peru, Novo Sol (PEN)</option><option value="ITALIA">Italia, Euro (EUR)</option><option value="FRANCA">Franca, Euro (EUR)</option><option value="CHINA">China, Iuan Renmimbi (CNY)</option><option value="COLOMBIA">Colombia, Peso (COP)</option><option value="NOVA ZELANDIA">Nova Zelandia, Dolar (NZD)</option></select>
    </p>
    <p>
      <label for="valor">Valor:</label>
      <input name="valor" id="valor" type="text" value="" onkeyup="return mascara(this)" maxlength="15" />
      <input type="submit" class="input_submit" value="Calcular" />
    </p>
    </form>
  </div>
</div>

<div id="patrocinio"><p>Patrocinado por <a href="http://www.hi-midia.com" title="Hi-Midia" target="_blank"><img src="<?php echo $template_url ?>/images/logo_hi_midia.png" width="55" height="16" alt="Hi-Midia" border="0" /></a></p></div>
</body>
</html>