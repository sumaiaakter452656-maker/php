<?php 
session_start();
if(isset($_POST['login_button'])){
    extract($_POST);
    $password = md5($password);
    include_once 'dbconfig.php';

    $result = $conn->query("SELECT * FROM `students` WHERE email = '$email' AND password = '$password'");

    //$row =$ result->fetch_assoc();
    
    if($result->num_rows > 0){
      //sesssion_start();
        $_SESSION['name'] = $result->fetch_assoc()['name'];
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        $login_error = "Login Failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Log In | Dashtrap - Responsive Bootstrap 5 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Myra Studio" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <script src="assets/js/config.js"></script>
</head>

<body class="bg-primary d-flex justify-content-center align-items-center min-vh-100 p-5">
    <?php if(isset($login_error)) { echo "<div class='alert alert-danger text-center'>$login_error</div>"; } ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-md-5">
                <div class="card">
                    <div class="card-body p-4">

                        <div class="text-center w-75 mx-auto auth-logo mb-4">
                            <a href="index.html" class="logo-dark">
                                <span><img src="assets/images/logo-dark.png" alt="" height="22"></span>
                            </a>

                            <a href="index.html" class="logo-light">
                                <span><img src="assets/images/logo-light.png" alt="" height="22"></span>
                            </a>
                        </div>




                        
              <div class="form-body mt-4">
                <form class="row g-3" method="POST" class="login-form">
                  <div class="col-12">
                    <label for="inputEmailAddress" class="form-label">Email</label>
                    <input type="email" class="form-control  border-3" id="inputEmailAddress" placeholder="Enter Email Address" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                  </div>
                  <div class="col-12">
                    <label for="inputChoosePassword" class="form-label">Password</label>
                    <div class="input-group" id="show_hide_password">
                      <input type="password" class="form-control border-end-0  border-3" id="inputChoosePassword" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>"  placeholder="Enter Password"> 
                      <a href="javascript:;" class="input-group-text bg-transparent  border-3"><i class="bi bi-eye-slash-fill"></i></a>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check form-switch  border-3">
                      <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                      <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                    </div>
                  </div>
                  <div class="col-md-6 text-end">	<a href="auth-cover-forgot-password.html">Forgot Password ?</a>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      
                      <button type="submit" name="login_button" class="btn  border-3 btn-primary">Login</button>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="text-start">
                      <p class="mb-0">Don't have an account yet? <a href="auth-cover-register.html">Sign up here</a>
                      </p>
                    </div>
                  </div>
                </form>
              </div>


                        

                       
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-white-50"> <a href="pages-register.html" class="text-white-50 ms-1">Forgot your password?</a></p>
                        <p class="text-white-50">Don't have an account? <a href="pages-register.html" class="text-white font-weight-medium ms-1">Sign Up</a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>

    <!-- App js -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>

</body>

</html>