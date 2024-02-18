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
      <main class="mt-5">
          <form class="mx-auto" method="POST" action="">
              <h3 class="text-center mx-auto mb-4 loginHeader" style="font-weight: bold;"> SIGN IN TO YOUR ACCOUNT</h3>
              <div class="form-outline text-center  mb-4 mx-auto">  
                  <input style="width: 250px;" id="username" name="username" placeholder="User name" required pattern="^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$"/>
              </div> 
              <div class="form-outline text-center  mb-4 mx-auto"> 
                <input style="width: 250px;" type="password" id="password" name="password" placeholder="Password" pattern="^[\w\-\@\!\']+$" required/>
              </div>

              <!-- CSRF -->
              <?php 
                // echo '<input type="hidden" name="nonce" value="'. string_sanitization(csrf_getNonce("login")). '" />'; 
              ?> 

              <div class="form-outline text-center mb-4 mx-auto">
                <button class="btn btn-primary btn-block" style="width: 250px;"> Login </button>
              </div>
          </form>
          <div class="form-outline text-center mb-4 mx-auto">
              <a href="./change_password_admin.php" > Create pw </a>
          </div>
      </smain>
</body>
</html>
