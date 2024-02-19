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
    <!--  /////////////////////////  Header  /////////////////////////  -->
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold fst-italic" href="#">
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
                        $start = (int) $_GET['start'];
                    } else {
                        $start = 0;
                    }
                    if (isset($_GET['len'])){
                        $length = (int) $_GET['len'];
                    } else {
                        $length = 9;
                    }
                    $username =  $auth;
                    $userid = get_userid_from_username($username);
                    if ($auth){
                        $images = (array) fetch_ten_image_auth($start, $length, $username);
                    }
                    else 
                        $images = (array) fetch_ten_public_image($start, $length);

                    
                    foreach ($images as $image){
                        if ($image["flag"]== 1){
                            $creation_source = "Public image";
                        }
                        else {
                            $creation_source = "Private image";
                        }
                        $img_id = $image["img_id"];
                        echo '<figcaption class="col-3 d-block">';
                        echo '    <div class="card">';
                        echo '        <img src="./lib/image.php?img_id='.$img_id.'" class="card-img-top" alt="Sunset over the Sea"/>';
                        echo '        <div class="card-body container py-2">';
                        echo '            <div class="row">';
                        echo '                <span class="card-title col-8">'.$creation_source."-".$img_id.'</span>';
                        echo '                <a href="'."./lib/image.php?img_id=". $img_id.'"class="btn btn-sm btn-secondary col-4">Edit</a>';
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


                <!-- <figcaption class="col-3 d-block">
                    <div class="card">
                        <img
                            src="https://mdbcdn.b-cdn.net/img/new/standard/nature/183.webp"
                            class="card-img-top"
                            alt="Sunset over the Sea"
                        />
                        <div class="card-body container py-2">
                            <div class="row">
                                <span class="card-title col-8">Card title </span>
                                <a href="#!" class="btn btn-sm btn-secondary col-4">Edit</a>
                            </div>
                        </div>
                    </div>
                </figcaption> -->

                <!-- <figcaption class="col-3 d-block">
                    <div class="card">
                        <img
                            src="./lib/image.php?img_id=1"
                            class="card-img-top"
                            alt="Sunset over the Sea"
                        />
                        <div class="card-body container py-2">
                            <div class="row">
                                <span class="card-title col-8">Card title </span>
                                <a href="#!" class="btn btn-sm btn-secondary col-4">Edit</a>
                            </div>
                        </div>
                      </div>
                  </figcaption> -->




        </section >

        <section class="row" >
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <li class="page-item">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo; Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link disabled" href="#" >1 of 18</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next &raquo;</a>
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
                        echo '          <input type="file" name="file" class="form-control d-none" id="inputGroupFile" accept="image/*">';
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
