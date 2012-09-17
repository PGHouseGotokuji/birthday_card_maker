<?php
App::uses('Model', 'Model');
class AppModel extends Model {

    /**
     * トランザクション（開始）
     *
     * @access public
     * @return object
     */
    public function begin()
    {
        return $this->getDataSource()->begin($this);
    }

    /**
     * トランザクション（コミット）
     *
     * @access public
     * @return object
     */
    public function commit()
    {
        return $this->getDataSource()->commit($this);
    }

    /**
     * トランザクション（ロールバック）
     *
     * @access public
     * @return object
     */
    public function rollback()
    {
        return $this->getDataSource()->rollback($this);
    }

    /**
     * 画像を保存
     *
     * @access pubilc
     * @param  int     $photoId
     * @param  string  $dirPass
     * @param  string  $img_file_base64
     * @return boolean
     */
    public function savePhoto($photoId, $dirPass, $img_file_base64)
    {
        $binaryData = base64_decode($img_file_base64);
        if (file_put_contents($dirPass . DS . $photoId . '.png', $binaryData) === false) {
            return false;
        }
        return true;
    }
}
