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

    /**
     * 画像保存
     *
     * @access public
     */
    public function uploadPhoto() 
    {
        $planId = $this->params['planId'];
        $plan   = $this->Plan->findById($planId);
        if (empty($plan)) {
            $this->log('存在しないプランIDを叩かれました。planId: ' . $planId . ', ' . $this->name . ', ' . $this->action . ', ' . __LINE__, 'warn');
            $this->Session->setFlash('誕生日プランを作成してください。', 'flash' . DS . 'error');
            return new CakeResponse(array('body' => json_encode(false)));
        }

        if ($this->request->is('post')) {
            $data = $this->request->data;
            try {            
                $this->Plan->begin();  /*** トランザクション開始 ***/
                // Plan
                $this->Plan->id = $planId;
                if (!$this->Plan->saveField('photo_id', $planId, false)) {
                    throw new Exception();
                }
                // put photo_data
                if (!$this->Plan->savePhoto($planId, PLAN_PHOTO_DIR, $data['img_file'])) {
                    throw new Exception();
                }
                $this->Plan->commit(); /*** トランザクション終了 ***/
            } catch (Exception $e) {
                $this->Plan->rollback();
                $this->Session->setFlash('画像保存時に問題が発生しました。再度お試しください。', 'flash' . DS . 'error');
                return new CakeResponse(array('body' => json_encode(false)));
            }
            $this->Session->setFlash('プラン画像を保存しました。', 'flash' . DS . 'success');
            return new CakeResponse(array('body' => json_encode(true)));
        }

        return new CakeResponse(array('body' => json_encode(false)));
    }
}
