<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
//ob_start();
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package     app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class SsmAdminController extends Controller {

    //Use check auth
    public $components  = array('Session','SsmAuth');
    public $helpers     = array('Shishimai', 'ExpiredContracts');

    public $site_id;

    public $view_id;

    public $loginUser   = null;

    public $listSiteID  = array();

    public function beforeFilter(){
        parent::beforeFilter();
        $this->loadModel('SsmSite');
        $this->loginUser = $this->SsmAuth->getLoginUser();
        $this->initCheckUser();

        if ($this->user_role == 'admin') {
            if (
                $this->request->is('get')   &&
                !$this->request->is('ajax')
            ) {
                $this->loadModel('SsmContracts');
                $expiredContracts = $this->SsmContracts->find('expiredContracts');
                $this->set('expiredContracts', $expiredContracts);
            }
        }
    }

    public function beforeRender(){
        parent::beforeRender();

        $this->set('site_id',$this->site_id);
        $this->set('listSiteIDName',$this->listSiteIDName);
        $this->set('listSiteIDInfo',$this->listSiteIDInfo);
        $this->set('user_role',$this->user_role);
        $this->set('allow_action',$this->allow_action);
    }

    //Check user login and do ...
    public function initCheckUser(){
        if(!$this->loginUser){
            if(($this->params['controller'] !='ottuser' && ($this->params['action'] != 'login' && $this->params['action'] != 'update')))
            $this->redirect(array("controller" => "OttUser", "action" => "login"));
        }else{
            $this->user_role = $this->loginUser['SsmUser']['role'];
            $this->allow_action = $this->SsmAuth->ui_action[$this->user_role];
            $this->setCurrentAction();
            $this->responseDontHavePermission($this->checkPermission($this->loginUser));
            $this->setSiteList();
            $this->responseSiteListEmpty();
            $this->setActiveSite();
            $this->responseSiteNotInList();
        }
    }

    //Set current action
    public function setCurrentAction(){
        $this->currentController    = strtolower($this->params['controller']);
        $this->currentMethod        = $this->params['action'];
        $this->currentControllerMethod  = $this->currentController."__".$this->currentMethod;

        //echo $this->currentControllerMethod;exit;
    }

    //Check login user have permission
    public function checkPermission(){

        //All action required login
        //All controller param and action param must be in lower case
        $action_required_permission = $this->SsmAuth->action_required;

        //Permisson
        $admin_allow_action     = $this->SsmAuth->admin_allow;
        $partner_allow_action   = $this->SsmAuth->partner_allow;
        $client_allow_action    = $this->SsmAuth->client_allow;
        $worker_allow_action    = $this->SsmAuth->worker_allow;

        if(in_array($this->currentControllerMethod,$action_required_permission) ){
            if($this->user_role == 'admin'){
                $login_allow = $admin_allow_action;
            }elseif($this->user_role == 'partner'){
                $login_allow = $partner_allow_action;
            }elseif($this->user_role == 'worker'){
                $login_allow = $worker_allow_action;
            }elseif($this->user_role == 'client'){
                $login_allow = $client_allow_action;
            }else{
                return false;
            }

            if(!in_array($this->currentControllerMethod, $login_allow)){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    //Response with result of check permission
    public function responseDontHavePermission($allow){
        if(!$allow){
            //echo "このアカウントはこの機能にアクセスする権限がありません";exit;
            $this->renderError('このアカウントはこの機能にアクセスする権限がありません');
        }
    }

    //set list site of user
    public function setSiteList(){
        if(!empty($this->loginUser)){
            $siteList = $this->SsmSite->getListSiteIDOfUser($this->loginUser['SsmUser']['role'],$this->loginUser['SsmUser']['id']);
            $this->listSiteID       = $siteList['arr_id'];
            $this->listSiteIDName   = $siteList['arr_id_name'];
            $this->listSiteIDInfo   = $siteList['arr_site_info'];
        }
    }

    //respone when site list empty
    public function responseSiteListEmpty(){
        if(!count($this->listSiteID)){
            if($this->user_role == 'admin'){
                $this->redirect(array("controller" => "OttClient", "action" => "index"));
            }elseif($this->user_role == 'partner'){
                $this->redirect(array("controller" => "OttUser", "action" => "login"));
            }else{
                $this->redirect(array("controller" => "OttUser", "action" => "login"));
            }
        }
    }

    //set active site
    public function setActiveSite(){
        if($this->currentControllerMethod == 'otthome__index'){
            if(isset($this->request->query['site_id'])){
                $this->site_id = $this->SsmAuth->setActiveSite($this->request->query['site_id']);
            }elseif(isset($this->request->params['named']['site_id'])){
                $this->site_id = $this->SsmAuth->setActiveSite($this->request->params['named']['site_id']);
            }elseif($this->SsmAuth->getActiveSite()){
                $this->site_id = $this->SsmAuth->getActiveSite();
            }else{
                if($this->loginUser['SsmUser']['role'] == 'admin'){
                    if(in_array(30,$this->listSiteID)){
                        $active_site_id = 30;
                    }
                }else{
                    $active_site_id = $this->listSiteID[0];
                }
                if ( $this->request->params['named']['report_id'] ) {
                    $active_site_id = $this->getReportInfo($this->request->params['named']['report_id']);
                    if ( !$active_site_id ) {
                        if(isset($this->request->query['site_id'])){
                            $this->site_id = $this->SsmAuth->setActiveSite($this->request->query['site_id']);
                        }elseif(isset($this->request->params['named']['site_id'])){
                            $this->site_id = $this->SsmAuth->setActiveSite($this->request->params['named']['site_id']);
                        }elseif($this->SsmAuth->getActiveSite()){
                            $this->site_id = $this->SsmAuth->getActiveSite();
                        }else{
                            $this->site_id = $this->SsmAuth->setActiveSite($this->listSiteID[0]);
                        }
                    } else {
                        $this->site_id = $this->SsmAuth->setActiveSite($active_site_id);
                    }
                }
            }
        }elseif($this->currentController == 'ssmclient'){
            /*if($this->SsmAuth->getActiveSite()){
                $this->site_id = $this->SsmAuth->getActiveSite();
            }else{
                $this->site_id = $this->SsmAuth->setActiveSite($this->listSiteID[0]);
            }*/
            if ( $this->request->params['named']['report_id'] ) {
                $active_site_id = $this->getReportInfo($this->request->params['named']['report_id']);
                if ( !$active_site_id ) {
                    if(isset($this->request->query['site_id'])){
                        $this->site_id = $this->SsmAuth->setActiveSite($this->request->query['site_id']);
                    }elseif(isset($this->request->params['named']['site_id'])){
                        $this->site_id = $this->SsmAuth->setActiveSite($this->request->params['named']['site_id']);
                    }elseif($this->SsmAuth->getActiveSite()){
                        $this->site_id = $this->SsmAuth->getActiveSite();
                    }else{
                        $this->site_id = $this->SsmAuth->setActiveSite($this->listSiteID[0]);
                    }
                } else {
                    $this->site_id = $this->SsmAuth->setActiveSite($active_site_id);
                }
            } else {
                if(isset($this->request->query['site_id'])){
                    $this->site_id = $this->SsmAuth->setActiveSite($this->request->query['site_id']);
                }elseif(isset($this->request->params['named']['site_id'])){
                    $this->site_id = $this->SsmAuth->setActiveSite($this->request->params['named']['site_id']);
                }elseif($this->SsmAuth->getActiveSite()){
                    $this->site_id = $this->SsmAuth->getActiveSite();
                }else{
                    $this->site_id = $this->SsmAuth->setActiveSite($this->listSiteID[0]);
                }
            }
        }else{
            if ( $this->request->params['named']['report_id'] ) {
                $active_site_id = $this->getReportInfo($this->request->params['named']['report_id']);
                if ( !$active_site_id ) {
                    if(isset($this->request->query['site_id'])){
                        $this->site_id = $this->SsmAuth->setActiveSite($this->request->query['site_id']);
                    }elseif(isset($this->request->params['named']['site_id'])){
                        $this->site_id = $this->SsmAuth->setActiveSite($this->request->params['named']['site_id']);
                    }elseif($this->SsmAuth->getActiveSite()){
                        $this->site_id = $this->SsmAuth->getActiveSite();
                    }else{
                        $this->site_id = $this->SsmAuth->setActiveSite($this->listSiteID[0]);
                    }
                } else {
                    $this->site_id = $this->SsmAuth->setActiveSite($active_site_id);
                }
            } else {
                if(isset($this->request->query['site_id'])){
                    $this->site_id = $this->SsmAuth->setActiveSite($this->request->query['site_id']);
                }elseif(isset($this->request->params['named']['site_id'])){
                    $this->site_id = $this->SsmAuth->setActiveSite($this->request->params['named']['site_id']);
                }elseif($this->SsmAuth->getActiveSite()){
                    $this->site_id = $this->SsmAuth->getActiveSite();
                }else{
                    $this->site_id = $this->SsmAuth->setActiveSite($this->listSiteID[0]);
                }
            }
        }
    }

    //response when site active site not in site list
    public function responseSiteNotInList(){
        if($this->user_role == 'client' || $this->user_role == 'partner'){
            if(!in_array($this->site_id,$this->listSiteID)){
                $this->renderError('このアカウントはこの機能にアクセスする権限がありません');
            }
        }
    }

    //render view error
    public function renderError($msg){
        $this->Session->setFlash($msg, 'warning');
        $this->render('/OttError/index','ott_blank');
    }

    public function getReportInfo($reportId) {
        $this->loadModel('SsmReport');
        $reportInfo = $this->SsmReport->find('first', array(
            'conditions' => array('id' => $reportId)
        ));

        if ( $reportInfo ) {
            return $reportInfo['SsmReport']['site_id'];
        }

        return '';
    }
}