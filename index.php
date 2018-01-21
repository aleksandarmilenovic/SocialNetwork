<?php
include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/Post.php');
include('./classes/Comment.php');
include('./classes/Notify.php');
$showTimeline = False;


if(Login::isLoggedIn()){
//  echo "LOGGED IN";
//  echo Login::isLoggedIn();
$userid = Login::isLoggedIn();
  $showTimeline = True;
}else {
  die('NOT LOGGED IN');

}

if (isset($_GET['postid'])) {
  $postId = $_GET['postid'];
  Post::likePost($postId,$userid);
}

if (isset($_GET['comment'])) {
  Comment::createComment($_POST['commentbody'],$_GET['comment'],$userid);
}

if(isset($_POST['search'])){
    $tosearch =explode(" ",$_POST['searchbox']);
    if(count($tosearch) == 1){
      $tosearch = str_split($tosearch[0],2);
    }

    $whereckause = "";
    $paramsarray = array(':username' => '%'.$_POST['searchbox'].'%');

    for ($i=0; $i < count($tosearch); $i++) {

        $whereckause .= " OR username LIKE :u$i ";
        $paramsarray[":u$i"] = $tosearch[$i];
    }



    $users = DB::query('SELECT users.username FROM users WHERE users.username LIKE :username '.$whereckause.'',$paramsarray);
    print_r($users);

    $whereckause = "";
    $paramsarray = array(':body' => '%'.$_POST['searchbox'].'%');

    for ($i=0; $i < count($tosearch); $i++) {
        if($i%2 == 0){
        $whereckause .= " OR body LIKE :p$i ";
        $paramsarray[":p$i"] = $tosearch[$i];
      }
    }

    $posts = DB::query('SELECT posts.body FROM posts WHERE posts.body LIKE :body '.$whereckause.'',$paramsarray);
    echo "<pre>";
    print_r($posts);
    echo "</pre>";
  }

?>
<form  action="index.php" method="post">
<input type="text" name="searchbox" value="">
<input type="submit" name="search" value="Search">
</form>

<?php
$followingposts = DB::query('SELECT posts.id,posts.body,posts.likes,users.username FROM users,posts,followers
WHERE posts.user_id = followers.user_id
AND users.id = posts.user_id
AND follower_id=:userid
ORDER BY posts.likes DESC',array(':userid'=>$userid));


foreach($followingposts as $post) {
        echo $post['body']." ~ ".$post['username'];
        echo "<form action='index.php?postid=".$post['id']."' method='post'>";
        if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$post['id'], ':userid'=>$userid))) {
        echo "<input type='submit' name='like' value='Like'>";
        } else {
        echo "<input type='submit' name='unlike' value='Unlike'>";
        }
        echo "<span>".$post['likes']." likes</span>
        </form>

        <form action='index.php?comment=".$post['id']."' method='post'>
        <textarea name='commentbody' rows='3' cols='50'></textarea>
        <input type='submit' name='comment' value='Comment'>
        </form>
          ";
          Comment::displayComments($post['id']);
          echo "
        <hr /></br />";
}




 ?>
