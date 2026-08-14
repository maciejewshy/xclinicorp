<?php
class ClientesModel extends CI_Model
{

  public function __construct(){
    parent::__construct();
    $srl ="SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))";
    $this->db->query($srl);
  }



    public function getClientes($id = null)
    {

        if (isset($id)) {
            $dados = $this->db->get('clientes_tb', $id)->result();
        } else {
            $dados = $this->db->get('clientes_tb')->result();
        }
        return $dados;
    }

    public function getMensagem($dados = null)
    {
        
        if ($dados) {
            $this->db->select('*');
            $this->db->where('mensagens_tb.id_cliente', $dados);
            $this->db->join('mensagens_tb ', 'mensagens_tb.id_canal = canais_tb.id_canal');
            $this->db->join('tiposcanal_tb ', 'tiposcanal_tb.id_tipoCanal = canais_tb.id_tipoCanal');
            $this->db->from('canais_tb');
        } else {
            $this->db->select('*');
            $this->db->join('canais_tb ', 'mensagens_tb.id_canal = canais_tb.id_canal');
            $this->db->join('tiposcanal_tb ', 'tiposcanal_tb.id_tipoCanal = canais_tb.id_tipoCanal');
            $this->db->from('mensagens_tb');
        }
        return $dados = $this->db->get()->result();
    }

    public function checkShoots($number)
    {
        $this->db->select('*');
        $this->db->from('disparos_tb');
        $this->db->where('numero', $number);
        $this->db->where('data_criacao', Date('Y-m-d'));
        if ($this->db->get()->result()) {
            return true;
        } else {
            return false;
        }
    }

    public function getMensage($dados = null)
    {
        if ($dados) {
            $this->db->select('*');
            $this->db->where('mensagens_tb.id_mensagem', $dados['id_mensagem']);
            $this->db->where('clientes_tb.id_cliente', $dados['id_cliente']);
            $this->db->join('canais_tb ', 'mensagens_tb.id_canal = canais_tb.id_canal');
            $this->db->join('tiposcanal_tb ', 'tiposcanal_tb.id_tipoCanal = canais_tb.id_tipoCanal');
            $this->db->join('clientes_tb', ' = clientes_tb.id_cliente = canais_tb.id_cliente');
            $this->db->from('mensagens_tb');
        }
        return $dados = $this->db->get()->result();
    }

    public function getCampanhas($dados = null)
    {

        $this->db->select('*, Count(disparos_tb.id_campanha )as TotalDisp');
        $this->db->group_by('disparos_tb.id_campanha');
        $this->db->where('id_cliente', $dados);
        $this->db->join('disparos_tb', 'disparos_tb.id_campanha = campanha_tb.id_campanha');
        $this->db->order_by('campanha_tb.id_campanha', 'DESC');
        $this->db->from('campanha_tb');

        return $dados = $this->db->get()->result();

    }

    public function getCanais($id = null)
    {
        if (isset($id['id_cliente'])) {
            $ids['id_cliente'] = $id['id_cliente'];
            $this->db->join('tiposcanal_tb as tipo', 'tipo.id_tipoCanal = canais_tb.id_tipoCanal');
            $this->db->from('canais_tb');
            $this->db->where($ids);
            $this->db->where('canais_tb.status',1);
            $this->db->order_by('canais_tb.id_canal','ASC');
            
          //  $this->db->group_by('canais_tb.idFila');
            $dados = $this->db->get();
        } else {
            $this->db->join('tiposcanal_tb as tipo', 'tipo.id_tipoCanal = canais_tb.id_tipoCanal');
            $dados = $this->db->get('canais_tb');
        }
        
        return $dados->result();
        
    }
    
    public function getCanal($id = null)
    {
        
            $this->db->join('tiposcanal_tb as tipo', 'tipo.id_tipoCanal = canais_tb.id_tipoCanal');
            $this->db->where('id_Canal', $id);
            $dados = $this->db->get('canais_tb');
            return $dados->result();
        
    }


    public function addSetor($data, $idCliente)
    {
        foreach ($data as $key => $value) {
            $dados['id_cliente'] = $idCliente['id_cliente'];
            $dados['id'] = $value->id;
            $dados['setor'] = $value->name;

            if (!$this->db->get_where('setor_tb', array('id' => $value->id))->result()) 
            {
                $this->db->insert('setor_tb', $dados);
            }
        }
        $this->db->where($idCliente);
        return $this->db->get('setor_tb')->result();


    }

    public function upSetor($id, $dados)
    {

        $this->db->where($id);
        if ($this->db->update('setor_tb', $dados) == true) {
            $this->db->where('id_cliente', $id['id_cliente']);
            
            $data = $this->db->get('setor_tb')->result();
        } else {
            $data = false;
        }
        return $data;
    }

    function login($login, $senha)
    {
        $this->db->select('id_cliente, cliente, email, host');
        $this->db->from('clientes_tb');
        $this->db->where('email', $login);
        $this->db->where('senha', base64_encode(($senha)));
        $this->db->where('status', 1);
        $data = $this->db->get();
        if ($data->row()) {
            $this->db->where('email', $login);
            $this->db->set('logged', 1);
            $this->db->update('clientes_tb');
            return $data->result();
        } else {
            return false;
        }
    }
    function logout($id_cliente)
    {
        $this->db->where('id_cliente', $id_cliente);
        $this->db->set('logged', 0);
        $this->db->update('clientes_tb');
        return true;
    }

    function getDisparos($id)
    {
        $this->db->select('*');
        $this->db->where('disparos_tb.id_campanha', $id);
        $this->db->join('campanha_tb', 'disparos_tb.id_campanha = campanha_tb.id_campanha');
        return $this->db->get('disparos_tb')->result();
    }

    function edtMensagem($inputs)
    {
        $this->db->where('id_mensagem', $inputs['id_mensagem']);
        $this->db->set($inputs);
        $this->db->update('mensagens_tb');
        return true;

    }

    public function getSetor($dados)
    {
        $this->db->select('*');
        $this->db->from('setor_tb');
        $this->db->where('setor_tb.id = ' . $dados . ' and setor_tb.status = 1');
        // $this->db->where('setor_tb.statssus', 1);
        if ($this->db->get()->result()) {
            return true;
        } else {
            return false;
        }
    }

   


}
?>
