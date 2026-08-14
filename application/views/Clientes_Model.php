<?php

class Clientes_Model extends CI_Model
{
  public function getClientes($id = null)
  {
    $Id = $id['id_usuario'];
    if (isset($id)) {
      if ($Id != 1) {
        $this->db->where('usuarios_tb.id_usuario', $Id);
        $this->db->join('usuarios_tb', 'usuarios_tb.id_usuario = clientes_tb.id_usuario', 'left');
        $dados = $this->db->get('clientes_tb', $id)->result();
      } else {
        $dados = $this->db->get('clientes_tb')->result();
      }
    } else {
      $dados = $this->db->get('clientes_tb')->result();
    }
    return $dados;
  }

  public function filterClientes($id = null)
  {
    if (isset($id)) {
      $this->db->where($id);
      $dados = $this->db->get('clientes_tb', $id)->result();
    } else {
      $dados = $this->db->get('clientes_tb')->result();
    }
    echo json_encode($dados);
  }

  public function addClientes($dados)
  {
    $dados['data_criacao'] = Date('Y-m-d');
    return $this->db->insert('clientes_tb', $dados);
  }

  public function addUser($dados)
  {
    $dados['data_criacao'] = Date('Y-m-d');
    return $this->db->insert('usuarios_tb', $dados);
  }

  public function addCanal($dados)
  {
    $dados['data_criacao'] = Date('Y-m-d');
    return $this->db->insert('canais_tb', $dados);
  }
  public function countCanais($table, $id)
  {
    $sql = "SELECT count(`id_cliente`) as num_canais FROM `canais_tb` WHERE id_cliente=" . $id;
    $dados = $this->db->query($sql);
    return $dados->result();
  }

  public function update($table, $dados)
  {
    // $this->db->set($dados);
    if (isset($dados['id_canal'])) {
      $this->db->where('id_canal', $dados['id_canal']);
    }

    if (isset($dados['id_mensagem'])) {
      $this->db->where('id_mensagem', $dados['id_mensagem']);
    }

    $dates = $this->db->update($table, $dados);

    if (isset($dados['id_canal'])) {
      if ($dates == 1) {
        $sql = "SELECT * from canais_tb LEFT join tiposcanal_tb as tc on tc.id_tipoCanal = canais_tb.id_tipoCanal where id_cliente = (SELECT id_cliente FROM `canais_tb` WHERE id_canal=" . $dados['id_canal'] . ")";
        $date = $this->db->query($sql);
      }
    }

    return $date->result();
  }

  public function updateCliente($table, $dados)
  {
    // $this->db->set($dados);
    $this->db->where('id_cliente', $dados['id_Cliente']);
    $dates = $this->db->update($table, $dados);
    if ($dates == 1) {
      $sql = "SELECT * FROM `clientes_tb`";
      $date = $this->db->query($sql);
    }
    return $date->result();
  }

  function tipoCanais()
  {
    return $this->db->get('tiposcanal_tb')->result();
  }
  public function getCanal($id = null)
  {
    if (isset($id)) {
      $ids['id_cliente'] = $id['id_cliente'];
      $this->db->join('tiposcanal_tb as tipo', 'tipo.id_tipoCanal = canais_tb.id_tipoCanal');
      $this->db->from('canais_tb');
      $this->db->where($ids);
      $dados = $this->db->get()->result();
    } else {
      $dados = $this->db->get('canais_tb');
    }
    return $dados;
  }

  public function addMensagem($id)
  {
    return $this->db->insert('mensagens_tb', $id);
  }
  public function getMensagens($id)
  {
    $ids = $id['id_cliente'];
    if (isset($id)) {
      $this->db->join('canais_tb ', 'mensagens_tb.id_canal = canais_tb.id_canal');
      $this->db->from('mensagens_tb');
      $this->db->where('canais_tb.id_cliente', $ids);
      $dados = $this->db->get()->result();
    }
    return $dados;
  }

  public function getMensagem($dados)
  {
    $this->db->select('*');
    $this->db->where('tiposcanal_tb.tipo', $dados);
    $this->db->join('canais_tb ', 'mensagens_tb.id_canal = canais_tb.id_canal');
    $this->db->join('tiposcanal_tb ', 'tiposcanal_tb.id_tipoCanal = canais_tb.id_tipoCanal');
    $this->db->from('mensagens_tb');
    return $dados = $this->db->get()->result();
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

  public function addcampaign($camp)
  {
    $this->db->insert('campanha_tb', $camp);
    return $this->db->insert_id();
  }

  public function addDisparos($erro, $numero, $mss, $idCamp, $idFila)
  {

    $dados['mensagem'] = $mss;
    $dados['id_campanha'] = $idCamp;
    $dados['numero'] = $numero;
    $dados['fila'] = $idFila;
    $dados['error'] = $erro;
    $dados['data_criacao'] = Date('Y-m-d');
    return $this->db->insert('disparos_tb', $dados);
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

  public function addAniversario($data)
  {

    return $this->db->insert('aniversario_tb', $data);
  }

  public function getPatients($data)
  {
    if ($this->db->get_where('aniversario_tb', $data)->result()) {
      return true;
    } else {
      return false;
    }
  }


  public function getAniversario($data)
  {
    return $this->db->get_where('aniversario_tb', 'dt_nasc = "' . $data . '"')->result();
  }

  function login($login, $senha)
  {
    $this->db->select('*');
    $this->db->from('usuarios_tb');
    $this->db->where('usuario', $login);
    $this->db->where('senha', base64_encode(($senha)));
    $this->db->where('status', 1);
    $data = $this->db->get();
    if ($data->row()) {
      $this->db->where('id_usuario', $login);
      $this->db->set('logged', 1);
      $this->db->update('usuarios_tb');
      return $data->result();
    } else {
      return false;
    }
  }
  function logout($id_usuario)
  {
    $this->db->where('id_usuario', $id_usuario);
    $this->db->set('logged', 0);
    $this->db->update('usuarios_tb');
    return true;
  }

  function getUserExist($dates)
  {
    if (isset($dates)) {
      $return = $this->db->get_where('usuarios_tb', $dates)->result();
    } else {
      $return = $this->db->get_where('usuarios_tb')->result();
    }
    return $return;
  }

  function updateUser($dates){
    
    
      $id['id_usuario'] = $dates['id_usuario'];
      $this->db->where( $id);
      return $this->db->update('usuarios_tb', $dates);
      
  }
}
