<?php


class CategoryTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Category');
    }

    /**
     * Method returns all category names and category slugs in wich are articles
     * published. Example:
     *
     * array(
     *     'category-slug1' => 'category-name1',
     *     'category-slug2' => 'category-name2'
     * );
     *
     * @return array returns array of article names and article slugs
     */
    public function getCategoryNames() {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT c.category_slug AS category_slug_key, c.name
        FROM category c
        INNER JOIN category_article ca ON ca.category_id = c.category_id
        LEFT JOIN article a ON a.article_id = ca.article_id
        AND a.published IS NOT NULL
        GROUP BY c.category_id
        ORDER BY c.name';
        $statement = $conn->execute($q);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');

        foreach($res as $k => $v) {
            $res[$k] = $res[$k]['name'];
        }
        return $res;
    }

    /**
     * Method returns categories (in which articles have been published) with
     * description and article count. Categories are ordered alhpabethically by
     * name. Example:
     *
     * array(
     *     'category-slug1' => array(
     *         'name' => 'category_name1',
     *         'description' => 'category_description example 1',
     *         'article_count' => '3'
     *     ),
     *     'category-slug2' => array(
     *         'name' => 'category_name2',
     *         'description' => 'category_description example 2',
     *         'article_count' => '1'
     *     )
     * );
     *
     * @return array returns array of categories grouped by category slugs
     */
    public function getCategories() {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT c.category_slug AS category_slug_key, c.name, c.description,
        COUNT(a.article_id) AS article_cnt
        FROM category c
        INNER JOIN category_article ca ON ca.category_id = c.category_id
        LEFT JOIN article a ON a.article_id = ca.article_id
        AND a.published IS NOT NULL
        GROUP BY c.category_id
        ORDER BY c.name';
        $statement = $conn->execute($q);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        return $res;
    }
}