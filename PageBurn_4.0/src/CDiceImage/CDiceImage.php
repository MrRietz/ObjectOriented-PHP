<?php
/**
 * A dice with images as graphical representation.
 *
 */
class CDiceImage extends CDice
{
   const FACES = 6;
    
  public function __construct() 
  {
    parent::__construct(self::FACES);
  }
  
   /**
   * Get the rolls as a serie of images.
   *
   */
  public function GetRollsAsImageList() 
  {
    $html = "<ul class='dice'>";
    foreach($this->rolls as $val) {
      $html .= "<li class='dice-{$val}'></li>";
    }
    $html .= "</ul>";
    return $html;
  }
}
