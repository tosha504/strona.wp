<?php
declare(strict_types=1);
namespace Theme;
final class Assets
{
  // Najprościej: domyślnie OFF. Włączysz w wp-config.php gdy chcesz HMR.
  private const DEV_SERVER = 'http://localhost:5173';
  public static function init(): void
  {
    add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue'], 20);
  }
  public static function enqueue(): void
  {
    if (wp_get_environment_type() === 'local') {
      self::enqueue_dev();
      return;
    }
    self::enqueue_build();
  }

  private static function enqueue_dev(): void
  {
    // Vite dev assets muszą być type="module"
    add_filter('script_loader_tag', function (string $tag, string $handle, string $src): string {
      if (in_array($handle, ['theme-vite-client', 'theme-vite-js'], true)) {
        return '<script type="module" src="' . esc_url($src) . '"></script>';
      }
      return $tag;
    }, 10, 3);
    // klient HMR
    wp_enqueue_script('theme-vite-client', self::DEV_SERVER . '/@vite/client', [], null, false);
    // entry JS (w nim importujesz SCSS)
    wp_enqueue_script('theme-vite-js', self::DEV_SERVER . '/src/js/theme.js', [], null, true);
    // ❌ usuń to:
    // wp_enqueue_script('theme-vite-scss', self::DEV_SERVER . '/src/sass/theme.scss', [], null, true);
  }
  private static function enqueue_build(): void
  {
    $dir           = get_stylesheet_directory();
    $uri           = get_stylesheet_directory_uri();
    $manifest_path = $dir . '/assets/dist/.vite/manifest.json';
    if (!file_exists($manifest_path)) {
      return; // brak builda
    }
    $manifest = json_decode((string) file_get_contents($manifest_path), true);
    if (!is_array($manifest))
      return;
    self::enqueue_manifest_js($manifest, 'src/js/theme.js', 'theme-js', $uri);
    self::enqueue_manifest_css($manifest, 'src/sass/theme.scss', 'theme-css', $uri);
  }
  private static function enqueue_manifest_js(array $manifest, string $key, string $handle, string $uri): void
  {
    if (empty($manifest[$key]['file']))
      return;
    wp_enqueue_script($handle, $uri . '/assets/dist/' . $manifest[$key]['file'], [], null, true);
    // jeśli JS entry ma powiązane CSS, doładuj je też
    if (!empty($manifest[$key]['css']) && is_array($manifest[$key]['css'])) {
      foreach ($manifest[$key]['css'] as $i => $css_file) {
        wp_enqueue_style($handle . '-css-' . $i, $uri . '/assets/dist/' . $css_file, [], null);
      }
    }
  }
  private static function enqueue_manifest_css(array $manifest, string $key, string $handle, string $uri): void
  {
    if (empty($manifest[$key]['file']))
      return;
    wp_enqueue_style($handle, $uri . '/assets/dist/' . $manifest[$key]['file'], [], null);
  }
}
