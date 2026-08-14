<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config extends CI_Controller 
{
    private $_TOKEN;
    public function __construct(){
        parent :: __construct();
        $this->load->model("ClientesModel");
    }

    public function index(){
        
        $res['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
        $res['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
        $res['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
        $res['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
        $res['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';

        $res['canais'] = $this->getCanais(); 
        $res['mensagem'] = $this->getMensagens(); 
        $res['setor'] = $this->getSetor(); 

        $this->load->view('pages/header', $res);
        $this->load->view('config');
        $this->load->view('pages/footer');
    }
    
    public function getDataCli(){
        
        $dados = $this->ClientesModel->getClientes();

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

        curl_close($curl);

        return json_decode(curl_exec($curl));
    }

    public function getCanais(){
        $data = $this->ClientesModel->getCanais();
       return $data;
    }
    
    public function getMensagens(){
        return $data = $this->ClientesModel->getMensagem();
    }

    public function getSetor(){
        
        $this->getDataCli();
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

        curl_close($curl);

        $data =  json_decode(curl_exec($curl));
        return $this->ClientesModel->addSetor($data);
    }

    public function upSetor(){
        $data['id'] = $_POST['id'];
        $date['status'] = $_POST['status'];
        $return = $this->ClientesModel->upSetor($data, $date);

        echo json_encode($return);
    }

    function edt_message(){
        $dados['id_mensagem'] = $_GET['id_mensagem'];
        $dados['id_cliente'] = $_SESSION['id_cliente'];
        $retun['msg'] = $this->ClientesModel->getMensage($dados);

        $this->load->view('pages/header', $retun);
        $this->load->view('mensagens');
        $this->load->view('pages/footer');
    }

    function edtMensagem(){
        $dados= $_POST;
       if($this->ClientesModel->edtMensagem($dados) == true){
        $this->index();
       }
    }

}

?>