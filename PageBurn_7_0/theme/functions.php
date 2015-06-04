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
  global $pageburn;
  return $title . (isset($pageburn['title_append']) ? $pageburn['title_append'] : null);
}

/**
 * Create a navigation bar / menu for the site.
 *
 * @param string $menu for the navigation bar.
 * @return string as the html for the menu.
*/
function echoActiveClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="active"';
}

/**
 * Create a navigation bar / menu, with submenu.
 *
 * @param string $menu for the navigation bar.
 * @return string as the html for the menu.
 */
function get_navbar($menu) {
  // Keep default options in an array and merge with incoming options that can override the defaults.
  $default = array(
    'id'      => null,
    'class'   => null,
    'wrapper' => 'nav',
  );
  $menu = array_replace_recursive($default, $menu);
 
  $create_menu = function($items, $callback)  {
  
  };
 
  // Create the ul li menu from the array, use an anonomous recursive function that returns an array of values.
  $create_menu = function($items, $callback) use (&$create_menu) {
    $html = null;
    $hasItemIsSelected = false;
    $navbarHeader = "<div class='navbar-header'>
          <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#rmNavbar'>
            <span class='sr-only'>Toggle navigation</span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
          </button>
          <a class='navbar-brand' href='home.php'>
           <img class ='sitelogo' src='img/header.png' alt='pageburn Logo'/> RM Rental Movies    
         </a>    
        </div>";
    $navbarSearchBar = "<form class='navbar-form navbar-left' role='search'>
         
            <div class='form-group'>
              <input class='form-control' placeholder='Sök på titel...' type='search' name='title' value=''>
                      </div>
                    <button type='submit' class='btn btn-default'>Sök</button>
    
          </form>"; 
    foreach($items as $item) {
 
      // has submenu, call recursivly and keep track on if the submenu has a selected item in it.
      $submenu        = null;
      $selectedParent = null;
   
      if(isset($item['submenu'])) 
      {
        list($submenu, $selectedParent) = array("<li class='dropdown'>", $callback);
        $selectedParent = $selectedParent ? " active" : null;
     
      }
      // Check if the current menuitem is selected
      $selected = $callback($item['url']) ? 'active' : null;
      if($selected) {
        $hasItemIsSelected = true;
      }
      $selected = ($selected || $selectedParent) ? " class='${selected}{$selectedParent}' " : null;      
      $html .= "\n<li{$selected}><a href='{$item['url']}' title='{$item['title']}'>{$item['text']}</a>{$submenu}</li>\n";
    }
 
    return array("\n<div class='container-fluid'>$navbarHeader<div class='collapse navbar-collapse' id='rmNavbar'><ul class='nav navbar-nav'>$html</ul>$navbarSearchBar</div></div>\n", $hasItemIsSelected);
  };
 
  // Call the anonomous function to create the menu, and submenues if any.
  list($html, $ignore) = $create_menu($menu['items'], $menu['callback']);
 
 
  // Set the id & class element, only if it exists in the menu-array
  $id      = isset($menu['id'])    ? " id='{$menu['id']}'"       : null;
  $class   = isset($menu['class']) ? " class='{$menu['class']}'" : null;
  $wrapper = $menu['wrapper'];
 
  return "\n<{$wrapper}{$id}{$class}>{$html}</{$wrapper}>\n";
}
