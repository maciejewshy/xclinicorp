<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Configuração de mensagem</h1>
    </div>
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="card col-md-12">
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Mensagens</h1>

                </div>

                <div class="col-6">
                    <form action="<?php echo base_url() ?>config/edtMensagem" id="addmensagem" method="POST" role="form">
                        <input type="hidden" name="id_mensagem" value="<?php echo $msg[0]->id_mensagem ?>">
                        <div class="form-group">
                            <label for="">Filas:</label>

                            <select name="id_canal" class="form-control" required="required">

                                <option value="<?php echo $msg[0]->id_canal; ?>">
                                    <?php echo $msg[0]->nomeCanal; ?>
                                </option>

                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="">Mensagem:</label>
                                <textarea class="form-control" name="mensagem" id="" rows="7">
                                    <?php echo $msg[0]->mensagem ?>
                                </textarea>
                            </div>
                            <label for=""> Para Adicionar os campos no texto:<br>
                                <b>Nome:</b> [nome]<br>
                                <b>Valor:</b> [valor]<br>
                                <b>Data:</b> [data]<br>
                                <b>hora:</b> [hora]<br>
                            </label>
                        </div>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"> Atualizar</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>