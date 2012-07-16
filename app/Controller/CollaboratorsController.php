<?php
class CollaboratorsController extends AppController 
{
    public $uses     = array('Plan', 'Collaborator');
    var $components  = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->blackHoleCallback = 'error';

        $this->userLoginCheck('getCollaborator');
    }

    /**
     * 誕生日に参加してくれる人たちの情報取得
     *
     * @access public
     */
    public function getCollaborators() 
    {
//        $user          = $this->loginUser;
//        $plan          = $this->Plan->findByFromId($user['User']['id']);
        $plan          = $this->Plan->findById($this->params['planId']);
        $collaborators = array();
        if (!empty($plan)) {

/*
$innerJoin = array(
    array(
        'type'  => 'INNER',
        'table' => '`users` `User`',
        'conditions' => '`Collaborator`.`uid`=`User`.`id`'
    )
);
*/
            $collaborators = $this->Collaborator->find('all', array(
//                'joins' => $innerJoin,
                'conditions' => array(
                    'plan_id' => $plan['Plan']['id']
                )
            ));
        }


//        $collaborators['Count'] = count($collaborators);
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
     */
    public function confirm(){

        $planId = $this->params['planId'];
        if (empty($this->loginUser)) {
            $this->Session->write('redirect', '/plan' . DS . $planId . DS . 'collaborator');
            $this->redirect('/');
            exit;
        }

        $joins = array(
            array(
                'type'  => 'INNER',
                'table' => '`users` `User`',
                'conditions' => '`User`.`id`=`Plan`.`from_id`',
            )
        );
        $plan = $this->Plan->find('first', array(
            'fields' => array('Plan.*', 'User.*'),
            'joins' => $joins,
            'conditions' => array(
                '`Plan`.`id`' => $planId
            )
        ));

        if(empty($plan)){
            die('id not found');
            return;
        }

        $this->set('from_name', $plan['User']['username']);

        $access_token = $this->loginUser['User']['access_token'];
        $url = 'https://graph.facebook.com/' . $plan['Plan']['to_id'] . '?access_token=' . $access_token . '&fields=username,picture';
        $target = json_decode(file_get_contents($url));

        $this->set('to_name', $target->username);
        $this->set('imageUrl', $target->picture);
    }

    /**
     * 許可！
     */
    public function accept(){

        $planId = $this->params['planId'];
        if (empty($this->loginUser)) {
            $this->Session->write('redirect', '/plan' . DS . $planId . DS . 'collaborator');
            $this->redirect('/');
            exit;
        }


        $data = array();
        $data['Collaborator'] = array();
        $data['Collaborator']['plan_id'] = $planId;
        $data['Collaborator']['uid'] = $this->loginUser['User']['id'];

        //DBに挿入
        $this->Collaborator->create();
        if (!$this->Collaborator->save($data)) {
            $this->Session->setFlash('処理中に問題が発生しました。', 'flash' . DS . 'error');
            $this->redirect('/');
            exit;
        }


        $joins = array(
            array(
                'type'  => 'INNER',
                'table' => '`users` `User`',
                'conditions' => '`User`.`id`=`Plan`.`from_id`',
            )
        );
        $plan = $this->Plan->find('first', array(
            'fields' => array('Plan.*', 'User.*'),
            'joins' => $joins,
            'conditions' => array(
                '`Plan`.`id`' => $planId
            )
        ));

        if(empty($plan)){
            die('id not found');
            return;
        }

        $access_token = $this->loginUser['User']['access_token'];
        $url = 'https://graph.facebook.com/' . $plan['Plan']['to_id'] . '?access_token=' . $access_token . '&fields=username,picture';
        //print($url);
        $target = json_decode(file_get_contents($url));

        $this->set('name', $target->username);
        $this->set('imageUrl', $target->picture);
    }
}
