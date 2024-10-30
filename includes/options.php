<?php
/**
 * @package JRWDEV Daily Specials
 * @since version 1.0
 */

 /* ------------------------------------------------------------------
 * Do Not Allow Direct Script Access
 * --------------------------------------------------------------- */
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

define('DS_OPTIONS_ADMIN_PAGE_NAME', 'daily-specials');
define('DS_OPTIONS_PAGE_HOOK','settings_page_'.DS_OPTIONS_ADMIN_PAGE_NAME);

 /* ------------------------------------------------------------------
 * Load JS for Administration Only
 * --------------------------------------------------------------- */
function jrwdev_ds_load_admin_scripts($hook) {

	if( $hook != DS_OPTIONS_PAGE_HOOK ) {
		return;
	}

	// Enqueues Needed Admin Styles
	wp_enqueue_script( 'jrwdev-ds-admin-scripts', JRWDEV_DS_PLUGIN_URL.'/includes/js/admin-scripts.js' );
}
add_action('admin_enqueue_scripts', 'jrwdev_ds_load_admin_scripts');

/* ------------------------------------------------------------------
 * Load CSS for Administration Only
 * --------------------------------------------------------------- */
function jrwdev_ds_load_admin_styles($hook) {

	if( $hook != DS_OPTIONS_PAGE_HOOK ) {
		return;
	}

	// Enqueues Needed Admin Styles
	wp_enqueue_style( 'jrwdev-ds-admin-styles', JRWDEV_DS_PLUGIN_URL.'/includes/css/admin-styles.css' );
}
add_action('admin_enqueue_scripts', 'jrwdev_ds_load_admin_styles');

//class that reperesent the complete plugin
class JRWDEV_DailySpecials_Settings {

	//constructor of class, PHP4 compatible construction for backward compatibility
	function JRWDEV_DailySpecials_Settings() {

		//add filter for WordPress 2.8 changed backend box system !
		add_filter('screen_layout_columns', array(&$this, 'on_screen_layout_columns'), 10, 2);

		//register callback for admin menu  setup
		add_action('admin_menu', array(&$this, 'on_admin_menu')); 

		//register the callback been used if options of page been submitted and needs to be processed
		add_action('admin_post_save_daily_specials_general', array(&$this, 'on_save_changes'));

	}

	//extend the admin menu
	function on_admin_menu() {

		//add our own option page, you can also add it to different sections or use your own one
		$this->pagehook = add_options_page('Daily Specials', "Daily Specials", 'manage_options', DS_OPTIONS_ADMIN_PAGE_NAME, array(&$this, 'on_show_page'));
		//register  callback gets call prior your own page gets rendered
		add_action('load-'.$this->pagehook, array(&$this, 'on_load_page'));
	}

	//will be executed if wordpress core detects this page has to be rendered
	function on_load_page() {

		//ensure, that the needed javascripts been loaded to allow drag/drop, expand/collapse and hide/show of boxes
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');

		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
	}

