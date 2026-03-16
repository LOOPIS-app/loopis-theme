<?php
/**
 * Hardcoded settings for Swish buttons.
 *
 * Included for everyone in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Used when showing buttons for buying membership or coins.
 */
function swish_payment() {
    // Set variables
    $fee = 50;
    $coins = 5;
    $number = '0739993265';
    $number_dash = '073-9993265';
    $receiver = 'föreningens kassör Tone Alin';

    // Return variables
    return array(
        'fee' => $fee,
        'coins' => $coins,
        'number' => $number,
        'number_dash' => $number_dash,
        'receiver' => $receiver
    );
}