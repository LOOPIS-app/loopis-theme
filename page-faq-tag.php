<?php
/**
 * Content for page using url /faq-tag/
 * 
 */

get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>💡 Frågor & svar - <?php the_title(); ?></h1>
<hr>
<p class="small">💡 Vanliga frågor och info om LOOPIS.</p>

<p>Undrar du något? Här finns svar på det mesta.</p>
<?php if ( is_user_logged_in() ) : ?>
<p class="small">🛟 Har du problem med en annons? Använd formuläret längst ner på annonssidan!</p>
<p class="small">💭 Du kan också skicka frågor och feedback till admin längst ner.</p>
<?php endif; ?>

<!-- start list all tags -->

<?php
$terms = get_terms([
    'taxonomy'   => 'faq-tag',
    'hide_empty' => false,
]);

if (!empty($terms) && !is_wp_error($terms)) :

    foreach ($terms as $term) : ?>

        <section class="faq-tag-section">
            <h2>
                <a href="<?php echo esc_url(get_term_link($term)); ?>">
                    <?php echo esc_html($term->name); ?>
                </a>
            </h2>

            <?php
            $faq_query = new WP_Query([
                'post_type'      => 'faq',
                'posts_per_page' => -1, // show all posts
                'tax_query'      => [
                    [
                        'taxonomy' => 'faq-tag',
                        'field'    => 'term_id',
                        'terms'    => $term->term_id,
                    ],
                ],
            ]);

            if ($faq_query->have_posts()) :

                echo '<ul>';

                while ($faq_query->have_posts()) :
                    $faq_query->the_post();
                    echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
                endwhile;

                echo '</ul>';

                wp_reset_postdata();

            else :
                echo '<p>Inga FAQ i denna tag.</p>';
            endif;
            ?>

        </section>

    <?php endforeach;

else :
    echo '<p>Inga tags hittades.</p>';
endif;
?>

<?php
// Reset postdata
wp_reset_postdata();
?>

<!-- end list all tags -->

<!-- tag cloud -->

<hr>
<h2>Taggmoln</h2>
<?php
wp_tag_cloud([
    'taxonomy'   => 'faq-tag',  // What taxonomy
    'smallest'   => 12,          // Min font-size in px
    'largest'    => 24,          // Max font-size in px
    'unit'       => 'px',
    'format'     => 'flat',      // 'flat', 'list', or 'array'
    'separator'  => " ",         // separator between tags
    'orderby'    => 'count',     // sort by count
    'order'      => 'DESC',      // the biggest first
    'number'     => 0,           // 0 = all terms
]);
?>

<!-- end tag cloud -->

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