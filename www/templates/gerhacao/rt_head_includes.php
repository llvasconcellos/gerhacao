<?php

// This information has been pulled out of index.php to make the template more readible.
//
// This data goes between the <head></head> tags of the template

?>

<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<link rel="shortcut icon" href="<?php echo $mosConfig_live_site;?>/images/favicon.ico" />
<?php if($mtype=="moomenu" or $mtype=="suckerfish") :?>
<link href="<?php echo $template_path; ?>/css/rokmoomenu.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<link href="<?php echo $template_path; ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<?php if($show_moduleslider=="true") :?>
<link href="<?php echo $template_path; ?>/css/rokslidestrip.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<link href="<?php echo $template_path; ?>/css/headers.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $template_path; ?>/css/<?php echo $bstyle; ?>.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	div.wrapper { <?php echo $template_width; ?>padding:0;}
	td.leftcol { width:<?php echo $leftcolumn_width; ?>px;padding:0;}
	td.rightcol { width:<?php echo $rightcolumn_width; ?>px;padding:0;}
</style>	
<?php if (rok_isIe7()) :?>
<!--[if IE 7]>
<link href="<?php echo $template_path; ?>/css/template_ie7.css" rel="stylesheet" type="text/css" />	
<![endif]-->	
<?php endif; ?>
<?php if (rok_isIe6()) :?>
<!--[if lte IE 6]>
<link href="<?php echo $template_path; ?>/css/template_ie6.php" rel="stylesheet" type="text/css" />
<![endif]-->
<?php endif; ?>
<?php if(!mootoolsExists()) :?>
<script type="text/javascript" src="<?php echo $template_path; ?>/js/mootools-release-1.11.js"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo $template_path; ?>/js/roktoppanel.js"></script>
<?php if($show_moduleslider=="true") :?>
<script type="text/javascript" src="<?php echo $template_path; ?>/js/rokslidestrip.js"></script>
<?php endif; ?>
<?php if($enable_ie6warn=="true") : ?>
<script type="text/javascript" src="<?php echo $template_path; ?>/js/rokie6warn.js"></script>
<?php endif; ?>
<?php if($mtype=="moomenu") :?>
<script type="text/javascript" src="<?php echo $template_path; ?>/js/rokmoomenu.js"></script>
<script type="text/javascript" src="<?php echo $template_path; ?>/js/mootools.bgiframe.js"></script>
<script type="text/javascript">
window.addEvent('domready', function() {
	new Rokmoomenu($E('ul.menutop '), {
		bgiframe: false,
		delay: 500,
		animate: {
			props: ['height'],
			opts: {
				duration:700,
				fps: 300,
				transition: Fx.Transitions.Back.easeOut
			}
		}
	});
});
</script>
<?php endif; ?>	
<?php if($mtype=="suckerfish" or $mtype=="splitmenu") :
	echo "<!--[if IE]>\n";		
  include_once( "$mosConfig_absolute_path/templates/" . $mainframe->getTemplate() . "/js/ie_suckerfish.js" );
  echo "<![endif]-->\n";
endif; ?>	
<?php if (rok_isIe6()) :?>
<script type="text/javascript">
window.addEvent('domready', function() {
if (window.ie6) {
 var mainShadow = $('main-shadow'), featuredShadow = $('featured-shadow');
 if (!mainShadow || !featuredShadow) return false;
 else {
  window.addEvent('resize', function() {
   var size = mainShadow.getPosition().x;
   featuredShadow.setStyle('margin-left', size - 63);
  });
  window.fireEvent('resize');
 }
};
});
</script>
<?php endif; ?>