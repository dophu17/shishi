<?php
App::uses('SsmAdminController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class OttTaskLabelController extends SsmAdminController {
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

        $this->loadModel('SsmTaskLabel');
        $plan = $this->SsmTaskLabel->find('all');
        $this->set('list',$plan);
    }

    public function add(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================
        $this->layout = "ott";
        $this->loadModel('SsmTaskLabel');

        if($this->request->is('post')){
            if($this->SsmTaskLabel->save($this->request->data)){
                $this->Session->setFlash("タスクジャンルが作成されました。", 'success');
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash("更新は失敗です。再度試してください。", 'warning');
            }
        }
    }

    public function update(){
        //User and Site define ========================================================
        $site_id    = $this->site_id;
        $loginUser  = $this->loginUser;

        //End User and Site define ====================================================

        $this->layout = "ott";

        $this->loadModel('SsmTaskLabel');
        if(isset($this->request->params['named']['label_id'])){
            $label_id = $this->request->params['named']['label_id'];
        }else{
            $this->Session->setFlash(__('入力データは正しくありません。'),'warning');
            return $this->redirect($this->referer());
        }

        $tasklabel = $this->SsmTaskLabel->findById($label_id);

        if($this->request->is('post') || $this->request->is('put')){
            $this->SsmTaskLabel->id = $tasklabel['SsmTaskLabel']['id'];
            if ($this->SsmTaskLabel->save($this->request->data)) {
                $this->Session->setFlash(__('更新は成功です。'),'success');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->Session->setFlash(__('更新は失敗です。再度試してください。'), 'warning');
            }
        }else{
            $this->request->data = $tasklabel;
        }
    }

    public function delete(){
        $this->layout = "ott";
        $this->loadModel('SsmTaskLabel');
        if(isset($this->request->params['named']['label_id'])){
            $label_id = $this->request->params['named']['label_id'];
        }else{
            $this->Session->setFlash(__('入力データは正しくありません。'),'warning');
            return $this->redirect($this->referer());
        }

        $this->loadModel('SsmTaskLog');

        $task_log = $this->SsmTaskLog->find('first',array(
            'conditions'=>array(
                'label_id'=>$label_id
            )
        ));

        if($task_log){
            $this->Session->setFlash(__('このタスクジャンルは利用されているため、削除できません。'),'warning');
            return $this->redirect($this->referer());
        }

        if($this->SsmTaskLabel->deleteAll(array('SsmTaskLabel.id'=>$label_id))){
            $this->Session->setFlash(__('タスクジャンルが削除されました。'),'success');
        }else{
            $this->Session->setFlash(__('削除しても良いですか？'),'warning');
        }
        return $this->redirect($this->referer());
    }
}
?>