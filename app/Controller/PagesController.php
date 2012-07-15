<?php
Class PagesController extends AppController
{
    public $name = 'Pages';
    public $uses = array('User');

    public function beforeFileter()
    {
        parent::beforeFilter();
    }

    /**
     * トップページ
     *
     * @access public
     */
    public function index()
    {
        $this->set('title_for_layout', TITLE);
        $this->set('title_for_page', TITLE);
    }
}
