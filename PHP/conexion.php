<?php

$host="localhost";
$username="root";
$password="";
$dbname="zapateria";
$port=4306;

$conexion=new mysqli($host,$username,$password,$dbname,$port);

if($conexion->connect_errno){
    die("Conexion fallida" . $conexion->connect_errno);}
?>