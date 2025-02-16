<?php include('header.php'); 
include 'connection.php';
$keyword=$_GET['keyword'];
if(empty($keyword)){
    header("location:index.php");
}
// Pagination
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = intval($_GET['page']);
}
$limit = 3;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM blogs 
        LEFT JOIN category ON blogs.category = category.cat_id 
        LEFT JOIN users ON blogs.author_id = users.user_id WhERE blog_title like '%$keyword%' or blog_body like '%$keyword%'
        ORDER BY blogs.publish_date DESC 
        LIMIT $offset, $limit";
$run = mysqli_query($config, $sql);
$rows = mysqli_num_rows($run);
?>
<div class="container mt-2" id="adminpage">
    <div class="row">
        <h3 class="mb-0">Search result for: <span  class="text-primary"><?= $keyword ?> </span></h3>
        <div class="col-lg-8">
            <hr>
            <?php
            if($rows)
            {
                while($result = mysqli_fetch_assoc($run)){
            ?>

            <div class="card shadow">
                <div class="card-body d-flex blog_flex">
                    <div class="flex-part1">
                        <a href="single_post.php?id=<?= $result['blog_id']?>"> 
                            <?php $img = $result['blog_image']; ?>
                            <img src="admin/upload/<?php echo $img; ?>" alt="Blog Image">
                        </a>
                    </div>
                    <div class="flex-grow-1 flex-part2">
                          <a href="single_post.php?id=<?= $result['blog_id']?>" id="title"><?php  echo ucfirst($result['blog_title'])?></a>
                        <p>
                          <a href="single_post.php?id=<?= $result['blog_id']?>" id="body">
                              <?= strip_tags(substr($result['blog_body'], 0, 200) . "...") ?>
                          </a> <span><br>
                          <a href="single_post.php?id=<?= $result['blog_id']?>" class="btn btn-sm btn-outline-primary">Continue Reading
                          </a></span>
                        </p>
                        <ul>
                            <li class="me-2"><a href=""><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span><?php echo $result['username'] ?></a></li>
                            <li class="me-2"><a href=""><span><i class="fa fa-calendar-o" aria-hidden="true"></i></span><?php $date = $result['publish_date'] ?><?= date('d-M-Y', strtotime($date)) ?></a></li>
                            <li><a href="category.php?id=<?= $result['cat_id'];?>" class="text-primary"><span><i class="fa fa-tag" aria-hidden="true"></i></span><?= $result['catname']; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php }} else
         {
            echo "<h5 class='text-danger'>No Record Found</h5>
            <b>Suggestions:</b>
            <li> Make sure that words are spelled correctly.</li>
            <li>Try different keywords.</li>";
        }?>
        <! _ _ _ _ _ _ _ pagination >

        <?php
        $pagination = "SELECT * FROM blogs WHERE blog_title like '%$keyword%' or blog_body like '%$keyword%'";
        $run_q = mysqli_query($config, $pagination);
        $total_post = mysqli_num_rows($run_q);
        $total_pages = ceil($total_post / $limit);
        if($total_post>$limit){
        ?>
        <ul class="pagination pt-2 pb-5">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <li class="page-item <?= ($i==$page)? $active="active":"";?>">
                <a href="search.php?keyword=<?=$keyword ?>&page=<?= $i ?>"class="page-link"><?= $i ?></a>
            </li>
        <?php } ?>
        </ul>
    <?php } ?>
        <!---------------->
        <?php include('sidebar.php'); ?>
    </div>
</div>

<?php include('footer.php'); 
?> 
