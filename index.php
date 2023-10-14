<?php require "./inc/session_start.php" ?>



<!DOCTYPE html>
<html lang="es">
<head>
<?php include "./inc/head.php"; ?>


</head>
<body>
    <?php
    if(!isset($_GET['vista']) || $_GET['vista']=="") {
        $_GET['vista']="login";
    }

// is_file= comprueba si un archivo o carpeta esta en el directorio

    if(is_file("./vistas/".$_GET['vista'].".php") && $_GET['vista'] 
        !="login" && $_GET['vista']!="404"){

            #CERRAR SESION

        if(!isset($_SESSION['id']) || ($_SESSION['id']=="") || 
        (!isset($_SESSION['usuario'])|| $_SESSION['usuario']=="")){
                 
            include "./vistas/logout.php";
            exit;

        }else{

        }

        include "./inc/navbar.php"; 
        include "./vistas/".$_GET['vista'].".php"; 


        include "./inc/script.php"; 
    
    }else{
        if($_GET['vista']=="login") {
            include "./vistas/login.php";
        }else{
            include "./vistas/404.php";
        }
    }


  ?>

</body>


</html>