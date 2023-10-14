<?php
    require_once "../inc/session_start.php";
    require_once "main.php";

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

#VERIFICANDO NOMBRE
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

#VERIFICANDO CATEGORIA
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

#DIRECTORIO DE IMAGENES
$img_dir="../img/producto/";

#COMPROBAR SI SE SELECCIONO UNA IMAGEN
if($_FILES['producto_foto']['name']!="" && $_FILES['producto_foto']['size']>0){

    #creando directorio de imagenes
    if(!file_exists($img_dir)){
        if(!mkdir($img_dir,0777,TRUE)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Error al crear el directorio
                </div>
                ';  
                exit();
        }

    }

    #VERIFICANDO FORMATOS DE IMAGENES
    if(mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" &&
    mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/png"){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La imagen que ha seleccionado es de un formato no permitido
            </div>
            ';  
            exit();
    }

    #FERIFICANDO EL TAMAÑO DE LA FOTO

    if(($_FILES['producto_foto']['size']/1024)>5120){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La imagen que ha seleccionado ha superado el tamaño permitido
            </div>
            ';  
            exit();
    }

    #EXTENSION DE LA IMAGEN
    switch(mime_content_type($_FILES['producto_foto']['tmp_name'])) {
        case'image/jpeg':
            $img_ext=".jpg";
        break;

        case'image/png':
            $img_ext=".png";
        break;
    }
    chmod($img_dir,0777);
    $img_nombre=renombrar_fotos($nombre);
    $foto=$img_nombre.$img_ext;

    #MOVIENDO LAS IMAGENES AL DIRECTORIO
    if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No podemos cargar la imagen al sistema en este momento
            </div>
            ';  
            exit();
    }
}else{
        $foto="";
}

#GUARDANDO DATOS

$guardar_producto=conexion();
$guardar_producto= $guardar_producto->prepare("INSERT INTO producto
(producto_codigo,producto_nombre,producto_precio,producto_stock,producto_foto
,categoria_id,usuario_id)VALUES(:codigo,:nombre,:precio,:stock,:foto,:categoria,:usuario)");

$marcadores=[
    ":codigo"=>$codigo,
    ":nombre"=>$nombre,
    ":precio"=>$precio,
    ":stock"=>$stock,
    ":foto"=>$foto,
    ":categoria"=>$categoria,
    ":usuario"=>$_SESSION['id']

];

$guardar_producto->execute($marcadores);

if($guardar_producto->rowCount()==1){
    echo 
        '<div class="notification is-info is-light">
                <strong>PRODUCTO REGISTRADO</strong><br>
                El producto se registro con exito.
         </div>
         ';  
}else{

        if(is_file($img_dir.$foto)){
            chmod($img_dir,0777);
            unlink($img_dir.$foto);
        }
    echo 
        '<div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo registrar el producto, Por favor intente nuevamente
        </div>
    ';  
}

$guardar_producto=null;

