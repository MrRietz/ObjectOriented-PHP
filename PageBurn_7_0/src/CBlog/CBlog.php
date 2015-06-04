<?php

class CBlog extends CContent {

    public function __construct($database) {
        parent::__construct($database);
    }
    public function getHomePosts() {
        
        $sql = "
            SELECT *
            FROM Content
            WHERE
            type = 'post' AND
              published <= NOW()
            ORDER BY published DESC LIMIT 3;
            ";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        return $res; 
    }
    public function getPosts() {
        
        $this->slug = isset($_GET['slug']) ? $_GET['slug'] : null;
        // Get content
        $slugSql = $this->slug ? 'slug = ?' : '1';

        $sql = "
            SELECT *
            FROM Content
            WHERE
              type = 'post' AND
              $slugSql AND
              published <= NOW()
            ORDER BY updated DESC
            ;
            ";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($this->slug));

        return $res; 
    }
    public function sanitizeVariables($c) {
        parent::sanitizeVariables($c);
        
        $filter = new CTextFilter(); 
        // Sanitize content before using it.
        $this->title  = htmlentities($c->title, null, 'UTF-8');
        $this->data   = $filter->doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);
    }

    public function renderHTML($c) {
        $editLink = $this->isAdmin ? "<a href='editController.php?id={$c->id}'>Uppdatera sidan</a>" : null;
        $html = null; 
        $html .= "<div class='panel panel-info'>";
        $html .= "<div class='panel-heading'><h3 class='panel-title'><a href='news.php?slug={$this->slug}'>{$this->title}</a></h3></div>";
        $html .= "<div class='panel-body' id='newscontent'>{$this->data}</div>";
        $html .= "<div class='panel-footer'>";
        $html .= "Published: {$this->published}";
        $html .= "{$editLink} </div></div>";
        return $html;
    }
    
    public function getTitle() {
        return $this->title; 
    }
    
    public function getSlug() {
        return $this->slug; 
    }
    

}
