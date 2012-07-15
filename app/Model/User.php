<?php
App::uses('AppModel', 'Model');
class User extends AppModel {

    var $name = 'User';

    // register_status
    const user_interim    = 0;
    const user_registered = 1;
    const user_withdrew   = 9;

    // user_gender
    const user_gender_none  = 0;
    const user_gender_man   = 1;
    const user_gender_woman = 2;

    public static $registerStatus = array(
        self::user_interim    => '仮登録',
        self::user_registered => '本登録',
        self::user_withdrew   => '退会'
    );

    public static $genderList = array(
        self::user_gender_man   => '男性',
        self::user_gender_woman => '女性'
    );

    public $validate = array(
// TODO 半角英数字以外にハイフン、アンダースコアも使えるようにしたい
        'username' => array(
            array('rule' => 'notEmpty', 'message' => '▲ユーザー名を入力してください'),
            array('rule' => array('maxLength', 20), 'message' => '▲ユーザー名は20文字以内にしてください'),
            array('rule' => array('checkExistedUsername'), 'message' => '▲既に登録されています。別のユーザー名で登録してください。'),
//            array('rule' => 'alphaNumeric', 'message' => '▲ユーザー名は英数字で入力してください')
        ),
        'passwd_old' => array(
            array('rule' => 'notEmpty', 'message' => '▲旧パスワードを入力してください'),
            array('rule' => array('checkOldPassword'), 'message' => '▲古いパスワードが一致していません')
        ),
        'passwd_first' => array(
            array('rule' => 'notEmpty', 'message' => '▲パスワードを入力してください'),
            array('rule' => array('maxLength', 20), 'message' => '▲パスワードは20文字以内にしてください')
        ),
        'passwd_confirm' => array(
            array('rule' => 'notEmpty', 'message' => '▲パスワード(確認用)を入力してください'),
            array('rule' => array('maxLength', 20), 'message' => '▲パスワード(確認用)は20文字以内にしてください'),
            array('rule' => array('confirmSamePassword'), 'message' => '▲新パスワード(確認用)と新パスワードが一致しません')
        ),
        'gender' => array(
            array('rule' => 'notEmpty', 'message' => '▲性別を入力してください')
        )
    );

    public function beforeSave() {
        if ($this->name == 'User' && !empty($this->data['User']['passwd_confirm'])) {
            // パスワードのhash化
            $this->data['User']['password'] = Security::hash($this->data['User']['passwd_confirm'], 'sha256', true);
        }
        return true;
    }


    /**
     * 既に登録されているユーザ名を確認
     *
     * @access public
     * @param  array   $this->data
     * @return boolean
     */
    public function checkExistedUsername()
    {
        $user = $this->findByUsername($this->data['User']['username']);
        if (empty($user) || $this->data['User']['username'] == $user['User']['username']) {
            return true;
        }
        return false;
    }

    /**
     * 現在のパスワード確認
     *
     * @access public
     * @param  array   $this->data
     * @return boolean
     */
    public function checkOldPassword()
    {
        $user = $this->findById($this->data['User']['id']);
        $oldPassword = Security::hash($this->data['User']['passwd_old'], 'sha256', true);
        if ($oldPassword == $user['User']['password']) {
            return true;
        }
        return false;
    }

    /**
     * 確認用パスワードと新パスワードが一致しているか確認
     *
     * @access public
     * @param  array   $this->data
     * @return boolean
     */
    public function confirmSamePassword()
    {
        if ($this->data['User']['passwd_first'] == $this->data['User']['passwd_confirm']) {
            return true;
        }
        return false;
    }
}
