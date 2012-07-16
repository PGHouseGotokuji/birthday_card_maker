<?php
App::uses('CardCreator', 'Lib');
App::uses('FacebookFeedPoster', 'Lib');
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
        $user   = $this->loginUser;
        $token  = $user['User']['access_token'];
        $poster = new FacebookFeedPoster($token);

        //自分にポスト
        $id = $poster->postToMe('Googleはこちら1。http://google.com');

//        echo $id;
    }
}
