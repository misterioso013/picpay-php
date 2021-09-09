<?php

class PicPay {
    private $default_url = "https://appws.picpay.com/ecommerce/public/";
    public $x_picpay_token;
    public $x_seller_token;
    public $callbackUrl;
    public $returnUrl;

    public function RequestAPI($url, $method,$data = null) {
        /// Faremos a requisição e retornaremos os dados
      
        if($method == "POST") {
            $headers = array(
                "x-picpay-token: ".$this->x_picpay_token,
                "x-seller-token: ".$this->x_seller_token,
                "Content-Type: application/json",
             );
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
 
 
 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
 
 //for debug only!
 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
 $resp = curl_exec($curl);
 $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 curl_close($curl);

 if($http_code != 200) {
     $erro = '{"error":'.$resp.'}';
     return $erro;

 }else{

    return $resp;
}

        }else{
          // Requisições GET

          $headers = array(
            "x-picpay-token: ".$this->x_picpay_token,
            "x-seller-token: ".$this->x_seller_token,
         );
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if($http_code != 200) {
            $erro = '{"error":'.$resp.'}';
            return $erro;
       
        }else{
       
           return $resp;
       }

        }
                

    }
    function Payments($data) {

        $url = $this->default_url."payments";
       $request = $this->RequestAPI($url,  "POST", json_encode($data));
       
       $result = json_decode($request);

    return $result;

    }

    function Status($id) {

        $url = $this->default_url."payments/{$id}/status";
       $request = $this->RequestAPI($url, "GET");

       $result = json_decode($request);

    return $result;

    }


}