<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>ToDoList - List</title>
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

    // Recover the project name from the list
    $req = $bdd->prepare('SELECT name FROM projects WHERE id = :id');
    $req->execute(array(
      'id' => $project_id
    ));
    $data = $req->fetch();
    $name_project = $data['name'];
    $req->closeCursor();

    // Recover the list name
    $req = $bdd->prepare('SELECT name FROM lists WHERE id = :id');
    $req->execute(array(
      'id' => $list_id
    ));
    $data = $req->fetch();
    $name_list = $data['name'];
    $req->closeCursor();

    // Recover the informations from the tasks where id_list = $list_id
    $req = $bdd->prepare('SELECT * FROM tasks WHERE id_list = :id');
    $req->execute(array(
      'id' => $list_id
    ));

?>
    <header class='navbar'>
      <ul>
        <li class='logo'>ToDoList</li>
        <li class='breadcrumb'><a href="../index.php">Home</a> / <a href="project.php?projectid=<?php echo $project_id; ?>"><?php echo $name_project; ?></a> / <?php echo $name_list; ?></li>
      </ul>
    </header>

    <div class="list">
      <p class="nameList"><?php echo $name_list; ?></p>
      <ul>
<?php
    while ($data = $req->fetch() ) {
      ?>
      <li>
        <p>Task: <?php echo $data['name'] ?> </p>
        <p>Limit-date: <?php echo $data['date_limit'] ?> </p>
        <form action="list.php" method="post">
          <input type="hidden" name="id_task" value="<?php echo $data['id'] ?>">
          <input type="submit" name="delete_this_task" value="Delete this task">
        </form>
      </li>
      <?php
    } // End of while ($data = $req->fetch() )
    $req->closeCursor();
?>
      </ul>
    </div>

    <form class="addTask" action="list.php" method="post">
      <p>Add a task :</p>
      <label for="name_task">Name of the task : </label><br /><input id="name_task" type="text" name="name_task" value="" required><br />
      <label for="date_limit_task">Limit-date : </label><br /><input id="date-limit-task" type="date" name="date-limit-task" min="<?php echo date("Y-m-d"); ?>" required> <input id="time_limit_task" type="time" name="time_limit_task" value="" required><br />
      <input type="submit" name="add_project" value="Add this project">
    </form>

<?php

  } else {
      // Redirect if the url is incomplete
      header('Location: ../index.php');
  }


// Delete the task in the database
if (isset($_POST['delete_this_task']) && isset($_POST['id_task']) && !empty($_POST['id_task']) ) {
  $id_task_delete = (int)htmlspecialchars($_POST['id_task']);

  $req = $bdd->prepare('DELETE FROM tasks WHERE id = :id');
  $req->execute(array(
    'id' => $id_task_delete
  ));

  $req->closeCursor();
  // header('Location: list.php?projectid=' . $project_id . '&amp;listid=' . $list_id);

} // end of if (isset($_POST['delete_this_project']) ...)


// Add a task in the database
if (isset($_POST['add_task']) ) {

  $name_task = htmlspecialchars($_POST['name_task']);
  $this_id_project = (int)htmlspecialchars($_POST['this_id_project']);

  if (isset($name_task) && !empty($name_task) && isset($this_id_project) && !empty($this_id_project)) {

    $req = $bdd->prepare('INSERT INTO tasks (id_project, name) VALUES (:id_project, :name)');
    $req->execute(array(
      'id_project' => $this_id_project,
      'name' => $name_task
    ));

    $req->closeCursor();
    // header('Location: list.php?projectid=' . $project_id . '&amp;listid=' . $list_id);

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
