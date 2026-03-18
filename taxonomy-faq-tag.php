<?php
/**
 * Content for page using url /faq-tag/tag
 * 
 */

get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>💡 Frågor & svar - tag</h1>
<hr>
<p class="small">💡 Vanliga frågor och info om LOOPIS.</p>

<p>Undrar du något? Här finns svar på det mesta.</p>
<?php if ( is_user_logged_in() ) : ?>
<p class="small">🛟 Har du problem med en annons? Använd formuläret längst ner på annonssidan!</p>
<p class="small">💭 Du kan också skicka frågor och feedback till admin längst ner.</p>
<?php endif; ?>

<!-- get specific tags-->

<?php
// Get the current term object
$term = get_queried_object();

// Show the term name as h1
?>
<header class="taxonomy-header">
    <h1><?php echo esc_html( $term->name ); ?></h1>
    <?php if ( ! empty( $term->description ) ) : ?>
        <div class="term-description">
            <?php
            // Show description without extra h2 (using wpautop)
            echo wpautop( wp_kses_post( $term->description ) );
            ?>
        </div>
    <?php endif; ?>
</header>

<?php
// Create a custom WP_Query for FAQ with the current term
$faq_query = new WP_Query([
    'post_type' => 'faq',
    'tax_query' => [
        [
            'taxonomy' => 'faq-tag',
            'field'    => 'slug',
            'terms'    => $term->slug,
        ]
    ],
    'posts_per_page' => -1, // all posts
    'orderby'        => 'title', // sort by title
    'order'          => 'ASC',
]);

if ( $faq_query->have_posts() ) : ?>
    <ul class="faq-list">
        <?php while ( $faq_query->have_posts() ) : $faq_query->the_post(); ?>
            <li>
                <a href="<?php echo esc_url( get_permalink() ); ?>">
                    <?php echo esc_html( get_the_title() ); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else : ?>
    <p>Inga FAQ hittades för denna tag.</p>
<?php endif; ?>

<?php
// Reset postdata
wp_reset_postdata();
?>

<!-- end get specific tag -->

<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>

<div class="wrapped">
<h5>⚠ Fler frågor?</h5>
<hr>
<?php if ( is_user_logged_in() ) { ?>
<p>→ Fråga i medlemmarnas <a rel="noreferrer noopener" href="https://web.facebook.com/groups/loopis.medlemmar" target="_blank">Facebook-grupp</a></p>
<p>→ Skicka en fråga eller feedback till admin i formuläret här nedanför.</p>
<?php } ?>
<p>→ Maila styrelsen på <a rel="noreferrer noopener" href="mailto:info@loopis.org" target="_blank">info@loopis.org</a></p>
</div>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>
