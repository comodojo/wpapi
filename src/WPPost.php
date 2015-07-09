<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress blog
 *
 * It allows to retrieve and edit posts from a wordpress blog.
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
class WPPost {
	
	/**
     * Wordpress blog reference
     *
     * @var Object
     */
	private $blog = null;
	
	/**
     * ID of the post
     *
     * @var int
     */
	private $id = 0;
	
	/**
     * Title of the post
     *
     * @var string
     */
	private $title = "";
	
	/**
     * Date of post creation in unix timestamp
     *
     * @var int
     */
	private $created = 0;
	
	/**
     * Date of last edit in unix timestamp
     *
     * @var int
     */
	private $modified = 0;
	
	/**
     * Post status
     *
     * @var string
     */
	private $status = "draft";
	private $supportedStatus = array();
	
	/**
     * Post type
     *
     * @var string
     */
	private $type = "post";
	private $supportedTypes = array();
	
	/**
     * Post format
     *
     * @var string
     */
	private $format = "standard";
	private $supportedFormats = array();
	
	/**
     * Post name
     *
     * @var string
     */
	private $name = "";
	
	/**
     * Reference to the author of the post
     *
     * @var Object
     */
	private $author = null;
	
	/**
     * Password for the post
     *
     * @var string
     */
	private $password = "";
	
	/**
     * Post excerpt
     *
     * @var string
     */
	private $excerpt = "";
	
	/**
     * Post content
     *
     * @var string
     */
	private $content = "";
	
	/**
     * Post parent
     *
     * @var int
     */
	private $parent = null;
	
	/**
     * Post mime type
     *
     * @var string
     */
	private $mime_type = "";
	
	/**
     * URL to access the post
     *
     * @var string
     */
	private $link = "";
	
	/**
     * GUID to access the post
     *
     * @var string
     */
	private $guid = "";
	
	/**
     * Post menu order
     *
     * @var int
     */
	private $menu_order = 0;
	
	/**
     * Comment status
     *
     * @var string
     */
	private $comment = "open";
	private $supportedCommentStatus = array('closed', 'open');
	
	/**
     * Ping status
     *
     * @var string
     */
	private $ping = "open";
	private $supportedPingStatus = array('closed', 'open');
	
	/**
     * Sticky
     *
     * @var boolean
     */
	private $sticky = false;
	
	/**
     * Thumbnail
     *
     * @var Object
     */
	private $thumbnail = null;
	
	/**
     * List of terms associated to the post
     *
     * @var array
     */
	private $terms = array();
	
	/**
     * List of custom fields for the post
     *
     * @var array
     */
	private $custom = array();
	
	/**
     * Enclosure
     *
     * @var array
     */
	private $enclosure = array();
	
	/**
     * Comments
     *
     * @var Object
     */
	private $comments = null;
	
