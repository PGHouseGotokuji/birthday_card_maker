<?php
class AuthsController extends AppController 
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->noLoginAction('userLogout');
    }

    /**
     * user logout
     *
     * @access public
     * @return void
     */
    public function userLogout()
    {
        $this->Session->delete('auth.user');
        $this->Session->setFlash('ログアウトしました！', 'flash' . DS . 'success');
        return $this->redirect('/');
    }
}
