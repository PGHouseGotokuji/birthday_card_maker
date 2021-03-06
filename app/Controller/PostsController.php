<?php
App::uses('CardCreator',        'Lib');
App::uses('FacebookFeedPoster', 'Lib');
class PostsController extends AppController 
{
    public $uses    = array('User', 'Plan', 'Collaborator');
    var $components = array('PlanSupport');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->noLoginAction();
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
     * 自分のタイムラインに投稿して友人に呼びかける画面
     *
     * @access public
     */
    public function confirmPostFbTimeline() 
    {
        $planId = $this->params['planId'];
        if(empty($planId)){
            die('planId required');
            exit;
        }
        $this->set('planId', $planId);

        $this->set('title_for_layout', '自分のタイムラインに投稿します。よろしいですか？');
        $this->set('title_for_page',   '自分のタイムラインに投稿します。よろしいですか？');
    }

    /**
     * 自分のタイムラインに投稿する処理
     *
     * @access public
     */
    public function postFbTimeline() 
    {
        if (empty($this->loginUser)) {
            return $this->redirect('/');
        }
        $user = $this->User->findById($this->loginUser['User']['id']);
        if (empty($user)) {
            return $this->redirect('/');
        }
        $planId  = $this->params['planId'];
        $plan    = $this->Plan->findById($planId);
        $this->Plan->id = $plan['Plan']['id'];
        // Plan.plan_status
        if (!$this->Plan->saveField('plan_status', Plan::BEFORE_JOIN_COLLABORATOR, false)) {
            $this->flashAndRedirect('投稿時に問題が発生しました。再度お試しください。', $this->referer());
        }

        $process = function($poster, $planInfo, $target) {
             $url         = SITE_URL . '/plan/' . $planInfo['Plan']['id'] . '/collaborator';
             $callMessage = $planInfo['User']['username'] . 'さんが' . $planInfo['Plan']['username'] . 'さんへ誕生日のお祝いカードを作ろうとしています。' . $url .  ' へアクセスして、みんなで一緒にお祝いカードを作りましょう！';
             return $poster->postToMyTimeline('me', $callMessage);
        };

        $this->set('title_for_layout', '自分のタイムラインに投稿完了しました！');
        $this->set('title_for_page',   '自分のタイムラインに投稿完了しました！');
        $this->doPostFbTimeLine($process);
    }

    /**
     * 確認 相手のタイムラインにバースデーカードのURLとメッセージを投稿
     *
     * @access public
     */
    public function confirmPostFriendFbTimeline()
    {
        $planId = $this->params['planId'];
        $plan = $this->Plan->findById($planId);

        if (empty($plan['Plan']['photo_id'])) {
            $this->Session->setFlash('カードを組み立ててから贈りましょう', 'flash' . DS . 'success');
            return $this->redirect('/mypage');
        }
        $this->set('planId', $planId);
        $this->set('plan',   $plan);
        $this->set('title_for_layout', '誕生日の相手のタイムラインに投稿します。よろしいですか？');
        $this->set('title_for_page',   '誕生日の相手のタイムラインに投稿します。よろしいですか？');
    }

    /**
     * 誕生日の相手のタイムラインに投稿する
     *
     * @access public
     */
    public function postFriendFbTimeline()
    {
        $planId = $this->params['planId'];
        $plan   = $this->Plan->findById($planId);
        if ($plan['Plan']['photo_id'] == 0) {
            return $this->redirect('/mypage');
        }

        try {
            $this->Plan->begin();  /*** トランザクション開始 ***/
            $this->Plan->id = $planId;
            if (!$this->Plan->saveField('photo_id', $planId, false)) {
                throw new Exception();
            }
            // Plan.plan_status
            if (!$this->Plan->saveField('plan_status', Plan::COMPLETE, false)) {
                throw new Exception();
            }
            $this->Plan->commit();  /*** トランザクション終了 ***/
        } catch (Exception $e) {
            $this->Plan->rollback();
            $this->flashAndRedirect('投稿時に問題が発生しました。再度お試しください。', $this->referer());
        }

        $process = function($poster, $planInfo, $target, $fromId) {
             $photoUrl    = SITE_URL . '/img/plan-photo/' . $planInfo['Plan']['id'] . '.png';
             $description = $planInfo['Plan']['username'] . 'さん誕生日おめでとうございます！' . $planInfo['User']['username'] . 'さんと友人の皆さんがあなたに誕生日のお祝いカードを作成しましたので、' . $photoUrl .  ' へアクセスして確認してみてください！';
             return $poster->postToFriendFbTimeline($fromId, $target->id, $photoUrl, $description);
        };

        $this->set('plan', $plan);
        $this->doPostFbTimeLine($process);
    }

    /**
     * タイムラインへ投稿する処理の抽象
     *
     * @access private
     */
    private function doPostFbTimeline($postProcess)
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

        $target = $this->PlanSupport->getToUser($token, $plan);
        $id = $postProcess($poster, $plan, $target, $this->loginUser['User']['fb_id']);
        if (!empty($id)) {
            $response['Success'] = 'true';
        }

        return new CakeResponse(array('body' => json_encode($response)));
    }

    /**
     * 投稿完了
     *
     * @access public
     */
    public function posted()
    {
        return $this->flashAndRedirect('Facebookへ投稿完了しました。', '/mypage', 'success');
    }
}
