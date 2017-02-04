<?php

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('V5echcjn1fRA5WXBLixP64bPatPoVUgDPrRnN+jfDO+MIJ2kBzvWc5WqeU9Fctvnlg0OhczFWlvEhUUrqComLtTBPBtocRnySqHdVj9qqx8tYFiEvrJRSBsQutU58w/Zrcczd/ZCIpceSpJVOhdU0AdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => 'fc8adf5b7258106c8e49eaf4975ae601']);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
$response = $bot->pushMessage('<to>', $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

?>
