<?php
Class PagesController extends AppController
{
    public $name = 'Pages';

    public function beforeFileter()
    {
        parent::beforeFilter();
        $this->noLoginAction('index');
    }

    /**
     * トップページ
     *
     * @access public
     */
    public function index()
    {
        $this->set('title_for_layout', TITLE . '｜' . SUB_TITLE);
        $this->set('title_for_page', TITLE .   '｜' . SUB_TITLE);
    }
}
