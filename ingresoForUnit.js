/**
 * Created by PC-ON on 5/6/2017.
 */



$(document).ready(function() {
    var producto;
    //alert("PASO");
    //comprobamos si se pulsa una tecla
    $('#suggestions').fadeOut(100);
    $('#suggestionscodigo').fadeOut(100);
    $('#suggestionscopia').fadeOut(100);
    $("#descripcion").keyup(function(e){

        //obtenemos el texto introducido en el campo de búsqueda
        producto = $("#descripcion").val();
        //hace la búsqueda
        //alert("PASO1");
        $.ajax({
            type: "POST",
            url: Routing.generate('_ajaxForUnit'),
            data: "producto="+producto,
            success: function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                if(data == ""){
                    $('#suggestions').fadeOut(500);
                }else{
                    $('#suggestions').fadeIn(500).html(data);
                    //Al hacer click en alguna de las sugerencias

                    $('.suggest-element').click(function(e){

                        //Obtenemos la id unica de la sugerencia pulsada
                        var id = $(this).attr('id');
                        //Editamos el valor del input con data de la sugerencia pulsada
                        $('#descripcion').val(id);

                        //Hacemos desaparecer el resto de sugerencias
                        $('#suggestions').fadeOut(100);

                        $('#descripcion').focus();

                        $("#descripcion").focusout(function(){
                            //alert("PASO2");
                            var str = $('#descripcion').val();
                            var res = str.split(" - ");
                            $('#descripcion').val(res[0]);
                            $('#codigo').val("");
                            $( "#form-registro" ).submit();

                        });
                    });
                }
            }
        });

    });

    $("#descripcioncopy").keyup(function(e){

        //obtenemos el texto introducido en el campo de búsqueda
        producto = $("#descripcioncopy").val();
        //hace la búsqueda
        //alert("PASO1");
        $.ajax({
            type: "POST",
            url: Routing.generate('_ajaxForUnit'),
            data: "producto="+producto,
            success: function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                if(data == ""){
                    $('#suggestionscopia').fadeOut(500);
                }else{
                    $('#suggestionscopia').fadeIn(500).html(data);
                    //Al hacer click en alguna de las sugerencias

                    $('.suggest-element').click(function(e){

                        //Obtenemos la id unica de la sugerencia pulsada
                        var id = $(this).attr('id');
                        //Editamos el valor del input con data de la sugerencia pulsada
                        $('#descripcioncopy').val(id);

                        //Hacemos desaparecer el resto de sugerencias
                        $('#suggestionscopia').fadeOut(100);

                        $('#versioncopy').focus();
                    });
                }
            }
        });
    });

    $("#versioncopy").keyup(function(e){

        //obtenemos el texto introducido en el campo de búsqueda
        var producto = $("#descripcioncopy").val();

        if(producto != "") {
            var version = $("#versioncopy").val();

            var str = producto;
            var res = str.split(" - ");

            //hace la búsqueda
            //alert("PASO1");
            $.ajax({
                type: "POST",
                url: Routing.generate('_ajaxForUnit'),
                data: "version=" + version + "&producto=" + res[1],
                success: function (data) {

                    //Escribimos las sugerencias que nos manda la consulta
                    if (data == "") {
                        $('#suggestionscopia').fadeOut(500);
                    } else {
                        $('#suggestionscopia').fadeIn(500).html(data);
                        //Al hacer click en alguna de las sugerencias

                        $('.suggest-element').click(function (e) {

                            //Obtenemos la id unica de la sugerencia pulsada
                            var id = $(this).attr('id');
                            //Editamos el valor del input con data de la sugerencia pulsada
                            $('#versioncopy').val(id);

                            //Hacemos desaparecer el resto de sugerencias
                            $('#suggestionscopia').fadeOut(100);

                            $('#versioncopy').focus();

                            $("#versioncopy").focusout(function () {
                                $('#copiafor').val(res[1] + "&&" + $('#versioncopy').val());
                            });
                        });
                    }
                }
            });
        }else{
            bootbox.alert("Debe seleccionar primero el producto a copiar");
            $('#descripcioncopy').focus();
        }
    });

    $("#codigo").keyup(function(e){

        //obtenemos el texto introducido en el campo de búsqueda
        prodCODIGO = $("#codigo").val();

        //hace la búsqueda
        //alert("PASO1");
        $.ajax({
            type: "POST",
            url: Routing.generate('_ajaxForUnit'),
            data: "prodCODIGO="+prodCODIGO,
            success: function(data) {

                //Escribimos las sugerencias que nos manda la consulta
                if(data == ""){
                    $('#suggestions').fadeOut(500);
                }else{
                    $('#suggestions').fadeIn(500).html(data);
                    //Al hacer click en alguna de las sugerencias

                    $('.suggest-element').click(function(e){

                        //Obtenemos la id unica de la sugerencia pulsada
                        var id = $(this).attr('id');
                        //Editamos el valor del input con data de la sugerencia pulsada
                        $('#codigo').val(id);

                        //Hacemos desaparecer el resto de sugerencias
                        $('#suggestions').fadeOut(100);

                        $('#codigo').focus();

                        $("#codigo").focusout(function(){
                            //alert("PASO2");
                            var str = $('#codigo').val();
                            var res = str.split(" - ");
                            $('#codigo').val(res[0]);
                            $('#descripcion').val("");
                            $( "form-registro" ).submit();
                        });
                    });
                }
            }
        });

    });
    asignaTds();
});

