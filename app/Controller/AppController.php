<?php
Class AppController extends Controller
{
    var $ext = '.html';
//    public $noLoginUrls = array('/', '/fblogin', '/plan/10/collaborator');

    public function beforeFilter()
    {
        $this->loginUser = null;

        // ユーザーがログインしていたら配列に格納
        if ($this->Session->check('auth.user')) {
            $this->loginUser = $loginUser = $this->Session->read('auth.user');
            $this->set(compact('loginUser'));
/*
        } else { 
            if (array_search($this->request->here, $this->noLoginUrls) === false) { 
                $this->Session->setFlash('ログインし直してください。', 'flash' . DS . 'success');
                $this->redirect('/');
            }
*/
        }
    }

    /**
     * エラー画面
     *
     * @access public
     */
    public function error()
    {
       $this->set('title_for_layout', 'エラー ' . TITLE);
       $this->set('title_for_page', 'エラー ' . TITLE);
       $this->render('/Errors/error');
    }
}
