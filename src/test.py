from linebot import LineBotApi
from linebot.models import TextSendMessage

line_bot_api = LineBotApi('V5echcjn1fRA5WXBLixP64bPatPoVUgDPrRnN+jfDO+MIJ2kBzvWc5WqeU9Fctvnlg0OhczFWlvEhUUrqComLtTBPBtocRnySqHdVj9qqx8tYFiEvrJRSBsQutU58w/Zrcczd/ZCIpceSpJVOhdU0AdB04t89/1O/w1cDnyilFU=')

try:
  line_bot_api.push_message('@ixq3955i', TextSendMessage(text='Hello World!'))
except linebot.exceptions.LineBotApiError as e:
  print e

