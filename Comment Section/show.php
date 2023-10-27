<?php 
require "includes/header.php"; 
require "config.php"; 

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $onePost = $conn->prepare("SELECT * FROM posts WHERE id=:id");
    $onePost->bindParam(':id', $id);
    $onePost->execute();

    $posts = $onePost->fetch(PDO::FETCH_OBJ);

    if ($posts) {
        $comments = $conn->prepare("SELECT * FROM comments WHERE post_id=:id");
        $comments->bindParam(':id', $id);
        $comments->execute();
        $comments = $comments->fetchAll(PDO::FETCH_OBJ);
    } else {
        echo "No posts found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}
?>

<!-- POST -->
<div class="row w-100 m-auto mt-5">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $posts->title; ?></h5>
            <p class="card-text"><?php echo $posts->body; ?></p>
        </div>
    </div>  
</div>

<!-- INSERT COMMENT -->
<?php if (isset($_SESSION['username'])) : ?>
<div class="row">
  <form method="POST" id="comment_data">

    <div class="form-floating">
      <input name="username" type="hidden" value="<?php echo $_SESSION['username']?>" class="form-control" id="username" placeholder="username">
    </div>

    <div class="form-floating">
      <input name="post_id" type="hidden" value="<?php echo $posts->id  ?>" class="form-control" id="post_id">
    </div>

    <div class="form-floating mt-3">
        <textarea rows="9" name="comment" placeholder="comment" class="form-control h-100" id="comment"></textarea>
        <label for="floatingBody">Comment</label>
    </div>

    <button name="submit" id="submit" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Create Comment</button>
  </form>

    <div id="msg"></div>
    <div id="delete-msg"></div>
</div>
<?php else: ?>
    <div class="row">
        <div class="alert alert-danger mt-3">You need to be logged in to comment!</div>
    </div>
<?php endif; ?>
<!-- SHOW COMMENTS -->
<div class="row w-100 m-auto mt-5" id="comments-section">
    <?php foreach ($comments as $singleComment) : ?>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title"><?php echo $singleComment->username; ?></h5>
            <p class="card-text"><?php echo $singleComment->comment; ?></p>
            <?php if (isset($_SESSION['username']) AND $_SESSION['username'] == $singleComment->username) : ?>
            <button class="delete-btn w-20 btn btn-sm btn-danger mt-2" value="<?php echo $singleComment->id; ?>" >Delete</button>
            <?php endif; ?>
        </div>
    </div>  
    <?php endforeach; ?>
</div>

<?php require "includes/footer.php"; ?>

<script>
    $(document).ready(function(){

        // INSERT COMMENT
        $('#comment_data').on('submit', function(e){
            e.preventDefault();
            var formdata = $(this).serialize()+ "&submit=submit";
            $.ajax({
                type: "post",
                url: "comment.php",
                data: formdata,

                success: function(data){
                    $('#comment').val('');

                    $("#msg").html("Comment added successfully").toggleClass('alert alert-success bg-success text-white mt-3');
                    fetchComments();
                }
            })
        })

        // DELETE COMMENT
        $(document).on('click', '.delete-btn', function(e){
            e.preventDefault();
            var id = $(this).val();
            $.ajax({
                type: "post",
                url: "delete.php",
                data: {
                    delete: 'delete',
                    id: id
                },

                success: function(){
                    $("#delete-msg").html("Comment deleted successfully").toggleClass('alert alert-success bg-danger text-white mt-3');
                    fetchComments();
                }
            })
        })

        // FETCH COMMENTS (update periodically)
        function fetchComments() {
            setInterval(() => {
                $("#comments-section").load("show.php?id=<?php echo $_GET['id']; ?> #comments-section");
            }, 2000);
        }

        fetchComments(); // initial fetch
    });
</script>