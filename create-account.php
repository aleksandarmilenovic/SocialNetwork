<?php
include('classes/DB.php');


if(isset($_POST['createaccount'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];

      DB::query('INSERT INTO users VALUES (null,:username,:password,:email)',array(':username' =>$username,':password'=>$password,':email'=>$email));
      echo "SUCCESS";
}

 ?>

<h1>Register</h1>
<form action="create-account.php" method="post">
  <input type="text" name="username" value="" placeholder="Username ..."><p/>
  <input type="text" name="password" value="" placeholder="Password ..."><p/>
  <input type="text" name="email" value="" placeholder="someone@somesite.com"><p/>
  <input type="submit" name="createaccount" value="Create Account">
</form>
