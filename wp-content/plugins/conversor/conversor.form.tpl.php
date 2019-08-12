<form class='converterForm' enctype="multipart/form-data" action="{action}" id="form2" name="form2" method="post">
	<div class='converterFormLine'>
		<label>Valor:</label>
		<input name="valor" value="{valor}" type="text" class="form_valor_box" id="conv_valor" onkeyup="return md(this)" />
	</div>

	<div class='converterFormLine'>
		<label>Origem:</label>
		{origem}
	</div>

	<div class='converterFormLine'>
		<label>Destino:</label>
		{destino}
	</div>

	<button name="button" type="submit" id="button">Converter</button>
</form>
