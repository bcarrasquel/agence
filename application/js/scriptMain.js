scriptMain = {
    campoSelect: ".listUsuario",
    init: function () {
        scriptMain.generateSelect()
        scriptMain.generateDatepicker();
        scriptMain.relatorioFunc();
        scriptMain.pizzaFunc();
        scriptMain.grafoFunc();
    },

    generateSelect: function(){
        $(scriptMain.campoSelect).select2({
            placeholder: 'Seleccionar usuarios',
             width: '82%'
        });
    },

    generateDatepicker: function(){
       $("#endDate").datepicker({
            format:'mm-yyyy',
            viewMode: "months",
            minViewMode: "months",
            startDate: '2007/03/03',
            startDate: new Date(2003, 00, 01),
            endDate: new Date(2007, 11, 31),
            autoclose: true
        })


       $("#iniDate").datepicker({
            format:'mm-yyyy',
            viewMode: "months",
            minViewMode: "months",
            startDate: new Date(2003, 00, 01),
            endDate: new Date(2007, 11, 31),
            autoclose: true
        }).on("changeDate", function(e) {
            value = $("#iniDate").datepicker("getDate");
            $("#endDate").datepicker("setStartDate", value)
            $("#endDate").prop('disabled', false);

        });
        $('#iniDate').datepicker('setDate', new Date(2007, 00, 01));
        $('#endDate').datepicker('setDate', new Date(2007, 11, 31));
        $("#endDate").prop('disabled', true);
    },

    relatorioFunc: function(){
        $("#relatorio").click(function(event) {
            valores = scriptMain.getData()
            if (valores.estatus == 200){
                $.ajax({
                    url: baseUrl + 'index.php/ejercicio/resultRelatorio',
                    type: 'post',
                    async: false,
                    data: {valores: valores},
                    dataType: 'json',
                    success: function (data) {
                        if(data.estatus == 200){
                            $('#resultadoConsulta').html(data.html)
                        }else{
                            scriptMain.sweetalert('No se encontraron coincidencias', 'Intentelo con otra busqueda', 'error')
                            $('#resultadoConsulta').html('')
                        }
                    }
                });
            }
        });
    },

    getData: function(){
        nombres = $(scriptMain.campoSelect).val();
        fechaInicio = $('#iniDate').val()
        fechaFin = $('#endDate').val()
        if (nombres == null || fechaInicio == '' || fechaFin == '') {
            scriptMain.sweetalert('Error!', 'Todos los campos son obligatorios', 'error')
            return {estatus: 500}
        } else if (fechaInicio > fechaFin){
            scriptMain.sweetalert('Error!', 'La fecha de inicio es superior a la fecha final', 'error')
            return {estatus: 500}
        }
        return {nombres: nombres, fechaInicio: fechaInicio, fechaFin: fechaFin, estatus: 200}
    },

    pizzaFunc: function(){
        $("#pizza").click(function(event) {
            valores = scriptMain.getData()
            if (valores.estatus == 200){
                $.ajax({
                    url: baseUrl + 'index.php/ejercicio/resultPizza',
                    type: 'post',
                    async: false,
                    data: {valores: valores},
                    dataType: 'json',
                    success: function (data) {
                        if (data.estatus == 200) {
                            $('#resultadoConsulta').html(data.html)
                            scriptMain.generatePizza(data.data);
                        }else{
                            scriptMain.sweetalert('No se encontraron coincidencias', 'Intentelo con otra busqueda', 'error')
                            $("canvas").hide()
                        }
                    }
                });
            }
        });
    },

    getRandomColor: function() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    },

    generatePizza: function(data){
        label = Array();
        datas = Array();
        color = Array();

        Object.keys(data).forEach(function (key) {
            label.push(key);
            datas.push(data[key].toFixed(2))
            color.push(scriptMain.getRandomColor())
        });
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: label,
                datasets: [{
                    data: datas,
                    backgroundColor: color,
                    borderWidth: 1
                }]
            }

        });
    },

    grafoFunc: function(){
        $("#grafico").click(function(event) {
            valores = scriptMain.getData()
            if (valores.estatus == 200){
                $.ajax({
                    url: baseUrl + 'index.php/ejercicio/resultGrafico',
                    type: 'post',
                    async: false,
                    data: {valores: valores},
                    dataType: 'json',
                    success: function (data) {
                        if (data.estatus == 200) {
                            $('#resultadoConsulta').html(data.html)
                            scriptMain.generateGrafica(data);
                        }else{
                            scriptMain.sweetalert('No se encontraron coincidencias', 'Intentelo con otra busqueda', 'error')
                            $("canvas").hide()
                        }
                    }
                });
            }
        });
    },

    generateGrafica: function(data){
        label = Array();
        datas = Array();
        color = Array();
        promedio = Array();
        Object.keys(data.data).forEach(function (key) {
            label.push(key);
            datas.push(data.data[key].toFixed(2));
            color.push(scriptMain.getRandomColor());
            promedio.push(data.promedio);
        });
        var ctx = document.getElementById("myChart");
        var mixedChart = new Chart(ctx, {
            type: 'bar',
            data: {
            datasets: [{
                  label: 'Custo Fixo Médio',
                  data: promedio,
                  borderColor:  '#00ffff',
                  type: 'line',
                  fill: false
                },{
                  label: 'Consultor',
                  data: datas,
                  backgroundColor: color,
                }],
            labels: label
            },
        });
        $("canvas").show()
    },

    sweetalert: function(title, message, type){
        swal(title, message, type)
    }

}