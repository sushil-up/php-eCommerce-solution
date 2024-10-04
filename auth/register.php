<?php
require_once('header.php');

if (isset($_SESSION["loggedin_user"]) && $_SESSION["loggedin_user"] != '')
    redirect();

$userError = false;

$userName = '';
$userEmail = '';
$userFname = '';
$userLname = '';
$userPhone = '';

if (isset($_POST["submit"])) {

    $userName = (trim($_POST["user_name"]));
    $userEmail = (trim($_POST["user_email"]));
    $userFname = (trim($_POST["user_fname"]));
    $userLname = (trim($_POST["user_lname"]));
    $userPhone = (trim($_POST["user_phone"]));
    // $image = $_POST["user_image"];
    $userPass = md5(trim($_POST["user_password"]));
    $confirmPass = md5(trim($_POST["confirm_password"]));

    if (strlen($userPhone) != 10) {
        $userError = 'Invalid phone number';

    } else {
        if ($userPass != $confirmPass) {
            // echo "Password and Confirm password should be same";
            $userError = "Password and Confirm password should be same";
        } else {


            $sqls = "SELECT * FROM `users` WHERE email='$userEmail' OR username ='$userName'";
            $results = $db->select($sqls, true);

            if (!empty($results)) {
                $userError = "this email  and user name is already used ";
            } else {
                $insertDate = date('Y-m-d H:i:s');
                $sql = "INSERT INTO `users`( `username`, `email`, `password`, `f_name`, `l_name`,`phone`, `role`, `status`, `created_date`) VALUES('$userName','$userEmail','$userPass',' $userFname','$userLname','$userPhone','customer','inactive', '$insertDate')";
                $res = $db->insert($sql);
                redirect(BASE_URL . "/login");
            }
        }
    }


}
?>


<section class="register-form">
    <div class="container">
        <div class="row">

            <div class="card login-card">
                <div class="card-body">
                    <h1 class="text-center">Register</h1>
                    <?php if ($userError)
                        echo "<div class='alert alert-danger' role='alert'>$userError</div>"; ?>

                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="exampleInputPassword1">First name</label>
                            <input type="text" name="user_fname" required class="form-control"
                                value="<?php echo $userFname; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Last name</label>
                            <input type="text" name="user_lname" class="form-control" value="<?php echo $userLname; ?>">
                        </div>
                        <!-- <div class="form-group">
                            <label for="exampleInputPassword1">image</label>
                            <input type="file" name="user_image" class="form-control"  value="<?php echo $userName; ?>">
                        </div> -->
                        <div class="form-group">
                            <label for="exampleInputPassword1">Username</label>
                            <input type="text" name="user_name" required class="form-control"
                                value="<?php echo $userName; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" name="user_email" required class="form-control"
                                value="<?php echo $userEmail; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Phone no </label>
                            <input type="text" name="user_phone" class="form-control" value="<?php echo $userPhone; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" name="user_password" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Confirm Password</label>
                            <input type="password" name="confirm_password" required class="form-control">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" required class="form-check-input" id="agreeCheckbox">
                            <label class="form-check-label" for="agreeCheckbox">I agrre to the Term and
                                Conditions.</label>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary float-start"
                                id="submit">Sign Up</button>
                            <a href="<?php echo BASE_URL ?>/login" class="btn btn-outline-primary float-end"
                                id="sign_in ">Login</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</section>
<?php require_once('footer.php'); ?>