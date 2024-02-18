<?php
require __DIR__.'/lib/db_connect.php';

header('Content-type: ' . 'image/png');

$imageData = retrieve_image();
$imageDataDecoded = base64_decode($imageData);
$imageDataDecoded = imagecreatefromstring($imageDataDecoded);

$image = new Imagick();
$image->setImageFormat('png');
$image->readImageBlob($imageData);

$image -> blurImage(5, 3);
$image -> borderImage('black', 5, 5);
echo $image;

?>