<style>
    .skin-blue .main-header .navbar {
        background-color: #<?= get_settings('NAV_COLOR')?>;
    } 
    .skin-blue .main-header .logo {
        background-color: #<?= get_settings('NAV_COLOR')?>;
        border-bottom: 0 solid transparent;
        color: #ffffff;
        font-weight: bold; font-size: 30px; text-decoration: none; 
        font-family: "lucida grande",tahoma,verdana,arial,sans-serif            
    }
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
        /*background: none repeat-x scroll 50% 50% #<?= get_settings('NAV_COLOR')?>;*/
        background-image: linear-gradient(to bottom, #<?= get_settings('NAV_COLOR')?> 99%, #fff 90%);        
        border: 1px solid #bbb;
        color: #fff;
        font-weight: bold;
    }      
</style>     
<header class="main-header">
        <!-- Logo -->
        <a class="logo" href="<?= base_url() ?>">Billingsof</a>        
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
          <?php
          if(!empty($top_nav_actions)){
                echo $top_nav_actions;              
          }
          ?>
              <!--</li>-->
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="glyphicon glyphicon-th-list"></i>
                  <!--<span class="label label-warning">10</span>-->
                </a>
                  
                <ul class="dropdown-menu">
                  <li class="header">Modulos Habilitados</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <?php
                    $allcapabilities = $this->user->getAllCapabilities();
                    
                    $cont = 0; $tabclass = ''; 
                    $label = '';
                    foreach ( $allcapabilities as $tab) {                        
                        if($tab->icon != ''){ $label = '<span class="'.$tab->icon.'"></span>&nbsp;<span class="">'.$tab->label.'</span>'; }else{ $label = $tab->label; }
                        
                        if($tab->tipo == 'module'){
                            $uritab = '';
                                if($tab->ubicacion != ''){ $uritab = $tab->ubicacion; }
                                if($tab->interno == 1){ $base_path = base_url();
                                }else{ $base_path = ''; }

                            if($tab->acceso == 'noregistered'  && empty($this->user->userid) || !empty($this->user->userid) && $this->user->essuperusuario == 1){
                                echo '<li class="'.$tabclass.'">';
                                    echo '<a href="'.$base_path.$uritab.'" data-toggle="tooltipx" title="<h4>'.$tab->descripcion.'</h4>"/>'.$label.'</a>';
                                echo '</li>';
                            continue;
                            }

                            if( $tab->acceso == 'all' || ($tab->acceso == 'registered' && !empty($this->user->userid)) ){
                                echo '<li class="'.$tabclass.'">';
                                    echo '<a href="'.$base_path.$uritab.'" data-toggle="tooltipx" title="'.$tab->descripcion.'"> '.$label.'</a>';
                                echo '</li>';
                            continue;
                            }else{
                                if(!empty($this->user->userid)){
                                    echo '<li class="'.$tabclass.'">';
                                            foreach ($this->user->get_assigned_role() as $ut) {
                                                if($ut->capacidad == $tab->capacidad){
                                                    echo '<a href="'.$base_path.$uritab.'" data-toggle="tooltipx" title="'.$tab->descripcion.'" > '.$label.' </a>';
                                                    continue;
                                                }
                                            }
                                    echo '</li>';
                                }
                            }
                        }                        
                      $cont++;
                    } 
                        ?>
                    </ul>
                  </li>
                  <li class="footer"><a href="<?= base_url() ?>">Ver Todos</a></li>
                </ul>
              </li>

              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="glyphicon glyphicon-user"></i>
                  <!--<span class="label label-warning">10</span>-->
                </a>
                  
                <ul class="dropdown-menu">
                  <li class="header">Opciones De Usuario</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> <?=  $this->user->username ?></a>
                        </li>
                        <li><a href="<?= base_url('login/editprofile') ?>"><i class="fa fa-gear fa-fw"></i> Configuraci&oacute;n</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?=  base_url('login/welcome/logout') ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="<?= base_url('login/editprofile') ?>">Mas Opciones</a></li>
                </ul>
              </li>
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="glyphicon glyphicon-question-sign"></i>
                  <span class="label label-danger">Ayuda!</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Ayuda Billingsof</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="<?= base_url('/help/') ?>">
                          <h3>
                            Manual de Usuario
                            <small class="pull-right">80%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">80% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->

                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>