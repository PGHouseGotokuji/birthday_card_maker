<?php
App::uses('AppModel', 'Model');
class Collaborator extends AppModel {

    var $name = 'Collaborator';

    public $validate = array(
    );

    /**
     * Collaboratorに紐づく画像を保存
     *
     * @access pubilc
     * @param  int     $photoId
     * @param  string  $img_file_base64
     * @return boolean
     */
    public function saveCollaboPhoto($photoId, $img_file_base64)
    {
        $binaryData = base64_decode($img_file_base64);
        if (file_put_contents(COLLABO_PHOTO_DIR . DS . $photoId . '.png', $binaryData) === false) {
            return false;
        }
        return true;
    }
}
