<?php

require_once("simple_picpay.php");

// Chamando a classe
$picpay = New PicPay();

/* CREDENCIAIS DE AUTENTICACAO
Consiga-as em:
https://painel-empresas.picpay.com/integracoes
 */
$picpay->x_picpay_token = "cole_aqui";
$picpay->x_seller_token = "cole_aqui";

$id = rand(100000000,999999999);
$data = array(
  "referenceId" => $id,
  "callbackUrl" => "http://www.sualoja.com.br/callback",
  "returnUrl" => "http://localhost/picpay-php/pedido.php?id=$id",
  "value" => 0.50,
  "expiresAt" => "2022-05-01T16:00:00-03:00",
  "channel" => "my-channel",
  "purchaseMode" => "in-store",
  "buyer" =>  [
    "firstName"=> "João",
    "lastName"=> "Da Silva",
    "document"=> "123.456.789-10",
    "email"=> "teste@picpay.com",
    "phone" => "+55 27 12345-6789"
  ]);
  
  $payment = $picpay->Payments($data);

  if(isset($payment->error)) {

    // Tratar erros
    echo $payment->error->message.'<br>';

    if(isset($payment->error->errors)){ // Erros 422
      
      foreach($payment->error->errors as $error) {

        $message = $error->message;
        $field = $error->field;
  
        echo "Atenção! {$message} | Campo: {$field} <br>";
      }
    }
 


  }else{
    // Tratar dados para realizar o pagamento

    $referenceID = $payment->referenceId;
    $paymentUrl = $payment->paymentUrl;
    $contentQR = $payment->qrcode->content;
    $imageQR = $payment->qrcode->base64;
    $expiresAt = $payment->expiresAt;


    echo '<h1>Finalizar Pagamento</h1>';
    echo '<p style="text-aligin:center;">Faça o pagamento até '.date("d/m/Y", strtotime($expiresAt)).'| ID do pedido: '.$referenceID;

    echo '<br><img src="'.$imageQR.'" width="200px"/>';
    echo '<br><a href="'.$paymentUrl.'" target="_blank" style="padding:10px;background:green;color:white;text-decoration:none;">PAGAR AGORA</a></p>';
  }