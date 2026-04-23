<?php
/**
 * The right sidebar containing the main widget area
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
if(get_field('display_sidebar') == 0) return;
?>

<div class="col-lg-3 widget-area" id="right-sidebar">
	<div class="singlePost__sidebar">
		<?php dynamic_sidebar( 'right-sidebar' ); ?>
	</div>
</div><!-- #right-sidebar -->
