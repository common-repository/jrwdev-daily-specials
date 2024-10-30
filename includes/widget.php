<?php
/**
 * @package JRWDEV Daily Specials
 * @since version 1.0
 */

/* Widget Functionality */
Class JRWDEV_DailySpecials extends WP_Widget {

	public static $default_args = array( 
		'title' => 'Daily Specials',
		'animation' => 'fade',
		'animation_speed' => '500',
		'animation_timeout' => '5500',
		'styles' => '',
		'margin' => '10px',
		'weekday_font_size' => '15px',
		'weekday_font_weight' => 'bold',
		'special_title_font_size' => '13px',
		'special_title_font_weight' => 'bold',
		'special_font_size' => '12px',
		'special_font_weight' => 'normal',
		'price_text_font_size' => '14px',
		'price_text_font_weight' => 'bold',
		'price_font_size' => '14px',
		'price_font_weight' => 'bold'
	);

	public function JRWDEV_DailySpecials() {

		$widget_options = array(
			'classname' => 'JRWDEV_DailySpecials',
			'description' => 'A widget to show today\'s Daily Special');

		parent::WP_Widget('JRWDEV_DailySpecials', 'Daily Specials', $widget_options);
	}

	protected function format_as_price($price, $symbol="$", $format=2) {

		$formatted_price = number_format(round($price,2), $format, '.', ',');
		$formatted_price = $symbol.$formatted_price;

		return $formatted_price;
	}

	public function widget($args, $instance) {

		//Set defaults from daily-specials options screen for widget defaults and merge with above defaults
		//Overwrite any defaults above with defaults from the user supplied options
		$options = get_option('jrwdev-daily-specials');
		$options = $options['widget_defaults'];
		$options = array_merge(JRWDEV_DailySpecials::$default_args, (array) $options);

		//Finally merge options from this instance with the user supplied options and defaults
		$instance = array_merge($options, $instance);

		extract( $args, EXTR_SKIP );
		extract( $instance, EXTR_SKIP );

		$title = apply_filters('widget_title', $title); // the widget title
		$orderby = (@$sort=="Weekday") ? "date" : "menu_order";
		$category = ($category=="All Categories") ? "" : $category;
		$category_query = ($category=="All Categories") ? "" : 'daily-special-category';
		$loop = new WP_Query( array( 'post_type' => 'daily_specials', 'orderby' => $orderby, 'order' => "DESC", 'posts_per_page' => -1, $category_query => $category ) );

		if ( $loop->have_posts() ) :
			echo $before_widget;
			if ( $title ) { echo $before_title . $title . $after_title; }
			
			echo "<div class='cycle-{$widget_id}' style='margin:{$margin};'>";
					$output = array();
					while ( $loop->have_posts() ) : $loop->the_post();

						$price = get_field('price');
						$deal = get_field('deal');
						
						$weekday = get_field('weekday');
						if(!is_array($weekday)){ @$weekday[] = $weekday; }
						foreach($weekday as $day):

							if($sort=="Weekday"):
								switch ($day) {
									case "Sunday" :
										$order = 0;
										break;
									case "Monday" :
										$order = 1;
										break;
									case "Tuesday" :
										$order = 2;
										break;
									case "Wednesday" :
										$order = 3;
										break;
									case "Thursday" :
										$order = 4;
										break;
									case "Friday" :
										$order = 5;
										break;
									case "Saturday" :
										$order = 6;
										break;
									default:
										$order = 1;
								}
							else:
								$order = "";
							endif;

							$special  = "";
							$special .= "<div class='daily-specials-each'>";

							if($styles):

								$special .= "<p class='weekday' style='font-size:{$weekday_font_size}; font-weight:{$weekday_font_weight};'>{$day}</p>";
								$special .= "<p class='special-title' style='font-size:{$special_title_font_size}; font-weight:{$special_title_font_weight};'>".get_the_title()."</p>";
								$special .= "<p class='special-description' style='font-size:{$special_font_size}; font-weight:{$special_font_weight};'>".get_field('description')."</p>";
								if($price!=""):
								$special .= "<p class='price-text' style='font-size:{$price_text_font_size}; font-weight:{$price_text_font_weight};'>Special Price:</p>";
								$special .= "<p class='price' style='font-size:{$price_font_size}; font-weight:{$price_font_weight};'>".$this->format_as_price($price)."</p>";
								elseif($deal!=""):
								$special .= "<p class='deal' style='font-size:{$price_font_size}; font-weight:{$price_font_weight};'>".$deal."</p>";
								endif;

							else:

								$special .= "<p class='weekday'>{$day}</p>";
								$special .= "<p class='special-title'>".get_the_title()."</p>";
								$special .= "<p class='special-description'>".get_field('description')."</p>";
								if($price!=""):
								$special .= "<p class='price-text'>Special Price:</p>";
								$special .= "<p class='price'>".$this->format_as_price($price)."</p>";
								elseif($deal!=""):
								$special .= "<p class='deal'>".$deal."</p>";
								endif;

							endif;

							$special .= "<div class='clear'></div>";
							$special .= "</div>";

							$output[$order][] = $special;


						endforeach;

						ksort($output);

					endwhile;

					foreach ($output as $weekdays):
						foreach ($weekdays as $daily_special):
							$final_output[] = $daily_special;
						endforeach;
					endforeach;

					if(!is_array($final_output)){ @$final_output[] = $final_output; }
					foreach ($final_output as $key => $value) :
						// if ($key == date("w")) :	
							echo $value;
						// endif;
					endforeach;

					echo "</div>";

			echo $after_widget;
		
		endif;
		?>
		<script>
		jQuery(document).ready( function(){
			jQuery('.cycle-<?php echo $widget_id;?>').cycle({
				fx: '<?php echo $animation;?>',
				speed: <?php echo $animation_speed;?>,
				timeout: <?php echo $animation_timeout;?>,
				pause: 1
			});
		});
		</script>
		<?php
	}

	public function form( $instance ) {
		
		$options = get_option('jrwdev-daily-specials');
		$options = $options['widget_defaults'];
		$options = array_merge(JRWDEV_DailySpecials::$default_args, (array) $options);
		$instance = wp_parse_args( (array) $instance, $options );
		extract($instance, EXTR_SKIP);
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title');?>">Title:</label>
		<input id="<?php echo $this->get_field_id('title');?>"	name="<?php echo $this->get_field_name('title');?>" value="<?php echo esc_attr($title); ?>" class="widefat" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('sort');?>">Order By:</label>
		<select id="<?php echo $this->get_field_id('sort');?>"	name="<?php echo $this->get_field_name('sort');?>" value="<?php echo esc_attr($sort); ?>" class="widefat">
			<option <?php echo ($sort=="Weekday") ? "selected" : "";?>>Weekday</option>
			<option <?php echo ($sort=="Custom") ? "selected" : "";?>>Custom</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('category');?>">Show by Category:</label>
		<select id="<?php echo $this->get_field_id('category');?>"	name="<?php echo $this->get_field_name('category');?>" value="<?php echo esc_attr($category); ?>" class="widefat">
			<option <?php echo ($category=="All Categories") ? "selected" : "";?>>All Categories</option>
			<?php $categories = get_terms('daily-special-category');
				foreach ($categories as $term):?>
			<option <?php echo ($category==$term->name) ? "selected" : "";?>><?php echo $term->name;?></option>
			<?php endforeach;?>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('animation');?>">Animation:</label>
		<select id="<?php echo $this->get_field_id('animation');?>"	name="<?php echo $this->get_field_name('animation');?>" class="widefat">			
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
		<label for="<?php echo $this->get_field_id('animation_speed');?>">Animation Speed (in ms):</label><br />
		<small>(e.g. 100, 500, 3000, 5000)</small>
		<input id="<?php echo $this->get_field_id('animation_speed');?>"	name="<?php echo $this->get_field_name('animation_speed');?>" value="<?php echo esc_attr($animation_speed); ?>" class="widefat" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('animation_timeout');?>">Animation Pause Time (in ms):</label><br />
		<small>(e.g. 100, 500, 3000, 5000)</small>
		<input id="<?php echo $this->get_field_id('animation_timeout');?>"	name="<?php echo $this->get_field_name('animation_timeout');?>" value="<?php echo esc_attr($animation_timeout); ?>" class="widefat" />
		</p>
		<p>
		<input class="checkbox" type="checkbox" <?php checked($styles, 'on') ?> id="<?php echo $this->get_field_id('styles'); ?>" name="<?php echo $this->get_field_name('styles'); ?>" />
		<label for="<?php echo $this->get_field_id('styles'); ?>"><?php _e('Turn On In-Line Styles'); ?></label>
		</p>
		<div class="style-inputs">
		<p>
		<label for="<?php echo $this->get_field_id('margin');?>">Margin Around Specials:</label><br />
		<small>(e.g. 15px, 20%, 10px 5px)</small>
		<input id="<?php echo $this->get_field_id('margin');?>"	name="<?php echo $this->get_field_name('margin');?>" value="<?php echo esc_attr($margin); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('weekday_font_size');?>">Weekday Font Size:</label><br />
		<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
		<input id="<?php echo $this->get_field_id('weekday_font_size');?>"	name="<?php echo $this->get_field_name('weekday_font_size');?>" value="<?php echo esc_attr($weekday_font_size); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('weekday_font_weight');?>">Weekday Font Weight:</label><br />
		<small>(e.g. lighter, normal, bold)</small>
		<input id="<?php echo $this->get_field_id('weekday_font_weight');?>"	name="<?php echo $this->get_field_name('weekday_font_weight');?>" value="<?php echo esc_attr($weekday_font_weight); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('special_title_font_size');?>">Special Title Font Size:</label><br />
		<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
		<input id="<?php echo $this->get_field_id('special_title_font_size');?>"	name="<?php echo $this->get_field_name('special_title_font_size');?>" value="<?php echo esc_attr($special_title_font_size); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('special_title_font_weight');?>">Special Title Font Weight:</label><br />
		<small>(e.g. lighter, normal, bold)</small>
		<input id="<?php echo $this->get_field_id('special_title_font_weight');?>"	name="<?php echo $this->get_field_name('special_title_font_weight');?>" value="<?php echo esc_attr($special_title_font_weight); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('special_font_size');?>">Special Font Size:</label><br />
		<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
		<input id="<?php echo $this->get_field_id('special_font_size');?>"	name="<?php echo $this->get_field_name('special_font_size');?>" value="<?php echo esc_attr($special_font_size); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('special_font_weight');?>">Special Font Weight:</label><br />
		<small>(e.g. lighter, normal, bold)</small>
		<input id="<?php echo $this->get_field_id('special_font_weight');?>"	name="<?php echo $this->get_field_name('special_font_weight');?>" value="<?php echo esc_attr($special_font_weight); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('price_text_font_size');?>">Price Text Font Size:</label><br />
		<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
		<input id="<?php echo $this->get_field_id('price_text_font_size');?>"	name="<?php echo $this->get_field_name('price_text_font_size');?>" value="<?php echo esc_attr($price_text_font_size); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('price_text_font_weight');?>">Price Text Font Weight:</label><br />
		<small>(e.g. lighter, normal, bold)</small>
		<input id="<?php echo $this->get_field_id('price_text_font_weight');?>"	name="<?php echo $this->get_field_name('price_text_font_weight');?>" value="<?php echo esc_attr($price_text_font_weight); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('price_font_size');?>">Price Font Size:</label><br />
		<small>(e.g. 15px, 1.1em, 12pt, 150%)</small>
		<input id="<?php echo $this->get_field_id('price_font_size');?>"	name="<?php echo $this->get_field_name('price_font_size');?>" value="<?php echo esc_attr($price_font_size); ?>" class="widefat inline-style" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('price_font_weight');?>">Price Font Weight:</label><br />
		<small>(e.g. lighter, normal, bold)</small>
		<input id="<?php echo $this->get_field_id('price_font_weight');?>"	name="<?php echo $this->get_field_name('price_font_weight');?>" value="<?php echo esc_attr($price_font_weight); ?>" class="widefat inline-style" />
		</p>
		</div>
		<script>
		jQuery(function($){
			if($("div[id*=<?php echo $this->id;?>] #<?php echo $this->get_field_id('styles'); ?>").prop('checked')==true){
				$("div[id*=<?php echo $this->id;?>] .inline-style").prop('disabled', false);
				$("div[id*=<?php echo $this->id;?>] .style-inputs").show();
			} else {
				$("div[id*=<?php echo $this->id;?>] .inline-style").prop('disabled', true);
				$("div[id*=<?php echo $this->id;?>] .style-inputs").hide();
			}
			$("div[id*=<?php echo $this->id;?>] #<?php echo $this->get_field_id('styles'); ?>").click(function(){
				if($(this).prop('checked')==true){
					$("div[id*=<?php echo $this->id;?>] .inline-style").prop('disabled', false);
					$("div[id*=<?php echo $this->id;?>] .style-inputs").slideDown();
				} else {
					$("div[id*=<?php echo $this->id;?>] .inline-style").prop('disabled', true);
					$("div[id*=<?php echo $this->id;?>] .style-inputs").slideUp();
				}
			});
		});
		</script>
		<?php
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$new_instance = (array) $new_instance;
		$instance = array( 'styles' => 0 );

		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = 1;
		}
		foreach($new_instance as $f => $v):
			$instance[$f] = strip_tags( $v );
		endforeach;

		return $instance;
	}
}

add_action('widgets_init','JRWDEV_dailyspecials_widgets_init');
function JRWDEV_dailyspecials_widgets_init() {
	register_widget("JRWDEV_DailySpecials");
}

/* Widget Scripts */
function JRWDEV_dailyspecials_get_scripts() {
	//no longer used for widget -- keep in for shortcode funcionality
	//@TODO move to shortcode.php
	wp_enqueue_script("daily-specials",	JRWDEV_DS_PLUGIN_URL. "includes/js/jrwdev-daily-specials.js", array("jquery"), JRWDEV_DAILYSPECIALS_VERSION);
	wp_enqueue_script("jQueryCycle", JRWDEV_DS_PLUGIN_URL. "includes/js/jquery.cycle.all.min.js", array("jquery") );
}
add_action('wp_enqueue_scripts', 'JRWDEV_dailyspecials_get_scripts');

/* Widget CSS */
function JRWDEV_dailyspecials_get_css() {
	wp_enqueue_style('daily-specials', JRWDEV_DS_PLUGIN_URL."includes/css/jrwdev-daily-specials.css", false, JRWDEV_DAILYSPECIALS_VERSION);
}
add_action('wp_enqueue_scripts', 'JRWDEV_dailyspecials_get_css');