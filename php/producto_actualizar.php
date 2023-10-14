<?php
require_once "../php/main.php";
$id=limpiar_cadena($_POST['producto_id']);

//verificar el producto
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$id'");
if($check_producto->rowCount()<=0){
    echo
     '<div class="notification is-danger is-light">
                <strong>ocurrio un error inesperado! </strong><BR>
            El producto no existe en el sistema
      </div>
  ';
    exit();
}else{
        $datos=$check_producto->fetch();
}
$check_producto=null;

 #almacenando datos 

 $codigo=limpiar_cadena($_POST['producto_codigo']);
 $nombre=limpiar_cadena($_POST['producto_nombre']);
 
 $precio=limpiar_cadena($_POST['producto_precio']);
 $stock=limpiar_cadena($_POST['producto_stock']);
 
 $categoria=limpiar_cadena($_POST['producto_categoria']);

 #VERIFICANDO CAMPOS OBLIGATORIOS

if($codigo=="" || $nombre=="" || $precio=="" || $stock=="" || $categoria=="" ){

    echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>';

    exit();

}

#VERIFICANDO INTEGRIDAD DE LOS CAMPOS O EXPRESIONES REGULARES

if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)){

    echo 
    '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El codigo no coincide con el formato solicitado
        </div>';

        exit();
}

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){

    echo 
    '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El nombre no coincide con el formato solicitado
        </div>';

        exit();
}

if(verificar_datos("[0-9.]{1,25}",$precio)){

    echo 
    '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El precio no coincide con el formato solicitado
        </div>';

        exit();
}

if(verificar_datos("[0-9]{1,25}",$stock)){

    echo 
    '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El stock no coincide con el formato solicitado
        </div>';

        exit();
}

#VERIFICANDO CODIGO

if($codigo!=$datos['producto_codigo']){

    $check_codigo=conexion();
    $check_codigo=$check_codigo->query("SELECT producto_codigo FROM
    producto WHERE producto_codigo='$codigo'");
    
    if($check_codigo->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El codigo ya esta registrado en la base de datos,
                    por favor intruduzca otro codigo
            </div>
            ';  
            exit();
    }
    $check_codigo=null;

}

#VERIFICANDO NOMBRE

if($nombre!=$datos['producto_nombre']){
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT producto_nombre FROM
    producto WHERE producto_nombre='$nombre'");
    
    if($check_nombre->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Nombre ya esta registrado en la base de datos,
                    por favor intruduzca otro nombre
            </div>
            ';  
            exit();
    }
    $check_nombre=null;
}

#VERIFICANDO CATEGORIA

if($categoria!=$datos['categoria_id']){
    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT categoria_id FROM
    categoria WHERE categoria_id='$categoria'");
    
    if($check_categoria->rowCount()<=0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La Categoria seleccionada no existe
            </div>
            ';  
            exit();
    }
    $check_categoria=null;
}

#Actualizar datos

$actualizar_producto=conexion();

$actualizar_producto=$actualizar_producto->prepare(" UPDATE producto SET producto_codigo=:codigo,
producto_nombre=:nombre,producto_precio=:precio,
producto_stock=:stock,categoria_id=:categoria WHERE producto_id=:id");

$marcadores=[
    ":codigo"=>$codigo,
    ":nombre"=>$nombre,
    ":precio"=>$precio,
    ":stock"=>$stock,
    ":categoria"=>$categoria,
    ":id"=>$id
 
];

if($actualizar_producto->execute($marcadores )){

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