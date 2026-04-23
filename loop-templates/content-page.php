<?php
/**
 * Partial template for content in page.php
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<div class="entry-content <?php if(!is_front_page()) echo 'noHome'; ?>">
		<?php
		the_content();
		understrap_link_pages();
		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
