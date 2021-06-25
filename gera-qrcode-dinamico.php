<?php

use App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/main.php';

$requests = [
	'calendario' => [
		'expiracao'	=> 3600
	],
	'devedor' => [
		'cpf'  => '123456789',
		'nome' => 'Bia naty'
	],
	'valor' => [
		'original' => '10.00',
	],
	'chave' 		     => '123456789',
	'solicitacaoPagador' => 'Pagamento do pedido 123'
];

$response = $obApiPix->createCob('WDEV12345678909876543211234', $requests);

if (!isset($response['location'])) {
	echo 'Problemas ao gerar PIX dinamico:' . $response;
}

// Instancia principal do payload pix
$obPayload = (new Payload)
	->setMerchantName('Robson Lucas')
	->setMerchantCity('SAO PAULO')
	->setAmount($response['valor']['original'])
	->setTxId($response['txid'])
	->setUniquePayment(true);

// Codigo de pagamento pix
$payloadQrCode = $obPayload->getPayload();
// QR Code
$obQrCode = new QrCode($payloadQrCode);
// Imagem do Qrcode
$image = (new Output\Png)->output($obQrCode, 400);

// header('Content-Type: image/png');
// echo $image;
?>

<h1>Qr code estatico PIX</h1>

<br />

<img src="data:image/png;base64, <?= base64_encode($image) ?>" alt="">

<br />
<br />

Codigo PIX:<br />
<strong><?= $payloadQrCode ?></strong>
