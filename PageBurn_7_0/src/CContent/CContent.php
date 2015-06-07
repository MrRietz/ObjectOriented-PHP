<?php

class CContent {

    protected $db;
    protected $isAdmin    = null;
    protected $id         = null; 
    protected $title      = null; 
    protected $slug       = null; 
    protected $url        = null; 
    protected $data       = null; 
    protected $type       = null; 
    protected $genre      = null; 
    protected $filter     = null; 

    protected $save       = null; 
    protected $remove     = null; 
    protected $noRemove   = null; 
    public function __construct($database) {
        
        $this->db =         $database;
        // Get parameters 
        $this->isAdmin =    isset($_SESSION['user']) ? $_SESSION['user'] : null;
        $this->id     =     isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
        $this->title =      isset($_POST['title']) ? $_POST['title'] : null;
        $this->slug =       isset($_POST['slug']) ? $this->slugify($_POST['slug']) : null;
        $this->url =        isset($_POST['url']) ? strip_tags($_POST['url']) : null;
        $this->genre =        isset($_POST['genre']) ? strip_tags($_POST['genre']) : null;
        $this->data =       isset($_POST['data']) ? $_POST['data'] : array();
        $this->type =       "post";//isset($_POST['type']) ? strip_tags($_POST['type']) : array();
        $this->filter =     isset($_POST['filter']) ? $_POST['filter'] : array();
        $this->save =       isset($_POST['save']) ? true : false;
        $this->remove =     isset($_POST['remove']) ? true : false; 
        $this->noRemove =     isset($_POST['noRemove']) ? true : false; 
        $this->acronym =    isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
    }

    public function renderAvailableContent() {
        
        $sql = 'SELECT *, (published <= NOW()) AS available FROM Content;';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);


