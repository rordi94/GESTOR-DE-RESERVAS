<?php

require_once "main.php";

#almacenando datos 

$nombre=limpiar_cadena($_POST['categoria_nombre']);
$ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);



#VERIFICANDO CAMPOS OBLIGATORIOS

if($nombre==""){

        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>';

        exit();

}




#VERIFICANDO INTEGRIDAD DE LOS CAMPOS O EXPRESIONES REGULARES

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){

    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El nombre no coincide con el formato solicitado
</div>';

exit();
}

if($ubicacion!=""){
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){

        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La Ubicacion no coincide con el formato solicitado
    </div>';

    exit();
    }
}

#VERIFICANDO NOMBRE
$check_nombre=conexion();
$check_nombre=$check_nombre->query("SELECT categoria_nombre FROM
categoria WHERE categoria_nombre='$nombre'");

if($check_nombre->rowCount()>0){
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El NOMBRE ya esta registrado en la base de datos,
    por favor intruduzca otro 
</div>';  
exit();
}
$check_nombre=null;

#GUARDANDO DATOS

$guardar_categoria=conexion();
$guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacion)
VALUES(:nombre,:ubicacion)");

$marcadores=[
    ":nombre"=>$nombre,
    ":ubicacion"=>$ubicacion
];

$guardar_categoria->execute($marcadores);

if($guardar_categoria->rowCount()==1){
    echo '<div class="notification is-info is-light">
    <strong>¡CATEGORIA REGISTRADA!</strong><br>
    La Categoria se registro con exito.
 </div>';  


}else{

    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    No se pudo registrar  La Categoria, Por favor intente nuevamente
 </div>';  

}

$guardar_categoria=null;