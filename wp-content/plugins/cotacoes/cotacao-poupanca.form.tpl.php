    <div class="row">
      <div class="col-md-6">
        <form class="FrmHistorico" action="{action}" method="post" accept-charset="utf-8">
          <div class="form-group">
            <label>Cotação por data:</label>
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="dia" maxlength="2" placeholder="" />
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="mes" maxlength="2" placeholder="" />  
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="ano" maxlength="4" placeholder="" />  
          </div>
          <div class="col-xs-2">
            <button type="submit" class="btn btn-default submit right">Verificar cotação</button>
            <input type="hidden" name="cotacao" value="data" />
          </div>
        </form>
      </div>
      <div class="col-md-6">
        <form class="FrmHistorico" action="{action}" method="post" accept-charset="utf-8">
          <div class="form-group">
            <label>Cotação por período:</label>
          </div>
          <div class="col-xs-1">
            <label>De:</label>
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="dia_de" maxlength="2" placeholder="" />
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="mes_de" maxlength="2" placeholder="" />
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="ano_de" maxlength="4" placeholder="" />
          </div><br><br><br>
          <div class="col-xs-1">
            <label>Até:</label>
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="dia_ate" maxlength="2" placeholder="" />
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="mes_ate" maxlength="2" placeholder="" />
          </div>
          <div class="col-xs-2">
            <input class="form-control" type="text" name="ano_ate" maxlength="4" placeholder="" />
          </div><br><br><br>
          <div class="col-xs-2">
            <input type="hidden" name="cotacao" value="periodo" />
            <button type="submit" class="btn btn-default submit periodo">Verificar cotação</button>
          </div>
        </form>
      </div>
    </div>
