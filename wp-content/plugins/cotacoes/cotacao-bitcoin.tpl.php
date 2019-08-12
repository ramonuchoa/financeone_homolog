<!--[if IE]><script language="javascript" type="text/javascript" src="{src}/js/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="{src}/js/jquery.flot.min.js"></script>

<script id="source" language="javascript" type="text/javascript">
  jQuery(document).ready(function($) {
     $('input[name="dia"], input[name="mes"], input[name="ano"], input[name="dia_de"], input[name="mes_de"], input[name="ano_de"], input[name="dia_ate"], input[name="mes_ate"], input[name="ano_ate"]').keyup(function(){
      var s = new String($(this).val());
    	s = s.replace(/\D/g,'');
    	$(this).val(s);
    });
  });
</script>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <h2>Última cotação</h2>
    <table style="font-size: 14px;">
      <tr>
        <th>Exchange</th>
        <th>Cotação</th>
        <th>USD Comercial</th>
        <th>USD Turismo</th>
      </tr>
      {ultima_cotacao}
    </table>
    <p style="font-size: 14px;" class="data"><em>{data_cotacao}</em></p>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <h2>Gráfico do Bitcoin</h2>
  </div>
</div>

<div id="Graficos" style="overflow: hidden;">
  <div class="row">
    <div class="col-md-12 tradingview-chart">
      <!-- TradingView Widget BEGIN -->
      <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
      <script type="text/javascript">
      new TradingView.widget({
        "autosize": true,
        "symbol": "FOXBIT:BTCBRL",
        "interval": "D",
        "timezone": "Etc/UTC",
        "theme": "Light",
        "style": "1",
        "locale": "br",
        "toolbar_bg": "rgba(16, 40, 74, 1)",
        "enable_publishing": false,
        "allow_symbol_change": true,
        "hideideas": true
      });
      </script>
      <!-- TradingView Widget END -->
    </div>
  </div>
</div>
