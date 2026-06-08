<?php
/*
TODO:
CAST SANITIZE VALIDATE 
*/

 /**
 *  ======================= REMOVE ======================
 */


 /**
 *  Removes entries from loopis ledger
 *  Only special due to recount necessity
 *  OBS: does not validate yet use with care
 *
 * @return void
 */
function loopis_ledger_remove($where){
    global $wpdb;
    if (!current_user_can('manage_options')){
        return;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    
    if (empty($where) || !is_array($where)){
        return;
    }

    $allowed = [
        'id' => '%d',
        'user_id' => '%d',
        'post_id' => '%d',
        'blog_id' => '%d',
        'event'   => '%s',
        'coins' => '%d',
        'payment_method' => '%s',
        'payment'   => '%d',
        'clover' => '%d',
    ];

    $allowed_keys = array_keys($allowed);

    $clauses = [];
    $values = [];

    if (isset($where['timestamp'])){
        if (is_array($where['timestamp'])){
            $count = count($where['timestamp']);
            if ($count===2){
                $clauses[] =  'timestamp BETWEEN %s AND %s';
                $values = array_merge($values, $where['timestamp']);
            }
        }
        unset($where['timestamp']);
    }
    
    foreach($where as $key => $value){
        // skip if not part of allowed arrays
        if (!isset($allowed[$key])){
            continue;
        }
        if (is_array($value)){
            $count = count($value);
            if ($count===0){
                continue;
            }
            $clause =  $key . ' IN (' . implode(',', array_fill(0,$count,$allowed[$key])) . ')';
            $values = array_merge($values, $value);
        }else{
            $clause = $key . ' = ' . $allowed[$key];
            $values[] =  $value;
        }
        $clauses[] = $clause;
    }

    if(empty($clauses)){
        return;
    }

    $user_ids = $wpdb->get_col(
        $wpdb->prepare("SELECT DISTINCT user_id FROM {$table_name} WHERE " . implode(' AND ', $clauses),
            $values)
        );

    if ($user_ids === NULL) {
        error_log($wpdb->last_error);
        return;
    }

    $result = $wpdb->query(
        $wpdb->prepare("DELETE FROM {$table_name} WHERE " . implode(' AND ', $clauses),
            $values)
        );

    if ($result === false) {
        error_log($wpdb->last_error);
        return;
    }

    if (!empty($user_ids)){
        foreach($user_ids as $user_id){
            $user_id = (int) $user_id;
            loopis_ledger_recount_user($user_id);
        } 
    }
    return;
}

 /**
 *  ======================= FETCH ======================
 */
 
 /**
 * Fetches user event information from ledger 
 *
 * @return array Of total amount of user events as count_submitted count_given, count_fetched, count_booked, count_regret, count_deleted
 */
function loopis_ledger_user_event_counts($user_id){
        global $wpdb;

    $user_id = (int) $user_id;
    if ( $user_id <= 0 ) {
        return null;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';

    $sql = $wpdb->prepare(
        "SELECT
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_submitted,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_given,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_fetched,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_booked,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_regret,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_deleted
         FROM {$table_name}
         WHERE user_id = %d",
        'submitted', 'given', 'fetched', 'booked', 'regret', 'removed', $user_id
    );

    return $wpdb->get_row( $sql, ARRAY_A );
}

 /**
 * Fetches user payment information from ledger 
 *
 * @return array ledger entries as [0] => ['payment' => 50, type => 'mynt' ...
 */
function loopis_ledger_user_payments($user_id){
        global $wpdb;

    $user_id = (int) $user_id;
    if ( $user_id <= 0 ) {
        return null;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $payments = $wpdb->get_results(
        $wpdb->prepare("SELECT *   
            FROM {$table_name} 
            WHERE `user_id`= %d AND `event` = 'payment'
            ORDER BY timestamp ASC",
            $user_id
        ), ARRAY_A
    );

    return $payments;
}
 /**
 * Fetches user reward information from ledger 
 *
 * @return array ledger entries as [0] => ['coins' => 1, type => 'survey' ...
 */
function loopis_ledger_user_rewards($user_id){
    global $wpdb;

    $user_id = (int) $user_id;
    if ( $user_id <= 0 ) {
        return null;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $rewards = $wpdb->get_results(
        $wpdb->prepare(
                "SELECT *   
                FROM {$table_name} 
                WHERE `user_id`= %d AND `event` = 'reward'",
            $user_id
        ), ARRAY_A
    );

    return $rewards;
}

 /**
 * Fetches information previously gotten by get_economy
 * 
 * Calculate economy for the specified user.
 * 
 *
 * @return array of economy info ['coins' => 12, clovers => 457 ...
 */
function loopis_ledger_economy($user_id){
    // Fetch the whole ledger in different chunks
    $events = loopis_ledger_user_event_counts($user_id);
    $payments = loopis_ledger_user_payments($user_id);
    $rewards = loopis_ledger_user_rewards($user_id);
    // Fetch the balances
    $clovers = (int) get_user_meta($user_id, 'loopis_clovers', true);
    $coins = (int) get_user_meta($user_id, 'loopis_balance', true);
    // Count payments and rewards
    $membership_coins = 0;
    $payments_coins = 0;
    $bought_coins = 0;
    $payments_membership = 0;
    if (!empty($payments)){
        $join_date = $payments[0]['timestamp'];
        foreach($payments as $payment){
            if ($payment['type']==='medlemskap'){
                $membership_coins += $payment['coins'];
                $payments_membership++;
            }elseif ($payment['type'] == 'mynt') {
                $payments_coins++;
                $bought_coins += $payment['coins'];
            } 
        }
    }else{
        $join_date = NULL;
    }
    $stars = 0;
    $star_coins = 0;
    if (!empty($rewards)){
        foreach($rewards as $reward){
            $stars++;
            $star_coins += (int) $reward['coins'];
        }
    }
    
    return array(
        //payments
        'payments_membership' => $payments_membership,
        'payments_coins' => $payments_coins,
        'membership_coins' => $membership_coins,
        'bought_coins' => $bought_coins,
        'joined_date' => $join_date, //membership date
        //event count
        'count_given' => (int) $events['count_given'],
        'count_booked' => (int) $events['count_booked'],
        'count_submitted' => (int) $events['count_submitted'],
        'count_deleted' => (int) $events['count_deleted'],
        //rewards 
        'stars' => $stars,
        'star_coins' => $star_coins,
        //balances,
        'clovers' => $clovers,
        'clover_coins' => floor($clovers/10),
        'coins' => $coins,
        );

}

 /**
 *  ======================= INSERT ======================
 */

 /**
 * Adds a ledger entry for a post event
 *
 * @return void
 */
function loopis_ledger_add_post($event, $user_id, $post_id, $options=[]){
    $user_id = (int) $user_id;
    $post_id = (int) $post_id;
    $defaults = [
        'location' => get_post_meta($post_id,'location',true) ?? 'unknown',
        'blog_id' => get_current_blog_id(),
        'timestamp' => current_time('Y-m-d H:i:s'),
    ];

    $blog_id = (int) ($options['blog_id'] ?? $defaults['blog_id']);
    $location = (string) ($options['location'] ?? $defaults['location']);
    $timestamp = $options['timestamp'] ?? $defaults['timestamp'];

    if (($user_id<=0)||($post_id<=0)||($blog_id<=0)){
        error_log('LEDGER ERROR AT EVENT:' . $event .', POST ID: '.$post_id. 'USER ID: '. $user_id);
        return;
    }
    

    global $wpdb;
    switch ($event) {
        case 'submitted':
            $coins = 0;            
            $clovers = 1;
            break;
        case 'given':
            $coins = 1;
            $clovers = 0;
            break;
        case 'fetched':
            $coins = 0;            
            $clovers = 1;
            break;
        case 'booked':
            $coins = -1;
            $clovers = 0;
            break;
        case 'removed':
            $coins = 0;
            $clovers = 0;
            break;
        case 'regret':
            $coins = 1;
            $clovers = 0;
            break;
        default:
            return;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $result = $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO {$table_name}
            (user_id, post_id, blog_id, location, event, coins, clover, timestamp)
            VALUES ( %d, %d, %d, %s, %s, %d, %d, %s)",
            $user_id,
            $post_id,
            $blog_id,
            $location,
            $event,
            $coins,
            $clovers,
            $timestamp
        )
    );

    if ($result === false) {
        error_log($wpdb->last_error);
        error_log('LEDGER ERROR AT EVENT:' . $event .', POST ID: '.$post_id. 'USER ID: '. $user_id);
    }else{
        loopis_update_coins($user_id, $coins, $clovers);
    }
}



 /**
 * Adds a ledger entry for a payment event
 *
 * @return void
 */
function loopis_ledger_add_payment($user_id,$options=[]){
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }
    global $wpdb;
    $defaults = [
        'type' => 'mynt',
        'description' => 'stripe',
        'location' => 'digital',
        'blog_id' => 1,
        'payment' => 50,
        'coins' => 5,
        'clovers'=>0,
    ];
    $type = $wpdb->esc_like((string)($options['type'] ?? $defaults['type']));
    $description = $wpdb->esc_like((string)($options['description'] ?? $defaults['description']));
    $location = (string)($options['location'] ?? $defaults['location']);
    $payment = isset($options['payment']) ? (int)$options['payment'] : (int)$defaults['payment'];
    $coins = isset($options['coins']) ? (int)$options['coins'] : (int)$defaults['coins'];
    $clovers = isset($options['clovers']) ? (int)$options['clovers'] : (int)$defaults['clovers'];
    $blog_id = isset($options['blog_id']) ? (int)$options['blog_id'] : (int)$defaults['blog_id'];
    $event = 'payment'; 
    $post_id=0;
    $timestamp = current_time('Y-m-d H:i:s');

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $result = $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO {$table_name}
            (user_id, post_id, blog_id, location, event, description, type, coins, clover, payment, timestamp)
            VALUES ( %d, %d, %d, %s, %s, %s, %s, %d, %d, %d, %s)",
            $user_id,
            $post_id,
            $blog_id,
            $location,
            $event,
            $description,
            $type,
            $coins,
            $clovers,
            $payment,
            $timestamp
        )
    );

    if ($result === false) {
        error_log($wpdb->last_error);
    }else{
        loopis_update_coins($user_id, $coins, $clovers);
    }
}



 /**
 * Adds a ledger entry for a reward event
 *
 * @return void
 */
