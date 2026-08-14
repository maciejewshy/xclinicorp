$(document).ready(function (e){
  $('.dataTable').DataTable( {
    'order':['0', 'desc']
  });
  $('#dataTable').DataTable();
  $('#dtCampanha').DataTable({
    'order':['0', 'asc']
  });
  $('.dtSetor').DataTable()
});

$(document).on('submit', '#addcanal', function (e) {
    alert();
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

$(document).on('submit', '#addmensagem', function (e) {
    e.preventDefault();
    var url = $('#addmensagem').attr('action');
    var serialized = $('#addmensagem').serialize();
    $.ajax({
        type: "post",
        url: url,
        data: serialized,
        dataType: "json",
        success: function (data) {
            $('#tbody').html(data.tr);
        }
    });
})

$(document).on('click', '.setor-checked', function (e) {

    var name = $(this).attr('id');
    var val = $(this).is(":checked");
    // alert($(this).is(":checked"));
    // alert(ck);
    // alert(name);
    if (val == true) {
        data = { 'id': name, 'status': 1 }
    } else {
        data = { 'id': name, 'status': 0 }
    }
    $.ajax({
        type: 'post',
        url: 'config/upSetor',
        data: data,
        success: function (data) {
            console.log(data);
            ck = '';
            name = '';
        }
    })
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

$(document).on('click', '#disparar', function (e) {
    e.preventDefault();
    if (confirm('Esta opção será disparados novas mensagens. Disparos realizados não serão reenviados! Deseja continuar?') == true) {
        var url = $(this).attr('href');
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function (data) {
                console.log(data);
                // $('#disparar').prop('hidden', true);
                // alert('Disparos realizados com sucesso!');
            }
        });
    }
});


$(document).on('click', '.status', function (e) {
    e.preventDefault();
    const id = [];
    var url = $(this).attr('href');

    $.ajax({
        type: "get",
        url: url,
        dataType: "json",
        success: function (data) {
            $('#tbody').html(data.tr);
        }
    });
});

$(document).on('click','.qrcod', function (e) {
   
    e.preventDefault();
    var url = $(this).attr('href');
 
    $.ajax({
        type: "get",
        url: url,
      
        dataType: "json",
        success: function (data) {
           //  alert(url);
            // console.log(data);
            $('#gerardorQRcod').modal('show');  
            $('#qrCode').html(data);
            setTimeout(function() {
                location.reload();     
            },30000);
        }
    });
    
}); 
