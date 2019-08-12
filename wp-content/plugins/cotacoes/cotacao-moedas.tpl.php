<!--[if IE]><script language="javascript" type="text/javascript" src="{src}/js/excanvas.min.js"></script><![endif]-->

<link href="{src}/css/pikaday.css" media="all" rel="stylesheet" />


<div class="row">
  <div class="col-md-12">
    <form class="FrmHistorico" action="{action}" method="post" accept-charset="utf-8">
      <div class="form-group">
        <label>Cotação por data:</label>
      </div>
      <div class="col-xs-6">
          <div class="row">
            <input id="date" name="date" class="form-control" type="text" placeholder="dd/mm/aaaa" value="{data}"> 
          </div>
      </div>
      <div class="col-xs-2">
        <button type="submit" class="btn btn-default submit right">Verificar cotação</button>
        <input type="hidden" name="cotacao" value="data" />
      </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <p style="font-size: 14px;">Cotações em {data}:</p>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <table style="font-size: 14px;">
      <tr>
        <th>País</th>
        <th>Moeda</th>
        <th>Compra</th>
        <th>Venda</th>
      </tr>
      {lista}
    </table>
  </div>
</div>

<p style="font-size: 14px;">As cotações acima são baseadas no valor de venda do Banco Central Brasileiro. Os valores devem ser utilizados SOMENTE para uma "BASE" . Não devem ser utilizados para venda de produtos, câmbio e afins.</p>

<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
<script src="{src}/js/pikaday.js"></script>
<script>
    $(document).ready(function(){
        var picker = new Pikaday({
            field: document.getElementById('date'),
            format: 'DD/MM/YYYY',
            onSelect: function() {
                console.log(this.getMoment().format('DD MMM YYYY'));
            },
            i18n: {
                previousMonth : 'Mês anterior',
                nextMonth     : 'Próximo mês',
                months        : ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                weekdays      : ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
                weekdaysShort : ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb']
            },
            maxDate: new Date()
        });
    });
    
</script>
