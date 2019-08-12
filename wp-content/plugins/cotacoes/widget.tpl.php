<script type="text/javascript" charset="utf-8">
jQuery(function(){
  jQuery('#cotacoes-abas > li > a:first').addClass('selected');
  jQuery('#cotacoes-list > div:not(:first)').hide();
  
  jQuery('#cotacoes-abas > li > a').click(function(){
    aba = jQuery(this).attr('id').replace('cot-aba-', '');
    
    jQuery('#cotacoes-abas > li > a.selected').removeClass('selected');
    jQuery(this).addClass('selected');
    
    jQuery('#cotacoes-list .cotacao').hide();
    jQuery('#cotacao-'+aba).show();
  });
});
</script>

<li class="widgetcontainer clearfix">
<div id="cotacoes-widget">
  <ul id="cotacoes-abas">
    <li><a href="javascript:void(0);" id="cot-aba-dolar">Dólar</a></li>
    <li><a href="javascript:void(0);" id="cot-aba-bolsa" style="display:none">Bolsa de valores</a></li>
    <li><a href="javascript:void(0);" id="cot-aba-poupanca">Poupança</a></li>
  </ul>
  <div id="cotacoes-list">
    <div id="cotacao-dolar" class="cotacao">
      <table>
        <tr>
          <td>&nbsp;</td>
          <th>Variação</th>
          <th>Compra</th>
          <th>Venda</th>
        </tr>
        {dolar}
      </table>
      <p class="data"><em>{data_dolar}</em></p>
    </div>
    <!--
    <div id="cotacao-bolsa" class="cotacao">
      <table>
        <tr>
          <th>&nbsp;</th>
          <th class="left">Bolsa</th>
          <th>Variação</th>
          <th>Pontos</th>
        </tr>
        {bolsa}
      </table>
      <p class="data"><em>às {data_bolsa}</em></p>
    </div>
  -->
    <div id="cotacao-poupanca" class="cotacao">
      <table>
        <tr>
          <th>Data</th>
          <th>Rendimento</th>
        </tr>
        {poupanca}
      </table>
    </div>
  </div>
</div>
</li>