<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api
{
    private $_URL;
    public function __construct()
    {
        $this->ci = &get_instance();
        if (empty($_SESSION)) {
            $this->_URL = $_SESSION['host'];
        }
    }
    public function getUrl()
    {
        return $this->_URL = $_SESSION['host'];
    }

    //FUNÇÕES DE CONTROLE DE FILAS 
    //Habilita a Fila no servidor preparando para conectar
    public function enableQueue($key, $url)
    {
        $url = $this->getUrl() . '' . $url;
        $keys = $key;
        return $this->execQueue($keys, $url);
    }
    public function disableQueue($key, $url)
    {
        $url = $this->getUrl() . '' . $url;
        $keys = $key;
        return $this->execQueue($keys, $url);
    }

    //Conecta a Fila do servidor
    public function connectQueue($key, $url)
    {
        $url = $this->getUrl() . '' . $url;
        $keys = $key;
        return $this->execQueue($keys, $url);
    }

    //Busca Fila
    public function getQueue($key, $url)
    {
        $url = $this->getUrl() . '' . $url;
        $keys = $key;
        return $this->execQueue($keys, $url);
    }

    //Puxar QrCode em svg
    public function getQueueQrCode($key, $url)
    {
        $url = $this->getUrl() . '' . $url;
        $keys = $key;
        return $this->execQueueQrCode($keys, $url);
    }

    //Verificar Status da Fila
    public function getQueueStatus($key, $url)
    {
        $url = $this->getUrl(). $url;
        $keys = $key;
        $return = $this->execQueue($keys, $url);
        return($return);
    }
//FIM DE FUNÇÕES DE CONTROLE DE FILAS

//EXECUTAR AÇÃO
    //Executa consulta
    public function execQueue($key, $url)
    {
        $curl = curl_init();
        $data_string = json_encode($key);

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
                CURLOPT_POSTFIELDS => $data_string

            )
        );
        $dados = curl_exec($curl);
        curl_close($curl);
        return (json_decode($dados));
    }


    public function execQueueQrCode($key, $url)
    {
        $curl = curl_init();
        $data_string = json_encode($key);
        // var_dump($data_string); var_dump($url); die;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: image/svg+xml'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return ($response);
    }
}



?>