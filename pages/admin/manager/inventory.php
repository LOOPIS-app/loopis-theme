<?php
/**
 * Inventory page!
 * List all items in locker
 * 
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📋 Inventering</h1>
<hr>
<p class="small">💡 Lista över alla saker i skåpet.</p>

<?php

// Get current timestamp
$now_time = (new DateTime(current_time('mysql')))->getTimestamp();

// Args
$args = array( 
	'post_type' => 'post',
	'cat' => loopis_cat('locker'),
);

// Query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts;

// Collect CSV data before display loop
$csv_rows = array();
$today = current_time('Y-m-d');
if ($the_query->have_posts()) {
    while ($the_query->have_posts()) : $the_query->the_post();
        $csv_rows[] = array(get_the_title(), '', get_permalink(), $today);
    endwhile;
    $the_query->rewind_posts();
    wp_reset_postdata();
}
?>

<h3>⏹ Skåpet</h3>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' sak'; } else { echo ' saker'; } ?>
</div><div class="column2"><a href="#" id="download-inventory-csv">📄Download.csv</a></div></div>
<hr>
<div class="post-list">

<?php if ($the_query->have_posts()) : ?>
    <?php $idx = 0; while ($the_query->have_posts()) : $the_query->the_post(); ?>

<div class="post-list-post">
	<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
	<div class="post-list-post-comment"><?php the_title(); ?></div>
	<div class="post-list-post-meta">
		<input type="number" class="inventory-qty" data-index="<?php echo $idx; ?>" placeholder="Vikt (g)" min="0" onclick="event.stopPropagation()" style="width:100px; margin-left:10px;">
	</div>
</div>

    <?php $idx++; endwhile; ?>
<?php else : ?>
    <p>💢 Inga saker finns i skåpet just nu.</p>
<?php endif; ?>

</div><!--post-list-->	

<?php wp_reset_postdata(); ?>

<script>
document.getElementById('download-inventory-csv').addEventListener('click', function(e) {
    e.preventDefault();
    var rows = <?php echo json_encode($csv_rows, JSON_UNESCAPED_UNICODE); ?>;
    document.querySelectorAll('.inventory-qty').forEach(function(input) {
        var i = parseInt(input.getAttribute('data-index'));
        if (rows[i]) rows[i][1] = input.value;
    });
    var csv = rows.map(function(row) {
        return row.map(function(cell) {
            return '"' + String(cell).replace(/"/g, '""') + '"';
        }).join(',');
    }).join('\r\n');
    var blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'Inventory.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});
</script>
