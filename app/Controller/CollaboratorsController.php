<?php
class CollaboratorsController extends AppController 
{
//    public $helpers  = array('Common', 'DispUser');
    public $uses     = array('Plan', 'Collaborator');
    var $components  = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->blackHoleCallback = 'error';

        $this->userLoginCheck('getCollaborator');
    }

    /**
     * 誕生日プラン情報取得
     *
     * @access public
     */
    public function getCollaborator() 
    {
        $user = $this->loginUser;
//        $plan = $this->Plan->findByFromId($user['User']['id']);

$collaborator = $this->User->findById($user['User']['id']);


pr($collaborator);
exit;

        return new CakeResponse(array('body' => json_encode($collaborator)));
    }
}
