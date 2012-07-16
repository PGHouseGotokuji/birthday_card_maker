<?php
class AuthsController extends AppController {

    var $components = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->requireAuth('');
        $this->Security->blackHoleCallback = 'error';
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
        $this->Session->setFlash('ログアウトしました', 'flash' . DS . 'success');
        $this->redirect('/');
    }
}
