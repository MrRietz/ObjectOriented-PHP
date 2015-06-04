<?php

class CMovies {

    private $db = null;
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
    
    private $title  =    null;  
    private $director  =   null;
    private $length  =     0;
    private $year  =    0;   
    private $plot =     null;   
    private $subtext  =   null; 
    private $speech  =     null;
    private $price  =      0;
    private $youtubelink = null;
    private $image  =      null;
    private $image1  =     null;
    private $asideImg  =   null;
    private $create =      null;
    private $acronym =     null;

    public function __construct($database) {
        $this->db = $database;
        $this->title  =       isset($_POST['title']) ? strip_tags($_POST['title']) : null;
        $this->director  =    isset($_POST['director']) ? strip_tags($_POST['director']) : null;
        $this->length  =      isset($_POST['length']) ? strip_tags($_POST['length']) : null;
        $this->year  =        isset($_POST['year']) ? strip_tags($_POST['year']) : null;
        $this->plot =         isset($_POST['plot']) ? strip_tags($_POST['plot']) : null;
        $this->subtext  =     isset($_POST['subtext']) ? strip_tags($_POST['subtext']) : null;
        $this->speech  =      isset($_POST['speech']) ? strip_tags($_POST['speech']) : null;
        $this->price  =       isset($_POST['price']) ? strip_tags($_POST['price']) : null;
        $this->youtubelink =  isset($_POST['youtubelink']) ? strip_tags($_POST['youtubelink']) : null;
        $this->imdblink =     isset($_POST['imdblink']) ? strip_tags($_POST['imdblink']) : null;
        $this->image  =       isset($_POST['image']) ? strip_tags($_POST['image']) : null;
        $this->image1  =      isset($_POST['image1']) ? strip_tags($_POST['image1']) : null;
        $this->asideImg  =    isset($_POST['asideImg']) ? strip_tags($_POST['asideImg']) : null;
        $this->create =       isset($_POST['create'])  ? true : false;
        $this->acronym =      isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
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

    public function GetAllGenres($isHome) {
        // Get all genres that are active
        $sql = '
		  SELECT DISTINCT G.name
		  FROM Genre AS G
		    INNER JOIN Movie2Genre AS M2G
		      ON G.id = M2G.idGenre
		';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        $this->allGenres .= "<div class='btn-group' role='group' aria-label='...'>"; 
        foreach ($res as $val) {
            if ($val->name == $this->genre) {
                $this->allGenres .= "<a class='btn btn-default active'>$val->name</a>";
            } else {
                if($isHome) {
                    $this->allGenres .= "<a class='btn btn-default' href='movies.php" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
                } else {
                    $this->allGenres .= "<a class='btn btn-default' href='" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
                }
            }
        }
        $this->allGenres .= "</div>"; 
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

        $container = "<div class='well'><div class='row'>";
        foreach ($res as $val) {

            $imgPath = IMG_PATH . $val->image;
            $item = "<img src='img.php?src={$imgPath}&amp;width=400&amp;height=260&amp;crop-to-fit'/>";

            $container .= "<div class='col-sm-6 col-md-4'>"
                    . "<a href='?id={$val->id}' title='{$val->title}'>"
                    . "<div class='thumbnail text-center'>"
                    . "{$item}"
                    . "<div class='caption'>{$val->title}"
                    . " ({$val->year})</a>"
                    . " <br><label for='price'><h4>Pris: </h4></label> {$val->price} kr"
                    . "<p><a href='#' class='btn btn-primary' role='button'>Youtube</a> <a href='#' class='btn btn-default' role='button'>IMDB</a></p>"
                    . "</div></div></div>";
        }
        $container .= "</div></div>";

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
                $nav .= "<a class='btn btn-xs btn-primary active'>$val </a> ";
            } else {
                $nav .= "<a class='btn btn-xs btn-default' href='" . $this->getQueryString(array('hits' => $val,'page' => 1)) . "'>$val</a> ";
            }
        }
        return $nav;
    }
    /**
     * Create bootstrap navigation among pages.
     *
     * @param integer $hits per page.
     * @param integer $page current page.
     * @param integer $max number of pages. 
     * @param integer $min is the first page number, usually 0 or 1. 
     * @return string as a link to this page.
     */
    private function getPageNavigation($hits, $page, $max, $min = 1) {
        $nav = ($page != $min) ? "<li><a aria-label='First' href='" . $this->getQueryString(array('page' => $min)) . "'>First</a></li>" : "<li class='disabled'><a href='#' aria-label='First'>First</a></li>";
        $nav .= ($page > $min) ? "<li><a aria-label='Previous' href='" . $this->getQueryString(array('page' => ($page > $min ? $page - 1 : $min))) . "'><span aria-hidden='false'>&laquo;</span></a></li> " : "<li class='disabled'><a href='#' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

        for ($i = $min; $i <= $max; $i++) {
            if ($page == $i) {
                $nav .= "<li class='active'><a href='#'>{$i}</a></li>";
            } else {
                $nav .= "<li><a href='" . $this->getQueryString(array('page' => $i)) . "'>$i</a></li> ";
            }
        }

        $nav .= ($page < $max) ? "<li><a aria-label='Next' href='" . $this->getQueryString(array('page' => ($page < $max ? $page + 1 : $max))) . "'><span aria-hidden='true'>&raquo;</span></a></li> " : "<li class='disabled'><a href='#' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
        $nav .= ($page != $max) ? "<li><a aria-label='Last' href='" . $this->getQueryString(array('page' => $max)) . "'>Last</a></li>" : "<li class='disabled'><a href='#' aria-label='Last'>Last</a></li>";
        return $nav;
    }

    /**
     * Function to create links for sorting
     *
     * @param string $column the name of the database column to sort by
     * @return string with links to order by column.
     */
    private function orderby($column) {
        $nav = "<a class='btn btn-xs btn-default' href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'asc')) . "'>&or;      </a>";
        $nav .= "<a class='btn btn-xs btn-default' href='" . $this->getQueryString(array('orderby' => $column, 'order' => 'desc')) . "'>&and;</a>";
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
    public function getMovieModal($title, $youtubeURL) {
        $modal = "<!-- Button trigger modal -->
        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal'>
          Se Trailer
        </button>

        <!-- Modal -->
        <div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
          <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
              <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='myModalLabel'>Trailer for {$title}</h4>
              </div>
              <div class='modal-body'>
                <div class='embed-responsive embed-responsive-16by9'><iframe class='embed-responsive-item' src='{$youtubeURL}'allowfullscreen></iframe></div>
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
              </div>
            </div>
          </div>
        </div>"; 
        return $modal; 
    }
    public function RenderSingleMovie($id) {
        $movie = $this->getMovieById($id);
        $imgPath = IMG_PATH . $movie->image;
        $modal = $this->getMovieModal($movie->title, "//www.youtube.com/embed/s7EdQ4FqbhY"); 
        $html = "<div class='well'>
        <article>
        <div class='row'>
        <div class='col-xs-6 col-md-3'>
          <a href='img.php?src={$imgPath}' class='thumbnail'>
          <img src='img.php?src={$imgPath}'>
          </a>
        </div>
        <div class='col-xs-6 col-md-3'>
          <a href='img.php?src={$imgPath}' class='thumbnail'>
          <img src='img.php?src={$imgPath}'>
          </a>
        </div>
        </div>

        <div class='plot'> {$movie->plot} </div>
            {$modal}
        </article></div>";

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
        $this->GetAllGenres(false);
        $gallery = $this->getMovieGallery();
        $hitsPerPage = $this->getHitsPerPage(array(2, 4, 8), $this->hits);
        $navigatePage = $this->getPageNavigation($this->hits, $this->page, $this->max);

        $sqlDebug = $this->db->Dump();

        return "
        <div class='row'><form>
        <fieldset>
            <input type=hidden name=genre value='{$this->genre}'/>
            <input type=hidden name=hits value='{$this->hits}'/>
         <div class=col-md-12><label><h4>Välj genre: </h4></label> {$this->allGenres}</div>        
        </fieldset>   
        </form>
        </div>
        <br>
        <div class='well well-sm'><div class='row'>
            <div class='col-md-6'>
                Vi hittade {$this->rows} filmer. {$hitsPerPage} 
            </div>
            <div class='col-md-6'>
                <div class='pull-right'>
                    Sortera på Titel: {$this->orderby('title')} År: {$this->orderby('year')} Pris: {$this->orderby('price')}
                </div>
            </div>
        </div></div>
            {$gallery} 
        <nav class='text-center'>
        <ul class='pagination pagination-md'>
          {$navigatePage}
        </ul>
      </nav>";
    }

    public function RenderThreeLAtest() {

        $sql = "SELECT * FROM movie WHERE published <= NOW() ORDER BY IFNULL(updated,published) DESC LIMIT 3; ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
  
        $container = "<div class='row'>";
        foreach ($res as $val) {
            $imgPath = IMG_PATH . $val->asideImg;
            $modal = $this->getMovieModal($val->title, "//www.youtube.com/embed/s7EdQ4FqbhY"); 
            $container .= "<div class='col-sm-6 col-md-4'>";
            $container .= "<div class='thumbnail text-center'>";
            $container .= "<img src='img.php?src={$imgPath}&amp;width=400&amp;height=260&amp;crop-to-fit'>";
            $container .= " <div class='caption'>";
            $container .= "<h3><a href='movies.php?id={$val->id}'>{$val->title}</a></h3>";
            $container .= "{$modal}<a href='#' class='btn btn-default' role='button'>IMDB</a>";
            $container .= " </div></div></div>";
        }
        $container .= "</div>";
        return $container;
    }

    public function insertContent($fileUpload) {
         $output = null;
        isset($this->acronym) or die('Check: You must login to edit.');
        if ($this->create) {
              $sql = 'INSERT INTO Movie(
                title, 
                director, 
                length, 
                year, 
                plot, 
                image, 
                image1, 
                subtext, 
                speech, 
                asideImg, 
                price, 
                youtubelink, 
                imdblink, 
                published, 
                created,
                updated) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(), NOW(), NOW())';
            
            $url = empty($url) ? null : $url;
            $params = array(
                $this->title, 
                $this->director, 
                $this->length, 
                $this->year, 
                $this->plot, 
                $this->image, 
                $this->image1, 
                $this->subtext, 
                $this->speech,
                $this->asideImg, 
                $this->price, 
                $this->youtubelink,
                $this->imdblink);
            $res = $this->db->ExecuteQuery($sql, $params);
            if ($res) {
                          
                $fileUpload->uploadFile('image');
                $fileUpload->uploadFile('image1');
                $fileUpload->uploadFile('asideImg');
//                header('Location: admin.php');
            } else {
                $output = 'Informationen EJ tillagd.<br><pre>' . print_r($this->db->Dump(), 1) . '</pre>';
            }
        }

        return $output; 
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
