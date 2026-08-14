<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clientes extends CI_Controller
{
    private $_TOKEN;
    private $_idAssinante;
    private $_idCliente;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Clientes_Model');
        $this->load->library('Api');
    }

    public function index()
    {
        $dados['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
        $dados['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
        $dados['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
        $dados['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
        $dados['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';


        if (isset($_SESSION['logged']) == false) {
            $this->load->view('login', $dados);
        } else {
            $id['id_usuario'] = $_SESSION['id_usuario'];
            $dados['clientes'] = $this->getCli($id);
            $this->load->view('pages/header', $dados);
            $this->load->view('clientes');
            $this->load->view('pages/footer');
        }
    }

    function login()
    {

        $login = $this->Clientes_Model->login($_POST['user'], $_POST['senha']);

        if ($login) {
            foreach ($login as $item) {
                $this->session->set_userdata('id_usuario', $item->id_usuario);
                $this->session->set_userdata('user', $item->usuario);
                $this->session->set_userdata('logged', TRUE);
                $this->session->set_flashdata('message', "Acesso realizado com sucesso!");
            }
        } else {
            // $this->funcionarios_model->atualizaHistorico("Tentativa de logar no sistema.",$user);
            $this->session->set_flashdata('error', "Erro de acesso. Verifique seus dados!");
        }
        redirect($this->index());
    }

    function logout()
    {
        $id_usuario = 1;

        if ($this->Clientes_Model->logout($id_usuario) == true) {
            $this->session->unset_userdata('id_usuario', $item->id_usuario);
            $this->session->unset_userdata('user', $item->usuario);
            $this->session->unset_userdata('logged', false);
            $this->session->set_flashdata('logout', "Deslogado com sucesso");
        }

        redirect($this->index());
    }

    public function disparos()
    {
        $horaAtual = date('H:m', time());

        if ((($horaAtual > '08:00') && ($horaAtual < '18:00'))) {

            if (isset($_GET)) {

                $dados = $this->Clientes_Model->getClientes($_GET);
            } else {

                $dados = $this->Clientes_Model->getClientes();
            }


            foreach ($dados as $key => $value) {
                if ($value->status == 1) {
                    $dateAg = '';
                    $filaId = '';
                    $id_cliente = $value->id_cliente;
                    $token = $value->token;
                    $url = $value->host;
                    $idAssinante = $value->idAssinante;
                    $matricula = $value->matricula;
                    $now = date('w', time());
                    if ($now == 6) {
                        $dateAg = date('Y-m-d', strtotime('+2 day'));
                    } else {
                        $dateAg = date('Y-m-d', strtotime('+1 day'));
                    }

                    $this->sendToken($token, $matricula);
                    $this->sendIdAssinante($idAssinante);

                    $this->_idCliente = $id_cliente;
                    $idCliCanal['id_cliente'] = $id_cliente;
                    $canal = $this->getCanal($idCliCanal);

                    foreach ($canal as $key => $canais) {

                        // Agenbda
                        if ($canais->id_tipoCanal == 2 && $canais->status == 1) {
                            $this->dispAgenda($idAssinante, $dateAg, $url, $id_cliente);
                        }
                        // Vencimento
                        if ($canais->id_tipoCanal == 1 && $canais->status == 1) {
                            $this->dispLembrete($idAssinante, $dateAg, $url, $id_cliente);
                        }

                        //Aniversario
                        // if ($canais->id_canal == 2 && $canais->status == 1) {
                        //     $this->dispAniversario($idAssinante, $dateAg, $url, $id_cliente);
                        // }
                        // Recobrança
                        // if ($canais->id_canal == 4 && $canais->status == 1) {
                        // }
                        //   $this->dispAniversario($idAssinante, $dateAg, $url, $id_cliente);
                        //   $this->dispLembrete($idAssinante, $dateAg, $url, $id_cliente);
                    }
                }
            }
            return true;
        }
    }

    function sendToken($token, $matricula)
    {
        $this->_TOKEN = base64_encode($matricula . ':' . $token);
    }

    function sendIdAssinante($idAssinante)
    {
        $this->_idAssinante = $idAssinante;
    }

    public function checkNumber($url, $filaId, $apiKey, $number)
    {

        $url = 'checkIfUserExists';

        $data = [
            "queueId" => intval($filaId),
            "apiKey" => strval($apiKey),
            "number" => $number,
        ];

        return $this->api->checkNumber($data, $url);
    }

    public function dispAgenda($idAssinante, $dateAg, $url, $id_cliente)
    {

        $res['lista'] = '';
        $urlCli = 'https://api.clinicorp.com/rest/v1/appointment/list?subscriber_id=';
        $returno = $this->getAgenda($urlCli, $idAssinante, $dateAg);

        $camp = $this->addCampanha('AGENDAMENTO', $id_cliente);

        $res['lista'] = $returno;


        foreach ($res['lista'] as $key => $value) {
            // echo " - num: {$value->MobilePhone} Numero enviado - agenda <br>";
            if ($this->Clientes_Model->checkShoots($value->MobilePhone) == false) {

                $date = $this->shoot($res, $url, $value, $camp, 'AGENDA');
            }
        }
    }

    public function getAgenda($url, $idAssinante, $dateAg)
    {

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url . $idAssinante . '&from=' . $dateAg . '&to=' . $dateAg,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $this->_TOKEN,
                    'accept: application/json; charset=utf-8'
                ),
            )
        );

        $ret = curl_exec($curl);
        curl_close($curl);

        return json_decode($ret);
    }

    function dispLembrete($idAssinante, $dateAg, $url, $id_cliente)
    {
        $dateAg = date('Y-m-d');
        $this->disLem2($idAssinante, $dateAg, $url, $id_cliente);
        $dateAg = date('Y-m-d', strtotime('+1 day'));
        $this->disLem2($idAssinante, $dateAg, $url, $id_cliente);
        $dateAg = date('Y-m-d', strtotime('+5 day'));
        $this->disLem2($idAssinante, $dateAg, $url, $id_cliente);
    }

    function getPacient($url, $idAssinante, $id)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.clinicorp.com/rest/v1/patient/get?subscriber_id=' . $idAssinante . '&PatientId=' . $id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $this->_TOKEN,
                    'accept: application/json; charset=utf-8'
                ),
            )
        );
        $ret = curl_exec($curl);
        curl_close($curl);
        return json_decode($ret);
    }

    //Função de disparar
    public function shoot($res, $url, $value, $idCamp, $tipo)
    {
        $url = 'enqueueMessageToSend';
        if (isset($value->Dentist_PersonId)) {
            $return = $this->Clientes_Model->getSetor($value->Dentist_PersonId);
        }

        if ($return == true) {
            if ($value->Deleted == "") {
                if ($ret = $this->Clientes_Model->getMensagem($tipo, $this->_idCliente)) {
                    $filaId = $ret[0]->idFila;
                    $apiKey = $ret[0]->apiKey;

                    $res['mensagem'] = $ret[0]->mensagem;
                    $msg = str_replace('[hora]', $value->fromTime, $res['mensagem']);
                    $msg = str_replace('[nome]', $value->PatientName, $msg);
                    $msg = str_replace('[data]', substr($value->AtomicDate, 6, 2) . "/" . substr($value->AtomicDate, 4, 2) . "/" . substr($value->AtomicDate, 0, 4), $msg);
                }
                //  $curl = curl_init();
                $data = [

                    "queueId" => intval($filaId),
                    "apiKey" => strval($apiKey),
                    "number" => $value->MobilePhone,
                    // "number" => "38984044593",
                    "text" => $msg,
                    // "campaignName" => "Confirmação de agenda",
                    "campaignName" => "Agenda " . $dateAg,
                    "hidden" => FALSE
                ];


                $data_string = ($data);


                $returno = $this->checkNumber($url, $filaId, $apiKey, $value->MobilePhone);

                $dates = $this->api->enqueueMessageToSend($data_string, $url);
                $enqueuedId = $dates->enqueuedId;


                if (isset($returno->message) == "Invalid phone number") {
                    $dates->message = "Número inválido ou não tem WhatsApp";
                }

                $this->Clientes_Model->addDisparos($dates->message, $value->MobilePhone, $msg, $idCamp, $filaId, $enqueuedId);

                return ($dates);
            }
        }
    }

    public function shootCob($url, $value, $idCamp, $tipo)
    {
        $dates['message'] = "";
        $url = 'enqueueMessageToSend';
        $val = $this->getPacient($url, $this->_idAssinante, $value->PatientId);
        $value->Phone = $val->Phone;

        $numero = '';
        if ($ret = $this->Clientes_Model->getMensagem($tipo)) {

            $filaId = $ret[0]->idFila;
            $apiKey = $ret[0]->apiKey;
            $nome = str_replace(")", "", explode("(", $value->PatientName));

            $valor = number_format($value->Amount, 2, ',', '.');

            $res['mensagem'] = $ret[0]->mensagem;
            $msg = str_replace('[hora]', $value->DueDateAtomic, $res['mensagem']);
            $msg = str_replace('[nome]', $nome[0], $msg);
            $msg = str_replace('[valor]', $valor, $msg);
            $msg = str_replace('[data]', substr($value->DueDateAtomic, 6, 2) . "/" . substr($value->DueDateAtomic, 4, 2) . "/" . substr($value->DueDateAtomic, 0, 4), $msg);
            if (($value->DueDateAtomic - Date('Ymd')) == 0) {
                $msg = str_replace('[dia]', ' vence hoje.', $msg);
            }
            if (($value->DueDateAtomic - Date('Ymd')) == 1) {
                $msg = str_replace('[dia]', ' vence amanhã.', $msg);
            }
            if (($value->DueDateAtomic - Date('Ymd')) > 1) {
                $msg = str_replace('[dia]', '.', $msg);
            }
        }
        // $curl = curl_init();
        $data = [
            "queueId" => intval($filaId),
            "apiKey" => strval($apiKey),
            "number" => $value->Phone,
            // "number" => "38988042960",
            "fileId" => 0,
            "text" => $msg,
            "campaignName" => "Lembrete de vencimento",
            // "campaignName" => "Agenda " . $dateAg,
            "hidden" => FALSE
        ];
        $data_string = ($data);
        $returno = $this->checkNumber($url, $filaId, $apiKey, $value->Phone);


        $dates = $this->api->enqueueMessageToSend($data_string, $url);

        $enqueuedId = $dates->enqueuedId;

        if (isset($returno->message) == "Invalid phone number") {
            $dates['message'] = "Número inválido ou não tem WhatsApp";
        }

        if ($this->Clientes_Model->checkShoots($value->Phone) == false) {
            $this->Clientes_Model->addDisparos($dates->message, $value->Phone, $msg, $idCamp, $filaId, $enqueuedId);
        }

        return ($dates);
    }



    function disLem2($idAssinante, $dateAg, $url, $id_cliente)
    {
        $urlCli = 'https://api.clinicorp.com/rest/v1/payment/list?subscriber_id=';
        $returno = $this->getLembrete($urlCli, $idAssinante, $dateAg);

        $res = $returno;

        $camp = $this->addCampanha('Lembrete de vencimento', $id_cliente);
        foreach ($res as $key => $value) {

            $this->shootCob($url, $value, $camp, 'COBRANÇA');
        }
    }
    public function getLembrete($url, $idAssinante, $dateAg)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url . $idAssinante . '&from=' . $dateAg . '&to=' . $dateAg . '&search_type=duedate',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $this->_TOKEN,
                    'accept: application/json; charset=utf-8'
                ),
            )
        );
        $ret = curl_exec($curl);
        curl_close($curl);
        return json_decode($ret);
    }

    function addCampanha($camp, $id_cliente)
    {
        $Camp['campanha'] = $camp;
        $Camp['id_cliente'] = $id_cliente;
        $Camp['data_criacao'] = Date("Y-m-d");
        return $this->Clientes_Model->addcampaign($Camp);
    }

    public function cobranca($dados) {}

    public function dispAniversario($idAssinante, $dateAg, $url, $id_cliente)
    {
        $aniv = $this->getAniversario();
    }
    public function recobranca($dados) {}
    function getCli($ids = null)
    {
        $id = $ids;


        if ($_GET) {
            $id['id_cliente'] = base64_decode($_GET['code']);
        }
        if ($_POST) {
            $id['id_cliente'] = $_POST['id_cliente'];
        }
        if (!isset($id)) {
            return $this->Clientes_Model->getClientes();
        } else {
            return $this->Clientes_Model->getClientes($id);
        }
    }

    function filterCli($id = null)
    {
        if ($_GET) {
            $id = $_GET;
        }
        if ($_POST) {
            $id = $_POST;
        }
        if (!isset($id)) {
            return $this->Clientes_Model->filterClientes();
        } else {
            return $this->Clientes_Model->filterClientes($id);
        }
    }
    public function add()
    {
        $this->load->view('pages/header');
        $this->load->view('addClientes');
        $this->load->view('pages/footer');
    }

    // public function addCliente()
    // {
    //     $dados = $_POST;
    //     $dados['id_usuario'] = $_SESSION['id_usuario'];
    //     $dados['data_criacao'] = date('Y-m-d');
    //     $dados['Senha'] = base64_encode($_POST['Senha']);

    //     $return = $this->Clientes_Model->addClientes($dados);
    //     $this->load->view('pages/header');
    //     $this->load->view('addClientes');
    //     $this->load->view('pages/footer');
    // }

    public function addCliente()
    {
        $dados = $_POST;
        $dados['id_usuario'] = $_SESSION['id_usuario'];
        $dados['data_criacao'] = date('Y-m-d');
        $dados['Senha'] = base64_encode($_POST['Senha']);

        if ($this->Clientes_Model->addClientes($dados)) {
            $this->session->set_flashdata('message', 'Cliente adicionado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar cliente.');
        }
        redirect('clientes'); // redireciona para a lista, não recarrega a view add
    }

    public function addCanais()
    {
        $dados['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
        $dados['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
        $dados['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
        $dados['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
        $dados['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';
        $_ID['id_cliente'] = base64_decode($_GET['code']);
        $_ID['id_usuario'] = $_SESSION['id_usuario'];

        $dados['cliente'] = $this->getCli($_ID);

        $dados['canais'] = $this->getCanal($_ID);

        $dados['tcanais'] = $this->Clientes_Model->tipoCanais();
        //    $this->Clientes_Model->addClientes($dados)->result();
        $this->load->view('pages/header', $dados);
        $this->load->view('addCanais');
        $this->load->view('pages/footer');
    }

    public function getCanal($dados)
    {
        $dados = $this->Clientes_Model->getCanal($dados);

        return $dados;
    }
    public function addCanal($id = null)
    {
        $data = $_POST;
        $data['data_criacao'] = Date('Y-m-d');
        $id['id_cliente'] = $_POST['id_cliente'];

        $qnt = $this->Clientes_Model->countCanais('canais_tb', $data['id_cliente']);
        $qcanal = $this->getCli($id);
        $qcanal = $qcanal[0]->canais;
        if ($qnt[0]->num_canais <= $qcanal) {
            if ($this->Clientes_Model->addCanal($data) == true) {
                $dados['resultado'] = 'sucesso';
                $dados['msg'] = 'Canal adicionado com sucesso!';
                $dados['canais'] = $this->getCanal($data);
                $dados['tr'] = '';
                foreach ($dados['canais'] as $key => $value) {
                    $key++;
                    if ($value->status == 1) {
                        $status = 'Bloquear';
                        $class = 'success';
                    } else {
                        $status = 'Ativar';
                        $class = 'danger';
                    }
                    $dados['tr'] .= '
                    <tr>
                        <td scope="col">' . $key . '</td>
                        <td scope="col">' . $value->nomeCanal . '</td>
                        <td scope="col">' . $value->idFila . '</td>
                        <td scope="col">' . $value->apiKey . '</td>
                        <td scope="col">' . $value->tipo . '</td>
                        <td scope="col">' . implode("/", array_reverse(explode("-", $value->data_criacao))) . '</td>
                        <td scope="col"><a href="' . base_url() . 'clientes/updateCanal?status=' . $value->status . '&id_canal=' . $value->id_canal . '" type="button" class="btn btn-sm btn-block btn-' . $class . ' status" id="canal' . $value->id_canal . '" >' . $status . '</a></td>
                    </tr>';
                }
            } else {

                $dados['msg'] = 'Erro ao adicionar canal!';
                $dados['canais'] = $this->Clientes_Model->getCanal($data['id_cliente']);
                echo json_encode($dados);
            }
        } else {
            echo json_encode('Limite de canais alcançado!');
        }
        return $this->addCanais();
    }

    public function updateCanal()
    {
        if ($_GET['status'] == 1) {
            $_GET['status'] = 0;
        } else {
            $_GET['status'] = 1;
        }
        $dados = $this->Clientes_Model->update('canais_tb', $_GET);

        $data['tr'] = '';
        foreach ($dados as $key => $value) {
            $key++;
            if ($value->status == 1) {
                $status = 'Bloquear';
                $class = 'success';
            } else {
                $status = 'Ativar';
                $class = 'danger';
            }
            $data['tr'] .= '
            <tr>
                <td scope="col">' . $key . '</td>
                <td scope="col">' . $value->nomeCanal . '</td>
                <td scope="col">' . $value->idFila . '</td>
                <td scope="col">' . $value->apiKey . '</td>
                <td scope="col">' . $value->tipo . '</td>
                <td scope="col">' . implode("/", array_reverse(explode("-", $value->data_criacao))) . '</td>
                <td scope="col"><a href="' . base_url() . 'clientes/updateCanal?status=' . $value->status . '&id_canal=' . $value->id_canal . '" type="button" class="btn btn-sm btn-block btn-' . $class . ' status" id="canal' . $value->id_canal . '" >' . $status . '</a></td>
            </tr>';
        }
        echo json_encode($data);
    }

    public function updateCliente()
    {
        if ($_GET['status'] == 1) {
            $_GET['status'] = 0;
        } else {
            $_GET['status'] = 1;
        }
        $dados = $this->Clientes_Model->updateCliente('clientes_tb', $_GET);


        $data['tr'] = '';
        foreach ($dados as $key => $value) {
            $key++;
            if ($value->status == 1) {
                $status = 'Bloquear';
                $class = 'success';
            } else {
                $status = 'Ativar';
                $class = 'danger';
            }
            $data['tr'] .= '
            <tr>
                <td scope="col">' . $key . '</td>
                <td scope="col">' . $value->cliente . '</td>
                <td scope="col">' . $value->cnpj . '</td>
                <td scope="col">' . $value->canais . '</td>
                <td scope="col">' . $value->host . '</td>
                <td scope="col">' . implode("/", array_reverse(explode("-", $value->data_criacao))) . '</td>
                <td scope="col">

                <a href="' . base_url() . 'clientes/addCanais?id_cliente=' . $value->id_cliente . ' class="mr-2 btn btn-primary addCanal">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-node-plus" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M11 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM6.025 7.5a5 5 0 1 1 0 1H4A1.5 1.5 0 0 1 2.5 10h-1A1.5 1.5 0 0 1 0 8.5v-1A1.5 1.5 0 0 1 1.5 6h1A1.5 1.5 0 0 1 4 7.5h2.025zM11 5a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 11 5zM1.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                </svg>
                </button>
                <a href="" class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                    </svg> </a>
                <a href="" class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path
                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                        <path
                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                    </svg></a>

                    </td>
                         <td scope="col"><a href="' . base_url() . 'clientes/updateCliente?status=' . $value->status . '&id_Cliente=' . $value->id_cliente . '" class="btn btn-sm btn-block btn-' . $class . ' status" id="cliente' . $value->id_cliente . '">' . $status . '</a></td>
                    </tr>';
        }
        echo json_encode($data);
    }

    public function getMensagens($dado = null)
    {


        $dados = (is_null($dado)) ? $this->Clientes_Model->getMensagens($dado) : $this->Clientes_Model->getMensagens($dado);


        return $dados;
    }

    public function addMensagens()
    {


        $data['css'][0] = '<link rel="stylesheet" href="' . base_url("assets/") . 'css/bootstrap-select.min.css">';
        $data['script'][0] = '<script src="' . base_url("assets/") . 'js/bootstrap-select.min.js"></script>';
        $data['script'][2] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';

        $idCliente['id_cliente'] = base64_decode($_GET['code']);

        $dados['cliente'] = $this->getCli($idCliente);


        $id['id_cliente'] = $dados['cliente'][0]->id_cliente;

        $dados['msg'] = $this->getMensagens($idCliente);


        $dados['canal'] = $this->Clientes_Model->getCanal($id);

        $this->load->view('pages/header', $dados);
        $this->load->view('mensagens');
        $this->load->view('pages/footer', $data);
    }

    public function addMensagem()
    {

        $data['id_canal'] = $_POST['id_canal'];
        $data['mensagem'] = $_POST['mensagem'];
        $data['data_criacao'] = Date('Y-m-d');
        $data['id_cliente'] = $_POST['id_cliente'];
        if ($this->Clientes_Model->addMensagem($data) == true) {
            $dados['resultado'] = 'sucesso';
            $dados['msg'] = 'Mensagem adcionada com sucesso!';
            $dados['tr'] = '';
            $dados['msgs'] = $this->Clientes_Model->getMensagens($date);

            foreach ($dados['msgs'] as $key => $value) {

                $key++;
                if ($value->status == 1) {
                    $status = 'Bloquear';
                    $class = 'success';
                } else {
                    $status = 'Ativar';
                    $class = 'danger';
                }
                $dados['tr'] .= '
                <tr>
                    <td scope="col">' . $key . '</td>
                    <td scope="col">' . $value->nomeCanal . '</td>
                    <td scope="col">' . $value->idFila . '</td>
                    <td scope="col">' . $value->mensagem . '</td>
                    <td scope="col">' . implode("/", array_reverse(explode("-", $value->data_criacao))) . '</td>
                    <td scope="col"><a href="' . base_url() . 'updateMsg?status=' . $value->status . '&id_canal=' . $value->id_mensagem . '" 
                    type="button" class="btn btn-sm btn-block btn-' . $class . ' status" id="canal' . $value->id_mensagem . '" >' . $status . '</a></td>
                </tr>';
            }
            echo json_encode($dados);
        }
    }

    public function updateMensagem()
    {
        if ($_GET['status'] == 1) {
            $_GET['status'] = 1;
        } else {
            $_GET['status'] = 0;
        }
        $dados = $this->Clientes_Model->update('mensagens_tb', $_GET);

        foreach ($dados as $key => $value) {
            $date['id_cliente'] = $value->id_cliente;
        }
        $data['mensagens'] = $this->getCanal($date);

        $data['tr'] = '';
        foreach ($data['mensagens'] as $key => $value) {
            $key++;
            if ($value->status == 1) {
                $status = 'Bloquear';
                $class = 'success';
            } else {
                $status = 'Ativar';
                $class = 'danger';
            }
            $data['tr'] .= '
            <tr>
                <td scope="col">' . $key . '</td>
                <td scope="col">' . $value->tipo . '</td>
                <td scope="col">' . $value->idFila . '</td>
                <td scope="col">' . $value->mensagem . '</td>
                <td scope="col">' . implode("/", array_reverse(explode("-", $value->data_criacao))) . '</td>
                <td scope="col"><a href="' . base_url() . 'clientes/updateMensagem?status=' . $value->status . '&id_mensagem=' . $value->id_mensagem . '" type="button" class="btn btn-sm btn-block btn-' . $class . ' status" id="canal' . $value->id_canal . '" >' . $status . '</a></td>
            </tr>';
        }
        echo json_encode($data);
    }

    public function listAniversario()
    {


        if ($_GET) {
            $dados = $this->Clientes_Model->getClientes($_GET['id_cliente']);
        } else {

            $dados = $this->Clientes_Model->getClientes();
        }

        foreach ($dados as $key => $value) {
            if ($value->status == 1) {
                $dateAg = '';
                $filaId = '';
                $id_cliente = $value->id_cliente;
                $token = $value->token;
                $url = $value->host;
                $idAssinante = $value->idAssinante;
                $matricula = $value->matricula;


                $this->sendToken($token, $matricula);
                $this->sendIdAssinante($idAssinante);
                // $this->dispAgenda($idAssinante, $dateAg, $url, $id_cliente);
                //   $this->dispLembrete($idAssinante, $dateAg, $url, $id_cliente);
            }
        }

        $idAssinante = $this->_idAssinante;
        $urlCli = 'https://api.clinicorp.com/rest/v1/appointment/list?subscriber_id=';
        $data = [
            0 => [
                'dtInit' => '2023-01-01',
                'dtFim' => '2023-01-31'
            ],
            1 => [
                'dtInit' => '2023-02-01',
                'dtFim' => '2023-02-31'
            ],
            2 => [
                'dtInit' => '2023-03-01',
                'dtFim' => '2023-03-31'
            ],
            2 => [
                'dtInit' => '2023-04-01',
                'dtFim' => '2023-04-31'
            ],
            4 => [
                'dtInit' => '2023-05-01',
                'dtFim' => '2023-05-31'
            ],
            5 => [
                'dtInit' => '2023-06-01',
                'dtFim' => '2023-06-31'
            ],
            6 => [
                'dtInit' => '2023-07-01',
                'dtFim' => '2023-07-31'
            ],
            7 => [
                'dtInit' => '2023-08-01',
                'dtFim' => '2023-08-31'
            ],
            8 => [
                'dtInit' => '2023-09-01',
                'dtFim' => '2023-09-31'
            ],
            9 => [
                'dtInit' => '2023-10-01',
                'dtFim' => '2023-10-31'
            ],
            10 => [
                'dtInit' => '2023-11-01',
                'dtFim' => '2023-11-31'
            ],
            11 => [
                'dtInit' => '2023-12-01',
                'dtFim' => '2023-12-31'
            ]
        ];

        $curl = curl_init();

        foreach ($data as $key => $value) {

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => $urlCli . $idAssinante . '&from=' . $value['dtInit'] . '&to=' . $value['dtFim'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Basic ' . $this->_TOKEN,
                        'accept: application/json; charset=utf-8'
                    ),
                )
            );

            $dados = (json_decode(curl_exec($curl)));
            curl_close($curl);

            foreach ($dados as $key => $dados) {
                // echo $dados->Patient_PersonId;
                $this->getPaciente($dados->Patient_PersonId);
            }
        }
        $this->index();
    }

    public function getPaciente($Patient_PersonId)
    {

        $curl = curl_init();
        $urlCli = 'https://api.clinicorp.com/rest/v1/patient/get?subscriber_id=' . $this->_idAssinante . '&PatientId=' . $Patient_PersonId;
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $urlCli,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $this->_TOKEN,
                    'accept: application/json; charset=utf-8'
                ),
            )
        );
        $result = (json_decode(curl_exec($curl)));

        curl_close($curl);
        $birthday = (substr($result->BirthDate, 5, 5));

        $data['PatientId'] = $result->PatientId;

        if ($this->Clientes_Model->getPatients($data) == false) {
            $data['idAssinante'] = $this->_idAssinante;
            $data['name'] = $result->Name;
            $data['telefone'] = $result->Phone;
            $data['dt_nasc'] = $birthday;


            $resultado = $this->Clientes_Model->addAniversario($data);
        }
    }


    public function getAniversario()
    {
        return $aniv = $this->Clientes_Model->getAniversario(Date('m-d'));
    }


    // NOVO: Exibe formulário para editar cliente
