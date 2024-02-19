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
    <!-- <?php
      // echo '<h1>Hello this world!</h1>';
      // echo '<p>This page uses PHP version '
      //     . phpversion()
      //     . '.</p>';
      // include('db_connect.php');
    ?>  -->
    
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

    <main class="container mt-2 border rounded border-dark">
        <section class="row ">
            <div class="col-md-12 mt-1 pt-1 fw-light">
                <h5>Photo Editor</h5>
            </div>
        </section >

        <section class="row py-2">
            
                <figcaption class="col-7 d-block">
                    <div class="card">
                        <img
                            src=
                            <?php
                            if (isset($_GET['img_id']) && isset($_GET['filter'])){
                                $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
                                $filter = validate_input(string_sanitization($_GET['filter'] ), '/^\w+$/', "invalid-filter");
                                echo "'./lib/edited_image.php?filter=". $filter."&img_id=". $img_id."'";
                                
                            } else if (isset($_GET['img_id'])){
                                $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
                                echo "'./lib/image.php?img_id=". $img_id."'";
                            } else {
                                echo "No image id provided";
                            }
                            ?>
                            class="card-img-top"
                        />
                        <div class="card-body container py-2">
                            <div class="row">
                                <span class="card-title col-8 align-middle">Card title </span>
                                <div class="col-2">
                                    <form class="row px-1">
                                        <a href=
                                        <?php
                                            echo "'./lib/process.php?action=delete_image&img_id=".$img_id."'";
                                        ?>
                                         class="btn btn-sm btn-danger">Discard</a>
                                    </form>
                                </div>
                                <div class="col-2">
                                    <form class="row px-1">
                                        <a href=
                                        <?php

                                        if (isset($_GET['img_id']) && isset($_GET['filter'])){
                                            echo "'./lib/process.php?action=finish_edit&filter=". $filter."&img_id=".$img_id."'";
                                        } else 
                                            echo "'./index.php'";
                                        
                                        ?>
                                        class="btn btn-sm btn-success">Finish</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                      </div>
                    </figcaption>
                    <form class="col-5" method="POST" action="./lib/process.php?action=show_request">
                        <div class="row m-1"> 
                            <span class="align-middle col-2">Filter: </span>
                            <div class="col-5"><div class="row px-1">
                                <a href= <?php echo "'./photo_editor.php?filter=border&img_id=". $img_id."'"; ?>class="btn btn-sm btn-primary"> Add Border</a>
                            </div></div>
                            <div class="col-5"><div class="row px-1">
                                <a href= <?php echo "'./photo_editor.php?filter=blackNwhite&img_id=". $img_id."'"; ?>class="btn btn-sm btn-primary"> Black & White</a>
                            </div></div>
                        </div>
                    </form>

              </div>
        </section >
  </body>
</html>