<div class="container-fluid">


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="card col-md-12">
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Canais</h1>

                </div>
            </div>
            <div>
                <table class="table table-bordered" id="dtCampanha" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="th-sm">CAMPANHA</th>
                            <th class="th-sm">DATA</th>
                            <th class="th-sm">Cliente</th>
                            <th class="th-sm">Número</th>
                            <th class="th-sm" style="width:150">Retorno</th>
                        </tr>
                    </thead>
                    <tbody >

                        <?php 
                        
                        foreach ($disparos as $key => $value) {
                          $data_criacao = str_replace('-','', $value->data_criacao);
                         
                          echo " 
                                <tr>
                                <td>".$value->campanha."</td>
                                <td>" .  substr($data_criacao, 6, 2) . "/" . substr($data_criacao, 4, 2) . "/" . substr($data_criacao, 0, 4). "</td>
                                <td>" . $value->numero . "</td>
                                <td>" . substr($value->mensagem, 0,100) . "...</td>
                                <td >" .$value->error."</td>
                                </tr>";
                        }
                        ?>


                    </tbody>
                    <tfoot>


                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</div>