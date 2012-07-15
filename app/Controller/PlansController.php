<?php
class PlansController extends AppController 
{
//    public $helpers  = array('Common', 'DispUser');
    public $uses     = array('Plan');
    var $components  = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->blackHoleCallback = 'error';

        $this->userLoginCheck('getPlan');
    }

    /**
     * 誕生日プラン情報取得
     *
     * @access public
     */
    public function getPlan() 
    {
        $user = $this->loginUser;
        $plan = $this->Plan->findByFromId($user['User']['id']);
        return new CakeResponse(array('body' => json_encode($plan)));
    }
}
