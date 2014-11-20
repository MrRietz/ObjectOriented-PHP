<?php
/**
 * Theme related functions. 
 *
 */
 
/**
 * Get title for the webpage by concatenating page specific title with site-wide title.
 *
 * @param string $title for this page.
 * @return string/null wether the favicon is defined or not.
 */
function get_title($title) {
  global $anax;
  return $title . (isset($anax['title_append']) ? $anax['title_append'] : null);
}

function get_navbar($menu) {
   if(isset($menu['callback'])) {
      $items = call_user_func($menu['callback'], $menu['items']);
    }
    $html = "<nav>\n";
       foreach($items as $item) {
      $html .= "<a href='{$item['url']}' class='{$item['class']}'>{$item['text']}</a>\n";
    }
    $html .= "</nav>\n";
    return $html;
  }
  
  
function modify_navbar($menu) {
  $ref = isset($_GET['p']) && isset($menu[$_GET['p']]) ? $_GET['p'] : null;
  if($ref) {
    $menu[$ref]['class'] .= 'selected'; 
  }
  return $menu;
}
