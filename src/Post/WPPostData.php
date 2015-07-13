<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress post data object
 *
 * It allows to get data of a post from a wordpress blog.
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
abstract class WPPostData extends WPBlogObject {
	
	/**
     * Title of the post
     *
     * @var string
     */
	protected $title = "";
	
	/**
     * Date of post creation in unix timestamp
     *
     * @var int
     */
	protected $created = 0;
	
	/**
     * Date of last edit in unix timestamp
     *
     * @var int
     */
	protected $modified = 0;
	
	/**
     * Post status
     *
     * @var string
     */
	protected $status = "draft";
	protected $supportedStatus = array();
	
	/**
     * Post type
     *
     * @var string
     */
	protected $type = "post";
	protected $supportedTypes = array();
	
	/**
     * Post format
     *
     * @var string
     */
	protected $format = "standard";
	protected $supportedFormats = array();
	
	/**
     * Post name
     *
     * @var string
     */
	protected $name = "";
	
	/**
     * Reference to the author of the post
     *
     * @var WPUser
     */
	protected $author = null;
	
	/**
     * Password for the post
     *
     * @var string
     */
	protected $password = "";
	
	/**
     * Post excerpt
     *
     * @var string
     */
	protected $excerpt = "";
	
	/**
     * Post content
     *
     * @var string
     */
	protected $content = "";
	
	/**
     * Post parent
     *
     * @var int
     */
	protected $parent = null;
	
	/**
     * Post mime type
     *
     * @var string
     */
	protected $mime_type = "";
	
	/**
     * URL to access the post
     *
     * @var string
     */
	protected $link = "";
	
	/**
     * GUID to access the post
     *
     * @var string
     */
	protected $guid = "";
	
	/**
     * Post menu order
     *
     * @var int
     */
	protected $menu_order = 0;
	
	/**
     * Comment status
     *
     * @var string
     */
	protected $comment = "open";
	protected $supportedCommentStatus = array('closed', 'open');
	
	/**
     * Ping status
     *
     * @var string
     */
	protected $ping = "open";
	protected $supportedPingStatus = array('closed', 'open');
	
	/**
     * Sticky
     *
     * @var boolean
     */
	protected $sticky = false;
	
	/**
     * Thumbnail
     *
     * @var Object
     */
	protected $thumbnail = null;
	
	/**
     * List of terms associated to the post
     *
     * @var array
     */
	protected $terms = array();
	
	/**
     * List of custom fields for the post
     *
     * @var array
     */
	protected $custom = array();
	
	/**
     * Enclosure
     *
     * @var array
     */
	protected $enclosure = array();
    
    /**
     * Get post title
     *
     * @return string $title
     */
    public function getTitle() {
    	
    	return $this->title;
    	
    }
    
    /**
     * Get creation date
     *
     * @param  string $format Date format (optional)
     *
     * @return mixed  $created
     */
    public function getCreationDate($format = null) {
    	
    	return $this->getFormattedDate($this->created, $format);
    	
    }
    
    /**
     * Get last modified date
     *
     * @param  string $format Date format (optional)
     *
     * @return mixed  $modified
     */
    public function getLastModifiedDate($format = null) {
    	
    	return $this->getFormattedDate($this->modified, $format);
    	
    }
    
    /**
     * Get post status
     *
     * @return string $status
     */
    public function getStatus() {
    	
    	return $this->status;
    	
    }
    
    /**
     * Get post type
     *
     * @return string $type
     */
    public function getType() {
    	
    	return $this->type;
    	
    }
    
    /**
     * Get post format
     *
     * @return string $format
     */
    public function getFormat() {
    	
    	return $this->format;
    	
    }
    
    /**
     * Get post name
     *
     * @return string $name
     */
    public function getName() {
    	
    	return $this->name;
    	
    }
    
    /**
     * Get post author
     *
     * @return WPPostData $author
     */
    public function getAuthor() {
    	
    	return $this->author;
    	
    }
    
