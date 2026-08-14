<div class="container-fluid">


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cadastro de clientes</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="card col-md-12">
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Canais</h1>
                    <a data-toggle="modal" href='#modal-id' class="btn btn-success d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Adcionar </a>
                </div>
            </div>
            <div>

                <?php

                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" style="width:35%">FILA</th>
                            <th scope="col">ID</th>
                            <th scope="col">APIKEY</th>
                            <th scope="col">TIPO</th>
                            <th scope="col">DATA CRIAÇÃO</th>
                            <th scope="col text-center" style="text-align:center; width:70px">STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php
                        if ($canais) {
                            foreach ($canais as $key => $value) {
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
                                            <td scope="col">' . $value->apiKey . '</td>
                                            <td scope="col">' . $value->tipo . '</td>
                                            <td scope="col">' . implode("/", array_reverse(explode("-",  $value->data_criacao))) . '</td>
                                            <td scope="col"><a href="' . base_url() . 'clientes/updateCanal?status=' . $value->status . '&id_canal=' . $value->id_canal . '" class="btn btn-sm btn-block btn-' . $class . ' status" id="canal' . $value->id_canal . '">' . $status . '</a></td>
                                      </tr>';
                            }
                        }
                        ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header  bg-warning">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Adicionar canais</h5>
                    <button type="button" class="close btn-default" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> </span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url() ?>clientes/addCanal" id="addcanal" method="POST" role="form">
                        <input type="hidden" name="id_cliente" value="<?php echo base64_decode($_GET['code']); ?>">
                        <div class="form-group float-start">
                            <label for="">FILA:</label>
                            <input type="text" class="form-control" id="fila" name="nomeCanal" placeholder="Nome da fila">
                        </div>
                        <div class="form-group  float-end">
                            <label for="">Id:</label>
                            <input type="text" class="form-control" id="idFila" name="idFila" placeholder="id da fila">
                        </div>
                        <div class="form-group">
                            <label for="">TIPO DE CANAL:</label>

                            <select name="id_tipoCanal" id="input" class="form-control" required="required">
                                <option value="">Selecione</option>
                                <?php
                                foreach ($tcanais as $key => $value) {
                                ?>
                                    <option value="<?php echo $value->id_tipoCanal; ?>">
                                        <?php echo $value->tipo; ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">APIKEY:</label>
                            <input type="text" class="form-control" id="apiKey" name="apiKey" placeholder="APIKEY  ">
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"> Adcionar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
                </form>
            </div>
        </div>
    </div>