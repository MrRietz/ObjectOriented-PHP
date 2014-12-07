 <?php
/**
 * Handles the process and output of gallery
 *
 */
class CGallery {
    /**
     * Members
     */

    // Paths
    private $galleryPath;
    private $galleryBaseurl;

    // Gallery that would be printed
    private $gallery;
    // Breadcrumb the would be printed
    private $breadcrumb;

    /**
     * Constructor sets the path and baseurl
     * Then process the gallery
     *
     * @param string $galleryPath with the path to all the images
     * @param string $galleryBaseurl if the images are in another folder
     *
     */
    public function __construct($galleryPath=null, $galleryBaseurl=null) {
        $this->galleryPath = $galleryPath;
        $this->galleryBaseurl = $galleryBaseurl;
        $this->Init();
    }

    /**
     * The progress of the gallery
     *
     */
    private function Init() {
        $pathToGallery = $this->GetAndValidate();
        $this->gallery = $this->CreateGallery($pathToGallery);
        $this->breadcrumb = $this->CreateBreadcrumb($pathToGallery);
    }

    /**
     * Gets the path if it's set. Building the gallarypath
     * and checks if its valid
     *
     * @return string with the path to gallery
     */
    private function GetAndValidate() {
        // Get incoming parameters
        $path = isset($_GET['path']) ? $_GET['path'] : null;

        $pathToGallery = realpath($this->galleryPath . '/' . $path);

        // Validate incoming arguments
        is_dir($this->galleryPath) or $this->ErrorMessage('The gallery dir is not a valid directory.');
        substr_compare($this->galleryPath, $pathToGallery, 0, strlen($this->galleryPath)) == 0 or $this->ErrorMessage('Security constraint: Source gallery is not directly below the directory $this->galleryPath.');

        return $pathToGallery;
    }

    /**
     * Gets the gallery. One or more images
     *
     * @param string $path to the current gallery directory.
     * @return string html with ul/li to display the gallery.
     */
    private function CreateGallery($path) {
        // Read and present images in the current directory
        if(is_dir($path)) {
          // $gallery = readAllItemsInDir($pathToGallery);
            return $this->ReadAllItemsInDir($path);
        }
        else if(is_file($path)) {
          // $gallery = readItem($pathToGallery);
            return $this->ReadItem($path);
        }
    }

    /**
     * Read directory and return all items in a ul/li list.
     *
     * @param string $path to the current gallery directory.
     * @param array $validImages to define extensions on what are considered to be valid images.
     * @return string html with ul/li to display the gallery.
     */
    private function ReadAllItemsInDir($path, $validImages = array('png', 'jpg', 'jpeg')) {
          $files = glob($path . '/*'); 
          $gallery = "<ul class='gallery'>\n";
          $len = strlen($this->galleryPath);

          foreach($files as $file) {
            $parts = pathinfo($file);
            $href  = str_replace('\\', '/', substr($file, $len + 1));

            // Is this an image or a directory
            if(is_file($file) && in_array($parts['extension'], $validImages)) {
                  $item    = "<img src='img.php?src=" . $this->galleryBaseurl . $href . "&amp;width=128&amp;height=128&amp;crop-to-fit' alt=''/>";
                  $caption = basename($file); 
            }
            elseif(is_dir($file)) {
                  $item    = "<img src='img/folder.png' alt=''/>";
                  $caption = basename($file) . '/';
            }
               else {
                  continue;
            }

            // Avoid to long captions breaking layout
            $fullCaption = $caption;
            if(strlen($caption) > 18) {
                  $caption = substr($caption, 0, 10) . '…' . substr($caption, -5);
            }

            $gallery .= "<li><a href='?path={$href}' title='{$fullCaption}'><figure class='figure overview'>{$item}<figcaption>{$caption}</figcaption></figure></a></li>\n";
          }
          $gallery .= "</ul>\n";

          return $gallery;
    }

    /**
     * Read and return info on choosen item.
     *
     * @param string $path to the current gallery item.
     * @param array $validImages to define extensions on what are considered to be valid images.
     * @return string html to display the gallery item.
     */
    private function ReadItem($path, $validImages = array('png', 'jpg', 'jpeg')) {
        $parts = pathinfo($path);
        if(!(is_file($path) && in_array($parts['extension'], $validImages))) {
            return "<p>This is not a valid image for this gallery.";
        }

        // Get info on image
        $imgInfo = list($width, $height, $type, $attr) = getimagesize($path);
        $mime = $imgInfo['mime'];
        $gmdate = gmdate("D, d M Y H:i:s", filemtime($path));
        $filesize = round(filesize($path) / 1024); 

        // Get constraints to display original image
        $displayWidth  = $width > 800 ? "&amp;width=800" : null;
        $displayHeight = $height > 600 ? "&amp;height=600" : null;

        // Display details on image
        $len = strlen($this->galleryPath);
        $href = $this->galleryBaseurl . str_replace('\\', '/', substr($path, $len + 1));
        $item = <<<EOD
<p><img src='img.php?src={$href}{$displayWidth}{$displayHeight}' alt=''/></p>
<p>Original image dimensions are {$width}x{$height} pixels. <a href='img.php?src={$href}'>View original image</a>.</p>
<p>File size is {$filesize}KBytes.</p>
<p>Image has mimetype: {$mime}.</p>
<p>Image was last modified: {$gmdate} GMT.</p>
EOD;

        return $item;
    }

    /**
     * Create a breadcrumb of the gallery query path.
     *
     * @param string $path to the current gallery directory.
     * @return string html with ul/li to display the thumbnail.
     */
    private function CreateBreadcrumb($path) {
          $parts = explode('/', trim(substr($path, strlen($this->galleryPath) + 1), '/'));
          $breadcrumb = "<ul class='breadcrumb'>\n<li><a href='?'>Hem</a> »</li>\n";

          if(!empty($parts[0])) {
            $combine = null;
            foreach($parts as $part) {
                  $combine .= ($combine ? '/' : null) . $part;
                  $breadcrumb .= "<li><a href='?path={$combine}'>$part</a> » </li>\n";
            }
          }

          $breadcrumb .= "</ul>\n";
          return $breadcrumb;
    }

    /**
     * Display error message.
     *
     * @param string $message the error message to display.
     */
    private function ErrorMessage($message) {
          header("Status: 404 Not Found");
          die('gallery.php says 404 - ' . htmlentities($message));
    }

    /**
     * Return gallary with images
     *
     * @return string html to display the gallery item/items
     */
    public function GetGallery() {
        return $this->gallery;
    }

    /**
     * Return breadcrumb for the page navigation
     *
     * @return string html with ul/li to display the thumbnail.
     */
    public function GetBreadCrumb() {
        return $this->breadcrumb;
    }
} 