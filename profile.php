<?php
include('./classes/DB.php');
include('./classes/Login.php');
$username = "";
$verified = False;
$isFollowing = False;
if (isset($_GET['username'])) {
        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
                $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
                $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
                $verified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];
                $followerid = Login::isLoggedIn();
                if (isset($_POST['follow'])) {
                        if ($userid != $followerid) {
                                if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))) {
                                        if ($followerid == 6) {
                                                DB::query('UPDATE users SET verified=1 WHERE id=:userid', array(':userid'=>$userid));
                                        }
                                        DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                                } else {
                                        echo 'Already following!';
                                }
                                $isFollowing = True;
                        }
                }
                if (isset($_POST['unfollow'])) {
                        if ($userid != $followerid) {
                                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))) {
                                        if ($followerid == 6) {
                                                DB::query('UPDATE users SET verified=0 WHERE id=:userid', array(':userid'=>$userid));
                                        }
                                        DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                                }
                                $isFollowing = False;
                        }
                }
                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid', array(':userid'=>$userid))) {
                        //echo 'Already following!';
                        $isFollowing = True;
                }


                if(isset($_POST['post'])){
                    $postbody = $_POST['postbody'];
                    $loggedInUserId = Login::isLoggedIn();

                    if(strlen($postbody) > 160 || strlen($postbody)<1){
                      die('Incorrect length!');
                    }

                    if($loggedInUserId == $userid){

                    DB::query('INSERT INTO posts VALUES (\'\',:postbody,NOW(),:userid,0)', array(':postbody' => $postbody,':userid'=>$userid ));
                  }else {
                    echo die('Incorrect user!!!');
                  }
                }

                if(isset($_GET['postid'])){
                  if(!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid',array(':postid' => $_GET['postid'],':userid'=>$followerid))){
                    DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid',array(':postid'=>$_GET['postid']));
                    DB::query('INSERT INTO post_likes VALUES (\'\',:postid,:userid)',array(':postid'=>$_GET['postid'],':userid'=>$followerid));
                  }else {
                    echo "Already liked";
                    DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid',array(':postid'=>$_GET['postid']));
                    DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid',array(':postid'=>$_GET['postid'],':userid'=>$followerid));
                  }

                }

                $dbpost = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid' => $userid));
                $posts = "";
                foreach ($dbpost as $p) {

                if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid',array(':postid'=>$p['id'],':userid'=>$followerid))){

                  $posts .= htmlspecialchars($p['body'])."
                  <form action='profile.php?username=$username&postid=".$p['id'].";' method='post'>
                     <input type='submit' name='like' value='Like'>
                     <span>".$p['likes']."likes</span>
                  </form>

                  <hr/></br />";
                }else {
                  $posts .= htmlspecialchars($p['body'])."
                  <form action='profile.php?username=$username&postid=".$p['id'].";' method='post'>
                     <input type='submit' name='unlike' value='Unike'>
                     <span>".$p['likes']."likes</span>
                  </form>

                  <hr/></br />";
                }
                }


        } else {
                die('User not found!');
        }
}
?>
<h1><?php echo $username; ?>'s Profile<?php if ($verified) { echo ' - Verified'; } ?></h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
        <?php
        if ($userid != $followerid) {
                if ($isFollowing) {
                        echo '<input type="submit" name="unfollow" value="Unfollow">';
                } else {
                        echo '<input type="submit" name="follow" value="Follow">';
                }
        }
        ?>
</form>

<form action="profile.php?username=<?php echo $username; ?>" method="post">
  <textarea name="postbody" rows="8" cols="80"></textarea>
  <input type="submit" name="post" value="Post">
</form>



<div class="posts">
  <?php echo $posts; ?>
</div>
