<!doctype html>
<html class='no-js' lang='<?=$lang?>'> <!-- Modernizr will replace the class 'no-js' with a list of features supported by the browser -->
<head>
<meta charset='utf-8'/>
<title><?=get_title($title)?></title>

<?php if(isset($favicon)): ?>
	<link rel='shortcut icon' href='<?=$favicon?>'/>
<?php endif ?>

<?php foreach($stylesheets as $val): ?>
<?php if(isset($inlinestyle)): ?><style><?=$inlinestyle?></style><?php endif; ?>
<link rel='stylesheet' type='text/css' href='<?=$val?>'/>
<?php endforeach; ?>


<script src='<?=$modernizr?>'></script>
</head>
<body>
  <div id='wrapper'>
    <div id='header'><?=$header?>
    <?php if(isset($navbar)): ?><div id='navbar'><?=get_navbar($navbar)?></div><?php endif; ?>
   </div>
	<div id='content'>
	    <div id='main'><?=$main?></div>
	</div>
    <?php if(isset($sidebar)): ?>
    <aside id='sidebar'><?= $sidebarTitle ?><br><?=$sidebar?></aside>
    <?php endif; ?>
    <div id='footer'><?=$footer?></div>
  </div>
<?php if(isset($jquery)):?><script src='<?=$jquery?>'></script><?php endif; ?>
 
<?php if(isset($javascript_include)): foreach($javascript_include as $val): ?>
<script src='<?=$val?>'></script>
<?php endforeach; endif; ?>
 
<?php if(isset($google_analytics)): ?>
<script>
  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>


</body>
</html>
  	 

