<?php
/**
 * Archive for custom post type 'faq' reached on URL /faq
 * 
 * Lists all existing faq posts dynamically (alphabetically ordered?) with tags as headers (alphabetically ordered?).
 */

get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>💡 Frågor & svar</h1>
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
            <h3>
                <?php echo esc_html($term->name); ?>
            </h3>
            <hr>

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

                while ($faq_query->have_posts()) :
                    $faq_query->the_post();
                    echo '<p><span class="big-link"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></span></p>';
                endwhile;

                wp_reset_postdata();

            else :
                echo '<p>💢 Inga FAQ med denna tagg.</p>';
            endif;
            ?>

        </section>

    <?php endforeach;

else :
    echo '<p>💢 Inga taggar hittades.</p>';
endif;
?>

<!-- end list all tags -->

<?php if ( is_user_logged_in() ) : ?>
<h3>För medlemmar</h3>
<hr>
<p><span class="big-link"><a href="https://drive.google.com/drive/folders/1l1B43flky-zXgQ2wFD24s_32N_pfWHvd?usp=drive_link"><i class="fas fa-share"></i> Föreningens protokoll</a></span></p>
<p><span class="big-link"><a href="https://www.facebook.com/groups/loopis" target="_blank" rel="noreferrer noopener"><i class="fas fa-share"></i> Facebook-grupp</a></span></p>
<?php endif; ?>

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