<?php
require __DIR__.'/db_connect.php';

if (isset($_GET['img_id'])){
    $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
    $img = get_binimage_from_id($img_id);
} else {
    return "No image id provided";
}

$imageDataDecoded = base64_decode($img);

$image = new Imagick();
$image->readImageBlob($imageDataDecoded);
// $image->setImageFormat('png');

$image -> setImageType (2);
$image -> borderImage('black', 5, 5);

header('Content-type: ' . 'image/png');
echo $image;

?>
