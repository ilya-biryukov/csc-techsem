<?php
  $filename = "/var/www/users.txt";
  $users = load_users($filename);

  $show_error = false;
  $error_str = "";
  if (array_key_exists("operation", $_POST)) {
    if ($_POST["operation"] == "add_user") {
      $user = $_POST["login"];
      $pass = $_POST["pass"];
      if (array_key_exists($user, $users)) {
        $show_error = true;
        $error_str = "sorry, the user is already in the list.";
      } else {
        $users[$user] = $pass;
        save_users($filename, $users);
      }
    } else if ($_POST["operation"] == "del_user") {
      $user = $_POST["login"];
      $pass = $_POST["pass"];

      if ($users[$user] != $pass) {
        $show_error = true;
        $error_str = "Sorry, wrong password.";
      } else {
        unset($users[$user]);
        save_users($filename, $users);
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>techsem task 5</title>
  <style>
    body {
      text-align: center;
    }

    .block {
      background-color: #66bbaa;
      border: solid 1px black;
      border-radius: 15px;
      padding: 10px;
      margin: 10px
    }

    .block .user form {
      display: inline;
    }

    .block .user .id {
      text-width: bold;
      padding-left: 5px;
      padding-right: 5px;
      border: solid 1px blue;
      border-radius: 5px;
    }

    .error {
      background-color: #cc0000;
      display: inline;
      border-radius: 15px;
      padding: 10px;
    }

    input[type="text"], input[type="password"] {
      width: 100px;
    }

    input[type="submit"] {
      width: auto;
    }
  </style>
</head>
<body>
  <?php if ($show_error):?>

  <div class="error">
    <?php echo $error_str; ?>
  </div>
  <?php endif;?>
  <div class="block">
    <form method="post" action="/">
      <label for="new_login">name:</label>
      <input type="text" id="new_login" name="login" />

      <label for="new_password">pass:</label>
      <input type="password" id="new_pass" name="pass" />

      <input type="submit" value="add user">

      <input type="hidden" name="operation" value="add_user" />
    </form>
  </div>

  <div class="block">
    <?php
      foreach ($users as $user => $pass):
    ?>
      <div class="user">
        <span class="id">User: <?php echo $user; ?></span>
        <form method="post" action="/">
          <label for="pass">Password:</label>
          <input type="password" id="pass" name="pass" />
          <input type="submit" value="delete user">

          <input type="hidden" name="operation" value="del_user" />
          <input type="hidden" name="login" value="<?php echo $user; ?>" />
        </form>
      </div>
    <?php
      endforeach;
    ?>
  </div>
</body>
</html>

<?php
  function load_users($users_filename) {
    $file_str = file_get_contents($users_filename);
    $users = json_decode($file_str, true);

    return $users;
  }

  function save_users($users_filename, $users) {
    $users_str = json_encode($users);
    file_put_contents($users_filename, $users_str);
  }
?>
