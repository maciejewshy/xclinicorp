<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api
{
    public function __construct()
    {
        $this->ci = &get_instance();
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

    public function getQueueStatus($key, $url)
    {

        $keys = $key;
        return $this->execQueue($keys, $url);

    }
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
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
                CURLOPT_POSTFIELDS => $data_string

            )
        );
        curl_close($curl);
          return (json_decode(curl_exec($curl)));
    }

}



?>