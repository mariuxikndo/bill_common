<?php
$css = array(
    base_url('resources/bootstrap-3.2.0/css/bootstrap.min.css'),
    base_url('resources/sb_admin/css/plugins/metisMenu/metisMenu.min.css'),
    base_url('resources/sb_admin/css/sb-admin-2.css'),
    base_url('resources/sb_admin/font-awesome-4.1.0/css/font-awesome.min.css'),  
    base_url('resources/bootstrap-3.2.0/css/bootstrap-theme.css')
);
echo csslink($css);

foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; 
            
        echo Open('div', array('class'=>'row','id'=>'container-fluid'));
               echo $output; 
        echo Close('div'); //cierra div .row
//echo Close('div'); //cierra div .row

$js = array(       
    base_url('resources/bootstrap-3.2.0/js/bootstrap.min.js'),
    base_url('resources/sb_admin/js/plugins/metisMenu/metisMenu.min.js'),
    base_url('resources/sb_admin/js/sb-admin-2.js'),    
);
echo jsload($js);