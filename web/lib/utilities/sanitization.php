<?php 

function int_sanitization($input){
    $input = (int)htmlspecialchars($input);
    return filter_var($input, FILTER_VALIDATE_INT);
}

function float_sanitization($input){
    $input = (float)htmlspecialchars($input);
    return filter_var($input, FILTER_VALIDATE_FLOAT);
}

function string_sanitization($input){
    $input = (String)htmlspecialchars($input);
    return filter_var($input, FILTER_SANITIZE_STRING);
}

function email_sanitization($input){
    $input = (String)htmlspecialchars($input);
    return filter_var($input, FILTER_VALIDATE_EMAIL);
}

function password_sanitization($input){
    $input = (String)htmlspecialchars($input);
    return filter_var($input, FILTER_SANITIZE_EMAIL);
}

?>