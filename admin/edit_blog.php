<?php 
include "header.php";

if(isset($_SESSION['user_data'])){
	$author_id=$_SESSION['user_data']['0'];
}

// Fetch categories
$sql = "SELECT * FROM category";
$query = mysqli_query($config, $sql);

// GET BLOG ID
$blogID = $_GET['id'];
if(empty($blogID))
{
	header("location:index.php");
}
$sql2 = "SELECT * FROM blogs LEFT JOIN category ON blogs.category = category.cat_id LEFT JOIN users ON blogs.author_id = users.user_id WHERE blog_id = '$blogID'";
$query2 = mysqli_query($config, $sql2);
$result = mysqli_fetch_assoc($query2);
?>
<div class="container">
	<h5 class="mb-2 text-gray-800">Blogs </h5>
	<div class="row">
		<div class="col-xl-12 col-lg-5">
			<div class="card">
				<div class="card-header">
					<h6 class="font-weight-bold text-primary mt-2">Edit Blog/Article</h6>
					<div class="card-body">
						<form method="post" action="" enctype="multipart/form-data">
							<div class="mb-3">
								<input type="text" name="blog_title" placeholder="Title" class="form-control" required value="<?= $result['blog_title'] ?>">
							</div>
							<div class="mb-3">
								<label>Body/Description</label>
								<textarea required class="form-control" name="blog_body" rows="2" id="blog"><?= $result['blog_body'] ?></textarea>
							</div>
							<div class="mb-3">
								<input type="file" name="blog_image" class="form-control">
								<img src="upload/<?= $result['blog_image'] ?>" width="100px" class="border mt-2">
							</div>
							<div class="mb-3">
								<select class="form-control" name="Category" required>
									<?php
									while($cats = mysqli_fetch_assoc($query)){
									?>
									<option value="<?= $cats['cat_id'] ?>" <?= ($result['category'] == $cats['cat_id']) ? 'selected' : ''; ?>>
										<?= $cats['catname'] ?>
									</option>
									<?php } ?>
								</select>
							</div>
							<div class="mb-3">
								<input type="submit" name="edit_blog" value="Update" class="btn btn-primary">
								<a href="index.php" class="btn btn-secondary">Back</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
include "footer.php";

if(isset($_POST['edit_blog'])){
	$title = mysqli_real_escape_string($config, $_POST['blog_title']);
	$body = mysqli_real_escape_string($config, $_POST['blog_body']);
	$category = mysqli_real_escape_string($config, $_POST['Category']);
	$filename = $_FILES['blog_image']['name'];
	$tmp_name = $_FILES['blog_image']['tmp_name'];
	$size = $_FILES['blog_image']['size'];
	$image_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	$allow_type = ['jpg', 'png', 'jpeg'];
	$destination = "upload/" . $filename;

	if(!empty($filename)){
		if(in_array($image_ext, $allow_type)){
			if($size <= 2000000){
				$unlink = "upload/" . $result['blog_image'];           //use unlink function
				unlink($unlink);
				move_uploaded_file($tmp_name, $destination);
				$sql3 = "UPDATE blogs SET blog_title='$title', blog_body='$body', category='$category', blog_image='$filename', author_id='$author_id' WHERE blog_id='$blogID'";
				$query3 = mysqli_query($config, $sql3);
				if($query3){
					$msg = ['Post Updated successfully','alert-success'];
					$_SESSION['msg'] = $msg;
					header("location:index.php");
				} else {
					$msg = ['Failed,Please try again','alert-danger'];
					$_SESSION['msg'] = $msg;
					header("location:index.php");
				}
			} else {
				$msg = ['image size should not be greater than 2mb','alert-danger'];
				$_SESSION['msg'] = $msg;
				header("location:index.php");
			}
		} else {
			$msg = ['File type is not allowed(only jpg, png, jpeg)','alert-danger'];
			$_SESSION['msg'] = $msg;
			header("location:index.php");
		}
	} else {
		$sql3 = "UPDATE blogs SET blog_title='$title', blog_body='$body', category='$category', author_id='$author_id' WHERE blog_id='$blogID'";
		$query3 = mysqli_query($config, $sql3);
		if($query3){
			$msg = ['Post Updated successfully','alert-success'];
			$_SESSION['msg'] = $msg;
			header("location:index.php");
		} else {
			$msg = ['Failed,Please try again','alert-danger'];
			$_SESSION['msg'] = $msg;
			header("location:index.php");
		}
	}
}
?>
