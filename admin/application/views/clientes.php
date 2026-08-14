<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Clientes</h1>
    <a href="<?php echo base_url() ?>clientes/add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Adcionar </a>
</div>


<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th>Name</th>
                        <th>CNPJ</th>
                        <th>Canais</th>
                        <th>Host</th>
                        <th>Data Criação</th>
                        <th>Opções</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>CNPJ</th>
                        <th>Canais</th>
                        <th>Host</th>
                        <th>Data Criação</th>
                        <th>Opções</th>
                        <th>Status</th>
                    </tr>
                </tfoot>
                <tbody id="tbody">
                    <?php foreach ($clientes as $key => $value) {
                        $key++;
                        if ($value->status == 1) {
                            $status = 'Bloquear';
                            $class = 'success';
                        } else {
                            $status = 'Ativar';
                            $class = 'danger';
                        }
                        ?>

                        <tr>
                            <td>
                                <?php echo $key ?>
                            </td>
                            <td>
                                <?php echo $value->cliente ?>
                            </td>
                            <td>
                                <?php echo $value->cnpj ?>
                            </td>
                            <td>
                                <?php echo $value->canais ?>
                            </td>
                            <td>
                                <?php echo $value->host ?>
                            </td>
                            <td>
                                <?php echo $value->data_criacao ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url() . 'addCanais?code='.base64_encode($value->id_cliente) ?>"
                                    type="button" class=" addCanal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" data-toggle="tooltip"
                                        data-original-title="Adicionar canal" height="25" fill="currentColor"
                                        class="bi bi-node-plus" viewBox="0 0 17 17">
                                        <path fill-rule="evenodd"
                                            d="M11 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM6.025 7.5a5 5 0 1 1 0 1H4A1.5 1.5 0 0 1 2.5 10h-1A1.5 1.5 0 0 1 0 8.5v-1A1.5 1.5 0 0 1 1.5 6h1A1.5 1.5 0 0 1 4 7.5h2.025zM11 5a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 11 5zM1.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                                    </svg>
                                </a>
                                <a href="<?php echo base_url() . 'addMensagens?code='.base64_encode($value->id_cliente)?>"
                                    type="button" class=" addMsg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" data-toggle="tooltip"
                                        data-original-title="Adicionar mensagem"  fill="currentColor"
                                        class="bi bi-chat-right-text" viewBox="0 0 17 17">
                                        <path
                                            d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1H2zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12z" />
                                        <path
                                            d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z" />
                                    </svg>
                                </a>
                                <!-- <button data-whatever="<?php echo $value->id_cliente ?>" class="mr-2 addCanais" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-node-plus" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M11 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM6.025 7.5a5 5 0 1 1 0 1H4A1.5 1.5 0 0 1 2.5 10h-1A1.5 1.5 0 0 1 0 8.5v-1A1.5 1.5 0 0 1 1.5 6h1A1.5 1.5 0 0 1 4 7.5h2.025zM11 5a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 11 5zM1.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                                        </svg>
                                    </button> -->
                                <a href="<?= base_url('clientes/editCliente?code=' . base64_encode($value->id_cliente)) ?>" class="mr-2" data-toggle="tooltip" data-original-title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 17 17">
                                        <path 
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" 
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </a>
                                <a href="" class="   ">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-trash" viewBox="0 0 17 17" data-toggle="tooltip"
                                        data-original-title="Excluir" >
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                        <path
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                    </svg>
                                </a>


                            </td>
                            <td scope="col"><a href="<?php echo base_url() . 'clientes/updateCliente?status=' . $value->status . '&id_Cliente='
                                . $value->id_cliente . '"type="button" class="btn btn-sm btn-block btn-'
                                . $class . ' status" id="cliente' . $value->id_cliente . '">'
                                . $status ?></a></td>
                                    
                            </tr>
                            <?php
                    } ?>

                    </tbody>
                </table>
            </div>
            </div>
            </div>