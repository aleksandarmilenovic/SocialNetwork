<?php
include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/Post.php');
include('./classes/Image.php');

if (isset($_GET['topics'])) {

  if(DB::query("SELECT topics FROM posts WHERE FIND_IN_SET(:topics,topics)",array(':topics'=>$_GET['topics']))){

          $posts = DB::query("SELECT * FROM posts WHERE FIND_IN_SET(:topics,topics)",array(':topics'=>$_GET['topics']));

        foreach ($posts as $post) {
              
              echo $post['body']."<br />";
        }
  }

}
 ?>
