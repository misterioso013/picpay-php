<?php
require '../vendor/autoload.php';
use Misterioso013\Payments\PicPay;


$picpay = New PicPay('x_picpay_token_aqui', 'x_seller_token_aqui');


$id = $_GET['id'];
$auth = $_GET['auth'];

if(empty($auth)){
    $request = $picpay->Cancellations($id);
}else{
    $request = $picpay->Cancellations($id, $auth);
}

print_r($request);