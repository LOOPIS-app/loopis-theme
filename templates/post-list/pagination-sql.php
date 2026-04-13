<?php

if (!defined('ABSPATH')) {
    exit;
}

function loopis_GET_pagenum($max_pages){
    $pagenum = (int) ($_GET['pagenum'] ?? 1);
    return max(1, min($pagenum, $max_pages));
}

function loopis_get_range($max, $pagenum){
    if ($pagenum<=5){
        if ($pagenum>$max-5){
            $range = range(1,$max);
        }else{
            $range = range(1,$pagenum+2);
            $range[] = '...';
            $range[] = $max-1;
            $range[] = $max;
        }
    }else{
        if ($pagenum>$max-5){
            $range[] = 1;
            $range[] = 2;
            $range[] = '...';
            $range = array_merge($range, range($pagenum-2,$max));
        }else{
            $range[] = 1;
            //$range[] = 2;
            $range[] = '...';
            $range = array_merge($range, range($pagenum-2,$pagenum+2));
            $range[] = '...';
            //$range[] = $max-1;
            $range[] = $max;
        }
    }
    return $range;
}


function loopis_sql_pagination($max_pages){
    if (!isset($max_pages)){
        return;
    } 
    $max_pages=intval($max_pages);
    if(($max_pages)<1){
        $max_pages=1;
    }
    $pagenum = loopis_GET_pagenum($max_pages);
    $range = loopis_get_range($max_pages, $pagenum);
    $folder = home_url(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    //The pagination template
    echo '<div id="post-pagination">';
    foreach ($range as $value) {
        $args = $_GET; //maybe overkill but good safety to have this inside forloop
        if ($value===1){
            if (!($pagenum===1)){
                $arrow = $pagenum-1;
                $args['pagenum'] = $arrow;
                $url = esc_url(add_query_arg($args, $folder));
                echo "<a class='prev page-numbers' href='". $url ."'>&lt;</a>";
                $args['pagenum'] = $value;
                $url = esc_url(add_query_arg($args, $folder));
                echo "<a class='page-numbers' href='". $url ."'>1</a>";
            }else{
                echo "<span aria-current='page' class='page-numbers current'>{$value}</span>";
            }
        }elseif($value===$pagenum){
            echo "<span aria-current='page' class='page-numbers current'>{$value}</span>";
        }elseif($value===$max_pages){
            $arrow = $pagenum+1;         
            $args['pagenum'] = $value;
            $url = esc_url(add_query_arg($args, $folder));
            echo "<a class='page-numbers' href='". $url ."'>{$value}</a>";
            $args['pagenum'] = $arrow;
            $url = esc_url(add_query_arg($args, $folder));
            echo "<a class='next page-numbers' href='". $url ."'>&gt;</a>";
        }elseif(is_string($value)){
            echo "<span aria-current='page' class='page-numbers current'>...</span>";
        }else{
            $args['pagenum'] = $value;
            $url = esc_url(add_query_arg($args, $folder));
            echo "<a class='page-numbers' href='". $url ."'>{$value}</a>";
        }
    }
    echo '</div>';
}