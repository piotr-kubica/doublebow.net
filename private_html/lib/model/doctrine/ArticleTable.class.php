<?php

class ArticleTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Article');
    }

    /**
     * Method (takes optional: date described by $yearMonth and) returns article
     * count of published articles (in given $yearMonth)
     *
     * @param string $yearMonth year and month of date in MySQL date format: Y M
     * ex. '2010 July'
     * @return integer published article count
     */
    public function getPublishedArticleCount($yearMonth = null) {
        $conn = Doctrine_Manager::connection();
        $q = '';
        $statement = null;

        if($yearMonth) {
            $q = '
                SELECT COUNT(pa.article_id) AS cnt
                FROM
                (   SELECT article_id, published
                    FROM article AS a
                    WHERE a.published IS NOT NULL
                    AND DATE_FORMAT(a.published, \'%Y %M\') = ? ) AS pa
                GROUP BY  DATE_FORMAT(pa.published, \'%Y %M\')
            ';
            $statement = $conn->execute($q, array($yearMonth));
        } else {
            $q = '
                SELECT COUNT(*) AS cnt
                FROM article AS a
                WHERE a.published IS NOT NULL
            ';
            $statement = $conn->execute($q);
        }
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        if(!$res) {
            return 0;
        }
        return intval($res[0]['cnt']);
    }

    /**
     * Method returns years, months and article count of published articles
     * grouped by years. Example:
     *
     * array(
     *     '2010' => array(
     *         'February' => '3'
     *     ),
     *     '2009' => array(
     *         'January' => '3',
     *         'March' => '1',
     *         'April' => '2',
     *         'December' => '3'
     *     )
     * );
     *
     * @return array returns grouped by years article count in months
     */

    public function getPublishedArticleYearMonth() {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT ra.year year_key, GROUP_CONCAT(CONCAT_WS(\'*\', ra.month, ra.cnt) ORDER BY ra.published DESC SEPARATOR \';;\') month_cnt
        FROM (
            SELECT a.published, DATE_FORMAT(a.published, \'%Y\') year,
                DATE_FORMAT(a.published, \'%M\') month, COUNT(a.article_id) cnt
            FROM article a
            WHERE a.published IS NOT NULL
            GROUP BY DATE_FORMAT(a.published, \'%Y %M\')
        ) AS ra
        GROUP BY ra.year
        ORDER BY ra.year DESC
        ';
        $statement = $conn->execute($q);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        $res = ArrayOptimizer::ExplodeItemsToAssocArray_2($res, 'month_cnt', ';;', '*');

        foreach($res as $k => $v) { // each year
            $mk = &$res[$k]["month_cnt"];

            foreach($mk as $kv => $vv) {
                $res[$k][$kv] = $vv;
            }
            unset($res[$k]["month_cnt"]);
        }
        return $res;
    }

    /**
     * Method returns limited (by $limit and $start parameters) array of article
     * prewievs ordered by comments posted to articles. Keys are specified by
     * article slug. Most commented articles are on top. Example result:
     *
     * array(
     *     'article-slug-1' => array(
     *         'title' => 'article title 1',
     *         'date' => '2008-08-03 13:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 13,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     ),
     *     'article-slug-2' => array(
     *         'title' => 'article title 2',
     *         'date' => '2008-08-03 15:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 4,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     )
     * );
     *
     * @param integer $limit max count of articles
     * @param integer $start index of first article to be fetched
     * @return array ordered by article comments article previews
     */
    public function getMostCommentedPublishedArticles($limit, $start = 0) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT a.article_slug AS article_slug_key, a.title,
            a.published AS date, a.abstract, ac.cnt AS comment_count,
            GROUP_CONCAT(CONCAT_WS(\'*\',ct.category_slug,ct.name) SEPARATOR \';;\') AS categories
        FROM (
            SELECT a.article_id, COUNT(c.comment_id) as cnt
            FROM article a
            LEFT JOIN comment c ON a.article_id = c.article_id
            WHERE a.published IS NOT NULL
            GROUP BY a.article_id
        ) AS ac
        LEFT JOIN article a ON ac.article_id = a.article_id
        LEFT JOIN category_article ca ON ac.article_id = ca.article_id
        LEFT JOIN category ct ON ca.category_id = ct.category_id
        GROUP BY a.article_id
        ORDER BY ac.cnt DESC, a.published DESC
        LIMIT ' . intval($start) . ',' . intval($limit) . '
        ';
        $statement = $conn->execute($q);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        $res = ArrayOptimizer::ExplodeItemsToAssocArray_2($res, 'categories', ';;', '*');
        return $res;
    }

    /**
     * Method returns limited (by $limit and $start parameters) article previews
     * ordered by date. Keys are specified by article slug. Latest articles are
     * on top. Example result:
     *
     * array(
     *     'article-slug-1' => array(
     *         'title' => 'article title 1',
     *         'date' => '2008-08-03 14:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 3,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     ),
     *     'article-slug-2' => array(
     *         'title' => 'article title 2',
     *         'date' => '2008-08-03 13:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 4,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     )
     * );
     *
     * @param integer $limit max count of articles
     * @param integer $start index of first article to be fetched
     * @return <type>
     */
    public function getLatestPublishedArticles($limit, $start = 0) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT a.article_slug AS article_slug_key, a.title,
            a.published AS date, a.abstract, ac.cnt AS comment_count,
            GROUP_CONCAT(CONCAT_WS(\'*\',ct.category_slug,ct.name) SEPARATOR \';;\') AS categories
        FROM (
            SELECT a.article_id, COUNT(c.comment_id) as cnt
            FROM article a
            LEFT JOIN comment c ON a.article_id = c.article_id
            WHERE a.published IS NOT NULL
            GROUP BY a.article_id
        ) AS ac
        LEFT JOIN article a ON ac.article_id = a.article_id
        LEFT JOIN category_article ca ON ac.article_id = ca.article_id
        LEFT JOIN category ct ON ca.category_id = ct.category_id
        GROUP BY a.article_id
        ORDER BY a.published DESC
        LIMIT ' . intval($start) . ',' . intval($limit) . '
        ';
        $statement = $conn->execute($q);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        $res = ArrayOptimizer::ExplodeItemsToAssocArray_2($res, 'categories', ';;', '*');
        return $res;
    }

    /**
     * Method returns limited (by $limit and $start parameters) article previews
     * published in date specified by year and month $yearMonth. Example:
     *
     * array(
     *     'article-slug-1' => array(
     *         'title' => 'article title 1',
     *         'date' => '2008-08-03 14:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 3,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     ),
     *     'article-slug-2' => array(
     *         'title' => 'article title 2',
     *         'date' => '2008-08-03 13:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 4,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     )
     * );
     *
     * @param string $yearMonth MySQL date format: Y M, ex. '2010 October'
     * @param integer $limit max count of articles
     * @param integer $start index of first article to be fetched
     * @return array returns array of article previews specified by year and month
     */
    public function getPublishedArticles($yearMonth, $limit, $start = 0) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT a.article_slug AS article_slug_key, a.title,
            a.published AS date, a.abstract, ac.cnt AS comment_count,
            GROUP_CONCAT(CONCAT_WS(\'*\',ct.category_slug,ct.name) SEPARATOR \';;\') AS categories
        FROM (
            SELECT a.article_id, COUNT(c.comment_id) as cnt
            FROM article a
            LEFT JOIN comment c ON a.article_id = c.article_id
            WHERE a.published IS NOT NULL
            AND DATE_FORMAT(a.published, \'%Y %M\') = ?
            GROUP BY a.article_id
        ) AS ac
        LEFT JOIN article a ON ac.article_id = a.article_id
        LEFT JOIN category_article ca ON ac.article_id = ca.article_id
        LEFT JOIN category ct ON ca.category_id = ct.category_id
        GROUP BY a.article_id
        ORDER BY a.published DESC
        LIMIT ' . intval($start) . ',' . intval($limit) . '
        ';
        $statement = $conn->execute($q, array($yearMonth));
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        $res = ArrayOptimizer::ExplodeItemsToAssocArray_2($res, 'categories', ';;', '*');
        return $res;
    }

    /**
     * Method returns categoriy name and article count in a category given by
     * parameter $categorySlug
     *
     * @param string $categorySlug category that which name and article count
     * should be retreived
     * @return array returns 2-element array which keys are 'article_cnt' and
     * 'category_name'
     */
    public function getPublishedArticleCategories($categorySlug) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT COUNT(a.article_id) AS article_cnt, c.name AS category_name
        FROM category c
        INNER JOIN category_article ca ON ca.category_id = c.category_id
        INNER JOIN article a ON a.article_id = ca.article_id
        WHERE c.category_slug = ?
        AND a.published IS NOT NULL
        GROUP BY c.category_id, c.name
        ';
        $statement = $conn->execute($q, array($categorySlug));
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        return $res[0];
    }

    /**
     * Method returns limited (by $limit and $start parameters) article previews
     * in a category given by parameter $categorySlug. Example:
     *
     * array(
     *     'article-slug-1' => array(
     *         'title' => 'article title 1',
     *         'date' => '2008-08-03 14:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 3,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     ),
     *     'article-slug-2' => array(
     *         'title' => 'article title 2',
     *         'date' => '2008-08-03 13:00:00',
     *         'abstract' => 'this is a test abstract',
     *         'comment_count' => 4,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     )
     * );
     *
     * @param string $categorySlug slug of category which article previews should
     * be retrieved 
     * @param integer $limit max count of articles
     * @param integer $start index of first article to be fetched
     * @return array article previews
     */
    public function getPublishedCategoryArticles($categorySlug, $limit, $start = 0) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT a.article_slug AS article_slug_key, a.title,
            a.published AS date, a.abstract, ra.cnt AS comment_count,
            GROUP_CONCAT(CONCAT_WS(\'*\',ct.category_slug,ct.name) SEPARATOR \';;\') AS categories
        FROM (
            SELECT ac.article_id, ac.cnt
            FROM (
                SELECT a.article_id, COUNT(c.comment_id) as cnt
                FROM article a
                LEFT JOIN comment c ON a.article_id = c.article_id
                WHERE a.published IS NOT NULL
                GROUP BY a.article_id
            ) AS ac 
            LEFT JOIN category_article ca2 ON ac.article_id = ca2.article_id
            LEFT JOIN category ct2 ON ca2.category_id = ct2.category_id
            WHERE ct2.category_slug = ?
        ) AS ra
        LEFT JOIN article a ON ra.article_id = a.article_id
        LEFT JOIN category_article ca ON ra.article_id = ca.article_id
        LEFT JOIN category ct ON ca.category_id = ct.category_id
        GROUP BY a.article_id
        ORDER BY a.published DESC
        LIMIT ' . intval($start) . ',' . intval($limit) . '
        ';
        $statement = $conn->execute($q, array($categorySlug));
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        $res = ArrayOptimizer::ExplodeItemsToAssocArray_2($res, 'categories', ';;', '*');
        return $res;
    }

    /**
     * Method returns article given by $articleSlug.
     *
     * array(
     *     'article_id' => 3,
     *     'article_slug' => 'article-slug1',
     *     'title' => 'example title',
     *     'date' => '2008-08-03 14:00:00',
     *     'content' => 'this is an example content',
     *     'comment_count' => '3',
     *     'author' => 'First Lastname',
     *     'categories' => array(
     *         'category-slug1' => 'category_name1',
     *         'category-slug2' => 'category_name2'
     *     ),
     *     'dynjs' => 'file1;file2',
     *     'dyncss' => 'file1;file2'
     * );
     *
     * @param string $articleSlug article slug that identyfies an article
     * @return array array containing article elemets
     */
    public function getArticle($articleSlug) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT a.article_id, a.article_slug, a.title, a.published AS date,
            a.abstract, a.content, ac.cnt AS comment_count, at.name AS author,
            GROUP_CONCAT(CONCAT_WS(\'*\', ct.category_slug,ct.name) SEPARATOR \';;\') AS categories,
            a.dynjs, a.dyncss
        FROM (
            SELECT a.article_id, COUNT(c.comment_id) as cnt
            FROM article a
            LEFT JOIN comment c ON a.article_id = c.article_id
            WHERE a.published IS NOT NULL
            AND a.article_slug = ?
            GROUP BY a.article_id
        ) AS ac
        LEFT JOIN article a ON ac.article_id = a.article_id
        LEFT JOIN category_article ca ON ac.article_id = ca.article_id
        LEFT JOIN category ct ON ca.category_id = ct.category_id
        LEFT JOIN author at ON a.author_id = at.author_id
        GROUP BY ac.article_id
        ';
        $statement = $conn->execute($q, array($articleSlug));
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::ExplodeItemsToAssocArray_2($res, 'categories', ';;', '*');
        
        if(!empty($res[0]['dynjs'])) {
            $res[0]['dynjs'] = explode(';', $res[0]['dynjs']);
        }
        
        if(!empty($res[0]['dyncss'])) {
            $res[0]['dyncss'] = explode(';', $res[0]['dyncss']);
        }
        return $res[0];
    }

    /*
    public function getSearchResultCount($keywords) {
        if(!$keywords) {
            return null;
        }
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT COUNT(keyword) AS hit_cnt, article_id AS article_id_key
        FROM article_index
        WHERE keyword = ?';
        
        for($i = 1, $cnt = count($keywords); $i < $cnt; $i++) {
            $q .= ' OR keyword = ? ';
        }
        $q .= 'GROUP BY article_id
               ORDER BY hit_cnt DESC';
        $statement = $conn->execute($q, $keywords);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        
        foreach($res as $k => $v) {
            $res[$k] = $res[$k]['hit_cnt'];
        }
        return $res;
    }//*/

    // TODO write documentation for searchArticleCnt
    public function searchArticleCnt($keywords) {
        if(!$keywords) {
            return 0;
        }
        $q = '
        SELECT count(ac.article_id) AS cnt
        FROM (
            SELECT article_id
            FROM article_index
            WHERE keyword = ?';

        for($i = 1, $cnt = count($keywords); $i < $cnt; $i++) {
            $q .= ' OR keyword = ? ';
        }
        $q .= '
            GROUP BY article_id
        ) AS sr 
        INNER JOIN (
            SELECT a.article_id
            FROM article a
            WHERE a.published IS NOT NULL
        ) AS ac ON sr.article_id = ac.article_id';
        $conn = Doctrine_Manager::connection();
        $statement = $conn->execute($q, $keywords);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return 0;
        }
        return intval($res[0]['cnt']);
    }

    /**
     * Method returns limited (by $limit and $start parameters) article previews
     * that conatin keywords given by parameter $keywords in title and abstract
     * ordered by count of keywords found. Article preview with most keywords is
     * on top. Example:
     *
     * array(
     *     'article-slug-1' => array(
     *         'title' => 'article title 1',
     *         'date' => '2008-08-03 14:00:00',
     *         'abstract' => 'this is a test abstract keyword1 keyword2',
     *         'comment_count' => 3,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     ),
     *     'article-slug-2' => array(
     *         'title' => 'article title 2',
     *         'date' => '2008-08-03 13:00:00',
     *         'abstract' => 'this is a test abstract keyword3',
     *         'comment_count' => 4,
     *         'categories' => array(
     *             'category-slug-1' => 'category name 1',
     *             'category-slug-2' => 'category name 2'
     *         )
     *     )
     * );
     *
     * @param array $keywords array of searched keywords in articles
     * @param integer $limit max count of articles
     * @param integer $start index of first article to be fetched
     * @return array returns array of article previews that conatin searched
     * keywords
     */
    public function searchArticles($keywords, $limit, $start = 0) {
        if(!$keywords) {
            return null;
        }       
        $q = '
        SELECT a.article_slug AS article_slug_key, a.title,
            a.published AS date, a.abstract, ac.cnt AS comment_count,
            GROUP_CONCAT(CONCAT_WS(\'*\',ct.category_slug,ct.name) SEPARATOR \';;\') AS categories
        FROM (
            SELECT COUNT(keyword) AS hit_cnt, article_id
            FROM article_index
            WHERE keyword = ?';

        for($i = 1, $cnt = count($keywords); $i < $cnt; $i++) {
            $q .= ' OR keyword = ? ';
        }
        $q .= 'GROUP BY article_id
            ORDER BY hit_cnt DESC
        ) AS sr 
        INNER JOIN (
            SELECT a.article_id, COUNT(c.comment_id) as cnt
            FROM article a
            LEFT JOIN comment c ON a.article_id = c.article_id
            WHERE a.published IS NOT NULL
            GROUP BY a.article_id
        ) AS ac ON sr.article_id = ac.article_id
        LEFT JOIN article a ON ac.article_id = a.article_id
        LEFT JOIN category_article ca ON ac.article_id = ca.article_id
        LEFT JOIN category ct ON ca.category_id = ct.category_id
        GROUP BY a.article_id
        ORDER BY sr.hit_cnt DESC, a.published DESC
        LIMIT ' . intval($start) . ',' . intval($limit);
        $conn = Doctrine_Manager::connection();
        $statement = $conn->execute($q, $keywords);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        $res = ArrayOptimizer::ExplodeItemsToAssocArray_2($res, 'categories', ';;', '*');
        return $res;
    }

    /**
     * Method returns limited (by $limit and $start parameters) array of article
     * basic informations: article slug, (one random) category slug and title in
     * form of an array:
     *
     * array(
     *     'article-slug1' => array(
     *         'title' => 'article title 1',
     *         'category_slug' => 'category-slug-x'
     *     ),
     *     'article-slug2' => array(
     *         'title' => 'article title 2'
     *         'category_slug' => 'category-slug-y'
     *     )
     * );
     *
     * @param integer $limit max count of articles
     * @param integer $start index of first article to be fetched
     * @return array returns array of articles article slug, random category slug
     * and title
     */
    public function getLatestPublishedArticleTitles($limit, $start = 0) {
        $conn = Doctrine_Manager::connection();
        $q = '
        SELECT a.article_slug AS article_slug_key, a.title, MIN(ct.category_slug) as category_slug
        FROM article a
        LEFT JOIN category_article ca ON a.article_id = ca.article_id
        LEFT JOIN category ct ON ca.category_id = ct.category_id
        WHERE a.published IS NOT NULL
        GROUP BY a.article_id
        ORDER BY a.published DESC
        LIMIT ' . intval($start) . ',' . intval($limit) . '
        ';
        $statement = $conn->execute($q);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }
        $res = ArrayOptimizer::MakeElementValueToKey($res, '_key');
        return $res;
    }
}