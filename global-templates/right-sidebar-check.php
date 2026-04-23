<?php
/**
 * Right sidebar check
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

</div><!-- #closing the primary container from /global-templates/left-sidebar-check.php -->

<?php
if(is_singular('post')){
	get_template_part( 'sidebar-templates/sidebar', 'right' );
}

