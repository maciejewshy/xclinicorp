<form id="addUsuarios" action="<?= base_url() ?>index/addUser" method="post" enctype="multipart/form-data">
    <div class="modal-header">
        <h5 class="modal-title" id="my-modal-title">Adcionar novo usuário </h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">

        <div class="container">
            <div class="form-group">
                <label for="nome"><strong> Nome</strong></label>
                <input type="text" class="form-control" name="nome" id="nome" required placeholder="Nome do usuario" onkeydown="javascript: return keydown(event)">
                <small id="helpNome" class="text-muted">Obrigatório. </small>
            </div>
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="usuario"><strong> Usuário</strong></label>
                        <input type="text" class="form-control keydown" name="usuario" value="" onkeydown="javascript: return keydown(event)" autocomplete="off" id="idUser" required placeholder="nome do usuario">
                        <small id="helpUsuario" class="text-muted">Obrigatório</small>
                    </div>

                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="senha"><strong> Senha</strong></label>
                        <input type="password" class="form-control" name="pws" id="senha" onkeydown="javascript: return keydown(event)" required autocomplete="off" placeholder="">
                        <small id="helpId" class="text-muted">Obrigatório</small>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" id="addUsuario" disabled class="btn btn-primary  btn-block btn-md" value="Salvar">
        <button type="button" class="btn btn-warning  btn-block btn-md" data-dismiss="modal">Sair</button>
    </div>

    <script>
       

        $("#idUser").blur(function(e) {
            e.preventDefault();

            $('#addUsuario').css("display", "none");
            var url = 'index/addUser';
            var usuario = $(this).val();
            $.ajax({
                type: "get",
                url: url,
                data: {
                    'usuario': usuario
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response == 1) {
                        $('#alert').modal('show');
                    } else {
                        $('#addUsuario').css("display", "block");
                        $('#addUsuario').prop("disabled", false); // Element(s) are now enabled.
                    }
                }
            });
        });


        $("#addUsuarios").submit(function(e) {
                    e.preventDefault();
                    var url = $(this).attr('action');
                    var data = $(this).serialize();
                    $.ajax({
                            type: "post",
                            url: url,
                            data: data,
                            dataType: "json",
                            success: function(response) {
                                msg = '<div class="modal-body"><div class="container"><h3 class="m-5 p-5 text-center">Cadastro realizado com sucesso!</h3></div></div>';
                                $("#MyModal").html(msg);
                                $('#usuarioModal').modal('show');
                            }
                        })
                    });
                
    </script>