public function editCliente()
{
    // Verifica se o código do cliente foi passado via GET
    if (!isset($_GET['code'])) {
        show_error('Cliente não especificado.');
    }

    $id_cliente = base64_decode($_GET['code']);
    $dados['cliente'] = $this->Clientes_Model->getClientes(['id_cliente' => $id_cliente]);

    if (empty($dados['cliente'])) {
        show_error('Cliente não encontrado.');
    }

    // Carrega assets necessários (opcional, igual ao add)
    $dados['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
    $dados['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
    $dados['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
    $dados['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
    $dados['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';

    $this->load->view('pages/header', $dados);
    $this->load->view('editCliente');   // você precisa criar esta view
    $this->load->view('pages/footer');
}

// NOVO: Processa a atualização dos dados do cliente via POST
public function updateClienteData()
{
    if (!isset($_POST['id_cliente'])) {
        show_error('Requisição inválida.');
    }

    $dados = $_POST;
    // Remove campos que não devem ser atualizados diretamente ou trata senha
    if (isset($dados['Senha']) && !empty($dados['Senha'])) {
        $dados['Senha'] = base64_encode($dados['Senha']);
    } else {
        unset($dados['Senha']); // não altera senha se campo vazio
    }
    // Remove índices que não são da tabela clientes_tb (ex: confirmar_senha)
    unset($dados['confirmar_senha']); // se existir no formulário

    // $dados['data_atualizacao'] = date('Y-m-d H:i:s'); // campo opcional

    $resultado = $this->Clientes_Model->updateClienteData($dados);
    
    // você precisa criar este método no model

    if ($resultado) {
        $this->session->set_flashdata('message', 'Cliente atualizado com sucesso!');
    } else {
        $this->session->set_flashdata('error', 'Erro ao atualizar cliente.');
    }

    redirect('clientes'); // volta para a listagem
}
}
