<?php


class CommentTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Comment');
    }

    /**
     * Method returns comments on article id given by parameter $articleId sorted
     * by date. The latest comment is on top. Example:
     *
     * array(
     *     '5' => array(
     *         'author' => 'John Newton',
     *         'date' => '2008-08-03 14:00:00',
     *         'content' => 'i like this article',
     *         'web' => 'http:www.mysite.com',
     *         'mail' => 'mymail@mail.com'
     *     ),
     *     '6' => array(
     *         'author' => 'Roman Smith',
     *         'date' => '2008-08-03 14:00:00',
     *         'content' => 'i think this is interesting',
     *         'web' => null
     *         'mail' => null
     *     )
     * );
     *
     * @param integer $articleId ID of article which comment should be retrieved
     * @return array returns array of comments
     */
    public static function getComments($articleId) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT comment_id as comment_id_key, author, comment.date, content, web, mail
        FROM comment
        WHERE article_id = ?
        ORDER BY comment.date DESC';
        $statement = $conn->execute($q, array($articleId));
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        return $res;
    }
}