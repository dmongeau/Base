<?php

class User extends Kate {
	
	public $source = array(
		'type' => 'db',
		'table' => array(
			'name' => array('u'=>'users'),
			'primary' => 'uid',
			'fields' => '*',
			'nowFields' => array('dateadded'),
			'deletedField' => 'deleted',
			'leftJoins' => array(
				array(array('p'=>'photos'),'p.pid = u.pid',array('photo'=>'filename')),
			)
		)
	);	
	
	/*
	 *
	 * User roles
	 *
	 */
	const ROLE_MEMBER = 0;
	const ROLE_ROOT = 1;
	const ROLE_ADMIN = 2;
	
	
	protected function _selectPrimary($select,$primary) {
		
		if(!is_numeric($primary) && Zend_Validate::is($primary,'EmailAddress')) {
			$select = $this->_parseQuery($select,array(
				'lower email' => strtolower($primary),
				'available' => true
			));
		} else if(!is_numeric($primary)) {
			$select = $this->_parseQuery($select,array(
				'lower username' => strtolower($primary),
				'available' => true
			));
		} else $select->where('u.uid = ?',$primary);
		
		return $select;	
	}
	
	/*
	 *
	 * Get property
	 *
	 */
	
	public function hasPhoto() {
		$data = $this->getData();
		
		if(!empty($data['photo']) || (int)$data['fbid'] > 0) return true;
		
		return false;
	}
	
	public function photo($size = 'thumb') {
		
		$data = $this->getData();
		
		return '/resizer/'.trim($size,'/').'/y0/f/'.ltrim($data['photo'],'/');
			
	}
	
	
	public function isCurrentUser() {
		
		if(!Gregory::get()->auth->isLogged()) return false;
		
		$data = $this->getData();
		
		return (int)$data['uid'] == (int)Gregory::get()->auth->getIdentity()->uid ? true:false;
		
	}
	
	
	public function isAdmin() {
		
		$data = $this->getData();
		
		if((int)$data['role'] == self::ROLE_ROOT || (int)$data['role'] == self::ROLE_ADMIN) return true;
		return false;
		
	}
	
	
	/*
	 *
	 * Get related items
	 *
	 */
	
	
	/*
	 *
	 * HTML Templates
	 *
	 */
	public function getListHTML() {
		
		ob_start();
		include PATH_PAGES.'/_helpers/user.list.php';
		$content = ob_get_clean();
		
		return $content;	
	}
	
	/*
	 *
	 * Mail
	 *
	 */
	public function mailRegister($pwd = '') {
		
		$data = $this->getData();
		
		$Mail = Gregory::get()->mail->create('Votre inscription',$data['email']);
		
		$message = 'Bonjour,'."\n";
		$message .= 'Merci d\'avoir pris le temps de vous inscrire à Show de Salon, le Réseau de concerts à la maison.'."\n";
		$message .= 'Voici vos informations de connexion :'."\n\n";
		$message .= 'Courriel : '.$data['email']."\n";
		if(!empty($data['username'])) $message .= 'Nom d\'utilisateur : '.$data['username']."\n";
		if(!empty($pwd)) $message .= 'Mot de passe : '.substr($pwd,0,strlen($pwd)-4).'****'."\n\n";
		$message .= '---'."\n\n";
		$message .= 'Voici quelques liens utiles :'."\n\n";
		$message .= 'Pour vous connecter : http://'.$_SERVER['HTTP_HOST'].'/connexion.html'."\n";
		$message .= 'Pour voir votre profil  : http://'.$_SERVER['HTTP_HOST'].$this->permalink()."\n";
		$message .= 'Pour modifier votre profil  : http://'.$_SERVER['HTTP_HOST'].$this->permalink('/modifier.html')."\n\n";
		$message .= '---'."\n\n";
		$message .= 'Site web'."\n";
		$message .= 'info@siteweb.com';
		
		$Mail->setBodyText($message);
		$Mail->send();
		
	}
	public function mailForget($pwd) {
		
		$data = $this->getData();
		
		$Mail = Gregory::get()->mail->create('Mot de passe oublié',$data['email']);
		
		$message = 'Bonjour,'."\n";
		$message .= 'Voici un mot de passe temporaire pour vous connecter au site.'."\n\n";
		$message .= 'Mot de passe : '.$pwd."\n\n";
		$message .= '---'."\n\n";
		$message .= 'Pour vous connecter : http://'.$_SERVER['HTTP_HOST'].'/connexion.html'."\n";
		$message .= 'Pour modifier votre mot de passe  : http://'.$_SERVER['HTTP_HOST'].$this->permalink('/modifier.html')."\n\n";
		$message .= '---'."\n\n";
		$message .= 'Site web'."\n";
		$message .= 'info@siteweb.com';
		
		$Mail->setBodyText($message);
		$Mail->send();
		
	}
	
	
	/*
	 *
	 * Validate user data
	 *
	 */
	public function validate() {
		
		$data = $this->getData();
		
		if(!isset($data['email']) || !Zend_Validate::is($data['email'],'EmailAddress')) {
			Gregory::get()->addError('Vous devez entrer une adresse courriel valide');
		}
		
		$username = $data['username'];
		if(!isset($username) || empty($username) || strlen($username) < 4 || strlen($username) > 20 || !preg_match('/^(^[a-z0-9\_\-\.]+)$/i',$username)) {
			Gregory::get()->addError('Vous devez entrer un nom d\'utilisateur entre 4 et 20 caractères. Il doit être composé de lettres, de chiffres et des caractères suivants _ - . seulement.');
		}
		
		$query = $this->isNew() ? array('email'=>$data['email']):array('email'=>$data['email'],'not uid'=>$data['uid']);
		$items = $this->getItems(array_merge(array('available'=>true),$query));
		if(isset($items) && sizeof($items)) {
			Gregory::get()->addError('Il y a déjà un compte pour cette adresse courriel');
		}
		
		if(!empty($data['username'])) {
			$query = $this->isNew() ? array('username'=>$data['username']):array('username'=>$data['username'],'not uid'=>$data['uid']);
			$items = $this->getItems(array_merge(array('available'=>true),$query));
			if(isset($items) && sizeof($items)) {
				Gregory::get()->addError('Ce nom d\'utilisateur est déjà pris');
			}
		}
		
		if(isset($data['description']) && strlen($data['description']) > 150) {
			Gregory::get()->addError('Votre description peut contenir un maximum de 150 caractères');
		}
		
		if($this->isNew()) {
			if(!isset($data['pwd']) || !isset($data['pwd2']) || empty($data['pwd']) || empty($data['pwd2'])) {
				Gregory::get()->addError('Vous devez entrer un mot de passe');
			} else if($data['pwd'] != $data['pwd2']) {
				Gregory::get()->addError('Vous devez entrer le même mot de passe dans la confirmation');
			}
		}
		
		if(isset($data['pwd']) && !empty($data['pwd']) && strlen($data['pwd']) < 6) {
			Gregory::get()->addError('Votre mot de passe doit contenir un minimum de 6 caractères');
		}
		
		if(Gregory::get()->hasErrors()) {
			throw new Exception('Votre formulaire contient des erreurs');	
		}
		
	}
	
	
	/*
	 *
	 * Data format
	 *
	 */
	protected function _putPwd($data,$value,$inputs) {
		
		if(!empty($value)) $data['pwd'] = Gregory::get()->auth->passwordHash($value);
		
		return $data;
		
	}
	
