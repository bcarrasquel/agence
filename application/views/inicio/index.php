<body>
    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
            <img src="<?php echo base_url('application/image/agence.png') ?>" alt="">
            <div class="thumbnail">
                <form action="index_submit" method="get" accept-charset="utf-8">
                    <div class="row">
                        <div class="col-md-7"><b>Seleccione Período:</b></div>
                        <div class="col-md-5"><b>Seleccione Consultor (es):</b></div>
                    </div>
                    <div class="row"><div class="col-md-12"><hr> </div></div>
                    <div class='row'>
                        <div class="col-md-1"> Desde: </div>
                        <div class="col-md-2"> <input class="form-control text-center" id="iniDate" class="fechas"> </div>
                        <div class="col-md-1"> Hasta: </div>
                        <div class="col-md-2"> <input class="form-control text-center" id="endDate" class="fechas"> </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <select style="height: 30px" class="form-control listUsuario" name="nombres[]" multiple="multiple" id="consultUsua">
                                <?php foreach ($result as $key => $value) {
                                    echo '<option value="'. $value["co_usuario"]  .'">'. $value["no_usuario"]  .'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="row"><div class="col-md-12"><hr></div></div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" id="relatorio" class="btn btn-success">Relatório</button>
                            <button type="button" id="grafico" class="btn btn-primary">Gráfico</button>
                            <button type="button" id="pizza" class="btn btn-warning">Pizza</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="resultadoConsulta">
    </div>
</body>
<script> $(function(){ scriptMain.init() }); </script>
