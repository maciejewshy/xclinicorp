<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KENTROCORP - HealthZap</title>

    <!-- Custom fonts for this template-->
    <link href="https://healthzap.top/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="https://healthzap.top/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://healthzap.top/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .symbol {
            position: relative;
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .medical-cross {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cross-vertical {
            width: 15px;
            height: 60px;
            background-color: #2A7DE1;
            border-radius: 3px;
            position: absolute;
        }

        .cross-horizontal {
            width: 60px;
            height: 15px;
            background-color: #2A7DE1;
            border-radius: 3px;
            position: absolute;
        }

        .message-dots {
            position: absolute;
            bottom: -3px;
            right: -3px;
            display: flex;
        }

        .dot {
            width: 10px;
            height: 10px;
            background-color: #34B7F1;
            border-radius: 50%;
            margin: 0 2px;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .healthzap {
            font-size: 24px;
            font-weight: bold;
            color: #2A7DE1;
            letter-spacing: 0.5px;
        }

        .tagline {
            font-size: 12px;
            color: #5E6D77;
            margin-top: 2px;
        }

        .clinicorp {
            color: #2A7DE1;
            font-weight: 600;
        }

        .system-info {
            text-align: center;
            max-width: 300px;
            color: #5E6D77;
            font-size: 14px;
            line-height: 1.5;
            margin-top: 20px;
        }

        @media (max-width: 991px) {
            .logo-container {
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body class="bg-gradient-primary py-5 my-5">

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5 m-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="logo-container">
                                    <div class="logo">
                                        <div class="symbol">
                                            <div class="medical-cross">
                                                <div class="cross-vertical"></div>
                                                <div class="cross-horizontal"></div>
                                            </div>
                                            <div class="message-dots">
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                                <div class="dot"></div>
                                            </div>
                                        </div>
                                        <div class="logo-text">
                                            <div class="healthzap">HealthZap</div>
                                            <div class="tagline">Sistema de disparos para <span class="clinicorp">Clinicorp</span></div>
                                        </div>
                                    </div>
                                    <div class="system-info">
                                        Sistema especializado em comunicação para clínicas e profissionais de saúde.
                                    </div>


                                    <div class="system-info">
                                        <a href="<?= base_url('privacidade') ?>">Nossa política de privaciadade</a> e <a href="<?= base_url('termos') ?>">Termos e condições</a>
                                    </div>
                                </div>

                            </div>


                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bem vindo!</h1>
                                    </div>
                                    <hr>
                                    <form action="<?php echo base_url() ?>index.php/index/login" method="post" name="frmlogin">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Entre Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="senha" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Senha">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://healthzap.top/assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="https://healthzap.top/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://healthzap.top/assets/js/clientes.js"></script>

</body>

</html>