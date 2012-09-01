<?php
class CollaboratorsController extends AppController 
{
    public $name = 'Collaborators';

    public $uses    = array('Plan', 'Collaborator');
    var $components = array('PlanSupport');

    public function beforeFilter()
    {
        parent::beforeFilter();

        $planId = $this->params['planId'];
        $plan   = $this->Plan->findById($planId);
        if (!empty($plan)) {
            $this->noLoginAction('joinCollaborator');
        } else {
            // ajax時と動作を分ける
        }
    }

    /**
     * 誕生日に参加してくれる人たちの情報取得
     *
     * @access public
     */
    public function getCollaborators() 
    {
        $plan          = $this->Plan->findById($this->params['planId']);
        $collaborators = array();
        if (!empty($plan)) {
            $collaborators = $this->Collaborator->find('all', array(
                'conditions' => array(
                    'plan_id' => $plan['Plan']['id']
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
        $planId = $this->params['planId'];
        if (empty($this->loginUser)) {
            $this->Session->write('redirectUrl', '/plan/' . $planId .'/collaborator');
            return $this->redirect('/');
        }
        return $this->redirect('/plan/' . $planId . '/collaborator/confirm');
    }


    /**
     * 投稿しますか？画面
     *
     * @access pubilc
     */
    public function confirm()
    {
        $planId = $this->params['planId'];
        if (empty($this->loginUser)) {
            $this->Session->write('redirect', '/plan' . DS . $planId . DS . 'collaborator');
            return $this->redirect('/');
        }
        $plan = $this->PlanSupport->findWithFromUser($this->Plan, $planId);
        if(empty($plan)){
            die('id not found');
            return;
        }

        $this->set(compact('planId'));
        $this->set('from_name', $plan['User']['username']);

        $access_token = $this->loginUser['User']['access_token'];
        $target       = $this->PlanSupport->getToUser($access_token, $plan);

        $this->set('to_name', $target->username);
        $this->set('imageUrl', $target->picture->data->url);
    }

    /**
     * 許可！
     *
     * @access pubilc
     */
    public function accept()
    {
        $planId = $this->params['planId'];
        if (empty($this->loginUser)) {
            $this->Session->write('redirect', '/plan' . DS . $planId . DS . 'collaborator');
            return $this->redirect('/');
        }

        $collaborator = $this->Collaborator->find('first', array(
            'conditions' => array(
                'uid' => $this->loginUser['User']['id'],
                'plan_id' => $planId
            )
        ));
        // 未登録の場合のみ、登録
        if (empty($collaborator)) {
            $data = array();
            $data['Collaborator'] = array();
            $data['Collaborator']['plan_id'] = $planId;
            $data['Collaborator']['uid'] = $this->loginUser['User']['id'];
    
            $this->Collaborator->create();
            if (!$this->Collaborator->save($data)) {
                $this->Session->setFlash('処理中に問題が発生しました。', 'flash' . DS . 'error');
                return $this->redirect('/');
            }
        }

        $plan = $this->PlanSupport->findWithFromUser($this->Plan, $planId);

        if(empty($plan)){
            die('id not found');
            return;
        }

        $access_token = $this->loginUser['User']['access_token'];
        $target       = $this->PlanSupport->getToUser($access_token, $plan);
        $this->set('name', $target->username);
        $this->set('imageUrl', $target->picture->data->url);
        $this->set('planId', $planId);
    }

    /**
     * 画像保存
     *
     * @access public
     */
    public function uploadPhoto()
    {
        $planId = $this->params['planId'];
        $uid    = $this->params['collaboratorId'];
        $collaborator = $this->Collaborator->find('first', array(
            'conditions' => array(
                'plan_id' => $planId,
                'uid'     => $uid
            )
        ));
        if (empty($collaborator)) {
            $this->log('存在しないプランID, もしくはコラボレータIDを叩かれました。planId: ' . $planId . ', collaboratorId: ' . $collaboratorId . ', ' . $this->name . ', ' . $this->action . ', ' . __LINE__, 'warn');
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
                $this->Collaborator->commit(); /*** トランザクション終了 ***/
            } catch (Exception $e) {
                $this->Collaborator->rollback();
                $this->Session->setFlash('画像保存時に問題が発生しました。再度お試しください。', 'flash' . DS . 'error');
                return new CakeResponse(array('body' => json_encode(false)));
            }
            $this->Session->setFlash('プラン画像を保存しました。', 'flash' . DS . 'success');
            return new CakeResponse(array('body' => json_encode(true)));
        }

        return new CakeResponse(array('body' => json_encode(false)));
    }
}
