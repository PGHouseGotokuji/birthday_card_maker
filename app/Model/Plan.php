<?php
App::uses('AppModel', 'Model');
class Plan extends AppModel {

    var $name = 'Plan';

    public $validate = array(
    );

    public function beforeSave() {
        return true;
    }
}
