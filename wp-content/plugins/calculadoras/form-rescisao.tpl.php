<link href="{src}/css/pikaday.css" media="all" rel="stylesheet" />
<div class="coluna-box">
    <p class="subtitulo-box">Calcula os valores da rescisão do contrato de trabalho de um empregado.</p>
    <form action="" method="post">
        <div class="form-group">
            <label>
                Motivo da rescisão
            </label>
            <select class="form-control" name="motivo">
                <option value="1">Pedido de demissão</option>
                <option value="2">Dispensa sem justa causa</option> 
                <option value="3">Dispensa com justa causa</option> 
                 <option value="4">Término de contrato de experiência</option>
            </select>
        </div>
        {form}
        <label>
            O aviso prévio foi:
        </label>
        <div class="radio">
            <label>
                <input type="radio" name="aviso" id="radio_avisoPrevio" value="trabalhado" checked>
                Trabalhado
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label>
                <input type="radio" name="aviso" id="radio_avisoPrevio" value="indenizado">
                Indenizado
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="ferias_ven" value="true"> Possui férias vencidas?
            </label>
        </div>
        <button type="submit" class="btn btn-default" name="bt_submit">Calcular</button>
    </form>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
<script src="{src}/js/pikaday.js"></script>
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
        jQuery('#input_salario').autoNumeric('init', autoNumericOptionsEuro);

        var input = 'input_dt_inicio';
        var options = {
            field: document.getElementById(input),
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
            //maxDate: new Date()
        };
        var picker = new Pikaday(options);
        options.field = document.getElementById('input_dt_fim');
        var picker2 = new Pikaday(options);
    });
    
</script>
