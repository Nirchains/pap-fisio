
<table id="actualizar-todo" class="actualiza-todo table table-bordered table-condensed">
<?php

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
//	require_once JPATH_BASE . '/actualiza_tutelas.php';
}


$con->close();  

?>
</table>