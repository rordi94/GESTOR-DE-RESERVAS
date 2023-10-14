<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="index.php?vista=home">
      <img src="./img/losaltos.jpg" width="100" height="150">
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      

               <div class="navbar-item has-dropdown is-hoverable">
                  <a class="navbar-link">
                  USUARIOS
                  </a>

                  <div class="navbar-dropdown">
                      <a class="navbar-item" href="index.php?vista=user_new">Nuevo</a >
                      <a class="navbar-item"href="index.php?vista=user_list">Lista  </a>
                      <a class="navbar-item" href="index.php?vista=user_search">Buscar</a>
                  </div>
              </div>
                  <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                           CATEGORIAS
                         </a>
                      <div class="navbar-dropdown">
                          <a class="navbar-item" href="index.php?vista=category_new"
                          >Nuevo</a >
                          <a class="navbar-item" href="index.php?vista=category_list"
                          >Lista  </a>
                          <a class="navbar-item" href="index.php?vista=category_search"
                          >Buscar</a>
                      </div>     
                  </div>  
              
                  <div class="navbar-item has-dropdown is-hoverable">
                  <a class="navbar-link">
                  PRODUCTOS
                  </a>

                  <div class="navbar-dropdown">
                      <a class="navbar-item"  href="index.php?vista=product_new">
                        Nuevo</a >
                      <a class="navbar-item" href="index.php?vista=product_list"> 
                        Lista</a>
                      <a class="navbar-item"  href="index.php?vista=product_category">
                        Por Categorias</a>
                      <a class="navbar-item" href="index.php?vista=product_search">
                        Buscar</a>

                  </div>
              </div>

              <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                           PRESUPUESTO
                         </a>
                      <div class="navbar-dropdown">
                          <a class="navbar-item" href="index.php?vista=category_new"
                          >Nuevo</a >
                          <a class="navbar-item" href="index.php?vista=category_list"
                          >Nuevo  </a>
                          <a class="navbar-item" href="index.php?vista=category_search"
                          >Buscar</a>
                      </div>     
                  </div>  
      </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a href="index.php?vista=user_update&user_id_up=<?php echo $_SESSION['id'];  ?>" class="button is-primary is-rounded" >
            MI CUENTA
        </a>
          <a href="index.php?vista=logout"  class="button is-link is-rounded">
            SALIR
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>