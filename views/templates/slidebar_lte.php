      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <?php
                echo $this->load->view('login/user_logo','',TRUE);
            ?>
          </div>          
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
              <?php
                if(!empty($section_links)){
                    echo $section_links;
                }
              ?>
          </ul>
          
            <br class="clr"/>          
            <?php
              if(!empty($slidebar_actions)){
                  echo $slidebar_actions;
              }
            ?>          
        </section>
        <!-- /.sidebar -->
      </aside>