    /**
     * Class constructor
     *
     * @param   Object  $blog     Reference to the wordpress blog
     * @param   int     $id       ID of the post (optional)
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $id=0) {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access post informations");
        	
        }
        
        $this->blog    = $blog;
        
        if ($id > 0) {
        	
        	try {
        		
        		$this->loadFromID($id);
        		
        	} catch (WPException $wpe) {
        		
        		throw $wpe;
        		
        	}
        	
        } else {
        	
        	$this->created = time();
        	
        	$this->author  = $blog->getProfile();
        	
        }
        
    }
	
    /**
     * Load data for a post
     *
     * @param   int     $id       ID of the post (optional)
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromID($id) {
    	
    	$this->resetData();
    	
    	try {
    		
            $post = $this->getWordpress()->sendMessage("wp.getPost", array(
                intval($id)
            ), $this->getBlog());
	
			$this->id           = intval($post['post_id']);
			
			$this->title        = $post['post_title'];
			
			$this->created      = (is_numeric($post['post_date']))?$post['post_date']:strtotime($post['post_date']);
			
			$this->modified     = (is_numeric($post['post_modified']))?$post['post_modified']:strtotime($post['post_modified']);
			
			$this->status       = $post['post_status'];
			
			$this->type         = $post['post_type'];
			
			$this->format       = $post['post_format'];
			
			$this->name         = $post['post_name'];
			
			$this->author       = new WPUser($this->getBlog(), $post['post_author']);
			
			$this->password     = $post['post_password'];
			
			$this->excerpt      = $post['post_excerpt'];
			
			$this->content      = $post['post_content'];
			
			$this->parent       = $post['post_parent'];
			
			$this->mime_type    = $post['post_mime_type'];
			
			$this->link         = $post['link'];
			
			$this->guid         = $post['guid'];
			
			$this->menu_order   = intval($post['menu_order']);
			
			$this->comment      = $post['comment_status'];
			
			$this->ping         = $post['ping_status'];
			
			$this->sticky       = filter_var($post['sticky'], FILTER_VALIDATE_BOOLEAN);
			
			$this->terms        = array();
			
			$this->custom       = $post['custom_fields'];
			
			$this->enclosure    = (isset($post['enclosure']))?$post['enclosure']:null;
            
        	$this->comments     = $this->getCommentsByStatus();
			
			if ( isset($post['post_thumbnail']['attachment_id']) ) {
				
				$this->thumbnail = new WPMedia($this->getBlog());
				
				$this->thumbnail->loadData($post['post_thumbnail']);
				
			}
			
			foreach ($post['terms'] as $term) {
				
				$taxonomy = $this->getBlog()->getTaxonomy($term['taxonomy']);
				
				$termObj  = new WPTerm($taxonomy);
				
				$termObj->loadData($term);
				
				array_push(
					$this->terms,
					$termObj
				);
				
			}
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
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
     * Get post blog
     *
     * @return  Object  $this->blog
     */
    public function getBlog() {
    	
    	return $this->blog;
    	
    }
    
    /**
     * Get post id
     *
     * @return  string  $this->id
     */
    public function getID() {
    	
    	return $this->id;
    	
    }
    
    /**
     * Get post title
     *
     * @return  string  $this->title
     */
    public function getTitle() {
    	
    	return $this->title;
    	
    }
    
    /**
     * Set post title
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setTitle($value) {
    	
    	$this->title = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get creation date
     *
     * @param   string  $format Date format
     *
     * @return  mixed  $this->created
     */
    public function getCreationDate($format = null) {
    	
    	if (is_null($format)) {
    		
    		return $this->created;
    		
    	} else {
    		
    		return date($format, $this->created);
    		
    	}
    	
    }
    
    /**
     * Set creation date
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setCreationDate($value) {
    	
    	if (is_numeric($value)) {
    		
    		$this->created = intval($value);
    		
    	} else {
    		
    		$this->created = strtotime($value);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get last modified date
     *
     * @param   string  $format Date format
     *
     * @return  mixed  $this->modified
     */
    public function getLastModifiedDate($format = null) {
    	
    	if (is_null($format)) {
    		
    		return $this->modified;
    		
    	} else {
    		
    		return date($format, $this->modified);
    		
    	}
    	
    }
    
    /**
     * Get post status
     *
     * @return  string  $this->status
     */
    public function getStatus() {
    	
    	return $this->status;
    	
    }
    
    /**
     * Set post status
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setStatus($value) {
            
        if (empty($this->supportedStatus)) 
        	$this->supportedStatus  = array_keys($this->getBlog()->getSupportedPostStatus());
    	
    	if (in_array($value, $this->supportedStatus)) {
    		
    		$this->status = $value;
    	
    		return $this;
    		
    	} else {
    		
    		throw new WPException("Unsupported post status");
    		
    	}
    	
    }
    
    /**
     * Get post type
     *
     * @return  string  $this->type
     */
    public function getType() {
    	
    	return $this->type;
    	
    }
    
