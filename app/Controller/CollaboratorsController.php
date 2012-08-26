<?php
class CollaboratorsController extends AppController 
{
    public $uses     = array('Plan', 'Collaborator');
    var $components  = array('Security', 'PlanSupport');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Security->blackHoleCallback = 'error';
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
            $this->redirect('/');
        }
        $this->redirect('/plan/' . $planId . '/collaborator/confirm');
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
            $this->redirect('/');
            exit;
        }
        $plan = $this->PlanSupport->findWithFromUser($this->Plan, $planId);
        if(empty($plan)){
            die('id not found');
            return;
        }

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

        $data = array();
        $data['Collaborator'] = array();
        $data['Collaborator']['plan_id'] = $planId;
        $data['Collaborator']['uid'] = $this->loginUser['User']['id'];

        $this->Collaborator->create();
        if (!$this->Collaborator->save($data)) {
            $this->Session->setFlash('処理中に問題が発生しました。', 'flash' . DS . 'error');
            return $this->redirect('/');
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
    }

    /**
     * 画像保存
     *
     * @access public
     */
    public function uploadPhoto()
    {
        return new CakeResponse(array('body' => json_encode(true)));
    }
}
