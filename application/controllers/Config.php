<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config extends CI_Controller
{
    private $_TOKEN;
    private $_AUTHENTICATED;
    private $_CONNECTED;
    private $_ENABLED;
    private $_HOST;

    function __construct()
    {
        parent::__construct();
        $this->load->model('ClientesModel');
        $this->load->library('api');
    }

    public function index()
    {

        $res['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
        $res['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
        $res['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
        $res['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
        $res['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';

        if (isset($_SESSION['logged']) == false) {

            $this->load->view('login', $res);
        } else {

            $res['canais'] = $this->getCanais($_SESSION);

            $res['mensagem'] = $this->getMensagens($_SESSION['id_cliente']);

            $res['setor'] = $this->getSetor($_SESSION['id_cliente']);


            $this->_HOST = $_SESSION['host'];

            $res['con'] = $this->getConnection();

            $this->load->view('pages/header', $res);
            $this->load->view('config');
            $this->load->view('pages/footer');
        }
    }


    function getConnection()
    {

        $data = $this->ClientesModel->getCanais(array('id_cliente' => $_SESSION['id_cliente']));
        $res['conection'] = 'CONECTADO';
        $res['BG'] = 'success';
        $res['msg'] = 'Todas as conexões estão ok.\n Ao ver este botão em vermelho refazer a conexão!';


        foreach ($data as $key => $dt) {

            if ($dt->status == 1) {
                $keys = array('apiKey' => $dt->apiKey, 'queueId' => $dt->idFila);

                $result = $this->getQueueStatus($keys);

                if ($result === false) {
                    $res['conect'][$dt->idFila] = false;
                    $res['conection'] = 'DESCONECTADO';
                    $res['BG'] = 'danger x-small';
                    $res['msg'] = 'Alguma de suas filas estão desconectados! <p></strong> Click aqui para refazer a conexão!</strong></p>';
                } else {
                    $res['conect'][$dt->idFila] = true;
                }
            }
        }
        //  print_r($res['msg']);exit();
        return $res;
    }

    public function getDataCli($id = null)
    {

        $dados = $this->ClientesModel->getClientes($id);

        foreach ($dados as $key => $value) {
            $filaId = '';
            $token = $value->token;
            $url = $value->host;
            $idAssinante = $value->idAssinante;
            $matricula = $value->matricula;
            $dateAg = date('Y-m-d', strtotime('+1 day'));
            $this->sendToken($token, $matricula);
        }
    }

    function sendToken($token, $matricula)
    {

        $this->_TOKEN = base64_encode($matricula . ':' . $token);
    }
    public function getAgenda($idAssinante, $dateAg)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.clinicorp.com/rest/v1/appointment/list?subscriber_id=' . $idAssinante . '&from=' . $dateAg . '&to=' . $dateAg,
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
                    //bWFyaWxpYTo4MDk2YmYzNi1kOGYzLTQwZjQtODgxMS03NmZhNWFhMzczM2I='
                ),
            )
        );

        $dados = curl_exec($curl);
        curl_close($curl);


        return json_decode($dados);
    }

    public function getCanais($id)
    {

        $data = $this->ClientesModel->getCanais($id);
        return $data;
    }
    public function getCanal($id)
    {
        $data = $this->ClientesModel->getCanal($id);
        return $data;
    }

    public function getMensagens($idCliente)
    {
        $id = $idCliente;
        return $data = $this->ClientesModel->getMensagem($id);
    }


    public function getSetor($id)
    {

        $this->getDataCli($id);
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.clinicorp.com/rest/v1/professional/list_all_professionals/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 100,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $this->_TOKEN,
                    'accept: application/json; charset=utf-8'
                    //bWFyaWxpYTo4MDk2YmYzNi1kOGYzLTQwZjQtODgxMS03NmZhNWFhMzczM2I='
                ),
            )
        );

        $dados = curl_exec($curl);
        curl_close($curl);


        $data = json_decode($dados);

        $idCliente['id_cliente'] = $id;


        return $this->ClientesModel->addSetor($data, $idCliente);
    }

    public function upSetor()
    {
        $data['id'] = $_POST['id'];
        $data['id_cliente'] = $_SESSION['id_cliente'];
        $date['status'] = $_POST['status'];
        $return = $this->ClientesModel->upSetor($data, $date);

        echo json_encode($return);
    }

    function edt_message()
    {
        $retun['con'] = $this->getConnection();
        $dados['id_mensagem'] = $_GET['id_mensagem'];
        $dados['id_cliente'] = $_SESSION['id_cliente'];
        $retun['msg'] = $this->ClientesModel->getMensage($dados);

        $this->load->view('pages/header', $retun);
        $this->load->view('mensagens');
        $this->load->view('pages/footer');
    }

    function edtMensagem()
    {
        $dados = $_POST;
        if ($this->ClientesModel->edtMensagem($dados) == true) {
            $this->index();
        }
    }

    function gerarQrcode()
    {
        $url = 'getQueueQrCode';
        $key = $_GET;
        $dados = $this->getCanal($key['id_Canal']);


        $keys['apiKey'] = $dados[0]->apiKey;
        $keys['queueId'] = $dados[0]->idFila;

        //Desabilitar servidor
        $this->api->disableQueue($keys, 'disableQueue');

        sleep(1);
        //Habilitar servidor
        $this->api->enableQueue($keys, 'enableQueue');

        sleep(1);
        //Desabilitar servidor
        $this->api->connectQueue($keys, 'connectQueue');
        sleep(2);
        $return = $this->api->getQueueQrCode($keys, $url);

        echo json_encode($return);
    }

    public function getQueueStatus($key)
    {
        $url = 'getQueueStatus';
        $keys = $key;
        $return = $this->api->getQueueStatus($keys, $url);
        $dados = $return;

        return $dados->authenticated;
    }
}
