<?php
/**
 * Statistics for our special user Max Murpos.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<h1>📊 Max Murpos</h1>
<hr>
<p class="small">💡 Här ser du statistik för saker vi räddat från soprum.</p>

<?php
// Get the current year
$current_year = date('Y');
?>

<div class="columns"><div class="column1"><h3>Resultat</h3></div>
<div class="column2">2024-<?php echo $current_year; ?></div></div>
<hr>
<?php
// Get profile economy
$profile_economy = loopis_ledger_user_event_counts('66');
$count_submitted = $profile_economy['count_submitted'];
$count_given = $profile_economy['count_given'];
if ($count_submitted !== 0) { $given_percentage = round(($count_given / $count_submitted) * 100); } else { $given_percentage = 0; }
?>

<p><span class="big-label">⬆ <?php echo $count_submitted; ?> saker upplagda</span></p>
<p><span class="big-label">✅ <?php echo $count_given; ?> saker bortskänkta</span> <span class="small">♻ <?php echo $given_percentage; ?>%</span></p>