    /**
     * Get post password
     *
     * @return mixed $password
     */
    public function getPassword() {
    	
    	return (empty($this->password))?false:$this->password;
    	
    }
    
    /**
     * Get post excerpt
     *
     * @return  string $excerpt
     */
    public function getExcerpt() {
    	
    	return $this->excerpt;
    	
    }
    
    /**
     * Get post content
     *
     * @return string $content
     */
    public function getContent() {
    	
    	return $this->content;
    	
    }
    
    /**
     * Get post parent
     *
     * @return WPPost $parent
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getParent() {
    	
    	if (empty($this->parent) || is_null($this->parent) || $this->parent == 0) return null;
    	
    	return new WPPost($this->getBlog(), $this->parent);
    	
    }
    
    /**
     * Get post mime type
     *
     * @return string $mime_type
     */
    public function getMimeType() {
    	
    	return $this->mime_type;
    	
    }
    
    /**
     * Get post link
     *
     * @return string $link
     */
    public function getLink() {
    	
    	return $this->link;
    	
    }
    
    /**
     * Get post guid
     *
     * @return string $guid
     */
    public function getGUID() {
    	
    	return $this->guid;
    	
    }
    
    /**
     * Get post menu order
     *
     * @return int $menu_order
     */
    public function getMenuOrder() {
    	
    	return $this->menu_order;
    	
    }
    
    /**
     * Get comment status
     *
     * @return string $commentStatus
     */
    public function getCommentStatus() {
    	
    	return $this->comment;
    	
    }
    
    /**
     * Get ping status
     *
     * @return string $ping
     */
    public function getPingStatus() {
    	
    	return $this->ping;
    	
    }
    
    /**
     * Sticky
     *
     * @return boolean $sticky
     */
    public function isSticky() {
    	
    	return $this->sticky;
    	
    }
    
    /**
     * Get custom fields
     *
     * @return array $customFields
     */
    public function getCustomFields() {
    	
    	return $this->custom;
    	
    }
    
    /**
     * Get custom field
     *
     * @param  string $field Field name
     *
     * @return mixed  $value Value of the custom field
     */
    public function getCustomField($field) {
    	
    	foreach ($this->custom as $custom) {
    		
    		if ($custom['key'] == $field) {
    			
    			return $custom['value'];
    			
    		}
    		
    	}
    		
    	return null;
    	
    }
    
    /**
     * Has custom field
     *
     * @param  string $field Field name
     *
     * @return boolean $hasCustomField
     */
    public function hasCustomField($field) {
    	
    	foreach ($this->custom as $custom) {
    		
    		if ($custom['key'] == $field) {
    			
    			return true;
    			
    		}
    		
    	}
    		
    	return false;
    	
    }
    
    /**
     * Get enclosure array
     *
     * @return array $enclosure
     */
    public function getEnclosure() {
    	
    	return $this->enclosure;
    	
    }
    
    /**
     * Get enclosure url
     *
     * @return string $enclosure_url
     */
    public function getEnclosureURL() {
    	
    	if (isset($this->enclosure['url']))
    		return $this->enclosure['url'];
    	else
    		return null;
    	
    }
    
    /**
     * Get enclosure length
     *
     * @return  string  $enclosure_length
     */
    public function getEnclosureLength() {
    	
    	if (isset($this->enclosure['length']))
    		return $this->enclosure['length'];
    	else
    		return null;
    	
    }
    
    /**
     * Get enclosure type
     *
     * @return string $enclosure_type
     */
    public function getEnclosureType() {
    	
    	if (isset($this->enclosure['type']))
    		return $this->enclosure['type'];
    	else
    		return null;
    	
    }
    
    /**
     * Get post thumbnail
     *
     * @return WPPostData $thumbnail
     */
    public function getThumbnail() {
    	
    	return $this->thumbnail;
    	
    }
    
}