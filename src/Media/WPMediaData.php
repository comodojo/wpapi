<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class contains Wordpress media info
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
abstract class WPMediaData extends WPBlogObject {
	
	/**
     * Date of creation
     *
     * @var int
     */
	protected $date = 0;
	
	/**
     * Post ID
     *
     * @var int
     */
	protected $post = 0;
	
	/**
     * URL to the media file
     *
     * @var string
     */
	protected $link = "";
	
	/**
     * Title
     *
     * @var string
     */
	protected $title = "";
	
	/**
     * Caption
     *
     * @var string
     */
	protected $caption = "";
	
	/**
     * Description
     *
     * @var string
     */
	protected $description = "";
	
	/**
     * Thumbnail
     *
     * @var string
     */
	protected $thumbnail = "";
	
	/**
     * File
     *
     * @var string
     */
	protected $file = "";
	
	/**
     * Image width
     *
     * @var int
     */
	protected $width = 0;
	
	/**
     * Image height
     *
     * @var int
     */
	protected $height = 0;
	
	/**
     * Image meta timestamp
     *
     * @var int
     */
	protected $meta_timestamp = 0;
	
	/**
     * Image meta focal length
     *
     * @var int
     */
	protected $meta_focal_length = 0;
	
	/**
     * Image meta iso
     *
     * @var int
     */
	protected $meta_iso = 0;
	
	/**
     * Image meta shutter speed
     *
     * @var int
     */
	protected $meta_shutter_speed = 0;
	
	/**
     * Meta credit
     *
     * @var string
     */
	protected $meta_credit = "";
	
	/**
     * Meta camera
     *
     * @var string
     */
	protected $meta_camera = "";
	
	/**
     * Meta caption
     *
     * @var string
     */
	protected $meta_caption = "";
	
	/**
     * Meta copyright
     *
     * @var string
     */
	protected $meta_copyright = "";
	
	/**
     * Meta title
     *
     * @var string
     */
	protected $meta_title = "";
	
	/**
     * Image size info
     *
     * @var array
     */
	protected $sizes = array();
	
	/**
     * Media data buffer
     *
     * @var string
     */
	protected $buffer = "";
	
	/**
     * Media mime type
     *
     * @var string
     */
	protected $mime = "";
	
	/**
     * Media filename
     *
     * @var string
     */
	protected $filename = "";
	
	/**
     * Allowed image sizes
     *
     * @var array
     */
	protected $size_allowed = array("thumbnail", "medium", "large", "post-thumbnail");
    
    /**
     * Get associated post ID
     *
     * @return  int $post
     */
    public function getPostID() {
    	
    	return $this->post;
    	
    }
    
    /**
     * Set associated post ID
     *
     * @param   int  $post
     *
     * @return  WPMediaData $this
     */
    public function setPostID($post) {
    		
    	$this->post = $post;
    	
    	return $this;
    	
    }
    
    /**
     * Get link
     *
     * @return  string $link
     */
    public function getLink() {
    	
    	return $this->link;
    	
    }
    
    /**
     * Get title
     *
     * @return  string $title
     */
    public function getTitle() {
    	
    	return $this->title;
    	
    }
    
    /**
     * Get caption
     *
     * @return  string  $caption
     */
    public function getCaption() {
    	
    	return $this->caption;
    	
    }
    
    /**
     * Get description
     *
     * @return  strind $description
     */
    public function getDescription() {
    	
    	return $this->description;
    	
    }
    
    /**
     * Get thumbnail
     *
     * @return string $thumbnail
     */
    public function getThumbnail() {
    	
    	return $this->thumbnail;
    	
    }
    
    /**
     * Get allowed image sizes
     *
     * @return array $size_allowed
     */
    public function getSupportedMediaSizes() {
    	
    	return $this->size_allowed;
    	
    }
    
    /**
     * Get file
     *
     * @return string $file
     */
    public function getFile() {
    	
    	return $this->file;
    	
    }
    
    /**
     * Get width
     *
     * @return  int  $width
     */
    public function getWidth() {
    	
    	return $this->width;
    	
    }
    
    /**
     * Get height
     *
     * @return  int  $height
     */
    public function getHeight() {
    	
    	return $this->height;
    	
    }
    
    /**
     * Get focal length
     *
     * @return  int  $meta_focal_length
     */
    public function getFocalLength() {
    	
    	return $this->meta_focal_length;
    	
    }
    
    /**
     * Get ISO
     *
     * @return  int  $meta_iso
     */
    public function getISO() {
    	
    	return $this->meta_iso;
    	
    }
    
    /**
     * Get shutter speed
     *
     * @return  int  $meta_shutter_speed
     */
    public function getShutterSpeed() {
    	
    	return $this->meta_shutter_speed;
    	
    }
    
    /**
     * Get credit
     *
     * @return  string  $meta_credit
     */
    public function getCredit() {
    	
    	return $this->meta_credit;
    	
    }
    
    /**
     * Get camera
     *
     * @return  string  $meta_camera
     */
    public function getCamera() {
    	
    	return $this->meta_camera;
    	
    }
    
    /**
     * Get meta caption
     *
     * @return  string  $meta_caption
     */
    public function getMetaCaption() {
    	
    	return $this->meta_caption;
    	
    }
    
    /**
     * Get meta copyright
     *
     * @return  string  $meta_copyright
     */
    public function getCopyright() {
    	
    	return $this->meta_copyright;
    	
    }
    
    /**
     * Get title extracted from metadata, it could differ from the object title, use instead getTitle()
     *
     * @return  string  $meta_title
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
     * @return  string $file
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
     * @return  string $mimetype
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
     * @return  mixed  $date
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
     * @return  mixed  $meta_timestamp
     */
    public function getMetaDate($format = null) {
    	
    	if (is_null($format)) {
    		
    		return $this->meta_timestamp;
    		
    	} else {
    		
    		return date($format, $this->meta_timestamp);
    		
    	}
    	
    }
    
    /**
     * Upload a file
     *
     * @param   string $fname File path or remote url
     *
     * @return  WPMediaData $this
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
     * @return  WPMediaData $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function uploadData($name, $buffer) {
    	
    	$finfo  = new \finfo(FILEINFO_MIME_TYPE);
    	
    	$type   = $finfo->buffer($buffer);
    	
    	$this->filename = $name;
    	
    	$this->mime = $type;
    	
    	$this->buffer = base64_encode($buffer);
    	
    	return $this;
    	
    }
    
}