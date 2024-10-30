<?php
get_header();?>
	<div id="content">
		<div id="daily-specials" class="JRWDEV_DailySpecials">
			<?php query_posts(array('post_type'=>'daily_specials','posts_per_page'=>-1, 'orderby' => $orderby, 'order' => "DESC"));?>
			<?php if(have_posts()): while(have_posts()): the_post();?>
			<?php 	$price = get_field('price');
					$deal = get_field('deal');
					$day = get_field('weekday');
			?>

			<div class="ds_col <?php if($ds_i%3==1):?>first<?php endif;?> daily-specials-each">
				<p class='weekday'><?php echo implode(', ', $day);?></p>
				<p class='special-title'><?php the_title();?></p>
				<p class='special-description'><?php the_field('description');?></p>
				<?php if($price!=""):?>
				<p class='price-text'>Special Price:</p>
				<p class='price'><?php echo format_as_price($price);?></p>
				<?php elseif($deal!=""): ?>
				<p class='deal'><?php echo $deal;?></p>
				<?php endif;?>
			</div>
			<?php endwhile; endif;?>
			<?php wp_reset_query();?>
		</div>
	</div>
<?php get_footer();?>