<?php
/**
 * List of rewards.
 * Should be improved.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>✨ Belöningar</h1>
<hr>
<p class="small">💡 Här ser du alla belöningar.</p>

<?php
global $wpdb;

// Query to retrieve all rewards
$results = $wpdb->get_results("
    SELECT um.meta_value AS reward_data, u.ID AS user_id, u.user_login
    FROM {$wpdb->usermeta} um
    INNER JOIN {$wpdb->users} u ON u.ID = um.user_id
    WHERE um.meta_key = 'wpum_rewards'
      AND um.meta_value != ''
      AND um.meta_value IS NOT NULL
");

// Set the field ID for the dropdown field
$field_id = 58;
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
$reward_reason_labels = [];

// If dropdown options are found, deserialize and build the mapping
if (!empty($dropdown_options)) {
    $options = maybe_unserialize($dropdown_options);
    if (is_array($options)) {
        foreach ($options as $option) {
            if (isset($option['value']) && isset($option['label'])) {
                $reward_reason_labels[$option['value']] = $option['label'];
            }
        }
    }
}

// Collect rewards for sorting and filtering
$all_rewards = [];

if (!empty($results)) {
    foreach ($results as $row) {
        $rewards = maybe_unserialize($row->reward_data);

        if (is_array($rewards)) {
            foreach ($rewards as $reward) {
                $reward_reason_date = isset($reward['wpum_reward_date'][0]['value']) ? $reward['wpum_reward_date'][0]['value'] : '';
                $reward_reason_value = isset($reward['wpum_reward_reason'][0]['value']) ? $reward['wpum_reward_reason'][0]['value'] : '';
                $reward_description = isset($reward['wpum_reward_description'][0]['value']) ? $reward['wpum_reward_description'][0]['value'] : '';

                $reward_reason_label = isset($reward_reason_labels[$reward_reason_value]) 
                    ? $reward_reason_labels[$reward_reason_value] 
                    : $reward_reason_value;

                $author_link = '<a href="' . esc_url(admin_url('user-edit.php?user_id=' . $row->user_id)) . '">' . esc_html($row->user_login) . '</a>';

                // Add reward to the list for sorting and filtering
                $all_rewards[] = [
                    'date' => $reward_reason_date,
                    'author_link' => $author_link,
                    'reason_label' => $reward_reason_label,
                    'reason_value' => $reward_reason_value,
                    'description' => $reward_description,
                ];
            }
        }
    }
}

// Sort rewards by date
usort($all_rewards, function ($a, $b) {
    return strcmp($a['date'], $b['date']);
});

// Handle filter (if selected)
$filter_reason = isset($_GET['filter_reason']) ? sanitize_text_field($_GET['filter_reason']) : '';

// Count displayed rewards
$displayed_count = 0;
foreach ($all_rewards as $reward) {
    if (empty($filter_reason) || $reward['reason_value'] === $filter_reason) {
        $displayed_count++;
    }
}
?>

<form method="GET" style="margin-bottom: 20px;">
    <select id="filter_reason" name="filter_reason" style="float:left; font-size:16px;">
        <option value="">Alla typer</option>
        <?php foreach ($reward_reason_labels as $value => $label): ?>
            <option value="<?php echo esc_attr($value); ?>" <?php echo ($filter_reason === $value) ? 'selected' : ''; ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="small" style="margin:3px 0 0 10px;">Filter</button>
</form>

<div class="columns">
    <div class="column1">↓ <?php echo $displayed_count; ?> stjärnor</div>
    <div class="column2 small">💡 Äldsta överst</div>
</div>
<hr>

<div class="logg" style="padding:0">
    <?php
    if (!empty($all_rewards)) {
        foreach ($all_rewards as $reward) {
            // Apply filter if set
            if (!empty($filter_reason) && $reward['reason_value'] !== $filter_reason) {
                continue;
            }
            ?>
            <p>
                <?php echo esc_html($reward['date']); ?> → 
                <?php echo $reward['author_link']; ?> – 
                <?php echo esc_html($reward['reason_label']); ?> 
                <?php echo esc_html($reward['description']); ?>
            </p>
            <?php
        }
    } else {
        ?>
        <p>💢 Inga belöningar.</p>
        <?php
    }
    ?>
</div>