<!-- HEADER -->
<?php require "includes/header.php"; ?>

<?php require "config.php" ?>

<?php
  // check for the submit button and checks if the client inputed the data
  // then executes the query and inserts the data into the database
  // and hashes the password

  if(isset($_SESSION['username'])){ // if position is set
    header("location:index.php"); // go to index.php
  }

  if (isset($_POST['submit'])){ // if user clicked submit button
    if($_POST['email'] == '' OR $_POST['username'] == '' OR $_POST['password'] == ''){ // if the inputs are empty
      echo "Some inputs are empty";
    } else { // if the inputs are not empty
      $email = $_POST['email'];
      $username = $_POST['username'];
      $password = $_POST['password'];

      $insert = $conn->prepare("INSERT INTO USERS (email, username, mypassword)
       VALUES (:email, :username, :mypassword)"); // insert the data into the database
       $insert->execute([ // execute the query
        ':email' => $email,
        ':username' => $username,
        ':mypassword' => password_hash($password, PASSWORD_DEFAULT),// hash the password
       ]);
    }
  }
?>

<main class="form-signin w-50 m-auto">
  <form method="POST" action="register.php"> 
   
    <h1 class="h3 mt-5 fw-normal text-center">Please Register</h1>

    <div class="form-floating">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>

    <div class="form-floating">
      <input name="username" type="text" class="form-control" id="floatingInput" placeholder="username">
      <label for="floatingInput">Username</label>
    </div>

    <div class="form-floating">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <button name="submit" class="w-100 btn btn-lg btn-primary" type="submit">register</button>
    <h6 class="mt-3">Aleardy have an account?  <a href="login.php">Login</a></h6>

  </form>
</main>

<!-- FOOTER -->
<?php require "includes/footer.php"; ?>