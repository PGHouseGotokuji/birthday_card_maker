<?php
class UsersController extends AppController 
{
    public $helpers  = array();
    public $uses     = array('User', 'Collaborator', 'Plan');
    var $components  = array('Security');

    public function beforeFilter()
    {
        parent::beforeFilter();
//        $this->Security->requireAuth('frontAddUser');
        $this->Security->blackHoleCallback = 'error';
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
        return new CakeResponse(array('body' => json_encode($user)));
    }


    public function getUserById(){
        $userId = $this->params['userId'];
        return new CakeResponse(array('body' => json_encode($this->User->findById($userId))));
    }

    /**
     * ユーザーの友達情報取得
     *
     * @access public
     */
    public function getFriends() 
    {
        $user = $this->loginUser;

        $url = 'https://graph.facebook.com/me/friends?access_token=' . $user['User']['access_token'];
        $fbFriends = json_decode(file_get_contents($url));

        foreach ($fbFriends->data as $key => $friend) {
            $friend->fb_picture = 'https://graph.facebook.com/' . $friend->id . DS . 'picture';
            $friends['Friend'][] = $friend;
        }

        return new CakeResponse(array('body' => json_encode($friends)));
    }

    /**
     * マイページ
     *
     * @access public
     */
    public function mypage() 
    {
        $user = $this->loginUser;
        $planId = $this->Plan->field('id', array('from_id' => $user['User']['id']));
        $this->set(compact('planId'));
        $this->set('title_for_layout', 'マイページ');
    }
}
