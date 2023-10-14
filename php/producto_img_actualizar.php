<?php
require_once "../php/main.php";
$product_id=limpiar_cadena($_POST['img_up_id']);

//verificar el producto
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id'");
if($check_producto->rowCount()==1){
    $datos=$check_producto->fetch();
        
}else{
    echo   
     '<div class="notification is-danger is-light">
                <strong>ocurrio un error inesperado! </strong><BR>
           La imagen del  producto no existe en el sistema
      </div>
  ';
    exit();
}
$check_producto=null;

#DIRECTORIO DE IMAGENES
$img_dir="../img/producto/";


#COMPROBAR SI SE SELECCIONO UNA IMAGEN
if($_FILES['producto_foto']['name']==""  ||  $_FILES['producto_foto']['size']==0){

    echo   
    '<div class="notification is-danger is-light">
               <strong>ocurrio un error inesperado! </strong><BR>
            NO HA SELECCIONADO NINGUNA IMAGEN VALIDA
     </div>
 ';
   exit();


}

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

chmod($img_dir,0777);

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

    $img_nombre=renombrar_fotos($datos['producto_nombre']);
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

    if(is_file($img_dir.$datos['producto_foto'] && $datos['producto_foto']!=$foto)){

        chmod($img_dir.$datos['producto_foto'],0777);
        unlink($img_dir.$datos['producto_foto']);
    }

#ACTUALIZAR DATOS
    
 $actualizar_producto=conexion();

$actualizar_producto=$actualizar_producto->prepare("UPDATE producto SET 
producto_foto=:foto WHERE producto_id=:id");

$marcadores=[
    ":foto"=>$foto,
    ":id"=>$product_id
 
];

if($actualizar_producto->execute($marcadores)){

    echo '<div class="notification is-info is-light">
    <strong>¡IMAGEN O FOTO ACTUALIZADA!</strong><br>
    La FOTO has ido actualizada con exito,pulse aceptar para 
    recargar los cambios
   
    <p class="has-text-centered pt-5 pb-5">  
        <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is link is rounded"> Aceptar  </a>
      </p>

  </div>';

exit();


}else{
    
    if(is_file($img_dir.$foto)){

        chmod($img_dir.$foto,0777);
        unlink($img_dir.$foto);
    }
    
        echo '<div class="notification is-warnig is-light">
        <strong>¡OCURRIO UN ERROR!</strong><br>
       No podemos subir la imagen en este momento, por favor intente nuevamente
      
       </div>
';
}


$actualizar_producto=null;