nhk_json=`curl "http://api.nhk.or.jp/v2/pg/now/130/g1.json?key=tAr19kxB6AJ7oaxjUEBcwlbtqOgxXcqm" | jq .nowonair_list.g1.previous.title `

curl -X POST \
-H 'Content-Type:application/json' \
-H 'Authorization: Bearer {V5echcjn1fRA5WXBLixP64bPatPoVUgDPrRnN+jfDO+MIJ2kBzvWc5WqeU9Fctvnlg0OhczFWlvEhUUrqComLtTBPBtocRnySqHdVj9qqx8tYFiEvrJRSBsQutU58w/Zrcczd/ZCIpceSpJVOhdU0AdB04t89/1O/w1cDnyilFU=}' \
-d '{
    "to": "R908c3308c9d3b8ff64ad3a5fc1ef45f5",
    "messages":[
        {
            "type":"text",
            "text":'$nhk_json'
        },
        {
            "type":"text",
            "text":"いいいいい"
        }
    ]
}' https://api.line.me/v2/bot/message/push
