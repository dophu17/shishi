<?php
App::uses('SsmAdminController', 'Controller');
App::uses('AppHelper', 'View/Helper');

class OttSettingController extends SsmAdminController{
    public $helpers     = array('Shishimai','Html', 'Session');
    public $components  = array('Session','SsmAuth', 'Cshishimai', 'OttClient');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function beforeRender(){
        parent::beforeRender();
    }

    public function index(){
        $this->layout = 'ott';

        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        $siteInfo = $this->SsmSite->getSiteInfo($site_id);
        $this->set('siteInfo',$siteInfo);

        if(!$siteInfo){
            $this->renderError('サイトは存在しません。再度確認してください。');
            return;
        }

        $view_id = $siteInfo['ga_view_id'];
        $this->set('view_id',$view_id);
        //End User and Site define ====================================================

        $this->loadModel('SsmSetting');

        $setting = $this->SsmSetting->find('all');
        $this->set('list',$setting);
    }


    public function update(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";

        $this->loadModel('SsmSetting');
        if(isset($this->request->params['named']['setting_id'])){
            $setting_id = $this->request->params['named']['setting_id'];
        }else{
            $this->Session->setFlash(__('プランIDが無効です。'),'warning');
            return $this->redirect($this->referer());
        }

        $setting = $this->SsmSetting->findById($setting_id);

        if($this->request->is('post') || $this->request->is('put')){

            $this->SsmSetting->id = $setting['SsmSetting']['id'];
            if ($this->SsmSetting->save($this->request->data)) {
                $this->Session->setFlash(__('更新は成功です。'),'success');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
            }

        }else{
            $this->request->data = $setting;
        }

        $this->set('setting',$setting);
    }


}

?>