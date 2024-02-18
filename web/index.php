<?php
        require __DIR__.'/lib/db_connect.php';
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
                        $auth = is_auth();
                        if ($auth){
                            if (is_admin($auth)){
                                echo "Admin</button>";
                                echo '<a href="./initialization.php" class="btn btn-danger"> Init </a>';
                            }
                            else 
                                echo "Normal User</button>"; 
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
                <figcaption class="col-3 d-block">
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
                  </figcaption>
              </div>
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
                <a class="navbar-brand" href="#"></a>
                <form action="upload.php" method="post" action="./lib/process.php?action=show_request" class="d-flex">
                    <div class="input-group">
                        <label for="inputGroupFile" class="input-group-text bg-light border-light">Upload Photo:</label>
                        <input type="file" class="form-control d-none" id="inputGroupFile">
                        <label class="input-group-text btn btn-info text-white" for="inputGroupFile">Upload</label>  
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary ms-2">Submit</button>
                    <div class="form-check form-switch ms-2">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked>
                        <div class="p-0"> Public </div>
                    </div>

                    
                </form>
            </div>
        </nav>
    </section>
  </body>
</html>
