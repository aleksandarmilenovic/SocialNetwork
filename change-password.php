<?php
include('./classes/DB.php');
include('./classes/Login.php');

if(Login::isLoggedIn()){
  if(isset($_POST['changepassword'])){
            $oldpassword = $_POST['oldpassword'];
            $newpassword = $_POST['newpassword'];
            $repeatnewpassword = $_POST['repeatnewpassword'];
            $userid = Login::isLoggedIn();
          if(password_verify($oldpassword,DB::query('SELECT password FROM users WHERE id=:userid',array(':userid'=>$userid))[0]['password'])){

            if($newpassword == $repeatnewpassword){
                if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
                  DB::query('UPDATE users SET password=:newpassword WHERE id=:userid',array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT),':userid'=>$userid));
                  echo "Password is changerd successfuly!!!";
                }else{
                  echo "Password is to shoort!!!";
                }

            }else{
                echo "Passwords dont match!!!";
            }


          }else{
              echo "Incorrect old password!!!";
          }
  }else{

  }
}else {
  echo "NOT LOGGED IN";

}

 ?>
 <h1>Change your Password</h1>
 <form action="change-password.php" method="post"><p/>
  <input type="password" name="oldpassword" value="" placeholder="Current Password ..."><p/>
  <input type="password" name="newpassword" value="" placeholder="New Password ..."><p/>
  <input type="password" name="repeatnewpassword" value="" placeholder="Repeat New Password ..."><p/>
  <input type="submit" name="changepassword" value="Change Password">
</form>
