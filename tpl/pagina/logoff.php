<title>Logoff | <?php echo SITENAME; ?></title>
<meta name="title" content="Logoff | <?php echo SITENAME.' - '.SITETAGS; ?>" />
<meta name="description" content="<?php echo SITEDESC; ?>" />
<meta name="keywords" content="<?php echo SITETAGS; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />   
<meta name="url" content="<?php echo BASE; ?>/pagina/logoff" />  
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<?php
	unset($_SESSION['autUser']);
	header('Location: '.BASE);
?>

</div><!-- //content -->