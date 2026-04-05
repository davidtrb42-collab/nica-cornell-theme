<?php
/**
 * Press Page Template — Nica Cornell Portfolio
 *
 * Serves /press/ — static list of press coverage and presentations,
 * split into two sections. No CPT or database queries needed.
 * Fully custom template; mirrors the visual language of page-writing.php.
 */

// Security: block direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -------------------------------------------------------------------------
// Static data — edit here to add / update entries
// Sections: $presentations (talks, panels, awards) and $press (media coverage)
// Order: newest first within each section
// -------------------------------------------------------------------------

$presentations = [
    [
        'year'  => '2025',
        'type'  => 'Award',
        'title' => 'Shortlisted for the Plymouth Laureate of Words',
        'venue' => 'Literature Works',
        'url'   => 'https://literatureworks.org.uk/plow-2025-vote/',
    ],
    [
        'year'  => '2021',
        'type'  => 'Talk',
        'title' => "\u{2018}Whose Body Is It Anyway?\u{2019} \u{2013} Disguising a Disabled Self",
        'venue' => 'Sartorial Society Series, Season 4 Week 5',
        'url'   => '',
    ],
    [
        'year'  => '2021',
        'type'  => 'Talk',
        'title' => 'Poetry is Feeling & Feeling is Poetry: On Trauma-Mapping and Healing Through Writing',
        'venue' => '20.35 Africa Conversations Series, with Lillian Akampurira',
        'url'   => 'https://2035africa.org/conversation/poetry-is-feeling-and-feeling-is-poetry-on-trauma-mapping-and-healing-through-poetry-by-lillian-akampurira-and-nica-cornell/',
    ],
    [
        'year'  => '2014',
        'type'  => 'Talk',
        'title' => "Born Free \u{2013} Nica Cornell \u{2013} Nothing but the Truth",
        'venue' => 'RUTV Journalism, Rhodes University',
        'url'   => 'https://www.youtube.com/watch?v=MnSzHJv0kYQ',
    ],
];

$press = [
    [
        'year'  => '2020',
        'type'  => 'Press',
        'title' => "A Doctor Displaced: Nkosazana Dlamini-Zuma\u{2019}s Time in Exile (1976\u{2013}1990)",
        'venue' => 'Mail & Guardian, Centre for Gender & Women Studies',
        'url'   => 'https://mg.co.za/special-reports/2020-11-30-centre-for-women-gender-studies/',
    ],
    [
        'year'  => '2020',
        'type'  => 'Press',
        'title' => 'Meet the Poet',
        'venue' => 'Best New African Poets, Facebook',
        'url'   => 'https://www.facebook.com/BestNewAfricanPoets2015/photos/a.1517235938408616/1938265609638978/',
    ],
    [
        'year'  => '2020',
        'type'  => 'Press',
        'title' => 'The Poetry of Unending Therapy',
        'venue' => 'Africa in Dialogue, interview by Ugochukwu Damian Okpara',
        'url'   => 'http://africaindialogue.com/2019/12/23/the-poetry-of-unending-therapy-a-dialogue-with-nica-cornell/',
    ],
    [
        'year'  => '2020',
        'type'  => 'Press',
        'title' => 'A Model of Sustainable Fashion',
        'venue' => 'Ealing News Extra',
        'url'   => 'https://ealingnewsextra.co.uk/features/fashion/',
    ],
    [
        'year'  => '2008',
        'type'  => 'Press',
        'title' => 'State of the Teen Nation',
        'venue' => 'SA Report, Sandy Coffey',
        'url'   => 'https://sandycoffey.com/images/publishedWork/State%20of%20the%20(Teen)%20Nation.pdf',
    ],
    [
        'year'  => '2007',
        'type'  => 'Press',
        'title' => 'Brand SA ads return to the box',
        'venue' => 'Bizcommunity',
        'url'   => 'https://www.bizcommunity.com/Article/196/12/16933.html',
    ],
];

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preload" as="image" href="<?php echo esc_url( content_url( 'uploads/2026/02/nica-logo.webp' ) ); ?>" type="image/webp">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'nc-page nc-press-page' ); ?>>
<?php wp_body_open(); ?>



<nav class="nc-nav is-scrolled" id="nc-nav" role="navigation" aria-label="Main navigation">

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nc-nav__logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
        <img src="<?php echo esc_url( content_url( 'uploads/2026/02/nica-logo.webp' ) ); ?>"
             alt="<?php bloginfo( 'name' ); ?>"
             class="nc-nav__logo-img"
             width="120"
             height="44">
    </a>

    <ul class="nc-nav__links" id="nc-nav-links">
        <li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">All Publications</a></li>
        <li><a href="<?php echo esc_url( home_url( '/press/' ) ); ?>" aria-current="page">Press</a></li>
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



<header class="nc-press-header" aria-label="Press page header">
    <div class="nc-container">
        <h1 class="nc-press-title">Press &amp; Presentations</h1>
    </div>
</header>



<div class="nc-press-controls" id="nc-press-controls">
    <div class="nc-container">
        <div class="nc-press-controls__inner">

            <div class="nc-sort-wrap">
                <label class="nc-sort-label" for="nc-press-filter">Type</label>
                <select class="nc-sort-select" id="nc-press-filter" aria-label="Filter by type">
                    <option value="all">All</option>
                    <option value="Award">Award</option>
                    <option value="Talk">Talk</option>
                    <option value="Press">Press</option>
                </select>
            </div>

        </div>
    </div>
