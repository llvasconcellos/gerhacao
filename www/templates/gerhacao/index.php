<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
define( 'YOURBASEPATH', $mosConfig_absolute_path."/templates/" . $mainframe->getTemplate() );
require( YOURBASEPATH . "/rt_styleswitcher.php");


	// *************************************************
	// Change the variables below to adjust the template
	//
	// If you have any issues, check out the forum at
	// http://www.rockettheme.com
	//
	// *************************************************

	$default_theme 				= "theme4";			 // [theme1... theme6]
	$body_style                 = "style1";			 // [style1... style6]
	$header_style               = "header1";		 // [header1... header10]
	$enable_ie6warn             = "true";            // true | false
	$font_family                = "catalyst";        // catalyst | geneva | optima | helvetica | trebuchet | lucida | georgia | palatino
	$template_width 			= "958";			 // width in px
	$leftcolumn_width			= "275";			 // width in px
	$rightcolumn_width			= "275";			 // width in px
	$splitmenu_col				= "leftcol";		 // leftcol | rightcol
	$menu_name 					= "mainmenu";		 // mainmenu by default, can be any Joomla menu name
	$menu_type 					= "moomenu";		 // moomenu | suckerfish | splitmenu | module
	$default_font 				= "default";         // smaller | default | larger
	$show_pathway 				= "false";			 // true | false
	$show_moduleslider			= "true";		     // true | false

	// module slider configuration
	
	$max_mods_per_row			= 3;				 // maximum number of modules per row (adjust the height if this wraps)
	$modules_list 				= array(array("title"=>"Group 1 Stuff", "module"=>"user8"),
								array("title"=>"Group 2 Panel", "module"=>"user9"),
								array("title"=>"Group 3 Collection", "module"=>"user10"),
								array("title"=>"Group 4 Assortment", "module"=>"user11"),
								array("title"=>"Group 5 Items", "module"=>"user12"));
								
	require(YOURBASEPATH . "/rt_styleloader.php");
	
	if ($theme != "custom") setStyles($theme);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php
	if ( $my->id ) {
		initEditor();
	}
	mosShowHead();	
					
	require(YOURBASEPATH . "/rt_tabmodules.php");
	require(YOURBASEPATH . "/rt_utils.php");
    require(YOURBASEPATH . "/rt_head_includes.php");
    ?>
	</head>
	<body id="ff-<?php echo $fontfamily; ?>" class="<?php echo $fontstyle; ?> <?php echo $bstyle; ?> <?php echo $hstyle; ?>">
		<!-- begin top panel -->
		<?php if (mosCountModules('top')) : ?>
			<div id="topmod">
				<div class="wrapper">
					<?php mosLoadModules('top',-2); ?>
				</div>
			</div>
		<?php endif; ?>
		<!-- end top panel -->
		<!-- begin header -->
		<div id="header">
			<div class="wrapper">
				<?php if (mosCountModules('search')) : ?>
					<div id="searchmod">
						<?php mosLoadModules('search',-2); ?>
					</div>
				<?php endif; ?>
				<?php if (mosCountModules('top')) : ?>
				<div id="top-tab">
					<span class="tab-text">Área Restrita</span>
				</div>
				<?php endif; ?>
				<a href="<?php echo $mosConfig_live_site;?>" class="nounder"><img src="<?php echo $template_path; ?>/images/blank.gif" border="0" alt="" id="logo" /></a>
			</div>
		</div>
		<!-- end header -->
		<!-- begin menu bar -->
		<div id="horiz-menu" class="<?php echo $mtype; ?>">
			<div class="wrapper">
				<?php if($mtype != "module") : ?>
					<?php echo $topnav->display(); ?>
				<?php else: ?>
					<?php mosLoadModules('toolbar',-1); ?>
				<?php endif; ?>
			</div>
		</div>
		<!-- end menu bar -->
		<!-- begin showcase -->
		<?php if (mosCountModules('header')) : ?>
		<div id="showcase">
			<div id="showcase2">
				<div class="wrapper">
					<div id="showcase-promo">
						<?php mosLoadModules('header',-2); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<!-- end showcase -->
		<!-- begin featured mods -->
		<?php if (mosCountModules('advert1') or mosCountModules('advert2') or mosCountModules('advert3') or mosCountModules('advert4')) : ?>
			<div id="featured-mod">
				<div id="featured-mod2">
					<div id="featured-shadow" class="png">
						<div class="wrapper">
							<div id="featuredmodules" class="spacer<?php echo $featuredmods_width; ?>">
								<?php if (mosCountModules('advert1')) : ?>
									<div class="block">
										<?php mosLoadModules('advert1',-3); ?>
									</div>
								<?php endif; ?>
								<?php if (mosCountModules('advert2')) : ?>
									<div class="block">
										<?php mosLoadModules('advert2',-3); ?>
									</div>
								<?php endif; ?>
								<?php if (mosCountModules('advert3')) : ?>
									<div class="block">
										<?php mosLoadModules('advert3',-3); ?>
									</div>
								<?php endif; ?>
								<?php if (mosCountModules('advert4')) : ?>
									<div class="block">
										<?php mosLoadModules('advert4',-3); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<!-- end featured mods -->
		<!-- begin main content -->
		<div id="page-bg">
			<div class="wrapper">
				<div id="main-shadow" class="png"></div>
				<div id="main-shadow2" class="png"></div>
				<div id="main-content">
					<table class="mainbody" border="0" cellspacing="0" cellpadding="0">
						<tr valign="top">
							<!-- begin leftcolumn-->
							<?php if (mosCountModules('left') or (isset($subnav) and $subnav->ismenu() and $splitmenu_col=="leftcol")) : ?>
							<td class="leftcol">
								<div class="padding">
									<?php if($subnav and $splitmenu_col=="leftcol" && $subnav->ismenu()) : ?>
										<div id="sub-menu">
											<?php echo $subnav->display(); ?>
										</div>
										<?php endif; ?>
									<?php mosLoadModules('left', -3); ?>
								</div>
							</td>
							<?php endif; ?>
							<!-- end leftcolumn -->
							<!-- begin maincolumn -->
							<td class="maincol">
								<div class="padding">
									<?php if ($show_pathway == "true") : ?>
										<div id="pathway">
											<?php mosPathway(); ?>
										</div>
									<?php endif; ?>
									<?php if (mosCountModules('inset')) : ?>
										<div id="inset">
											<?php mosLoadModules('inset',-3); ?>
										</div>
									<?php endif; ?>
									<?php if ($show_moduleslider=="true" and (mosCountModules('user8') or mosCountModules('user9') 
		    					or mosCountModules('user10') or mosCountModules('user11') or mosCountModules('user12'))) : ?>
		    							<div id="moduleslider-size">
		    								<?php displayTabs(); ?>
		    							</div>
		    						<?php endif; ?>
									<?php mosMainbody(); ?>
									<?php if (mosCountModules('user1') or mosCountModules('user2') or mosCountModules('user3')) : ?>
										<div id="mainmodules" class="spacer<?php echo $mainmod_width; ?>">
											<?php if (mosCountModules('user1')) : ?>
												<div class="block">
													<?php mosLoadModules('user1',-3); ?>
												</div>
											<?php endif; ?>
											<?php if (mosCountModules('user2')) : ?>
												<div class="block">
													<?php mosLoadModules('user2',-3); ?>
												</div>
											<?php endif; ?>
											<?php if (mosCountModules('user3')) : ?>
												<div class="block">
													<?php mosLoadModules('user3',-3); ?>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</td>
							<!-- end maincolumn -->
							<!-- begin rightcolumn -->
							<?php if (mosCountModules('right') or (isset($subnav) and $subnav->ismenu() and $splitmenu_col=="rightcol")) : ?>
								<td class="rightcol">
									<div class="padding">
										<?php if($subnav and $splitmenu_col=="rightcol" && $subnav->ismenu()) : ?>
											<div id="sub-menu">
												<?php echo $subnav->display(); ?>
											</div>
											<?php endif; ?>
										<?php mosLoadModules('right', -3); ?>
									</div>
								</td>
							<?php endif; ?>
							<!-- end rightcolumn -->
						</tr>
					</table>
				</div>
			</div>
		</div>
		<!-- end main content -->
		<div id="page-bg-bottom">
			<?php if (mosCountModules('user4') or mosCountModules('user5') or mosCountModules('user6') or mosCountModules('user7')) : ?>
			<div class="wrapper">
				<div id="bottom-tab">
					<span class="bottom-tab-text">Mais Informações</span>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<!-- begin bottom section -->
		<?php if (mosCountModules('user4') or mosCountModules('user5') or mosCountModules('user6') or mosCountModules('user7')) : ?>
		<div id="bottom">
			<div class="wrapper">
				<div id="bottommodules" class="spacer<?php echo $bottommods_width; ?>">
					<?php if (mosCountModules('user4')) : ?>
						<div class="block">
							<?php mosLoadModules('user4',-3); ?>
						</div>
					<?php endif; ?>
					<?php if (mosCountModules('user5')) : ?>
						<div class="block">
							<?php mosLoadModules('user5',-3); ?>
						</div>
					<?php endif; ?>
					<?php if (mosCountModules('user6')) : ?>
						<div class="block">
							<?php mosLoadModules('user6',-3); ?>
						</div>
					<?php endif; ?>
					<?php if (mosCountModules('user7')) : ?>
						<div class="block">
							<?php mosLoadModules('user7',-3); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<!-- end bottom section -->
		<!-- begin footer -->
		<div id="footer">
			<div class="wrapper">
				<div id="footer2">
				<?php if (mosCountModules('bottom')) : ?>
					<div id="bottom-menu">
						<?php mosLoadModules('bottom', -2); ?>
					</div>
				<?php endif; ?>
				</div>
				<div style="text-align:center; color:#FFFFFF;">Rua General Camara  n° 195 - Bom Retiro – Joinville – Santa Catarina  Fone: (47) 3025-1848</div>
				<br /><br />
				<?php mosLoadModules('debug', -1); ?>
			</div>
		</div>
		<!-- end footer -->
	</body>
</html>