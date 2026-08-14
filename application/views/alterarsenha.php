<form id="rPws" action="<?php echo base_url() ?>config/ressetPws" method="post">
    <div class="modal-header  btn-danger ">
        <h5 class="modal-title" id="my-modal-title">Digite sua nova senha</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for=""></label>
            <input type="password" name="pws" id="pws" class="form-control" placeholder="" aria-describedby="helpId">
            <input type="hidden" name="id_cliente" value="<?php echo $_SESSION['id_cliente'] ?>">
            <small id="helpId" class="text-muted">Nova senha</small>
        </div>
    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
        <button type="submit" class="btn btn-primary">Alterar</button>

    </div>
</form>


<script>
    $("#rPws").submit(function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            success: function(response) {
                $(".modal-dialog").removeClass('modal-sm');
                msg = '<div class="modal-body"><div class="container"><h3 class="m-5 p-5 text-center">Senha alterada com sucesso!</h3></div></div>';
                $("#MyModal").html(msg);
                $('#usuarioModal').modal('show');
            }
        })
    });
</script>