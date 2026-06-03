<?php

function loopis_ledger_remove($where){
    global $wpdb;
    if (!current_user_can('manager')){
        return;
    }
    $table_name = $wpdb->base_prefix . 'loopis_ledger';

    if (!isset($where)){
        return;
    }
    if (!is_array($where)){
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
        if (!in_array($key, $allowed_keys)){
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


    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$table_name} WHERE " . implode(' AND ', $clauses),
            $values)
        );

    //recount affected users
    if (!empty($user_ids)){
        foreach($user_ids as $user_id){
            $user_id = (int) $user_id;
            loopis_ledger_recount_user($user_id);
        } 
    }
    return;
}

function loopis_ledger_recount_user($user_id){
    global $wpdb;
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $balances = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT COALESCE(SUM(coins),0) as balance, COALESCE(SUM(clover),0) as cl_balance FROM {$table_name} WHERE `user_id`= %d",
            $user_id
        ), ARRAY_A
    );
    if (!is_array($balances)){
        $balances = ['balance' => 0, 'cl_balance' => 0];
    }
    update_user_meta($user_id, 'loopis_clovers', $balances['cl_balance']);
    update_user_meta($user_id, 'loopis_balance', ($balances['balance']+ floor($balances['cl_balance']/10)));
    return $balances;
}


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

function loopis_ledger_user_payments($user_id){
        global $wpdb;

    $user_id = (int) $user_id;
    if ( $user_id <= 0 ) {
        return null;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $payments = $wpdb->get_results(
        $wpdb->prepare("SELECT payment, type, description, coins, timestamp   
            FROM {$table_name} 
            WHERE `user_id`= %d AND `event` = 'payment'
            ORDER BY timestamp ASC",
            $user_id
        ), ARRAY_A
    );

    return $payments;
}

function loopis_ledger_user_rewards($user_id){
    global $wpdb;

    $user_id = (int) $user_id;
    if ( $user_id <= 0 ) {
        return null;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $payments = $wpdb->get_results(
        $wpdb->prepare(
                "SELECT type, description, coins, timestamp   
                FROM {$table_name} 
                WHERE `user_id`= %d AND `event` = 'reward'",
            $user_id
        ), ARRAY_A
    );

    return $payments;
}

function loopis_ledger_economy($user_id){
    $events = loopis_ledger_user_event_counts($user_id);
    $payments = loopis_ledger_user_payments($user_id);
    $rewards = loopis_ledger_user_rewards($user_id);
    $clovers = (int) get_user_meta($user_id, 'loopis_clovers', true);
    $coins = (int) get_user_meta($user_id, 'loopis_balance', true);
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
        $join_date = $payments[0]['timestamp'];
    }
    $stars = 0;
    $star_coins = 0;
    if (!empty($rewards)){
        foreach($rewards as $reward){
            $stars++;
            $star_coins = $reward['coins'];
        }
    }
    

    return array(
        //
        'payments_membership' => $payments_membership,
        'payments_coins' => $payments_coins,
        'membership_coins' => $membership_coins,
        'bought_coins' => $bought_coins,
        'joined_date' => $join_date, //membership date
        //event count
        'count_given' => $count_given,
        'count_booked' => $count_booked,
        'count_submitted' => $count_submitted,
        'count_deleted' => $count_deleted,
        //rewards 
        'stars' => $stars,
        'star_coins' => $star_coins,
        //balances,
        'clovers' => $clovers,
        'clover_coins' => floor($clovers/10),
        'coins' => $coins,
        );

}



function loopis_ledger_add_post($event, $user_id, $blog_id=0, $post_id,$location,){
    if (!is_int($user_id)||!is_int($post_id)||!is_int($blog_id)||!is_string($event)){
        return;
    }

    global $wpdb;
    switch ($event) {
        case 'submitted':
            $coins = 0;            
            $clovers = 1;
            $ts = date('Y-m-d H:i:s',  strtotime(get_post_field('post_date', $post_id)));
            break;
        case 'given':
            $coins = 1;
            $clover = 0;
            $ts = date('Y-m-d H:i:s',  strtotime(get_post_meta($post_id, 'fetch_date', true)));
            break;
        case 'fetched':
            $coins = 0;            
            $clovers = 1;
            $ts = date('Y-m-d H:i:s',  strtotime(get_post_meta($post_id, 'fetch_date', true)));
            break;
        case 'booked':
            $coins = -1;
            $clovers = 0;
            $ts = date('Y-m-d H:i:s',  strtotime(get_post_meta($post_id, 'book_date', true)));
            break;
        case 'removed':
            $coins = 0;
            $clovers = 0;
            $ts = date('Y-m-d H:i:s',  strtotime(get_post_meta($post_id, 'removed_date', true)));
            break;
        case 'regret':
            $coins = 1;
            $clovers = 0;
            $ts = current_time('Y-m-d H:i:s');
            break;
        default:
            return false;
    }
    // unless we add a -clover option this logic is enough else fix with a -1 to balance for (clover less than 0) and (current_clover%10 +clover less than 0)
    loopis_update_coins($user_id, $coins, $clovers);
    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $wpdb->query(
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
            $ts
        )
    );
}