    /**
     * Set ping post type
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setType($value) {
            
        if (empty($this->supportedTypes)) 
        	$this->supportedTypes   = array_keys($this->getBlog()->getSupportedTypes());
    	
    	if (in_array($value, $this->supportedTypes)) {
    		
    		$this->type = $value;
    	
    		return $this;
    		
    	} else {
    		
    		throw new WPException("Unsupported post type");
    		
    	}
    	
    }
    
    /**
     * Get post format
     *
     * @return  string  $this->format
     */
    public function getFormat() {
    	
    	return $this->format;
    	
    }
    
    /**
     * Set post format
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setFormat($value) {
            
        if (empty($this->supportedFormats)) 
        	$this->supportedFormats = array_keys($this->getBlog()->getSupportedFormats());
    	
    	if (in_array($value, $this->supportedFormats)) {
    		
    		$this->format = $value;
    	
    		return $this;
    		
    	} else {
    		
    		throw new WPException("Unsupported post format");
    		
    	}
    	
    }
    
    /**
     * Get post name
     *
     * @return  string  $this->name
     */
    public function getName() {
    	
    	return $this->name;
    	
    }
    
    /**
     * Get post author
     *
     * @return  Object  $this->author
     */
    public function getAuthor() {
    	
    	return $this->author;
    	
    }
    
    /**
     * Set author
     *
     * @param   mixed  $value
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setAuthor($value) {
    	
    	if (is_numeric($value)) {
    		
	    	try {
	    		
	    		$this->author = new WPUser($this->getBlog(), $value);
	    		
	    	} catch (WPException $wpe) {
	    		
	    		throw $wpe;
	    		
	    	}
	    	
    		
    	} else {
    		
    		$this->author = $value;
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get post password
     *
     * @return  mixed  $this->password
     */
    public function getPassword() {
    	
    	return (empty($this->password))?false:$this->password;
    	
    }
    
    /**
     * Set post password
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setPassword($value) {
    	
    	$this->password = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get post excerpt
     *
     * @return  string  $this->excerpt
     */
    public function getExcerpt() {
    	
    	return $this->excerpt;
    	
    }
    
    /**
     * Set post excerpt
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setExcerpt($value) {
    	
    	$this->excerpt = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get post content
     *
     * @return  string  $this->content
     */
    public function getContent() {
    	
    	return $this->content;
    	
    }
    
