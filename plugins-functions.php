<?php 
/*
Plugin Name: Scrollbar Supper Wordpress
Plugin URI: http://websitebuilderbd.com/plugins/scrollbar-supper/
Description: Scrollbar supper is a Jquery scrollbar for wordpress website. The Scrollbar is easy to embed especially document, and have smooth option features. The Scrollbar is portions of your wordpress web page automatically updates itself and also can use easy to customize both business and personal wordpress website.
Author: MD. Mamunur Roshid
Version: 1.0
Author URI:  http://websitebuilderbd.com/plugins
*/


/* Adding Latest jQuery from Wordpress */
function WM_scrollbar_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'WM_scrollbar_latest_jquery');

// Setting files

define ('CUSTOM_SCROLLBAR_FILE', WP_PLUGIN_URL . '/' .plugin_basename(dirname(__FILE__)) .'/');
wp_enqueue_script('WM_scrollbar_color_picker_js', CUSTOM_SCROLLBAR_FILE.'/js/jquery.nicescroll.min.js', array('jquery'));
wp_enqueue_style( 'WM_scrollbar_css', CUSTOM_SCROLLBAR_FILE.'/css/style.css');

// options panel hook
function WM_scrollbar_options_framework(){
	add_options_page('Scrollbar Supper Options','Scrollbar Supper Options', 'manage_options', 'WM-scrollbar-settings','WM_crollbar_supper_options_framework');
	
}
add_action('admin_menu','WM_scrollbar_options_framework');

// color picker hook
add_action('admin_enqueue_scripts','scrollbar_WM_colar_picker_function');
function scrollbar_WM_colar_picker_function($hook_suffix){
	wp_enqueue_style( 'wp-color-picker');
	wp_enqueue_script('my-script-handle', plugins_url('/js/carousel-color-script.js', __FILE__), array('wp-color-picker'), true);
}

// settings options 
$WM_scrollbar_options = array(
	'cursor_color' => '#666',
	'cursor_width' => '5px',
	'cursor_border_radius' => '0px',
	'cursor_border' => '0px solid #000',
	'scroll_speed' => '60',
	'auto_hide_mode' => true,
);

if (is_admin()) :

function WM_scrollbar_register_settings(){
	register_setting('WM_scrollbar_supper_options','WM_scrollbar_options','WM_scrollbar_validate_options');
}
add_action('admin_init','WM_scrollbar_register_settings');

$auto_hide_mode =array(
	'auto_hide_yes' => array(
		'value' => 'true',
		'label' => 'Active auto hide',
	),
	'auto_hide_no' => array(
		'value' => 'false',
		'label' => 'Deactive auto hide',
	),
);

