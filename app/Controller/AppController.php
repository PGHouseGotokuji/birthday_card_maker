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
//        } else { 
//            $this->Session->setFlash('セッションがタイムアウトしました。', 'flash' . DS . 'success');
//            $this->redirect('/');
        }
        $this->set(compact('loginUser'));
    }

    /**
     * ユーザーログインチェック
     *
     * @access public
     * @return void
     */
    public function userLoginCheck()
    {
        $actions = func_get_args();
        foreach ($actions as $action) {
            if ($this->action == $action && !$this->Session->check('auth.user')) {
                // TODO ここ、ログイン画面できたらログイン画面へ飛ばすようにする
                $this->redirect('/');
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
//       $this->set('title_for_layout', 'エラー ' . TITLE);
//       $this->set('title_for_page', 'エラー ' . TITLE);
       $this->render('/Errors/error');
    }
}