function change_desc(sel,tr){
    var prodId = $('#'+sel.name).children(":selected").attr("id");
    $.ajax({
        type: "POST",
        url: Routing.generate('_ajaxForUnit'),
        data: "proddescripselect=1&ing="+prodId,
        success: function(data) {
            if(data != ''){
                var porcion = data.split("&&");
                $("#productocod_"+tr).html(data);
                $("#lbUnd_"+tr).html(porcion[1]);
            }
        }
    });
    $('#ing_'+tr).val(prodId);
}

function change_cod(sel,tr){
    var prodId = $('#'+sel.name).children(":selected").attr("id");
    $.ajax({
        type: "POST",
        url: Routing.generate('_ajaxForUnit'),
        data: "prodcodselect=1&ing="+prodId,
        success: function(data) {
            if(data != ''){
                var porcion = data.split("&&");
                $("#productodesc_"+tr).html(porcion[0]);
                $("#lbUnd_"+tr).html(porcion[1]);
            }
        }
    });
    $('#ing_'+tr).val(prodId);
}

function eliminarReg(valor,tr){
    bootbox.confirm('Esta seguro de que desea eliminar este registro?', function(result) {
        if (result === true) {
            $.ajax({
                type: "POST",
                url: Routing.generate('_ajaxForUnit'),
                data: "eliminar="+valor,
                success: function(data) {
                    if(data != ''){
                        var porcion = data.split("&");
                        alerta('Eliminado con exito','info');
                        $("#tr_" +tr+" ").remove();
                        if(porcion[1] == 2){
                            $('#tamano').val("");
                            $('#undlote').val("");
                            $('#comentario').val("");
                            $('#lbversion').html("");
                        }
                        var rowCount = $('#t_ingredientes tr').length;
                        if(rowCount == 1){
                            addfieldForUnit("delete");
                        }else {
                            asignaTds();
                        }
                    }else{
                        alerta('No se puedo eliminar el registro, ya que contiene documentos asociados','warning');
                    }
                }
            });
        }
    });
}



function addfieldForUnit(valor){
    var rowCount = $('#t_ingredientes tr').length;
    $.ajax({
        type: "POST",
        url: Routing.generate('_ajaxForUnit'),
        data: "proddescrip=1&rowcount="+rowCount,
        success: function(data) {
            if(data != ''){
                var porcion = data.split("&&");
                $("#t_ingredientes tr:last").after('<tr id="tr_'+rowCount+'"><td>'+porcion[1]+'</td><td>'+porcion[0]+'</td><td><label for="prodUMEDIDA" id="lbUnd_'+rowCount+'" name="lbUnd_'+rowCount+'"></label></td><td><input class="form-control input-sm" type="number" id="cantidad_'+rowCount+'" required name="cantidad_'+rowCount+'"/></td><td><input type="hidden" id="ing_' + rowCount + '" name="ing_' + rowCount + '"/><a href="#" onclick="eliminarReg(tr_'+rowCount+','+rowCount+')" ><span class="glyphicon glyphicon-remove-sign" style="color:red"></span></a></td></tr>');
                setTimeout(3000);
                if(valor == "delete"){
                    $("#modificar").val('agregar');
                    $("#modificar").html('Agregar');
                    $("#modificar").attr('name', 'agregar');
                    $('#total_tds').val(1);
                }else {
                    asignaTds();
                }
            }
        }
    });
}

function addForUnitcopy(prodid){
    var rowCount = $('#t_ingredientes tbody > tr').length+1;
    var valor = prodid.value;
    var porcion = valor.split("&&");
    $.ajax({
        type: "POST",
        url: Routing.generate('_ajaxForUnit'),
        data: "copia=1&rowcount="+rowCount+"&prodid="+porcion[0]+"&versiones="+porcion[1],
        success: function(data) {
            if(data != ''){
                var porciones = data.split("&&");
                $("#t_ingredientes tr:last").after(porciones[0]);
                setTimeout(3000);
                $('#total_tds').val(porciones[1]);
            }
        }
    });
}


function asignaTds(){
    var rowCount = $('#t_ingredientes tbody > tr').length;
    $('#total_tds').val(rowCount);
}

