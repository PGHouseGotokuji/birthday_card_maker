<?php
Class AppController extends Controller
{
    var $ext = '.html';

    public $loginUser = null;

    public function beforeFilter()
    {
        // ユーザーがログインしていたらloginUserをセット
        if ($this->Session->check('auth.user')) {
            $this->loginUser = $loginUser = $this->Session->read('auth.user');
            $this->set(compact('loginUser'));
        }
    }

    /**
     * ログイン不要URLか判定する
     *
     * @access public
     */
    public function noLoginAction()
    {
        $actions = func_get_args();
        if (!$this->Session->check('auth.user')) {
            if (!empty($this->action) && in_array($this->action, $actions) || $actions[0] == '*') {
                return true;
            } else {
                $this->Session->setFlash('再度ログインしてください。', 'flash' . DS . 'success');
                return $this->redirect('/');
            }
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
       $this->set('title_for_page',   'エラー ' . TITLE);
       $this->render('/Errors/error');
    }
}
