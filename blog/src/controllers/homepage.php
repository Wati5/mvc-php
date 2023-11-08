<?php

require_once('src/model/post.php');
require_once('src/lib/database.php');

function homepage()
{
    $postRepository = new PostRepository();
    $postRepository->connection = new DatabaseConnection();
    $posts = $postRepository->getPosts();

    require('templates/homepage.php');
}
