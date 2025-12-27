<?php
/**
 * All payments page
 * View all payments to the organization with filtering options
 * Shows memberships, coin purchases, and other payment types by year and type
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📒 Alla köp</h1>
<hr>
<p class="small">💡 Här kan du se alla betalningar till föreningen.</p>

<?php
// Fetch dropdown options for wpum_payment_type_labels
global $wpdb;
$field_id = 35;
$dropdown_meta_key = 'dropdown_options';

// Query the database to get the dropdown options
$dropdown_options = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->prefix}wpum_fieldmeta WHERE wpum_field_id = %d AND meta_key = %s",
        $field_id,
        $dropdown_meta_key
    )
);

// Initialize the mapping array
$wpum_payment_type_labels = array();

// If dropdown options are found, deserialize and build the mapping
if (!empty($dropdown_options)) {
    $options = maybe_unserialize($dropdown_options);
    if (is_array($options)) {
        foreach ($options as $option) {
            if (isset($option['value']) && isset($option['label'])) {
                $wpum_payment_type_labels[$option['value']] = $option['label'];
            }
        }
    }
}

// Fetch all users with payments
$member_users = get_users(array(
    'fields' => array('ID', 'display_name')
));

// Define the current date and year
$today = date('Y-m-d');
$current_year = date('Y');

$all_payments = array();
$available_years = array();

foreach ($member_users as $user) {
    $meta_key = 'wpum_payments';
    $meta_values = get_user_meta($user->ID, $meta_key, true);

    if (!empty($meta_values)) {
        $user_data = get_userdata($user->ID);
        $user_roles = $user_data->roles;
        $is_member = in_array('member', $user_roles);

        foreach ($meta_values as $row) {
            $payment_date = $row['wpum_payment_date'][0]['value'];
            $payment_amount = $row['wpum_payment_amount'][0]['value'];
            $payment_method = $row['wpum_payment_method'][0]['value'];
            $payment_type = $row['wpum_payment_type'][0]['value'];

            // Extract the year from the payment date
            $payment_year = date('Y', strtotime($payment_date));
            if (!in_array($payment_year, $available_years)) {
                $available_years[] = $payment_year;
            }

            $all_payments[] = array(
                'user_id'        => $user->ID,
                'display_name'   => $user->display_name,
                'payment_date'   => $payment_date,
                'payment_type'   => $payment_type,
                'payment_amount' => $payment_amount,
                'payment_method' => $payment_method,
                'is_member'      => $is_member
            );
        }
    }
}

// Sort the payments by date (newest first)
usort($all_payments, function($a, $b) {
    return strtotime($b['payment_date']) - strtotime($a['payment_date']);
});

// Sort available years in descending order
rsort($available_years);

// Handle filters
$filter_year = isset($_GET['filter_year']) ? sanitize_text_field($_GET['filter_year']) : $current_year;
$filter_payment_type = isset($_GET['filter_payment_type']) ? sanitize_text_field($_GET['filter_payment_type']) : '';

// Filter payments by year
if (!empty($filter_year)) {
    $all_payments = array_filter($all_payments, function ($payment) use ($filter_year) {
        return date('Y', strtotime($payment['payment_date'])) === $filter_year;
    });
}

// Initialize summary variables
$count = 0;
$total_payment_amount = 0;
$mynt_count = 0;
$mynt_total_amount = 0;
$medlemskap_count = 0;
$medlemskap_total_amount = 0;

// Calculate summary based on filtered payments
foreach ($all_payments as $payment) {
    $count++;
    $total_payment_amount += $payment['payment_amount'];

    if ($payment['payment_type'] === 'Mynt') {
        $mynt_count++;
        $mynt_total_amount += $payment['payment_amount'];
    } elseif ($payment['payment_type'] === 'Medlemskap') {
        $medlemskap_count++;
        $medlemskap_total_amount += $payment['payment_amount'];
    }
}

// Count displayed payments after filtering by payment type
$filtered_count = 0;
foreach ($all_payments as $payment) {
    if (empty($filter_payment_type) || $payment['payment_type'] === $filter_payment_type) {
        $filtered_count++;
    }
}
?>

<!-- Year Filter -->
<form method="GET" action="/admin/" style="margin-bottom: 20px;">
    <input type="hidden" name="view" value="economy/payments">
    <select id="filter_year" name="filter_year" style="float: left; font-size: 16px;">
        <option value="">Alla år</option>
        <?php foreach ($available_years as $year) : ?>
            <option value="<?php echo esc_attr($year); ?>" <?php echo ($filter_year === $year) ? 'selected' : ''; ?>>
                <?php echo esc_html($year); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="small" style="margin: 3px 0 0 10px;">Välj år</button>
</form>

<!-- Summary Section -->
<div class="columns">
    <div class="column1">↓ Summering</div>
    <div class="column2 small"><?php echo esc_html($filter_year); ?></div>
</div>
<hr>

<p><span class="big-label">💰 <?php echo $count; ?> köp totalt = <?php echo $total_payment_amount; ?> kr</span></p>
<p><span class="label">🪙 <?php echo $mynt_count; ?> köp av mynt = <?php echo $mynt_total_amount; ?> kr</span></p>
<p><span class="label">👤 <?php echo $medlemskap_count; ?> köp av medlemskap = <?php echo $medlemskap_total_amount; ?> kr</span></p>

<!-- Payment Type Filter -->
<form method="GET" action="/admin/" style="margin-bottom: 20px;">
    <input type="hidden" name="view" value="economy/payments">
    <input type="hidden" name="filter_year" value="<?php echo esc_attr($filter_year); ?>">
    <select id="filter_payment_type" name="filter_payment_type" style="float: left; font-size: 16px;">
        <option value="">Alla typer</option>
        <?php foreach ($wpum_payment_type_labels as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php echo ($filter_payment_type === $value) ? 'selected' : ''; ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="small" style="margin: 3px 0 0 10px;">Filtrera typ</button>
</form>

<!-- Payments List -->
<h3>🗃 Registrerade köp</h3>
<div class="columns">
    <div class="column1">↓ <?php echo $filtered_count; ?> köp</div>
    <div class="column2 small">💡 Senaste överst</div>
</div>
<hr>

<?php if ($filtered_count > 0) : ?>
    <?php foreach ($all_payments as $payment) : ?>
        <?php
        // Apply filter if set
        if (!empty($filter_payment_type) && $payment['payment_type'] !== $filter_payment_type) {
            continue;
        }

        $author_url = get_author_posts_url($payment['user_id']);
        $red = $payment['is_member'] ? '' : 'red_light';
        $payment_date = date('Y-m-d', strtotime($payment['payment_date']));
        $blue = ($payment_date === $today) ? 'blue_light' : '';
        $payment_type_label = $wpum_payment_type_labels[$payment['payment_type']] ?? $payment['payment_type'];
        ?>
        
        <p>
            <span class="link grey <?php echo esc_attr($red . ' ' . $blue); ?>">
                <a href="<?php echo esc_url($author_url); ?>" class="link grey">
                    <i class="fas fa-receipt"></i><?php echo esc_html($payment['payment_date']); ?>: 
                    <?php echo esc_html($payment['display_name']); ?> - 
                    <?php echo esc_html($payment_type_label); ?> - 
                    <?php echo esc_html($payment['payment_amount']); ?>kr 
                    (<?php echo esc_html($payment['payment_method']); ?>)
                </a>
            </span>
        </p>
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Hittade inga registrerade betalningar.</p>
<?php endif; ?>