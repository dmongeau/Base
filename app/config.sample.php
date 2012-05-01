<?php

$config = array(
	'path' => array(
		'pages' => PATH_PAGES,
		'plugins' => PATH_PLUGINS
	),
	
	'error' => array(
		'404' => PATH_PAGES.'/errors/404.html',
		'500' => PATH_PAGES.'/errors/500.html'
	),
	
	'debug' => array(
		'stats' => true
	),
	
	'db' => array(
		'adapter' => 'pdo_mysql',
		'config' => array(
			'host' => 'localhost',
			'username' => 'user',
			'password' => 'password',
			'dbname' => 'database'
		)
	),
	
	'auth' => array(
		'table' => 'users',
		'identityColumn' => 'email',
		'passwordColumn' => 'pwd',
		'roleColumn' => 'role',
		'hashMode' => 'sha1',
		'hashSalt' => '!_GREGORY_!@',
		'block' => array(
			'deleted' => 1,
			'published' => 0
		),
		'valid' => array(
			'deleted' => 0,
			'published' => 1
		),
	
	),
	
	'mail' => array(
		'from' => array('Site web'=>'info@siteweb.com'),
		'contact' => 'dev@commun.ca'
	
	),
	
	
	'facebook' => array(
		'appId'  => '209594579080515',
		'secret' => 'a1416607f0a7b038455e1225f5aaf000',
		'cookie' => true,
	),
	
	'cache' => array(
		'cacheDir' => PATH_APP.'/_cache',
		'cache' => array(
			
			'core' => array(
				'frontend' => array(
					'name' => 'Core'
				),
				'backend' => array(
					'name' => 'File'
				)
			),
			
			'kate' => array(
				'frontend' => array(
					'name' => 'Core',
					'options' => array(
						'lifetime' => 7200,
						'automatic_serialization' => true
					)
				),
				'backend' => array(
					'name' => 'File'
				)
			),
			
			'data' => array(
				'frontend' => array(
					'name' => 'Core',
					'options' => array(
						'lifetime' => (3600*24*7),
						'automatic_serialization' => true
					)
				),
				'backend' => array(
					'name' => 'File'
				)
			)
		
		),
	),
	
	'resizer' => array(
		'path' => PATH_ROOT.'/statics/photos',
		'cache' => true,
		'cachePath' => PATH_ROOT.'/statics/photos/_cache',
		'size' => array(
			'thumb' => array(
				'width' => 35,
				'height' => 35,
				'ratio' => true
			)
		)
	)
	
);


return $config;