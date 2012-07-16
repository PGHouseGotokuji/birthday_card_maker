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
            $this->Session->write('redirect', '/plan' . DS . $planId . DS . 'collaborator');
            $this->redirect('/');
        }


exit;




    }
}
