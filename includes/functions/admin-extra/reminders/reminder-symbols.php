<?php
/**
 * Reminder symbols based on the number of reminders.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


function reminder_symbols(int $reminders) {
    $auto = str_repeat('🔔', min($reminders, 3));
    $manual = ($reminders >= 4) ? '📱' : '';
    $symbols = $auto . $manual;
    return $symbols;
}