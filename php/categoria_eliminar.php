<?php
$category_id_del=limpiar_cadena($_GET['category_id_del']);

    //verificando Categoria

    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria 
    WHERE categoria_id='$category_id_del'");

    if($check_categoria->rowCount()==1){
             //verificando categoria

             $check_productos=conexion();
             $check_productos=$check_productos->query("SELECT categoria_id FROM producto 
             WHERE categoria_id='$category_id_del' LIMIT 1");

            if($check_productos->rowCount()<=0){
                    
                $eliminar_categoria=conexion();
                $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id=:id");
               
                $eliminar_categoria->execute([":id"=>$category_id_del]);

                //Cerrando la conexion

                if($eliminar_categoria->rowCount()==1){
                    echo'
                    <div class="notification is-info is-light">
                        <strong> Categoria Eliminada </strong><br>
                        La categoria fue eliminado correctamente
                    </div>
                    '; 

                }else{
                    echo'
                    <div class="notification is-danger is-light">
                        <strong> ocurrio un error inesperado </strong><br>
                        la categoria no pudo se eliminado, por favor intente nuevamente
                    </div>
                    '; 
                }
                $eliminar_categoria=null;

            }else{
                echo'
                <div class="notification is-danger is-light">
                    <strong> ocurrio un error inesperado </strong><br>
                   No podemos eliminar la categoria ya que tiene productos registrados
                </div>
                '; 

            }

            $check_productos=null;

    }else{
        echo'
        <div class="notification is-danger is-light">
            <strong> ocurrio un error inesperado </strong><br>
            La categoria que intenta eliminar no existe
        </div>
        ';

    } 
    $check_usuario=null;



            