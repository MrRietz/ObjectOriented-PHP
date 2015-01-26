<?php

class CPage extends CContent {

    public function __construct($database) {
        parent::__construct($database);
    }

    public function getCurrentPage() {
        // Get content
        
        $sql = "SELECT * FROM Content WHERE
                  type = 'page' AND
                  url = ? AND
                  published <= NOW();
                ";
        
        $this->url = isset($_GET['url']) ? $_GET['url'] : null;
        if($this->url)
        {
            $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($this->url));
        }
        else
        {
            die('Misslyckades: det finns ingen url.');
        }

        if (isset($res[0])) {
            $c = $res[0];
        } else {
            die('Misslyckades: det finns inget innehåll.');
        }
        return $c; 
    }
    public function sanitizeVariables($c) {
        parent::sanitizeVariables($c);
        
        $filter = new CTextFilter(); 
        // Sanitize content before using it.
        $this->title = htmlentities($c->title, null, 'UTF-8');
        $this->data = $filter->doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);
    }

    public function renderHTML($c) {
        $editLink = $this->isAdmin ? "<a href='editController.php?id={$c->id}'>Uppdatera sidan</a>" : null;
        $html = null; 
        $html .= "<article>"; 
        $html .= "<header>";
        $html .= "<h1>{$this->title}</h1>";
        $html .= "</header>";
        $html .= "{$this->data}";
        $html .= "<footer><br><br>";
        $html .= "{$editLink}";
        $html .= "</footer>";
        $html .= "</article>";
        return $html;
    }
    
    public function getTitle() {
        return $this->title; 
    }
    

}
