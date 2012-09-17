<?php
App::uses('AppModel', 'Model');
class Plan extends AppModel {

    var $name = 'Plan';

    // plan_status
    const BEFORE_POST_FBTIMELINE   = 0;
    const BEFORE_JOIN_COLLABORATOR = 1;
    const BEFORE_MAKE_CARD         = 2; // 今ここの実装中
    const BEFORE_POST_CARD         = 3;
    const COMPLETE                 = 4;
    const PLAN_STATUS_ERROR        = 9;

    public $validate = array(
    );

    /**
     * planに紐づく画像を保存
     *
     * @access pubilc
     * @return boolean
     */
    public function savePlanPhoto($planId, $data)
    {
        $fileNameParts  = explode('.', $data['Plan']['plan_photo']['name']);
        $uploadFileName = $planId . '.' . $fileNameParts[1];
        if (move_uploaded_file($data['Plan']['plan_photo']['tmp_name'], PLAN_PHOTO_DIR . DS . $uploadFileName)) {
            return true;
        }
        return false;
    }
}
