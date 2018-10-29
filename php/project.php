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


    if (isset($project_id) && !empty($project_id)) {

      $req = $bdd->prepare('SELECT * FROM projects WHERE id = :id');
      $req->execute(array(
        'id' => $project_id
      ));

      $data = $req->fetch();

    ?>

      <!-- Display the navbar -->
      <header class='navbar'>
        <ul>
          <li class='logo'>ToDoList</li>
          <li class='breadcrumb'><a href="../index.php">Home</a> / <?php echo $data['name']; ?></li>
        </ul>
      </header>

      <!-- Display the project -->
      <div class="project">
        <p class="nameProject"><?php echo $data['name']; ?></p>
        <p>Limit-date : <?php echo $data['date_limit']; ?></p>
        <p>Description : <?php echo $data['description']; ?></p>
      </div>

    <?php

      $req->closeCursor();

      $req = $bdd->prepare('SELECT * FROM lists WHERE id_project = :id_project ORDER BY name');
      $req->execute(array(
        'id_project' => $project_id
      ));

      while ($data = $req->fetch() ) {

        ?>

        <div class="list">
          <p><a href="list.php?projectid=<?php echo $project_id; ?>&amp;listid=<?php echo $data['id']; ?>"><?php echo $data['name']; ?></a></p>
          <form action="project.php" method="post">
            <input type="hidden" name="id_list" value="<?php $data['id']; ?>">
            <input type="submit" name="delete_this_list" value="Delete this list (inactive)">
          </form>
        </div>

        <?php

      } // end of while ($data = $req->fetch() )
      $req->closeCursor();

     ?>

      <!-- Form for add a list -->
      <form class="addList" action="project.php" method="post">
      <p>Add a list :</p>
      <label for="name_list">Name of the list :</label><br /><input id="name_list" type="text" name="name_list" value=""><br />
      <input type="hidden" name="this_id_project" value="<?php echo $project_id; ?>">
      <input type="submit" name="add_list" value="Add this list">
      </form>

    <?php

  } else {
      // Redirect if the url is incomplete
      header('Location: ../index.php');
  }

    // Delete list in the database
    if (isset($_POST['delete_this_list']) && isset($_POST['id_list']) && !empty($_POST['id_list']) ) {
      $id_list_delete = (int)htmlspecialchars($_POST['id_list']);

      $req = $bdd->prepare('DELETE FROM lists WHERE id = :id');
      $req->execute(array(
        'id' => $id_list_delete
      ));

      $req->closeCursor();

    } // end of if (isset($_POST['delete_this_list']) ... )


    // Add a list in the database
    if (isset($_POST['add_list']) ) {

      $name_list = htmlspecialchars($_POST['name_list']);
      $this_id_project = (int)htmlspecialchars($_POST['this_id_project']);

      if (isset($name_list) && !empty($name_list) && isset($this_id_project) && !empty($this_id_project)) {

        $req = $bdd->prepare('INSERT INTO lists (id_project, name) VALUES (:id_project, :name)');
        $req->execute(array(
          'id_project' => $this_id_project,
          'name' => $name_list
        ));

        $req->closeCursor();

      }
    } // end of if (isset($_POST['add_list']) )



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
