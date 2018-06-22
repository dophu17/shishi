<?php
App::uses('SsmAdminController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class OttPlanController extends SsmAdminController {
    public $helpers     = array('Html', 'Form', 'Session');
    public $components  = array('Session','SsmAuth');

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

        $this->loadModel('SsmPlan');
        $plan = $this->SsmPlan->find('all');
        $this->set('list',$plan);
    }

    public function add(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================
        $this->layout = "ott";
        $this->loadModel('SsmPlan');

        if($this->request->is('post')){
            if($this->SsmPlan->save($this->request->data)){
                $this->Session->setFlash("プランが作成されました。", 'success');
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash("エラーが発生しました。もう一度お試しください。", 'warning');
            }
        }
    }

    public function update(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";

        $this->loadModel('SsmPlan');
        if(isset($this->request->params['named']['plan_id'])){
            $plan_id = $this->request->params['named']['plan_id'];
        }else{
            $this->Session->setFlash(__('プランIDが無効です。'),'warning');
            return $this->redirect($this->referer());
        }

        $plan = $this->SsmPlan->findById($plan_id);

        if($this->request->is('post') || $this->request->is('put')){
            $this->SsmPlan->id = $plan['SsmPlan']['id'];
            if ($this->SsmPlan->save($this->request->data)) {
                $this->Session->setFlash(__('更新は成功です。'),'success');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
            }
        }else{
            $this->request->data = $plan;
        }
    }

    function delete(){
        $this->layout = "ott";
        $this->loadModel('SsmPlan');
        if(isset($this->request->params['named']['plan_id'])){
            $plan_id = $this->request->params['named']['plan_id'];
        }else{
            $this->Session->setFlash(__('プランIDが無効です。'),'warning');
            return $this->redirect($this->referer());
        }

        $this->loadModel('SsmContract');

        $contract = $this->SsmContract->find('first',array(
            'conditions'=>array(
                'plan_id'=>$plan_id
            )
        ));

        if($contract){
            $this->Session->setFlash(__('このプランが設定されているクライアント様がいるため、削除できません。'),'warning');
            return $this->redirect($this->referer());
        }

        if($this->SsmPlan->deleteAll(array('SsmPlan.id'=>$plan_id))){
            $this->Session->setFlash(__('プランを正常に削除しました！'),'success');
        }else{
            $this->Session->setFlash(__('削除しても良いですか？'),'warning');
        }
        return $this->redirect($this->referer());
    }
}
?>