<?php
/**
 * Writing Page Template — Nica Cornell Portfolio
 *
 * Serves /writing/ — a filterable, sortable grid of all publications.
 * Fully custom template; does not use Astra's header/footer wrappers.
 * Mirrors the visual language of front-page.php.
 */

// Security: block direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Query all publication_type taxonomy terms for the filter tabs
$filter_terms = get_terms( [
    'taxonomy'   => 'publication_type',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );

// Query every published publication, alphabetical by title
$all_pubs = get_posts( [
    'post_type'      => 'publication',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
] );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'nc-page nc-writing-page' ); ?>>
<?php wp_body_open(); ?>


<!-- =====================================================================
     NAVIGATION — starts solid (no hero beneath it)
     ===================================================================== -->
<nav class="nc-nav is-scrolled" id="nc-nav" role="navigation" aria-label="Main navigation">

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nc-nav__logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
        <img src="<?php echo esc_url( content_url( 'uploads/2026/02/nica-logo.webp' ) ); ?>"
             alt="<?php bloginfo( 'name' ); ?>"
             class="nc-nav__logo-img"
             width="120"
             height="44">
    </a>

    <ul class="nc-nav__links" id="nc-nav-links">
        <li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>" aria-current="page">All Publications</a></li>
        <li><a href="<?php echo esc_url( home_url( '/press/' ) ); ?>">Press</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#nc-about' ) ); ?>">About</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#nc-contact' ) ); ?>">Contact</a></li>
    </ul>

    <button class="nc-nav__toggle"
            id="nc-nav-toggle"
            aria-expanded="false"
            aria-controls="nc-nav-links"
            aria-label="Open navigation menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

</nav>


<!-- =====================================================================
     PAGE HEADER — cream bg, editorial intro
     ===================================================================== -->
<header class="nc-writing-header" aria-label="Writing page header">
    <div class="nc-container">
        <h1 class="nc-writing-title">All Publications</h1>
    </div>
</header>


<!-- =====================================================================
     CONTROLS BAR — sticky, filter tabs + sort select
     ===================================================================== -->
