<h3 data-id="<?php echo $key ?>"><span class="dashicons dashicons-arrow-right"></span> [<?php echo $key ?>]</h3>

<div data-id="<?php echo $key ?>" class="shortcode_info hidden">
	<?php echo $shortcode['plugin'] ?>

	<h4><span class="dashicons dashicons-arrow-down"></span> Attributes</h4>

	<?php if ( is_array( $shortcode['out'] ) ) { ?>

	<dl class="value">
		<?php foreach( $shortcode['out'] as $out_key => $out_value ) { ?>
			<dt><?php echo $out_key ?></dt>
			<dd><?php echo ( $out_value ) ? $out_value : 'null' ?></dd>
		<?php } ?>
	</dl>

	<?php } else { ?>

	<p class="value">Couldn't get the attributes for this shortcode automatically.<br/>See the code at <code><?php echo $shortcode['file_name'] ?></code></p>

	<?php } ?>

	<h4><span class="dashicons dashicons-arrow-down"></span> Running Example</h4>
	<div class="running_example">
	<?php echo do_shortcode( '[' . $key . ']' ); ?>
	</div>
</div>