function loopis_ledger_add_reward($user_id, $options=[]){
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }
    global $wpdb;

    $defaults = [
        'type' => 'star',
        'description' => 'Du är en ⭐️',
        'location' => 'digital',
        'blog_id' => 1,
        'coins' => 1,
        'clovers'=>0,
    ];

    $type = $wpdb->esc_like((string)($options['type'] ?? $defaults['type']));
    $description = $wpdb->esc_like((string)($options['description'] ?? $defaults['description']));
    $location = $wpdb->esc_like((string)($options['location'] ?? $defaults['location']));
    $coins = isset($options['coins']) ? (int)$options['coins'] : (int)$defaults['coins'];
    $clovers = isset($options['clovers']) ? (int)$options['clovers'] : (int)$defaults['clovers'];
    $blog_id = isset($options['blog_id']) ? (int)$options['blog_id'] : (int)$defaults['blog_id'];
    $event= 'reward';
    $post_id=0;
    $timestamp = current_time('Y-m-d H:i:s');

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $result = $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO {$table_name}
            (user_id, post_id, blog_id, location, event, description, type, coins, clover, timestamp)
            VALUES ( %d, %d, %d, %s, %s, %s, %s, %d, %d, %s)",
            $user_id,
            $post_id,
            $blog_id,
            $location,
            $event,
            $description,
            $type,
            $coins,
            $clovers,
            $timestamp
        )
    );

    if ($result === false) {
        error_log($wpdb->last_error);
    }else{
        loopis_update_coins($user_id, $coins, $clovers);
    }
}

 /**
 *  ======================= COINS ======================
 */