    /**
     * Set post content
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setContent($value) {
    	
    	$this->content = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get post parent
     *
     * @return  Object  $this->parent
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getParent() {
    	
    	if (empty($this->parent) || is_null($this->parent) || $this->parent == 0) return null;
    	
    	try {
    		
    		return new WPPost($this->getBlog(), $this->parent);
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	
    }
    
    /**
     * Set post parent
     *
     * @param   mixed  $value
     *
     * @return  Object  $this
     */
    public function setParent($value) {
    	
    	if (is_numeric($value)) {
    		
    		$this->parent = intval($value);
    		
    	} else {
    		
    		$this->parent = $value->getID();
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get post mime type
     *
     * @return  string  $this->mime_type
     */
    public function getMimeType() {
    	
    	return $this->mime_type;
    	
    }
    
    /**
     * Get post link
     *
     * @return  string  $this->link
     */
    public function getLink() {
    	
    	return $this->link;
    	
    }
    
    /**
     * Get post guid
     *
     * @return  string  $this->guid
     */
    public function getGUID() {
    	
    	return $this->guid;
    	
    }
    
    /**
     * Get post menu order
     *
     * @return  int  $this->menu_order
     */
    public function getMenuOrder() {
    	
    	return $this->menu_order;
    	
    }
    
    /**
     * Set post menu order
     *
     * @param   int  $value
     *
     * @return  Object  $this
     */
    public function setMenuOrder($value) {
    	
    	$this->menu_order = intval($value);
    	
    	return $this;
    	
    }
    
    /**
     * Get comment status
     *
     * @return  string  $this->comment
     */
    public function getCommentStatus() {
    	
    	return $this->comment;
    	
    }
    
    /**
     * Set comment status
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setCommentStatus($value) {
    	
    	if (in_array($value, $this->supportedCommentStatus)) {
    		
    		$this->comment = $value;
    	
    		return $this;
    		
    	} else {
    		
    		throw new WPException("Unsupported comment status");
    		
    	}
    	
    }
    
    /**
     * Get ping status
     *
     * @return  string  $this->ping
     */
    public function getPingStatus() {
    	
    	return $this->ping;
    	
    }
    
    /**
     * Set ping status
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setPingStatus($value) {
    	
    	if (in_array($value, $this->supportedPingStatus)) {
    		
    		$this->ping = $value;
    	
    		return $this;
    		
    	} else {
    		
    		throw new WPException("Unsupported ping status");
    		
    	}
    	
    }
    
    /**
     * Sticky
     *
     * @return  boolean  $this->sticky
     */
    public function isSticky() {
    	
    	return $this->sticky;
    	
    }
    
    /**
     * Set sticky
     *
     * @param   boolean  $value
     *
     * @return  Object  $this
     */
    public function setSticky($value) {
    	
    	$this->sticky = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get custom field
     *
     * @param   string  $field Field name
     *
     * @return  mixed  $this->modified
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
     * Set custom field
     *
     * @param   string  $key
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setCustomField($key, $value) {
    	
    	foreach ($this->custom as $id => $custom) {
    		
    		if ($custom['key'] == $key) {
    			
    			$this->custom[$id]['value'] = $value;
    			
    			return $this;
    			
    		}
    		
    	}
    	
    	// If the custom field requested does not exists, a new one will be created
    	array_push($this->custom, array(
    		'key'   => $key,
    		'value' => $value
    	));
    	
    	return $this;
    	
    }
    
    /**
     * Get enclosure url
     *
     * @return  string  $this->enclosure['url']
     */
    public function getEnclosureURL() {
    	
    	if (isset($this->enclosure['url']))
    		return $this->enclosure['url'];
    	else
    		return null;
    	
    }
    
    /**
     * Set enclosure url
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setEnclosureURL($value) {
    	
    	$this->enclosure['url'] = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get enclosure length
     *
     * @return  string  $this->enclosure['length']
     */
    public function getEnclosureLength() {
    	
    	if (isset($this->enclosure['length']))
    		return $this->enclosure['length'];
    	else
    		return null;
    	
    }
    
    /**
     * Set enclosure length
     *
     * @param   int  $value
     *
     * @return  Object  $this
     */
    public function setEnclosureLength($value) {
    	
    	$this->enclosure['length'] = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get enclosure type
     *
     * @return  string  $this->enclosure['type']
     */
    public function getEnclosureType() {
    	
    	if (isset($this->enclosure['type']))
    		return $this->enclosure['type'];
    	else
    		return null;
    	
    }
    
    /**
     * Set enclosure type
     *
     * @param   string  $value
     *
     * @return  Object  $this
     */
    public function setEnclosureType($value) {
    	
    	$this->enclosure['type'] = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get terms
     *
     * @return  array  $this->terms
     */
    public function getTerms() {
    	
    	return $this->terms;
    	
    }
    
    /**
     * Get categories
     *
     * @return  array  $categories
     */
    public function getCategories() {
    	
    	$categories = array();
    	
    	foreach ($this->terms as $term) {
    		
    		if ($term->getTaxonomy()->getName() == "category") array_push($categories, $term);
    		
    	}
    	
    	return $categories;
    	
    }
    
    /**
     * Add category
     *
     * @param   string $category category name
     *
     * @return  Object $this
     */
    public function addCategory($category) {
    	
    	if ($this->getBlog()->hasCategory($category)) {
    		
    		$term = $this->getBlog()->getCategory($category);
    		
    	} else {
    		
    		$taxonomy = $this->getBlog()->getTaxonomy("category");
    		$term = new WPTerm($taxonomy);
    		$term->setName($category)->save();
    		
    		$this->getBlog()->addTag($term);
    	}
    	
    	return $this->addTerm($term);
    	
    }
    
    /**
     * Has category
     *
     * @param   string  $category Category name
     *
     * @return  boolean $hasCategory
     */
    public function hasCategory($category) {
    	
    	foreach ($this->getCategories() as $t) {
    		
    		if ($t->getName() == $category) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Get tags
     *
     * @return  array  $tags
     */
    public function getTags() {
    	
    	$tags = array();
    	
    	foreach ($this->terms as $term) {
    		
    		if ($term->getTaxonomy()->getName() == "post_tag") array_push($tags, $term);
    		
    	}
    	
    	return $tags;
    	
    }
    
    /**
     * Has tag
     *
     * @param   string  $tag Tag name
     *
     * @return  boolean $hasTag
     */
    public function hasTag($tag) {
    	
    	foreach ($this->getTags() as $t) {
    		
    		if ($t->getName() == $tag) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Add tag
     *
     * @param   string  $tag Tag name
     *
     * @return  Object $this
     */
    public function addTag($tag) {
    	
    	if ($this->getBlog()->hasTag($tag)) {
    		
    		$term = $this->getBlog()->getTag($tag);
    		
    	} else {
    		
    		$taxonomy = $this->getBlog()->getTaxonomy("post_tag");
    		$term = new WPTerm($taxonomy);
    		$term->setName($tag)->save();
    		
    		$this->getBlog()->addTag($term);
    	}
    	
    	return $this->addTerm($term);
    	
    }
    
    /**
     * Add term
     *
     * @param   Opject  $term WPTerm object
     *
     * @return  Object  $this
     */
    public function addTerm($term) {
    	
    	if (!$this->hasTerm($term)) {
    		
    		array_push($this->terms, $term);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Has term
     *
     * @param   mixed   $term Term ID or WPTerm object
     *
     * @return  boolean $hasTerm
     */
    public function hasTerm($term) {
    	
    	if (is_numeric($term)) {
    		
    		$term = intval($term);
    		
    	} else {
    		
    		$term = $term->getID();
    		
    	}
    	
    	foreach ($this->terms as $t) {
    		
    		if ($t->getID() == $term) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Get comments for current post
     *
     * @return  Object $commentIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getComments() {
    	
    	return $this->comments;
    	
    }
    
    /**
     * Get comments for current post filtered by status
     *
     * @param   string $status Comment status (check WPBlog::getSupportedCommentStatus)
     *
     * @return  Object $commentIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getCommentsByStatus($status = "") {
    	
    	if ($this->getID() > 0) {
	    	try {
	            
	            return new WPCommentIterator($this, $status);
            
	    	} catch (WPException $wpe) {
	    		
	    		throw $wpe;
	    		
	    	}
	            
    	}
    	
    	return null;
    	
    }
    
    /**
     * Get post thumbnail
     *
     * @return  Object  $this->thumbnail
     */
    public function getThumbnail() {
    	
    	return $this->thumbnail;
    	
    }
    
    /**
     * Set post thumbnail
     *
     * @param   mixed  $thumb
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setThumbnail($thumb) {
    	
    	if (is_numeric($thumb)) {
    	
	    	try {
	    		
	    		$this->thumbnail = new WPMedia($this->getBlog(), $thumb);
	            
	    	} catch (WPException $wpe) {
	    		
	    		throw $wpe;
	    		
	    	}
    		
    	} else {
    		
    		$this->thumbnail = $thumb;
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get post attachments
     * 
     * @param   string $mime The mime-type of the media you want to fetch
     *
     * @return  Object  $mediaIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getAttachments($mime = null) {
    	
    	$mediaIterator = null;
    	
    	try {
    		
            $mediaIterator = new WPMediaIterator($this->getBlog(), $this->getID(), $mime);
            
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $mediaIterator;
    	
    }
	
    /**
     * Save post
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function save() {
    	
    	try {
    	
	    	if ($this->getID() == 0) {
	    		
	    		$this->createPost();
	    		
	    	} else {
	    		
	    		$this->editPost();
	    		
	    	}
	    	
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Create post
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function createPost() {
    	
    	$content = $this->getPostData();
    	
    	try {
    		
            $id = $this->getWordpress()->sendMessage("wp.newPost", array(
                $content
            ), $this->getBlog(), array( "post_date", "datetime" ));
            
            $this->loadFromID($id);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to create new post (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Edit post
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function editPost() {
    	
    	$content = $this->getPostData();
    	
    	try {
    		
            $this->getWordpress()->sendMessage("wp.editPost", array(
                $this->getID(),
                $content
            ), $this->getBlog(), array( "post_date", "datetime" ));
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to edit post (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Load post data
     *
     * @return  array  $data
     */
    private function getPostData() {
    	 	
    	$data = array(
    		'post_type'      => $this->type,
    		'post_status'    => $this->status,
    		'post_title'     => $this->title,
    		'post_author'    => $this->author->getID(),
    		'post_content'   => $this->content,
    		'post_date'      => $this->created,
    		'post_format'    => $this->format,
    		'comment_status' => $this->comment,
    		'menu_order'     => $this->menu_order,
    		'ping_status'    => $this->ping,
    		'sticky'         => ($this->sticky)?1:0
    	);
    	
    	if (count($this->custom) > 0) {
    		
    		$data['custom_fields'] = $this->custom;
    		
    	}
    	
    	if (!is_null($this->getParent())) {
    		
    		$data['post_parent'] = $this->parent;
    		
    	}
    	
    	if (!is_null($this->thumbnail)) {
    		
    		$data['post_thumbnail'] = $this->thumbnail->getID();
    		
    	}
    	
    	if (!empty($this->enclosure)) {
    		
    		$data['enclosure'] = $this->enclosure;
    		
    	}
    	
    	if (!empty($this->password)) {
    		
    		$data['post_password'] = $this->password;
    		
    	}
    	
    	if (!empty($this->excerpt)) {
    		
    		$data['post_excerpt'] = $this->excerpt;
    		
    	}
    	
    	if (count($this->terms) > 0) {
    		
    		$data['terms'] = array();
    		
    		foreach ($this->terms as $term) {
    			
    			$key = $term->getTaxonomy()->getName();
    			
    			if (!isset($data['terms'][$key])) $data['terms'][$key] = array();
    			
    			array_push($data['terms'][$key], $term->getID());
    			
    		}
    		
    		
    	}
    	
    	return $data;
    		
    }
    
    /**
     * Delete post
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function delete() {
    	
    	try {
            
            $return = $this->getWordpress()->sendMessage("wp.deletePost", array(
                $this->getID()
            ), $this->getBlog());
            
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to delete post (".$wpe->getMessage().")");
    		
    	}
    	
    	$this->resetData();
    	
    	return filter_var($return, FILTER_VALIDATE_BOOLEAN);
    	
    }
	
    /**
     * Reset data of the object, it can still be used calling the loadFromID method
     *
     * @return  Object  $this
     */
    
    private function resetData() {
			
		$this->id           = 0;
		
		$this->title        = "";
		
		$this->created      = 0;
		
		$this->modified     = 0;
		
		$this->status       = "draft";
		
		$this->type         = "post";
		
		$this->format       = "standard";
		
		$this->name         = "";
		
		$this->author       = null;
		
		$this->password     = "";
		
		$this->excerpt      = "";
		
		$this->content      = "";
		
		$this->parent       = null;
		
		$this->mime_type    = "";
		
		$this->link         = "";
		
		$this->guid         = "";
		
		$this->menu_order   = 0;
		
		$this->comment      = "open";
		
		$this->ping         = "open";
		
		$this->sticky       = false;
		
		$this->thumbnail    = null;
		
		$this->terms        = array();
		
		$this->custom       = array();
		
		$this->enclosure    = array();
		
		$this->comments     = null;
    	
    	return $this;
        
    }
    
}