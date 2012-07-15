<?php
class PlansController extends AppController 
{
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

    /**
     * 誕生日プラン登録
     *
     * @access public
     */
    public function insertPlan() 
    {
        $user     = $this->loginUser;
        $response = false;
        if (!empty($this->request->data)) {

pr($this->request->data);
exit;

            $data = json_decode($this->request->data);

pr($data);
exit;

            if ($this->Plan->save($data)) {
                $response = true;
            }
        }

exit;

        return new CakeResponse(array('body' => json_encode($response)));
    }

}
