<?php
/**
 * Front page header.
 *
 * Included in front-page.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

    <!-- List header -->
    <div class="columns">
        <?php if ( is_front_page() ) : ?>
        <div class="column1">↓ <?php echo $count_new; ?> nya och <?php echo $count_old; ?> tidigare</div>
        <?php else : ?>
        <div class="column1">↓ <?php echo $count; ?> aktuella annonser</div>
        <?php endif; ?>
        <div class="column2"><button class="link-style" onclick="reveal('filter');"><i class="fas fa-sliders-h"></i>Filter</button></div>
    </div>
    <hr>

    <div id="filter" class="filter-container">
        <div class="filter-wrapper">
            <form class="loopis-form" method="get" action="">
                <div class="form-row" id="gift-untags-row">
                <label for="untags-search">Jag vill inte se:</label>
                <input type="search" id="untags-search" placeholder="Sök kategori..." autocomplete="off">
                <div id="untags-options" class="terms-options" role="group" aria-label="Kategorier">
                    <?php if ( ! is_wp_error( $all_tags ) ) : ?>
                        <?php foreach ( $all_tags as $tag ) : ?>
                            <label class="term-option" data-term-label="<?php echo esc_attr($tag->name); ?>">
                                <input type="checkbox" name="untags[]" value="<?php echo esc_attr($tag->slug); ?>" <?php checked(in_array($tag->slug, $unselected_tags, true)); ?>>
                                <span><?php echo esc_html($tag->name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>         
                </div>
                <input type="hidden" name="filter_submitted" value="1">
                <?php wp_nonce_field( 'save_loopis_filter', 'loopis_filter_nonce', false ); ?>
                <button type="submit" name="save" value="0" class="green small">Aktivera</button>
                <?php if ( is_user_logged_in() ) : ?>
                    <button type="submit" name="save" value="1" class="blue small">Spara</button>
                <?php endif; ?>

                <a id="clear-untags" class="hideable" href="#" onclick="clearChecks('untags[]'); return false;">  <i class="fas fa-times"></i>Rensa</a>

            </form>
        </div>
    </div>


       
    <script>

    function reveal(objectId) {
        const object = document.getElementById(objectId);
        object.classList.toggle('is-visible');
    }

    function clearChecks(objectId) {
        document.querySelectorAll('input[type="checkbox"][name="'+objectId+'"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateButton();
    }

    function updateButton() {
        const boxes = document.querySelectorAll('input[name="untags[]"]');
        document.getElementById('clear-untags').classList.toggle(
          'is-visible',
          [...boxes].some(box => box.checked)
        );
    }

    const boxes = document.querySelectorAll('input[name="untags[]"]');
    boxes.forEach(box => box.addEventListener('change', updateButton));
    updateButton();
    </script>