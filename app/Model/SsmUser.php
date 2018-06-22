<?php
App::uses('AppModel', 'Model');

class SsmUser extends AppModel {

	var $component = array('SsmAuth');
	public $validate = array(
	    'username' => array(
	        'isUnique' => array(
	            'rule' => 'isUnique',
	            'message' => 'メールアドレスはすでに登録されています。',
	            'last' => true,
	        ),
	        'notEmpty' => array(
	            'rule' => 'notEmpty',
	            'message' => '(*) 必須!',
	        ),
	        'email' => array(
	            'rule' => 'email',
	            'message' => 'このフィールドはメールでなければなりません。',
	        ),
	    ),
	    'password' => array(
	        'notEmpty' => array(
	            'rule' => 'notEmpty',
	            'on' => 'create',
	            'message' => '(*) 必須!',
	        ),
	        'minlength' => array(
	            'rule' => array('minLength', 6),
	            'message' => 'パスワードは6文字以上でなければなりません。'
			),
	        'matchPasswords' => array(
                'rule' => array('matchPasswords'),
                'message' => 'パスワードは間違います。'
            )
	    ),
	    'role' => array(
	        'valid' => array(
	            'rule' => array('inList', array('admin', 'staff')),
	            'message' => 'Please enter a valid role',
	            'allowEmpty' => false,
	        )
	    ),
	    'file_path' => array(
            /*'uploadError' => array(
                'rule' => 'uploadError',
                'message' => 'Something went wrong with the file upload',
                'required' => FALSE,
                'allowEmpty' => TRUE,
            ),*/
            /*'mimeType' => array(
                'rule' => array('mimeType', array('application/pdf')),
                'message' => 'Invalid file, only pdf allowed',
                'required' => FALSE,
                'allowEmpty' => TRUE,
            ),*/
            'processUpload' => array(
                'rule' => 'processUpload',
                'message' => 'ファイルの処理中は何か間違ってます。',
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'last' => TRUE,
            )
        )
	);

	function validate_edit(){
        $this->validate = array(
                'username' => array(
			        'isUnique' => array(
			            'rule' => 'isUnique',
			            'message' => 'メールアドレスはすでに登録されています。',
			            'last' => true,
			        ),
			        'notEmpty' => array(
			            'rule' => 'notEmpty',
			            'message' => '(*) 必須!',
			        ),
			        'email' => array(
			            'rule' => 'email',
			            'message' => 'このフィールドはメールでなければなりません。',
			        ),
			    ),
			    'first_name' => array(
			        'notEmpty' => array(
			            'rule' => 'notEmpty',
			            'message' => '(*) 必須!',
			        )
			    ),
			    'last_name' => array(
			        'notEmpty' => array(
			            'rule' => 'notEmpty',
			            'message' => '(*) 必須!',
			        )
			    ),
                'file_path' => array(
                    'processUpload' => array(
                        'rule' => 'processUpload',
                        'message' => 'ファイルの処理中は何か間違ってます',
                        'required' => false,
                        'allowEmpty' => true
                    )
                ),
                'password' => array(
		            'notEmpty' => array(
			            'rule' => 'notEmpty',
			            'message' => '(*) 必須!',
			        ),
			        'minlength' => array(
			            'rule' => array('minLength', 6),
			            'message' => 'パスワードは6文字以上でなければなりません。'
			        )
		        ),
		        'repeat_password' => array(
		            'notEmpty' => array(
			            'rule' => 'notEmpty',
			            'message' => '(*) 必須!',
			        ),
			        'match' => array(
		                'rule' => array('equalToField','password'),
		                'message' => 'パスワードは間違います。',
		            ),
		        ),
            );
        if($this->validates($this->validate))
            return TRUE;
        else
            return FALSE;
    }

    function validate_inviteadmin(){
    	$this->validate = array(
            'username' => array(
            	'isUnique' => array(
		            'rule' => 'isUnique',
		            'message' => 'メールアドレスはすでに登録されています。',
		            'last' => true,
		        ),
		        'notEmpty' => array(
		            'rule' => 'notEmpty',
		            'message' => '(*) 必須!',
		        ),
		        'email' => array(
		            'rule' => 'email',
		            'message' => 'このフィールドはメールでなければなりません',
		        ),
		    )
        );
        if($this->validates($this->validate))
            return TRUE;
        else
            return FALSE;
    }

