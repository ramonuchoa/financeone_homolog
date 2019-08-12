<div class="coluna-box">
    <p class="subtitulo-box">Rescisão pelo empregado</p>
    <h3>Salários</h3>
    <p>
        Período trabalhado: {dtAdmissao} até {dtDemissao}<br /><br />
        Saldo de salário ({diasTrabalhados}/30): {saldoDeSalario} <br />
        INSS: {inss} <br />
        IRPF: {ir} <br />
        Total de descontos sobre salário: {totalDescontosSalario} <br />
        <strong>Total a receber de salário: {totalSalario}</strong> <br />
    </p>
    <p>

    </p>
    <p>
        {linhaDecimo}
        INSS 13º: {inssDecimo} <br />
        IRPF 13º: {irDecimo} <br />
        Total de descontos sobre 13º: {totalDescontosDecimo}<br />
        {linhaDecimoTotal}
    </p>
    <p>
        {ferias}
    </P>
    <p>
        Indenização por quebra de contrato: {valorPrazoRemanescente} <br />
        Total de outros descontos: {valorPrazoRemanescente} <br />
    </p>
    <p>
        {linhaVencimentos}
        {linhaDescontos}
        <strong>Total Líquido: {totalLiquido}</strong><br /><br />
        {warning}
    </P>
    <p><a onClick="history.go(-1);return true;" style="cursor:pointer">Voltar</a></p>
</div>
