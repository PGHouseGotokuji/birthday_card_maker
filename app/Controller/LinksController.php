<?php
class LinksController extends AppController {

    public $name = 'Links';
    public $uses = array('User');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * fblogin
     *
     * @access public
     * @return void
     */
    public function fbLogin()
    {
        // Facebook認証前
        if (empty($_GET['code'])) {
            $state = sha1(uniqid(mt_rand(), true));
            $this->Session->write('fblogin.state', $state);
            $this->Session->write('fblogin.ref', '/mypage');
            $params = array(
                'client_id'    => APP_ID,
                'redirect_uri' => SITE_URL . '/fblogin',
                'state'        =>  $state
            );
            $url = 'https://www.facebook.com/dialog/oauth?' . http_build_query($params);
            $this->redirect($url);
            exit;

        // Facebook認証後
        } else {
            $referer = $this->Session->read('fblogin.ref');
            if ($this->Session->read('fblogin.state') != $_GET['state']) {
                $this->Session->setFlash('処理中に問題が発生しました。', 'flash' . DS . 'error');
                $this->redirect($referer);
            }
            $this->Session->delete('fblogin');

            $params = array(
                'client_id'     => APP_ID,
                'client_secret' => APP_SECRET,
                'code'          => $_GET['code'],
                'redirect_uri'  => SITE_URL . '/fblogin'
            );
            $url = 'https://graph.facebook.com/oauth/access_token?' . http_build_query($params);
            $body = file_get_contents($url);

            parse_str($body);
            $url = 'https://graph.facebook.com/me?access_token=' . $access_token . '&fields=username,gender,picture';
            $me = json_decode(file_get_contents($url));

            $user = $this->User->find('first', array(
                'conditions' => array('fb_id' => $me->id)
            ));
            if (empty($user)) {
                $data['User']['username']        = $me->username;
                $data['User']['register_status'] = 1;
                if ($me->gender == 'male') {
                    $data['User']['gender']      = 1;
                } else {
                    $data['User']['gender']      = 2;
                }
                $data['User']['fb_id']           = $me->id;
                $data['User']['fb_picture']      = $me->picture;

                $this->User->create();
                if (!$this->User->save($data)) {

// TODO バリデーションチェックでひっかかっているらしいので確認すること！
// TODO あと、flashメッセージが表示されない…
pr($this->User->validationErrors);
pr('hoeghoewhpgejo');
exit;
                    $this->Session->setFlash('処理中に問題が発生しました。', 'flash' . DS . 'error');
                    $this->redirect($referer);
                }
                $user = $this->User->findByFbId($data['User']['fb_id']);
            }

            $loginUser['User']['id']              = $user['User']['id'];
            $loginUser['User']['username']        = $user['User']['username'];
//            $loginUser['User']['register_status'] = $user['User']['register_status'];
//            $loginUser['User']['gender']          = $user['User']['gender'];
            $loginUser['User']['fb_picture']      = $user['User']['fb_picture'];
            $loginUser['User']['access_token']    = $access_token;
            $this->Session->write('auth.user', $loginUser);

/*
            if (empty($user['User']['password'])) {
                $this->redirect(array('controller' => 'users', 'action' => 'frontAddUser'));
            }
            $this->Session->setFlash('ログインしました！', 'flash' . DS . 'success');
*/
            $this->redirect($referer);
        }
    }
}
