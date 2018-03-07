<?php
/**
 * @package Weglot
 * @version 1.4.6
 */

/*
Plugin Name: Weglot Translate
Plugin URI: http://wordpress.org/plugins/Weglot/
Description: Translate your website into multiple languages in minutes without doing any coding. Fully SEO compatible.
Author: Remy B
Author URI: https://weglot.com/
Text Domain: weglot
Domain Path: /languages/
Version: 1.4.6
*/

/*  Copyright 2015  Remy Berda  (email : remy@weglot.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Exit if absolute path
*/
if ( ! defined( 'ABSPATH' ) ) exit;

//define( 'WP_DEBUG', true );
//define( 'WP_DEBUG_LOG', true );


define('WEGLOT_VERSION', '1.4.6');
define('WEGLOT_DIR', dirname(__FILE__));
define('WEGLOT_BNAME', plugin_basename(__FILE__));
define('WEGLOT_DIRURL', plugin_dir_url( __FILE__ ));
define('WEGLOT_INC', WEGLOT_DIR.'/includes');
define('WEGLOT_RESURL', WEGLOT_DIRURL.'resources/');


/* Load our files. Could do an autoloader here but for now, there is only 4 files so... */
require WEGLOT_DIR.'/WeglotPHPClient/weglot.php';
require WEGLOT_DIR.'/simple_html_dom.php';
require WEGLOT_DIR.'/WGUtils.php';
require WEGLOT_DIR.'/WeglotWidget.php';

/* Singleton class Weglot */
class Weglot {

	private $original_l;
	private $destination_l;

	private $request_uri;
	private $home_dir;
	private $currentlang;
	private $allowed;
	private $userInfo;
	private $translator;

