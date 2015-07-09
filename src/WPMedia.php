<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress media item
 *
 * It allows to retrieve informations about a media object.
 * 
 * @package     Comodojo Spare Parts
 * @author      Marco Castiello <marco.castiello@gmail.com>
 * @license     MIT
 *
 * LICENSE:
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class WPMedia {
	
	/**
     * Blog reference
     *
     * @var Object
     */
	private $blog = null;
	
	/**
     * Attachment ID
     *
     * @var int
     */
	private $id = 0;
	
	/**
     * Date of creation
     *
     * @var int
     */
	private $date = 0;
	
	/**
     * Post ID
     *
     * @var int
     */
	private $post = 0;
	
	/**
     * URL to the media file
     *
     * @var string
     */
	private $link = "";
	
	/**
     * Title
     *
     * @var string
     */
	private $title = "";
	
	/**
     * Caption
     *
     * @var string
     */
	private $caption = "";
	
	/**
     * Description
     *
     * @var string
     */
	private $description = "";
	
	/**
     * Thumbnail
     *
     * @var string
     */
	private $thumbnail = "";
	
	/**
     * File
     *
     * @var string
     */
	private $file = "";
	
	/**
     * Image width
     *
     * @var int
     */
	private $width = 0;
	
	/**
     * Image height
     *
     * @var int
     */
	private $height = 0;
	
	/**
     * Image meta timestamp
     *
     * @var int
     */
	private $meta_timestamp = 0;
	
	/**
     * Image meta focal length
     *
     * @var int
     */
	private $meta_focal_length = 0;
	
	/**
     * Image meta iso
     *
     * @var int
     */
	private $meta_iso = 0;
	
	/**
     * Image meta shutter speed
     *
     * @var int
     */
	private $meta_shutter_speed = 0;
	
	/**
     * Meta credit
     *
     * @var string
     */
	private $meta_credit = "";
	
	/**
     * Meta camera
     *
     * @var string
     */
	private $meta_camera = "";
	
	/**
     * Meta caption
     *
     * @var string
     */
	private $meta_caption = "";
	
	/**
     * Meta copyright
     *
     * @var string
     */
	private $meta_copyright = "";
	
	/**
     * Meta title
     *
     * @var string
     */
	private $meta_title = "";
	
	/**
     * Image size info
     *
     * @var array
     */
	private $sizes = array();
	
	/**
     * Allowed image sizes
     *
     * @var array
     */
	private $size_allowed = array("thumbnail", "medium", "large", "post-thumbnail");
	
    /**
     * Class constructor
     *
     * @param   Object  $blog Reference to a blog object
     * @param   int     $id   Attachment ID (optional)
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $id=0) {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to fetch media items");
        	
        }
        
        $this->blog = $blog;
        
        $this->id   = intval($id);
        
        if ($this->id > 0) {
        	
        	$this->loadFromID($this->id);
        	
        }
        
    }
    
    /**
     * Get wordpress reference
     *
     * @return  Object  $wordpress
     */
    public function getWordpress() {
    	
    	return $this->getBlog()->getWordpress();
    	
    }
    
    /**
     * Get user's blog
     *
     * @return  Object  $this->blog
     */
    public function getBlog() {
    	
    	return $this->blog;
    	
    }
    
    /**
     * Get ID
     *
     * @return  Object  $this->id
     */
    public function getID() {
    	
    	return $this->id;
    	
    }
    
    /**
     * Get associated post ID
     *
     * @return  Object  $this->post
     */
    public function getPostID() {
    	
    	return $this->post;
    	
    }
    
    /**
     * Set associated post ID
     *
     * @param   int  $post
     *
     * @return  Object  $this
     */
    public function setPostID($post) {
    		
    	$this->post = $post;
    	
    	return $this;
    	
    }
    
    /**
     * Get link
     *
     * @return  Object  $this->link
     */
    public function getLink() {
    	
    	return $this->link;
    	
    }
    
    /**
     * Get title
     *
     * @return  Object  $this->title
     */
    public function getTitle() {
    	
    	return $this->title;
    	
    }
    
    /**
     * Get caption
     *
     * @return  Object  $this->caption
     */
    public function getCaption() {
    	
    	return $this->caption;
    	
    }
    
    /**
     * Get description
     *
     * @return  Object  $this->description
     */
    public function getDescription() {
    	
    	return $this->description;
    	
    }
    
    /**
     * Get thumbnail
     *
     * @return  Object  $this->thumbnail
     */
    public function getThumbnail() {
    	
    	return $this->thumbnail;
    	
    }
    
    /**
     * Get allowed image sizes
     *
     * @return  Object  $this->size_allowed
     */
    public function getSupportedMediaSizes() {
    	
    	return $this->size_allowed;
    	
    }
    
    /**
     * Get file
     *
     * @return  Object  $this->file
     */
    public function getFile() {
    	
    	return $this->file;
    	
    }
    
    /**
     * Get width
     *
     * @return  int  $this->width
     */
    public function getWidth() {
    	
    	return $this->width;
    	
    }
    
    /**
     * Get height
     *
     * @return  int  $this->height
     */
    public function getHeight() {
    	
    	return $this->height;
    	
    }
    
    /**
     * Get focal length
     *
     * @return  int  $this->meta_focal_length
     */
    public function getFocalLength() {
    	
    	return $this->meta_focal_length;
    	
    }
    
    /**
     * Get ISO
     *
     * @return  int  $this->meta_iso
     */
    public function getISO() {
    	
    	return $this->meta_iso;
    	
    }
    
    /**
     * Get shutter speed
     *
     * @return  int  $this->meta_shutter_speed
     */
    public function getShutterSpeed() {
    	
    	return $this->meta_shutter_speed;
    	
    }
    
    /**
     * Get credit
     *
     * @return  string  $this->meta_credit
     */
    public function getCredit() {
    	
    	return $this->meta_credit;
    	
    }
    
    /**
     * Get camera
     *
     * @return  string  $this->meta_camera
     */
    public function getCamera() {
    	
    	return $this->meta_camera;
    	
    }
    
    /**
     * Get meta caption
     *
     * @return  string  $this->meta_caption
     */
    public function getMetaCaption() {
    	
    	return $this->meta_caption;
    	
    }
    
    /**
     * Get meta copyright
     *
     * @return  string  $this->meta_copyright
     */
    public function getCopyright() {
    	
    	return $this->meta_copyright;
    	
    }
    
    /**
     * Get title extracted from metadata, it could differ from the object title, use instead getTitle()
     *
     * @return  string  $this->meta_title
     */
    public function getImageTitle() {
    	
    	return $this->meta_title;
    	
    }
    
    /**
     * Get width for specified size
     *
     * @param   string $size A string representing a particular size which must be included in the $size_allowed list
     *
     * @return  int    $width
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getSizeWidth($size) {
    	
    	if (in_array($size, $this->size_allowed)) {
    		
    		if (isset($this->sizes[$size])) {
    			
    			return intval($this->sizes[$size]['width']);
    			
    		} else {
    		
	    		throw new WPException("The requested size information is not availabe");
	    		
	    	}
    		
    	} else {
    		
    		throw new WPException("The requested size is not supported");
    		
    	}
    	
    }
    
    /**
     * Get height for specified size
     *
     * @param   string $size A string representing a particular size which must be included in the $size_allowed list
     *
     * @return  int    $height
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getSizeHeight($size) {
    	
    	if (in_array($size, $this->size_allowed)) {
    		
    		if (isset($this->sizes[$size])) {
    			
    			return intval($this->sizes[$size]['height']);
    			
    		} else {
    		
	    		throw new WPException("The requested size information is not availabe");
	    		
	    	}
    		
    	} else {
    		
    		throw new WPException("The requested size is not supported");
    		
    	}
    	
    }
    
    /**
     * Get file for specified size
     *
     * @param   string $size A string representing a particular size which must be included in the $size_allowed list
     *
     * @return  int    $file
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getSizeFile($size) {
    	
    	if (in_array($size, $this->size_allowed)) {
    		
    		if (isset($this->sizes[$size])) {
    			
    			return $this->sizes[$size]['file'];
    			
    		} else {
    		
	    		throw new WPException("The requested size information is not availabe");
	    		
	    	}
    		
    	} else {
    		
    		throw new WPException("The requested size is not supported");
    		
    	}
    	
    }
    
    /**
     * Get mime-type for specified size
     *
     * @param   string $size A string representing a particular size which must be included in the $size_allowed list
     *
     * @return  int    $mimetype
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getSizeMimeType($size) {
    	
    	if (in_array($size, $this->size_allowed)) {
    		
    		if (isset($this->sizes[$size])) {
    			
    			return $this->sizes[$size]['mime-type'];
    			
    		} else {
    		
	    		throw new WPException("The requested size information is not availabe");
	    		
	    	}
    		
    	} else {
    		
    		throw new WPException("The requested size is not supported");
    		
    	}
    	
    }
    
    /**
     * Get creation date
     *
     * @param   string $format Date format
     *
     * @return  mixed  $this->date
     */
    public function getCreationDate($format = null) {
    	
    	if (is_null($format)) {
    		
    		return $this->date;
    		
    	} else {
    		
    		return date($format, $this->date);
    		
    	}
    	
    }
    
    /**
     * Get meta date
     *
     * @param   string $format Date format
     *
     * @return  mixed  $this->meta_timestamp
     */
    public function getMetaDate($format = null) {
    	
    	if (is_null($format)) {
    		
    		return $this->meta_timestamp;
    		
    	} else {
    		
    		return date($format, $this->meta_timestamp);
    		
    	}
    	
    }
    
    /**
     * Load a media object from its attachment ID
     *
     * @param   int    $id   Attachment ID
     *
     * @return  Object $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function loadFromID($id) {
    	
    	$this->resetData();
    	
    	try {
    		
            $data = $this->getWordpress()->sendMessage("wp.getMediaItem", array(
                intval($id)
            ), $this->getBlog());
            
            $this->loadData($data);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve media informations from attachment ID (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Load a media object from its position in the media library
     *
     * @param   int    $count Position in the media library
     *
     * @return  Object $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function loadFromLibrary($count, $mime) {
    	
    	$content = array(
    		"number" => 1,
    		"offset" => $count
    	);
    	
    	if ($this->post > 0) {
    		$content["parent_id"] = $this->post;
    	}
    	
    	if (!is_null($mime)) {
    		$content["mime_type"] = $mime;
    	}
    	
    	try {
            
            $data = $this->getWordpress()->sendMessage("wp.getMediaLibrary", array(
                $content
            ), $this->getBlog());
            
            if (count($data) > 0) {
            
            	$this->loadData($data[0]);
            	
            } else {
            	
            	$this->resetData();
            	
            	return null;
            	
            }
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve media informations from iteration (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
	
    /**
     * Load media object data
     *
     * @param   array   $data
     *
     * @return  Object  $this
     */
    
    public function loadData($data) {
			
		$this->id          = intval($data['attachment_id']);
			
		$this->post        = (isset($data['parent_id']))?intval($data['parent_id']):null;
			
		$this->date        = strtotime($data['date_created_gmt']);
		
		$this->link        = $data['link'];
		
		$this->title       = $data['title'];
		
		$this->caption     = $data['caption'];
		
		$this->description = $data['description'];
		
		$this->thumbnail   = $data['thumbnail'];
		
		if (isset($data['metadata']) && is_array($data['metadata'])) {
			
			$this->width   = intval($data['metadata']['width']);
			
			$this->height  = intval($data['metadata']['height']);
			
			$this->file    = $data['metadata']['file'];
			
			$this->sizes   = $data['metadata']['sizes'];
			
		}
		
		if (isset($data['image_meta'])) {
			
			$this->meta_timestamp     = strtotime($data['image_meta']['created_timestamp']);
			
			$this->meta_focal_length  = intval($data['image_meta']['focal_length']);
			
			$this->meta_iso           = intval($data['image_meta']['iso']);
			
			$this->meta_shutter_speed = intval($data['image_meta']['shutter_speed']);
			
			$this->meta_credit        = $data['image_meta']['credit'];
			
			$this->meta_camera        = $data['image_meta']['camera'];
			
			$this->meta_caption       = $data['image_meta']['caption'];
			
			$this->meta_copyright     = $data['image_meta']['copyright'];
			
			$this->meta_title         = $data['image_meta']['title'];
			
		}
    	
    	return $this;
        
    }
	
    /**
     * Reset object
     *
     * @return  Object  $this
     */
    
    public function resetData() {
			
		$this->id                  = 0;

		$this->post                = 0;

		$this->date                = 0;

		$this->link                = "";

		$this->title               = "";

		$this->caption             = "";

		$this->description         = "";

		$this->thumbnail           = "";

		$this->width               = 0;

		$this->height              = 0;

		$this->file                = "";

		$this->meta_timestamp      = 0;

		$this->meta_focal_length   = 0;

		$this->meta_iso            = 0;

		$this->meta_shutter_speed  = 0;

		$this->meta_credit         = "";

		$this->meta_camera         = "";

		$this->meta_caption        = "";

		$this->meta_copyright      = "";

		$this->meta_title          = "";

		$this->sizes               = array();
    	
    	return $this;
        
    }
    
    /**
     * Upload a file
     *
     * @param   string $fname File path or remote url
     *
     * @return  Object $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function upload($fname) {
    	
    	$name   = explode('/', $fname);
    	$name   = $name[count($name)-1];
    	$name   = explode("\\", $name);
    	$name   = $name[count($name)-1];
    	$buffer = file_get_contents($fname);
    	
    	if (!is_string($buffer)) {
    		
    		throw new WPException("Unable to open file $name");
    		
    	}
    	
    	try {
    	
    		return $this->uploadData($name, $buffer);
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Upload a string of data
     *
     * @param   string $name   File name
     * @param   string $buffer Data buffer
     *
     * @return  Object $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function uploadData($name, $buffer) {
    	
    	$finfo  = new \finfo(FILEINFO_MIME_TYPE);
    	$type   = $finfo->buffer($buffer);
    	
    	$content = array(
    		"name" => $name,
    		"type" => $type,
    		"bits" => base64_encode($buffer)
    	);
    	
    	if ($this->post > 0) {
    		$content["post_id"] = $this->post;
    	}
    	
    	try {
            
            $data = $this->getWordpress()->sendMessage("wp.uploadFile", array(
                $content
            ), $this->getBlog(), array( array( &$content["bits"], "base64" )));
            
            $this->loadFromID($data['id']);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to upload file (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
}