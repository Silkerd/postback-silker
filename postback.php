<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

const AUTHORIZED_IPS = [
    "50.18.215.132", "50.18.215.133", "50.18.215.134", "50.18.215.135",
    "107.21.28.235", "107.21.36.214", "107.23.2.46", "107.23.2.50",
    "54.64.15.176", "54.64.21.195", "54.94.179.76", "54.207.34.180",
    "54.207.36.218", "54.246.166.8", "54.246.166.9", "54.246.166.12",
    "54.246.166.17", "209.170.120.242", "209.170.120.243", "209.170.120.244"
];

$client_ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($client_ip, AUTHORIZED_IPS)) {
    echo json_encode([
        "success" => false,
        "error" => "Unauthorized access."
    ]);
    die();
}

error_log(print_r($_GET, true)); 
file_put_contents('postback_log.txt', print_r($_GET, true), FILE_APPEND);

$user_id = $_GET['user_id']; 
$offer_id = $_GET['offer_id']; 
$payout = $_GET['payout'];   

if (isset($user_id) && isset($offer_id) && isset($payout)) {

    $coins = $payout * 26;

    if (get_userdata($user_id) !== false) {
        
        if (function_exists('mycred_add')) {
            mycred_add(
                'completed_offer',        
                $user_id,                 
                $coins,                   
                'Completed offer ID ' . $offer_id . ' with payout of ' . $payout 
            );

            echo "OK"; 
        } else {
            echo "myCred plugin not installed or active.";
        }
    } else {
        echo "Invalid user ID.";
    }
} else {
    echo "ERROR: Missing parameters.";
}
