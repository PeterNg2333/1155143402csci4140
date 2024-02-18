<?php
require __DIR__.'/lib/db_connect.php';

$imageData = retrieve_image();
$imageDataDecoded = base64_decode($imageData);

$image = new Imagick();
$image->readImageBlob($imageDataDecoded);
// $image->setImageFormat('png');

$image -> blurImage(5, 3);
$image -> borderImage('black', 5, 5);

header('Content-type: ' . 'image/png');
echo $image;

?>
