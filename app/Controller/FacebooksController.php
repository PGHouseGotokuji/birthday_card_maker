<?php
Class FacebooksController extends AppController
{
    public $name = 'Facebooks';

    public function beforeFileter()
    {
        parent::beforeFilter();
        $this->noLoginAction('getImgPass');
    }

    /**
     * facebook画像をbirthday_card_makerのURLで取得
     *
     * @access public
     */
    public function getImgPass()
    {
        $url = 'https://graph.facebook.com/' . $this->params['fb_id'] . '/picture';
        header("Content-type: image/jpeg; charset=UTF-8");
        echo readfile($url);
        exit;
    }
}
