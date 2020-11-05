<?php


namespace classes\Messaging;


class TelegramRaw
{
    //Um Nachrichten zu senden, mit der Telegram-App (nicht mit dem Browser) einen Bot
    //anlegen.

    public static function sendTelegramMessage($chatID, $nachricht, $token){
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
        $url = $url . "&text=" . urlencode($nachricht);

        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}