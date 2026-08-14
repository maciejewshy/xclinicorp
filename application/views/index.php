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
          <div class="col-md-10 m-0 p-0">
            <h1 class="h3 mb-0 text-gray-800">Campanhas</h1>
          </div>
          <div class="col-md-1">
            <a href="<?php echo base_url('admin/clientes/disparos?id_cliente=') . '' . $_SESSION['id_cliente'] ?>"
              class="btn btn-info" id="disparar"> DISPARAR</a>
          </div>
        
        </div>
      </div>
      <div>
        <table class="table table-bordered" id="dtCampanha" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="th-sm">ID

              </th>
              <th class="th-sm">DATA

              </th>
              <th class="th-sm">CAMPANHA

              </th>
              <th class="th-sm">Total

              </th>

              <th class="th-sm">
                  Disparos
              </th>
            </tr>
          </thead>
          <tbody >

            <?php

            if (isset($campanha)) {
              foreach ($campanha as $key => $value) {
                $data_criacao = str_replace('-', '', $value->data_criacao);
                $id = $key + 1;
                echo " 
                    <tr>
                    <td>" . $id . "</td>
                    <td>" . substr($data_criacao, 6, 2) . "/" . substr($data_criacao, 4, 2) . "/" . substr($data_criacao, 0, 4) . "</td>
                    <td>" . $value->campanha . "</td>
                    <td>" . $value->TotalDisp . "</td>
                    <td> <a href='" . base_url() . "index/getListDisparos?id_campanha=" . $value->id_campanha . "'> Ver lista </a></td>
                    </tr>";
              }
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