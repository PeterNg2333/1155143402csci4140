<?php
require __DIR__.'/db_connect.php';


if (isset($_GET['img_id'])){
    $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
    $img = get_binimage_from_id($img_id);
    header('Content-type: ' . $result['filetype']);
    echo base64_decode($img);
    exit();
} else {
    return "No image id provided";
}

?>