<?php 


function validate_input($username, $reg, $error_message){
    if (!preg_match('/[^$@\'&"=|]+/', $username))
        throw new Exception($error_message);
    else 
        return $username;
}
function is_form_action($action){
    if ($action == "create_pd" 
        || $action == "login" 
        || $action == "logout" 
        || $action == "fetch_ten_image" 
        || $action == "retrieve_image"
        || $action == "create_image"
        || $action == "save_image" 
        || $action == "delete_image" 
        || $action == "delete_all"
        || $action == "db_test"
        || $action == "show_request"
        || $action == "check_auth"
        ){
        return true;
    }
}

?>