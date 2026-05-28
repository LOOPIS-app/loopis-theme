<?php

function loopis_ledger_remove($where){
    global $wpdb;

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
        'payment_type' => '%s',
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

    $uids = $wpdb->get_col(
        $wpdb->prepare("SELECT DISTINCT user_id FROM {$table_name} WHERE " . implode(' AND ', $clauses),
            $values)
        );


    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$table_name} WHERE " . implode(' AND ', $clauses),
            $values)
        );

    foreach($uids as $uid){
        loopis_ledger_recount_user($uid);
    } 
    return;
}

function loopis_ledger_recount_user($uid){
    global $wpdb;

    if (!is_int($uid)){
        return;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $balances = $wpdb->get_row(
        $wpdb->prepare(
                "SELECT SUM(coins) as balance , SUM(clover) as cl_balance FROM {$table_name} WHERE `user_id`= %d",
            $uid
        ), ARRAY_A
    );
    update_user_meta($uid, 'loopis_clovers', $balances['cl_balance']);
    update_user_meta($uid, 'loopis_balance', ($balances['balance']+ floor($balances['cl_balance']/10)));
    return $balances;
}


function loopis_ledger_user_event_counts($uid){
        global $wpdb;

    $uid = (int) $uid;
    if ( $uid <= 0 ) {
        return null;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';

    $sql = $wpdb->prepare(
        "SELECT
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_submitted,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_given,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS fetched,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_booked,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS regret,
            SUM(CASE WHEN event = %s THEN 1 ELSE 0 END) AS count_deleted
         FROM {$table_name}
         WHERE user_id = %d",
        'Submitted', 'Given', 'Fetched', 'Booked', 'Regret', 'Removed', $uid
    );

    return $wpdb->get_row( $sql, ARRAY_A );
}

function loopis_ledger_user_payments($uid){
        global $wpdb;

    if (!is_int($uid)){
        return;
    }

    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $payments = $wpdb->get_row(
        $wpdb->prepare(
                "SELECT payment, coins, timestamp, payment_type   
                FROM {$table_name} 
                WHERE `user_id`= %d AND `event` = `Payment`",
            $uid
        ), ARRAY_A
    );

    return $payments;
}



function loopis_ledger_add($event, $uid, $bid=0, $pid=0, $timestamp=Null, $payment=NULL, $payment_type=NULL, $coin_amount=NULL){
    global $wpdb;
    $current_balance = (int) get_user_meta($uid, 'loopis_balance', true);
    $current_clover  = (int) get_user_meta($uid, 'loopis_clovers', true);
    $ts = $timestamp ?? current_time('mysql');
    switch ($event) {
        case 'Submitted':
            $coins = 0;            
            $clover = 1;
            break;
        case 'Given':
            $coins = 1;
            $clover = 0;
            break;
        case 'Fetched':
            $coins = 0;            
            $clover = 1;
            break;
        case 'Booked':
            $coins = -1;
            $clover = 0;
            break;
        case 'Removed':
            $coins = 0;
            $clover = 0;
            break;
        case 'Regret':
            $coins = 1;
            $clover = 0;
            break;
        case 'Payment':
            $coins = ($coin_amount !== NULL) ? $coin_amount : 5; 
            $clover = 0;
            break;
        default:
            return false;
    }
    // unless we add a -clover option this logic is enough else fix with a -1 to balance for (clover less than 0) and (current_clover%10 +clover less than 0)
    $current_balance += $coins;
    $current_clover += $clover;
    if (($current_clover%10 === 0)&& $clover!==0){
        $current_balance += 1;
    }
    $table_name = $wpdb->base_prefix . 'loopis_ledger';
    $wpdb->query(
        $wpdb->prepare(
            "INSERT INTO {$table_name}
            (user_id, post_id, blog_id, timestamp, coins, event, payment_type, payment, clover)
            VALUES ( %d, %d, %d, %s, %d, %s, %s, %d, %d)",
            $uid,
            $pid,
            $bid,
            $ts,
            $coins,
            $event,
            $payment_type,
            $payment,
            $clover
            )
        );

    update_user_meta($uid, 'loopis_clovers', $current_clover);
    update_user_meta($uid, 'loopis_balance', $current_balance);
}

