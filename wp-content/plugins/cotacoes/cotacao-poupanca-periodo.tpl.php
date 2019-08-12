<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
  $('#cotacoes-nav .desce').click(function() {
    var top
    //top = $(this).parent().parent().parent().parent().next('div').offset().top - $(this).offset().top;
    top = 52;
    $('body').animate({scrollTop : '+=' + top + 'px'}, 500);
  });
  $('#cotacoes-nav .sobe').click(function() {
    var top;
    //top = $(this).parent().parent().parent().parent().prev('div').offset().top - $(this).offset().top;
    top = 52;
    $('body').animate({scrollTop : '-=' + top + 'px'}, 500);
  });
});
</script>


<div class="coluna-box">
  <p class="subtitulo-box">Cotação por período:</p>
  <div id="box-cotacoes">
    <table class="nomargin">
      <tr>
        <th>Data</th>
        <th>Rendimento</th>
      </tr>
    </table>
    <div id="cotacao-container" class="nomargin">
      <div id="cotacao-lista" class="cotacao-poupanca">
        <table class="nomargin">
          {lista}
        </table>
      </div>
    </div>
  </div>
  <p id="cotacoes-nav">
    <img src="{src}/img/s_up.png" alt="Sobe" class="sobe link" /> 
    <img src="{src}/img/s_down.png" alt="Desce" class="desce link" />
  </p>
  <p class="nomargin"><a href="{action}">Voltar</a></p>
</div>