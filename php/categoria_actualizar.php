<?php
require_once "../php/main.php";
$id=limpiar_cadena($_POST['categoria_id']);

//verificar el usuario
$check_categoria=conexion();
$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");

if($check_categoria->rowCount()<=0){
    echo
     '<div class="notification is-danger is-light">
                <strong>ocurrio un error inesperado! </strong><BR>
            La categoria no existe en el sistema
      </div>
  ';
    exit();
}else{
        $datos=$check_categoria->fetch();
}
$check_categoria=null;

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
if($nombre!=$datos['categoria_nombre']){
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
    
}

#Actualizar datos

$actualizar_categoria=conexion();

$actualizar_categoria=$actualizar_categoria->prepare(" UPDATE categoria SET 
categoria_nombre=:nombre,categoria_ubicacion=:ubicacion WHERE categoria_id=:id");

$marcadores=[
    ":nombre"=>$nombre,
    ":ubicacion"=>$ubicacion,
    ":id"=>$id
 
];

if($actualizar_categoria->execute($marcadores )){

    echo '<div class="notification is-info is-light">
    <strong>¡CATEGORIA ACTUALIZADA!</strong><br>
    La Categoria se actualizo con exito
  </div>';

exit();


}else{
    
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No se pudo actualizar la Categoria, por favor intente nuevamente
      </div>';

exit();
}


$actualizar_categoria=null;