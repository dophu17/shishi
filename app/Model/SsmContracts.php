<?php
App::uses('AppModel', 'Model');

/**
 * SsmContracts Model
 */
class SsmContracts extends AppModel
{
    public $findMethods = array('expiredContracts' =>  true);

    public function _findExpiredContracts($state, $query, $results = array())
    {
        $query = <<<SQL
            SELECT Contract.id,
                   Contract.site_id,
                   Contract.start_day,
                   Contract.end_day,
                   Site.suspend
            FROM ssm_contracts Contract
            INNER JOIN ( 
                SELECT site_id,
                       max(end_day) AS max_end_day
                FROM ssm_contracts
                GROUP BY site_id
              ) MaxEndDayContract ON 
              Contract.site_id = MaxEndDayContract.site_id AND
              Contract.end_day = MaxEndDayContract.max_end_day
            INNER JOIN ssm_plans Plan ON
              Contract.plan_id = Plan.id
            INNER JOIN ssm_sites Site ON
              Contract.site_id = Site.id
            WHERE 
              Contract.end_day <= DATE(NOW()) AND
              Site.suspend = 0
SQL;

        $expiredContracts = $this->query($query);

        /*$expiredContracts = array_map(function($expiredContract) {
            return [
                'contract_id' => $expiredContract['Contract']['id'],
                'site_name'   => $expiredContract['Site']['site_name']
            ];
        }, $expiredContracts);*/
        return $expiredContracts;
    }
}
