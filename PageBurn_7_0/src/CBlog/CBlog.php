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

    public function getAllPosts() {
        $sql = "SELECT * FROM Content
            WHERE type = 'post' 
            AND published <= NOW()
            ORDER BY updated DESC";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($this->slug));
        $html = "";
        foreach($res as $val) {
            $html .= $this->renderHTML($val, true); 
        }
        return $html; 
    }
    
    public function getGenreList() {
        $sql = "SELECT DISTINCT genre
                FROM Content";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql); 
        $html = "<ul class='list-group'>"; 
        foreach($res as $val) {
            $html .= "<li class='list-group-item'><div class='btn-group btn-group-justified'><a class='btn btn-info' href='news.php?genre={$val->genre}'>{$val->genre}</a></div></li>";
        } 
        $html .= "</ul>"; 
        return $html; 
    }
    
    public function getPostsByGenre() {
        $genre = isset($_GET['genre']) ? $_GET['genre'] : null;
        $html = ""; 
        $sql = "SELECT * FROM Content WHERE genre = ?";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($genre));
        foreach($res as $val) {
            $html .= $this->renderHTML($val, true); 
        }
        return $html; 
    }
    
    public function getPostBySlug() {
         $this->slug = isset($_GET['slug']) ? $_GET['slug'] : null;

        $sql = "SELECT * FROM Content WHERE slug = ?";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($this->slug));
        $html = "";
        foreach($res as $val) {
            $html .= $this->renderHTML($val, false); 
        }
        return $html; 
    }
    
    public function sanitizeVariables($c) {
        parent::sanitizeVariables($c);
        
        $filter = new CTextFilter(); 
        // Sanitize content before using it.
        $this->title  = htmlentities($c->title, null, 'UTF-8');
        $this->data   = $filter->doFilter(htmlentities($c->data, null, 'UTF-8'), $c->filter);
    }

    public function renderHTML($c, $isSubstring) {
        $html = null; 
        $html .= "<div class='panel panel-info'>";
        $html .= "<div class='panel-heading'><h3 class='panel-title'><a href='news.php?slug={$c->slug}'>{$c->title}</a></h3></div>";
        if($isSubstring) {
            $html .= "<div class='panel-body' id='newscontent'>{$this->get_snippet($c->data, 25)} ...</div>";
        } else {
              $html .= "<div class='panel-body' id='newscontent'>{$c->data}</div>";
        }

        $html .= "<div class='panel-footer'>";
        $html .= "Published: {$c->published} &nbsp; Genre: {$c->genre}</div></div>";
        return $html;
    }
    
    public function getTitle() {
        return $this->title; 
    }
    
    public function getSlug() {
        return $this->slug; 
    }
    
    private function get_snippet($str, $wordCount) 
    {
      return implode( 
        '', 
        array_slice( 
          preg_split(
            '/([\s,\.;\?\!]+)/', 
            $str, 
            $wordCount*2+1, 
            PREG_SPLIT_DELIM_CAPTURE
          ),
          0,
          $wordCount*2-1
        )
      );
    }
    

}
