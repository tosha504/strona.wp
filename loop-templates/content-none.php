<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Thenewlook
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>
<section class="no-results not-found">
  <header class="page-header">
    <h1 class="page-title"><?php esc_html_e('Nothing Found', 'thenewlook'); ?></h1>
  </header><!-- .page-header -->
  <div class="page-content">
    <?php
    $kses = ['a' => ['href' => []]];
    printf(
      /* translators: 1: Link to WP admin new post page. */
      '<p>' . wp_kses(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'thenewlook'), $kses) . '</p>',
      esc_url(admin_url('post-new.php'))
    ); ?>
  </div><!-- .page-content -->
</section><!-- .no-results -->