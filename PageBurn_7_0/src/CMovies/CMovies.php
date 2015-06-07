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
    private $target_dir = 'img/movies/';
    
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
    private $image2  =   null;
    private $create =      null;
    private $update =      null; 
    private $remove =      null; 
    private $noRemove = null; 
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

        $this->create =       isset($_POST['create'])  ? true : false;
        $this->update =       isset($_POST['update'])  ? true : false;
        $this->remove =       isset($_POST['remove'])  ? true : false;
        $this->noRemove =     isset($_POST['noRemove'])  ? true : false;
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
    
    public function GetInsertForm() {
        $sql = 'SELECT * FROM Genre'; 
        $checkBoxes = "<label class='control-label'>Genre: </label>"; 
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        foreach($res as $val) {
            $checkBoxes .= "<div class='checkbox'>
                        <label><input name='genres[]' type='checkbox' value='{$val->id}'>{$val->name}</label>
                       </div>"; 
        }
        $output = "<form method=post enctype='multipart/form-data'>
          <fieldset>
          <div class='row'>
            <div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>
              <label class='control-label'>Titel: </label><input class='form-control' type='text'     name='title'/>
              <label class='control-label'>Regissör: </label><input class='form-control' type='text'  name='director'/>
              <label class='control-label'>Längd i minuter: </label><input class='form-control' type='number'   name='length'/>
              <label class='control-label'>Årtal: </label><input class='form-control' type='number'   name='year'/>
              <label class='control-label'>Subs: </label><input class='form-control' type='text'      name='subtext'/>
              <label class='control-label'>Språk: </label><input class='form-control' type='text'     name='speech'/>
              <label class='control-label'>Pris: </label><input class='form-control' type='number'    name='price'/>
              <label class='control-label'>Youtube: </label><input class='form-control' type='url'    name='youtubelink'/>
              <label class='control-label'>IMDB: </label><input class='form-control' type='url'       name='imdblink'/>      
           </div>
           <div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>
              <label class='control-label'>Handling: </label><textarea rows='12' cols='50' class='form-control' name='plot'/></textarea>
              <label class='control-label'>Huvudbild: </label><input  type='file' name='image' id='uploadfile'>
              <label class='control-label'>Sido bild: </label><input  type='file' name='image1' id='uploadfile'>
              <label class='control-label'>Header bild: </label><input  type='file' name='image2' id='uploadfile'>
              
            </div>
            <div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>
            {$checkBoxes}
                <div class='row-spacing'></div><input class='btn btn-primary' type='submit' name='create' value='Lägg till'/>
            </div>
            </div>
            </fieldset>
        </form>"; 

        return $output; 
    }
    public function GetEditForm($id, $output) {
  
        $movie = $this->getMovieById($id); 
//        $this->sanitizeVariables($content); 
        $checkBoxes = $this->getMovieGenreById($id); 
       
        $html = "<form method=post>
        <fieldset>
        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4'>
              <label class='control-label'>Titel: </label><input class='form-control' type='text'     value='{$movie->title}' name='title'/>
              <label class='control-label'>Regissör: </label><input class='form-control' type='text'  value='{$movie->director}' name='director'/>
              <label class='control-label'>Längd i minuter: </label><input class='form-control' type='number' value='{$movie->length}'   name='length'/>
              <label class='control-label'>Årtal: </label><input class='form-control' type='number'   value='{$movie->year}' name='year'/>
              <label class='control-label'>Subs: </label><input class='form-control' type='text'      value='{$movie->subtext}' name='subtext'/>
              <label class='control-label'>Språk: </label><input class='form-control' type='text'     value='{$movie->speech}' name='speech'/>
              <label class='control-label'>Pris: </label><input class='form-control' type='number'    value='{$movie->price}' name='price'/>
              <label class='control-label'>Youtube: </label><input class='form-control' type='url'    value='{$movie->youtubelink}' name='youtubelink'/>
              <label class='control-label'>IMDB: </label><input class='form-control' type='url'       value='{$movie->imdblink}' name='imdblink'/>      
           </div>
           <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4'>
              <label class='control-label'>Handling: </label><textarea rows='12' cols='50' class='form-control' name='plot'/>{$movie->plot}</textarea>   
              <label class='control-label'>Huvudbild: </label><input class='form-control' type='text' name='image' value='{$movie->image}' id='uploadfile'>
              <label class='control-label'>Sido bild: </label><input  class='form-control' type='text' name='image1' value='{$movie->image1}' id='uploadfile'>
              <label class='control-label'>Header bild: </label><input  class='form-control' type='text' name='image2' value='{$movie->image2}' id='uploadfile'>
            </div>
            <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4'>
            {$checkBoxes}
                <div class='row-spacing'></div><input class='btn btn-primary' type='submit' name='update' value='Uppdatera'/>
                $output
            </div>
        </fieldset>
        </form>";
        return $html; 
    }
     public function GetRemoveForm($id, $output) {
        $content = $this->getMovieById($id); 
        
        $html = "<form method=post>
            <fieldset>
            <input type='hidden' name='id' value='{$id}'/>
            <p>Vill du verkligen ta bort inlägget med titeln: {$content->title}</p>
            <div class='btn-group' role='group' aria-label='remove'><input class='btn btn-warning' type='submit' name='remove' value='Ja'/>
            <input class='btn btn-default' type='submit' name='noRemove' value='Nej'/></div>
            <output>{$output}</output>
            </fieldset>
            </form>"; 
        return $html; 
    }
    
    public function GetAllGenres($isHome) {
        // Get all genres that are active
        $sql = 'SELECT DISTINCT G.name
            FROM Genre AS G
            INNER JOIN Movie2Genre AS M2G
            ON G.id = M2G.idGenre
		';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
       
        $this->allGenres .= "<div class='btn-group' role='group' aria-label='...'>"; 
         if(!$isHome) {
            $this->allGenres .= "<a id='allButton' class='btn btn-default' >all</a>";
        }
        foreach ($res as $val) {
            if ($val->name == $this->genre) {
                $this->allGenres .= "<a class='btn btn-default active'>$val->name</a>";
            } else {
                if($isHome) {
                    $this->allGenres .= "<a class='btn btn-default' href='movies.php" . $this->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
                } else {
                    $this->allGenres .= "<a class='btn btn-default' href='" . $this->getQueryString(array('page' => 1,'genre' => $val->name)) . "'>{$val->name}</a> ";
//                    $url = "movies.php?genre={$val->name}";
//                    $this->allGenres .= "<a class='btn btn-default genre-btn' id='{$val->name}' data-url='{$url}'> {$val->name}</a> ";
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

    public function getAllMovies() {
        $sql = 'SELECT * FROM Movie'; 
        $res =  $this->db->ExecuteSelectQueryAndFetchAll($sql);
        
        $html = ""; 
        foreach($res as $movie) {
            $html .= "<p><li>{$movie->title} <a class='btn btn-primary' href='movie_edit.php?id={$movie->id}'>editera</a>"
            . " <a class='btn btn-default' href='movie_remove.php?id={$movie->id}'>ta bort</a></li></p>";
        }
        return $html;
    }
    
    public function getMovieById($id) {

        $sql = "SELECT * FROM Movie WHERE id=?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));

        return $res[0];
    }
    
    public function getMovieGenreById($id) {
        $sql = 'SELECT * FROM Movie2Genre
                WHERE idMovie = ?';
        $checkedBoxes = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));
      
        $sql = 'SELECT * FROM Genre'; 
        $allGenres = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        $checkBoxes = "<label class='control-label'>Genre: </label>"; 
        $found; 
        foreach($allGenres as $genre) {
            $found = false;
            foreach($checkedBoxes as $checkedBoxId) {
                if($genre->id == $checkedBoxId->idGenre) { 
                    $found = true;
                }
            }
            if($found) {
                  $checkBoxes .= "<div class='checkbox'>
                        <label><input name='genres[]' type='checkbox' value='{$genre->id}' checked>{$genre->name}</label>
                       </div>";
            } else {
                $checkBoxes .= "<div class='checkbox'>
                <label><input name='genres[]' type='checkbox' value='{$genre->id}'>{$genre->name}</label></div>";
            }
        }
        return $checkBoxes; 
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

            $imgPath = IMG_PATH . $val->image1;
            $item = "<img src='img.php?src={$imgPath}&amp;width=400&amp;height=260&amp;crop-to-fit'/>";

            $container .= "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-4'>"
                    . "<a href='?id={$val->id}' title='{$val->title}'>"
                    . "<div class='thumbnail text-center'>"
                    . "{$item}"
                    . "<div class='caption'>{$val->title}"
                    . " ({$val->year})</a>"
                    . " <br><label for='price'><h4>Pris: </h4></label> {$val->price} kr<br>"
                    . " {$this->getMovieModal($val->id, $val->title, $val->youtubelink)}<a href='{$val->imdblink}' class='btn btn-default' role='button'>IMDB</a>"
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
    public function getMovieModal($modalId, $title, $youtubeURL) {
        $modal = "<!-- Button trigger modal -->
        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#{$modalId}'>
          Se Trailer
        </button>

        <!-- Modal -->
        <div class='modal fade' id='{$modalId}' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
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
    public function RenderSingleMovie($movie) {
        $imgPaths = array(IMG_PATH . $movie->image);
        
        $modal = $this->getMovieModal($movie->id,$movie->title, $movie->youtubelink); 
        $html = "
        <article>
        <div class='row'>
            <div class='media'>
              <div class='media-left'>
              <div class='col-xs-6 col-sm-3 col-md-3'>
                <a href='img.php?src={$imgPaths[0]}' class='thumbnail'>
                  <img src='img.php?src={$imgPaths[0]}'>
                </a>
              </div>
              </div>
              <div class='media-body'>
              <div class='col-xs-12 col-sm-8 col-md-8'>
                <h4 class='media-heading'>Handling</h4>
                {$movie->plot}
                    </div>
              </div>
            </div>
        </div>
            {$modal}<a href='{$movie->imdblink}' target='_blank' class='btn btn-default' role='button'>IMDB</a>
        </article>";
            
        return $html;
    }

    public function RenderSingleMovieAside($id) {
        $movie = $this->getMovieById($id);

        $imgPath = IMG_PATH . $movie->image1;
        $html = "<div class='singlemovie'>
         <article>
            <img width='100%' src='img.php?src={$imgPath}&amp;width=400&amp;height=260&amp;crop-to-fit'/>
             <div class='list-group'>
                <a class='list-group-item'>     
                 <h4 class='list-group-item-heading'>Title</h4>
                 <p class='list-group-item-text'>{$movie->title}</p>
                </a>
                <a class='list-group-item'>     
                 <h4 class='list-group-item-heading'>Regissör</h4>
                 <p class='list-group-item-text'>{$movie->director}</p>
                </a>
                <a class='list-group-item'>     
                 <h4 class='list-group-item-heading'>Längd</h4>
                 <p class='list-group-item-text'>{$movie->length} min</p>
                </a>
                <a class='list-group-item'>     
                 <h4 class='list-group-item-heading'>År</h4>
                 <p class='list-group-item-text'>{$movie->year}</p>
                </a>
                <a class='list-group-item'>     
                 <h4 class='list-group-item-heading'>Språk</h4>
                 <p class='list-group-item-text'>{$movie->speech}</p>
                </a>
                <a class='list-group-item'>     
                 <h4 class='list-group-item-heading'>Text</h4>
                 <p class='list-group-item-text'>{$movie->subtext}</p>
                </a>
                <a class='list-group-item'>     
                 <h4 class='list-group-item-heading'>Pris</h4>
                 <p class='list-group-item-text'>{$movie->price} kr</p>
                </a>
             </div>
          </article></div>";
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

        return "<div id='movieSection'>
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
            <div class='col-sm-5 col-md-5 col-lg-6'>
                {$hitsPerPage}
            </div>
            <div class='col-sm-7 col-md-7 col-lg-6'>
                <div class='pull-right'>
                    Sortera på Titel: {$this->orderby('title')} År: {$this->orderby('year')} Pris: {$this->orderby('price')}
                </div>
            </div>
        </div></div>
        <div class='alert alert-success' role='alert'> Vi hittade {$this->rows} filmer.</div>
            {$gallery} 
        <nav class='text-center'>
        <ul class='pagination pagination-md'>
          {$navigatePage}
        </ul>
      </nav></div>";
    }
    public function GetAdminToolbar() {
            return '<div class="row"><div class="col-xs-12 col-sm-3 col-md-3"><div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="moviesDropdown" data-toggle="dropdown" aria-expanded="true">
              Filmer
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="moviesDropdown">
              <li role="presentation"><a role="menuitem" tabindex="-1" href="movie_create.php">Lägg till film</a></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="movie_view.php">Editera film</a></li>
            </ul>
          </div></div><div class="col-xs-12 col-sm-3 col-md-3">
          <div class="dropdown">
            <button class="btn btn-info dropdown-toggle" type="button" id="newsDropdown" data-toggle="dropdown" aria-expanded="true">
              Nyheter
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="newsDropdown">
              <li role="presentation"><a role="menuitem" tabindex="-1" href="news_create.php">Lägg till nyhet</a></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="news_view.php">Editera nyhet</a></li>
            </ul>
          </div></div>
              <div class="col-xs-12 col-sm-3 col-md-3">
               <form method=post>
              <p><input type="submit" class="btn btn-primary" name="logout" value="Logga ut"/></p>
              </form>
             </div>';
    }
    
    public function RenderThreeLAtest() {

        $sql = "SELECT * FROM Movie WHERE published <= NOW() ORDER BY IFNULL(updated,published) DESC LIMIT 3; ";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        return $res;
    }

    public function insertContent($fileUpload) {
         $output = null;
         $res = null; 
        isset($this->acronym) or die('Check: You must login to edit.');
        if ($this->create) {
                 
            $this->image  =   basename($_FILES['image']['name']);
            $this->image1  =  basename($_FILES['image1']['name']);
            $this->image2  =  basename($_FILES['image2']['name']);
                                  
                $file1 = $fileUpload->uploadFile($this->target_dir,'image');
                $file2 = $fileUpload->uploadFile($this->target_dir,'image1');
                $file3 = $fileUpload->uploadFile($this->target_dir,'image2');
            if($file1[1] && $file2[1] && $file3[1]) {
                $sql = 'INSERT INTO Movie(
                  title, 
                  director, 
                  length, 
                  year, 
                  plot, 
                  image, 
                  image1, 
                  image2,
                  subtext, 
                  speech,  
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
                  $this->image2, 
                  $this->subtext, 
                  $this->speech,
                  $this->price, 
                  $this->youtubelink,
                  $this->imdblink);
              $res = $this->db->ExecuteQuery($sql, $params);
            } else {
                $output .= $file1[0];
                $output .= $file2[0];
                $output .= $file3[0];
            }
       
            if ($res) {
                $movie_id = $this->db->LastInsertId();
                if (isset($_POST['genres'])) {
                    $sql = 'INSERT INTO Movie2Genre(
                            idMovie,
                            idGenre)
                            VALUES (?, ?)';

                    foreach($_POST['genres'] as $key => $value) {
                        $params = array($movie_id, $value);
                        $res = $this->db->ExecuteQuery($sql, $params); 
                    }
                    $output = '<div class="alert alert-success" role="alert">Film uppdaterad.</div>';
                }
            } else {
                $output .= '<div class="alert alert-danger" role="alert">Filmen EJ tillagd.<br><pre>' . print_r($this->db->Dump(), 1) . '</pre></div>';
            }
        }

        return $output; 
    }
    
    public function updateContent($id) {
        $movie = $this->getMovieById($id);
        $output = null;
        
        $this->image =     isset($_POST['image']) ? strip_tags($_POST['image']) : null;
        $this->image1 =      isset($_POST['image1']) ? strip_tags($_POST['image1']) : null;
        $this->image2 =       isset($_POST['image2']) ? strip_tags($_POST['image2']) : null;
        
        
        if ($this->update) {
            $sql = '
                UPDATE Movie SET
                    title = ?, 
                    director = ?, 
                    length = ?, 
                    year = ?, 
                    plot = ?, 
                    image = ?, 
                    image1 = ?,
                    image2 = ?, 
                    subtext = ?, 
                    speech = ?,  
                    price = ?, 
                    youtubelink = ?, 
                    imdblink = ?, 
                    updated = NOW() 
                WHERE 
                  id = ?;
              ';
            $params = array(
                $this->title, 
                $this->director, 
                $this->length, 
                $this->year, 
                $this->plot, 
                $this->image, 
                $this->image1,
                $this->image2, 
                $this->subtext, 
                $this->speech,
                $this->price, 
                $this->youtubelink,
                $this->imdblink,
                $id);
            $res = $this->db->ExecuteQuery($sql, $params);
            echo $res[0]; 
            if ($res) {
                

                if (isset($_POST['genres'])) {
                    $output = '<div class="row-spacing"></div><div class="alert alert-success" role="alert">Informationen sparades.</div>';
                    $sql = 'INSERT INTO Movie2Genre(
                            idMovie,
                            idGenre)
                            VALUES (?, ?)';

                    foreach($_POST['genres'] as $key => $value) {
                        $params = array($id, $value);
                        $res = $this->db->ExecuteQuery($sql, $params); 
                    }  
                }
                  $output = '<div class="alert alert-success" role="alert">Film uppdaterad.</div>';
            } else {
                $output = '<div class="alert alert-danger" role="alert">Filmen uppdaterades EJ.<br><pre>' . print_r($this->db->Dump(), 1) . '</pre></div>';
            }
        }
        return $output; 
    }
    
    public function deleteContent($id) {
        $output = null;
        if ($this->remove) {
            $sql = 'DELETE FROM Movie2Genre
                  WHERE idMovie = ?';
            $res = $this->db->ExecuteQuery($sql, array($id));
            echo $res[0]; 
            if ($res) {
                
                $sql = "DELETE FROM Movie WHERE id = ?;";
                $res = $this->db->ExecuteQuery($sql, array($id));

                if($res){
                    header("Location: movie_view.php");
                }else{
                    $output = '<div class="alert alert-danger" role="alert">Filmen raderades EJ.<br><pre>' . print_r($this->db->Dump(), 1) . '</pre></div>';
                } 
               
                
            } else {
                $output = '<div class="alert alert-danger" role="alert">Filmen raderades EJ.<br><pre>' . print_r($this->db->Dump(), 1) . '</pre></div>';
            }
        } else if($this->noRemove) {
            header("Location: movie_view.php");
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