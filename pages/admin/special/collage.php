<?php
/**
 * Collage page
 * Creates a pretty collage for communication purposes 
 * 
 * (Not yet checked by CoPilot)
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🖼 Kollage</h1>
<hr>
<p class="small">💡 Välj vilket kollage du vill se.</p>

<style>
#collage-form .form-row {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}
#collage-form label {
    font-size: 15px !important;
    margin-right: 10px;
    margin-bottom: 0;
    min-width: 90px;
}
#collage-form select {
    font-size: 15px !important;
    width: 220px;
    max-width: 100%;
    margin-bottom: 0;
}
#collage-form button {
    margin-top: 10px;
}
#collage-form label, #collage-form select {
    font-size: 15px !important;
}
#collage-form label {
    display: block;
    margin-bottom: 2px;
}
#collage-form select {
    display: block;
    margin-bottom: 12px;
    width: 220px;
    max-width: 100%;
}
#collage-form button {
    margin-top: 10px;
}
</style>
<?php
// Get all categories
$categories = get_categories(array('hide_empty' => false));
// Get selected category and number from GET or default
$selected_cat = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : (isset($categories[0]) ? $categories[0]->term_id : 0);
$selected_num = isset($_GET['num']) ? intval($_GET['num']) : 100;
// Get selected padding from GET or default
$selected_padding = isset($_GET['padding']) ? intval($_GET['padding']) : 5;
$padding_options = [0, 1, 2, 3, 4, 5];
// Get selected order from GET or default
$order_options = ['desc' => 'Nyast först', 'asc' => 'Äldst först'];
$selected_order = isset($_GET['order']) && in_array(strtolower($_GET['order']), ['asc','desc']) ? strtolower($_GET['order']) : 'desc';

// Get total posts in selected category
$total_posts = 0;
if ($selected_cat) {
    $total_posts = (int) get_category($selected_cat)->count;
}

// Build dropdown for categories, number, and padding
?>
<form id="collage-form" method="get" style="margin-bottom:20px;">
    <div class="form-row">
        <label for="cat_id">Kategori:</label>
        <select name="cat_id" id="cat_id">
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat->term_id; ?>" <?php selected($selected_cat, $cat->term_id); ?>><?php echo esc_html($cat->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-row">
        <label for="num">Antal:</label>
        <select name="num" id="num">
            <?php
            $max_posts = max($total_posts, 100);
            for ($n = 100; $n <= min($max_posts, 1000); $n += 100) {
                echo '<option value="' . $n . '"' . selected($selected_num, $n, false) . '>' . $n . '</option>';
            }
            for ($n = 2000; $n <= $max_posts; $n += 1000) {
                echo '<option value="' . $n . '"' . selected($selected_num, $n, false) . '>' . $n . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-row">
        <label for="order">Sortering:</label>
        <select name="order" id="order">
            <?php foreach ($order_options as $val => $label): ?>
                <option value="<?php echo $val; ?>" <?php selected($selected_order, $val); ?>><?php echo $label; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-row">
        <label for="padding">Padding:</label>
        <select name="padding" id="padding">
            <?php foreach ($padding_options as $pad): ?>
                <option value="<?php echo $pad; ?>" <?php selected($selected_padding, $pad); ?>><?php echo $pad; ?> px</option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="green small">Ladda kollage</button>
</form>
<div id="collage-grid-wrapper">
<?php
// Query posts
$args = array(
    'cat' => $selected_cat,
    'posts_per_page' => $selected_num,
    'post_status' => 'publish',
    'fields' => 'ids',
    'orderby' => 'date',
    'order' => strtoupper($selected_order),
);
$post_ids = get_posts($args);
$count = count($post_ids);
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(300); // Increase max execution time for large grids

$failed_thumbnails = array();
$rendered = 0;
if ($count > 0) {
    $side = ceil(sqrt($count));
    $thumb_size = 'thumbnail'; // Use WP's default square thumbnail
    echo '<div id="collage-grid" style="display:grid;grid-template-columns:repeat(' . $side . ',1fr);gap:' . $selected_padding . 'px;background:#fff;">';
    foreach ($post_ids as $post_id) {
        $thumb = get_the_post_thumbnail($post_id, $thumb_size, array('style'=>'width:100%;aspect-ratio:1/1;object-fit:cover;display:block;background:#eee;'));
        if ($thumb) {
            echo '<div style="background:#fff;padding:0;">' . $thumb . '</div>';
        } else {
            echo '<div style="background:#fbb;padding:0;"></div>';
            $failed_thumbnails[] = $post_id;
        }
        $rendered++;
        if ($rendered % 500 === 0) {
            echo "<!-- Flushing after $rendered -->\n";
            flush();
            ob_flush();
        }
    }
    // Fill empty spots to keep square
    for ($i = $count; $i < $side * $side; $i++) {
        echo '<div style="background:#eee;padding:0;"></div>';
    }
    echo '</div>';
    // Add counter after the grid
    if ($rendered < $count) {
        echo '<div class="small" style="color:#c00;margin-top:10px;">Varning: Sidan laddades inte klart. Endast ' . $rendered . ' av ' . $count . ' bilder renderades. (Timeout/minne?)</div>';
    }
    else {
        echo '<div class="small" style="margin-top:10px;">Antal bilder: ' . $count . ' &nbsp;|&nbsp; Rutnät: ' . $side . ' × ' . $side . '</div>';
    }
    // Error output for failed thumbnails
    if (!empty($failed_thumbnails)) {
        echo '<div class="small" style="color:#c00;margin-top:5px;">Varning: ' . count($failed_thumbnails) . ' tumnaglar kunde inte laddas. Post IDs: ' . implode(", ", $failed_thumbnails) . '</div>';
    }
    // Export button and spinner after grid/counter
    echo '<button id="export-collage" class="orange small" type="button" style="margin-top:15px;margin-bottom:0;display:none;">Exportera kollage</button>';
    echo '<span id="export-loading" style="display:none;vertical-align:middle;"><svg width="22" height="22" viewBox="0 0 50 50"><circle cx="25" cy="25" r="20" fill="none" stroke="#2ecc40" stroke-width="5" stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)"><animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="1s" repeatCount="indefinite"/></circle></svg></span>';
    echo '<p class="info">JPG (3000 x 3000 px)</p>';
} else {
    echo '<p>Inga inlägg hittades i denna kategori.</p>';
}
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
// Show export button only when grid is loaded
window.addEventListener('DOMContentLoaded', function() {
    var grid = document.getElementById('collage-grid');
    var btn = document.getElementById('export-collage');
    if (grid && btn) btn.style.display = 'inline-block';
});
document.getElementById('export-collage') && document.getElementById('export-collage').addEventListener('click', function() {
    var grid = document.getElementById('collage-grid');
    var loading = document.getElementById('export-loading');
    if (!grid) return;
    if (loading) loading.style.display = 'inline-block';
    // Clone grid to avoid resizing the visible one
    var clone = grid.cloneNode(true);
    var side = Math.sqrt(clone.children.length);
    var cellSize = Math.floor(3000 / side);
    var padding = <?php echo (int)$selected_padding; ?>;
    // Use flex for export
    clone.style.display = 'flex';
    clone.style.flexWrap = 'wrap';
    clone.style.width = (cellSize * side) + 'px';
    clone.style.height = (cellSize * side) + 'px';
    clone.style.background = '#fff';
    clone.style.gap = '0';
    clone.style.gridTemplateColumns = '';
    // Set all children to square, fill parent, and add padding
    Array.from(clone.children).forEach(function(child) {
        child.style.width = cellSize + 'px';
        child.style.height = cellSize + 'px';
        child.style.margin = '0';
        child.style.padding = padding + 'px';
        child.style.background = '#fff';
        child.style.boxSizing = 'border-box';
        // Remove any img width/height attributes for scaling
        var img = child.querySelector('img');
        if (img) {
            img.removeAttribute('width');
            img.removeAttribute('height');
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover'; // Use cover for consistent fill
            img.style.objectPosition = 'center'; // Center image
            img.style.display = 'block';
            img.style.aspectRatio = '';
        }
    });
    // Create a hidden container
    var hidden = document.createElement('div');
    hidden.style.position = 'fixed';
    hidden.style.left = '-9999px';
    hidden.style.top = '0';
    hidden.style.width = (cellSize * side) + 'px';
    hidden.style.height = (cellSize * side) + 'px';
    hidden.appendChild(clone);
    document.body.appendChild(hidden);
    html2canvas(clone, {width: cellSize * side, height: cellSize * side, backgroundColor: '#fff', useCORS: true}).then(function(canvas) {
        var link = document.createElement('a');
        link.download = 'kollage.jpg';
        link.href = canvas.toDataURL('image/jpeg', 1.0);
        link.click();
        document.body.removeChild(hidden);
        if (loading) loading.style.display = 'none';
    }).catch(function() {
        if (loading) loading.style.display = 'none';
    });
});
</script>
