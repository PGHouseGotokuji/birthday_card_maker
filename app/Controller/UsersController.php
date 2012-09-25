<?php
class UsersController extends AppController 
{
    public $helpers  = array();
    public $uses     = array('User', 'Collaborator', 'Plan');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->noLoginAction();
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
     * ログインユーザに紐づいたユーザー情報取得
     *
     * @access public
     */
    public function getUser() 
    {
        $user = $this->loginUser;
        return new CakeResponse(array('body' => json_encode($user)));
    }

    /**
     * 単純にユーザー情報を取得
     *
     * @access public
     */
    public function getUserById()
    {
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
        $debug = Configure::read('debug');
        if ($debug > 0) {
            $url = 'https://graph.facebook.com/me/friends?access_token=' . $user['User']['access_token'];
            $fbFriendsJson = file_get_contents($url);
        } else {
            $url = 'https://graph.facebook.com/fql?q=' . urlencode('SELECT substr(birthday_date, 0, 2), uid, name, birthday_date FROM user WHERE uid in (SELECT uid2 FROM friend WHERE uid1=me()) AND (substr(birthday_date, 0, 2) == "' . date('m') . '" OR substr(birthday_date, 0, 2) == "' . date('m', strtotime('+1 month')) . '")') . '&access_token=' . $user['User']['access_token'];
            $fbFriendsJson = file_get_contents($url);
            // そのままjson_decodeするとfb_idが整数型で扱えないので、あらかじめ文字列に
            $fbFriendsJson = preg_replace('/"uid":([0-9]+)/', '"uid":"${1}"', $fbFriendsJson);
        }

        $fbFriends = json_decode($fbFriendsJson);
        // 整形
        foreach ($fbFriends->data as $key => $friend) {
            if ($debug == 0) {
                $friend->id = $friend->uid;
                unset($friend->uid);
            }
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
