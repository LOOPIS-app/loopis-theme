<?php
/**
 * Pagination template
 * Works with both default WordPress query and custom WP_Query
 */

if (!defined('ABSPATH')) {
    exit;
}

// Determine which query to use
global $wp_query;
$query = isset($the_query) ? $the_query : $wp_query;

// Only show pagination if there's more than one page
if ($query->max_num_pages > 1) :
    $paged = get_query_var('paged');
    if (!$paged && isset($_GET['paged'])) {
        $paged = absint($_GET['paged']);
    }
    $paged = max(1, (int) $paged);

    $pagination_add_args = array();
    if (!empty($_GET) && is_array($_GET)) {
        $pagination_add_args = wp_unslash($_GET);
        unset($pagination_add_args['paged']);
    }
    ?>
    <div id="post-pagination">
        <?php
        echo wp_kses_post(paginate_links(array(
            'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'total'        => $query->max_num_pages,
            'current'      => $paged,
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 2,
            'prev_next'    => true,
            'prev_text'    => '<',
            'next_text'    => '>',
            'add_args'     => $pagination_add_args,
            'add_fragment' => '',
        )));
        ?>
    </div><!--/.post-pagination-->
<?php endif; ?>
