<?php
        require __DIR__.'/lib/db_connect.php';
        $start = (int) $_GET['start'] ?? 0;
        $length = (int) $_GET['len'] ?? 1;
        // $username = is_auth();
        echo count_image("admin01");
        echo json_encode(fetch_ten_public_image($start, $length));
?> 