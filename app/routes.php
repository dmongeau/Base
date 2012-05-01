<?php



return array(

	'/' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'home'
		)
	),
	
	/*
	 *
	 * À propos
	 *
	 */
	'/a-propos/' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'about'
		)
	),
	'/a-propos/:action.html' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'about'
		)
	),
	
	/*
	 *
	 * Inscription
	 *
	 */
	'/inscription.html' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'register'
		)
	),
	'/inscription/confirmation.html' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'register',
			'action' => 'confirm'
		)
	),
	
	
	
	/*
	 *
	 * Login
	 *
	 */
	'/connexion.html' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'login'
		)
	),
	'/connexion/mot-de-passe-oublie.html' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'login',
			'action' => 'forgot'
		)
	),
	'/connexion/changement-mot-de-passe.html' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'login',
			'action' => 'change'
		)
	),
	'/deconnexion.html' => array(
		'page' => 'controller.php',
		'params' => array(
			'module' => 'login',
			'action' => 'logout'
		)
	),
	
);