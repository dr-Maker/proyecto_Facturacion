<?php

$host='localhost';
$user='root';
$pass='';
$database='facturacion';

$conection= mysqli_connect($host,$user,$pass,$database);



if(!$conection){
    echo "Error en la conexion";
}

?>