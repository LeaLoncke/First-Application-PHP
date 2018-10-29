<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>ToDoList - Home</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
</head>

<body>
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

  <?php
    include 'php/bdd_connection.php';
   ?>

  <!-- Navbar -->
  <header class='navbar'>
    <ul>
      <li class='logo'>ToDoList</li>
      <li class='breadcrumb'><a href="index.php">Home</a></li>
    </ul>
  </header>


  <!-- We recover the data for display the projects in a loop -->
  <div>
    <?php
      $req = $bdd->query('SELECT * FROM projects ORDER BY date_limit');
      while ($data = $req->fetch() ) {
        ?>
        <div class="project">
          <p><a href="php/project.php?projectid=<?php echo $data['id']; ?>"><?php echo $data['name']; ?></a></p>
          <p>Limit-date : <?php echo $data['date_limit']; ?></p>
          <p>Description : <?php echo $data['description']; ?></p>
          <!-- Input for delete "this" project (look at the lower php) -->
          <form action="index.php" method="post">
            <input type="hidden" name="id_project" value="<?php echo $data['id']; ?>">
            <input type="submit" name="delete_this_project" value="Delete this project">
          </form>
        </div>
        <?php
      }
      $req->closeCursor();
     ?>
  </div>

  <!-- Form for add a project -->
  <form class="addProject" action="index.php" method="post">
    <p>Add a project :</p>
    <label for="name_project">Name of the project :</label><br /><input id="name_project" type="text" name="name_project" value="" required><br />
    <label for="description_project">Description of the project :</label><br /><input id="description_project" type="text" name="description_project" value="" required><br />
    <label for="date_limit_project">Limit-date :</label><br /><input id="date_limit_project" type="date" name="date_limit_project" value="" min="<?php echo date("Y-m-d"); ?>" required><input id="time_limit_project" type="time" name="time_limit_project" value="" required><br />
    <input type="submit" name="add_project" value="Add this project">
  </form>


  <?php

  // Delete project in the database
  if (isset($_POST['delete_this_project']) && isset($_POST['id_project']) && !empty($_POST['id_project']) ) {
    $id_project_delete = (int)htmlspecialchars($_POST['id_project']);

    $req = $bdd->prepare('DELETE FROM projects WHERE id = :id');
    $req->execute(array(
      'id' => $id_project_delete
    ));

    $req->closeCursor();
    header('Location: index.php');
  } // end of if (isset($_POST['delete_this_project']) ...)


  // Add a new project in the database
  if (isset($_POST['add_project']) ) {

    $name = htmlspecialchars($_POST['name_project']);
    $description = htmlspecialchars($_POST['description_project']);
    $date_limit = htmlspecialchars($_POST['date_limit_project']) . ' ' . htmlspecialchars($_POST['time_limit_project']) . ':00';

    if (isset($name) && !empty($name) && isset($description) && !empty($description) && isset($date_limit) && !empty($_POST['date_limit_project']) && !empty($_POST['time_limit_project'])) {

      $req = $bdd->prepare('INSERT INTO projects (name, description, date_limit) VALUES (:name, :description, :date_limit)');
      $req->execute(array(
        'name' => $name,
        'description' => $description,
        'date_limit' => $date_limit
      ));

      $req->closeCursor();
      header('Location: index.php');

    } else {
      ?>
      <p class="errorMessage">Your project is not saved because you have not completed the form correctly !</p>
      <?php
    }

  } // end of if (isset($_POST['add_project']))

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