function loopis_ledger_add_payment($user_id,$options=[]){
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }
    global $wpdb;
    $defaults = [
        'type' => 'mynt',
        'description' => 'stripe',
        'location' => '',
        'blog_id' => 0,
        'payment' => 50,
        'coins' => 5,
        'clovers'=>0,
    ];
    $type = $options['type'] ?? $defaults['type'];
    $description = $options['description'] ?? $defaults['description'];
    $location = $options['location'] ?? $defaults['location'];
    $payment = isset($options['payment']) ? (int)$options['payment'] : (int)$defaults['payment'];
    $coins = isset($options['coins']) ? (int)$options['coins'] : (int)$defaults['coins'];
    $clovers = isset($options['clovers']) ? (int)$options['clovers'] : (int)$defaults['clovers'];
    $blog_id = isset($options['blog_id']) ? (int)$options['blog_id'] : (int)$defaults['blog_id'];
    $event = 'payment'; 
    $post_id=0;
    $timestamp = current_time();

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $wpdb->query(
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
            $ts
        )
    );

    loopis_update_coins($user_id,$coins,$clovers);
}



function loopis_ledger_add_reward($user_id, $options=[]){
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }
    global $wpdb;

    $defaults = [
        'type' => 'star',
        'description' => 'Du är en ⭐️',
        'location' => '',
        'blog_id' => 0,
        'coins' => 5,
        'clovers'=>0,
    ];

    $type = $options['type'] ?? $defaults['type'];
    $description = $options['description'] ?? $defaults['description'];
    $location = $options['location'] ?? $defaults['location'];
    $coins = isset($options['coins']) ? (int)$options['coins'] : (int)$defaults['coins'];
    $clovers = isset($options['clovers']) ? (int)$options['clovers'] : (int)$defaults['clovers'];
    $blog_id = isset($options['blog_id']) ? (int)$options['blog_id'] : (int)$defaults['blog_id'];
    $event= 'reward';
    $post_id=0;

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $wpdb->query(
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
            $ts
        )
    );

    loopis_update_coins($user_id,$coins,$clovers);
}



function loopis_update_coins($user_id, $coins=0,$clovers=0){
    $user_id = (int) $user_id;
    if ($user_id <= 0){
        return;
    }
    $coins = (int) $coins;
    $clover = (int) $clovers;
    $current_balance = (int) get_user_meta($user_id, 'loopis_balance', true);
    $current_clover  = (int) get_user_meta($user_id, 'loopis_clovers', true);
    $new_clovers += $current_clovers + $clovers;
    $overflow = floor($current_clovers/10)-floor(($new_clovers)/10);
    $new_balance = $current_balance + $coins + $overflow;
    update_user_meta($user_id, 'loopis_clovers', $new_clover);
    update_user_meta($user_id, 'loopis_balance', $new_balance);
}
