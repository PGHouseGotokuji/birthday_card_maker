<?php
class UsersController extends AppController 
{
//    public $helpers  = array('Common', 'DispUser');
    public $uses     = array('User', 'Collaborator', 'Plan');
    var $components  = array('Security');


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->requireAuth('frontAddUser');
        $this->Security->blackHoleCallback = 'error';

        $this->userLoginCheck('frontAddUser');
    }

    /**
     * ユーザー登録(パスワード)
     *
     * @access public
     */
/*
    public function frontAddUser() 
    {
        $this->User->recursive = 0;
        $user = $this->loginUser;
        if (!empty($user)) {;
            $user = $this->User->findById($user['User']['id']);
            if ($this->request->is('post')) {
                $data = $this->request->data;
                $this->User->id = $user['User']['id'];
                if ($this->User->save($data)) {
                    $this->Session->setFlash('ユーザ登録が完了しました。', 'flash' . DS . 'success');
                    $this->redirect('/');
                } else {
                    $this->Session->setFlash('入力内容に不備があります。ご確認ください。', 'flash' . DS . 'error');
                    $user = $data;
                }
            }
            $addMessage = '未登録の項目を入力してください';
            $this->set(compact('user', 'addMessage'));
        }

        $this->set('title_for_layout', TITLE . ' ユーザー登録');
        $this->set('title_for_page', TITLE . '　ユーザー登録');
    }
*/

    /**
     * ユーザー情報取得
     *
     * @access public
     */
    public function getUser() 
    {
        $user = $this->loginUser;
/*
        $plan = $this->Plan->findByFromId($user['User']['id']);
        if (!empty($plan)) {
            $data = $user + $plan;
        } else {
            $data = $user;
        }
*/
        return new CakeResponse(array('body' => json_encode($user)));

//        $this->set(compact('user'));
//        $this->set('title_for_layout', TITLE . ' マイページ');
//        $this->set('title_for_page', TITLE . '　マイページ');
    }
}