	/*
	 *
	 * Items query custom parameters
	 *
	 */
	protected function _queryAvailable($select, $value) {
		
		if($value === true || $value === 1 || $value === '1' || $value === 'true') {
			$select->where('u.published = 1 AND u.deleted = 0');
		} else if($value === false || $value === 0 || $value === '0' || $value === 'false') {
			$select->where('u.published = 0 OR u.deleted = 1');
		}
		
		return $select;	
	}
	
	protected function _querySearch($select, $value) {
		
		if(!empty($value)) {
			
			$query = trim(stripslashes($value));
			$wheres = array();
			if(strlen($query) > 3) {
				$wheres[] = '('.Gregory::get()->db->quoteInto('MATCH(u.firstname,u.lastname,u.username,u.email) AGAINST(?)',$query).')';
			} else {
				$wheres[] ='('.Gregory::get()->db->quoteInto('LOWER(u.username) LIKE ?',strtolower('%'.$query.'%')).')';
				$wheres[] ='('.Gregory::get()->db->quoteInto('LOWER(u.firstname) LIKE ?',strtolower('%'.$query.'%')).')';
				$wheres[] ='('.Gregory::get()->db->quoteInto('LOWER(u.lastname) LIKE ?',strtolower('%'.$query.'%')).')';
			}
			
			if(sizeof($wheres)) {
				$select->where(implode(' OR ',$wheres));
				$select->order('('.implode(' + ',$wheres).') DESC');
			}
			
		} else {
			$select->where('u.uid = -1');
		}
		
		return $select;	
	}
	
	
	public function getCurrent() {
		
		if(!Gregory::get()->auth->isLogged() || !Gregory::get()->auth->hasIdentity()) return null;
		
		return new self((int)Gregory::get()->auth->getIdentity()->uid);
		
	}
	
	
	/*
	 *
	 * Login hooks
	 *
	 */
	public static function loggedIn($user) {
		
		$db = Gregory::get()->db;
		
		$db->insert('users_logins',array(
			'uid' => (int)$user->uid,
			'ip' => Bob::run('network.getip'),
			'useragent' => $_SERVER['HTTP_USER_AGENT'],
			'dateadded' => date('Y-m-d H:i:s')
		));
		
	}
	
	public static function filterIdentity($user) {
		
		if(isset($user->pwd)) unset($user->pwd);
		
		try {
			
			$User = new User();
			$User->setData((array)$user);
			
		} catch(Exception $e) {}
		
		
		return $user;
		
	}
	
	public static function updateIdentity() {
		
		try {
			
			if(!Gregory::get()->auth->isLogged()) return;
			
			$identity = Gregory::get()->auth->getIdentity();
			
			$User = new User();
			$User->setData((array)$identity);
			
			Gregory::get()->auth->setIdentity($identity);
			
		} catch(Exception $e) {}
		
	}
	
}
