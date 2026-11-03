<?php
/**
 * Pagination template for pages routed through a view query parameter.
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wp_query;
$query = isset($the_query) ? $the_query : (get_query_var('custom_query') ?: $wp_query);

if ($query->max_num_pages > 1) :
    $paged = max(
        1,
        absint(get_query_var('paged')),
        isset($_GET['paged']) ? absint(wp_unslash($_GET['paged'])) : 0
    );
    $pagination_args = wp_unslash($_GET);
    unset($pagination_args['paged']);
    $request_path = wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH);
    $base_path = preg_replace('#/page/\d+/?$#', '/', $request_path);
    $pagination_base = trailingslashit($base_path) . '%_%';
    ?>
    <div id="post-pagination">
        <?php
        echo wp_kses_post(paginate_links(array(
            'base'      => $pagination_base,
            'format'    => '?paged=%#%',
            'total'     => $query->max_num_pages,
            'current'   => max(1, $paged),
            'show_all'  => false,
            'type'      => 'plain',
            'end_size'  => 2,
            'mid_size'  => 2,
            'prev_next' => true,
            'prev_text' => '<',
            'next_text' => '>',
            'add_args'  => $pagination_args,
        )));
        ?>
    </div><!--/.post-pagination-->
<?php endif; ?>
