<?php
/**
 * Main function for raffle cronjob.
 *
 * Runs every day at 12.00.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** CRON: RAFFLE */
// Cronjob initiatied at 12 every day
function cron_job_raffle() {

	// Set start time
	$start_time = new DateTime(current_time('mysql'));
	
	// Calculate the start and end of yesterday
	$current_time = new DateTime(current_time('mysql'));
	$yesterday = clone $current_time;
	$yesterday->modify('-1 day');
	$yesterday_start = $yesterday->format('Y-m-d 00:00:00');
	$yesterday_end = $yesterday->format('Y-m-d 23:59:59');
	
	// args
	$args = array( 
		'post_type' => 'post',
		'posts_per_page' => -1,
		'date_query' => array(
			array(
				'after'     => $yesterday_start,
				'before'    => $yesterday_end,
				'inclusive' => true,
			),
		),
	);
	
	// query
	$the_query = new WP_Query( $args );
	$total_count = $the_query->found_posts;
	
	// Set variables
	$available_count = 0;
	$booked_count = 0;
	$locker_count = 0;
	$erased_count = 0;
	$email_count = 0;
	$mood_count = 0;
	$happy_count = 0;
	$sad_count = 0;
		
	// Start loop
	if( $the_query->have_posts() ):
		while( $the_query->have_posts() ) : 
		$the_query->the_post(); 
	
	// Get variables
		$post_id = get_the_ID();
		$location = get_post_meta($post_id, 'location', true);
		$participants = get_post_meta($post_id, 'participants', true); 
		if (is_array($participants) && !empty($participants)) {
			$participants = array_filter($participants);   /* remove gaps  */
			$participants = array_values($participants) ;}  /* re-index */
		if (is_array($participants)) { $tickets = count($participants); } else { $tickets = 0; } 
	
	// Start raffle
	if (in_category( 'new' )) {
	
	// Post with no participants
	if ($tickets == 0) { admin_action_switch($post_id); $available_count++; }
	
	// Post with 1 participant & location locker
	if ($tickets == 1 && $location == 'Skåpet') { $winner_id = $participants[0]; admin_action_book_locker($winner_id, $post_id); $booked_count++; $locker_count++; $email_count += 2; $happy_count += 2; }
	
	// Post with 1 participant & custom location
	if ($tickets == 1 && $location != 'Skåpet') { $winner_id = $participants[0]; admin_action_book_custom($winner_id, $post_id); $booked_count++; $email_count += 2; $happy_count += 2; }
	
	// Post with 1+ participants & location locker
	if ($tickets > 1 && $location == 'Skåpet') { admin_action_raffle_locker($participants, $tickets, $post_id); $booked_count++; $locker_count++; $email_count += $tickets + 1; $happy_count += 2; $sad_count += $tickets - 1; } 
	
	// Post with 1+ participants & custom location
	if ($tickets > 1 && $location != 'Skåpet') { admin_action_raffle_custom($participants, $tickets, $post_id); $booked_count++; $email_count += $tickets + 1; $happy_count += 2; $sad_count += $tickets - 1; }
	
	// Post removed
	} elseif (in_category('removed')) { admin_action_erase($post_id); $erased_count++; } 
				
	endwhile;
	wp_reset_postdata();
	endif;
	
	// Set end time
	$end_time = new DateTime(current_time('mysql'));
		
	// Calculate the execution time
	$interval = $start_time->diff($end_time);
	$execution_time = $interval->format('%s');
	
	// Calculate % booked
	$final_count = $total_count - $erased_count;
	if ($final_count < 1) { $booked_percentage = 0; } else {
	$booked_percentage = round(($booked_count / $final_count) * 100); }
	
	// Calculate % happy/sad
	$mood_count = $happy_count + $sad_count;
	if ($happy_count < 1) { $happy_percentage = 0; } else {
	$happy_percentage = round(($happy_count / $mood_count) * 100); }
	if ($sad_count < 1) { $sad_percentage = 0; } else {
	$sad_percentage = round(($sad_count / $mood_count) * 100); }
		
	// Count stuff currently in locker
    $locker_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => 104,
            ),
        ),
    );

    $locker_query = new WP_Query($locker_args);
    $locker_current = $locker_query->found_posts;
	
	// Prepare email
	$to = "lotten@loopis.app";
	$subject = "🎲 Lottning " . $start_time->format('d/m');
	$message = "
	<b>🎁 {$final_count} annonser hanterades</b><br>
	<hr>
	❤ {$booked_count} annonser paxades (♻ {$booked_percentage}%)<br>
	🟢 {$available_count} annonser blev först till kvarn<br>
	🔥 {$erased_count} annonser raderades<br>
	<br>
	✉ {$email_count} email skickades<br>
	🙂 {$happy_count} glada besked ({$happy_percentage}%)<br>
	☹ {$sad_count} tråkiga besked ({$sad_percentage}%)<br>
	<br>
	🐎 Processen tog {$execution_time} sekunder<br>
	⏰ " . $start_time->format('H:i:s') . " → " . $end_time->format('H:i:s') . "<br>
	<br>
	▶ {$locker_count} saker ska nu till skåpet<br>
	⏹ {$locker_current} saker finns i skåpet just nu
	";
	$headers = array(
		'From: info@loopis.app',
		'Content-Type: text/html; charset=UTF-8',
		'X-Emoji-Service: twemoji'
			);
	// Send email
	wp_mail($to, $subject, $message, $headers);	
}