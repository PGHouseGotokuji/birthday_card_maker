<?php
class PlansController extends AppController 
{
    public $uses     = array('Plan');
//    var $components  = array('Security');

    public function beforeFilter()
    {
        parent::beforeFilter();

//        $this->Security->blackHoleCallback = 'error';

//        $this->userLoginCheck('getPlan');
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

    /**
     * 誕生日プラン情報全件取得
     *
     * @access public
     */
    public function getPlans() 
    {
        $user = $this->loginUser;
        $plans = $this->Plan->find('all', array(
            'conditions' => array(
                'from_id' => $user['User']['id']
            )
        ));
        return new CakeResponse(array('body' => json_encode($plans)));
    }

    /**
     * 誕生日プラン登録
     *
     * @access public
     */
    public function insertPlan() 
    {
        $user = $this->loginUser;
        $response['Success'] = 'false';
        if (!empty($this->request->data)) {
            $data['Plan']['from_id']    = $user['User']['id'];
            $data['Plan']['to_id']      = $this->request->data['id'];
            $data['Plan']['username']   = $this->request->data['name'];
            $data['Plan']['fb_picture'] = $this->request->data['fb_picture'];
//$this->log($data, 'warn');
//exit;
            $this->Plan->create();
            if ($this->Plan->save($data)) {
                $response['Success'] = 'true';
                $this->Session->write('planId', $this->Plan->id);
            }
        }

        return new CakeResponse(array('body' => json_encode($response)));
    }

}
