<?php

declare(strict_types=1);

/**
 * Mega Menu Manager
 *
 * Author: thenewlook
 * Author URI: https://thenewlook.eu
 */

if (!defined('ABSPATH')) {
    exit;
}

// if (class_exists('TNL_Mega_Menu_Manager')) {
//     return;
// }

final class TNL_Mega_Menu_Manager
{
    private const CPT = 'megamenu';

    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_filter('wp_nav_menu_objects', [$this, 'filter_menu_items'], 20, 2);
        add_filter('nav_menu_link_attributes', [$this, 'filter_link_attributes'], 20, 4);
        add_filter('walker_nav_menu_start_el', [$this, 'filter_mobile_mega_trigger_markup'], 15, 4);
        add_filter('walker_nav_menu_start_el', [$this, 'append_megamenu_markup'], 20, 4);
    }

    public function register_post_type(): void
    {
        $labels = [
            'name'               => __('Mega menu', 'thenewlook'),
            'singular_name'      => __('Mega menu', 'thenewlook'),
            'menu_name'          => __('Mega menu', 'thenewlook'),
            'name_admin_bar'     => __('Mega menu', 'thenewlook'),
            'add_new'            => __('Dodaj mega menu', 'thenewlook'),
            'add_new_item'       => __('Dodaj nowe mega menu', 'thenewlook'),
            'edit_item'          => __('Edytuj mega menu', 'thenewlook'),
            'new_item'           => __('Nowe mega menu', 'thenewlook'),
            'view_item'          => __('Podgląd mega menu', 'thenewlook'),
            'search_items'       => __('Szukaj mega menu', 'thenewlook'),
            'not_found'          => __('Nie znaleziono mega menu', 'thenewlook'),
            'not_found_in_trash' => __('Brak mega menu w koszu', 'thenewlook'),
        ];

        $args = [
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => false,
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_icon'           => 'dashicons-screenoptions',
            'supports'            => ['title'],
            'rewrite'             => false,
        ];

        register_post_type(self::CPT, $args);
    }

    public function filter_menu_items(array $items, stdClass $args): array
    {
        foreach ($items as $item) {
            $mega_menu_id = $this->get_mega_menu_id_for_item($item);

            if (!$mega_menu_id) {
                continue;
            }

            $item->classes[] = 'menu-item--mega';
            $item->classes[] = 'menu-item--mega-' . $mega_menu_id;

            if (($args->theme_location ?? '') === 'mobile') {
                $item->classes[] = 'menu-item--mega-mobile';
            }
        }

        return $items;
    }

    public function filter_link_attributes(array $atts, WP_Post $item, stdClass $args, int $depth): array
    {
        if ($depth !== 0) {
            return $atts;
        }

        $mega_menu_id = $this->get_mega_menu_id_for_item($item);

        if (!$mega_menu_id) {
            return $atts;
        }

        $theme_location = $args->theme_location ?? '';
        $existing_class = $atts['class'] ?? '';

        if ($theme_location === 'primary') {
            $atts['class']           = trim($existing_class . ' js-mega-trigger');
            $atts['aria-haspopup']   = 'true';
            $atts['aria-expanded']   = 'false';
            $atts['data-mega-panel'] = 'aw-mega-panel-' . $item->ID;
        }

        return $atts;
    }
    public function filter_mobile_mega_trigger_markup(string $item_output, WP_Post $item, int $depth, stdClass $args): string
    {
        if ($depth !== 0) {
            return $item_output;
        }

        if (($args->theme_location ?? '') !== 'mobile') {
            return $item_output;
        }

        $mega_menu_id = $this->get_mega_menu_id_for_item($item);

        if (!$mega_menu_id) {
            return $item_output;
        }

        $panel_id = 'aw-mega-mobile-panel-' . $item->ID;

        return sprintf(
            '<button type="button" class="fixedMenu__link js-mega-mobile-open" data-mega-panel="%1$s" aria-haspopup="true" aria-expanded="false" aria-controls="%1$s"><span class="fixedMenu__link-label">%2$s</span><span class="fixedMenu__arrow" aria-hidden="true"></span></button>',
            esc_attr($panel_id),
            esc_html($item->title)
        );
    }
    public function append_megamenu_markup(string $item_output, WP_Post $item, int $depth, stdClass $args): string
    {
        if ($depth !== 0) {
            return $item_output;
        }

        $mega_menu_id = $this->get_mega_menu_id_for_item($item);

        if (!$mega_menu_id) {
            return $item_output;
        }

        $theme_location = $args->theme_location ?? '';

        if ($theme_location === 'primary') {
            $item_output .= $this->render_desktop_panel($item, $mega_menu_id);
        }

        if ($theme_location === 'mobile') {
            $item_output .= $this->render_mobile_panel($item, $mega_menu_id);
        }

        return $item_output;
    }

    private function get_mega_menu_id_for_item(WP_Post $item): int
    {
        $is_enabled = (bool) get_field('enable_mega_menu', $item);

        if (!$is_enabled) {
            return 0;
        }

        $mega_menu_reference = get_field('mega_menu_reference', $item);

        if (empty($mega_menu_reference)) {
            return 0;
        }

        if (is_numeric($mega_menu_reference)) {
            return (int) $mega_menu_reference;
        }

        if (is_object($mega_menu_reference) && isset($mega_menu_reference->ID)) {
            return (int) $mega_menu_reference->ID;
        }

        return 0;
    }

    private function get_mobile_panel_title(int $mega_menu_id, WP_Post $item): string
    {
        $override = trim((string) get_field('mobile_panel_title', $mega_menu_id));

        if ($override !== '') {
            return $override;
        }

        return trim((string) $item->title);
    }

    private function render_desktop_panel(WP_Post $item, int $mega_menu_id): string
    {
        $panel_id = 'aw-mega-panel-' . $item->ID;

        ob_start();
        ?>
        <div id="<?php echo esc_attr($panel_id); ?>" class="awMegaMenu" aria-hidden="true">
            <div class="awMegaMenu__panel">
                <?php echo $this->render_mega_menu_content($mega_menu_id, false); ?>
            </div>
        </div>
        <?php

        return (string) ob_get_clean();
    }



    private function render_mobile_panel(WP_Post $item, int $mega_menu_id): string
    {
        $panel_id = 'aw-mega-mobile-panel-' . $item->ID;
        $title    = $this->get_mobile_panel_title($mega_menu_id, $item);

        ob_start();
        ?>
        <div id="<?php echo esc_attr($panel_id); ?>" class="awMegaMenuMobile" aria-hidden="true" tabindex="-1">
            <div class="awMegaMenuMobile__header">
                <button type="button" class="awMegaMenuMobile__back js-mega-mobile-back">
                    <span class="awMegaMenuMobile__backIcon" aria-hidden="true"></span>
                    <span><?php esc_html_e('Wróć', 'thenewlook'); ?></span>
                </button>

                <div class="awMegaMenuMobile__title"><?php echo esc_html($title); ?></div>
            </div>

            <div class="awMegaMenuMobile__body">
                <?php echo $this->render_mega_menu_content($mega_menu_id, true); ?>
            </div>
        </div>
        <?php

        return (string) ob_get_clean();
    }

    private function render_mega_menu_content(int $mega_menu_id, bool $is_mobile): string
    {
        $columns = get_field('mm_columns', $mega_menu_id);

        if (empty($columns) || !is_array($columns)) {
            return '';
        }

        ob_start();
        ?>
        <div class="awMegaMenu__grid <?php echo $is_mobile ? 'awMegaMenu__grid--mobile' : ''; ?>">
            <?php foreach ($columns as $column): ?>
                <?php
                $layout = $column['acf_fc_layout'] ?? '';
                $span   = $this->sanitize_column_span((string) ($column['column_span'] ?? '4'));

                switch ($layout) {
                    case 'links_list':
                        $this->render_links_list_column($column, $span);
                        break;

                    case 'stacked_cards':
                        $this->render_stacked_cards_column($column, $span);
                        break;

                    case 'feature_card':
                        $this->render_feature_card_column($column, $span);
                        break;
                }
                ?>
            <?php endforeach; ?>
        </div>
        <?php

        return (string) ob_get_clean();
    }

    private function sanitize_column_span(string $span): string
    {
        $allowed = ['3', '4', '5', '6', '7', '8', '12'];

        return in_array($span, $allowed, true) ? $span : '4';
    }

    private function render_links_list_column(array $column, string $span): void
    {
        $heading      = $column['heading'] ?? '';
        $heading_icon = (int) ($column['heading_icon'] ?? 0);
        $items        = $column['links'] ?? [];
        ?>
        <section class="awMegaMenu__col awMegaMenu__col--span-<?php echo esc_attr($span); ?> awMegaMenu__col--links">
            <?php if (!empty($heading) || $heading_icon > 0): ?>
                <div class="awMegaMenu__heading">
                    <?php if ($heading_icon > 0): ?>
                        <span class="awMegaMenu__headingIcon" aria-hidden="true">
                            <?php
                            echo wp_get_attachment_image(
                                $heading_icon,
                                'thumbnail',
                                false,
                                [
                                    'class'   => 'awMegaMenu__headingIconImage',
                                    'loading' => 'lazy',
                                    'alt'     => '',
                                ]
                            );
                            ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <span class="awMegaMenu__headingText"><?php echo esc_html($heading); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($items) && is_array($items)): ?>
                <ul class="awMegaMenuList" role="list">
                    <?php foreach ($items as $menu_item): ?>
                        <?php
                        $link        = $menu_item['link'] ?? null;
                        $title       = $menu_item['title'] ?? '';
                        $description = $menu_item['description'] ?? '';
                        $icon_id     = (int) ($menu_item['icon'] ?? 0);

                        if (empty($link) || empty($link['url']) || empty($title)) {
                            continue;
                        }

                        $link_url    = $link['url'];
                        $link_target = !empty($link['target']) ? $link['target'] : '_self';
                        $link_rel    = $link_target === '_blank' ? 'noopener noreferrer' : '';
                        ?>
                        <li class="awMegaMenuList__item">
                            <a class="awMegaMenuList__link" href="<?php echo esc_url($link_url); ?>"
                                target="<?php echo esc_attr($link_target); ?>" rel="<?php echo esc_attr($link_rel); ?>">
                                <?php if ($icon_id > 0): ?>
                                    <span class="awMegaMenuList__icon">
                                        <?php
                                        echo wp_get_attachment_image(
                                            $icon_id,
                                            'thumbnail',
                                            false,
                                            [
                                                'class'   => 'awMegaMenuList__iconImage',
                                                'loading' => 'lazy',
                                                'alt'     => '',
                                            ]
                                        );
                                        ?>
                                    </span>
                                <?php endif; ?>

                                <span class="awMegaMenuList__content">
                                    <span class="awMegaMenuList__title"><?php echo esc_html($title); ?></span>

                                    <?php if (!empty($description)): ?>
                                        <span class="awMegaMenuList__description"><?php echo nl2br(esc_html($description)); ?></span>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
        <?php
    }

    private function render_stacked_cards_column(array $column, string $span): void
    {
        $cards = $column['cards'] ?? [];

        if (empty($cards) || !is_array($cards)) {
            return;
        }
        ?>
        <section class="awMegaMenu__col awMegaMenu__col--span-<?php echo esc_attr($span); ?> awMegaMenu__col--cards">
            <div class="awMegaMenuCards">
                <?php foreach ($cards as $card): ?>
                    <?php
                    $theme = !empty($card['theme']) && $card['theme'] === 'dark' ? 'dark' : 'light';
                    $icon  = (int) ($card['icon'] ?? 0);
                    $title = $card['title'] ?? '';
                    $text  = $card['text'] ?? '';
                    $link  = $card['link'] ?? null;
                    $image = (int) ($card['image'] ?? 0);

                    $link_url    = !empty($link['url']) ? $link['url'] : '';
                    $link_title  = !empty($link['title']) ? $link['title'] : '';
                    $link_target = !empty($link['target']) ? $link['target'] : '_self';
                    $link_rel    = $link_target === '_blank' ? 'noopener noreferrer' : '';
                    ?>
                    <article class="awMegaCard awMegaCard--<?php echo esc_attr($theme); ?>">
                        <a class="awMegaCard__inner" href="<?php echo esc_url($link_url); ?>"
                            target="<?php echo esc_attr($link_target); ?>" rel="<?php echo esc_attr($link_rel); ?>">
                            <?php if ($icon > 0): ?>
                                <div class="awMegaCard__icon" aria-hidden="true">
                                    <?php
                                    echo wp_get_attachment_image(
                                        $icon,
                                        'thumbnail',
                                        false,
                                        [
                                            'class'   => 'awMegaCard__iconImage',
                                            'loading' => 'lazy',
                                            'alt'     => '',
                                        ]
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($title)): ?>
                                <p class="awMegaCard__title"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($text)): ?>
                                <div class="awMegaCard__text"><?php echo wp_kses_post($text); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($link_url) && !empty($link_title)): ?>
                                <span class="awMegaCard__link">
                                    <?php echo esc_html($link_title); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($image > 0): ?>
                                <div class="awMegaCard__image">
                                    <?php
                                    echo wp_get_attachment_image(
                                        $image,
                                        'medium_large',
                                        false,
                                        [
                                            'class'   => 'awMegaCard__imageTag',
                                            'loading' => 'lazy',
                                        ]
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }

    private function render_feature_card_column(array $column, string $span): void
    {
        $theme      = !empty($column['theme']) && $column['theme'] === 'dark' ? 'dark' : 'light';
        $eyebrow    = $column['eyebrow'] ?? '';
        $title      = $column['title'] ?? '';
        $text       = $column['text'] ?? '';
        $link       = $column['link'] ?? null;
        $image      = (int) ($column['image'] ?? 0);
        $badge_text = $column['badge_text'] ?? '';
        ?>
        <section class="awMegaMenu__col awMegaMenu__col--span-<?php echo esc_attr($span); ?> awMegaMenu__col--feature">
            <a href="<?php echo esc_url($link['url']); ?>"
                target="<?php echo esc_attr(!empty($link['target']) ? $link['target'] : '_self'); ?>"
                rel="<?php echo esc_attr((!empty($link['target']) && $link['target'] === '_blank') ? 'noopener noreferrer' : ''); ?>">
                <article class="awMegaFeature awMegaFeature--<?php echo esc_attr($theme); ?>">
                    <div class="awMegaFeature__content" href="<?php echo esc_url($link['url']); ?>"
                        target="<?php echo esc_attr(!empty($link['target']) ? $link['target'] : '_self'); ?>"
                        rel="<?php echo esc_attr((!empty($link['target']) && $link['target'] === '_blank') ? 'noopener noreferrer' : ''); ?>">
                        <?php if (!empty($eyebrow)): ?>
                            <div class="awMegaFeature__eyebrow"><?php echo esc_html($eyebrow); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($title)): ?>
                            <p class="awMegaFeature__title"><?php echo esc_html($title); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($text)): ?>
                            <div class="awMegaFeature__text"><?php echo nl2br(esc_html($text)); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($link['url']) && !empty($link['title'])): ?>
                            <span class="awMegaFeature__link">
                                <?php echo esc_html($link['title']); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($image > 0): ?>
                        <div class="awMegaFeature__media">
                            <?php
                            echo wp_get_attachment_image(
                                $image,
                                'large',
                                false,
                                [
                                    'class'   => 'awMegaFeature__image',
                                    'loading' => 'lazy',
                                ]
                            );
                            ?>

                            <?php if (!empty($badge_text)): ?>
                                <span class="awMegaFeature__badge"><?php echo esc_html($badge_text); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </a>
        </section>
        <?php
    }
}

new TNL_Mega_Menu_Manager();
