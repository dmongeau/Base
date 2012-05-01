<?php


switch($ACTION) {
	
	
	case 'contact':
	
		include PATH_MODULE_ABOUT_PUBLIC.'/contact.php';
	
	break;
	
	
	case 'conditions':
		
		include PATH_MODULE_ABOUT_PUBLIC.'/conditions.php';
	
	break;
	
	
	case 'confidentialite':
		
		include PATH_MODULE_ABOUT_PUBLIC.'/privacy.php';
	
	break;
	
	default:
		
		include PATH_MODULE_ABOUT_PUBLIC.'/about.php';
		
	break;
	
	
}
