<?php 
function int_sanitization($input){
    return (int)$input;
}

function float_sanitization($input){
    return (float)$input;
}

function string_sanitization($input){
    return (string) htmlspecialchars($input, ENT_QUOTES);
}

function email_sanitization($input){
    return (string) filter_var($input, FILTER_VALIDATE_EMAIL);
}
?>
