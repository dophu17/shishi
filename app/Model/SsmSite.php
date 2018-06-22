<?php
App::uses('SsmModel', 'Model');

class SsmSite extends SsmModel {

	//Get site info
	function getSiteInfo($site_id){

		$site = $this->find('first',array(
			'conditions'=>array(
				'id'=>$site_id
			)
		));

		if($site){
			if($site['SsmSite']['report_range'] != ""){
				$site['SsmSite']['report_range'] = unserialize($site['SsmSite']['report_range']);
			}
			if($site['SsmSite']['report_kpi'] != ""){
				$site['SsmSite']['report_kpi'] = unserialize($site['SsmSite']['report_kpi']);
			}else{
				$site['SsmSite']['report_kpi'] = array();
			}
			if($site['SsmSite']['report_kpi_view2'] != ""){
				$site['SsmSite']['report_kpi_view2'] = unserialize($site['SsmSite']['report_kpi_view2']);
			}else{
				$site['SsmSite']['report_kpi_view2'] = array();
			}

			return $site['SsmSite'];
		}else{
			return false;
		}
	}


	function getListSiteIDOfUser($role,$user_id){

		$arr = array();
		$arr_site = array();
		$arr_site_info = array();
		if($role == 'admin'){
			$sites = $this->find('all',array(
				'fields'=>array(
					'id','site_name','show_on_menu'
				)
			));

			if($sites){
				foreach ($sites as $site) {
					$arr[] = $site['SsmSite']['id'];
					$arr_site[$site['SsmSite']['id']] = $site['SsmSite']['site_name'];
					$arr_site_info[$site['SsmSite']['id']] = $site['SsmSite'];
				}
			}
		}else{
			$sites = $this->find('all',array(
				'joins' => array(
			        array(
			            'table' => 'ssm_site_users',
			            'alias' => 'SsmSiteUser',
			            'type' => 'INNER',
			            'conditions' => array(
			                'SsmSiteUser.site_id = SsmSite.id'
			            )
			        )
			    ),
				'fields'=>array(
					'SsmSite.id','SsmSite.site_name'
				),
				'conditions'=>array(
					'SsmSiteUser.user_id'=>$user_id
				)
			));

			if($sites){
				foreach ($sites as $site) {
					$arr[] = $site['SsmSite']['id'];
					$arr_site[$site['SsmSite']['id']] = $site['SsmSite']['site_name'];
					$arr_site_info[$site['SsmSite']['id']] = $site['SsmSite'];
				}
			}
		}

		return array(
			'arr_id'=>$arr,
			'arr_id_name'=>$arr_site,
			'arr_site_info'=>$arr_site_info
		);
	}
}
