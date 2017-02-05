<?php
$accessToken = 'V5echcjn1fRA5WXBLixP64bPatPoVUgDPrRnN+jfDO+MIJ2kBzvWc5WqeU9Fctvnlg0OhczFWlvEhUUrqComLtTBPBtocRnySqHdVj9qqx8tYFiEvrJRSBsQutU58w/Zrcczd/ZCIpceSpJVOhdU0AdB04t89/1O/w1cDnyilFU=';

$fp = fopen("sample.txt", "w");
fwrite($fp, $json_string);
fclose($fp);

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
  exit;
}

$fp = fopen("before.txt","r");
while (!feof($fp)) {
  $line = fgets($fp);
}
fclose($fp);

if ($line != ""){
   if ($line == "CD"){
       $response_format_text = KickRakutenAPI("CD",$text);
   }elseif ($line == "DVD"){
       $response_format_text = KickRakutenAPI("DVD",$text);
   }elseif ($line == "TV"){
           $response_format_text = getTV($text); 
   }

   $fp = fopen("before.txt", "w");
   fwrite($fp, "");
   fclose($fp);
    
}else{

if($text == "CD"){
$fp = fopen("before.txt", "w");
fwrite($fp, "CD");
fclose($fp);
$response_format_text = [
  "type" => "text",
  "text" => "アーティストは？"
  ];

} elseif ($text == "DVD"){
$fp = fopen("before.txt", "w");
fwrite($fp, "DVD");
fclose($fp);
$response_format_text = [
  "type" => "text",
  "text" => "出演者は？"
  ];
}elseif($text == "TV"){
$fp = fopen("before.txt", "w");
fwrite($fp, "TV");
fclose($fp);
$response_format_text = [
  "type" => "text",
  "text" => "テレビ出演者は？"
  ];
}else{

$reply_text = webnist($text);

//返信データ作成
$response_format_text = [
  "type" => "text",
  "text" => "$reply_text"
  ];
}
}

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

function KickRakutenAPI($type,$text){
$ch = curl_init(); // init
if ($type == "CD"){
    curl_setopt($ch, CURLOPT_URL, "https://app.rakuten.co.jp/services/api/BooksCD/Search/20130522?format=json&artistName=".$text."&booksGenreId=002&sort=-releaseDate&applicationId=1044766103436809187"); // URLをセット
} elseif ($type == "DVD"){
    curl_setopt($ch, CURLOPT_URL, "https://app.rakuten.co.jp/services/api/BooksDVD/Search/20130522?format=json&artistName=".$text."&booksGenreId=003&sort=-releaseDate&applicationId=1044766103436809187"); // URLをセット
} 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // curl_exec()の結果を文字列で返す
$c = curl_exec($ch); // 実行
curl_close($ch); // おまじない

$d = json_decode($c, true);

$thumb1 = str_replace("http","https",$d["Items"][0]["Item"]["mediumImageUrl"]);
$title1 = mb_strimwidth($d["Items"][0]["Item"]["title"], 0, 35, "..."); 
$caption1 =  mb_strimwidth($d["Items"][0]["Item"]["itemCaption"], 0, 50, "..."); 
if( $caption1 == ""){
    $caption1 = "no message";
}
$thumb2 = str_replace("http","https",$d["Items"][1]["Item"]["mediumImageUrl"]);
$title2 = mb_strimwidth($d["Items"][1]["Item"]["title"], 0, 35, "..."); 
$caption2 =  mb_strimwidth($d["Items"][1]["Item"]["itemCaption"], 0, 50, "..."); 
if( $caption2 == ""){
    $caption2 = "no message";
}
$thumb3 = str_replace("http","https",$d["Items"][2]["Item"]["mediumImageUrl"]);
$title3 = mb_strimwidth($d["Items"][2]["Item"]["title"], 0, 35, "..."); 
$caption3 =  mb_strimwidth($d["Items"][2]["Item"]["itemCaption"], 0, 50, "..."); 
if( $caption3 == ""){
    $caption3 = "no message";
}
$thumb4 = str_replace("http","https",$d["Items"][3]["Item"]["mediumImageUrl"]);
$title4 = mb_strimwidth($d["Items"][3]["Item"]["title"], 0, 35, "..."); 
$caption4 =  mb_strimwidth($d["Items"][3]["Item"]["itemCaption"], 0, 50, "..."); 
if( $caption4 == ""){
    $caption4 = "no message";
}
$thumb5 = str_replace("http","https",$d["Items"][4]["Item"]["mediumImageUrl"]);
$title5 = mb_strimwidth($d["Items"][4]["Item"]["title"], 0, 35, "..."); 
$caption5 =  mb_strimwidth($d["Items"][4]["Item"]["itemCaption"], 0, 50, "..."); 
if( $caption5 == ""){
    $caption5 = "no message";
}

//返信データ作成
  $response_format_text = [
    "type" => "template",
    "altText" => "最新CD情報",
    "template" => [
      "type" => "carousel",
      "columns" => [
          [
            "thumbnailImageUrl" => "$thumb1",
            "title" => "$title1",
            "text" => "$caption1",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],
          [
            "thumbnailImageUrl" => "$thumb2",
            "title" => "$title2",
            "text" => "$caption2",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],
          [
            "thumbnailImageUrl" => "$thumb3",
            "title" => "$title3",
            "text" => "$caption3",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],
          [
            "thumbnailImageUrl" => "$thumb4",
            "title" => "$title4",
            "text" => "$caption4",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],
          [
            "thumbnailImageUrl" => "$thumb5",
            "title" => "$title5",
            "text" => "$caption5",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ]
      ]
    ]
  ];

return $response_format_text;
}


