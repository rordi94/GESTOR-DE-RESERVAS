<?php

require_once "../php/main.php";

#almacenando datos 

$nombre=limpiar_cadena($_POST['usuario_nombre']);
$apellido=limpiar_cadena($_POST['usuario_apellido']);

$usuario=limpiar_cadena($_POST['usuario_usuario']);
$email=limpiar_cadena($_POST['usuario_email']);

$clave_1=limpiar_cadena($_POST['usuario_clave_1']);
$clave_2=limpiar_cadena($_POST['usuario_clave_2']);


#VERIFICANDO CAMPOS OBLIGATORIOS

if($nombre=="" || $apellido=="" || $usuario=="" || $clave_1=="" || $clave_2=="" ){

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>';

        exit();

}

#VERIFICANDO INTEGRIDAD DE LOS CAMPOS O EXPRESIONES REGULARES

if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){

    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El nombre no coincide con el formato solicitado
</div>';

exit();
}

if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){

    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El apellido no coincide con el formato solicitado
</div>';

exit();
}

if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){

    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El usuario no coincide con el formato solicitado
</div>';

exit();
}

if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) 
|| verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){

    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    las CLAVES  no coincide con el formato solicitado
</div>';

exit();
}

# VERIFICANDO EL EMAIL

if($email!=""){
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){

            $check_email=conexion();
            $check_email=$check_email->query("SELECT usuario_email FROM
             USUARIO WHERE usuario_email='$email'");

             if($check_email->rowCount()>0){
                echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El correo ya esta registrado en la base de datos,
                 por favor intruduzca otro correo
            </div>';  
            exit();
             }
             $check_email=null;
        }else{

            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            las CLAVES  no coincide con el formato solicitado
            </div>';
            exit();
        }
       
}

#VERIFICANDO USUARIO
            $check_usuario=conexion();
            $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM
            USUARIO WHERE usuario_usuario='$usuario'");

            if($check_usuario->rowCount()>0){
                echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario ya esta registrado en la base de datos,
                por favor intruduzca otro usuario
            </div>';  
            exit();
            }
            $check_usuario=null;


#VERIFICANDO LAS CLAVES

        if($clave_1 != $clave_2){

            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
           Las claves no coinciden,
            por favor intruduzca nuevamente la clave
        </div>';  
        exit();

        }else{
             $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
        }


#GUARDANDO DATOS

        $guardar_usuario=conexion();
        $guardar_usuario= $guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre,usuario_apellido,
        usuario_usuario,usuario_clave,usuario_email)VALUES(:nombre,:apellido,:usuario,:clave,:email)");

        $marcadores=[
            ":nombre"=>$nombre,
            ":apellido"=>$apellido,
            ":usuario"=>$usuario,
            ":clave"=>$clave,
            ":email"=>$email
 
        ];

        $guardar_usuario->execute($marcadores);

        if($guardar_usuario->rowCount()==1){
            echo '<div class="notification is-info is-light">
            <strong>USUARIO REGISTRADO</strong><br>
            El usuario se registro con exito.
         </div>';  


        }else{

            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo registrar el usuario, Por favor intente nuevamente
         </div>';  

        }

        $guardar_usuario=null;