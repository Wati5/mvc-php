<?php
namespace Application\Model\Comment;
class Comment
{
    public $author;
    public $frenchCreationDate;
    public $comment;
}

class CommentRepository{
    public $connection;
    function getComments(string $post): array
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%imin%ss') AS french_creation_date FROM comments WHERE post_id = ? ORDER BY comment_date DESC"
        );
        $statement->execute([$post]);

        $comments = [];
        while (($row = $statement->fetch())) {
            $comment = new Comment();
            $comment->author = $row['author'];
            $comment->frenchCreationDate = $row['french_creation_date'];
            $comment->comment = $row['comment'];

            $comments[] = $comment;
        }

        return $comments;
    }

    function createComment(string $post, string $author, string $comment)
    {
        $database = commentDbConnect();
        $statement = $database->prepare(
            'INSERT INTO comments(post_id, author, comment, comment_date) VALUES(?, ?, ?, NOW())'
        );
        $affectedLines = $statement->execute([$post, $author, $comment]);

        return ($affectedLines > 0);
    }

    function commentDbConnect()
    {
        $database = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', 'root');

        return $database;
    }
}