</div>



<main class="nc-press-main" id="nc-press-main">
    <div class="nc-container">

        <?php
        // Helper: group an array of items by year, newest first
        function nc_group_by_year( $items ) {
            $grouped = [];
            foreach ( $items as $item ) {
                $grouped[ $item['year'] ][] = $item;
            }
            krsort( $grouped );
            return $grouped;
        }
        ?>

        
        <section class="nc-press-section" aria-label="Presentations and Awards">

            <?php foreach ( nc_group_by_year( $presentations ) as $year => $entries ) : ?>
            <div class="nc-year-group">

                <div class="nc-year-group__anchor" aria-hidden="true">
                    <span class="nc-year-group__year"><?php echo esc_html( $year ); ?></span>
                </div>

                <ul class="nc-year-group__entries" role="list">
                    <?php foreach ( $entries as $item ) : ?>
                    <li class="nc-entry" data-type="<?php echo esc_attr( $item['type'] ); ?>">
                        <p class="nc-entry__type"><?php echo esc_html( $item['type'] ); ?></p>
                        <p class="nc-entry__title">
                            <?php if ( $item['url'] ) : ?>
                                <a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html( $item['title'] ); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html( $item['title'] ); ?>
                            <?php endif; ?>
                        </p>
                        <p class="nc-entry__venue"><?php echo esc_html( $item['venue'] ); ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>

            </div>
            <?php endforeach; ?>

        </section>


        
        <section class="nc-press-section" aria-label="Press coverage">
            <h2 class="nc-press-section__label">Press</h2>

            <?php foreach ( nc_group_by_year( $press ) as $year => $entries ) : ?>
            <div class="nc-year-group">

                <div class="nc-year-group__anchor" aria-hidden="true">
                    <span class="nc-year-group__year"><?php echo esc_html( $year ); ?></span>
                </div>

                <ul class="nc-year-group__entries" role="list">
                    <?php foreach ( $entries as $item ) : ?>
                    <li class="nc-entry" data-type="<?php echo esc_attr( $item['type'] ); ?>">
                        <p class="nc-entry__type"><?php echo esc_html( $item['type'] ); ?></p>
                        <p class="nc-entry__title">
                            <?php if ( $item['url'] ) : ?>
                                <a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html( $item['title'] ); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html( $item['title'] ); ?>
                            <?php endif; ?>
                        </p>
                        <p class="nc-entry__venue"><?php echo esc_html( $item['venue'] ); ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>

            </div>
            <?php endforeach; ?>

        </section>


    </div>
</main>



<footer class="nc-footer" role="contentinfo">
    <div class="nc-container">

        <p class="nc-footer__copy">
            &copy; <?php echo esc_html( date( 'Y' ) ); ?> Nica Cornell
        </p>

        <ul class="nc-footer__links" aria-label="Footer navigation">
            <li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">All Publications</a></li>
            <li><a href="<?php echo esc_url( home_url( '/press/' ) ); ?>">Press</a></li>
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
       Sticky nav — threshold 10px (no hero beneath)
       ------------------------------------------------------------------ */
    var nav      = document.getElementById('nc-nav');
    var controls = document.getElementById('nc-press-controls');
    var THRESHOLD = 10;

    function updateNav() {
        nav.classList.toggle('is-scrolled', window.scrollY > THRESHOLD);
    }

    function sealControlsTop() {
        if (controls) {
            controls.style.top = nav.getBoundingClientRect().bottom + 'px';
        }
    }

    window.addEventListener('scroll', function () {
        updateNav();
        if (window.innerWidth < 600) { sealControlsTop(); }
    }, { passive: true });
    window.addEventListener('resize', sealControlsTop, { passive: true });
    updateNav();
    sealControlsTop();

    /* ------------------------------------------------------------------
       Mobile menu toggle
       ------------------------------------------------------------------ */
    var toggle = document.getElementById('nc-nav-toggle');

    if (toggle) {
        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            toggle.classList.toggle('is-open', isOpen);
            toggle.setAttribute('aria-expanded', String(isOpen));
            toggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
            document.body.style.overflow = isOpen ? 'hidden' : '';
            sealControlsTop();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('is-open')) {
                nav.classList.remove('is-open');
                toggle.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-label', 'Open navigation menu');
                document.body.style.overflow = '';
                toggle.focus();
                sealControlsTop();
            }
        });
    }

    /* ------------------------------------------------------------------
       Type filter
       ------------------------------------------------------------------ */
    var filterSelect = document.getElementById('nc-press-filter');

    if (filterSelect) {
        filterSelect.addEventListener('change', function () {
            var val = filterSelect.value;

            // Show/hide individual entries
            document.querySelectorAll('.nc-entry').forEach(function (entry) {
                entry.hidden = val !== 'all' && entry.dataset.type !== val;
            });

            // Hide year groups that have no visible entries after filtering
            document.querySelectorAll('.nc-year-group').forEach(function (group) {
                var hasVisible = group.querySelectorAll('.nc-entry:not([hidden])').length > 0;
                group.hidden = !hasVisible;
            });
        });
    }

}());
</script>

</body>
</html>
