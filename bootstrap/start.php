<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
*/

$env = $app->detectEnvironment(array(

	'local' => array('cafecoder-Aspire-V3-572G'),
	'production'=>array('camscon-application')

));

/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
*/

$app->bindInstallPaths(require __DIR__.'/paths.php');

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load this Illuminate application. We will keep this in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

$framework = $app['path.base'].
                 '/vendor/laravel/framework/src';

require $framework.'/Illuminate/Foundation/start.php';

/*
|--------------------------------------------------------------------------
| Autop helper function
|--------------------------------------------------------------------------
|
| Temporary placement of the AutoP custom helper function
|
*/

function autop($rawText) {
	$utfVal=iconv('UTF-8', 'UTF-8//IGNORE', $rawText);
	$brText=nl2br($utfVal, true);
	$pArray=explode('<br /><br />', $brText);
	$output='';
	foreach ($pArray as $p) {
		$lines=explode('<br />', $p);
		$cleanLines=array();
		foreach ($lines as $line) {
			$cleanLines[]=htmlentities($line, ENT_HTML5, 'UTF-8', false);
		}
		$cleanP=implode('<br />', $cleanLines);
		$output.=sprintf('<p>%s</p>', $cleanP);
	}
	return $output;
}

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
