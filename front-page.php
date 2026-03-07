<?php
/**
 * Front Page Template — Nica Cornell Portfolio
 *
 * Completely custom template. Does not use Astra's header/footer
 * wrappers — builds the full page from scratch while keeping
 * all WordPress hooks (wp_head, wp_footer, admin bar) intact.
 */

// Security: block direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preload" as="image" href="<?php echo esc_url( content_url( 'uploads/2025/12/HEROHOME.webp' ) ); ?>" type="image/webp">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'nc-page' ); ?>>
<?php wp_body_open(); ?>

<!-- =====================================================================
     NAVIGATION — fixed, transparent → solid on scroll
     ===================================================================== -->
<nav class="nc-nav" id="nc-nav" role="navigation" aria-label="Main navigation">

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nc-nav__logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
        <img src="<?php echo esc_url( content_url( 'uploads/2026/02/nica-logo.webp' ) ); ?>"
             alt="<?php bloginfo( 'name' ); ?>"
             class="nc-nav__logo-img"
             width="120"
             height="44">
    </a>

    <ul class="nc-nav__links" id="nc-nav-links">
        <li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">All Publications</a></li>
        <li><a href="<?php echo esc_url( home_url( '/press/' ) ); ?>">Press</a></li>
        <li><a href="#nc-about">About</a></li>
        <li><a href="#nc-contact">Contact</a></li>
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
     HERO — 100vh, full-bleed image, tagline + CTA buttons
     To add your hero image, go to homepage.css and set:
         background-image: url('/wp-content/uploads/your-image.jpg');
     on the .nc-hero selector.
     ===================================================================== -->
<section class="nc-hero" id="nc-hero" aria-label="Introduction">

    <div class="nc-hero__content">
        <h1 class="nc-hero__name">Nica Cornell</h1>
        <p class="nc-hero__desc">Writer &middot; Poet &middot; Academic</p>
        <div class="nc-hero__cta">
            <a href="#nc-publications" class="nc-btn--hero-primary">View Writing</a>
            <a href="#nc-contact" class="nc-btn--hero-ghost">Contact</a>
        </div>
    </div>

    <span class="nc-hero__scroll" aria-hidden="true">Scroll</span>

</section>


<!-- =====================================================================
     SECTION 1 — Featured Publications (3 static cards)
     ===================================================================== -->
<section class="nc-section nc-featured-pubs" id="nc-publications" aria-label="Featured Publications">

    <div class="nc-container">

        <h2 class="nc-pubs-heading">Featured Publications</h2>
        <br>
        <div class="nc-featured-grid">

            <?php
            // Order: Sickness in Style (1839), Inyathi Ibuzwa Kwabaphambili (1841), a sky is falling (1840)
            $featured_pubs = get_posts( [
                'post_type'      => 'publication',
                'post__in'       => [ 1839, 1841, 1840 ],
                'orderby'        => 'post__in',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
            ] );

            foreach ( $featured_pubs as $pub ) :
                $pub_id      = $pub->ID;
                $pub_title   = esc_html( $pub->post_title );
                $pub_ext_url = esc_url( get_post_meta( $pub_id, 'publication_url', true ) );

                $year_terms  = get_the_terms( $pub_id, 'publication_year' );
                $pub_year    = ( $year_terms && ! is_wp_error( $year_terms ) ) ? esc_html( $year_terms[0]->name ) : '';

                $pub_tagline   = esc_html( get_post_meta( $pub_id, 'tagline', true ) );
                $pub_publisher = esc_html( get_post_meta( $pub_id, 'publisher', true ) );
                $thumb_id      = get_post_thumbnail_id( $pub_id );
            ?>

            <div class="nc-pub-card">
                <div class="nc-carousel-card">

                    <p class="nc-carousel-card__title">
                        <?php if ( $pub_ext_url ) : ?>
                            <a href="<?php echo $pub_ext_url; ?>" target="_blank" rel="noopener noreferrer"><?php echo $pub_title; ?></a>
                        <?php else : ?>
                            <?php echo $pub_title; ?>
                        <?php endif; ?>
                    </p>

                    <?php if ( $pub_year ) : ?>
                       <p class="nc-carousel-card__year"><?php echo $pub_year; ?></p>
                    <?php endif; ?>
                    <?php if ( $pub_publisher ) : ?>
                        <p class="nc-carousel-card__publisher"><?php echo $pub_publisher; ?></p>
                    <?php endif; ?>
                    <br>
                    <div class="nc-carousel-card__img-wrap">
                        <?php if ( $thumb_id ) : ?>
                            <?php echo wp_get_attachment_image( $thumb_id, 'full', false, [
                                'alt'          => $pub_title,
                                'loading'      => 'eager',
                                'data-no-lazy' => '1',
                            ] ); ?>
                        <?php else : ?>
                            <div class="nc-carousel-card__img-placeholder" aria-hidden="true"></div>
                        <?php endif; ?>
                    </div>

                    <?php if ( $pub_tagline ) : ?>
                        <div class="nc-tagline-reveal">
                            <button class="nc-tagline-btn" type="button">View tagline</button>
                            <div class="nc-tagline-box" role="tooltip"><?php echo $pub_tagline; ?></div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php endforeach; ?>

        </div><!-- .nc-featured-grid -->

    </div><!-- .nc-container -->

