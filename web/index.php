<?php
        require __DIR__.'/lib/db_connect.php';
        $auth = is_auth();
?> 

<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1"/>
      <link rel="icon" type="image/x-icon" href="Resources/Instagram_icon.png"/>
      <title>CSCI4140-Web Instagram-1155143402</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
  </head>
  <body>
    <style>
         /* Debug: https://github.com/twbs/bootstrap/issues/33757 */
        :root {
            scroll-behavior: auto !important;
        }
    </style>
    <!--  /////////////////////////  Header  /////////////////////////  -->
    <nav class="navbar navbar-light bg-light">
        <div class="container">
        <a class="navbar-brand fw-bold fst-italic" href="./index.php">
              <img src="Resources/Instagram_icon.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              Web Instagram
            </a>
            <form class="d-flex" method="POST" action="./lib/process.php?action=logout">
                <button class="btn btn-outline-light text-dark" disabled>
                    <?php
                        
                        if ($auth){
                            if (is_admin($auth)){
                                echo $auth."</button>";
                                echo '<a href="./initialization.php" class="btn btn-danger"> Init </a>';
                                echo '<div> &nbsp; </div>';
                            }
                            else 
                                echo $auth."</button>"; 
                            echo '<button type="submit" class="btn btn-outline-success">Exit</button>';
                        } else {
                            echo "Guest";
                            echo '</button><a href="./login.php" class="btn btn-outline-primary">Login</a>';
                        }
                    ?>
                
            </form>
        </div>
    </nav>

    <!--  /////////////////////////  Main  /////////////////////////  -->
    <main class="container mt-2 border rounded border-dark">
        <section class="row ">
            <div class="col-md-12 mt-1 pt-1 fw-light">
                <h5>Photo Gallery</h5>
            </div>
        </section >

        <section class="row">
            <div class="photo-frame row mb-2" style="margin-left: 0px; margin-right: 0px">
                <?php
                try {
                    if (isset($_GET['start'])){
                        $start = validate_input(int_sanitization($_GET['start']) , '/^\d+$/', "invalid-start");
                    } else {
                        $start = 0;
                    }
                    if (isset($_GET['len'])){
                        $length = validate_input(int_sanitization($_GET['len']) , '/^\d+$/', "invalid-len");
                    } else {
                        $length = 8;
                    }
                    $username =  $auth;
                    $userid = get_userid_from_username($username);
                    if ($auth){
                        $res = (array) fetch_ten_image_auth($start, $length, $userid);
                    }
                    else 
                        $res = (array) fetch_ten_public_image($start, $length);
                    $res = (array) array_slice($res, $start, $length);
                        foreach ($res as $image){
                            $flag = $image["flag"];
                            if ((int) $flag == 0){
                                $flag = "Private image";
                            }
                            else {
                                $flag = "Public image";
                            }
                            echo '<figcaption class="col-3 d-block mb-2">';
                            echo '    <div class="card w-100 h-100">';
                            echo '        <img src="./lib/image.php?img_id='.$image['img_id'].'" class="card-img-top h-100" alt="Sunset over the Sea"/>';
                            echo '        <div class="card-body container py-2">';
                            echo '            <div class="row">';
                            echo '                <span class="card-title col-8">'.$flag."-".$image['img_id'].'</span>';
                            // echo '                <a href="'."./photo_editor.php?img_id=". $image['img_id'].'"class="btn btn-sm btn-secondary col-4">Edit</a>';
                            echo '            </div>';
                
                            echo '        </div>';
                            echo '    </div>';
                            echo '</figcaption>';
                        }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo '    <strong>Error!</strong> '.$e->getMessage();
                    echo '</div>';
                }
            ?>  






        </section >

        <section class="row" >
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <li class="page-item">
                        <a href=
                        <?php 
                            if ($auth){
                                $count = count_image($auth);
                            } else {
                                $count = count_image("guest");
                            }
                            $previous = $start-8;
                            if ($previous < 0){
                                $previous = 0;
                            }
                            if ($start == 0){
                                $previous = 0;
                            }
                            echo '"./index.php?start='.($previous).'"';
                            echo 'class="page-link ';
                            if ((int) $start-8 < 0){
                                echo 'disabled';
                            }
                            echo '"';
                        ?>
                        tabindex="-1" aria-disabled="true">&laquo; Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link disabled" href="#" >
                        <?php 
                            $end = $start+8;
                            if ($end >= $count){
                                $end = $count;
                            }
                            echo $start+1 . " - " . $end . " of ";
                            echo $count;

                        ?>
                    </a></li>
                    <li class="page-item">
                        <a href=
                        <?php 
                            $next = $start+ 8;
                            if ($next >= $count){
                                $next = $start;
                            }
                            echo '"./index.php?start='.($next).'"'; 
                            echo 'class="page-link ';
                            if ((int) $start+8 >= (int) $count){
                                echo 'disabled';
                            }
                            echo '"';
                        ?>
                        >Next &raquo;</a>
                    </li>
                </ul>
            </nav>
        </section >
    </main>

    <!--  /////////////////////////  Upload file  /////////////////////////  -->
    <section class="container mt-2 bg-light p-0 mt-2 border border-dark">
        <nav class="navbar navbar-light bg-light">
            <div class="container">
                <?php
                    if ($auth){
                        echo '<a class="navbar-brand" href="#"></a>';
                        echo '<form  method="post" action="./lib/process.php?action=upload_image" enctype="multipart/form-data" class="d-flex">';
                        echo '      <div class="input-group">';
                        echo '          <label for="inputGroupFile" class="input-group-text bg-light border-light">Upload Photo:</label>';
                        echo '          <input type="file" name="file" style="opacity: 0; width: 1px; height: 1px; overflow: hidden;" required class="form-control" style id="inputGroupFile" accept="image/*">';
                        echo '          <label class="input-group-text btn btn-info text-white" for="inputGroupFile">Upload</label>  ';
                        echo '      </div>';
                        echo '      <button type="submit" class="btn btn-sm btn-primary ms-2">Submit</button>';
                        echo '      <div class="form-check form-switch ms-2">';
                        echo '           <input class="form-check-input" name="isPublic" type="checkbox" id="flexSwitchCheckDefault" checked>';
                        echo '          <div class="p-0"> Public </div>';
                        echo '      </div>';
                        echo '</form>';
                    } else {
                        echo '<div class="row"> <span class="text-center text-secondary">Please login to upload photos</span></div>';
                    }
                ?>

            </div>
        </nav>
    </section>
  </body>
</html>
