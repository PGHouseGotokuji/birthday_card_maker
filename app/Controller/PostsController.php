<?php
App::uses('CardCreator', 'Lib');
App::uses('FacebookFeedPoster', 'Lib');
class PostsController extends AppController 
{
    public $uses     = array('User', 'Plan', 'Collaborator');
//    var $components  = array('Security');

    var $components  = array('PlanSupport');

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
                'name'     => $collaborator['User']['username']
            ));
        }
        $img = $creator->createImage();
        responseJpeg($img);
    }

    /**
     * 確認 自分のタイムラインに投稿
     *
     * @access public
     */
    public function confirmPostFbTimeline() 
    {
        $this->set('title_for_layout', '自分のタイムラインに投稿します。よろしいですか？');
        $this->set('title_for_page', '自分のタイムラインに投稿します。よろしいですか？');
    }

    /**
     * 自分のタイムラインに投稿
     *
     * @access public
     */
    public function postFbTimeline() 
    {
        $planId = $this->params['planId'];
        if(empty($planId)){
            die('planId required');
            exit;
        }

        $response['Success'] = 'false';
        $user   = $this->loginUser;
        $token  = $user['User']['access_token'];
        $poster = new FacebookFeedPoster($token);

        $plan = $this->PlanSupport->findWithFromUser($this->Plan, $planId);
        if(empty($plan)){
            die('id not found');
            return;
        }

        $access_token = $this->loginUser['User']['access_token'];
        $target = $this->PlanSupport->getToUser($access_token, $plan);

//上記の情報を利用して文章を作ること。

        $content = 'Sato ShunさんがHiroki Masuiさんへ誕生日のお祝いカードを皆さんと作ろうとしています。http://birthdaycard.com/x53287xxx
                    へアクセスして下さい。';


        $id = $poster->postToMe('テスト投稿が完了しました!! http://dev.birthday-card-maker.com/plan/' . $planId . '/collaborator');
        if (!empty($id)) {
            $response['Success'] = 'true';
        }

        return new CakeResponse(array('body' => json_encode($response)));
    }


    public function confirm(){

        $planId = $this->params['planId'];
        $this->set('planId', $planId);

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
