<div class="content-coluna left noborder euro">
  <div class="coluna-box">
    <p class="subtitulo-box">Cotação por data:</p>
    <form action="{action}" method="post" accept-charset="utf-8">
      <input type="text" name="dia" maxlength="2" value="{dia}" /> - 
      <input type="text" name="mes" maxlength="2" value="{mes}" /> - 
      <input type="text" name="ano" maxlength="4" value="{ano}" /> 
      <input type="submit" value="Verificar cotação" class="submit right" />
      <input type="hidden" name="cotacao" value="data" />
    </form>
    <p class="help">Formato: dia-mês-ano</p>
  </div>
</div>

<div class="content-coluna right euro">
  <div class="coluna-box">
    <p class="subtitulo-box">Cotação por data:</p>
    <div id="box-cotacoes">
      <table>
        <tr>
          <th>Data</th>
          <th>Compra</th>
          <th>Venda</th>
        </tr>
        <tr>
          <td>{data}</td>
          <td>R$ {cota-compra}</td>
          <td>R$ {cota-venda}</td>
        </tr>
      </table>
    </div>
    <p><a href="{action}">Voltar</a></p>
  </div>
</div>