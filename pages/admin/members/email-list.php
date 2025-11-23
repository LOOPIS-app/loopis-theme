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
// Get all users with the role 'member'
$args = array(
    'role'    => 'member',
    'fields'  => array('user_email'), // Only retrieve the email addresses
);

$users = get_users($args);

// Extract email addresses into an array
$emails = wp_list_pluck($users, 'user_email');

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