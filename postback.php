<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

error_log(print_r($_GET, true)); 
file_put_contents('postback_log.txt', print_r($_GET, true), FILE_APPEND);

// Get variables from OGAds postback
$user_id = $_GET['user_id'];  
$offer_id = $_GET['offer_id']; 
$payout = $_GET['payout'];        
$secret_key = 'KYRvAM7a1abDQQ5nX2cKKuWIF5oGPwtz'; // Secret key for security

if (isset($_GET['key']) && $_GET['key'] !== $secret_key) {
    die('Invalid request'); 
}

error_log(print_r($_GET, true));

if (isset($user_id) && isset($offer_id) && isset($payout)) {

    // Multiply the payout by 100 to get the coin value
    $coins = $payout * 26;

    if (get_userdata($user_id) !== false) {
        
        if (function_exists('mycred_add')) {
            // Add coins to the user via myCred
            mycred_add(
                'completed_offer',        
                $user_id,                 
                $coins,                   
                'Completed offer ID ' . $offer_id . ' with payout of ' . $payout
            );

            // Return success response to OGAds
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