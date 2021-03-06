<?php
require '../vendor/autoload.php';
use Misterioso013\Payments\PicPay;

$picpay = New PicPay('x_picpay_token_aqui', 'x_seller_token_aqui');


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
        case 'completed':
        case 'paid':
            $status = "Pago";
            break;
        case 'chargeback':
        case 'refunded':
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
    <p>O seu pedido do N° (<?php echo $referenceId; ?>) no valor de R$<?php echo number_format($value,2,',','.'); ?> está <?php echo $status; ?><br><a href="">Atualizar</a> <br> <a href="index.php" target="_blank">Comprar novamente</a><br><a href="cancellations.php?id=<?=$referenceId?>&auth=<?=$authorizationId?>" target="_blank">Cancelar a compra</a></p>
</body>
</html>