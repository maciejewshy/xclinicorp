
$(document).on('submit', '#addcanal', function (e) {
    e.preventDefault();
    var url = $('#addcanal').attr('action');
    var serialized = $('#addcanal').serialize();
    $.ajax({
        type: "post",
        url: url,
        data: serialized,
        dataType: "json",
        success: function (data) {
            $('#canais').html(data.tr);
        }
    });
})

function keydown(evt) {
    var bool = true;
    if (evt.keyCode == 13) {
        bool = false;
    }
    return bool;
}
$(document).on('submit', '#trocarsenha', function (e) {

  e.preventDefault();  
  var url = $(this).attr('action');
  var serialized = $('#trocarsenha').serialize();

    $.ajax({
        type: "post",
        url: url,
        data: serialized,
        dataType: "json",
        success: function (data) {
           if(data){
            alert('Senha alterada com sucesso!');
           }
           else{
            alert('Erro ao alterar a senha!');
           }
        }
    });  

});


$(document).on('click', '.status', function (e) {
    e.preventDefault();
    const id = [];
    var url = $(this).attr('href');
    $.ajax({
        type: "get",
        url: url,
        data: id,
        dataType: "json",
        success: function (data) {
            $('#tbody').html(data.tr);
        }
    });
})

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

