<?php
require __DIR__.'/db_connect.php';

if (isset($_GET['img_id']) || isset($_GET['filter'])){
    $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
    $filter = validate_input(int_sanitization($_GET['filter'] ), '/^\d+$/', "invalid-filter");
    $img = get_binimage_from_id($img_id);
} else {
    return "No image id or filter provided";
}

$imageDataDecoded = base64_decode($img);

$image = new Imagick();
$image->readImageBlob($imageDataDecoded);
// $image->setImageFormat('png');
if ($filter == "border"){
    $image -> borderImage('black', 5, 5);
} else if ($filter == "blackNwhite"){
    $image -> setImageType (2);
}
header('Content-type: ' . 'image/png');
echo $image;

?>
