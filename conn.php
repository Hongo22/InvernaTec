<?php

    //Variables de conexión
    $host = "localhost";
    $usuario = "root";
    $contraseña = "";
    $base_datos = "invernatec";

    //Conexión
    $mysqli = new mysqli($host, $usuario, $contraseña, $base_datos);

    //verificar conexión
    if ($mysqli->connect_error)
    {
        die("Error de conexión: " . $mysqli->connect_error);
        exit();
    }

    
?>