        $items = null;
        if ($this->isAdmin) {
            foreach ($res AS $key => $val) {
                $items .= "<p><li>{$val->type} (" 
                .(!$val->available ? 'inte ' : null) . "publicerad): " 
                        . htmlentities($val->title, null, 'UTF-8') 
                        . "  <a class='btn btn-info' href='news_edit.php?id={$val->id}'>editera</a> <a class='btn btn-warning' href='news_remove.php?id={$val->id}'>ta bort</a>"
                        . " <a class='btn btn-success' href='" . $this->getUrlToContent($val) . "'>visa</a></li></p>";
            }
        } else {
            foreach ($res AS $key => $val) {
                $items .= "<li>{$val->type} (" 
                . (!$val->available ? 'inte ' : null) . "publicerad): " 
                        . htmlentities($val->title, null, 'UTF-8') 
                        . " (<a href='" . $this->getUrlToContent($val) . "'>visa</a>)</li>\n";
            }
        }
        return $items;
    }
    
    public function updateContent() {

        $output = null;
        if ($this->save) {
            $sql = '
                UPDATE Content SET
                  title   = ?,
                  genre   = ?,
                  slug    = ?,
                  url     = ?,
                  data    = ?,
                  filter  = ?,
                  updated = NOW()
                WHERE 
                  id = ?;
              ';
            $this->url = empty($this->url) ? null : $this->url;
            $params = array($this->title, $this->genre, $this->slug, $this->url, $this->data, $this->filter, $this->id);
            $res = $this->db->ExecuteQuery($sql, $params);
            if ($res) {
                $output = '<div class="alert alert-success" role="alert">Informationen sparades.</div>';
            } else {
                $output = '<div class="alert alert-danger" role="alert">Informationen sparades EJ.</div><br><pre>' . print_r($this->db->Dump(), 1) . '</pre>';
            }
        }
        else if($this->remove) {
            $this->deleteContentById($this->id);
        }
        else if($this->noRemove) {
                 header('Location: news_view.php');
        }
        return $output; 
    }
    
    public function insertContent() {
         $output = null;
        if ($this->save) {
            $sql = 'INSERT INTO Content(
                title, 
                genre,
                slug, 
                url, 
                data, 
                filter, 
                type,
                published, 
                created) 
                VALUES(
                ?, 
                ?,
                ?, 
                ?, 
                ?, 
                ?, "post", NOW(), NOW())'; 
            
            $this->url = empty($this->url) ? null : $this->url;
            $params = array($this->title, $this->genre, $this->slug, $this->url, $this->data, $this->filter);
            $res = $this->db->ExecuteQuery($sql, $params);
            if ($res) {
                header('Location: news_view.php');
            } else {
                $output = 'Informationen EJ tillagd.<br><pre>' . print_r($this->db->Dump(), 1) . '</pre>';
            }
        }
        return $output; 
    }

    public function renderEditForm($output) {
  
        $content = $this->selectContentById($this->id); 
        $this->sanitizeVariables($content); 
        
        $html = null; 
        $html .= "<form method=post>";
        $html .= "<fieldset>";
        $html .= "<input type='hidden' name='id' value='{$this->id}'/>";
        $html .= "<label class='control-label'>Titel:<br/><input class='form-control' type='text' name='title' value='{$this->title}'/></label></p>";
        $html .= "<label class='control-label'>Genre:<br/><input class='form-control' type='text' name='genre' value='{$this->genre}'/></label></p>";
        $html .= "<label class='control-label'>Slug:<br/><input class='form-control' type='text' name='slug' value='{$this->slug}'/></label></p>";
        $html .= "<label class='control-label'>Url:<br/><input class='form-control' type='text' name='url' value='{$this->url}'/></label></p>";
        $html .= "<label class='control-label'>Text:<br/><textarea rows='12' cols='50' class='form-control' name='data'>{$this->data}</textarea></label></p>";
        $html .= "<label class='control-label'>Filter:<br/><input class='form-control' type='text' name='filter' value='{$this->filter}'/></label></p>";
        $html .= "<input class='btn btn-primary' class='form-control' type='submit' name='save' value='Spara'/>";
        $html .= "<output>{$output}</output>";
        $html .= "</fieldset>";
        $html .= "</form>";
        return $html; 
    }
    
    public function renderInsertForm($output) {
        $html = null;
        $html .= "<form method=post>";
        $html .= "<fieldset>";
        $html .= "<input type='hidden' name='id' value='{$this->id}'/>";
        $html .= "<label class='control-label'>Titel:<br/><input class='form-control' type='text' name='title' value=''/></label></p>";
        $html .= "<label class='control-label'>Genre:<br/><input class='form-control' type='text' name='genre' value=''/></label></p>";
        $html .= "<label class='control-label'>Slug:<br/><input class='form-control' type='text' name='slug' value=''/></label></p>";
        $html .= "<label class='control-label'>Text:<br/><textarea class='form-control' name='data' rows='12' cols='50'></textarea></label></p>";
        $html .= "<label class='control-label'>Filter:<br/><input class='form-control' type='text' name='filter' value='nl2br'/></label></p>";
        $html .= "<p><input class='btn btn-primary' type='submit' name='save' value='Lägg till'/></p>";
        $html .= "<output>{$output}</output>";
        $html .= "</fieldset>";
        $html .= "</form>";
        return $html;
    }
    
    public function renderRemoveForm($output) {
        $content = $this->selectContentById($this->id); 
        $this->sanitizeVariables($content); 
        
        $html = null; 
        $html .= "<form method=post>";
        $html .= "<fieldset>";
        $html .= "<input type='hidden' name='id' value='{$content->id}'/>";
        $html .= "<p>Vill du verkligen ta bort inlägget med titeln: {$content->title}</p>";
        $html .= "<div class='btn-group' role='group' aria-label='remove'><input class='btn btn-warning' type='submit' name='remove' value='Ja'/>
            <input class='btn btn-default' type='submit' name='noRemove' value='Nej'/></div>";
        $html .= "<output>{$output}</output>";
        $html .= "</fieldset>";
        $html .= "</form>";
        return $html; 

    }

    public function resetDB() {

        $sql = file_get_contents('resetBlogTables.sql');
        $res = $this->db->ExecuteQuery($sql);

        if ($res) {
            return true;
        } else {
            return false;
        }
    }
    
    public function sanitizeVariables($c) {
        
        $this->title      = htmlentities($c->title, null, 'UTF-8');
        $this->slug       = htmlentities($c->slug, null, 'UTF-8');
        $this->url        = htmlentities($c->url, null, 'UTF-8');
        $this->data       = htmlentities($c->data, null, 'UTF-8');
        $this->genre      = htmlentities($c->genre, null, 'UTF-8');
        $this->type       = htmlentities($c->type, null, 'UTF-8');
        $this->filter     = htmlentities($c->filter, null, 'UTF-8');
        $this->published  = htmlentities($c->published, null, 'UTF-8');
    }
    
    private function selectContentById($id) {
        
        $sql = 'SELECT * FROM Content WHERE id = ?';
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));

        if (isset($res[0])) {
            $c = $res[0];
        } else {
            die('Misslyckades: det finns inget innehåll med sådant id.');
        }
        return $c;
    }
    
    private function deleteContentById($id) {
        
        $sql = "DELETE FROM Content WHERE id = ?;";
        $res = $this->db->ExecuteQuery($sql, array($id));
        
        if($res){
            header("Location: news_view.php");
        }else{
            $output = "Informationen raderades EJ.<br><pre>" .print_r($this->db->Dump(), 1) ."</pre>";
        } 
        return $output;
    }

    private function getUrlToContent($content) {
        switch ($content->type) {
            case 'page': return "pageController.php?url={$content->url}";
                break;
            case 'post': return "news.php?slug={$content->slug}";
                break;
            default: return null;
                break;
        }
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
   /**
   * Create a slug of a string, to be used as url.
    *
   * @param string $str the string to format as slug.
   * @returns str the formatted slug. 
   */
  private function slugify($str) {
        $str = mb_strtolower(trim($str));
        $str = str_replace(array('å', 'ä', 'ö'), array('a', 'a', 'o'), $str);
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = trim(preg_replace('/-+/', '-', $str), '-');
        return $str;
    }

}
