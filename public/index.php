<?php
require __DIR__ . '/../vendor/autoload.php';

use \pillr\library\http\Response as HttpResponse;

# TIP: Use the $_SERVER Sugerglobal to get all the data your need from the Client's HTTP Request.

# TIP: HTTP headers are printed natively in PHP by invoking header().
#      Ex. header('Content-Type', 'text/html');

$protocolVersion = substr($_SERVER['SERVER_PROTOCOL'], 5);
$statusCode = http_response_code();
$reasonPhrase =  "";

$messageBody = array('@id' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
    "to" => "Pillr",
    "subject" => "Hello Pillr",
    "message" => "Here is my submission.",
    "from" => "Taras Kubrak",
    "timeSent" => date("Y-m-d H:i:s"));

$headers = array('Date' => date("D, d M Y H:i:s T"),
                'Server' => $_SERVER['SERVER_NAME'],
                'Last-Modified' => date("D, d M Y H:i:s T"),
                'Content-Length' => strlen(json_encode($messageBody, JSON_PRETTY_PRINT)),
                'Content-Type' => 'application/json');

header('Date: '. date("D, d M Y H:i:s T"));
header('Server: '. $_SERVER['SERVER_NAME']);
header('Last-Modified: ' . date("D, d M Y H:i:s T"));
header('Content-Length' . strlen(json_encode($messageBody, JSON_PRETTY_PRINT)));
header('Content-Type: application/json');

$httpResponse = new HttpResponse($protocolVersion, $statusCode, $reasonPhrase, $headers, $messageBody);

echo substr($_SERVER['SERVER_PROTOCOL'], 0, 5) . $httpResponse->getProtocolVersion()
    . " " . $httpResponse->getStatusCode() . " " . $httpResponse->getReasonPhrase() . "\n";

$responseHeaders = $httpResponse->getHeaders();

foreach($responseHeaders as $key => $value){
    echo $key . ": " . $value . "\n";
}

$jsonBodyMessage = json_encode($httpResponse->getBody(), JSON_PRETTY_PRINT);
echo stripslashes($jsonBodyMessage);

