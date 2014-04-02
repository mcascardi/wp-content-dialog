<?php
/*
 * Main Section
 */
?>

<?php if ( 'wpcd_url' == $field['label_for'] ) : ?>

	<input id="<?php esc_attr_e( 'wpcd_settings[main][url]' ); ?>" name="<?php esc_attr_e( 'wpcd_settings[main][url]' ); ?>" class="regular-text" value="<?php esc_attr_e( $settings['main']['url'] ); ?>" />


<?php elseif ( 'wpcd_cta' == $field['label_for'] ) : ?>

	<input id="<?php esc_attr_e( 'wpcd_settings[main][cta]' ); ?>" name="<?php esc_attr_e( 'wpcd_settings[main][cta]' ); ?>" class="regular-text" value="<?php esc_attr_e( $settings['main']['cta'] ); ?>" />

<?php elseif ( 'wpcd_alt' == $field['label_for'] ) : ?>

	<input id="<?php esc_attr_e( 'wpcd_settings[main][alt]' ); ?>" name="<?php esc_attr_e( 'wpcd_settings[main][alt]' ); ?>" class="regular-text" value="<?php esc_attr_e( $settings['main']['alt'] ); ?>" />

<?php elseif ( 'wpcd_width' == $field['label_for'] ) : ?>

	<input id="<?php esc_attr_e( 'wpcd_settings[main][width]' ); ?>" name="<?php esc_attr_e( 'wpcd_settings[main][width]' ); ?>"  value="<?php esc_attr_e( $settings['main']['width'] ); ?>" />

<?php elseif ( 'wpcd_height' == $field['label_for'] ) : ?>

	<input id="<?php esc_attr_e( 'wpcd_settings[main][height]' ); ?>" name="<?php esc_attr_e( 'wpcd_settings[main][height]' ); ?>"  value="<?php esc_attr_e( $settings['main']['height'] ); ?>" />

<?php elseif ( 'wpcd_overlay' == $field['label_for'] ) : ?>

  <select id="<?php esc_attr_e('wpcd_settings[main][overlay]'); ?>" name="<?php esc_attr_e('wpcd_settings[main][overlay]'); ?>"><option value=""></option>
	   <?php
	   
	   foreach ( array(
			   'fancybox' => 'FancyBox', 
			   'thickbox' => 'ThickBox',
			   'prettyphoto' => 'PrettyPhoto')
		     as $key => $val) { 
	   
	   echo "<option value='{$key}' "
	   . (
	      ($settings['main']['overlay'] == $key) ?
	      "selected='selected'" : '' 
	      ) 
	   . ">{$val}</option>";
	   
	 }
	   ?>
  </select>

<?php endif; ?>
