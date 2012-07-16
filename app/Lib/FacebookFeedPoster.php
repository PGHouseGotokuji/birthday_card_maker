<?php

    class FacebookFeedPoster{

        private $token;

        function FacebookFeedPoster($token){
            $this->token = $token;
        }

        function postToMe($msg){
            return $this->postTo('me', $msg);
        }

        function postTo($id, $msg){

            $token = $this->token;
            $ch = curl_init();
            $params = 'access_token=' . urlencode($token);
            $params .= '&message=' .urlencode($msg);
            curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/' . $id . '/feed');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $resp = curl_exec($ch);
            curl_close($ch);
            if ($resp === false) {
              //通信エラー時の処理
              return FALSE;
            }
            else {
              $resp = json_decode($resp);
              if (isset($resp->id)) {
                  return $resp->id;
              }
              else if (isset($resp->error)) {
                  //投稿エラー時の処理
                  return FALSE;
              }
            }
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
