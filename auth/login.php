<?php

require_once('header.php');

if (isset($_SESSION["loggedin_user"]) && $_SESSION["loggedin_user"] != '' ) {
redirect();
  }
$loginError = [];

if (isset($_POST['submit'])) {

  $userEmail = $_POST['user_email'];
  $userPass = md5(trim($_POST["user_password"]));

  $sql = "SELECT id, username, role, status, email FROM `users` WHERE email='$userEmail' AND password ='$userPass'";

  $userObj = $db->select($sql, true);
  
  if ($userObj) {
    if ($userObj['status'] == 'active') {
      $_SESSION["loggedin_user"] = $userObj;
      if ($userObj['role'] == 'admin'){
        redirect(ADMIN_URL);
      }else{
        redirect(BASE_URL);
      }
    }else{
      $loginError [] = "User is inactive.";
    }
  } else {
    $loginError [] = "Incorrect Username or Password!";
  }
}

?>
<section class="log-in-form">
  <div class="container">
    <div class="row">
      <div class="card login-card">
        <div class="card-body">
          <h2 class="text-center">Login</h2>
          <?php
          if (!empty($loginError)) {
            foreach ($loginError as $error) {
              echo "<div class='alert alert-danger' role='alert'>$error</div>";
            }
          }
          ?>
          <form action="" method="POST">
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" class="form-control" required name="user_email" id="exampleInputEmail1"
                aria-describedby="emailHelp" placeholder="">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" class="form-control" required name="user_password" placeholder="">
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="rememberCheckbox">
              <label class="form-check-label" for="rememberCheckbox">Remember me.</label>
            </div>
            <div class="form-group">
              <button type="submit" name="submit" value="submit" class="btn btn-primary float-start"
                id="submit">Login</button>
              <a href="<?php echo BASE_URL ?>/register" class="btn btn-outline-primary float-end" id="sign_in ">Sign
                Up</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once('footer.php'); ?>