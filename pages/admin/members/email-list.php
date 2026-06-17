<?php
/**
 * Email list page
 * Tool for extracting email addresses of members
 * Should be improved with filters in the future.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>✉ Epost-adresser</h1>
<hr>
<p class="small">💡 Verktyg för att plocka ut e-postadresser till medlemmar.</p>

<?php
// Define available roles
$available_roles = array(
    'member' => 'Nuvarande medlemmar',
    'member_earlier' => 'Tidigare medlemmar',
);

// Get selected roles from GET or default to all
$selected_roles = isset($_GET['roles']) ? (array) $_GET['roles'] : array_keys($available_roles);
// Validate selected roles
$selected_roles = array_intersect($selected_roles, array_keys($available_roles));
if (empty($selected_roles)) {
    $selected_roles = array_keys($available_roles);
}
?>

<!-- Role Selection Form -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <!-- Preserve the view parameter -->
    <input type="hidden" name="view" value="<?php echo esc_attr(isset($_GET['view']) ? $_GET['view'] : ''); ?>">
    <label for="roles">Välj roller:</label><br>
    <?php foreach ($available_roles as $role => $label) : ?>
        <label style="display: inline-block; margin-right: 15px; margin-bottom: 10px;">
            <input type="checkbox" name="roles[]" value="<?php echo esc_attr($role); ?>" 
                <?php checked(in_array($role, $selected_roles)); ?>>
            <?php echo esc_html($label); ?>
        </label>
    <?php endforeach; ?>
    <br>
    <button type="submit" class="green small" style="margin-top: 10px;">Hämta epostadresser</button>
</form>

<?php
// Fetch users for selected roles
$all_users = array();
$role_counts = array();

foreach ($selected_roles as $role) {
    $args = array(
        'role'    => $role,
        'fields'  => array('user_email'),
    );
    $users = get_users($args);
    $all_users = array_merge($all_users, $users);
    $role_counts[$role] = count($users);
}

// Extract email addresses into an array
$emails = wp_list_pluck($all_users, 'user_email');
$total_count = count($emails);

// Display counts
echo '<p><strong>Antal medlemmar:</strong><br>';
foreach ($role_counts as $role => $count) {
    echo '• ' . esc_html($available_roles[$role]) . ': ' . $count . '<br>';
}
echo '• Totalt: ' . $total_count . '</p>';
echo '<hr>';

// Initialize variables
$output = '';
$counter = 0;

// Loop through the emails and build the output
foreach ($emails as $email) {
    $output .= $email;

    // Increment the counter
    $counter++;

    // Add a comma and space if it's not the last email
    if ($counter < count($emails)) {
        $output .= ', ';
    }

    // Add two line breaks after every 100 emails
    if ($counter % 100 === 0) {
        $output .= '<br><br>';
    }
}

// Output the email list
echo $output;
?>