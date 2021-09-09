<?php
require_once("../simple_picpay.php");

// Chamando a classe
$picpay = New PicPay();

$picpay->x_picpay_token = "cole_aqui";
$picpay->x_seller_token = "cole_aqui";

$id = filter_input(INPUT_GET,'id');

$request = $picpay->Status($id);

// Tratando dos dados
if(isset($request->error)) {
    // Tratando de erros 

    // echo "Erro código ". $request->error->code;
    echo 'Ops! '.$request->error->message;
    exit;

}else{
    // Status do pedido
    $referenceId = $request->referenceId;
    $status = $request->status;
    $createdAt = $request->createdAt;
    $updatedAt = $request->updatedAt;
    $value = $request->value;
    $authorizationId = $request->authorizationId;

    // Traduzir Status

    switch ($status) {
        case 'created':
            $status = "Pendente";
            break;
        case 'expired':
            $status = "Expirado";
            break;
        case 'analysis':
            $status = "Em analise";
            break;
        case 'paid':
            $status = "Pago";
            break;
        case 'completed':
            $status = "Pago";
            break;
        case 'refunded':
            $status = "Devolvido";
            break;
        case 'chargeback':
            $status = "Devolvido";
            break;
        
    }

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido <?php echo $referenceId; ?></title>
    <style>
        h1,p {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Detalhes do seu pedido</h1>
    <p>O seu pedido do N° (<?php echo $referenceId; ?>) no valor de R$<?php echo number_format($value,2,',','.'); ?> está <?php echo $status; ?><br><a href="">Atualizar</a> <br> <a href="test.php" target="_blank">Comprar novamente</a></p>
</body>
</html>