</section>


<!-- =====================================================================
     SECTION 2 — All Publications (5-across slider)
     ===================================================================== -->
<section class="nc-section nc-all-pubs" aria-label="All Publications">

    <div class="nc-container">

        <div class="nc-all-pubs__head">
            <div>
                <h2 class="nc-pubs-heading">All Publications</h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>" class="nc-btn">View All</a>
        </div>

        <div class="nc-slider-viewport">
            <div class="nc-slider-track" id="nc-slider-track">

                <?php
                $all_pubs = get_posts( [
                    'post_type'      => 'publication',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                ] );

                foreach ( $all_pubs as $pub ) :
                    $pub_id      = $pub->ID;
                    $pub_title   = esc_html( $pub->post_title );
                    $pub_ext_url = esc_url( get_post_meta( $pub_id, 'publication_url', true ) );

                    $year_terms  = get_the_terms( $pub_id, 'publication_year' );
                    $pub_year    = ( $year_terms && ! is_wp_error( $year_terms ) ) ? esc_html( $year_terms[0]->name ) : '';

                    $pub_tagline   = esc_html( get_post_meta( $pub_id, 'tagline', true ) );
                    $pub_publisher = esc_html( get_post_meta( $pub_id, 'publisher', true ) );
                    $thumb_id      = get_post_thumbnail_id( $pub_id );
                ?>

                <div class="nc-pub-card">
                    <div class="nc-carousel-card">

                        <p class="nc-carousel-card__title">
                            <?php if ( $pub_ext_url ) : ?>
                                <a href="<?php echo $pub_ext_url; ?>" target="_blank" rel="noopener noreferrer"><?php echo $pub_title; ?></a>
                            <?php else : ?>
                                <?php echo $pub_title; ?>
                            <?php endif; ?>
                        </p>
                        <?php if ( $pub_year ) : ?>
                            <p class="nc-carousel-card__year"><?php echo $pub_year; ?></p>
                        <?php endif; ?>
                        <?php if ( $pub_publisher ) : ?>
                            <p class="nc-carousel-card__publisher"><?php echo $pub_publisher; ?></p>
                        <?php endif; ?>
                        <br>
                        <div class="nc-carousel-card__img-wrap">
                            <?php if ( $thumb_id ) : ?>
                                <?php echo wp_get_attachment_image( $thumb_id, 'full', false, [
                                    'alt'          => $pub_title,
                                    'loading'      => 'lazy',
                                    'data-no-lazy' => '1',
                                ] ); ?>
                            <?php else : ?>
                                <div class="nc-carousel-card__img-placeholder" aria-hidden="true"></div>
                            <?php endif; ?>
                        </div>

                        <?php if ( $pub_tagline ) : ?>
                            <div class="nc-tagline-reveal">
                                <button class="nc-tagline-btn" type="button">View tagline</button>
                                <div class="nc-tagline-box" role="tooltip"><?php echo $pub_tagline; ?></div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <?php endforeach; ?>

            </div><!-- .nc-slider-track -->
        </div><!-- .nc-slider-viewport -->

        <div class="nc-slider-nav">
            <button class="nc-slider-nav__btn" id="nc-slider-prev" aria-label="Previous publications">&#8592;</button>
            <button class="nc-slider-nav__btn" id="nc-slider-next" aria-label="Next publications">&#8594;</button>
        </div>

    </div><!-- .nc-container -->

</section>


<!-- =====================================================================
     SECTION 3 — About the Author
     ===================================================================== -->
