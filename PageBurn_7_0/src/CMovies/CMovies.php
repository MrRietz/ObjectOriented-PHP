<?php

class CMovies {

    private $db = null;
    private $title = null;
    private $genre = null;
    private $allGenres = null;
    private $hits = 8;
    private $page = 1;
    private $year1 = null;
    private $year2 = null;
    private $params = array();
    private $orderby = 'id';
    private $order = 'asc';
    private $max = 0;
    private $rows = 0;

    public function __construct($database) {
        $this->db = $database;
    }

    private function SetVariables() {
        $this->title = isset($_GET['title']) ? $_GET['title'] : null;
        $this->genre = isset($_GET['genre']) ? $_GET['genre'] : null;
        $this->hits = isset($_GET['hits']) ? $_GET['hits'] : 8;
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->orderby = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
        $this->order = isset($_GET['order']) ? strtolower($_GET['order']) : 'asc';

        // Check that incoming parameters are valid
        is_numeric($this->hits) or die('Check: Hits must be numeric.');
        is_numeric($this->page) or die('Check: Page must be numeric.');
    }

    private function GetTitleSql() {
        // Select by title
        if ($this->title) {
            $this->params[] = $this->title;
            return ' AND title LIKE ?';
        } else {
            return null;
        }
    }

    public function GetAllGenres() {
        // Get all genres that are active
        $sql = '
		  SELECT DISTINCT G.name
		  FROM Genre AS G
		    INNER JOIN Movie2Genre AS M2G
		      ON G.id = M2G.idGenre
		';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        foreach ($res as $val) {
            if ($val->name == $this->genre) {
                $this->allGenres .= "$val->name ";
            } else {
                $this->allGenres .= "<a href='" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
            }
        }
        return $this->allGenres;
    }

    public function GetAllGenresHome() {
        // Get all genres that are active
        $sql = '
		  SELECT DISTINCT G.name
		  FROM Genre AS G
		    INNER JOIN Movie2Genre AS M2G
		      ON G.id = M2G.idGenre
		';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        foreach ($res as $val) {
            if ($val->name == $this->genre) {
                $this->allGenres .= "$val->name ";
            } else {
                $this->allGenres .= "<a href='movies.php" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
            }
        }
        return $this->allGenres;
    }

    private function GetByYearSql() {
        $where = null;
        // Select by year
        if ($this->year1) {
            $where .= ' AND year >= ?';
            $this->params[] = $this->year1;
        }
        if ($this->year2) {
            $where .= ' AND year <= ?';
            $this->params[] = $this->year2;
        }
        if (isset($where)) {
            return $where;
        } else {
            return null;
        }
    }

    private function GetGenreSql() {
        // Select by genre
        if ($this->genre) {
            $this->params[] = $this->genre;
            return ' AND G.name = ?';
        } else {
            return null;
        }
    }

    private function GetWhereStatement() {
        //WHERE 1 lets us use additional conditions with AND... it has no impact on execution time.
        $where = " WHERE 1 " . $this->GetTitleSql() . $this->GetByYearSql() . $this->GetGenreSql();
        if ($where == " WHERE 1 ") {
            return null;
        } else {
            return $where;
        }
    }

    private function GetLimit() {
        // Pagination
        if ($this->hits && $this->page) {
            return " LIMIT {$this->hits} OFFSET " . (($this->page - 1) * $this->hits);
        }
    }

    private function GetMoviesFromDB() {
        $sqlStart = '
	    SELECT M.*,
            GROUP_CONCAT(G.name) AS genre
	    FROM Movie AS M
	    LEFT OUTER JOIN Movie2Genre AS M2G
	    ON M.id = M2G.idMovie
	    INNER JOIN Genre AS G
	    ON M2G.idGenre = G.id';

        $groupby = ' GROUP BY M.id';
        $sort = " ORDER BY {$this->orderby} {$this->order}";
        $where = $this->GetWhereStatement();

        // Get max pages for current query, for navigation
        $sql = "
		  SELECT
		    COUNT(id) AS rows
		  FROM 
		  (
		    $sqlStart $where $groupby
		  ) AS Movie
		";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $this->params);
        $this->rows = $res[0]->rows;
        $this->max = ceil($this->rows / $this->hits);

        $sql = $sqlStart . $where . $groupby . $sort . $this->GetLimit();
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $this->params);

        return $res;
    }

    public function getMovieById($id) {

        $sql = "SELECT * FROM Movie WHERE id=?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));

        return $res[0];
    }

    private function getHtmlTable() {
        $container = " <table>
		<tr>
			<th>Row</th> 
			<th>Id " . $this->orderby('id') . "</th> 
			<th>Movie Title " . $this->orderby('title') . "</th>
			<th>Image</th>
			<th>Year " . $this->orderby('year') . "</th> 
		</tr>";

        $res = $this->GetMoviesFromDB();

        foreach ($res AS $key => $obj) {
            $container .= "
			<tr>
				<td>{$key}</td>
				<td>{$obj->id}</td>
				<td>{$obj->title}</td>
				<td><img src='" . IMG_PATH . "'{$obj->image}' width='80' height='40'></td>
				<td>{$obj->year}</td>
			</tr>
			 ";
        }
        $container .=" </table>";
        return $container;
    }

    private function getMovieGallery() {

        $res = $this->GetMoviesFromDB();

        $container = "<ul class='gallery'>";

        foreach ($res as $val) {

            $imgPath = IMG_PATH . $val->image;
            $item = "<img src='img.php?src={$imgPath}&amp;width=200&amp;height=260&amp;crop-to-fit'/>";

            $container .= "<li><a href='?id={$val->id}' title='{$val->title}'>"
                    . "<figure>{$item}<figcaption><span>{$val->title}</span>"
                    . " ({$val->year})<br>{$val->price} kr</figcaption></figure></a></li>";
        }
        $container .= "</ul>";

        return $container;
    }

    /**
     * Use the current querystring as base, modify it according to $options and return the modified query string.
     *
     * @param array $options to set/change.
     * @param string $prepend this to the resulting query string
     * @return string with an updated query string.
     */
    private function getQueryString($options = array(), $prepend = '?') {
        // parse query string into array
        $query = array();
        parse_str($_SERVER['QUERY_STRING'], $query);

        // Modify the existing query string with new options
        $query = array_merge($query, $options);

        // Return the modified querystring
        return $prepend . htmlentities(http_build_query($query));
    }

    /**
     * Create links for hits per page.
     *
     * @param array $hits a list of hits-options to display.
     * @param array $current value.
     * @return string as a link to this page.
     */
    private function getHitsPerPage($hits, $current = null) {
        $nav = "Träffar per sida: ";
        foreach ($hits AS $val) {
            if ($current == $val) {
                $nav .= "$val ";
            } else {
                $nav .= "<a href='" . $this->getQueryString(array('hits' => $val)) . "'>$val</a> ";
            }
        }
        return $nav;
    }

    /**
     * Create navigation among pages.
     *
     * @param integer $hits per page.
     * @param integer $page current page.
     * @param integer $max number of pages. 
     * @param integer $min is the first page number, usually 0 or 1. 
     * @return string as a link to this page.
     */
    private function getPageNavigation($hits, $page, $max, $min = 1) {
        $nav = ($page != $min) ? "<a href='" . $this->getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> " : '&lt;&lt; ';
        $nav .= ($page > $min) ? "<a href='" . $this->getQueryString(array('page' => ($page > $min ? $page - 1 : $min))) . "'>&lt;</a> " : '&lt; ';

        for ($i = $min; $i <= $max; $i++) {
            if ($page == $i) {
                $nav .= "$i ";
            } else {
                $nav .= "<a href='" . $this->getQueryString(array('page' => $i)) . "'>$i</a> ";
            }
        }

        $nav .= ($page < $max) ? "<a href='" . $this->getQueryString(array('page' => ($page < $max ? $page + 1 : $max))) . "'>&gt;</a> " : '&gt; ';
        $nav .= ($page != $max) ? "<a href='" . $this->getQueryString(array('page' => $max)) . "'>&gt;&gt;</a> " : '&gt;&gt; ';
        return $nav;
    }

    /**
     * Function to create links for sorting
     *
     * @param string $column the name of the database column to sort by
     * @return string with links to order by column.
     */
    private function orderby($column) {
        $nav = "<a href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'asc')) . "'>&or;      </a>";
        $nav .= "<a href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'desc')) . "'>&and;</a>";
        return "<span class='orderby'>" . $nav . "</span>";
    }

    public function RenderMovieTable() {
        $this->SetVariables();
        $table = $this->GetHTMLTable();
        $this->GetAllGenres();

        $hitsPerPage = $this->getHitsPerPage(array(2, 4, 8), $this->hits);
        $navigatePage = $this->getPageNavigation($this->hits, $this->page, $this->max);
        $sqlDebug = $this->db->Dump();
        return "<form>
			<fieldset>
			<legend>Sök</legend>
			<input type=hidden name=genre value='{$this->genre}'/>
			<input type=hidden name=hits value='{$this->hits}'/>
			<p><label>Titel (delsträng, använd % som wildcard): <input type='search' name='title' value='{$this->title}'/></label></p>
			
			<p><label>Välj genre:</label> {$this->allGenres}</p>
			<p><input type='submit' name='submit' value='Sök'/></p>
			<p><a href='?'>Visa alla</a></p>
			</fieldset>
		</form>
			
			<div class='dbtable'>
			  <div class='rows'>{$this->rows} träffar. {$hitsPerPage}</div>
			  {$table}
			  <div class='pages'>{$navigatePage}</div>
			</div>";
    }

    public function RenderSingleMovie($id) {

        $movie = $this->getMovieById($id);
        $imgPath = IMG_PATH . $movie->image;
        $html = "<div class='movie-single'>";
        $html .= "<h1>{$movie->title}</h1>";
        $html .= "<article>";
        $html .= "<img src='img.php?src={$imgPath}'>";
        $html .= "<div class='plot'> {$movie->plot} </div>";
        $html .= "</article></div>";

        return $html;
    }

    public function RenderSingleMovieAside($id) {
        $movie = $this->getMovieById($id);

        $imgPath = IMG_PATH . $movie->asideImg;
        $html = "<div class='singlemovie'>";
        $html .= "<article>";
        $html .= "<img src='img.php?src={$imgPath}&amp;width=200&amp;height=260&amp;crop-to-fit'/>";
        $html .= "<div><h3>Regissör: </h3> {$movie->director} </div>";
        $html .= "<div><h3>Title: </h3>{$movie->title} </div>";
        $html .= "<div><h3>Year: </h3>{$movie->length} min</div>";
        $html .= "<div><h3>Year: </h3>{$movie->year} </div>";
        $html .= "<div><h3>Text: </h3>{$movie->subtext}</div>";
        $html .= "<div><h3>Pris: </h3>{$movie->price} </div>";

        $html .= "</article></div>";

        return $html;
    }

    //Prints the movies gallery style
    public function RenderMovies() {

        $this->SetVariables();
        $this->GetAllGenres();
        $gallery = $this->getMovieGallery();
        $hitsPerPage = $this->getHitsPerPage(array(2, 4, 8), $this->hits);
        $navigatePage = $this->getPageNavigation($this->hits, $this->page, $this->max);

        $sqlDebug = $this->db->Dump();

        return "<form>
                    <fieldset>
                        <legend>Sök</legend>
                        <input type=hidden name=genre value='{$this->genre}'/>
                        <input type=hidden name=hits value='{$this->hits}'/>
                        <p><label>Välj genre:</label> {$this->allGenres}</p>
                        <p><a href='?'>Visa alla</a></p>
                    </fieldset>   
                </form>
                <br>
                <div class='movienav'>
                    <div class='rows'>
                        Vi hittade {$this->rows} filmer. {$hitsPerPage} 
                    </div>
                    <div class='orderby'>
                        Sortera på: Titel {$this->orderby('title')}| År{$this->orderby('year')}| Pris{$this->orderby('price')}
                    </div>
                </div>
                <br>
                    {$gallery} 
		<div class='pages'>{$navigatePage}</div>";
    }

    public function RenderThreeLAtest() {

        $sql = "SELECT * FROM movie WHERE published <= NOW() ORDER BY IFNULL(updated,published) DESC LIMIT 3; ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);


        $container = "<ul class='gallery'>";
        foreach ($res as $val) {

            $imgPath = IMG_PATH . $val->asideImg;
            $container .= "<li><a href='movies.php?id={$val->id}'>";
            $container .= "<figure class='homeMovie'>";
            $container .= "<img src='img.php?src={$imgPath}&amp;width=200&amp;height=260&amp;crop-to-fit'>";
            $container .= "<figcaption>{$val->title}</figcaption>";
            $container .= "</figure>";
            $container .= "</a></li>";
        }
        $container .= "</ul>";
        return $container;
    }

    /**
     * Display error message.
     *
     * @param string $message the error message to display.
     */
    private function errorMessage($message) {
        header("Status: 404 Not Found");
        die('gallery.php says 404 - ' . htmlentities($message));
    }

}
