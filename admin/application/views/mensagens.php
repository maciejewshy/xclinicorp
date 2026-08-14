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
                    <a data-toggle="modal" href='#modal-id'
                        class="btn btn-success d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Adcionar </a>
                </div>
            </div>
            <div>
                <div col-6>
                    <div class="form-group col-6">
                        <label for="" class="h3">
                            <?php echo $cliente[0]->cliente ?>
                        </label>

                    </div>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" style="width:35%">TIPO</th>
                                <th scope="col">FILA</th>
                                <th scope="col">MENSAGEM</th>
                                <th scope="col">DATA CRIAÇÃO</th>
                                <th scope="col text-center" style="text-align:center; width:70px">STATUS</th>
                                <th scope="col text-center" style="text-align:center; width:70px">AÇÃO</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" style="width:35%">TIPO</th>
                                <th scope="col">FILA</th>
                                <th scope="col">MENSAGEM</th>
                                <th scope="col">DATA CRIAÇÃO</th>
                                <th scope="col text-center" style="text-align:center; width:70px">STATUS</th>
                                <th scope="col text-center" style="text-align:center; width:70px">AÇÃO</th>
                            </tr>
                        </tfoot>
                        <tbody id="tbody">
                            <?php

                            if (isset($msg)) {
                                foreach ($msg as $key => $value) {
                                    $key++;
                                    if ($value->status == 1) {
                                        $status = 'Bloquear';
                                        $class = 'success';
                                    } else {
                                        $status = 'Ativar';
                                        $class = 'danger';
                                    }
                                    echo '  <tr>
                                                <td scope="col">' . $key . '</td>
                                                <td scope="col">' . $value->nomeCanal . '</td>
                                                <td scope="col">' . $value->idFila . '</td>
                                                <td scope="col">' . substr($value->mensagem,0,75) . '...</td>
                                                <td scope="col">' . implode("/", array_reverse(explode("-", $value->data_criacao))) . '</td>
                                                <td scope="col">' . $value->status . '</td>
                                                <td scope="col"><a href="' . base_url() . 'clientes/updateMensagem?status=' . $value->status . '&id_mensagem=' . $value->id_mensagem . '" class="btn btn-sm btn-block btn-' . $class . ' status" id="canal' . $value->id_canal . '">' . $status . '</a></td>
                                          </tr>';
                                }
                            } else{
                                echo "<tr> <td colspan=10> nenhum dado encontrado </td> </tr>";
                            }
                            ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header  bg-warning">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Adicionar mensagem</h5>
                        <button type="button" class="close btn-default" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"> </span>
                        </button>
                    </div>

                    <div class="modal-body">
                     
                        <form action="<?php echo base_url() ?>clientes/addMensagem" id="addmensagem" method="POST" role="form">
                            <input type="hidden" name="id_cliente" value="<?php echo $cliente[0]->id_cliente ?>">
                            <div class="form-group">
                                <label for="">Fila:</label>

                                <select name="id_canal" id="input" class="form-control" required="required">
                                    <option value="">Selecione</option>
                                    <?php
                                    foreach ($canal as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value->id_canal; ?>">
                                            <?php echo ($value->nomeCanal); ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="">Mensagem:</label>
                                    <textarea class="form-control" name="mensagem" id="" rows="7"></textarea>
                                </div>
                                <label for=""> Para Adicionar os campos no texto:<br>
                                    <b>Nome:</b> [nome]<br>
                                    <b>Valor:</b> [valor]<br>
                                    <b>Data:</b> [dia]<br>
                                    <b>hora:</b> [hora]<br>
                                </label>
                            </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"> Salvar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>