<section class="nc-section nc-about" id="nc-about" aria-label="About the author">

    <div class="nc-container">
        <div class="nc-about__grid">

            <!-- Portrait -->
            <div class="nc-about__image-wrap">
                <img src="<?php echo esc_url( content_url( 'uploads/2026/03/nica-avatar.webp' ) ); ?>"
                     alt="Nica Cornell"
                     class="nc-about__img"
                     width="800"
                     height="800"
                     loading="lazy">
            </div>

            <!-- Text -->
            <div class="nc-about__text">

                <h2 class="nc-about__name">Nica Cornell</h2>
                <br>
                <div class="nc-about__bio">
                    <p>Nica Cornell is a South African writer, poet, and academic whose work spans fashion studies, disability, and African intellectual history. Her debut memoir <em>Sickness in Style</em> (Lived Places Publishing, 2025) explores the relationship between dress, illness, and identity from South Africa to the United Kingdom.</p>
                    <p>Her debut poetry anthology <em>a sky is falling</em> (Mwanaka Media and Publishing) was published in 2023. Her poems have also appeared in a range of publications including 20.35 Africa and Best New African Poets, along with being translated into Spanish and Tamil.</p>
                    <p>Her academic writing has been published in the International Journal of Fashion Studies, the South African Foreign Policy Review, and across several edited collections on African intellectual history.</p>
                </div>

                <a href="#nc-contact" class="nc-btn--primary">
                    Get in Touch
                </a>

            </div><!-- .nc-about__text -->

        </div><!-- .nc-about__grid -->
    </div><!-- .nc-container -->

</section>


<!-- =====================================================================
     SECTION 4 — Contact
     ===================================================================== -->
<section class="nc-section nc-contact" id="nc-contact" aria-label="Contact">

    <div class="nc-container">
        <div class="nc-contact__grid">

            <!-- Left: heading -->
            <div class="nc-contact__intro">
                <h2 class="nc-contact__heading">Get in Touch</h2>
            </div>

            <!-- Right: form -->
            <div class="nc-contact__form">
                <?php echo do_shortcode('[contact-form-7 id="d03532c" title="Contact Me"]'); ?>
            </div>

        </div>
    </div>

</section>


<!-- =====================================================================
     FOOTER — minimal
     ===================================================================== -->
<footer class="nc-footer" role="contentinfo">
    <div class="nc-container">

        <p class="nc-footer__copy">
            &copy; <?php echo esc_html( date( 'Y' ) ); ?> Nica Cornell
        </p>

        <ul class="nc-footer__links" aria-label="Footer navigation">
            <li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">All Publications</a></li>
            <li><a href="#nc-about">About</a></li>
            <li><a href="#nc-contact">Contact</a></li>
        </ul>

    </div>
</footer>


<?php wp_footer(); ?>

<script>
(function () {
    'use strict';

    /* ------------------------------------------------------------------
       Sticky nav: transparent → solid on scroll
       ------------------------------------------------------------------ */
    var nav       = document.getElementById('nc-nav');
    var THRESHOLD = 80; // px before nav solidifies

    function updateNav() {
        nav.classList.toggle('is-scrolled', window.scrollY > THRESHOLD);
    }

    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav(); // run on load in case page is already scrolled

    /* ------------------------------------------------------------------
       Mobile menu toggle
       ------------------------------------------------------------------ */
    var toggle = document.getElementById('nc-nav-toggle');

    if (toggle) {
        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            toggle.classList.toggle('is-open', isOpen);
            toggle.setAttribute('aria-expanded', String(isOpen));
            // Prevent body scroll while menu is open
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        // Close on ESC
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
       Publications slider — prev / next buttons
       ------------------------------------------------------------------ */
    var track   = document.getElementById('nc-slider-track');
    var btnPrev = document.getElementById('nc-slider-prev');
    var btnNext = document.getElementById('nc-slider-next');

    /* ------------------------------------------------------------------
       Tagline portal — renders tooltip as position:fixed on <body> so it
       escapes the scroll container's overflow clipping entirely
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
                portal.style.top  = (r.bottom + 8) + 'px';
                portal.style.left = r.left + 'px';
                portal.style.opacity = '1';
            });

            reveal.addEventListener('mouseleave', function () {
                portal.style.opacity = '0';
            });
        });
    }());

    if (track && btnPrev && btnNext) {

        function getScrollStep() {
            var card = track.querySelector('.nc-pub-card');
            if (!card) { return 320; }
            var gap = parseFloat(getComputedStyle(track).columnGap) || 32;
            return card.offsetWidth + gap;
        }

        btnPrev.addEventListener('click', function () {
            track.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
        });

        btnNext.addEventListener('click', function () {
            track.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
        });
    }

}());
</script>

</body>
</html>
