<?php

if($_SERVER['HTTP_X_SELLER_TOKEN'] != "x_seller_token_aqui") {
    header('HTTP/1.0 401 Unauthorized');
    die('x_seller_token recebido é inválido');
}

// Recebe qualquer alteração de status em suas ordens
$json = file_get_contents("php://input");
file_put_contents('test.txt', $json);

// Aqui você derá chamar a função Status e passar o ID recebido para verificar qual foi a mudança ocorrida.