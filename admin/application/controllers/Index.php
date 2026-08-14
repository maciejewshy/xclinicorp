<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Clientes_Model');
    }

    public function index()
    {
       
    }

    public function clientes()
    {
        $this->load->view('pages/header');
        $this->load->view('clientes');
        $this->load->view('pages/footer');
    }

    function adduser()
    {
        if ($_GET) {
            $dados = $this->Clientes_Model->getUserExist($_GET);
            if ($dados) {
                echo true;
            } else {
                echo json_encode($dados);
            }
        }

        if ($_POST) {
            $dados = [
                'nome' => $_POST['nome'],
                'usuario' => $_POST['usuario'],
                'senha' => base64_encode($_POST['pws'])
            ];


            $dates = $this->Clientes_Model->addUser($dados);

            if ($dates) {
                echo json_encode('Sucesso');
            } else {
                echo json_encode('Falha');
            }
        }
    }

    function usuarios()
    {
        if ($_SESSION['id_usuario'] != 1) {
            $this->load->view('pages/error_404');
        } else {


            $dados['css'][0] = '<link href="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
            $dados['script'][0] = '<script src="' . base_url("assets/") . 'vendor/datatables/jquery.dataTables.min.js"></script>';
            $dados['script'][1] = '<script src="' . base_url("assets/") . 'vendor/datatables/dataTables.bootstrap4.min.js"></script>';
            $dados['script'][2] = '<script src="' . base_url("assets/") . 'js/demo/datatables-demo.js"></script>';
            $dados['script'][3] = '<script src="' . base_url("assets/") . 'js/clientes.js"></script>';

            $dados['usuarios'] = $this->Clientes_Model->getUserExist(null);

            $this->load->view('pages/header', $dados);
            $this->load->view('usuarios');
            $this->load->view('pages/footer');
        }
    }

    function formUser()
    {
        $this->load->view('addUser');
    }

    function ressetPws()
    {
        if (!$_POST) {
            $this->load->view('alterarsenha');
        } else {

            $dados = [
                'id_usuario' => $_POST['id_usuario'],
                'senha' => base64_encode($_POST['pws'])
            ];

            $dates = $this->Clientes_Model->updateUser($dados);

            if ($dates) {
                echo json_encode('Sucesso');
            } else {
                echo json_encode('Falha');
            }
        }
    }
}
