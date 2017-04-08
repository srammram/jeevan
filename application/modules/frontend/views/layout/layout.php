<!doctype html>
<html>
<head>
<?php load_meta_tags(array('metatitle'=>(isset($meta_title)? $meta_title: ''),'metacontent'=>(isset($meta_content)? $meta_content : ''),'metakeyword'=>(isset($meta_keyword)? $meta_keyword : '')));?>

<meta charset="utf-8">
   <?php echo $this->load->view('layout/header-includes');?>
</head>
<body class="page-<?php echo str_replace("/", ' ', uri_string()); ?>">
	<?php echo $this->load->view('layout/header');?>
	<?php echo $site_body; ?>	
    <?php echo $this->load->view('layout/footer');?>  
    <?php echo $this->load->view('layout/footer-includes');?>      
</body>
</html>
