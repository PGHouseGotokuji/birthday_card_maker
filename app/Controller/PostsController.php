<?php
App::uses('CardCreator', 'Lib');
App::uses('FacebookFeedPoster', 'Lib');
class PostsController extends AppController 
{
    public $uses     = array('User', 'Plan', 'Collaborator');
//    var $components  = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

//        $this->Security->blackHoleCallback = 'error';

//        $this->userLoginCheck('postCard');
    }

    /**
     * 誕生日色紙画像取得
     *
     * @access public
     */
    public function postCard() 
    {

// TODO たぶんto_id情報も必要になるねコレ

        $user = $this->loginUser;
        $plan = $this->Plan->findByFromId($user['User']['id']);

        $joins = array(
            array(
                'type'  => 'INNER',
                'table' => '`users` `User`',
                'conditions' => '`User`.`id`=`Collaborator`.`uid`',
            )
        );
        $collaborators = $this->Collaborator->find('all', array(
            'fields' => array('Collaborator.*', 'User.username', 'User.fb_id'),
            'joins' => $joins,
            'conditions' => array(
                'plan_id' => $plan['Plan']['id']
            )
        ));

        $creator = new CardCreator();
        $creator->setBackground(WWW_ROOT . 'img/test.jpg');
        foreach ($collaborators as $key => $collaborator) {
            $creator->put(array(
                'imageUrl' => 'https://graph.facebook.com/' . $collaborator['User']['fb_id'] . '/picture',
                'name' => $collaborator['User']['username']
            ));
        }
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
// 増井TODO テストしてないっす
        $planId = !empty($this->Session->read('planId')) ? $this->Session->read('planId') : null;
        $response['Success'] = 'false';
        $user   = $this->loginUser;
        $token  = $user['User']['access_token'];
        $poster = new FacebookFeedPoster($token);

        //自分にポスト
        if (!empty($planId)) {
            $id = $poster->postToMe('テスト投稿が完了しました!! http://dev.birthday-card-maker.com/plan/' . $planId . '/collaborator');
            if (!empty($id)) {
                $response['Success'] = 'true';
            }
        }
        return new CakeResponse(array('body' => json_encode($response)));        
    }

    /**
     * 誕生日の人のタイムラインに投稿完了
     *
     * @access public
     */
    public function cardPosted() 
    {
    }
}
