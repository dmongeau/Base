<?php

/*
 *
 * Get route
 *
 */
$this->PARAMS = $PARAMS = $this->getParams();

$this->MODULE = $MODULE = isset($PARAMS['module']) ? $PARAMS['module'] : 'home';
$this->ACTION = $ACTION = isset($PARAMS['action']) ? $PARAMS['action'] : '';
$this->SUBACTION = $SUBACTION = isset($PARAMS['subaction']) ? $PARAMS['subaction'] : '';
$this->FORMAT = $FORMAT = isset($PARAMS['format']) ? $PARAMS['format'] : '';
$this->ID = $ID = isset($PARAMS['id']) ? $PARAMS['id']:null;

//Get format if not specified
if(!empty($SUBACTION) && empty($FORMAT) && ($posExt = strpos($SUBACTION,'.'))) {
     $this->FORMAT = $FORMAT = substr($SUBACTION, $posExt+1);
    $this->SUBACTION = $SUBACTION = substr($SUBACTION, 0, $posExt);
	$this->setParams('format', $FORMAT);
	$this->setParams('subaction', $ACTION);
} else if(!empty($ACTION) && empty($FORMAT) && ($posExt = strpos($ACTION,'.'))) {
    $this->FORMAT = $FORMAT = substr($ACTION, $posExt+1);
    $this->ACTION = $ACTION = substr($ACTION, 0, $posExt);
	$this->setParams('format', $FORMAT);
	$this->setParams('action', $ACTION);
}

/*
 *
 * Path constants
 *
 */
define('PATH_MODULE_'.strtoupper($MODULE),PATH_PAGES.'/'.$MODULE);
define('PATH_MODULE_'.strtoupper($MODULE).'_PUBLIC',PATH_PAGES.'/'.$MODULE.'/public');


/*
 *
 * Set layout data for this module
 *
 */
$this->setData('module',$MODULE);
$this->setData('action',$ACTION);
$this->setData('subaction',$SUBACTION);

if(Gregory::isAJAX()) {
	$this->setLayout(null);
}

/*
 *
 * Run module controller
 *
 */
	
include PATH_PAGES.'/'.$this->MODULE.'/'.$this->MODULE.'.php';