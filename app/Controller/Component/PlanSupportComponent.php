<?php
class PlanSupportComponent extends Component
{
    /**
     * from_idのユーザと一緒に取得する
     *
     */
    function findWithFromUser($Plan, $planId)
    {
        $joins = array(
            array(
                'type'  => 'INNER',
                'table' => '`users` `User`',
                'conditions' => '`User`.`id`=`Plan`.`from_id`',
            )
        );
        $plan = $Plan->find('first', array(
            'fields' => array('Plan.*', 'User.*'),
            'joins' => $joins,
            'conditions' => array(
                '`Plan`.`id`' => $planId
            )
        ));

        return $plan;
    }

    /**
     * $plan情報を元にしてTOユーザの情報を取得する
     *
     */
    function getToUser($access_token, $plan)
    {
        $url = 'https://graph.facebook.com/' . $plan['Plan']['to_id'] . '?access_token=' . $access_token . '&fields=username,picture';
        return json_decode(file_get_contents($url));
    }


}
