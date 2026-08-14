<!-- Begin Page Content -->
<div class="container-fluid">


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cadastro de clientes</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="card col-md-8">
            <div class="card-body">
                <h5 class="card-title">Entre com os dados do cliente</h5>

                <!-- Floating Labels Form -->
                <form class="row g-3" method="post" action="<?php echo base_url()?>clientes/addCliente">
                    <div class="col-md-6 ">
                        <div class="form-floating">
                            <label for="floatingName">Cliente</label>
                            <input type="text" class="form-control" id="cliente"  name="cliente" placeholder="Cliente">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-floating">
                            <label for="floatingCnpj">Cnpj</label>
                            <input type="number" class="form-control" name="cnpj" id="cnpj" placeholder="Cnpj">
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="form-floating">
                            <label for="floatingEmail">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="form-floating">
                            <label for="floatingHost">Host</label>
                            <input type="text" class="form-control" id="host"  name="host" placeholder="Host">
                        </div>
                    </div>
                    <div class="col-md-2 mt-3">
                        <div class="form-floating">
                            <label for="floatingIdAssinante">ID do Assinante</label>
                            <input type="text" class="form-control" id="idAssinante"  name="idAssinante" placeholder="id do Assinante">

                        </div>
                    </div>
                    <div class="col-md-2 mt-3">
                        <div class="form-floating">
                            <label for="floatingmatricula">Matrícula</label>
                            <input type="text" class="form-control" id="matricula"  name="matricula" placeholder="matricula">

                        </div>
                    </div>
                    <div class="col-md-2 mt-3">
                        <div class="form-floating">
                            <label for="floatingSenha">Senha</label>
                            <input type="password" class="form-control" name="Senha"  id="Senha" placeholder="Senha">
                        </div>
                    </div>
                    <div class="col-md-2 mt-3">
                        <div class="form-floating">
                            <label for="floatingCanais">Qnt de canais</label>
                            <input type="number" class="form-control" name="canais"  id="canais"  placeholder="0">
                        </div>
                    </div>
                    
                    <div class="col-md-4 mt-3">
                        <div class="form-floating">
                            <label for="floatingToken">Token</label>
                            <input type="text" class="form-control" id="token"  name="token" placeholder="Token">
                        </div>
                    </div>
                    <div class="col-md-12 text-center mt-5">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>

                </form><!-- End floating Labels Form -->

            </div>
        </div>



    </div>