    function validate_invite(){
    	$this->validate = array(
            'username' => array(
		        'notEmpty' => array(
		            'rule' => 'notEmpty',
		            'message' => '(*) 必須!',
		        ),
		        'email' => array(
		            'rule' => 'email',
		            'message' => 'このフィールドはメールでなければなりません',
		        ),
		    ),
		    'role'=>array(
		    	'notEmpty' => array(
		            'rule' => 'notEmpty',
		            'message' => '(*) 必須!',
		        ),
		        'inlist' =>array(
		        	'rule'=>array('inList', array('client','partner','worker')),
		        	'message' => 'Role must is client,worker or partner '
		        )
		    )
        );
        if($this->validates($this->validate))
            return TRUE;
        else
            return FALSE;
    }

    function validate_changepassword(){

        $this->validate = array(
            'password' => array(
	            'notEmpty' => array(
		            'rule' => 'notEmpty',
		            'message' => '(*) 必須!',
		        ),
		        'minlength' => array(
		            'rule' => array('minLength', 6),
		            'message' => 'パスワードは6文字以上でなければなりません。'
		        )
	        ),
	        'repeat_password' => array(
	            'notEmpty' => array(
		            'rule' => 'notEmpty',
		            'message' => '(*) 必須!',
		        ),
		        'match' => array(
	                'rule' => array('equalToField','password'),
	                'message' => 'パスワードは間違います。',
	            ),
	        ),
        );

        if($this->validates($this->validate))
            return true;
        else
            return false;
    }

    function equalToField($array, $field) {
    	if($this->data[$this->alias][key($array)] == $this->data[$this->alias][$field]){
    		$return = true;;
    	}else{
    		$return = false;
    	}
    	return $return;
    }

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['repeat_password'])) {
            unset($this->data['repeat_password']);
            unset($this->validate['repeat_password']);
        }

        // a file has been uploaded so grab the filepath
        if (!empty($this->data[$this->alias]['new_avatar'])) {
            $this->data[$this->alias]['avatar'] = $this->data[$this->alias]['new_avatar'];
        }else{
        	unset($this->data[$this->alias]['avatar']);
        }

        // fallback to our parent
        return parent::beforeSave($options);
    }

    /**
     * Process the Upload
     * @param array $check
     * @return boolean
     */
    public function processUpload($check=array()) {
        // deal with uploaded file
        if (!empty($check['file_path']['tmp_name'])) {

            // check file is uploaded
            if (!is_uploaded_file($check['file_path']['tmp_name'])) {
                return FALSE;
            }

            // build full filename
            $filename = time()."_".rand(9999,99999).'.'.pathinfo($check['file_path']['name'], PATHINFO_EXTENSION);
            Configure::load('config_shishimai');
            $uploadDir = Configure::read('upload_dir.user');
            $file_url = WWW_ROOT . ltrim($uploadDir,'/') . DS .$filename;

            // try moving file
            if (!move_uploaded_file($check['file_path']['tmp_name'], $file_url)) {
                return FALSE;
            }
            $this->data[$this->alias]['new_avatar'] = $filename;
        }else{

            if (!empty($check['file_path_reupload']['tmp_name'])) {
                // check file is uploaded
                if (!is_uploaded_file($check['file_path_reupload']['tmp_name'])) {
                    return FALSE;
                }

                // build full filename
                $filename = time()."_".rand(9999,99999).'.'.pathinfo($check['file_path_reupload']['name'], PATHINFO_EXTENSION);
                $file_url = WWW_ROOT . $this->uploadDir . DS .$filename;

                // try moving file
                if (!move_uploaded_file($check['file_path_reupload']['tmp_name'], $file_url)) {
                    return FALSE;
                }
                $this->data[$this->alias]['new_avatar'] = $filename;
            }
        }

        return true;
    }


	//Get user info
	public function getUserInfo($user_id){
		$user = $this->find('first',array(
			'conditions'=>array(
				'id'=>$user_id
			)
		));

		if($user){
			return $user['SsmUser'];
		}else{
			return false;
		}
	}
}
?>