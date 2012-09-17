<?php
class CollaboratorsController extends AppController 
{
    public $name       = 'Collaborators';
    public $uses       = array('Plan', 'Collaborator', 'User');
    public $components = array('PlanSupport');

    public $plan       = array();

    public function beforeFilter()
    {
        parent::beforeFilter();

        $planId = $this->params['planId'];
        $plan   = $this->Plan->findById($planId);
        if (!empty($plan)) {
            $this->plan = $plan;
            $this->noLoginAction('joinCollaborator');
        } else {
            // ajax時と動作を分ける
        }
    }

    /**
     * 指定のプランのコラボレータ情報を全件取得
     *
     * @access public
     */
    public function getCollaborators() 
    {
        $collaborators = array();
        if (!empty($this->plan)) {
            $collaborators = $this->Collaborator->find('all', array(
                'conditions' => array(
                    'plan_id' => $this->plan['Plan']['id']
                )
            ));
        }

        return new CakeResponse(array('body' => json_encode($collaborators)));
    }

    /**
     * 参加者がfacebookログインしていなかったら登録
     *
     * @access public
     */
    public function joinCollaborator() 
    {
        if (empty($this->loginUser)) {
            $this->Session->write('redirectUrl', '/plan/' . $this->plan['Plan']['id'] . '/collaborator');
            return $this->redirect('/');
        }
        return $this->redirect('/plan/' . $this->plan['Plan']['id'] . '/collaborator/confirm');
    }


    /**
     * 投稿しますか？画面
     *
     * @access pubilc
     */
    public function confirm()
    {
        if(empty($this->plan)){
            die('id not found');
            return;
        }
        // plans.to_idがログインユーザのfb_idと一致していたらトップへ戻す
        if ($this->loginUser['User']['fb_id'] == $this->plan['Plan']['to_id']) {
            $this->Session->setFlash('申し訳ございません、この誕生日企画には参加できません。', 'flash' . DS . 'error');
            return $this->redirect('/');
        }

        $planFromUser = $this->User->findById($this->plan['Plan']['from_id']);
        $this->set('from_name', $planFromUser['User']['username']);
        $this->set('to_name',   $this->plan['Plan']['username']);
        $this->set('imageUrl',  $this->plan['Plan']['fb_picture']);
        $this->set('planId',    $this->plan['Plan']['id']);
    }

    /**
     * 許可！
     *
     * @access pubilc
     */
    public function accept()
    {
        if (empty($this->plan)) {
            die('id not found');
            return;
        }
        // plans.to_idがログインユーザのfb_idと一致していたらトップへ戻す
        if ($this->loginUser['User']['fb_id'] == $this->plan['Plan']['to_id']) {
            $this->Session->setFlash('申し訳ございません、この誕生日企画には参加できません。', 'flash' . DS . 'error');
            return $this->redirect('/');
        }

        $collaborator = $this->Collaborator->find('first', array(
            'conditions' => array(
                'uid' => $this->loginUser['User']['id'],
                'plan_id' => $this->plan['Plan']['id']
            )
        ));
        // 未登録の場合のみ、登録
        if (empty($collaborator)) {
            $data = array();
            $data['Collaborator'] = array();
            $data['Collaborator']['plan_id'] = $this->plan['Plan']['id'];
            $data['Collaborator']['uid'] = $this->loginUser['User']['id'];
    
            $this->Collaborator->create();
            if (!$this->Collaborator->save($data)) {
                $this->Session->setFlash('処理中に問題が発生しました。', 'flash' . DS . 'error');
                return $this->redirect('/');
            }
        }

//        $access_token = $this->loginUser['User']['access_token'];
//        $target       = $this->PlanSupport->getToUser($access_token, $plan);
//        $this->set('name', $target->username);
//        $this->set('imageUrl', $target->picture->data->url);
//        $this->set('name',     $plan['Plan']['username']);
//        $this->set('imageUrl', $plan['Plan']['fb_picture']);
        $this->set('planId', $this->plan['Plan']['id']);
    }

    /**
     * 画像保存
     *
     * @access public
     */
    public function uploadPhoto()
    {
        if (empty($this->plan)) {
            die('id not found');
            return;
        }
        // plans.to_idがログインユーザのfb_idと一致していたらトップへ戻す
        if ($this->loginUser['User']['fb_id'] == $this->plan['Plan']['to_id']) {
            $this->Session->setFlash('申し訳ございません、この誕生日企画には参加できません。', 'flash' . DS . 'error');
            return $this->redirect('/');
        }

        $uid          = $this->params['collaboratorId'];
        $collaborator = $this->Collaborator->find('first', array(
            'conditions' => array(
                'plan_id' => $this->plan['Plan']['id'],
                'uid'     => $uid
            )
        ));
        if (empty($collaborator)) {
            $this->log('存在しないプランID, もしくはコラボレータIDを叩かれました。planId: ' . $this->plan['Plan']['id'] . ', collaboratorId: ' . $collaboratorId . ', ' . $this->name . ', ' . $this->action . ', ' . __LINE__, 'warn');
            $this->Session->setFlash('誕生日プランを作成してください。', 'flash' . DS . 'error');
            return new CakeResponse(array('body' => json_encode(false)));
        }

        if ($this->request->is('post')) {
            $data = $this->request->data;
            try {            
                $this->Collaborator->begin();  /*** トランザクション開始 ***/
                // Collaborator
                $this->Collaborator->id = $collaborator['Collaborator']['id'];
                $photoId = $collaborator['Collaborator']['plan_id'] . '-' . $collaborator['Collaborator']['id'];
                if (!$this->Collaborator->saveField('photo_id', $photoId, false)) {
                    throw new Exception();
                }
                // put photo_data
                if (!$this->Collaborator->savePhoto($photoId, COLLABO_PHOTO_DIR, $data['img_file'])) {   
                    throw new Exception();
                }
                // Plan.plan_status 増井TODO ここがどうやらうまく動いていないっぽい
                $this->Plan->id = $this->plan['Plan']['id'];
                if (!$this->Plan->saveField('plan_status', Plan::BEFORE_MAKE_CARD, false)) {
                    throw new Exception();
                }
                $this->Collaborator->commit(); /*** トランザクション終了 ***/
            } catch (Exception $e) {
                $this->Collaborator->rollback();
                $this->Session->setFlash('画像保存時に問題が発生しました。再度お試しください。', 'flash' . DS . 'error');
                return new CakeResponse(array('body' => json_encode(false)));
            }
            $this->Session->setFlash('メッセージの提供、ありがとうござました。', 'flash' . DS . 'success');
            return new CakeResponse(array('body' => json_encode(true)));
        }

        return new CakeResponse(array('body' => json_encode(false)));
    }
}
