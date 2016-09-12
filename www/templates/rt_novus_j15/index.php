<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );
define( 'YOURBASEPATH', dirname(__FILE__) );
require( YOURBASEPATH.DS."rt_styleswitcher.php");
JHTML::_( 'behavior.mootools' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<?php
	$live_site        = $mainframe->getCfg('live_site');
	$side_column	  = $this->params->get("sideColumn", "25%");
	$template_width   = $this->params->get("templateWidth", "950"); 
	$menu_name        = $this->params->get("menuName", "mainmenu");
	$menu_type        = $this->params->get("menuType", "splitmenu");
	$default_font     = $this->params->get("defaultFont", "default");
	$show_breadcrumbs = ($this->params->get("showBreadcrumbs", 0)  == 0)?"false":"true";
    $iepng_fix        = ($this->params->get("iepngFix", 0)  == 0)?"false":"true";

	require(YOURBASEPATH .DS."rt_styleloader.php");
	require(YOURBASEPATH .DS."rt_utils.php");

?>
	<link rel="shortcut icon" href="<?php echo $this->baseurl; ?>/images/favicon.ico" />
	
	<?php
	if (eregi("MSIE 6",getenv("HTTP_USER_AGENT")) ||	eregi("Internet Explorer 6",getenv("HTTP_USER_AGENT"))) {
		?>
			<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie_css.css" rel="stylesheet" type="text/css" />		
		<?
	}
	elseif (eregi("MSIE 7",getenv("HTTP_USER_AGENT")) || eregi("Internet Explorer",getenv("HTTP_USER_AGENT"))) {
		?>
			<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie7_css.css" rel="stylesheet" type="text/css" />	
		<?
	}
	 else {
		?>
			<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_css.css" rel="stylesheet" type="text/css" />	
		<?
	   }
	?>

	<?php
	  // 
				  
	?>
	
	<style type="text/css">
		img { behavior: url(iepngfix.htc) }
	</style> 
	<style type="text/css">
		div.wrapper { <?php echo $template_width; ?>}
		#sidecol { width: <?php echo $side_column; ?>;}
		#main-column { margin-left: <?php echo $side_column; ?>;}
	</style>	
	<?php if (isIe6()) :?>
		<!--[if lte IE 6]>
		<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie6.css" rel="stylesheet" type="text/css" />

	<![endif]-->
	<?php endif; ?>
	<?php if($mtype=="splitmenu") :
		echo "<!--[if IE]>\n";		
	  require( YOURBASEPATH.DS."js/ie_splitmenu.js");
	  echo "<![endif]-->\n";
	endif; ?>	
	</head>
	<body class="<?php echo $fontstyle; ?> <?php echo $tstyle; ?>">
		<div id="menu-bar">
			<div class="wrapper">
				<div id="horiz-menu" class="<?php echo $mtype; ?>">
					<?php if($mtype != "module") :
						echo $mainnav;
					else: ?>
					<jdoc:include type="modules" name="toolbar" style="none" />
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div id="inset" style="text-align:center; vertical-align:middle;">
		<div id="logoset">
            <object data="logo.swf" type="application/x-shockwave-flash" height="199" width="950">
                <param name="src" value="logo.swf" />
                <param name="wmode" value="transparent" />
                <param name="base" value="/" />
                <param name="name" value="logo.swf" />
            </object>
        </div>
	</div>
	<div id="content">
		<div class="wrapper">
			<?php if ($this->countModules('left') or $subnav) : ?>
				<div id="sidecol">
					<div id="side-column">
						<div class="padding">
							<div class="inner">
								<?php if($subnav) : ?>
									<div id="sub-menu">
										<?php echo $subnav; ?>
									</div>
								<?php endif; ?>
								<jdoc:include type="modules" name="left" style="xhtml" />
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div id="main-column">
				<div class="padding">
					<div class="inner">
						<?php if ($show_breadcrumbs == "true") : ?>
							<jdoc:include type="module" name="breadcrumbs" style="none" />
						<?php endif; ?>
						<div id="top">
							<jdoc:include type="modules" name="top" style="xhtml" />
						</div>
						<?php if ($this->countModules('user1') or $this->countModules('user2')) : ?>
							<div id="topmodules" class="spacer<?php echo $topmod_width; ?>">
								<?php if ($this->countModules('user1')) : ?>
									<div class="block">
										<jdoc:include type="modules" name="user1" style="xhtml" />
									</div>
								<?php endif; ?>
								<?php if ($this->countModules('user2')) : ?>
									<div class="block">
										<jdoc:include type="modules" name="user2" style="xhtml" />
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="contentpadding">
							<jdoc:include type="modules" name="user8" style="xhtml" />
							<jdoc:include type="component" />
						</div>
						<?php if ($this->countModules('user3') or $this->countModules('user4')) : ?>
							<div id="bottommodules" class="spacer<?php echo $bottommod_width; ?>">
								<?php if ($this->countModules('user3')) : ?>
									<div class="block">
										<jdoc:include type="modules" name="user3" style="xhtml" />
									</div>
								<?php endif; ?>
								<?php if ($this->countModules('user4')) : ?>
									<div class="block">
										<jdoc:include type="modules" name="user4" style="xhtml" />
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="clr"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clr"></div>
	<div id="bottom">
		<div class="wrapper">
			<?php if ($this->countModules('user5') or $this->countModules('user6') or $this->countModules('user7')) : ?>
				<div id="topmodules" class="spacer<?php echo $footermod_width; ?>">
					<?php if ($this->countModules('user5')) : ?>
						<div class="block">
							<jdoc:include type="modules" name="user5" style="xhtml" />
						</div>
					<?php endif; ?>
					<?php if ($this->countModules('user6')) : ?>
						<div class="block">
							<jdoc:include type="modules" name="user6" style="xhtml" />
						</div>
					<?php endif; ?>
					<?php if ($this->countModules('user7')) : ?>
						<div class="block">
							<jdoc:include type="modules" name="user7" style="xhtml" />
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div align="center">
				<br /><br />
				GeRHa&ccedil;&atilde;o - Rua General Camara, 195 - Bom Retiro - Joinville - Santa Catarina - (47) 3025-1848  <br />
				Todos os direitos Reservados
				<p style="font-size:10px;">Desenvolvido por <a href="http://www.devhouse.com.br">DevHouse</a></p>

				<br /><br />
			</div>
		</div>
	</div>
</body>	
</html>