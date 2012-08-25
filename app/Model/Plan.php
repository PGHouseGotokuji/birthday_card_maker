<?php
App::uses('AppModel', 'Model');
class Plan extends AppModel {

    var $name = 'Plan';

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
