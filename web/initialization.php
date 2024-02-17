<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1"/>
      <link rel="icon" type="image/x-icon" href="Resources/Instagram_icon.png"/>
      <title>CSCI4140-Web Instagram-1155143402</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
      <link href="index.css" rel="stylesheet"/>
  </head>
  <body>
    <?php
      echo '<h1>Hello this world! 1.0 </h1>';
      echo '<p>This page uses PHP version '
          . phpversion()
          . '.</p>';
      include('lib/db_connect.php');
      echo db_test();
    ?> 
  </body>
</html>