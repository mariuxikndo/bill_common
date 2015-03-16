<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?= get_settings('SYSTEM_NAME')?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
<!--    <link href="<?= base_url('resources/AdminLTE-2.0.3') ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    -->
    <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
    <!-- Theme style -->
    <link href="<?= base_url('resources/AdminLTE-2.0.3') ?>/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?= base_url('resources/AdminLTE-2.0.3') ?>/dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?= base_url('resources/AdminLTE-2.0.3') ?>/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <!--<link href="<?= base_url('resources/AdminLTE-2.0./') ?>/plugins/morris/morris.css" rel="stylesheet" type="text/css" />-->
    <!-- jvectormap -->
    <!--<link href="<?= base_url('resources/AdminLTE-2.0.3') ?>/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />-->
    <!-- Date Picker -->
    <!--<link href="<?= base_url('resources/AdminLTE-2.0.3') ?>/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />-->
    <!-- Daterange picker -->
    <!--<link href="<?= base_url('resources/AdminLTE-2.0.3') ?>/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
    <?php
        echo input(array('type'=>'hidden','id'=>'main_path','value'=>  base_url()));    
        $css = array(
            base_url('resources/bootstrap-3.2.0/css/bootstrap.min.css'),
//            base_url('resources/sb_admin/css/plugins/metisMenu/metisMenu.min.css'),
//            base_url('resources/sb_admin/css/sb-admin-2.css'),
//            base_url('resources/sb_admin/font-awesome-4.1.0/css/font-awesome.min.css'),    
            base_url('resources/bootstrap-3.2.0/css/bootstrap-theme.css'),
            base_url('resources/js/libs/combobox/css/bootstrap-combobox.css'),    
            base_url('assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css'),   
            base_url('assets/grocery_crud/themes/datatables/css/demo_table_jui.css'),
            base_url('assets/grocery_crud/themes/datatables/css/datatables.css'),
            base_url('assets/grocery_crud/themes/datatables/css/jquery.dataTables.css'),
//            base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/css/TableTools.css'),
            base_url('resources/css/datepicker.css'),    
            base_url('resources/js/libs/sco.js/css/sco.message.css'),
            base_url('resources/js/libs/jsPanel-bootstrap/source/jsPanel.css'),
            base_url('resources/js/libs/autosuggest/css/style.css'),
            base_url('resources/js/libs/pick-a-color/build/1.2.3/css/pick-a-color-1.2.3.min.css'),
            base_url('resources/css/style.css'),     
        );
        echo csslink($css);    
    
        $js = array(
            base_url('assets/grocery_crud/js/jquery-1.10.2.min.js'),
            base_url('assets/grocery_crud/js/jquery_plugins/jquery.noty.js'),
            base_url('assets/grocery_crud/js/jquery_plugins/config/jquery.noty.config.js'),
            base_url('assets/grocery_crud/js/common/lazyload-min.js'),
            base_url('assets/grocery_crud/js/common/list.js'),
            base_url('assets/grocery_crud/themes/datatables/js/jquery.dataTables.min.js'),
            base_url('assets/grocery_crud/themes/datatables/js/datatables-extras.js'),
            base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/js/ZeroClipboard.js'),
            base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/js/TableTools.min.js'),
            base_url('assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js'),    
            base_url('resources/bootstrap-3.2.0/js/bootstrap.min.js'),
            base_url('resources/js/comunes/printThis.js'),
            base_url('resources/js/libs/sco.js/js/sco.valid.js'),
            base_url('resources/js/libs/sco.js/js/sco.modal.js'),
            base_url('resources/js/libs/sco.js/js/sco.message.js'),
            base_url('resources/js/libs/sco.js/js/sco.ajax.js'),
            base_url('resources/js/libs/jform/jquery.form.js'),
            base_url('resources/js/bootstrap-datepicker.js'),
            base_url('resources/js/bootstrap-datepicker.es.js'),
            base_url('resources/js/libs/autosuggest/bootstrap-typeahead.js'),
            base_url('resources/js/libs/autosuggest/hogan-2.0.0.js'),
            base_url('resources/js/libs/jsPanel/source/AC_RunActiveContent.js'),
            base_url('resources/js/libs/jsPanel-bootstrap/source/jquery.jspanel.bs-1.4.0.min.js'),              
            base_url('resources/js/libs/combobox/js/bootstrap-combobox.js'),
            base_url('resources/js/libs/numeric/jquery.numeric.js'),
            base_url('resources/js/libs/combobox/js/bootstrap-combobox.js'),    
            base_url('resources/js/libs/pick-a-color/build/dependencies/tinycolor-0.9.15.min.js'),    
            base_url('resources/js/libs/pick-a-color/src/js/pick-a-color.js'),    
            base_url('resources/js/comunes/jquery.blockUI.js'),     
            base_url('resources/js/modules/comunes.js'),
        //    base_url('resources/js/modules/ajuste_entrada.js'),
            base_url('resources/sb_admin/js/plugins/metisMenu/metisMenu.min.js'),
            base_url('resources/sb_admin/js/sb-admin-2.js')
        );
        echo jsload($js);    
    ?>
  </head>
  <body class="skin-blue">
    <div class="wrapper">
        <?php

    
        $open_content_div = '';
        $close_content_div = '';
                
            echo $this->load->view('common/templates/navigation_lte','',TRUE);
            
            if(!empty($slidebar)){                
                echo $slidebar;   
                $open_content_div = Open('div', array('class'=>'content-wrapper'));
                $close_content_div = Close('div');                
            }
            
      /* Content Wrapper. Contains page content */      
      echo $open_content_div;
        ?>

        <!-- Content Header (Page header) -->
        <section class="content-header col-md-12" style="background: #ddd; margin-bottom: 5px; padding: 2px; border-bottom: solid 1px #ddddee">
<!--          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>-->
                            <?php
                            if(!empty($top_nav_actions)){
                                  echo $top_nav_actions;              
                            }
                            ?>              
<!--          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>-->
        </section>
        
        <!-- Main content -->
        <section class="content">
          <!-- Main row -->
          <div class="row">
              <?php
                echo $view;
              ?>
          </div> <!--/.row (main row) -->

        </section> <!--/.content -->
        <?php
            echo $close_content_div;
        ?>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0
        </div>
          <strong>Copyright &copy; 2015-2016 <a href="<?=  base_url()?>">Masterpc</a>.</strong> All rights reserved.
      </footer>
    </div><!-- ./wrapper -->



    <!-- AdminLTE App -->
    <script src="<?= base_url('resources/AdminLTE-2.0.3') ?>/dist/js/app.min.js" type="text/javascript"></script>

  </body>
</html>