	//executed to show the plugins complete admin page
	function on_show_page() {

		add_meta_box('ds-shortcode-documentation', 'Shortcode Documentation', array(&$this, 'ds_shortcode_documentation'), $this->pagehook, 'shortcode', 'core');
		add_meta_box('ds-widget-documentation', 'Widget Documentation', array(&$this, 'ds_widget_documentation'), $this->pagehook, 'widgets', 'core');
		add_meta_box('ds-widget-defaults', 'Widget Default Settings', array(&$this, 'ds_widget_default_settings'), $this->pagehook, 'widgets', 'core');

		$options = get_option('jrwdev-daily-specials');
		?>
		<div id="plugin-options" class="wrap">
		<?php screen_icon('options-general'); ?>
		<h2>Daily Specials Settings</h2>
		<form action="options.php" method="post">
			<h2 class="nav-tab-wrapper">
				<a href="#widgets" class="nav-tab"><?php _e( 'Widgets'); ?></a>
				<a href="#shortcode" class="nav-tab"><?php _e( 'Shortcode'); ?></a>
			</h2>
			<?php wp_nonce_field('update-options') ?>
			<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
			<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
			<input type="hidden" name="action" value="update" />  
            <input type="hidden" name="page_options" value="jrwdev-daily-specials" />
			<div id="tab_container">
				<div class="tab_content" id="widgets">
					<h3><?php _e( 'Widget Defaults', 'daily-specials' ); ?></h3>
					<div id="poststuff" class="metabox-holder">
						<div id="left-column" >
							<div id="post-body-content" >
								<?php do_meta_boxes($this->pagehook, 'widgets', @$data); ?>
							</div>
						</div>
						<div id="right-column" class="wp-box">
							<div class="inner">
								<h1>Daily Specials</h1>
								<h3><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EPD25LMXF4A4E">Donate</a></h3>
								<p>Please support this plugin!</p>
								<h3><a target="_blank" href="http://twitter.com/jrwdev">Updates</a></h3>
								<p>Subscribe to my twitter for updated info.</p>
								<h3><a target="_blank" href="http://wordpress.org/support/plugin/jrwdev-daily-specials">Support</a></h3>
								<p>Have a question? Find an issue? Let me know in the support forum.</p>
							</div>
							<div class="footer">
								<p><a target="_blank" href="http://twitter.com/jrwdev">Twitter</a> | <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EPD25LMXF4A4E">Donate</a> | <a target="_blank" href="http://wordpress.org/support/plugin/jrwdev-daily-specials">Get Support</a></p>
							</div>
						</div>
						<br class="clear"/>
						<p>
							<input type="submit" value="Save Changes" class="button-primary" name="Submit"/>	
						</p>				
					</div>
				</div>
				<div class="tab_content" id="shortcode">
					<h3><?php _e( 'Basic Settings', 'daily-specials' ); ?></h3>
					<div id="poststuff" class="metabox-holder">
						<div id="left-column" >
							<div id="post-body-content">
								<?php do_meta_boxes($this->pagehook, 'shortcode', @$data); ?>		
							</div>
						</div>
						<div id="right-column" class="wp-box">
							<div class="inner">
								<h1>Daily Specials</h1>
								<h3><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EPD25LMXF4A4E">Donate</a></h3>
								<p>Please support this plugin!</p>
								<h3><a target="_blank" href="http://twitter.com/jrwdev">Updates</a></h3>
								<p>Subscribe to my twitter for updated info.</p>
								<h3><a target="_blank" href="http://wordpress.org/support/plugin/jrwdev-daily-specials">Support</a></h3>
								<p>Have a question? Find an issue? Let me know in the support forum.</p>
							</div>
							<div class="footer">
								<p><a target="_blank" href="http://twitter.com/jrwdev">Twitter</a> | <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EPD25LMXF4A4E">Donate</a> | <a target="_blank" href="http://wordpress.org/support/plugin/jrwdev-daily-specials">Get Support</a></p>
							</div>
						</div>
						<br class="clear"/>
						<p>
							<input type="submit" value="Save Changes" class="button-primary" name="Submit"/>	
						</p>				
					</div>
				</div>
			</div>
		</form>
		</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// show all postboxes
			$('.hide-if-js').removeClass('hide-if-js');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
		});
		//]]>
	</script>
		<?php
	}
	
	//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes() {

		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Cheatin&#8217; uh?') );			
		//cross check the given referer
		check_admin_referer('update-options');
		//process here your on $_POST validation and / or option saving
		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect($_POST['_wp_http_referer']);		
	}

	function ds_shortcode_documentation($data) {

		$options = get_option('jrwdev-daily-specials');
		@sort($data);
		?>
			<p>At this point, there's not much documentation because there's not much functionality. If you want a feature added to this plugin, comment on <a href="http://www.jrwdev.com/2011/09/daily-specials-widget-for-wordpress/">my post</a> and let me know what you want added!</p>
			<p>To use the shortcode on a page or post add <code>[daily-specials]</code> to your page or post.</p>
			<p>You can add a title to the specials slider by adding a title attribute to the shortcode (e.g. <code>[daily-specials title="Our Sweet Specials"]</code>).</p>
			<!--<input type="text" name="jrwdev-daily-specials[shortcode]" value="<?php echo $options['shortcode'];?>" />-->
		<?php
	}

	function ds_widget_documentation($data) {

		$options = get_option('jrwdev-daily-specials');
		@sort($data);
		?>
			<p>The Daily Specials widget is a multi-widget. This means that you can have as many instances of the widget as you'd like! Each one will keep its own individual settings. Below you'll see options for setting the defaults on all of your daily specials widgets. These are the defaults when you drag the widget into one of your sidebars. All of these settings can be overridden on an individual basis in the widget itself. This just makes it easy to set all the defaults in one place.</p>
		<?php
	}

	function ds_widget_default_settings($data) {

		$options = get_option('jrwdev-daily-specials');
		sort($data);
		$defaults = JRWDEV_DailySpecials::$default_args;
		$default_options = array_merge($defaults, (array) $options['widget_defaults']);
		extract((array) $default_options, EXTR_SKIP);
		?>
			<p>
			<label for="title">Title:</label>
			<input id="title" name="jrwdev-daily-specials[widget_defaults][title]" value="<?php echo esc_attr($title); ?>" class="widefat" />
			</p>
			<p>
			<label for="margin">Margin Around Specials:</label><br />
			<small>(e.g. 15px, 20%, 10px 5px)</small>
			<input id="margin"	name="jrwdev-daily-specials[widget_defaults][margin]" value="<?php echo esc_attr($margin); ?>" class="widefat" />
			</p>
			<p>
			<label for="animation">Animation:</label>
			<select id="animation"	name="jrwdev-daily-specials[widget_defaults][animation]" class="widefat">			
				<option <?php echo ($animation=="blindX") ? "selected" : "";?>>blindX</option>
				<option <?php echo ($animation=="blindY") ? "selected" : "";?>>blindY</option>
				<option <?php echo ($animation=="blindZ") ? "selected" : "";?>>blindZ</option>
				<option <?php echo ($animation=="cover") ? "selected" : "";?>>cover</option>
				<option <?php echo ($animation=="curtainX") ? "selected" : "";?>>curtainX</option>
				<option <?php echo ($animation=="curtainY") ? "selected" : "";?>>curtainY</option>
				<option <?php echo ($animation=="fade") ? "selected" : "";?>>fade</option>
				<option <?php echo ($animation=="fadeZoom") ? "selected" : "";?>>fadeZoom</option>
				<option <?php echo ($animation=="growX") ? "selected" : "";?>>growX</option>
				<option <?php echo ($animation=="growY") ? "selected" : "";?>>growY</option>
				<option <?php echo ($animation=="none") ? "selected" : "";?>>none</option>
				<option <?php echo ($animation=="scrollUp") ? "selected" : "";?>>scrollUp</option>
				<option <?php echo ($animation=="scrollDown") ? "selected" : "";?>>scrollDown</option>
				<option <?php echo ($animation=="scrollLeft") ? "selected" : "";?>>scrollLeft</option>
				<option <?php echo ($animation=="scrollRight") ? "selected" : "";?>>scrollRight</option>
				<option <?php echo ($animation=="scrollHorz") ? "selected" : "";?>>scrollHorz</option>
				<option <?php echo ($animation=="scrollVert") ? "selected" : "";?>>scrollVert</option>
				<option <?php echo ($animation=="shuffle") ? "selected" : "";?>>shuffle</option>
				<option <?php echo ($animation=="slideX") ? "selected" : "";?>>slideX</option>
				<option <?php echo ($animation=="slideY") ? "selected" : "";?>>slideY</option>
				<option <?php echo ($animation=="toss") ? "selected" : "";?>>toss</option>
				<option <?php echo ($animation=="turnUp") ? "selected" : "";?>>turnUp</option>
				<option <?php echo ($animation=="turnDown") ? "selected" : "";?>>turnDown</option>
				<option <?php echo ($animation=="turnLeft") ? "selected" : "";?>>turnLeft</option>
				<option <?php echo ($animation=="turnRight") ? "selected" : "";?>>turnRight</option>
				<option <?php echo ($animation=="uncover") ? "selected" : "";?>>uncover</option>
				<option <?php echo ($animation=="wipe") ? "selected" : "";?>>wipe</option>
				<option <?php echo ($animation=="zoom") ? "selected" : "";?>>zoom</option>
			</select>
			</p>
			<p>
			<label for="animation_speed">Animation Speed (in ms):</label><br />
			<small>(e.g. 100, 500, 3000, 5000)</small>
			<input id="animation_speed"	name="jrwdev-daily-specials[widget_defaults][animation_speed]" value="<?php echo esc_attr($animation_speed); ?>" class="widefat" />
			</p>
			<p>
			<label for="animation_timeout">Animation Pause Time (in ms):</label><br />
			<small>(e.g. 100, 500, 3000, 5000)</small>
			<input id="animation_timeout"	name="jrwdev-daily-specials[widget_defaults][animation_timeout]" value="<?php echo esc_attr($animation_timeout); ?>" class="widefat" />
			</p>
			<p>
			<label for="weekday_font_size">Weekday Font Size:</label><br />
			<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
			<input id="weekday_font_size"	name="jrwdev-daily-specials[widget_defaults][weekday_font_size]" value="<?php echo esc_attr($weekday_font_size); ?>" class="widefat" />
			</p>
			<p>
			<label for="weekday_font_weight">Weekday Font Weight:</label><br />
			<small>(e.g. lighter, normal, bold)</small>
			<input id="weekday_font_weight"	name="jrwdev-daily-specials[widget_defaults][weekday_font_weight]" value="<?php echo esc_attr($weekday_font_weight); ?>" class="widefat" />
			</p>
			<p>
			<label for="special_title_font_size">Special Title Font Size:</label><br />
			<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
			<input id="special_title_font_size"	name="jrwdev-daily-specials[widget_defaults][special_title_font_size]" value="<?php echo esc_attr($special_title_font_size); ?>" class="widefat" />
			</p>
			<p>
			<label for="special_title_font_weight">Special Title Font Weight:</label><br />
			<small>(e.g. lighter, normal, bold)</small>
			<input id="special_title_font_weight"	name="jrwdev-daily-specials[widget_defaults][special_title_font_weight]" value="<?php echo esc_attr($special_title_font_weight); ?>" class="widefat" />
			</p>
			<p>
			<label for="special_font_size">Special Font Size:</label><br />
			<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
			<input id="special_font_size"	name="jrwdev-daily-specials[widget_defaults][special_font_size]" value="<?php echo esc_attr($special_font_size); ?>" class="widefat" />
			</p>
			<p>
			<label for="special_font_weight">Special Font Weight:</label><br />
			<small>(e.g. lighter, normal, bold)</small>
			<input id="special_font_weight"	name="jrwdev-daily-specials[widget_defaults][special_font_weight]" value="<?php echo esc_attr($special_font_weight); ?>" class="widefat" />
			</p>
			<p>
			<label for="price_text_font_size">Price Text Font Size:</label><br />
			<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
			<input id="price_text_font_size"	name="jrwdev-daily-specials[widget_defaults][price_text_font_size]" value="<?php echo esc_attr($price_text_font_size); ?>" class="widefat" />
			</p>
			<p>
			<label for="price_text_font_weight">Price Text Font Weight:</label><br />
			<small>(e.g. lighter, normal, bold)</small>
			<input id="price_text_font_weight"	name="jrwdev-daily-specials[widget_defaults][price_text_font_weight]" value="<?php echo esc_attr($price_text_font_weight); ?>" class="widefat" />
			</p>
			<p>
			<label for="price_font_size">Price Font Size:</label><br />
			<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
			<input id="price_font_size"	name="jrwdev-daily-specials[widget_defaults][price_font_size]" value="<?php echo esc_attr($price_font_size); ?>" class="widefat" />
			</p>
			<p>
			<label for="price_font_weight">Price Font Weight:</label><br />
			<small>(e.g. lighter, normal, bold)</small>
			<input id="price_font_weight"	name="jrwdev-daily-specials[widget_defaults][price_font_weight]" value="<?php echo esc_attr($price_font_weight); ?>" class="widefat" />
			</p>
		<?php
	}

}

$JRWDEV_DailySpecials_Settings = new JRWDEV_DailySpecials_Settings();