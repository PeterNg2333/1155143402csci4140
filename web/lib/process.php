<?php
include_once('db_connect.php');
include_once('utilities/sanitization.php');
include_once('utilities/validation.php');

header('Content-Type: text/html; charset=UTF-8');
$process_action = string_sanitization($_REQUEST['action']);

if (empty($process_action) 
    || !preg_match('/^\w+$/', $process_action)
    || !is_form_action($process_action)
    ){
        echo json_encode(array('failed'=>'undefined'));
        exit();
    }else{
        try{
            if (($return_value = call_user_func('csci4140_' . $process_action)) === false) {
                if ($conn && $conn->errorCode()) {
                    echo json_encode(array('failed'=>'error-db'));
                }      
            } else {
                echo string_sanitization($return_value);
            }
        } catch(Exception $e) {
            echo json_encode(array('failed' => $e->getMessage()));
        }
    }

exit();
?>  