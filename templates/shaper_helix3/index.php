<?php
/**
 * @package Helix3 Framework
 * Template Name - Shaper Helix3
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

$doc = JFactory::getDocument();

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework'); //Force load Bootstrap
unset($doc->_scripts[$this->baseurl . '/media/jui/js/bootstrap.min.js']); // Remove joomla core bootstrap

//Load Helix
$helix3_path = JPATH_PLUGINS.'/system/helix3/core/helix3.php';

if (file_exists($helix3_path)) {
    require_once($helix3_path);
    $this->helix3 = helix3::getInstance();
} else {
    die('Please install and activate helix plugin');
}

//Coming Soon
if($this->helix3->getParam('comingsoon_mode')) header("Location: ".$this->baseUrl."?tmpl=comingsoon");

//Class Classes
$body_classes = '';

$body_classes .= ($this->helix3->getParam('boxed_layout', 0)) ? ' layout-boxed' : ' layout-fluid';

//Body Background Image
if($bg_image = $this->helix3->getParam('body_bg_image')) {

    $body_style  = 'background-image: url(' . JURI::base(true ) . '/' . $bg_image . ');';
    $body_style .= 'background-repeat: '. $this->helix3->getParam('body_bg_repeat') .';';
    $body_style .= 'background-size: '. $this->helix3->getParam('body_bg_size') .';';
    $body_style .= 'background-attachment: '. $this->helix3->getParam('body_bg_attachment') .';';
    $body_style .= 'background-position: '. $this->helix3->getParam('body_bg_position') .';';
    $body_style  = 'body.site {' . $body_style . '}';

    $doc->addStyledeclaration( $body_style );
}
/*
//Body Font
$webfonts = array();

if( $this->params->get('enable_body_font') ) {
    $webfonts['body'] = $this->params->get('body_font');
}

//Heading1 Font
if( $this->params->get('enable_h1_font') ) {
    $webfonts['h1'] = $this->params->get('h1_font');
}

//Heading2 Font
if( $this->params->get('enable_h2_font') ) {
    $webfonts['h2'] = $this->params->get('h2_font');
}

//Heading3 Font
if( $this->params->get('enable_h3_font') ) {
    $webfonts['h3'] = $this->params->get('h3_font');
}

//Heading4 Font
if( $this->params->get('enable_h4_font') ) {
    $webfonts['h4'] = $this->params->get('h4_font');
}

//Heading5 Font
if( $this->params->get('enable_h5_font') ) {
    $webfonts['h5'] = $this->params->get('h5_font');
}

//Heading6 Font
if( $this->params->get('enable_h6_font') ) {
    $webfonts['h6'] = $this->params->get('h6_font');
}

//Navigation Font
if( $this->params->get('enable_navigation_font') ) {
    $webfonts['.sp-megamenu-parent'] = $this->params->get('navigation_font');
}

//Custom Font
if( $this->params->get('enable_custom_font') && $this->params->get('custom_font_selectors') ) {
    $webfonts[ $this->params->get('custom_font_selectors') ] = $this->params->get('custom_font');
}

$this->helix3->addGoogleFont($webfonts);
*/
//Custom CSS
if($custom_css = $this->helix3->getParam('custom_css')) {
    $doc->addStyledeclaration( $custom_css );
}

//Custom JS
if($custom_js = $this->helix3->getParam('custom_js')) {
    $doc->addScriptdeclaration( $custom_js );
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="image_src" href="/images/banners/remont-bytovoj-tehniki.jpg">
    <?php
    if($favicon = $this->helix3->getParam('favicon')) {
        $doc->addFavicon( JURI::base(true) . '/' .  $favicon);
    } else {
        $doc->addFavicon( $this->helix3->getTemplateUri() . '/images/favicon.ico' );
    }
    ?>

    <jdoc:include type="head" />
</head>
<?php
$app = JFactory::getApplication();
$menu = $app->getMenu()->getActive();
$pageclass = '';

if (is_object($menu))
    $pageclass = $menu->params->get('pageclass_sfx');
?>
<body class="<?php echo $this->helix3->bodyClass( $body_classes . $pageclass ); ?>">

    <div class="body-innerwrapper">
        <?php $this->helix3->generatelayout(); ?>

        <div class="offcanvas-menu">
            <a href="#" class="close-offcanvas"><i class="fa fa-remove"></i></a>
            <div class="offcanvas-inner">
                <?php if ($this->helix3->countModules('offcanvas')) { ?>
                    <jdoc:include type="modules" name="offcanvas" style="sp_xhtml" />
                <?php } else { ?>
                    <p class="alert alert-warning"><?php echo JText::_('HELIX_NO_MODULE_OFFCANVAS'); ?></p>
                <?php } ?>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,800,600,regular&subset=latin" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->helix3->getTemplateUri(); ?>/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->helix3->getTemplateUri(); ?>/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->helix3->getTemplateUri(); ?>/css/legacy.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->helix3->getTemplateUri(); ?>/css/template.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->helix3->getTemplateUri(); ?>/css/presets/preset1.css" type="text/css" class="preset" />
    <link rel="stylesheet" href="http://remontvolt.com/modules/mod_ext_callback/assets/css/default.css" type="text/css" />
    <style type="text/css">
        body{font-family:Open Sans, sans-serif; font-weight:300; }
        h1{font-family:Open Sans, sans-serif; font-weight:800; }
        h2{font-family:Open Sans, sans-serif; font-weight:600; }
        h3{font-family:Open Sans, sans-serif; font-weight:normal; }
        h4{font-family:Open Sans, sans-serif; font-weight:normal; }
        h5{font-family:Open Sans, sans-serif; font-weight:600; }
        h6{font-family:Open Sans, sans-serif; font-weight:600; }
        #sp-top-bar{ background-color:#f5f5f5;color:#999999; }
    </style>
    <script src="<?php echo $this->helix3->getTemplateUri(); ?>/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->helix3->getTemplateUri(); ?>/js/custom.js" type="text/javascript"></script>
    <script src="<?php echo $this->helix3->getTemplateUri(); ?>/js/main.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(window).on('load',  function() {
            new JCaption('img.caption');
        });
    </script>

    <jdoc:include type="modules" name="debug" />

    <?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false): ?>
        <script type="text/javascript">
            //****** GoogleAnalytics
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-76952802-1', 'auto');
            ga('send', 'pageview');

        </script>
    <?php endif; ?>

</body>
</html>