<div class="nc-writing-controls" id="nc-writing-controls">
    <div class="nc-container">

        <div class="nc-writing-controls__inner">

            <!-- Filter by type -->
            <div class="nc-sort-wrap">
                <label class="nc-sort-label" for="nc-filter-select">Type</label>
                <select class="nc-sort-select" id="nc-filter-select" aria-label="Filter publications by type">
                    <option value="all">All</option>
                    <?php if ( ! is_wp_error( $filter_terms ) && $filter_terms ) : ?>
                        <?php foreach ( $filter_terms as $term ) : ?>
                            <option value="<?php echo esc_attr( $term->name ); ?>">
                                <?php echo esc_html( $term->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Sort -->
            <div class="nc-sort-wrap">
                <label class="nc-sort-label" for="nc-sort-select">Sort</label>
                <select class="nc-sort-select" id="nc-sort-select" aria-label="Sort publications">
                    <option value="title-asc">Name A–Z</option>
                    <option value="title-desc">Name Z–A</option>
                    <option value="year-desc">Year newest</option>
                    <option value="year-asc">Year oldest</option>
                </select>
            </div>

        </div><!-- .nc-writing-controls__inner -->

    </div>
</div><!-- .nc-writing-controls -->


<!-- =====================================================================
     PUBLICATIONS GRID
     ===================================================================== -->
<main class="nc-writing-main" id="nc-writing-main">
    <div class="nc-container">

        <div class="nc-writing-grid" id="nc-writing-grid">

            <?php foreach ( $all_pubs as $pub ) :
                $pub_id      = $pub->ID;
                $pub_title   = $pub->post_title;
                $pub_ext_url = esc_url( get_post_meta( $pub_id, 'publication_url', true ) );

                $year_terms = get_the_terms( $pub_id, 'publication_year' );
                $pub_year   = ( $year_terms && ! is_wp_error( $year_terms ) )
                              ? $year_terms[0]->name
                              : '';

                $section_terms = get_the_terms( $pub_id, 'publication_type' );
                $pub_type      = '';
                if ( $section_terms && ! is_wp_error( $section_terms ) ) {
                    $pub_type = implode( '|', wp_list_pluck( $section_terms, 'name' ) );
                }

                $pub_tagline   = esc_html( get_post_meta( $pub_id, 'tagline', true ) );
                $pub_publisher = esc_html( get_post_meta( $pub_id, 'publisher', true ) );
                $type_names    = ( $section_terms && ! is_wp_error( $section_terms ) )
                                 ? array_map( 'strtolower', wp_list_pluck( $section_terms, 'name' ) )
                                 : [];
                $show_publisher = $pub_publisher && ! in_array( strtolower( $pub_publisher ), $type_names );
                $thumb_id      = get_post_thumbnail_id( $pub_id );
            ?>

            <article class="nc-wcard"
                     data-title="<?php echo esc_attr( strtolower( $pub_title ) ); ?>"
                     data-year="<?php echo esc_attr( $pub_year ); ?>"
                     data-type="<?php echo esc_attr( $pub_type ); ?>">

                <!-- Title -->
                <p class="nc-wcard__title">
                    <?php if ( $pub_ext_url ) : ?>
                        <a href="<?php echo $pub_ext_url; ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html( $pub_title ); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html( $pub_title ); ?>
                    <?php endif; ?>
                </p>
                <br>
                <!-- Type · Year meta line -->
                <p class="nc-wcard__meta">
                    <?php if ( $pub_type ) : ?>
                        <span class="nc-wcard__type">
                            <?php echo esc_html( str_replace( '|', ' · ', $pub_type ) ); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ( $pub_type && $pub_year ) : ?>
                        <span class="nc-wcard__sep" aria-hidden="true"> | </span>
                    <?php endif; ?>
                    <?php if ( $pub_year ) : ?>
                        <span class="nc-wcard__year"><?php echo esc_html( $pub_year ); ?></span>
                    <?php endif; ?>
                </p>
                <?php if ( $show_publisher ) : ?>
                    <p class="nc-wcard__publisher"><?php echo $pub_publisher; ?></p>
                <?php else : ?>
                    <br>
                <?php endif; ?>
                <!-- Cover image -->
                <div class="nc-wcard__img-wrap">
                    <?php if ( $thumb_id ) : ?>
                        <?php echo wp_get_attachment_image( $thumb_id, 'medium', false, [
                            'alt'     => esc_attr( $pub_title ),
                            'loading' => 'lazy',
                        ] ); ?>
                    <?php else : ?>
                        <div class="nc-wcard__img-placeholder" aria-hidden="true"></div>
                    <?php endif; ?>
                </div>

                <!-- Tagline button (reuses homepage portal pattern) -->
                <?php if ( $pub_tagline ) : ?>
                    <div class="nc-tagline-reveal">
                        <button class="nc-tagline-btn" type="button">View tagline</button>
                        <div class="nc-tagline-box" role="tooltip"><?php echo $pub_tagline; ?></div>
                    </div>
                <?php endif; ?>

            </article>

            <?php endforeach; ?>

        </div><!-- .nc-writing-grid -->

        <p class="nc-writing-empty" id="nc-writing-empty" hidden aria-live="polite">
            No publications found for this filter.
        </p>

    </div>
</main>


<!-- =====================================================================
     FOOTER — identical to front-page.php
     ===================================================================== -->
<footer class="nc-footer" role="contentinfo">
    <div class="nc-container">

        <p class="nc-footer__copy">
            &copy; <?php echo esc_html( date( 'Y' ) ); ?> Nica Cornell
        </p>

        <ul class="nc-footer__links" aria-label="Footer navigation">
            <li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">All Publications</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#nc-about' ) ); ?>">About</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#nc-contact' ) ); ?>">Contact</a></li>
        </ul>

    </div>
</footer>


<?php wp_footer(); ?>

<script>
(function () {
    'use strict';

    /* ------------------------------------------------------------------
       Sticky nav — threshold 10px so it's effectively solid from load
       (no hero beneath the nav on this page)
       ------------------------------------------------------------------ */
    var nav      = document.getElementById('nc-nav');
    var controls = document.getElementById('nc-writing-controls');
    var THRESHOLD = 10;

    function updateNav() {
        nav.classList.toggle('is-scrolled', window.scrollY > THRESHOLD);
    }

    /* Seal the gap between the fixed nav and the sticky controls bar by
       measuring the nav's actual rendered height and applying it as the
       controls' top offset. Runs on load and on resize to stay accurate. */
    function sealControlsTop() {
        if (controls) {
            controls.style.top = nav.getBoundingClientRect().bottom + 'px';
        }
    }

    window.addEventListener('scroll', function () {
        updateNav();
        // On mobile the admin bar scrolls away, changing the nav's top position.
        // Re-seal the controls bar so it stays flush with the nav on every frame.
        if (window.innerWidth < 600) { sealControlsTop(); }
    }, { passive: true });
    window.addEventListener('resize', sealControlsTop, { passive: true });
    updateNav();
    sealControlsTop();

    /* ------------------------------------------------------------------
       Mobile menu toggle — identical to front-page.php
       ------------------------------------------------------------------ */
    var toggle = document.getElementById('nc-nav-toggle');

    if (toggle) {
        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            toggle.classList.toggle('is-open', isOpen);
            toggle.setAttribute('aria-expanded', String(isOpen));
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('is-open')) {
                nav.classList.remove('is-open');
                toggle.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
                toggle.focus();
            }
        });
    }

    /* ------------------------------------------------------------------
       Tagline portal — renders tooltip as position:fixed on <body>
       so it escapes any overflow clipping. Identical to front-page.php.
       ------------------------------------------------------------------ */
    (function () {
        var portal = document.createElement('div');
        portal.className = 'nc-tagline-portal';
        document.body.appendChild(portal);

        document.querySelectorAll('.nc-tagline-reveal').forEach(function (reveal) {
            var btn = reveal.querySelector('.nc-tagline-btn');
            var box = reveal.querySelector('.nc-tagline-box');
            if (!btn || !box) { return; }

            reveal.addEventListener('mouseenter', function () {
                var r = btn.getBoundingClientRect();
                portal.textContent = box.textContent;
                portal.style.top     = (r.bottom + 8) + 'px';
                portal.style.left    = r.left + 'px';
                portal.style.opacity = '1';
            });

            reveal.addEventListener('mouseleave', function () {
                portal.style.opacity = '0';
            });
        });
    }());

    /* ------------------------------------------------------------------
       Filter + sort engine
       ------------------------------------------------------------------ */
    var grid    = document.getElementById('nc-writing-grid');
    var emptyEl = document.getElementById('nc-writing-empty');
    var sortSelect = document.getElementById('nc-sort-select');

    var activeFilter = 'all';
    var activeSort   = 'title-asc';

    // Cache all card elements once
    var cards = Array.from(grid.querySelectorAll('.nc-wcard'));

    function render() {
        // Determine which cards match the active filter
        var visible = [];
        cards.forEach(function (card) {
            var typeAttr = card.dataset.type || '';
            var types    = typeAttr ? typeAttr.split('|') : [];
            var matches  = activeFilter === 'all' || types.indexOf(activeFilter) !== -1;
            card.hidden  = !matches;
            if (matches) { visible.push(card); }
        });

        // Sort the visible subset
        visible.sort(function (a, b) {
            switch (activeSort) {
                case 'title-desc':
                    return (b.dataset.title || '').localeCompare(a.dataset.title || '');
                case 'year-desc':
                    return parseInt(b.dataset.year || '0', 10) - parseInt(a.dataset.year || '0', 10);
                case 'year-asc':
                    return parseInt(a.dataset.year || '0', 10) - parseInt(b.dataset.year || '0', 10);
                default: // title-asc
                    return (a.dataset.title || '').localeCompare(b.dataset.title || '');
            }
        });

        // Re-append in sorted order (hidden cards stay in DOM but invisible)
        visible.forEach(function (card) { grid.appendChild(card); });

        // Show empty state when nothing matches
        emptyEl.hidden = visible.length > 0;
    }

    // Filter select change
    var filterSelect = document.getElementById('nc-filter-select');
    if (filterSelect) {
        filterSelect.addEventListener('change', function () {
            activeFilter = filterSelect.value;
            render();
        });
    }

    // Sort select change
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            activeSort = sortSelect.value;
            render();
        });
    }

}());
</script>

</body>
</html>
