<?php
/*
 * Main Section
 */
?>

<?php if ( 'wpcd_field-url' == $field['label_for'] ) : ?>

	<input id="<?php esc_attr_e( 'wpcd_settings[main][field-url]' ); ?>" name="<?php esc_attr_e( 'wpcd_settings[main][field-url]' ); ?>" class="regular-text" value="<?php esc_attr_e( $settings['main']['field-url'] ); ?>" />

<?php elseif ( 'wpcd_field-type' == $field['label_for'] ) : ?>

  <select id="<?php esc_attr_e('wpcd_settings[main][field-type]'); ?>" name="<?php esc_attr_e('wpcd_settings[main][field-type]'); ?>"><option value=""></option>
	   <?php
	   
	   foreach ( array(
			   'fancybox' => 'fancyBox', 
			   'thickbox' => 'thickBox',
			   'prettyphoto' => 'prettyPhoto',
			   'facebox' => 'faceBox')
		     as $key => $val) { 
	   
	   echo "<option value='{$key}' "
	   . (($settings['main']['field-type'] == $key) ? "selected='selected'" : '' ) 
	   . ">{$val}</option>";
	   
	 }
	   ?>
  </select>

<?php endif; ?>