// Options panel items
function WM_crollbar_supper_options_framework(){
	
	global $WM_scrollbar_options, $auto_hide_mode;
	
	if (! isset ($_REQUEST['updated']))
		$_REQUEST['updated']= false;
	?>
		
		<div class="wrap">
		<h2> Scrollbar Supper options </h2>
		<?php if (false !== $_REQUEST['updated']) : ?>
		<div class="updated fade"><p><strong><?php _e('options saved'); ?></strong></p></div>
		<?php endif; ?>
		
		<form method="post" action="options.php">
		
		<?php $settings = get_option ('WM_scrollbar_options',$WM_scrollbar_options); ?>
		<?php settings_fields('WM_scrollbar_supper_options');?>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="cursor_color"> Scrollbar Supper color</label></th>
				<td>
					<input id="cursor_color" type="text" name="WM_scrollbar_options[cursor_color]" value="<?php echo stripslashes($settings['cursor_color']); ?>" class="my-color-field"/> <p class="description">Our default color value: #666 , you also change another Scrollbar color and just copy color Code <a href="http://www.w3schools.com/html/html_colors.asp">here</a> beside you can use flat UI color <a href="http://www.flatuicolorpicker.com/">click here </a></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="cursor_width"> Scrollbar supper width</label></th>
				<td>
					<input id="cursor_width" type="text" name="WM_scrollbar_options[cursor_width]" value="<?php echo stripslashes($settings['cursor_width']); ?>"/> <p class="description">Our default Scrollbar width value : 5px, you also can change it but be careful that must have included with px</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="cursor_border_radius"> Scrollbar supper border radius</label></th>
				<td>
					<input id="cursor_border_radius" type="text" name="WM_scrollbar_options[cursor_border_radius]" value="<?php echo stripslashes($settings['cursor_border_radius']); ?>"/> <p class="description">Our default Scrollbar border radius value : 0px, you can increase it but be careful that must have included with px</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="cursor_border"> Scrollbar border Style</label></th>
				<td>
					<input id="cursor_border" type="text" name="WM_scrollbar_options[cursor_border]" value="<?php echo stripslashes($settings['cursor_border']); ?>"/> <p class="description">Our default value : 0px solid #000, Select scrollbar border style here, Border style must be three part. Make sure you have used correct format 1px solid #666 or 2px solid red.</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="scroll_speed"> Scrollbar supper speed</label></th>
				<td>
					<input id="scroll_speed" type="text" name="WM_scrollbar_options[scroll_speed]" value="<?php echo stripslashes($settings['scroll_speed']); ?>"/> <p class="description">Scrollbar speed default value : 60.If you increase scrolling value here, the scrolling speed will slower. On the other hand, if you decrease scrolling value then it will be faster. </p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="auto_hide_mode"> Scrollbar visibility setting</label></th>
				<td>
					<?php foreach ($auto_hide_mode as $activate): ?>
					<input id="<?php echo $activate['value']; ?>" type="radio" name="WM_scrollbar_options[auto_hide_mode]" value="<?php esc_attr_e($activate['value']); ?>" <?php checked ($settings['auto_hide_mode'], $activate['value']); ?>/>
					<label for="<?php echo $activate['value']; ?>"> <?php echo $activate['label']; ?></label><br/>
					<?php endforeach; ?>
				</td>
			</tr>
		</table>
		
		<p class="submit"> <input type="submit" class="button-primary" value="save options" /> </p>
		</form>
		</div>
	<?php
}
function WM_scrollbar_validate_options($input){
	global $WM_scrollbar_options, $auto_hide_mode;
	$settings = get_option ('WM_scrollbar_options', $WM_scrollbar_options);
	
	$input['cursor_color'] =  wp_filter_post_kses( $input ['cursor_color']);
	$input['cursor_width'] =  wp_filter_post_kses( $input ['cursor_width']);
	$input['cursor_border_radius'] =  wp_filter_post_kses( $input ['cursor_border_radius']);
	$input['cursor_border'] =  wp_filter_post_kses( $input ['cursor_border']);
	$input['scroll_speed'] =  wp_filter_post_kses( $input ['scroll_speed']);

	$prev = $settings ['layout_only'];
	if (!array_key_exists ($input ['layout_only'],$auto_hide_mode))
		$input['layout_only'] = $prev;
	
	return $input;	
}
endif;

function WM_scrollbar_supper_active() {?>

<?php global $WM_scrollbar_options; $WM_scrollbar_settings = get_option('WM_scrollbar_options', $WM_scrollbar_options); ?>
	
	<script type="text/javascript">
	jQuery(document).ready(function(){
	  jQuery("html") .niceScroll({
		  cursorcolor : "<?php echo $WM_scrollbar_settings['cursor_color']; ?>",
		  cursorwidth : "<?php echo $WM_scrollbar_settings['cursor_width']; ?>",
		  cursorborderradius : "<?php echo $WM_scrollbar_settings['cursor_border_radius']; ?>",
		  cursorborder : "<?php echo $WM_scrollbar_settings['cursor_border']; ?>",
		  scrollspeed : "<?php echo $WM_scrollbar_settings['scroll_speed']; ?>",
		  autohidemode : <?php echo $WM_scrollbar_settings['auto_hide_mode']; ?>,
		  touchbehavior  : true,
		  bouncescroll  : true,
		  horizrailenabled  : false,
		  
	  });
	});
	</script>
<?php
}
add_action('wp_head', 'WM_scrollbar_supper_active');


?>