/**
 * Coin update helper 
 * 
 * @return void
*/
function loopis_update_coins($user_id, $coins=0,$clovers=0){
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }
    $coins = (int) $coins;
    $clovers = (int) $clovers;
    $current_balance = (int) get_user_meta($user_id, 'loopis_balance', true);
    $current_clovers  = (int) get_user_meta($user_id, 'loopis_clovers', true);
    $new_clovers = $current_clovers + $clovers;
    $overflow = floor($new_clovers/10)-floor(($current_clovers)/10); // calculates difference in 10s
    $new_balance = $current_balance + $coins + $overflow;
    update_user_meta($user_id, 'loopis_clovers', $new_clovers);
    update_user_meta($user_id, 'loopis_balance', $new_balance);
}

/**
 *  Recounts user coins 
 *
 * @return array Associative array of user [(int) balance, (int) clovers]
 */
function loopis_ledger_recount_user($user_id){
    global $wpdb;
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $balances = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT COALESCE(SUM(coins),0) as balance, COALESCE(SUM(clover),0) as clovers FROM {$table_name} WHERE `user_id`= %d",
            $user_id
        ), ARRAY_A
    );
    if (!is_array($balances)){
        $balances = ['balance' => 0, 'clovers' => 0];
    }
    update_user_meta($user_id, 'loopis_clovers', $balances['clovers']);
    update_user_meta($user_id, 'loopis_balance', ($balances['balance']+ floor($balances['clovers']/10)));
    return $balances;
}

 /**
 *  ======================= MISC ======================
 */

 function loopis_ledger_type_output($type){
    $output_for=[
        'survey'=>'Enkätsvar',
        'google_review'=>'Googlerecension',
        'mynt'=>'Mynt',
        'medlemskap'=>'Medlemskap',
        'poster_garbage'=>'Lapp i soprum',
        'top_user'=>'Mest aktiv',
        'poster_storage'=>'Lapp i förråd',
    ];
    return $output_for[$type];
 }