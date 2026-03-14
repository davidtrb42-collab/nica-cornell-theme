<?php
/**
 * Astra Child — functions.php
 * Nica Cornell Portfolio
 */

// 0. Theme supports
add_action( 'after_setup_theme', function () {
    add_theme_support( 'post-thumbnails' );
} );

// 1. Enqueue parent Astra stylesheet (required by WP child theme spec)
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'astra-parent',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme( 'astra' )->get( 'Version' )
    );

    if ( is_front_page() ) {
        // Remove Astra's stylesheet on the homepage — our template
        // builds the full page from scratch and doesn't need it.
        wp_dequeue_style( 'astra-theme-css' );

        // Homepage stylesheet
        wp_enqueue_style(
            'nc-homepage',
            get_stylesheet_directory_uri() . '/homepage.css',
            [],
            '1.2.8'
        );
    }

    if ( is_page( 'writing' ) ) {
        // Remove Astra's stylesheet — writing page is fully custom.
        wp_dequeue_style( 'astra-theme-css' );

        // Shared design system (variables, nav, cards, footer, tagline portal, etc.)
        wp_enqueue_style(
            'nc-homepage',
            get_stylesheet_directory_uri() . '/homepage.css',
            [],
            '1.2.8'
        );

        // Writing-page-specific overrides
        wp_enqueue_style(
            'nc-writing',
            get_stylesheet_directory_uri() . '/writing.css',
            [ 'nc-homepage' ],
            '1.0.6'
        );
    }

    if ( is_page( 'press' ) ) {
        // Remove Astra's stylesheet — press page is fully custom.
        wp_dequeue_style( 'astra-theme-css' );

        // Shared design system
        wp_enqueue_style(
            'nc-homepage',
            get_stylesheet_directory_uri() . '/homepage.css',
            [],
            '1.2.8'
        );

        // Press-page-specific styles
        wp_enqueue_style(
            'nc-press',
            get_stylesheet_directory_uri() . '/press.css',
            [ 'nc-homepage' ],
            '1.4.0'
        );
    }
}, 20 );

// 2. Google Fonts — on front page and writing page, injected early in <head>
add_action( 'wp_head', function () {
    if ( ! is_front_page() && ! is_page( 'writing' ) && ! is_page( 'press' ) ) {
        return;
    }
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Inter:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap">' . "\n";
}, 2 );

// 3. Register primary nav menu location
register_nav_menus( [
    'primary' => __( 'Primary Navigation', 'astra-child' ),
] );

// 4. On mobile (< 600px) the WP admin bar is not fixed — it scrolls with the
//    page. Our nav has a CSS top offset for it, so once the bar scrolls away a
//    gap opens above the nav. This script tracks the admin bar's live bottom
//    edge and applies it as an inline top value, closing the gap on all pages.
add_action( 'wp_footer', function () {
    if ( ! is_admin_bar_showing() ) {
        return; // no admin bar → no gap to fix
    }
    ?>
    <script>
    (function () {
        'use strict';
        var nav      = document.getElementById('nc-nav');
        var adminBar = document.getElementById('wpadminbar');
        if (!nav || !adminBar) { return; }

        function fixNavTop() {
            if (window.innerWidth < 600) {
                // Admin bar scrolls with page on mobile — track its bottom edge
                nav.style.top = Math.max(0, adminBar.getBoundingClientRect().bottom) + 'px';
            } else {
                // Desktop: CSS rules handle the offset; remove any inline override
                nav.style.removeProperty('top');
            }
        }

        window.addEventListener('scroll', fixNavTop, { passive: true });
        window.addEventListener('resize', fixNavTop, { passive: true });
        fixNavTop();
    }());
    </script>
    <?php
}, 20 );

// 5. Register the 'publisher' ACF field on the publication post type (local field group,
//    no JSON file needed). Falls back silently if ACF is not active.
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }
    acf_add_local_field_group( [
        'key'    => 'group_nc_publication_extra',
        'title'  => 'Publication Extra',
        'fields' => [
            [
                'key'          => 'field_nc_publisher',
                'label'        => 'Publisher',
                'name'         => 'publisher',
                'type'         => 'text',
                'required'     => 0,
                'instructions' => 'Journal, magazine, or press that published this piece.',
            ],
        ],
        'location' => [
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'publication' ] ],
        ],
        'menu_order'            => 10,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
    ] );
} );

// 6. Cloudflare Turnstile for Contact Form 7
//    Client: loads turnstile script on front page (where the form lives).
//    Server: verifies the token before CF7 sends mail; marks as spam if invalid.

add_action( 'wp_enqueue_scripts', function () {
    if ( is_front_page() ) {
        wp_enqueue_script(
            'cf-turnstile',
            'https://challenges.cloudflare.com/turnstile/v0/api.js',
            [],
            null,
            true
        );
    }
}, 20 );

// Inject the Turnstile widget into the CF7 form before the submit button.
add_filter( 'wpcf7_form_elements', function ( $elements ) {
    $widget = '<div class="cf-turnstile" data-sitekey="0x4AAAAAAACq4qpE9syPB_1JX"></div>';
    return preg_replace( '/(<input[^>]+type=["\']submit["\'][^>]*>)/i', $widget . '$1', $elements, 1 );
} );

add_filter( 'wpcf7_spam', function ( $spam ) {
    if ( $spam ) {
        return $spam; // already caught by another check
    }

    $token  = isset( $_POST['cf-turnstile-response'] ) ? sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ) ) : '';
    $secret = defined( 'NC_TURNSTILE_SECRET' ) ? NC_TURNSTILE_SECRET : '';

    if ( empty( $token ) || empty( $secret ) ) {
        return true; // no token → treat as spam
    }

    $response = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        'body' => [
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
        ],
    ] );

    if ( is_wp_error( $response ) ) {
        return true; // network error → block submission
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    return empty( $body['success'] ); // true = spam if verification failed
} );

// 7. Force our custom templates to win against Elementor's template_include hook.
//    Elementor overrides template selection at priority 12; we run at 999.
add_filter( 'template_include', function ( $template ) {
    if ( is_front_page() ) {
        $ours = get_stylesheet_directory() . '/front-page.php';
        if ( file_exists( $ours ) ) {
            return $ours;
        }
    }
    if ( is_page( 'writing' ) ) {
        $ours = get_stylesheet_directory() . '/page-writing.php';
        if ( file_exists( $ours ) ) {
            return $ours;
        }
    }
    if ( is_page( 'press' ) ) {
        $ours = get_stylesheet_directory() . '/page-press.php';
        if ( file_exists( $ours ) ) {
            return $ours;
        }
    }
    return $template;
}, 999 );
