<?php
App::uses('CardCreator', 'Lib');
class PostsController extends AppController 
{
    public $uses     = array('User', 'Plan', 'Collaborator');
    var $components  = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->blackHoleCallback = 'error';

        $this->userLoginCheck('postCard');
    }

    /**
     * 誕生日プラン情報取得
     *
     * @access public
     */
    public function postCard() 
    {
        $user = $this->loginUser;
        $plan = $this->Plan->findByFromId($user['User']['id']);

// TODO たぶんto_id情報も必要になるねコレ
        $creator = new CardCreator();
        $creator->setBackground(WWW_ROOT . 'img/test.jpg');
        $creator->put(array(
            'imageUrl' => 'https://graph.facebook.com/100000514317787/picture',
            'name' => 'Shin.Kinjo'
        ));
        $creator->put(array(
            'imageUrl' => 'https://graph.facebook.com/100000514317787/picture',
            'name' => 'Shin.Kinjo'
        ));
        $creator->put(array(
            'imageUrl' => 'https://graph.facebook.com/100000316117821/picture',
            'name' => 'Shin.Kinjo'
        ));
        $img = $creator->createImage();
        responseJpeg($img);
    }

    /**
     * 自分のタイムラインに投稿
     *
     * @access public
     */
    public function postFbTimeline() 
    {
        $user = $this->loginUser;
$token = $user['User']['access_token'];
$ch = curl_init();
$params = 'access_token=' . urlencode($token);
$params .= '&message=' .urlencode('テスト投稿');
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me/feed');
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$resp = curl_exec($ch);
curl_close($ch);
if ($resp === false) {
pr(1);
exit;
//  通信エラー時の処理
} else {
  $resp = json_decode($resp);
  if (isset($resp->id)) {
//    投稿に成功した時の処理
pr(2);
exit;
  }
  else if (isset($resp->error)) {
//    投稿に失敗した時の処理

pr(3);
exit;
  }
}




    }
}
