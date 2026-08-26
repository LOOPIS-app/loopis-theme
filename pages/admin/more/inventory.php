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
	<div class="post-list-post-meta" >
		<input type="number" class="inventory-qty" data-index="<?php echo $idx; ?>" placeholder="Vikt (g)" min="0" onclick="event.stopPropagation()" style="width:100px; margin-left:10px;">
        <label style="display:flex;align-items:center;gap:6px;margin-left:10px;" class="right">
            <input type="radio" name="inventory_group_<?php echo $idx; ?>" class="inventory-status" data-index="<?php echo $idx; ?>" value="checked" checked>
            <span>Finns</span>
            <input type="radio" name="inventory_group_<?php echo $idx; ?>" class="inventory-status" data-index="<?php echo $idx; ?>" value="unchecked">
            <span>Finns ej</span>
        </label>
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

    const rows = <?php echo json_encode($csv_rows, JSON_UNESCAPED_UNICODE); ?>;

    const checkedRows = [];
    const uncheckedRows = [];

    document.querySelectorAll('.post-list-post').forEach(function(card) {
        const qtyInput = card.querySelector('.inventory-qty');
        const i = parseInt(qtyInput?.getAttribute('data-index'), 10);
        if (!rows[i]) return;

        // qty column is index 1
        rows[i][1] = qtyInput.value;

        const status = card.querySelector('.inventory-status:checked')?.value;
        if (status === 'checked') checkedRows.push(rows[i]);
        else uncheckedRows.push(rows[i]);
    });

    function toCSV(data) {
        return data.map(row =>
            row.map(cell => '"' + String(cell ?? '').replace(/"/g, '""') + '"').join(',')
        ).join('\r\n');
    }

    function downloadCSV(filename, data) {
        const csv = toCSV(data);
        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        URL.revokeObjectURL(url);
    }

    // If you still want BOTH files:
    downloadCSV('Inventory-Checked.csv', checkedRows);
    setTimeout(function () {
        downloadCSV('Inventory-Unchecked.csv', uncheckedRows);
    }, 250);
});
</script>
