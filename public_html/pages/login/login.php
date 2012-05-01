<?php

switch($ACTION) {
	
	case 'logout':

		try {
			
			$this->auth->logout();
			
			if(!Gregory::isAJAX()) {
				if(isset($_REQUEST['next']) && !empty($_REQUEST['next'])) Gregory::redirect($_REQUEST['next']);
				else Gregory::redirect('/accueil.html');
			} else {
				Gregory::JSON(array('success'=>true));
			}
			
		} catch(Zend_Exception $e) {
			
			if(!Gregory::isAJAX()) $error = 'Il s\'est produit une erreur';
			else Gregory::JSON(array('success'=>false, 'error'=>'Il s\'est produit une erreur'));
			
		} catch(Exception $e) {
			
			if(!Gregory::isAJAX()) $error = $e->getMessage();
			else Gregory::JSON(array('success'=>false, 'error'=>$e->getMessage()));
			
		}
	
	break;
	
	case 'forgot':
		if($_POST) {

			try {
			
				if(!isset($_POST['email']) || empty($_POST['email']) || !Zend_Validate::is($_POST['email'],'EmailAddress')) {
					throw new Exception('Vous devez entrer une adresse courriel valide.');	
				}
				
				Kate::requireModel('User');
				
				$User = new User();
				$users = $User->getItems(array('lower email'=>strtolower($_POST['email']),'available'=>true));
				
				if(!isset($users[0]) || !isset($users[0]['email'])) {
					throw new Exception('Il n\'y a pas de compte pour cette adresse courriel.');
				}
				
				$User->setData($users[0]);
				
				$newpwd = substr(md5(time().uniqid()),0,6);
				$User->setData(array('pwd'=>$newpwd,'tmpPwd'=>1));
				$User->save();
				
				$User->mailForget($newpwd);
				
				Gregory::redirect('/connexion.html?newpwd=1');
				
			} catch(Zend_Exception $e) {
				if(!Gregory::isAJAX()) $this->addError('Il s\'est produit une erreur',500,$e);
				else Gregory::JSON(array('success'=>false, 'error'=>'Il s\'est produit une erreur'));
				
			} catch(Exception $e) {
				
				if(!Gregory::isAJAX()) $this->addError($e->getMessage(),$e->getCode(),$e);
				else Gregory::JSON(array('success'=>false, 'error'=>$e->getMessage()));
				
			}
		
		}
		
		include PATH_MODULE_LOGIN_PUBLIC.'/forgot.php';
		
	break;
	
	case 'change':
		
		if(!$this->auth->isLogged()) Gregory::redirect('/connexion.html');

		if($_POST) {
		
			try {
				
				$data = $_POST;
				
				if(!isset($data['pwd']) || !isset($data['pwd2']) || empty($data['pwd']) || empty($data['pwd2'])) {
					Gregory::get()->addError('Vous devez entrer un mot de passe');
				} else if($data['pwd'] != $data['pwd2']) {
					Gregory::get()->addError('Vous devez entrer le même mot de passe dans la confirmation');
				}
				
				if(isset($data['pwd']) && !empty($data['pwd']) && strlen($data['pwd']) < 6) {
					Gregory::get()->addError('Votre mot de passe doit contenir un minimum de 6 caractères');
				}
				
				Kate::requireModel('User');
				
				$User = new User($this->auth->getIdentity()->uid);
				$User->setData(array('pwd'=>$data['pwd'],'tmpPwd'=>0));
				$User->save();
				
				if(isset($_REQUEST['next']) && !empty($_REQUEST['next'])) Gregory::redirect($_REQUEST['next']);
				else Gregory::redirect('/accueil.html');
				
			} catch(Zend_Exception $e) {
				if(!Gregory::isAJAX()) $this->addError('Il s\'est produit une erreur',500,$e);
				else Gregory::JSON(array('success'=>false, 'error'=>'Il s\'est produit une erreur'));
				
			} catch(Exception $e) {
				
				if(!Gregory::isAJAX()) $this->addError($e->getMessage(),$e->getCode(),$e);
				else Gregory::JSON(array('success'=>false, 'error'=>$e->getMessage()));
				
			}
		
		}
		
		include PATH_MODULE_LOGIN_PUBLIC.'/change.php';
	
	break;
	
	
	default:

		if($this->auth->isLogged()) {
			if(isset($_REQUEST['next']) && !empty($_REQUEST['next'])) Gregory::redirect($_REQUEST['next']);
			else Gregory::redirect('/');	
		}
		
		if($_POST) {
			
			try {
				
				if(!isset($_POST['email']) || !isset($_POST['pwd']) || empty($_POST['email']) || empty($_POST['pwd'])) {
					throw new Exception('Vous devez entrer votre adresse courriel et votre mot de passe.');	
				}
				
				Kate::requireModel('User');
				
				$this->addFilter('auth.login.identity',array('User','filterIdentity'));
				$this->addAction('auth.login.valid',array('User','loggedIn'));
				
				$user = $this->auth->login(Bob::x('ne',$_POST,'email'),Bob::x('ne',$_POST,'pwd'),false);
				
				if((int)$user->tmpPwd == 1) {
					Gregory::redirect('/connexion/changement-mot-de-passe.html?next='.rawurlencode(Bob::x('ne',$_REQUEST,'next')));
				}
				
				if(!Gregory::isAJAX()) {
					if(isset($_REQUEST['next']) && !empty($_REQUEST['next'])) Gregory::redirect($_REQUEST['next']);
					else Gregory::redirect('/');
				} else {
					Gregory::JSON(array('success'=>true,'user'=>$user));
				}
				
			} catch(Zend_Exception $e) {
				
				if(!Gregory::isAJAX()) $this->addError('Il s\'est produit une erreur',500,$e);
				else Gregory::JSON(array('success'=>false, 'error'=>'Il s\'est produit une erreur'));
				
			} catch(Exception $e) {
				
				if(!Gregory::isAJAX()) $this->addError($e->getMessage(),$e->getCode(),$e);
				else Gregory::JSON(array('success'=>false, 'error'=>$e->getMessage()));
				
			}
			
		}
		
		include PATH_MODULE_LOGIN_PUBLIC.'/index.php';
	
	break;
	
}

