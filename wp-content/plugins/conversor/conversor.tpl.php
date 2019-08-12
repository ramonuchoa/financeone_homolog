<div class='converterResponse'>
	<p class='fromCurrency'>
		<b>Origem:</b> {pais_from} ({nome_from})
		<br>
		<span>{code_from} {valor}</span>
	</p>
	<button type="submit" name="button" id="inverter" form='form1'><i class="fas fa-exchange-alt"></i></button>
	<p class='toCurrency'>
		<b>Destino:</b> {pais_to} ({nome_to})
		<br>
		<span>{code_to} {resultado}</span>
	</p>

	<p class='exchangeDate'>* baseado no fechamento em {data}</p>
</div>

<form id="form1" name="form1" method="post" action="{action}">
	<input type="hidden" name="conv_from" value="{pais_to}" id="conv_from"/>
	<input type="hidden" name="conv_to" value="{pais_from}" id="conv_to"/>
	<input type="hidden" name="valor" value="{valor}" id="valor"/>
</form>
<a href="{action}" class='goBack'>Voltar</a>
