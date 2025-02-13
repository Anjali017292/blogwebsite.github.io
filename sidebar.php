<?php include "connection.php"; 
$select="SELECT * FROM category";
$query=mysqli_query($config,$select);
//Recent posts
$select2="SELECT * FROM blogs ORDER BY  publish_date limit 5 ";
$query2=mysqli_query($config,$select2);
?>
<div class="col-lg-4">
			<div class="card">
				<div class="card-body d-flex right-section">
					<div id="categories">
						<h6 class="fw-bold">Categories</h6>
						<ul>
							<?php while($cats=mysqli_fetch_assoc($query)){ ?>
							<li>
								<a href="category.php?id=<?= $cats['cat_id'];?>"  class="text-success fw-bold"><?= $cats['catname']?></a>
							</li>
						<?php } ?>
						</ul>
					</div>
				    <div id="posts">
						<h6 class="fw-bold">Recent Posts</h6>
						<ul>
							<?php while($posts=mysqli_fetch_assoc($query2)){ ?>
							<li>
								<a href="single_post.php?id=<?= $posts['blog_id']; ?>" class="text-success fw-bold"><?= $posts['blog_title'];?></a>
							</li>
						    <?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>