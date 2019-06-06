
<table id="actualizar-todo" class="actualiza-todo table table-bordered table-condensed">
<?php

// Saves the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);

if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__);
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';

// Set profiler start time and memory usage and mark afterLoad in the profiler.
JDEBUG ? JProfiler::getInstance('Application')->setStart($startTime, $startMem)->mark('afterLoad') : null;

// Instantiate the application.
//$app = JFactory::getApplication('site');

// Execute the application.
//$app->execute();



//Incrementamos el tiempo de ejecución máximo
set_time_limit(300);

$user   = JFactory::getUser();
$userid = $user->get('id');
$usergroups = JAccess::getGroupsByUser($userid);

$app= JFactory::getApplication();
$host = $app->getCfg('host');
$usuario = $app->getCfg('user');
$contraseña = $app->getCfg('password');
$fabrikdb =  $app->getCfg('db2');
$con=mysqli_connect($host,$usuario,$contraseña,$fabrikdb);
// Check connection
if (mysqli_connect_errno()) {
	$formModel->getForm()->error = "Failed to connect to MySQL: " . mysqli_connect_error();
}

if(in_array(12, $usergroups)) {
	require_once JPATH_BASE . '/actualiza_profesores.php';
	require_once JPATH_BASE . '/actualiza_grupos.php';
	require_once JPATH_BASE . '/actualiza_seminarios.php';
	require_once JPATH_BASE . '/actualiza_tutelas.php';
}


$con->close();  

?>
</table>