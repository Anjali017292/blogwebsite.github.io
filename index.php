<?php 
include('header.php'); 
include 'connection.php';

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM blogs 
        LEFT JOIN category ON blogs.category = category.cat_id 
        LEFT JOIN users ON blogs.author_id = users.user_id 
        ORDER BY blogs.publish_date DESC 
        LIMIT $offset, $limit";

$run = mysqli_query($config, $sql);
$rows = mysqli_num_rows($run);
?>

<div class="container mt-2" id="adminpage">
    <div class="row">
        <div class="col-lg-8">
            <?php if($rows): ?>
                <?php while($result = mysqli_fetch_assoc($run)): ?>
                    <div class="card shadow">
                        <div class="card-body d-flex blog_flex">
                            <div class="flex-part1">
                                <a href="single_post.php?id=<?= $result['blog_id'] ?>"> 
                                    <img src="admin/upload/<?= $result['blog_image'] ?>" alt="Blog Image">
                                </a>
                            </div>
                            <div class="flex-grow-1 flex-part2">
                                <a href="single_post.php?id=<?= $result['blog_id'] ?>" id="title" class="text-dark fw-bold">
                                    <?= ucfirst($result['blog_title']) ?>
                                </a>
                                <p>
                                    <a href="single_post.php?id=<?= $result['blog_id'] ?>" id="body">
                                        <?= strip_tags(substr($result['blog_body'], 0, 200) . "...") ?>
                                    </a> 
                                    <span><br>
                                        <a href="single_post.php?id=<?= $result['blog_id'] ?>" class="btn btn-sm btn-outline-primary">
                                            Continue Reading
                                        </a>
                                    </span>
                                </p>
                                <ul>
                                    <li class="me-2">
                                        <a href=""> 
                                            <span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                            <?= $result['username'] ?>
                                        </a>
                                    </li>
                                    <li class="me-2">
                                        <a href=""> 
                                            <span><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                            <?= date('d-M-Y', strtotime($result['publish_date'])) ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="category.php?id=<?= $result['cat_id'] ?>" class="text-primary">
                                            <span><i class="fa fa-tag" aria-hidden="true"></i></span>
                                            <?= $result['catname'] ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        
        <?php include('sidebar.php'); ?>
    </div>

    <!-- Pagination -->
    <?php
    $pagination_sql = "SELECT * FROM blogs";
    $run_q = mysqli_query($config, $pagination_sql);
    $total_post = mysqli_num_rows($run_q);
    $total_pages = ceil($total_post / $limit);

    if($total_post > $limit):
    ?>
        <ul class="pagination pt-2 pb-5">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a href="index.php?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
