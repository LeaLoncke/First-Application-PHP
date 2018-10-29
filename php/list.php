<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>ToDoList - Project</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/main.css">
</head>

<body>
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

<?php
  include 'bdd_connection.php';

  $project_id = (int)htmlspecialchars($_GET['projectid']);
  $list_id = (int)htmlspecialchars($_GET['listid']);


  if (isset($list_id) && !empty($list_id) && isset($project_id) && !empty($project_id) ) {

    $req = $bdd->prepare('SELECT name FROM projects WHERE id = :id');
    $req->execute(array(
      'id' => $project_id
    ));
    $data = $req->fetch();
    $name_project = $data['name'];
    $req->closeCursor();


    $req = $bdd->prepare('SELECT * FROM lists WHERE id = :id');
    $req->execute(array(
      'id' => $list_id
    ));
    $data = $req->fetch();

    ?>

    <header class='navbar'>
      <ul>
        <li class='logo'>ToDoList</li>
        <li class='breadcrumb'><a href="../index.php">Home</a> / <a href="project.php?projectid=<?php echo $project_id; ?>"><?php echo $name_project; ?></a> / <?php echo $data['name']; ?></li>
      </ul>
    </header>

    <div class="list">
      <p class="nameList"><?php echo $data['name']; ?></p>
    </div>

<?php

  } // end of if (isset($list_id) && !empty($list_id) ... )


 ?>








  <script src="js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>

  <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
  <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('send', 'pageview')
  </script>
  <script src="https://www.google-analytics.com/analytics.js" async defer></script>
</body>

</html>
