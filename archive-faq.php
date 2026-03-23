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
<p class="small">💡 Undrar du något? Här finns svar på det mesta.</p>

<p><b>Titta alltid på frågorna nedan innan du skickar en fråga till admin!</b></p>

<?php if ( is_user_logged_in() ) : ?>
<h3>För medlemmar</h3>
<hr>
<p style="margin-bottom: 0px;">🎁 Har du problem med en annons?</p>
<p class="small">→ Skriv en kommentar på annonsen och pinga admin med @LOOPIS</p>
<p style="margin-bottom: 0px;">💭 Har du andra frågor eller feedback?</p>
<p class="small">→ Skriv till admin genom hjälp-rutan längst ner på den berörda sidan.</p>
<p style="margin-bottom: 0px;">🗨 Vill du diskutera med andra medlemmar?</p>
<p class="small">→ Gå till medlemmarnas <a rel="noreferrer noopener" href="https://web.facebook.com/groups/loopis.medlemmar" target="_blank">Facebook-grupp</a> eller <a rel="noreferrer noopener" href="https://discord.com/channels/1480883243740954626/1480883244449927231" target="_blank">Discord-server</a></p>
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
<p><span class="big-link"><a href="https://drive.google.com/drive/folders/1l1B43flky-zXgQ2wFD24s_32N_pfWHvd?usp=drive_link"><i class="fas fa-share"></i> Föreningens protokoll</a></span> på Google Drive</p>
<p><span class="big-link"><a href="https://www.facebook.com/groups/loopis" target="_blank" rel="noreferrer noopener"><i class="fas fa-share"></i> Facebook-grupp</a></span> för frågor och diskussion</p>
<p><span class="big-link"><a href="https://discord.com/channels/1480883243740954626/1480883244449927231" target="_blank" rel="noreferrer noopener"><i class="fas fa-share"></i> Discord-server</a></span> för frågor och diskussion</p>
<?php endif; ?>

<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>

<!-- More questions? -->
<?php include LOOPIS_THEME_DIR . '/templates/faq/questions-faq.php'; ?>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>