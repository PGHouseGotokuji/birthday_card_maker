<?php
class FacebookFeedPoster
{
    private $token;

    function FacebookFeedPoster($token)
    {
        $this->token = $token;
    }

    function postToMyTimeline($target, $msg)
    {
        $token   = $this->token;
        $ch      = curl_init();
        $params  = 'access_token=' . urlencode($token);
        $params .= '&message=' .urlencode($msg);
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/' . $target . '/feed');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp    = curl_exec($ch);
        curl_close($ch);
        if ($resp === false) {
            //通信エラー時の処理
            return FALSE;
        } else {
            $resp = json_decode($resp);
            if (isset($resp->id)) {
                return $resp->id;
            } else if (isset($resp->error)) {
                //投稿エラー時の処理
                return FALSE;
            }
        }
    }

    function postToFriendFbTimeline($fromId, $toId, $photoUrl, $description)
    {
        $token = $this->token;
        $url   = 'https://www.facebook.com/dialog/feed?app_id=' . APP_ID . '&access_token=' . urlencode($token) . '&from=' . $fromId . '&to=' . $toId . '&picture=' . urlencode($photoUrl) . '&redirect_uri=' . urlencode(SITE_URL . '/mypage') . '&description=' . $description;
        header('Location:' . $url);
        exit;
    }
}

/*
    include_once('FacebookFeedPoster.php');

    session_start();
    //echo($_SESSION['access_token']);

    $token = $_SESSION['access_token'];
    $poster = new FacebookFeedPoster($token);

    //自分にポスト
    $id = $poster->postToMe('Googleはこちら1。http://google.com');

    //マッスンにポスト
    $id = $poster->postTo('imausr', 'Googleはこちら1。http://google.com');

    echo $id;
*/
