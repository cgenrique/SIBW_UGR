$(obtenerCientificosRegistrados());

function obtenerCientificosRegistrados(cientifico)
{
    $.ajax({
        url : 'buscarCientifico.php',
        type : 'POST',
        dataType : 'html',
        data : { cientifico: cientifico },
        })

    .done(function(resultadoBusqueda){
        $("#tablaRes").html(resultadoBusqueda);
    })
}

$(document).on('keyup', '#busqueda', function()
{
    var valorBusqueda=$(this).val();
    if (valorBusqueda!="")
    {
        obtenerCientificosRegistrados(valorBusqueda);
    }
    else
        {
            obtenerCientificosRegistrados();
        }
});
