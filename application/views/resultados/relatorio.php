<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
        <div class="table-responsive">
        <?php foreach ($result as $llave => $valor) {
        $receta = 0;
        $costoFixo = 0;
        $comissao = 0;
        $lucro = 0;
        ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <th colspan="5" class="danger">
                        <?php echo $llave?>
                    </th>
                </thead>
                <tbody>
                    <tr class="info">
                        <td>Período</td>
                        <td>Receita líquida</td>
                        <td>Custo Fixo</td>
                        <td>Comissão</td>
                        <td>Lucro</td>
                    </tr>
                    <?php foreach ($valor as $key => $value) { ?>

                        <tr>
                            <td> <?php echo $value["mes"] ?> </td>
                            <td> <?php echo number_format($value["receita"], 2, ',', '.') ?> </td>
                            <td> <?php echo number_format($salario[$llave], 2, ',', '.') ?> </td>
                            <td> <?php echo number_format($value["comissao"], 2, ',', '.') ?> </td>
                            <td> <?php echo number_format($value["receita"] - ($salario[$llave] + $value["comissao"]), 2, ',', '.') ?> </td>
                        </tr>
                    <?php
                        $receta += $value["receita"];
                        $costoFixo += $salario[$llave];
                        $comissao += $value["comissao"];
                        $lucro += $value["receita"] - ($salario[$llave] + $value["comissao"]);
                    } ?>
                </tbody>
                <thead>
                    <tr class="success">
                        <td><b>Saldo</b></td>
                        <td><b><?php echo number_format($receta, 2, ',', '.')  ?></b></td>
                        <td><b><?php echo number_format($costoFixo, 2, ',', '.')  ?></b></td>
                        <td><b><?php echo number_format($comissao, 2, ',', '.')  ?></b></td>
                        <td><b><?php echo number_format($lucro, 2, ',', '.')  ?></b></td>
                    </tr>
                </thead>
            </table>
        <?php } ?>
        </div>
    </div>
</div>