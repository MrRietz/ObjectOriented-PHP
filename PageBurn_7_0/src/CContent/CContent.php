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
    protected $filter     = null; 
    protected $published  = null; 

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
        $this->data =       isset($_POST['data']) ? $_POST['data'] : array();
        $this->type =       "post";//isset($_POST['type']) ? strip_tags($_POST['type']) : array();
        $this->filter =     isset($_POST['filter']) ? $_POST['filter'] : array();
        $this->published =  isset($_POST['published']) ? strip_tags($_POST['published']) : array();
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
                $items .= "<li>{$val->type} (" 
                .(!$val->available ? 'inte ' : null) . "publicerad): " 
                        . htmlentities($val->title, null, 'UTF-8') 
                        . " (<a href='removeController.php?id={$val->id}'>ta bort</a> <a href='editController.php?id={$val->id}'>editera</a> <a href='" 
                        . $this->getUrlToContent($val) . "'>visa</a>)</li>\n";
            }
        }
        else {
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
                  slug    = ?,
                  url     = ?,
                  data    = ?,
                  type    = ?,
                  filter  = ?,
                  published = ?,
                  updated = NOW()
                WHERE 
                  id = ?;
              ';
            $url = empty($url) ? null : $url;
            $params = array($this->title, $this->slug, $this->url, $this->data, $this->type, $this->filter, $this->published, $this->id);
            $res = $this->db->ExecuteQuery($sql, $params);
            if ($res) {
                $output = 'Informationen sparades.';
            } else {
                $output = 'Informationen sparades EJ.<br><pre>' . print_r($db->Dump(), 1) . '</pre>';
            }
        }
        else if($this->remove) {
            $this->deleteContentById($this->id);
        }
        else if($this->noRemove) {
                 header('Location: viewController.php');
        }
        return $output; 
    }
    
    public function insertContent() {
         $output = null;
        if ($this->save) {
            $sql = 'INSERT INTO Content(
                title, 
                slug, 
                url, 
                data, 
                type, 
                filter, 
                published, 
                updated) 
                VALUES(?, ?, ?, ?, ?, ?, NOW(), NOW())'; 
            
            $url = empty($url) ? null : $url;
            $params = array($this->title, $this->slug, $this->url, $this->data, $this->type, $this->filter);
            $res = $this->db->ExecuteQuery($sql, $params);
            if ($res) {
                header('Location: viewController.php');
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
        $html .= "<legend>Uppdatera innehåll</legend>";
        $html .= "<input type='hidden' name='id' value='{$this->id}'/>";
        $html .= "<p><label>Titel:<br/><input type='text' name='title' value='{$this->title}'/></label></p>";
        $html .= "<p><label>Slug:<br/><input type='text' name='slug' value='{$this->slug}'/></label></p>";
        $html .= "<p><label>Url:<br/><input type='text' name='url' value='{$this->url}'/></label></p>";
        $html .= "<p><label>Text:<br/><textarea name='data'>{$this->data}</textarea></label></p>";
        $html .= "<p><label>Type:<br/><input type='text' name='type' value='{$this->type}'/></label></p>";
        $html .= "<p><label>Filter:<br/><input type='text' name='filter' value='{$this->filter}'/></label></p>";
        $html .= "<p><label>Publiseringsdatum:<br/><input type='text' name='published' value='{$this->published}'/></label></p>";
        $html .= "<p class=buttons><input type='submit' name='save' value='Spara'/></p>";
        $html .= "<p><a href='viewController.php'>Visa alla</a></p>";
        $html .= "<output>{$output}</output>";
        $html .= "</fieldset>";
        $html .= "</form>";
        return $html; 
    }
    
    public function renderInsertForm($output) {
        $html = null;
        $html .= "<form method=post>";
        $html .= "<fieldset>";
        $html .= "<legend>Lägg till nytt innehåll</legend>";
        $html .= "<input type='hidden' name='id' value='{$this->id}'/>";
        $html .= "<p><label>Titel:<br/><input type='text' name='title' value=''/></label></p>";
        $html .= "<p><label>Slug:<br/><input type='text' name='slug' value=''/></label></p>";
        $html .= "<p><label>Text:<br/><textarea name='data' height='200' width='300'></textarea></label></p>";
        $html .= "<p><label>Filter:<br/><input type='text' name='filter' value='nl2br'/></label></p>";
        $html .= "<p class=buttons><input type='submit' name='save' value='Spara'/></p>";
        $html .= "<p><a href='viewController.php'>Visa alla</a></p>";
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
        $html .= "<legend>Ta bort innehåll</legend>";
        $html .= "<input type='hidden' name='id' value='{$this->id}'/>";
        $html .= "Vill du verkligen ta bort inlägget med titeln: {$this->title}</label></p>";
        $html .= "<p class=buttons><input type='submit' name='remove' value='Ja'/>";
        $html .= " <input type='submit' name='noRemove' value='Nej'/></p>";
        $html .= "<p><a href='viewController.php'>Visa alla</a></p>";
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
            header("Location: viewController.php");
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
