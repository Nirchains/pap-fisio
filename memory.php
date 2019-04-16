<?php 
echo ini_get("memory_limit");
ini_set("memory_limit","128M");
echo ini_get("memory_limit");
ini_restore("memory_limit");
echo ini_get("memory_limit");



?>