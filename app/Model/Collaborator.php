<?php
App::uses('AppModel', 'Model');
class Collaborator extends AppModel {

    var $name = 'Collaborator';

    public $validate = array(
    );

    public function beforeSave() {
        return true;
    }
}
