<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CI_Controller
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
    function index()
    {

        $res['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
        $res['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
        $res['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
      //  $res['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
        $res['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';
        $return = '';


        if ($this->session->flashdata("message")) {
            $return = 'toastr.success(' . $this->session->flashdata("message") . ')';
        } else
            if ($this->session->flashdata("error")) {
                $return = 'toastr.error(' . $this->session->flashdata("error") . ')';
            } else
                if ($this->session->flashdata("logout")) {
                    $return = 'toastr.error(' . $this->session->flashdata("error") . ')';
                }


        $res['script'][5] = '<script> ' . $return . ' </script>';



        if (isset($_SESSION['logged']) == false) {
            $this->load->view('login', $res);
        } else {
            if ($ret = $this->ClientesModel->getCampanhas($_SESSION['id_cliente'])) 
            {
                $res['campanha'] = $ret;
            }


         //   $this->_HOST =  $_SESSION['host'];

          $res['con'] = $this->getConnection();

         

            $this->load->view('pages/header', $res);
            $this->load->view('index');
            $this->load->view('pages/footer');
        }
    }

    function getConnection() {
        $data = $this->ClientesModel->getCanais(array('id_cliente' => $_SESSION['id_cliente']));
        $res['conection'] = 'CONECTADO';
        $res['BG'] = 'success';
        $res['msg'] = 'Todas as conexões estão ok.\n Ao ver este botão em vermelho refazer a conexão!';
        foreach ($data as $key => $dt) {

            if ($dt->status == 1) {
                $keys = array('apiKey' => $dt->apiKey, 'queueId' => $dt->idFila);

                $result = $this->getQueueStatus($keys);
                if ($result === FALSE) {
                    $res['conection'] = 'DESCONECTADO';
                    $res['BG'] = 'danger x-small';
                    $res['msg'] = 'Alguma de suas filas estão desconectados! <p></strong> Click aqui para refazer a conexão!</strong></p>';
                }

            }
        }
        return $res;
    }
    
    function login()
    {
        $login = $this->ClientesModel->login($_POST['email'], $_POST['senha']);
    
        if ($login) {
            foreach ($login as $item) {
                $this->session->set_userdata('id_cliente', $item->id_cliente);
                $this->session->set_userdata('email', $item->email);
                $this->session->set_userdata('cliente', $item->cliente);
                $this->session->set_userdata('host', $item->host);
                $this->session->set_userdata('logged', TRUE);
                $this->session->set_flashdata('message', "Acesso realizado com sucesso!");
            }

        } else {
            // $this->funcionarios_model->atualizaHistorico("Tentativa de logar no sistema.",$user);
            $this->session->set_flashdata('error', "Erro de acesso. Verifique seus dados!");
        }
        redirect($this->index());
    }

    function disparos($dados)
    {

    }

    function getAgenda($idAssinante, $dateAg)
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

    function logout()
    {
        $id_cliente = ($_SESSION['id_cliente']);
        if ($this->ClientesModel->logout($id_cliente) == true) {
            $this->session->unset_userdata('id_cliente', $item->id_cliente);
            $this->session->unset_userdata('email', $item->email);
            $this->session->unset_userdata('cliente', $item->cliente);
            $this->session->unset_userdata('logged', TRUE);
            $this->session->set_flashdata('logout', "Deslogado com sucesso");
        }

        redirect($this->index());
    }


    function getListDisparos()
    {
        $dados['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
        $dados['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
        $dados['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
        $dados['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
        $dados['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';
        $id_campanha = $_GET['id_campanha'];

        $dados['disparos'] = $this->ClientesModel->getDisparos($id_campanha);
        $dados['con'] = $this->getConnection();

        $this->load->view('pages/header', $dados);
        $this->load->view('listDisparos');
        $this->load->view('pages/footer');

    }



    public function connectQueue($key)
    {

    }

    public function getQueue($key)
    {
    }
    public function enableQueue($key)
    {
    }

    public function getQueueQrCode($key)
    {
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