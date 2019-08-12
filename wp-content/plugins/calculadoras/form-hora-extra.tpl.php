<div class="coluna-box">
    <p class="subtitulo-box">Calcula o valor da hora extra.</p>
    <form action="" method="post">
        <input type="hidden" name="acao" value="calcHoraExtra">
        <div class="form-group">
            <label for="input_salario">Salário Base</label>
            <input type="text" class="form-control" id="input_salario" name="salario" placeholder="Salário Base" required="required" maxlength="12">
        </div>
        <div class="form-group">
            <label for="input_jornada_total">Jornada mensal (horas)</label>
            <input type="number" class="form-control" id="input_jornada_total" name="jornada_mensal" placeholder="Total mensal jornada em horas" value="0" maxlength="2">
        </div>
        <div class="form-group">
            <label for="input_qtd_extra_normal">Quantidade de horas extras normais</label>
            <input type="text" class="form-control" id="input_qtd_extra_normal" name="qtd_extra_normal" placeholder="Horas durante a semana ou sábado" min="0" maxlength="2">
        </div>
        <div class="form-group">
            <label for="input_qtd_extra_100">Quantidade de horas extras 100%</label>
            <input type="text" class="form-control" id="input_qtd_extra_100" name="qtd_extra_100" placeholder="Horas de domingo ou feriados" min="0" maxlength="2">
        </div>
        <button type="submit" class="btn btn-default" name="bt_submit">Calcular</button>
    </form>

    <div class="calc-explain">
        <h4>Como usar a calculadora</h4>
        <ul class="list-no-style">
            <li>
                1) Salário base: preencha com o valor do salário base.
            </li>
            <li>
                2) Jornada mensal: multiplique o número de horas semanais normalmente trabalhadas
                por 5 (número máximo de semanas que um mês pode ter). Usualmente, o trabalhador
                cumpre 44 horas semanais.<br><br>

                <p class="text-center">44 horas x 5 semanas = 220 horas mensais.</p>
                <div class="small">
                    <table class="text-center">
                        <thead>
                            <tr>
                                <th>Horas Semanais</th>
                                <th>Horas Mensais</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>44</td>
                                <td>220</td>
                            </tr>
                            <tr>
                                <td>40</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>36</td>
                                <td>180</td>
                            </tr>
                            <tr>
                                <td>30</td>
                                <td>150</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>
            <li>
                3) Quantidade de horas extras normais: preencha com o número de horas extras trabalhadas de segunda a sábado.
            </li>
            <li>
                4) Quantidade de horas extras 100%: preencha com o número de horas extras
                trabalhadas nos domingos e feriados.<br><br>

                Algumas categorias profissionais possuem percentuais diferenciados. Na dúvida,
                consulte convenções ou acordos coletivos.<br><br>

                <strong>Como calcular a hora extra</strong><br><br>
                Para calcular o valor da hora extra, divida o valor do salário bruto pelo número de horas trabalhadas
                no mês. Exemplo:<br><br>

                <strong>R$ 1.000,00/220 horas = R$ 4,54 (valor da hora)</strong><br><br>

                Multiplique o valor da hora pelo percentual adicional:<br><br>
                R$ 4,54 X 50% = R$ 2,27<br><br>
                Some o valor da hora de trabalho ao valor adicional:<br><br>
                R$ 4,54 + R$ 2,27= R$ 6,81<br><br>
                <strong>O valor da hora extra será R$ 6,81.</strong><br><br>

                No caso do percentual adicional de 100%, o cálculo será:<br>
                R$ 4,54 x 100% = R$ 4,54<br><br>
                Some o valor da hora de trabalho ao valor adicional:<br>
                R$ 4,54 + R$ 4,54 = R$ 9,10<br><br>
                <strong>O valor da hora extra será R$ 9,08.</strong><br>
            </li>
        </ul>
    </div>

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
        jQuery('#input_salario').autoNumeric('init', autoNumericOptionsEuro);
    });
</script>

<style type="text/css">
    .calc-explain {
        font-size: 120%;
        margin-top: 40px;
    }

    .list-no-style {
        text-decoration: none;
        list-style: none;
        margin: 0;
        padding: 0 20px;
    }
        .list-no-style li {
            margin: 20px 0;
        }

    div.small table {
        margin: 0 auto;
        width: 250px;
        font-size: 120%;
        margin-bottom: 20px;
    }

    div.small table thead {
        background-color: #CACACA;
    }

    div.small table th,
    div.small table td {
        text-align: center;
        border: solid 1px #666;
    }
</style>
