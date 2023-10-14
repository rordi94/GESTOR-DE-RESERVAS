<?php
    $product_id_del=limpiar_cadena($_GET['product_id_del']);

    //verificando Producto

    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM producto WHERE
     producto_id='$product_id_del'");

    if($check_producto->rowCount()==1){
   
                $datos=$check_producto->fetch();

                $eliminar_producto=conexion();
                $eliminar_producto=$eliminar_producto->prepare("DELETE FROM producto WHERE
                producto_id=:id");

                $eliminar_producto->execute([":id"=>$product_id_del]); 

                    if(is_file("./img/producto/".$datos['producto_foto'])){
                            chmod("./img/producto/".$datos['producto_foto'],0777);
                            unlink("./img/producto/".$datos['producto_foto']);
                    }


                if($eliminar_producto->rowCount()==1){
                    echo'
                    <div class="notification is-info is-light">
                        <strong> Producto </strong><br>
                        El producto se elimino correctamente
                    </div>
                    '; 
 
                }else{
                    echo'
                    <div class="notification is-danger is-light">
                        <strong> ocurrio un error inesperado </strong><br>
                        El producto no pudo se eliminado, por favor intente nuevamente
                    </div>
                    '; 
                }
                $eliminar_producto=null;


    }else{
        echo'
        <div class="notification is-danger is-light">
            <strong> ocurrio un error inesperado </strong><br>
            El Producto que intenta eliminar no existe
        </div>
        ';

    } 
    $check_producto=null;