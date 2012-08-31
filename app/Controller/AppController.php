<?php
Class AppController extends Controller
{
    var $ext = '.html';

    public function beforeFilter()
    {
        $this->loginUser = null;

        // ユーザーがログインしていたら配列に格納
        if ($this->Session->check('auth.user')) {
            $this->loginUser = $loginUser = $this->Session->read('auth.user');
            $this->set(compact('loginUser'));
        }
    }

    /**
     * ログイン不要URL
     *
     * @access public
     */
    public function noLoginAction()
    {
        $actions = func_get_args();
        if (!empty($this->action) && in_array($this->action, $actions)) || $actions[0] == '*') {
            $this->Session->setFlash('セッションがタイムアウトしました。再度ログインしてください。', 'flash' . DS . 'success');
            $this->redirect('/');
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
