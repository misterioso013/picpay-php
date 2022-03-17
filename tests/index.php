<?php
require '../vendor/autoload.php';
use Misterioso013\Payments\PicPay;


// Chamando a classe
$picpay = New PicPay('x_picpay_token_aqui', 'x_seller_token_aqui');


$id = rand(100000000,999999999);
$data = array(
  "referenceId" => $id,
  "callbackUrl" => "http://localhost/callback.php",
  "returnUrl" => "http://localhost:8080/tests/pedido-test.php?id=$id",
  "value" => 0.50,
  "expiresAt" => "2025-05-01T16:00:00-03:00",
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
    echo '<br><a href="'.$paymentUrl.'" target="_blank" style="padding:10px;background:green;color:white;text-decoration:none;">PAGAR AGORA</a><br><br><br><br><a href="cancellations.php?id='.$id.'" target="_blank">Cancelar a compra</a></p>';
  }