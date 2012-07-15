<?php
App::uses('CardCreator', 'Lib');
class PostsController extends AppController 
{
    public $uses     = array('User', 'Plan', 'Collaborator');
    var $components  = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->blackHoleCallback = 'error';

        $this->userLoginCheck('postCard');
    }

    /**
     * 誕生日プラン情報取得
     *
     * @access public
     */
    public function postCard() 
    {
        $user = $this->loginUser;
        $plan = $this->Plan->findByFromId($user['User']['id']);

// TODO 
        $creator = new CardCreator();
        $creator->setBackground(WWW_ROOT . 'img/test.jpg');
        $img = $creator->createImage();
        responseJpeg($img);
    }
}