	/*
	 * constructor
	 *
	 * @since 0.1
	 */
	private function __construct() {

		if (version_compare(phpversion(), '5.3.0', '<')) {
			add_action( 'admin_notices', array(&$this, 'wg_admin_notice2'),0);
			return;
		}

		if(function_exists('apache_get_modules') && !in_array('mod_rewrite', apache_get_modules())) {
			add_action( 'admin_notices', array(&$this, 'wg_admin_notice3'),0);
			return;
		}



		add_action('plugins_loaded', array(&$this, 'wg_load_textdomain'));
		add_action('init', array(&$this, 'init_function'),11);
		add_action('wp_head',array(&$this, 'add_alternate'));
		add_action('wp', array(&$this, 'rr_404_my_event') );
		add_shortcode('weglot_switcher', array(&$this, 'wg_switcher_creation'));
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this,'wg_plugin_action_links') );

		$this->original_l = get_option("original_l");
		$this->destination_l = get_option("destination_l");

		$this->home_dir = $this->getHomeDirectory();
		$this->request_uri = $this->getRequestUri($this->home_dir);

		$this->noredirect = false;
		if (strpos($this->request_uri, '?no_lredirect=true') !== false) {
			$this->noredirect = true;
			$_SERVER['REQUEST_URI'] = str_replace("?no_lredirect=true","",	$_SERVER['REQUEST_URI'] );
		}
		$this->request_uri = str_replace("?no_lredirect=true","",$this->request_uri);
		$curr = $this->getLangFromUrl($this->request_uri);
		$this->currentlang = $curr ? $curr:$this->original_l;

		if($this->currentlang!=$this->original_l) {
			$_SERVER['REQUEST_URI'] = str_replace('/'.$this->currentlang.'/','/',$_SERVER['REQUEST_URI']);
		}

		if(WGUtils::isLanguageRTL($this->currentlang)) {
			$GLOBALS['text_direction'] = "rtl";
		}
		else {
			$GLOBALS['text_direction'] = "ltr";
		}


		$apikey = get_option("project_key");
		$this->translator = $apikey ? new \Weglot\Client($apikey):null;
		$this->allowed = $apikey ? get_option("wg_allowed"):true;

		if (is_admin()) {
			if(strpos($this->request_uri, 'page=Weglot') !== false) {
				if($this->translator) {
					try {
						$this->userInfo = $this->translator->getUserInfo();
						if($this->userInfo) {
							$this->allowed = $this->userInfo['allowed'];
							update_option('wg_allowed',$this->allowed?1:0);
						}
					}
					catch(\Exception $e) {

					}
				}

			}
			elseif($this->allowed==0) {
				add_action( 'admin_notices', array(&$this, 'wg_admin_notice1'),0);
			}
		}

		$urlRacine = 	($this->currentlang!=$this->original_l) ? substr($this->request_uri,3) :$this->request_uri;
		$isURLOK = $this->isEligibleURL($urlRacine);
		if($isURLOK) {
			add_action('widgets_init', array(&$this, 'addWidget'));

			if(get_option("is_menu")=='on') {
				add_filter( 'wp_nav_menu_items', 'your_custom_menu_item', 10, 2 );
				function your_custom_menu_item ( $items, $args ) {
					$button = Weglot::Instance()->returnWidgetCode();
					$items .= $button;

					return $items;
				}
			}
		}
	}

	// Get our only instance of Weglot class
	public static function Instance() {
		static $inst = null;
		if($inst == null)
		{
			$inst = new Weglot();
		}
		return $inst;
	}

	public static function plugin_activate() {
		if (version_compare(phpversion(), '5.3.0', '<')) {
			wp_die(
				'<p>' . __( 'Thank you for downloading <strong>Weglot Translate</strong>!', 'weglot' ) . '</p><p>' . sprintf( __( 'In order to activate Weglot, you need PHP version <strong>5.3</strong> or greater. Your current version of PHP is %s.', 'weglot' ), phpversion() ) . '</p><p>' . __( 'Please upgrade your PHP version. You can ask your host provider to do this by sending them an email.', 'weglot' ) . '</p>',
				__( 'Plugin Activation Error', 'weglot' ),
				array( 'response'=>200, 'back_link'=>TRUE )
			);
		}
		//$this->updateRewriteRule();
		add_option('with_flags','on');
		add_option('with_name','on');
		add_option('is_dropdown','on');
		add_option('is_fullname','off');
		add_option('override_css','');
		add_option('is_menu','off');
		update_option('wg_allowed',1);
		if(get_option('permalink_structure')=="") {
			add_option('wg_old_permalink_structure_empty','on');
			update_option('permalink_structure','/%year%/%monthnum%/%day%/%postname%/');
		}
	}

	public static function plugin_deactivate() {
		flush_rewrite_rules();
		if(get_option('wg_old_permalink_structure_empty')=="on") {
			delete_option('wg_old_permalink_structure_empty');
			update_option('permalink_structure','');
		}
	}

	public static function plugin_uninstall() {
		flush_rewrite_rules();
		delete_option('project_key');
		delete_option('original_l');
		delete_option('destination_l');
		delete_option('show_box');
	}

	public function wg_load_textdomain() {
		load_plugin_textdomain( 'weglot', false, dirname( WEGLOT_BNAME ) . '/languages/' );
	}

	public function wg_plugin_action_links( $links ) {
	   $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=Weglot') ) .'">'.__('Settings','weglot').'</a>';
	   return $links;
	}

	public function wg_admin_notice1() {
    ?>
    <div class="updated settings-error notice is-dismissible">
        <p><?php echo sprintf( __( 'Weglot Translate is not active because you have exceeded the free limit. Please %supgrade your plan%s if you want to keep the service running.', 'weglot' ),  '<a target="_blank" href="https://weglot.com/change-plan">', '</a>' ); ?></p>
    </div>
    <?php
	}

	public function wg_admin_notice2() {
    ?>
    <div class="error settings-error notice is-dismissible">
        <p><?php echo sprintf(__( 'Weglot Translate plugin requires at least PHP 5.3 and you have PHP %s. Please upgrade your PHP version (you can contact your host and they will do it for you).', 'weglot' ), phpversion() ); ?></p>
    </div>
    <?php
	}

	public function wg_admin_notice3() {
    ?>
    <div class="error settings-error notice is-dismissible">
        <p><?php echo sprintf( __( 'Weglot Translate: You need to activate the mod_rewrite module. You can find more information here : %1$sUsing Permalinks%3$s. If you need help, just ask us directly %2$shere%3$s.', 'weglot' ), '<a target="_blank" href="https://codex.wordpress.org/Using_Permalinks">', '<a target="_blank" href="https://tawk.to/remyb">', '</a>' ); ?></p>
    </div>
    <?php
	}

	public function wg_switcher_creation(){
		$button = Weglot::Instance()->returnWidgetCode();
		echo $button;
	}

	public function init_function() {

		add_action('admin_menu', array(&$this, 'plugin_menu'));
		add_action('admin_init', array(&$this, 'plugin_settings') );

		$dest = explode(",",$this->destination_l);

		if($this->request_uri=="/" && !$this->noredirect && !WGUtils::is_bot()) { //front_page
			if(get_option("wg_auto_switch")=="on"&& isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				/* Redirects to browser L */
				$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
				//exit(print_r($dest));
				if(in_array($lang,$dest)) {
					wp_redirect( $this->home_dir."/$lang/" );
					exit();
				}
			}
		}

		/* prevent homepage redirect in canonical.php in case of show */
		$request_uri =  $this->request_uri;
		foreach($dest as $d) {
			if($request_uri == '/'.$d.'/')
				$thisL = $d;
		}
		$url = 	(isset($thisL) && $thisL!='') ? substr($request_uri,3) :$request_uri;

		if($url=="/" && (isset($thisL) && $thisL!='') && 'page' == get_option('show_on_front')) {
			add_action('template_redirect',array(&$this, 'kill_canonical_wg_92103'),1);
		}


		if(!is_admin() || (is_admin() && strpos($this->request_uri, 'page=Weglot') !== false)) {
			// Add JS
			wp_register_script('wp-weglot-js', WEGLOT_RESURL.'wp-weglot-js.js', false,WEGLOT_VERSION, false);
			wp_enqueue_script('wp-weglot-js');

			//Add CSS
			wp_register_style('wp-weglot-css', WEGLOT_RESURL.'wp-weglot-css.css', false,WEGLOT_VERSION, false);
			wp_enqueue_style('wp-weglot-css');

			wp_add_inline_style( 'wp-weglot-css', $this->getInlineCSS() );

			if(is_admin()) {
				// Add Admin JS
				wp_register_script('wp-weglot-admin-js', WEGLOT_RESURL.'wp-weglot-admin-js.js', array('jquery'),WEGLOT_VERSION, true);
				wp_enqueue_script('wp-weglot-admin-js');

				//Add Admin CSS
				wp_register_style('wp-weglot-admin-css', WEGLOT_RESURL.'wp-weglot-admin-css.css', false,WEGLOT_VERSION, false);
				wp_enqueue_style('wp-weglot-admin-css');

                // Add Selectize JS


                wp_enqueue_script( 'jquery-ui',     WEGLOT_RESURL . 'selectize/js/jquery-ui.min.js', array( 'jquery' ), WEGLOT_VERSION, true );
                wp_enqueue_script( 'jquery-selectize',     WEGLOT_RESURL . 'selectize/js/selectize.min.js', array( 'jquery' ), WEGLOT_VERSION, true );
                //wp_enqueue_style( 'selectize-css',     WEGLOT_RESURL . 'selectize/css/selectize.css', array(),          $ver );
                wp_enqueue_style( 'selectize-defaut-css',     WEGLOT_RESURL . 'selectize/css/selectize.default.css', array(),          WEGLOT_VERSION );

			}
		}

		/* Putting it in init makes that buffer deeper than caching ob */
		ob_start(array(&$this,'treatPage'));
	}

	public function add_alternate() {

		if($this->destination_l!="") {

			//$thisL = $this->currentlang;
			$dest = explode(",",$this->destination_l);

			$full_url = ($this->currentlang!=$this->original_l) ?  str_replace('/'.$this->currentlang.'/','/',$this->full_url($_SERVER)):$this->full_url($_SERVER);
			$output= '<link rel="alternate" hreflang="'.$this->original_l.'" href="'.$full_url.'" />'."\n";
			foreach($dest as $d) {
				$output.= '<link rel="alternate" hreflang="'.$d.'" href="'.$this->replaceUrl($full_url,$d).'" />'."\n";
			}
			echo $output;
		}
	}

	public function getCurrentLang() {
		return $this->currentlang;
	}

	public function rr_404_my_event() {

		$request_uri = $this->request_uri;
		//$thisL = $this->currentlang;
		$url = 	($this->currentlang!=$this->original_l) ? substr($request_uri,3) :$request_uri;

		//regex logic here
		$isURLOK = $this->isEligibleURL($url);
		if (!$isURLOK && $this->currentlang!=$this->original_l) {
			global $wp_query;
			$wp_query->set_404();
			status_header(404);
		}
	}

	public function kill_canonical_wg_92103() {
		add_action('redirect_canonical','__return_false');
	}

	public function plugin_menu() {
		$hook = add_menu_page('Weglot', 'Weglot', 'administrator', 'Weglot', array(&$this, 'plugin_settings_page'),  WEGLOT_DIRURL.'/images/weglot_fav_bw.png');
		//add_action('load-'.$hook,array(&$this, 'updateRewriteRule'));
		if(isset($_GET['settings-updated']) && $_GET['settings-updated'] && strpos($this->request_uri, 'page=Weglot') !== false)
		{
			//$this->updateRewriteRule();
			$d = explode(",",preg_replace('/\s+/', '', trim($this->destination_l,',')));
			$accepted = array("sq","en","ar","hy","az","af","eu","be","bg","bs","vi","hu","ht","nl","el","ka","da","he","id","ga","it","is","es","kk","ca","ky","zh","tw","ko","lv","lt","mg","ms","mt","mk","mn","de","no","fa","pl","pt","ro","ru","sr","sk","sl","sw","tg","th","tr","uz","uk","fi","fr","hr","cs","sv","et","ja","hi","ur");
			foreach($d as $k=>$l) {
				if(!in_array($l,$accepted) || $l==$this->original_l)
					unset($d[$k]);
			}
			update_option('destination_l',implode (",",$d));
			$this->destination_l = implode(",",$d);
			/* Display Box */
			if( !get_option( 'show_box' ) ) {
				add_option('show_box','on');
			} else {
				//your migrate stuff here
			}

			if($this->userInfo['plan']<=0 || in_array($this->userInfo['plan'],array(18,19,1001,1002))) {
				$d = explode(",",preg_replace('/\s+/', '', trim($this->destination_l,',')));
				$this->destination_l = $d[0];
				update_option('destination_l',$this->destination_l);
			}
		}
	}

	public function plugin_settings() {
		register_setting( 'my-plugin-settings-group', 'project_key' );
		register_setting( 'my-plugin-settings-group', 'original_l' );
		register_setting( 'my-plugin-settings-group', 'destination_l' );
		register_setting( 'my-plugin-settings-group', 'wg_auto_switch' );
		register_setting( 'my-plugin-settings-group', 'override_css' );
		register_setting( 'my-plugin-settings-group', 'flag_css' );
		register_setting( 'my-plugin-settings-group', 'with_flags' );
		register_setting( 'my-plugin-settings-group', 'type_flags' );
		register_setting( 'my-plugin-settings-group', 'with_name' );
		register_setting( 'my-plugin-settings-group', 'is_dropdown' );
		register_setting( 'my-plugin-settings-group', 'is_fullname' );
		register_setting( 'my-plugin-settings-group', 'is_menu' );
		register_setting( 'my-plugin-settings-group', 'exclude_url' );
		register_setting( 'my-plugin-settings-group', 'exclude_blocks' );
		register_setting( 'my-plugin-settings-group', 'rtl_ltr_style' );
	}

	public function plugin_settings_page() {
		include(WEGLOT_DIR.'/includes/wg-settings-page.php');
	}

	public function addWidget() {
		return register_widget("WeglotWidget");
	}

	public function treatPage($final) {

		$request_uri =  $this->request_uri;
		if(!is_admin() && strpos($request_uri,'wc-ajax') === false && $this->original_l!="" && $this->destination_l!="") {

			//$final = file_get_contents(__DIR__.'/content.html'); //Testing purpose.

			//Get the original request
			$url = 	($this->currentlang!=$this->original_l) ? substr($request_uri,3) : $request_uri;

			if($this->isEligibleURL($url) && WGUtils::is_HTML($final)) {

				//If a language is set, we translate the page & links.
				if($this->currentlang!=$this->original_l) {
					try {
						$l =  $this->currentlang;
						$final = $this->translatePageTo($final,$l);
					}
					catch(\Weglot\WeglotException $e) {
						$final .= "<!--Weglot error : ".$e->getMessage()."-->";
						if(strpos($e->getMessage(), 'NMC') !== false) {
							update_option('wg_allowed',0);
						}
					}
					catch(\Exception $e) {
						$final .= "<!--Weglot error : ".$e->getMessage()."-->";
					}
				}

				//Place the button if we see short code
				if (strpos($final,'<div id="weglot_here"></div>') !== false) {

					$button = $this->returnWidgetCode();
					$final = str_replace('<div id="weglot_here"></div>',$button,$final);
				}
				//Place the button if not in the page
				if (strpos($final,'class="wgcurrent') === false) {

					$button = $this->returnWidgetCode(true);
					$button = WGUtils::str_lreplace('<aside data-wg-notranslate class="','<aside data-wg-notranslate class="wg-default ',$button);
					$final = (strpos($final, '</body>') !== false) ? WGUtils::str_lreplace('</body>',$button.' </body>',$final):WGUtils::str_lreplace('</footer>',$button.' </footer>',$final);
				}
				return $final;
			}
			else {
				return $final;
			}
		}
		elseif((strpos($request_uri,'admin-ajax.php') !== false || strpos($request_uri,'?wc-ajax') !== false) && $this->destination_l!="" && $this->original_l!="" && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER["HTTP_REFERER"],'admin') === false ) {

			$thisL = $this->getLangFromUrl($this->URLToRelative($_SERVER["HTTP_REFERER"]));
			if(isset($thisL) && $thisL!='') {
				try {
					if($final[0]=='{' || ($final[0]=='[' && $final[1]=='{')) {
						$json = json_decode($final,true);
						if(json_last_error() == JSON_ERROR_NONE) {
							$jsonT = $this->translateArray($json,$thisL);
							return json_encode($jsonT);
						}
						else {
							return $final;
						}
					}
					elseif(WGUtils::is_AJAX_HTML($final)) {
						return $this->translatePageTo($final,$thisL);
					}
					else {
						return $final;
					}
				}
				catch(\Weglot\WeglotException $e) {
					return $final;
				}
				catch(\Exception $e) {
					return $final;
				}
			}
			else {
				return $final;
			}
		}
		else {
			return $final;
		}
	}

	/* translation of the page */
	function translateArray($array,$to) {
		foreach($array as $key=>$val) {
			if(is_array($val)) {
				$array[$key] = $this->translateArray($val,$to);
			}
			else {
				if(WGUtils::is_AJAX_HTML($val)) {
					$array[$key] = $this->translatePageTo($val,$to);
				}
			}
		}
		return $array;
	}

	function translatePageTo($final,$l) {

		if($this->allowed==0) {
			return $final."<!--Not allowed-->";
		}
		$translatedPage = $this->translator->translateDomFromTo($final,$this->original_l,$l); //$page is your html page
		// to do : condenser, replacer les endwidths par contient 'upload' ?

		$admin_url = admin_url();

		preg_match_all('/<a([^\>]+?)?href=(\"|\')([^\s\>]+?)(\"|\')([^\>]+?)?>/',$translatedPage,$out, PREG_PATTERN_ORDER);
		for($i=0;$i<count($out[0]);$i++) {
			$sometags = $out[1][$i];
			$sometags2 = $out[5][$i];
			$current_url = $out[3][$i];
			$parsed_url = parse_url($current_url);
			if((($current_url[0] == 'h' && $parsed_url['host']==$_SERVER['HTTP_HOST']) || ($current_url[0] =='/' && $current_url[1] !='/'))
				&& strpos($current_url,$admin_url) === false && strpos($current_url,'wp-login') === false && !WGUtils::endsWith($current_url,'.jpg') && !WGUtils::endsWith($current_url,'.jpeg') && !WGUtils::endsWith($current_url,'.png') && !WGUtils::endsWith($current_url,'.pdf')
				&& $this->isEligibleURL($current_url) && strpos($sometags,'data-wg-notranslate') === false && strpos($sometags2,'data-wg-notranslate') === false)
			{
				$translatedPage = preg_replace('/<a'.preg_quote($sometags,'/').'href='.preg_quote($out[2][$i].$current_url.$out[4][$i],'/').'/','<a'.$sometags.'href='.$out[2][$i].$this->replaceUrl($current_url,$l).$out[4][$i],$translatedPage);
			}
		}
		preg_match_all('/<([^\>]+?)?data-link=(\"|\')([^\s\>]+?)(\"|\')([^\>]+?)?>/',$translatedPage,$out, PREG_PATTERN_ORDER);
		for($i=0;$i<count($out[0]);$i++) {
			$sometags = $out[1][$i];
			$sometags2 = $out[5][$i];
			$current_url = $out[3][$i];
			$parsed_url = parse_url($current_url);
			if((($current_url[0] == 'h' && $parsed_url['host']==$_SERVER['HTTP_HOST']) || ($current_url[0] =='/' && $current_url[1] !='/'))
				&& strpos($current_url,$admin_url) === false && strpos($current_url,'wp-login') === false && !WGUtils::endsWith($current_url,'.jpg') && !WGUtils::endsWith($current_url,'.jpeg') && !WGUtils::endsWith($current_url,'.png') && !WGUtils::endsWith($current_url,'.pdf')
				&& $this->isEligibleURL($current_url) && strpos($sometags,'data-wg-notranslate') === false && strpos($sometags2,'data-wg-notranslate') === false)
			{
				$translatedPage = preg_replace('/<'.preg_quote($sometags,'/').'data-link='.preg_quote($out[2][$i].$current_url.$out[4][$i],'/').'/','<'.$sometags.'data-link='.$out[2][$i].$this->replaceUrl($current_url,$l).$out[4][$i],$translatedPage);
			}
		}
		preg_match_all('/<form([^\>]+?)?action=(\"|\')([^\s\>]+?)(\"|\')/',$translatedPage,$out, PREG_PATTERN_ORDER);
		for($i=0;$i<count($out[0]);$i++) {
			$sometags = $out[1][$i];
			$current_url = $out[3][$i];
			$parsed_url = parse_url($current_url);
			if((($current_url[0] == 'h' && $parsed_url['host']==$_SERVER['HTTP_HOST']) || ($current_url[0] =='/' && $current_url[1] !='/'))
				&& strpos($current_url,$admin_url) === false && strpos($current_url,'wp-login') === false && !WGUtils::endsWith($current_url,'.jpg') && !WGUtils::endsWith($current_url,'.jpeg') && !WGUtils::endsWith($current_url,'.png') && !WGUtils::endsWith($current_url,'.pdf')
				&& $this->isEligibleURL($current_url) && strpos($sometags,'data-wg-notranslate') === false)
			{
				$translatedPage = preg_replace('/<form'.preg_quote($sometags,'/').'action='.preg_quote($out[2][$i].$current_url.$out[4][$i],'/').'/','<form '.$sometags.'action='.$out[2][$i].$this->replaceUrl($current_url,$l).$out[4][$i],$translatedPage);
			}
		}
		preg_match_all('/<option (.*?)?(\"|\')((https?:\/\/|\/)[^\s\>]*?)(\"|\')(.*?)?>/',$translatedPage,$out, PREG_PATTERN_ORDER);
		for($i=0;$i<count($out[0]);$i++) {
			$sometags = $out[1][$i];
			$current_url = $out[3][$i];
			$parsed_url = parse_url($current_url);
			if((($current_url[0] == 'h' && $parsed_url['host']==$_SERVER['HTTP_HOST']) || $current_url[0] =='/')
				&& strpos($current_url,$admin_url) === false && strpos($current_url,'wp-login') === false && !WGUtils::endsWith($current_url,'.jpg') && !WGUtils::endsWith($current_url,'.jpeg') && !WGUtils::endsWith($current_url,'.png') && !WGUtils::endsWith($current_url,'.pdf')
				&& $this->isEligibleURL($current_url) && strpos($sometags,'data-wg-notranslate') === false)
			{
				$translatedPage = preg_replace('/<option '.preg_quote($sometags,'/').preg_quote($out[2][$i].$current_url.$out[5][$i],'/').'(.*?)?>/','<option '.$sometags.$out[2][$i].$this->replaceUrl($current_url,$l).$out[5][$i].'$2>',$translatedPage);
			}
		}
		preg_match_all('/<link rel="canonical"(.*?)?href=(\"|\')([^\s\>]+?)(\"|\')/',$translatedPage,$out, PREG_PATTERN_ORDER);
		for($i=0;$i<count($out[0]);$i++) {
			$sometags = $out[1][$i];
			$current_url = $out[3][$i];
			$parsed_url = parse_url($current_url);
			if((($current_url[0] == 'h' && $parsed_url['host']==$_SERVER['HTTP_HOST']) || $current_url[0] =='/')
				&& strpos($current_url,$admin_url) === false && strpos($current_url,'wp-login') === false && !WGUtils::endsWith($current_url,'.jpg') && !WGUtils::endsWith($current_url,'.jpeg') && !WGUtils::endsWith($current_url,'.png') && !WGUtils::endsWith($current_url,'.pdf')
				&& $this->isEligibleURL($current_url) && strpos($sometags,'data-wg-notranslate') === false)
			{
				$translatedPage = preg_replace('/<link rel="canonical"'.preg_quote($sometags,'/').'href='.preg_quote($out[2][$i].$current_url.$out[4][$i],'/').'/','<link rel="canonical"'.$sometags.'href='.$out[2][$i].$this->replaceUrl($current_url,$l).$out[4][$i],$translatedPage);
			}
		}

		preg_match_all('/<meta property="og:url"(.*?)?content=(\"|\')([^\s\>]+?)(\"|\')/',$translatedPage,$out, PREG_PATTERN_ORDER);
		for($i=0;$i<count($out[0]);$i++) {
			$sometags = $out[1][$i];
			$current_url = $out[3][$i];
			$parsed_url = parse_url($current_url);
			if((($current_url[0] == 'h' && $parsed_url['host']==$_SERVER['HTTP_HOST']) || $current_url[0] =='/')
				&& strpos($current_url,$admin_url) === false && strpos($current_url,'wp-login') === false && !WGUtils::endsWith($current_url,'.jpg') && !WGUtils::endsWith($current_url,'.jpeg') && !WGUtils::endsWith($current_url,'.png') && !WGUtils::endsWith($current_url,'.pdf')
				&& $this->isEligibleURL($current_url) && strpos($sometags,'wg-notranslate') === false)
			{
				$translatedPage = preg_replace('/<meta property="og:url"'.preg_quote($sometags,'/').'content='.preg_quote($out[2][$i].$current_url.$out[4][$i],'/').'/','<meta property="og:url"'.$sometags.'content='.$out[2][$i].$this->replaceUrl($current_url,$l).$out[4][$i],$translatedPage);
			}
		}

		$translatedPage = preg_replace('/<html (.*?)?lang=(\"|\')(\S*)(\"|\')/','<html $1lang=$2'.$l.'$4',$translatedPage);
		$translatedPage = preg_replace('/property="og:locale" content=(\"|\')(\S*)(\"|\')/','property="og:locale" content=$1'.$l.'$3',$translatedPage);
		return $translatedPage;
	}

	/* Urls functions */
	public function replaceUrl($url,$l) {
		$home_dir = $this->home_dir;
		if($home_dir) {
			$parsed_url = parse_url($url);
			$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
			$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
			$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
			$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
			$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
			$pass     = ($user || $pass) ? "$pass@" : '';
			$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '/';
			$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
			$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

			if($l=='') {
				return trim($home_dir, '/')."$l".$this->URLToRelative($url);
			}
			else {
				return trim($home_dir, '/')."/$l".$this->URLToRelative($url);
			}

		}
		else {
			$parsed_url = parse_url($url);
			$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
			$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
			$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
			$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
			$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
			$pass     = ($user || $pass) ? "$pass@" : '';
			$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '/';
			$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
			$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
			if($l=='') {
				return $url;
			}
			else {
				return (strlen($path)>2 && substr($path,0,4)=="/$l/") ? "$scheme$user$pass$host$port$path$query$fragment":"$scheme$user$pass$host$port/$l$path$query$fragment";
			}
		}
	}
	public function url_origin($s, $use_forwarded_host=false) {
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
		$sp = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
		$port = $s['SERVER_PORT'];
		$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
		$host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
		$host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}
	public function full_url($s, $use_forwarded_host=false) {
	   return $this->url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
	}
	public function isEligibleURL($url) {
		$url = $this->URLToRelative($url);

		$exclusions = preg_replace('#\s+#',',',get_option("exclude_url"));
		$exclusions = $exclusions=="" ? "/amp(/)?$":$exclusions.",/amp(/)?$";
		$regex = explode(",",$exclusions);

		if($exclusions!="") {
			foreach($regex as $ex) {
				if(preg_match('/'.str_replace('/', '\/',$ex).'/',$url)==1)
					return false;
			}
			return true;
		}
		else
			return true;
	}
	public function URLToRelative($url) {

		if ((substr($url, 0, 7) == 'http://') || (substr($url, 0, 8) == 'https://')) {
			// the current link is an "absolute" URL - parse it to get just the path
			$parsed = parse_url($url);
			$path     = isset($parsed['path']) ? $parsed['path'] : '';
			$query    = isset($parsed['query']) ? '?' . $parsed['query'] : '';
			$fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

			$home_dir = $this->home_dir;
			if($home_dir) {
				$relative = str_replace(trim($home_dir ,'/'),'',$url);
				return ($relative=="") ? '/':$relative;
			}
			else {
				return $path.$query.$fragment;
			}
		}
		return $url;
	}
	public function getRequestUri($home_dir) {
		if($home_dir) {
			return str_replace(trim($home_dir,'/'),'',$this->full_url($_SERVER));
		}
		else {
			return $_SERVER['REQUEST_URI'];
		}
	}
	public function getLangFromUrl($request_uri) {
		$l= null;
		$dest = explode(",",$this->destination_l);
		foreach($dest as $d) {
			if(substr($request_uri,0,4) == '/'.$d.'/')
				$l = $d;
		}
		return $l;
	}

	public function getHomeDirectory() {
		$opt_siteurl = trim(get_option("siteurl"),'/');
		$opt_home = trim(get_option("home"),'/');
		if($opt_siteurl!="" && $opt_home!="" /*&& $opt_siteurl==$opt_home*/) {
			if( (substr($opt_home,0,7) == "http://" && strpos(substr($opt_home,7),'/') !== false) ||  (substr($opt_home,0,8) == "https://" && strpos(substr($opt_home,8),'/') !== false) ) {
				return $opt_home;
			}
		}
		return null;
	}

	/* button function (code and CSS) */
	public function getInlineCSS() {
		$css= get_option("override_css");
		if( (WGUtils::isLanguageRTL($this->original_l) && !WGUtils::isLanguageRTL($this->currentlang)) ||
			(!WGUtils::isLanguageRTL($this->original_l) && WGUtils::isLanguageRTL($this->currentlang))) {
			$css.= get_option("rtl_ltr_style");
		}
		if(!is_admin()) {
			$css.= get_option("flag_css");
		}
		return $css;
	}

	public function returnWidgetCode($forceNoMenu = false) {
		$original = $this->original_l;
		$request_uri =  $this->request_uri;

		$url = 	($this->currentlang!=$this->original_l) ? substr($request_uri,3) : $request_uri;

		$full = get_option("is_fullname")=='on';
		$withname = get_option("with_name")=='on';
		$is_dropdown = get_option("is_dropdown")=='on';
		$is_menu = $forceNoMenu ? false:get_option("is_menu")=='on';
		$flag_class = (get_option("with_flags")=='on') ? 'wg-flags ':'';

		$type_flags = get_option("type_flags") ? get_option("type_flags"):0;
		$flag_class .= $type_flags==0 ? '':'flag-'.$type_flags.' ';

		$current = $this->currentlang;
		$list = $is_dropdown ? "<ul>":"";
		$destEx = explode(",",$this->destination_l);
		array_unshift($destEx,$original);
		foreach($destEx as $d) {
			if($d!=$current) {
				$link = (($d!=$original) ? $this->replaceUrl($url,$d):$this->replaceUrl($url,''));
				if($link=="/" && get_option("wg_auto_switch")=="on")
					$link = $link."?no_lredirect=true";
				$list .= '<li class="wg-li '.$flag_class.$d.'"><a data-wg-notranslate href="'.$link.'">'.($withname ? ($full? WGUtils::getLangNameFromCode($d,false):strtoupper($d)):"").'</a></li>';
			}
		}
		$list .= $is_dropdown ? "</ul>":"";
		$tag =  $is_dropdown ? "div":"li";

		$moreclass = (get_option("is_dropdown")=='on') ? 'wg-drop ':'wg-list ';

		$aside1 = ($is_menu && !$is_dropdown) ? '':'<aside data-wg-notranslate class="'.$moreclass.'country-selector closed" onclick="openClose(this);" >';
		$aside2 = ($is_menu && !$is_dropdown) ? '':'</aside>';

		$button = '<!--Weglot '.WEGLOT_VERSION.'-->'.$aside1.'<'.$tag.' data-wg-notranslate class="wgcurrent wg-li '.$flag_class.$current.'"><a href="javascript:void(0);" >'.($withname ? ($full? WGUtils::getLangNameFromCode($current,false):strtoupper($current)):"").'</a></'.$tag.'>'.$list.$aside2;

		return $button;
	}
}

register_activation_hook( __FILE__, array('Weglot', 'plugin_activate') );
register_deactivation_hook( __FILE__, array('Weglot', 'plugin_deactivate') );
register_uninstall_hook( __FILE__, array('Weglot', 'plugin_uninstall') );

add_action( 'plugins_loaded', array ( 'Weglot', 'Instance' ), 10 );
//Weglot::Instance();
