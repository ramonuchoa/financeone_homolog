<div class="content-coluna left noborder euro periodo">
	<div class="coluna-box">
		<p class="subtitulo-box">Cotação por data:</p>

		<form action="{action}" method="post" accept-charset="utf-8">
			<input type="text" name="dia" maxlength="2" /> - 
			<input type="text" name="mes" maxlength="2" /> - 
			<input type="text" name="ano" maxlength="4" /> 
			<input type="submit" value="Verificar cotação" class="submit right" />
			<input type="hidden" name="cotacao" value="data" />
		</form>

		<p class="help">Formato: dia-mês-ano</p>
	</div>

	<div class="coluna-box">
		<p class="subtitulo-box">Cotação por período:</p>

		<form action="{action}" method="post" accept-charset="utf-8">
			<label for="dia_de">A partir de:</label>

			<input type="text" name="dia_de" maxlength="2" value="{dia_de}" /> - 
			<input type="text" name="mes_de" maxlength="2" value="{mes_de}" /> - 
			<input type="text" name="ano_de" maxlength="4" value="{ano_de}" />

			<br/>

			<label for="dia_ate">Até: </label>

			<input type="text" name="dia_ate" maxlength="2" value="{dia_ate}" /> - 
			<input type="text" name="mes_ate" maxlength="2" value="{mes_ate}" /> - 
			<input type="text" name="ano_ate" maxlength="4" value="{ano_ate}" />

			<p class="help periodo">Formato: dia-mês-ano</p>

			<input type="hidden" name="cotacao" value="periodo" />
			<input type="submit" value="Verificar cotação" class="submit periodo" />
		</form>
	</div>
</div>

<div class="content-coluna right euro double">
	<div class="coluna-box">
		<p class="subtitulo-box">Cotação por período:</p>

		<div id="box-cotacoes">
			<table class="nomargin">
				<tr>
					<th>Data</th>
					<th>Compra</th>
					<th>Venda</th>
				</tr>
				{lista}
			</table>
		</div>
	</div>

	<p class="nomargin"><a href="{action}">Voltar</a></p>
</div>
