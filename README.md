# Receba Pagamentos via PicPay
Vamos utilizar a API do [PicPay E-commerce](https://ecommerce.picpay.com/) para receber pagamentos em nosso sistema simples criado com PHP 8. 


## Instalação

Inclua o arquivo `PicPay.php` no código:
```php
<?php

require_once("src/PicPay.php");

use Misterioso013\Payments\PicPay; 
$picpay = New PicPay(x_picpay_token, x_seller_token);
```
Ou utilize o [composer](https://getcomposer.org/) para facilitar a sua vida. Instalação:
```
composer require misterioso013/picpay-php
```
Exemplo de uso:
```php
<?php

require __DIR__. "vendor/autoload.php";

use Misterioso013\Payments\PicPay; 
$picpay = New PicPay(x_picpay_token, x_seller_token);
```
**Atenção:** Lembre-se de substituir `x_picpay_token` e `x_seller_token` pelos seus token encontrados no site do PicPay.
## Solicitar Pagamentos
O cliente decide pagar usando o PicPay e agora é só gerar o pagamento com os dados dele.
Teste o código:
```php
$data = array(
  "referenceId" => 100000000,
  "callbackUrl" => "http://www.sualoja.com.br/callback",
  "returnUrl" => "http://www.sualoja.com.br/cliente/pedido/102030",
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

  print_r($picpay->Payments($data));
```
### Entenda a resposta
Ao solicitar o pagamento você recebe um Array com os seguintes dados:

| Índice/Chave |  Descrição  |
| ------------------- | ------------------- |
|  referenceId |  Irá retornar o ID que você definiu anteriormente  |
|  paymentUrl  |  URL da página onde o cliente faz o pagamento e depois é redirecionado para `returnUrl`  |
|  qrcode  |  Chave de Array com `content` e `base64`  |
|  qrcode->content  |  Conteúdo do QR Code. Poder ser útil para criar o seu próprio QR  |
|  qrcode->base64  |  A imagem do QR code em Base64  |
|  expiresAt  |  Data da expiração do pedido em `ISO 8601` que foi definida anteriormente  |



#### Erros
Os erros irão retornar as seguintes mensagens:
- O Token informado é inválido
- Algumas propriedades não passaram no teste de validação.
    * > [Entenda esse erro](https://github.com/misterioso013/picpay-php#erro-422---valida%C3%A7%C3%A3o-de-dados)
- Problema geral, verifique se a transação foi criada ou cancele a mesma.

Esperar por erros ajuda a não depender apenas do cliente definir digitar os dados corretamente ou da API que você está usando. Aconteça o que acontecer mas seu sistema nunca cairá por causa de algum erro.

##### Erro 422 - Validação de Dados

Resposta à possíveis erros:

```
Array
(
    [0] => stdClass Object
        (
            [message] => O campo reference id é obrigatório.
            [field] => referenceId
        )

    [1] => stdClass Object
        (
            [message] => O campo value é obrigatório.
            [field] => value
        )

    [2] => stdClass Object
        (
            [message] => O campo callback url é obrigatório.
            [field] => callbackUrl
        )

    [3] => stdClass Object
        (
            [message] => O campo buyer é obrigatório.
            [field] => buyer
        )

    [4] => stdClass Object
        (
            [message] => O campo buyer.document é obrigatório.
            [field] => buyer.document
        )

)
```
Exemplo de como verificar esses erros:

```
$data = array(
  "referenceId" => "",
  "callbackUrl" => "",
  "returnUrl" => "",
  "value" => "",
  "expiresAt" => "",
  "channel" => "my-channel",
  "purchaseMode" => "in-store",
  "buyer" =>  [
    "firstName"=> "",
    "lastName"=> "",
    "document"=> "",
    "email"=> "",
    "phone" => ""
  ]);
$payment = $picpay->Payments($data);

  if(isset($payment->error)) {

    if(isset($payment->error->errors)){
      
      foreach($payment->error->errors as $error) {

        $message = $error->message;
        $field = $error->field;
  
        echo "Atenção! {$message} | Campo: {$field} <br>";
      }
    }
  }

```

## Status
Consulte o status do seu pedido de forma simples. Use nosso exemplo para melhor compreensão
> Use o `referenceId` como indetificador do pedido

Exemplo:
```php
$request = $picpay->Status($referenceId);
print_r($request);

```
Retorno em caso de sucesso:

```php
stdClass Object
(
    [referenceId] => 960361262
    [status] => paid
    [createdAt] => 2021-09-09T07:29:37.000000Z
    [updatedAt] => 2021-09-09T09:00:17.000000Z
    [value] => 0.5
    [authorizationId] => 0000b800cf788600237f30f3
)

```
Entenda o Array acima:

| Índice/Chave    | Descrição                                                    |
|-----------------|--------------------------------------------------------------|
| referenceId     | Irá retornar o ID que você definiu anteriormente             |
| status          | Status atual do pedido                                       |
| createdAt       | Data da criação do pedido em `ISO 8601`                      |
| updatedAt       | Data da última atualização de status do pedido em `ISO 8601` |
| value           | Preço do pedido                                              |
| authorizationId | Número da autorização de pagamento **(caso esteja pago)**    |

Possíveis status:

| Status     | Descrição                                 |
|------------|-------------------------------------------|
| created    | registro criado                           |
| expired    | prazo para pagamento expirado             |
| analysis   | pago e em processo de análise anti-fraude |
| paid       | pago                                      |
| completed  | pago e saldo disponível                   |
| refunded   | pago e devolvido                          |
| chargeback | pago e com chargeback                     |

## Cancelamento
Cancele pedidos que ainda não foram pagos ou devolva o dinheiro de pagamentos já concluídos.
> Use o `referenceId` como indetificador do pedido
> Use o `authorizationId` para cancelar pedidos que já estão pagos

Exemplo:
```php
# o authorizationId só é obrigatório para pedidos que já foram pagos
$request = $picpay->Cancellations($referenceId, $authorizationId);
print_r($request);

```
Retorno em caso de sucesso:

```php
stdClass Object
(
    [referenceId] => 960361262
    [cancellationId] => 0000b800cf788600237f30f3
)

```
Entenda o Array acima:

| Índice/Chave   | Descrição                                          |
|----------------|----------------------------------------------------|
| referenceId    | Irá retornar o ID que você definiu anteriormente   |
| cancellationId | Número de verificação do cancelamento do pagamento |

Não pude testar tudo que é possível acontecer com o uso da API, e a sua última documentação se encontra fora de funcionamento no momento desse commit.

Sinta-se a vontade para fazer um fork e continuar esse projeto, tenho certeza que isso ajudará muita gente.