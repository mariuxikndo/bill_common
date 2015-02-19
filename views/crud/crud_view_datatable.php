
    <div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>

<?php
    $js = array(
         base_url('assets/grocery_crud/themes/datatables/js/datatables.js'),
         base_url('assets/grocery_crud/js/jquery_plugins/jquery.easing-1.3.pack.js'),   
         base_url('resources/js/libs/jsPanel/source/AC_RunActiveContent.js'),
         base_url('resources/js/libs/jsPanel-bootstrap/source/jquery.jspanel.bs-1.4.0.min.js'),
    );
    echo jsload($js);  


