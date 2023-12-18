<?php

require_once 'vendor/autoload.php';
require_once 'vendor/payjp/payjp-php/init.php';

use Payjp\Payjp;
use Payjp\Charge;
use Payjp\Customer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    if ($data) {
        $result = $data->result;
        $charge_id = $data->charge_id;

        if ($result === "success") {

            try {
                echo "Payment successful for charge ID: " . $charge_id;

                if (class_exists('\Payjp\Payjp')) {
                    \Payjp\Payjp::setApiKey("sk_test_03599cbf212b2b23d85589b2");
                    $ch = \Payjp\Charge::retrieve($charge_id);
                    $tdsFinishResult = $ch->tdsFinish();

                    // echo "Result of tdsFinish: " . json_encode($tdsFinishResult);         
                } else {
                    echo "Payjp library not found.";
                }
            } catch (Exception $error) {
                echo $error->getMessage();
            }
            
        } elseif ($result === "error") {
            $error_message = $data->error_message;
            echo "Error processing payment for charge ID " . $charge_id . ": " . $error_message;
        } else {
            echo "Unknown result for charge ID " . $charge_id;
        }
    } else {
        http_response_code(400);
        echo "Invalid data received.";
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
