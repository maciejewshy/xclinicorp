<div class="container-fluid" style="font-size:small">


    <!-- Page Heading -->


    <!-- Content Row -->
    <div class="row">

        <div class="card col-12">

            <div class="card-body">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div class="col-md-10 m-0 p-0">
                        <h1 class="h3 mb-0 text-gray-800">Campanhas</h1>
                    </div>
                </div>
                <div class="row">

                    <div class="col">
                        <div class="container-fluid">
                            <ul id="clothingnav1" class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" href="#home1" id="hometab1" role="tab"
                                        data-toggle="tab" aria-controls="home" aria-expanded="true">TIPOS</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#paneTwo1" role="tab" id="hatstab1"
                                        data-toggle="tab" aria-controls="hats">CANAIS</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#paneTwo2" role="tab" id="hatstab2"
                                        data-toggle="tab" aria-controls="hats">MENSAGENS</a> </li>
                                <!-- Dropdown -->
                            </ul>
                        </div>
                        <!-- Content Panel -->
                        <div id="clothingnavcontent1" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active p-5 border " id="home1"
                                aria-labelledby="hometab1">

                                <table class="table table-striped col-12 table-border-responsive" id="dataTable">
                                    <thead class="thead-inverse">

                                        <tr>
                                            <th>Tipo</th>
                                            <th>Fila</th>
                                            <th>Canal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="dataTable">
                                        <?php
                                        foreach ($canais as $key => $value) {
                                            $value->status = (1) ? $status = "ativo" : $status = "inativo";
                                            ?>
                                            <tr>
                                                <td scope="row">
                                                    <?php echo $value->tipo ?>
                                                </td>
                                                <td scope="row">
                                                    <?php echo $value->idFila ?>
                                                </td>
                                                <td scope="row">
                                                    <?php echo $value->nomeCanal ?>
                                                </td>
                                                <td scope="row">
                                                    <?php echo $status ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>

                            <!---------------------------------------------------------------TABLEPANEL 2-------------------------------------------------------- -->

                            <div role="tabpanel" class="tab-pane fade  p-5 border " id="paneTwo1"
                                aria-labelledby="hatstab1">

                                <table class="table table-striped table-inverse table " id="dtSetor">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Setor</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody class="dataTable">
                                        <?php
                                        foreach ($setor as $key => $value) {
                                            ?>

                                            <tr>
                                                <td scope="row">
                                                    <?php echo $value->setor ?>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="setor<?php echo $key ?>"
                                                        id="<?php echo $value->id; ?>" value="<?php if ($value->status == 1) {
                                                               echo 1;
                                                           } else {
                                                               echo 0;
                                                           } ?>" class="setor-checked" <?php if ($value->status == 1)
                                                                echo "checked" ?>>
                                                    </td>
                                                </tr>

                                            <?php
                                        } ?>
                                    </tbody>
                                </table>


                            </div>


                            <!---------------------------------------------------TABLEPANEL 3------------------------------------------------------------- -->
                            <div role="tabpanel" class="tab-pane fade p-5 border " id="paneTwo2"
                                aria-labelledby="hatstab2">

                                <table class="table table-striped table-inverse table-responsive " id="">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Canal</th>
                                            <th>Mensagem</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody class="dataTable">
                                        <?php
                                        foreach ($mensagem as $key => $value) {
                                            ?>

                                            <tr>
                                                <td scope="row">
                                                    <?php echo $value->tipo ?>
                                                </td>
                                                <td style="font-size:small">
                                                    <?php echo substr($value->mensagem, 0, 250) ?>
                                                    </h6>
                                                </td>
                                                <td><a href="<?php echo base_url('config/edt_message?id_mensagem=' . $value->id_mensagem) ?>"
                                                        class="btn-edit" style="border:none"><i class="fas fa-edit    "></i>
                                                    </a> <i class="fa fa-trash"></i></td>
                                            </tr>

                                            <?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="EDIT" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    OK
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>