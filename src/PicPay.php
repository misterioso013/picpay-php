<?php
namespace Misterioso013\Payments;
class PicPay {

   const API_URL_DEFAULT = "https://appws.picpay.com/ecommerce/public/";
    public string $x_picpay_token;
    public string $x_seller_token;

    public function __construct(string $x_picpay_token, string $x_seller_token)
    {
        $this->x_picpay_token = $x_picpay_token;
        $this->x_seller_token = $x_seller_token;
    }

    private function RequestAPI(string $url, string $method, $data = null): string
    {

      

            $headers = array(
                "x-picpay-token: " . $this->x_picpay_token,
                "x-seller-token: " . $this->x_seller_token,
                "Content-Type: application/json",
            );
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
        if($method == "POST") {
              curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $resp = curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($http_code != 200) {
                return '{"error":' . $resp . '}';

            } else {

                return $resp;
            }
    }

    public function Payments($data) {

        $url = self::API_URL_DEFAULT."payments";
       $request = $this->RequestAPI($url,  "POST", json_encode($data));
        return json_decode($request);

    }

    public function Status($id) {

        $url = self::API_URL_DEFAULT."payments/{$id}/status";
       $request = $this->RequestAPI($url, "GET");
        return json_decode($request);

    }

    public function Cancellations($referenceId, $authorizationId = null) {
        if($authorizationId) {
            $data = '{ "authorizationId": "'. $authorizationId .'" }';
        }
            $url = self::API_URL_DEFAULT."payments/{$referenceId}/cancellations";
            $request = $this->RequestAPI($url,  "POST", $data?? null);
            return json_decode($request);
    }
}