<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
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