function webnist($text) {
  $context_file = dirname(__FILE__).'/.docomoapi.context';
  $api_key = '537378555754626667475874424c7a4239304f44615646364a497959633669725675424656727a464b6631';
  $api_url = sprintf('https://api.apigw.smt.docomo.ne.jp/dialogue/v1/dialogue?APIKEY=%s', $api_key);
  $req_body = array('utt' => $text);
  if ( file_exists($context_file) ) {
    $req_body['context'] = file_get_contents($context_file);
  }
  $headers = array(
    'Content-Type: application/json; charset=UTF-8',
    );
  $options = array(
    'http'=>array(
        'method'  => 'POST',
        'header'  => implode( "\r\n", $headers ),
        'content' => json_encode($req_body),
        )
    );
  $stream = stream_context_create( $options );
  $res = json_decode(file_get_contents($api_url, false, $stream));
  if (isset($res->context)) {
    file_put_contents($context_file, $res->context);
  }
  return isset($res->utt) ? $res->utt : '';
}

function getTV($text){
    //対象URL取得
    $geturl = "http://tv.so-net.ne.jp/schedulesBySearch.action?stationPlatformId=0&condition.keyword=".$text."&submit=%E6%A4%9C%E7%B4%A2";
    $channel = [];
    $content = [];

    if($geturl != ""){
        // HTMLソース取得
        $html = file_get_contents($geturl);
        if($html != ""){
            // あれやこれやと整形
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xml = simplexml_import_dom($dom);
            $json = json_encode($xml);
            $scraped_data = json_decode($json,true);
            $array = $scraped_data["body"]["div"][5]["form"][0]["div"]["div"];
            foreach ($array as $id => $rec) {
               if ($rec["h2"]["a"] != NULL){
                     array_push($channel, $rec["h2"]["a"]);
                     array_push($content, $rec["p"]);
               }
            }

            $info_msg = "ファイルの取得に成功しました";
        }else{
            $info_msg = "ファイルの取得に失敗しました";
        }
    }else{
        $info_msg = "取得対象URLを入力して下さい";
    }


$channel0 = mb_strimwidth($channel[0], 0, 35, "..."); 
$content0 =  mb_strimwidth($content[0], 0, 50, "..."); 
$channel1 = mb_strimwidth($channel[1], 0, 35, "..."); 
$content1 =  mb_strimwidth($content[1], 0, 50, "..."); 
$channel2 = mb_strimwidth($channel[2], 0, 35, "..."); 
$content2 =  mb_strimwidth($content[2], 0, 50, "..."); 

//返信データ作成
  $response_format_text = [
    "type" => "template",
    "altText" => "最新CD情報",
    "template" => [
      "type" => "carousel",
      "columns" => [
          [
            "title" => "$channel0",
            "text" => "$content0",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],[
            "title" => "$channel2",
            "text" => "$content2",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],[
            "title" => "$channel2",
            "text" => "$content2",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "buy",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "favorite",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳細",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ]
      ]
    ]
  ];

   return $response_format_text;
}

?>
