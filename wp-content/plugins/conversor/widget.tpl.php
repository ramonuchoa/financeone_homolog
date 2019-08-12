<script type="text/javascript" charset="utf-8">
function md(el)
{
  var s = new String(el.value);
	s = s.replace(/[^0-9,.]/g,'');
	el.value = s;
}
function validaConversor()
{
  cf = document.getElementById('conv_from');
  ct = document.getElementById('conv_to');
  vl = document.getElementById('conv_valor');
  
  if (cf.value == ct.value) {
    alert('As moedas escolhidas devem ser diferentes');
    return false;
  }
  
  if (vl.value == '') {
    alert('Por favor, preencha o valor a ser convertido');
    vl.focus();
    return false;
  }
}

jQuery(function($){
  $('#conv_from').append('<option value="outras">Outras moedas</option>');
  $('#conv_to').append('<option value="outras">Outras moedas</option>');
  
  $('#conv_to, #conv_from').change(function(){
    if ($(this).val() == 'outras')
    {
      window.location = '/moedas/conversor-de-moedas/';
    }
  });
});
</script>
<div id="conversor-home-content">
  <form action="{action}" method="post" onsubmit="return validaConversor();">
  <p class="nomargin">
  <label>Da moeda:
  {origem}</label>
  <label>Para a moeda:
  {destino}</label>

  <span class="valor">
    <label for="" >Valor:</label><br/>
    <input name="valor" id="conv_valor" type="text" value="{valor}" onkeyup="return md(this)" />
    <input type="submit" id="f1_submit2" name="f1_submit2" class="form_botao"  value="Calcular" />
  </span>
  </p>
  </form>
</div>

<span class="clearfix"></span>
<div class="box_conversor_bottom">
<div id="textos_obs2">Patrocinado por</div>
<div id="box_itau"><a href="http://www.hi-midia.com" title="Hi-Midia" target="_blank"><img src="{template_url}/images/logo_hi_midia.png" width="55" height="16" alt="Hi-Midia" border="0" /></a></div>
</div>
<p id="link_gostou"><a href="{url}/gostou-coloque-no-seu-site">Gostou? Coloque ele no seu site</a></p>