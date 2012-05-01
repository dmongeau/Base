<?php


switch($ACTION) {
	
	case 'confirm':
		
		try {
		
		
			if(!isset($_REQUEST['key']) || empty($_REQUEST['key']) || !isset($_REQUEST['t']) || empty($_REQUEST['t'])) {
				Gregory::redirect('/inscription.html');
			}
			
			Kate::requireModel('User');
			
			$User = new User();
			$users = $User->getItems(array('confirmKey'=>$_REQUEST['key']));
			
			if(!$users || !sizeof($users) || sizeof($users) > 1 || md5(strtotime($users[0]['dateadded'])) != $_REQUEST['t']) {
				throw new Exception('Confirmation invalide');
			}
			
			$User->setData($users[0]);
			$User->setData(array(
				'confirmKey'=>'',
				'published'=>1
			));
			$User->save();
			
			
			if(isset($_REQUEST['next']) && !empty($_REQUEST['next'])) Gregory::redirect($_REQUEST['next']);
			else Gregory::redirect('/compte/');
		
			
		} catch(Exception $e) {
			$this->catchError($e);
			Gregory::redirect('/inscription.html');
			
		}


	
	break;
	
	default:
		
		if($_POST) {
			
			
			try {
				
				Kate::requireModel('User');
				
				$data = $_POST;
				$data['published'] = 1;
				
				$User = new User();
				$User->setData($data);
				$User->validate();
				$User->save();
				
				if(isset($_FILES['photo']['size']) && (int)$_FILES['photo']['size'] > 0) {
					Kate::requireModel('Photo');
					try {
						$Photo = Photo::addPhoto($_FILES['photo']);
					
						$User->setData(array('pid' => $Photo->getPrimary()));
						$User->save();
					} catch(Exception $e) {
						$this->addError($e->getMessage(),$e->getCode(),$e);
					}
				}
				
				$user = $User->fetch();
				
				$User->mailRegister($data['pwd']);
				
				$this->auth->login($user['email'],$user['pwd'],true);
				
				if(!Gregory::isAJAX()) {
					if(isset($_REQUEST['next']) && !empty($_REQUEST['next'])) Gregory::redirect($_REQUEST['next']);
					else {
						Gregory::redirect('/membres/'.$user['username'].'?register=1');
					}
				} else {
					Gregory::JSON(array('success'=>true,'user'=>$user));
				}
				
			} catch(Zend_Exception $e) {
				if(!Gregory::isAJAX()) $this->addError('Il s\'est produit une erreur');
				else Gregory::JSON(array('success'=>false, 'error'=>'Il s\'est produit une erreur'));
				
			} catch(Exception $e) {
				if(Gregory::isAJAX()) Gregory::JSON(array('success'=>false, 'error'=>$e->getMessage()));
				
			}
			
			
		}
		
		
		include PATH_MODULE_REGISTER_PUBLIC.'/index.php';
		
		
	break;

}