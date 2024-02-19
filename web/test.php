<?php
        require __DIR__.'/lib/db_connect.php';
        $start = $_GET['start'] ?? 0;
        $length = $_GET['len'] ?? 1;
        echo json_encode(fetch_ten_public_image($start, $length));
?> 