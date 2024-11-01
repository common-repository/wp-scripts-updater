<?php
/*
Plugin Name: WP Scripts Updater
Plugin URI: 
Description: Update Wordpress scripts and add some new by using this plugin.
Version: 0.1.0
Author: T.I.M.
License: GNU GPL v3
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Check
if ( !class_exists( 'WP_Scripts_Upd' ) ) {
// Init
add_action( 'plugins_loaded', array( 'WP_Scripts_Upd', 'init' ));
// Main Class
class WP_Scripts_Upd {
		// Root
		var $plugin_base;
		var $plugin_name;
		var $do_footer = false;
		function register_plugin ($name, $base)
		{
			$this->plugin_base = rtrim (dirname ($base), '/');
			$this->plugin_name = $name;

			$this->add_action ('init', 'load_locale');
		}

		// Languages
		function load_locale ()
		{
			$locale = get_locale ();
			if ( empty($locale) )
				$locale = 'en_US';

			$mofile = dirname (__FILE__)."/locale/$locale.mo";
			load_textdomain ($this->plugin_name, $mofile);
		}

		// Plugin Base
		function dir ()
		{
			return $this->plugin_base;
		}

		// Get URL to the plugin
		function url ($url = '')
		{
			if ($url)
				return str_replace ('\\', urlencode ('\\'), str_replace ('&amp;amp', '&amp;', str_replace ('&', '&amp;', $url)));
			else
			{
				$url = substr ($this->plugin_base, strlen ($this->realpath (ABSPATH)));
				if (DIRECTORY_SEPARATOR != '/')
					$url = str_replace (DIRECTORY_SEPARATOR, '/', $url);

				$url = get_bloginfo ('wpurl').'/'.ltrim ($url, '/');

				global $is_IIS;
				if (isset ($_SERVER['HTTPS']) && !$is_IIS)
					$url = str_replace ('http://', 'https://', $url);
			}
			return $url;
		}

		// Main Function
		function __construct() {
			/***********************/
			/***********************/
			/******* Settings ******/
			/***********************/
			/***********************/
			add_action('wp_head', array($this, 'action_header'));
			add_action('wp_enqueue_scripts', array($this, 'action_enqueue_scripts'));
			add_filter('the_content', array($this, 'activate_filters'));
			add_filter('wp_get_attachment_link', array($this, 'activate_filters'));
			add_action('wp_footer', array($this, 'action_footer'));

			/***********************/
			/***********************/
			/******* Versions ******/
			/***********************/
			/***********************/
			$versions = array
			(
			'genericons' => '2.09',
			'classie' => '1.0',
			'prefixfree' => '1.0.7',
			'html5shiv' => '3.6.2',
			'selectivizr' => '1.0.3b',
			'PIE' => '1.0.0',
			'modernizr' => '2.6.2',
			'jquery' => '2.0.3',
			'jquery-migrate' => '1.2.1',
			'jquery-ui' => '1.10.3',
			'jquery-mobile' => '1.3.2',
			'sizzle' => '1.10.7-pre',
			'jquery-cookie' => '1.3.1',
			'cropper' => '1.2.2',
			'jcrop' => '0.9.12',
			'jquery-lazy-load' => '1.8.5',
			'jquery-form' => '3.40.0',
			'jquery-color' => '2.1.2',
			'jquery-masonry' => '3.1.2',
			'underscore' => '1.5.1',
			'backbone' => '1.0.0',
			'append-url' => '0.2b'
			);

			/***********************/
			/***********************/
			/******** Helpers ******/
			/***********************/
			/***********************/
			// Genericons Font
			// Genericons are vector icons embedded in a webfont designed to be clean and simple keeping with a generic aesthetic.
			// More: http://genericons.com
			wp_deregister_style('genericons'); // why not to try it? ;)
			wp_register_style('genericons', plugins_url('/fonts/genericons/genericons.min.css', __FILE__), array(), $versions['genericons']);

			// Classie
			// Class helper functions
			// More: https://github.com/desandro/classie
			wp_deregister_script('classie'); // why not to try it? ;)
			wp_register_script('classie', plugins_url('/js/helpers/classie.js', __FILE__), false, $versions['classie']);

			// StyleFix & PrefixFree
			// -prefix-free lets people use only unprefixed CSS properties everywhere. It works behind the scenes, adding the current browser’s prefix to any CSS code, only when it’s needed.
			// More: http://leaverou.github.io/prefixfree
			wp_deregister_script('prefixfree');
			wp_deregister_script('prefixfree-core');
			wp_deregister_script('prefixfree-dynamic'); // why not to try it? ;)
			wp_register_script('prefixfree', false, array( 'prefixfree-core', 'prefixfree-dynamic' ), $versions['prefixfree']);
			wp_register_script('prefixfree-core', plugins_url('/js/helpers/prefixfree.min.js', __FILE__), array(), $versions['prefixfree']);
			wp_register_script('prefixfree-dynamic', plugins_url('/js/helpers/prefixfree.dynamic-dom.min.js', __FILE__), array(), $versions['prefixfree']);

			// Selectivizr
			// Selectivizr is a JavaScript utility that emulates CSS3 pseudo-classes and attribute selectors in Internet Explorer 6-8.
			// More: http://selectivizr.com
			wp_deregister_script('selectivizr'); // why not to try it? ;)
			wp_register_script('selectivizr', plugins_url('/js/helpers/selectivizr.min.js', __FILE__), false, $versions['selectivizr']);

			// html5shiv & html5shiv-printshiv
			// The HTML5 Shiv enables use of HTML5 sectioning elements in legacy Internet Explorer and provides basic HTML5 styling for Internet Explorer 6-9, Safari 4.x (and iPhone 3.x), and Firefox 3.x.
			// More: https://github.com/aFarkas/html5shiv
			wp_deregister_script('html5shiv'); // why not to try it? ;)
			wp_register_script('html5shiv', plugins_url('/js/helpers/html5shiv.min.js', __FILE__), false, $versions['html5shiv']);
			wp_deregister_script('html5shiv-printshiv'); // why not to try it? ;)
			wp_register_script('html5shiv-printshiv', plugins_url('/js/helpers/html5shiv-printshiv.min.js', __FILE__), false, $versions['html5shiv']);

			// PIE
			// PIE makes Internet Explorer 6-9 capable of rendering several of the most useful CSS3 decoration features.
			// More: http://css3pie.com
			wp_deregister_script('PIE'); // why not to try it? ;)
			wp_register_script('PIE', plugins_url('/js/helpers/PIE.min.js', __FILE__), false, $versions['PIE']);

			// Modernizr
			// Modernizr is a JavaScript library that detects HTML5 and CSS3 features in the user’s browser.
			// More: http://modernizr.com
			wp_deregister_script('modernizr'); // why not to try it? ;)
			wp_register_script('modernizr', plugins_url('/js/helpers/modernizr.min.js', __FILE__), false, $versions['modernizr']);

			/***********************/
			/***********************/
			/*** jQuery Library ****/
			/***********************/
			/***********************/
			// Library
			// jQuery is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal and manipulation, event handling, animation, and Ajax much simpler with an easy-to-use API that works across a multitude of browsers.
			// More: http://jquery.com
			wp_deregister_script('jquery');
			wp_deregister_script('jquery-core');
			wp_deregister_script('jquery-migrate');
			wp_register_script('jquery', false, array( 'jquery-core', 'jquery-migrate' ), $versions['jquery']);
			wp_register_script('jquery-core', plugins_url('/js/jquery/jquery.min.js', __FILE__), array(), $versions['jquery']);
			wp_register_script('jquery-migrate', plugins_url('/js/jquery/jquery-migrate.min.js', __FILE__), array(), $versions['jquery-migrate']);

			/***********************/
			/***********************/
			/****** jQuery UI ******/
			/***********************/
			/***********************/
			// Full Set (Tools & Effects)
			// jQuery UI is a curated set of user interface interactions, effects, widgets, and themes built on top of the jQuery JavaScript Library. 
			// More: http://jqueryui.com
			wp_deregister_script('jquery-ui'); // why not to try it? ;)
			wp_deregister_style('wp-jquery-ui-dialog');
			wp_register_script('jquery-ui', plugins_url('/js/jquery/ui/jquery-ui.min.js', __FILE__), array('jquery'), $versions['jquery-ui']);
			wp_register_style('jquery-ui', plugins_url('/css/jquery/ui/themes/base/jquery-ui.min.css', __FILE__), array(), $versions['jquery-ui']);

			// Tools - Core
			wp_deregister_script('jquery-ui-core');
			wp_register_script('jquery-ui-core', plugins_url('/js/jquery/ui/jquery.ui.core.min.js', __FILE__), array('jquery'), $versions['jquery-ui']);

			// Effects - Core
			wp_deregister_script('jquery-effects-core');
			wp_register_script('jquery-effects-core', plugins_url('/js/jquery/ui/jquery.ui.effect.min.js', __FILE__), array('jquery-ui-core'), $versions['jquery-ui']);

			// Tools - Accordion
			wp_deregister_script('jquery-ui-accordion');
			wp_register_script('jquery-ui-accordion', plugins_url('/js/jquery/ui/jquery.ui.accordion.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-widget'), $versions['jquery-ui']);

			// Tools - Autocomplete
			wp_deregister_script('jquery-ui-autocomplete');
			wp_register_script('jquery-ui-autocomplete', plugins_url('/js/jquery/ui/jquery.ui.autocomplete.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position', 'jquery-ui-menu'), $versions['jquery-ui']);

			// Tools - Button
			wp_deregister_script('jquery-ui-button');
			wp_register_script('jquery-ui-button', plugins_url('/js/jquery/ui/jquery.ui.button.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-widget'), $versions['jquery-ui']);

			// Tools - Datepicker
			wp_deregister_script('jquery-ui-datepicker');
			wp_register_script('jquery-ui-datepicker', plugins_url('/js/jquery/ui/jquery.ui.datepicker.min.js', __FILE__), array('jquery-ui-core'), $versions['jquery-ui']);

			// Tools - Dialog
			wp_deregister_script('jquery-ui-dialog');
			wp_register_script('jquery-ui-dialog', plugins_url('/js/jquery/ui/jquery.ui.dialog.min.js', __FILE__), array('jquery-ui-resizable', 'jquery-ui-draggable', 'jquery-ui-button', 'jquery-ui-position'), $versions['jquery-ui']);

			// Tools - Draggable
			wp_deregister_script('jquery-ui-draggable');
			wp_register_script('jquery-ui-draggable', plugins_url('/js/jquery/ui/jquery.ui.draggable.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-mouse'), $versions['jquery-ui']);

			// Tools - Droppable
			wp_deregister_script('jquery-ui-droppable');
			wp_register_script('jquery-ui-droppable', plugins_url('/js/jquery/ui/jquery.ui.droppable.min.js', __FILE__), array('jquery-ui-draggable'), $versions['jquery-ui']);

			// Tools - Menu
			wp_deregister_script('jquery-ui-menu');
			wp_register_script('jquery-ui-menu', plugins_url('/js/jquery/ui/jquery.ui.menu.min.js', __FILE__), array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position' ), $versions['jquery-ui']);

			// Tools - Mouse
			wp_deregister_script('jquery-ui-mouse');
			wp_register_script('jquery-ui-mouse', plugins_url('/js/jquery/ui/jquery.ui.mouse.min.js', __FILE__), array('jquery-ui-widget'), $versions['jquery-ui']);

			// Tools - Position
			wp_deregister_script('jquery-ui-position');
			wp_register_script('jquery-ui-position', plugins_url('/js/jquery/ui/jquery.ui.position.min.js', __FILE__), array('jquery-ui-core'), $versions['jquery-ui']);

			// Tools - Progressbar
			wp_deregister_script('jquery-ui-progressbar');
			wp_register_script('jquery-ui-progressbar', plugins_url('/js/jquery/ui/jquery.ui.progressbar.min.js', __FILE__), array('jquery-ui-widget'), $versions['jquery-ui']);

			// Tools - Resizeable
			wp_deregister_script('jquery-ui-resizable');
			wp_register_script('jquery-ui-resizable', plugins_url('/js/jquery/ui/jquery.ui.resizable.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-mouse'), $versions['jquery-ui']);

			// Tools - Selectable
			wp_deregister_script('jquery-ui-selectable');
			wp_register_script('jquery-ui-selectable', plugins_url('/js/jquery/ui/jquery.ui.selectable.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-mouse'), $versions['jquery-ui']);

			// Tools - Slider
			wp_deregister_script('jquery-ui-slider');
			wp_register_script('jquery-ui-slider', plugins_url('/js/jquery/ui/jquery.ui.slider.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-mouse'), $versions['jquery-ui']);

			// Tools - Sortable
			wp_deregister_script('jquery-ui-sortable');
			wp_register_script('jquery-ui-sortable', plugins_url('/js/jquery/ui/jquery.ui.sortable.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-mouse'), $versions['jquery-ui']);

			// Tools - Spinner
			wp_deregister_script('jquery-ui-spinner');
			wp_register_script('jquery-ui-spinner', plugins_url('/js/jquery/ui/jquery.ui.spinner.min.js', __FILE__), array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-button' ), $versions['jquery-ui']);

			// Tools - Tabs
			wp_deregister_script('jquery-ui-tabs');
			wp_register_script('jquery-ui-tabs', plugins_url('/js/jquery/ui/jquery.ui.tabs.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-widget'), $versions['jquery-ui']);

			// Tools - Tooltip
			wp_deregister_script('jquery-ui-tooltip'); // why not to try it? ;)
			wp_register_script('jquery-ui-tooltip', plugins_url('/js/jquery/ui/jquery.ui.tooltip.min.js', __FILE__), array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position' ), $versions['jquery-ui']);

			// Tools - Widget
			wp_deregister_script('jquery-ui-widget');
			wp_register_script('jquery-ui-widget', plugins_url('/js/jquery/ui/jquery.ui.widget.min.js', __FILE__), array('jquery-ui-core'), $versions['jquery-ui']);

			// Effects - Blind
			wp_deregister_script('jquery-effects-blind');
			wp_register_script('jquery-effects-blind', plugins_url('/js/jquery/ui/jquery.ui.effect-blind.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Bounce
			wp_deregister_script('jquery-effects-bounce');
			wp_register_script('jquery-effects-bounce', plugins_url('/js/jquery/ui/jquery.ui.effect-bounce.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Clip
			wp_deregister_script('jquery-effects-clip');
			wp_register_script('jquery-effects-clip', plugins_url('/js/jquery/ui/jquery.ui.effect-clip.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Drop
			wp_deregister_script('jquery-effects-drop');
			wp_register_script('jquery-effects-drop', plugins_url('/js/jquery/ui/jquery.ui.effect-drop.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Explode
			wp_deregister_script('jquery-effects-explode');
			wp_register_script('jquery-effects-explode', plugins_url('/js/jquery/ui/jquery.ui.effect-explode.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Fade
			wp_deregister_script('jquery-effects-fade');
			wp_register_script('jquery-effects-fade', plugins_url('/js/jquery/ui/jquery.ui.effect-fade.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Fold
			wp_deregister_script('jquery-effects-fold');
			wp_register_script('jquery-effects-fold', plugins_url('/js/jquery/ui/jquery.ui.effect-fold.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Highlight
			wp_deregister_script('jquery-effects-highlight');
			wp_register_script('jquery-effects-highlight', plugins_url('/js/jquery/ui/jquery.ui.effect-highlight.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Pulsate
			wp_deregister_script('jquery-effects-pulsate');
			wp_register_script('jquery-effects-pulsate', plugins_url('/js/jquery/ui/jquery.ui.effect-pulsate.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Scale
			wp_deregister_script('jquery-effects-scale');
			wp_register_script('jquery-effects-scale', plugins_url('/js/jquery/ui/jquery.ui.effect-scale.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Shake
			wp_deregister_script('jquery-effects-shake');
			wp_register_script('jquery-effects-shake', plugins_url('/js/jquery/ui/jquery.ui.effect-shake.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Slide
			wp_deregister_script('jquery-effects-slide');
			wp_register_script('jquery-effects-slide', plugins_url('/js/jquery/ui/jquery.ui.effect-slide.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			// Effects - Transfer
			wp_deregister_script('jquery-effects-transfer');
			wp_register_script('jquery-effects-transfer', plugins_url('/js/jquery/ui/jquery.ui.effect-transfer.min.js', __FILE__), array('jquery-effects-core'), $versions['jquery-ui']);

			/***********************/
			/***********************/
			/**** jQuery Mobile ****/
			/***********************/
			/***********************/
			// Framework
			// jQuery Mobile: Touch-Optimized Web Framework for Smartphones & Tablets
			// More: http://jquerymobile.com
			wp_deregister_script('jquery-mobile'); 
			wp_deregister_style('jquery-mobile-default-theme');
			wp_deregister_style('jquery-mobile-structure');// why not to try it? ;)
			wp_register_style('jquery-mobile-default-theme', plugins_url('/css/jquery/mobile/jquery.mobile.theme.min.css', __FILE__), array(), $versions['jquery-mobile']);
			wp_register_style('jquery-mobile-structure', plugins_url('/css/jquery/mobile/jquery.mobile.structure.min.css', __FILE__), array(), $versions['jquery-mobile']);
			wp_register_script('jquery-mobile', plugins_url('/js/jquery/mobile/jquery.mobile.min.js', __FILE__), array('jquery'), $versions['jquery-mobile']);

			/***********************/
			/***********************/
			/******* Sizzle ********/
			/***********************/
			/***********************/
			// Sizzle Content
			// A pure-JavaScript CSS selector engine designed to be easily dropped in to a host library.
			// More: http://sizzlejs.com
			wp_deregister_script('sizzle'); // why not to try it? ;)
			wp_register_script('sizzle', plugins_url('/js/jquery/sizzle/sizzle.min.js', __FILE__), array('jquery'), $versions['sizzle']);

			/***********************/
			/***********************/
			/*** jQuery Plugins ****/
			/***********************/
			/***********************/
			// jQuery Cookie
			// A simple, lightweight jQuery plugin for reading, writing and deleting cookies.
			// More: https://github.com/carhartl/jquery-cookie
			wp_deregister_script('jquery-cookie');// why not to try it? ;)
			wp_register_script('jquery-cookie', plugins_url('/js/jquery/plugins/jquery.cookie.min.js', __FILE__), array('jquery'), $versions['jquery-cookie']);

			// Cropper
			// JavaScript Image Cropper UI using Prototype v 1.2.2.
			// More: http://www.defusion.org.uk
			wp_deregister_script('cropper');
			wp_deregister_style('cropper');// why not to try it? ;)
			wp_register_script('cropper', plugins_url('/js/jquery/plugins/cropper.js', __FILE__), array('scriptaculous-dragdrop'), $versions['cropper']);
			wp_register_style('cropper', plugins_url('/css/jquery/plugins/cropper.min.css', __FILE__), array(), $versions['cropper']);

			// Jcrop
			// Jcrop is the quick and easy way to add image cropping functionality to your web application.
			// More: http://deepliquid.com/content/Jcrop.html
			wp_deregister_script('jcrop');
			wp_deregister_style('jcrop');
			wp_register_script('jcrop', plugins_url('/js/jquery/plugins/jquery.Jcrop.min.js', __FILE__), array('jquery'), $versions['jcrop']);
			wp_register_style('jcrop', plugins_url('/css/jquery/plugins/jquery.Jcrop.min.css', __FILE__), array(), $versions['jcrop']);

			// jQuery Lazy Load
			// jQuery plugin for "lazy" loading images.
			// More: http://www.appelsiini.net/projects/lazyload
			wp_deregister_script('jquery-lazy-load');
			wp_register_script('jquery-lazy-load', plugins_url('/js/jquery/plugins/jquery.lazyload.min.js', __FILE__), array('jquery'), $versions['jquery-lazy-load']);

			// jQuery Color
			// jQuery plugin for color manipulation and animation support.
			// More: https://github.com/jquery/jquery-color
			wp_deregister_script('jquery-color');
			wp_register_script('jquery-color', plugins_url('/js/jquery/plugins/jquery.color.min.js', __FILE__), array('jquery'), $versions['jquery-color']);

			// jQuery Form
			// The jQuery Form Plugin allows people to easily and unobtrusively upgrade HTML forms to use AJAX.
			// More: https://github.com/malsup/form
			wp_deregister_script('jquery-form');
			wp_register_script('jquery-form', plugins_url('/js/jquery/plugins/jquery.form.min.js', __FILE__), array('jquery'), $versions['jquery-form']);

			// jQuery Masonry
			// Masonry is a JavaScript grid layout library. It works by placing elements in optimal position based on available vertical space, sort of like a mason fitting stones in a wall. You’ve probably seen it in use all over the Internet.
			// More: http://masonry.desandro.com
			wp_deregister_script('jquery-masonry');
			wp_register_script('jquery-masonry', plugins_url('/js/jquery/plugins/jquery.masonry.min.js', __FILE__), array('jquery'), $versions['jquery-masonry']);

			/***********************/
			/***********************/
			/****** Underscore *****/
			/***********************/
			/***********************/
			// Underscore Library
			// Underscore is a utility-belt library for JavaScript that provides a lot of the functional programming support...
			// More: http://underscorejs.org
			wp_deregister_script('underscore');
			wp_register_script('underscore', plugins_url('/js/underscore-min.js', __FILE__), false, $versions['underscore']);

			// Backbone
			// Backbone gives structure to web applications by providing models with key-value binding and custom events, collections with a rich API of enumerable functions, views with declarative event handling, and connects it all to your existing API over a RESTful JSON interface.
			// More: http://backbonejs.org/
			wp_deregister_script('backbone');
			wp_register_script('backbone', plugins_url('/js/backbone-min.js', __FILE__), false, $versions['backbone']);

			/***********************/
			/***********************/
			/*** Pure Javascript ***/
			/***********************/
			/***********************/
			wp_deregister_script('append-url');
			wp_register_script('append-url', plugins_url('/js/appendurl.min.js', __FILE__), false, $versions['append-url']);
		}

		// Header!
		function action_header() {
		echo <<<EOF
<style type='text/css'>
img.lazy { display: none; }
</style>

EOF;
		}

		// Automatically activate jQuery Lazy Load Plugin 
		function action_enqueue_scripts() {
			wp_enqueue_script('jquery-lazy-load');
		}

		// Return the callback
		function activate_filters ($content) {
			if (is_feed()) return $content;
			return preg_replace_callback('/(<\s*img[^>]+)(src\s*=\s*"[^"]+")([^>]+>)/i', array($this, 'preg_replace_callback'), $content);
		}

		// Some magic ^^
		function preg_replace_callback($matches) {
			$this->do_footer = true;

			if (!preg_match('/class\s*=\s*"/i', $matches[0])) {
				$class_attr = 'class="" ';
			}
			$replacement = $matches[1] . $class_attr . 'src="' . plugins_url('/images/null.gif', __FILE__) . '" data-original' . substr($matches[2], 3) . $matches[3];

			$replacement = preg_replace('/class\s*=\s*"/i', 'class="lazy ', $replacement);

			$replacement .= '<noscript>' . $matches[0] . '</noscript>';
		return $replacement;
		}

		// Footer!
		function action_footer() {
			if (!$this->do_footer) {
			return;
		}

		echo <<<EOF
<script type="text/javascript">
(function($){
  $("img.lazy").show().lazyload({effect: "fadeIn"});
})(jQuery);
</script>

EOF;
		}

		// Init Function
		public static function init() {
		$class = __CLASS__;
		new $class;
		}
	}
}
?>