<?php
$accessToken = 'V5echcjn1fRA5WXBLixP64bPatPoVUgDPrRnN+jfDO+MIJ2kBzvWc5WqeU9Fctvnlg0OhczFWlvEhUUrqComLtTBPBtocRnySqHdVj9qqx8tYFiEvrJRSBsQutU58w/Zrcczd/ZCIpceSpJVOhdU0AdB04t89/1O/w1cDnyilFU=';


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

$fp = fopen("sample.txt", "w");
fwrite($fp, $json_string);
fclose($fp);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
  exit;
}

//返信データ作成
$response_format_text = [
  "type" => "text",
  "text" => "hello japanese"
  ];
$post_data = [
  "replyToken" => $replyToken,
  "messages" => [$response_format_text]
  ];

$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);

?>
