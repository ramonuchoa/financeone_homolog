<div class="coluna-box">
    <p class="subtitulo-box">Calcula os valores de salário Líquido.</p>
    <form action="" method="post">
        <input type="hidden" name="acao" value="calcSalarioLiquido">
        <div class="form-group">
            <label for="input_salario">Salário Base</label>
            <input type="text" class="form-control" id="input_salario" name="salario" placeholder="Salário Base" required="required" maxlength="12">
        </div>
        <div class="form-group">
            <label for="input_dependentes">Número de Dependentes</label>
            <input type="number" class="form-control" id="input_dependentes" name="dependentes" placeholder="Número de dependentes" value="0" maxlength="2">
        </div>
        <div class="form-group">
            <label for="input_descontos">Outros descontos</label>
            <input type="text" class="form-control" id="input_descontos" name="descontos" placeholder="Outros descontos. Ex: Vale transporte, saúde, etc" min="0" maxlength="2">
        </div>
        <button type="submit" class="btn btn-default" name="bt_submit">Calcular</button>
    </form>
</div>
<script src="{src}/js/autoNumeric.min.js"></script>

<script>
    jQuery(document).ready(function(){
        const autoNumericOptionsEuro = {
            digitGroupSeparator        : '.',
            decimalCharacter           : ',',
            decimalCharacterAlternative: '.',
            currencySymbol             : 'R$',
            currencySymbolPlacement    : 'p',
        };
        // Initialization
        jQuery('#input_salario, #input_descontos').autoNumeric('init', autoNumericOptionsEuro);
    });
</script>