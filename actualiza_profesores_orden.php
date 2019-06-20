<tr class="fabrik_row actualiza-profesores"><td><b>Actualizando profesores-orden</b></td></tr>
<?php

$orden = 1;

$sql_profesores = "select id, prelacion, orden
from t_usuarios
order by prelacion";
$stmt_profesores = $con->prepare($sql_profesores);
$stmt_profesores->execute();

$result_profesores = $stmt_profesores->get_result();

while ($row = $result_profesores->fetch_assoc()) {
	$id = $row["id"];
	

  	//Actualizamos la tabla de usuarios
	$query_update_usuarios = "UPDATE t_usuarios 
	SET orden = ?
	WHERE id = ?";

	$stmt_update_usuarios = $con->prepare($query_update_usuarios);
	$stmt_update_usuarios->bind_param('ii',$orden,$id);
	$stmt_update_usuarios->execute();
  	//print_r($stmt_update_usuarios);
	if ($stmt_update_usuarios->affected_rows) {
		echo "<tr class='fabrik_row'><td>Profesor-orden ".$id." actualizado con el orden ".$orden.". </td></tr>";	
	}
	$stmt_update_usuarios->close(); 

	$orden+=1;
}
$stmt_profesores->close();
?>
<tr class="fabrik_row actualiza-profesores"><td><b>Profesores-orden actualizados</b></td></tr>