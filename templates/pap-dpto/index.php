<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app  = JFactory::getApplication();
$user = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add Stylesheets
JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'override.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'animate.min.css', array('version' => 'auto', 'relative' => true));

// Use of Google Font
if ($this->params->get('googleFont'))
{
	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $this->params->get('googleFontName'));
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . str_replace('+', ' ', $this->params->get('googleFontName')) . "', sans-serif;
	}");
}



// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}
// Logo file or site title param
if ($this->params->get('sitetitle'))
{
	//$logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';
}
else
{
	//$logo = '<span class="site-title" title="'. $sitename .'">'. $sitename .'</span>';
}
	$logo = '<div class="logous"><img src="' .($this->baseurl). '/images/logo-us.jpg" alt="Universidad de Sevilla" /></div>';;
if ($this->params->get('logoFile'))
{
	$logofile = '<img class="logo-file" src="'. JUri::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
}
else {
	$logofile = '';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?>">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-83217323-1', 'auto');
  ga('send', 'pageview');
</script>

	<!-- Body -->
	<div class="body">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<!-- Header -->
			<header class="header" role="banner">
				<div>
					<div class="header-search pull-right">
							<jdoc:include type="modules" name="position-0" style="none" />
					</div>
					<div class="header-inner">
						<a class="brand pull-left" href="<?php echo $this->baseurl; ?>">
							<?php echo $logo;?> <?php if ($this->params->get('sitedescription')) { echo '<div class="site-description">'. htmlspecialchars($this->params->get('sitedescription')) .'</div>'; } ?>
						</a>
					</div>
					<div class="header-search pull-right clearfix divlogo">
							<?php echo $logofile;?>
					</div>
				</div>
				<div style="clear: both;"></div>
			</header>
		</div>
	</div>
	<div>
			<?php if ($this->countModules('position-1')) : ?>
			<nav class="navigation" role="navigation">
				<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
					<div class="navbar pull-right">
						<a class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
					</div>
					<jdoc:include type="modules" name="position-1" style="none" />
				</div>
			</nav>
			<?php endif; ?>
	</div>
	<div class="body">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<jdoc:include type="modules" name="breacrump" style="xhtml" />
			<jdoc:include type="modules" name="banner" style="xhtml" />
			<?php if ($this->countModules('ico-1') || $this->countModules('ico-2') || $this->countModules('ico-3') || $this->countModules('ico-4') || $this->countModules('ico-4')) : ?>
			<div class="row-fluid">
				<div class="span2 icono ico1"><div class="ico">&nbsp;</div><jdoc:include type="modules" name="ico-1" style="none" /></div>
				<div class="span2 icono ico2"><div class="ico">&nbsp;</div><jdoc:include type="modules" name="ico-2" style="none" /></div>
				<div class="span2 icono ico3"><div class="ico">&nbsp;</div><jdoc:include type="modules" name="ico-3" style="none" /></div>
				<div class="span2 icono ico4"><div class="ico">&nbsp;</div><jdoc:include type="modules" name="ico-4" style="none" /></div>
				<div class="span4 icono ico5"><jdoc:include type="modules" name="ico-5" style="none" /></div>
			</div>
			<?php endif;?>
			<?php if ($this->countModules('top') ) : ?>
			<div class="row-fluid">
  				<div class="top animated fadeIn slow">
					<jdoc:include type="modules" name="top" style="xhtml" />
				</div>
			</div>
			<?php endif;?>
			<div class="row-fluid">
				<?php if ($this->countModules('position-8')) : ?>
				<!-- Begin Sidebar -->
				<div id="sidebar" class="span3">
					<div class="sidebar-nav">
						<jdoc:include type="modules" name="position-8" style="xhtml" />
					</div>
				</div>
				<!-- End Sidebar -->
				<?php endif; ?>
				<main id="content" role="main" class="<?php echo $span;?>">
					<!-- Begin Content -->
					<jdoc:include type="modules" name="position-3" style="xhtml" />
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="position-2" style="xhtml" />
					<!-- End Content -->
				</main>
				<?php if ($this->countModules('position-7')) : ?>
				<div id="aside" class="span3">
					<!-- Begin Right Sidebar -->
					<jdoc:include type="modules" name="position-7" style="well" />
					<!-- End Right Sidebar -->
				</div>
				<?php endif; ?>
			</div>
			<?php if ($this->countModules('home-left') || $this->countModules('home-right')) : ?>
			<div class="row-fluid">
				<div class="span6 home-left"><jdoc:include type="modules" name="home-left" style="none" /></div>
				<div class="span6 home-right"><jdoc:include type="modules" name="home-right" style="none" /></div>
			</div>
			<?php endif;?>
		</div>
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<jdoc:include type="modules" name="footer" style="none" />
			<?php /*?>
			<p class="pull-right">
				<a href="#top" id="back-top">
					<?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?>
				</a>
			</p>
			<?php */?>
			<div class="clearfix"></div>
			<p>
				&copy; <?php echo $sitename; ?> <?php echo date('Y');?>
			</p>
		</div>
	</footer>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
