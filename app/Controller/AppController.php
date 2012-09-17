<?php
App::uses('Util', 'Lib');
Class AppController extends Controller
{
    public $ext = '.html';

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
            if (!empty($this->action) && in_array($this->action, $actions)) {
                return true;
            } else {
                $this->Session->setFlash('再度ログインしてください。', 'flash' . DS . 'success');
                return $this->redirect('/');
            }
        }
    }

    /**
     * setFlashとredirectをまとめて行なう
     *
     * @access public
     * @params string $msg      setFlashに表示させるメッセージ
     * @params string $redirect リダイレクト先を指定
     * @params string $type     success or error
     */
    public function flashAndRedirect($msg = null, $redirect = '/', $type = 'error')
    {
        $this->Session->setFlash($msg, 'flash' . DS . $type);
        return $this->redirect($redirect);
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
