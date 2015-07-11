<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress post data object
 *
 * It allows to get and set data of a post from a wordpress blog.
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
     * Set post title
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setTitle($value) {
    	
    	$this->title = $value;
    	
    	return $this;
    	
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
     * Set creation date
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setCreationDate($value) {
    	
    	$this->created = $this->parseTimestamp($value);
    	
    	return $this;
    	
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
     * Set modified date
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    protected function setLastModifiedDate($value) {
    	
    	$this->modified = $this->parseTimestamp($value);
    	
    	return $this;
    	
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
     * Set post status
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setStatus($value) {
            
        if (empty($this->supportedStatus)) 
        	$this->supportedStatus  = $this->getBlog()->getSupportedPostStatus();
    	
    	return $this->setCheckedValue($this->supportedStatus, $value, $this->status);
    	
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
     * Set ping post type
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setType($value) {
            
        if (empty($this->supportedTypes)) 
        	$this->supportedTypes = $this->getBlog()->getSupportedTypes();
    	
    	return $this->setCheckedValue($this->supportedTypes, $value, $this->type);
    	
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
     * Set post format
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setFormat($value) {
            
        if (empty($this->supportedFormats)) 
        	$this->supportedFormats = $this->getBlog()->getSupportedFormats();
    	
    	
    	return $this->setCheckedValue($this->supportedFormats, $value, $this->format);
    	
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
     * Set post name
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    protected function setName($value) {
    	
    	$this->name = $value;
    	
    	return $this;
    	
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
     * Set author
     *
     * @param  mixed      $value Author ID or WPUser reference
     *
     * @return WPPostData $this
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
     * @return mixed $password
     */
    public function getPassword() {
    	
    	return (empty($this->password))?false:$this->password;
    	
    }
    
    /**
     * Set post password
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setPassword($value) {
    	
    	$this->password = $value;
    	
    	return $this;
    	
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
     * Set post excerpt
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setExcerpt($value) {
    	
    	$this->excerpt = $value;
    	
    	return $this;
    	
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
     * Set post content
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setContent($value) {
    	
    	$this->content = $value;
    	
    	return $this;
    	
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
    	
    	try {
    		
    		return new WPPost($this->getBlog(), $this->parent);
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Set post parent
     *
     * @param  mixed      $value Parent post ID or WPPost reference
     *
     * @return WPPostData $this
     */
    public function setParent($value = null) {
    	
    	if (is_null($value)) {
    		
    		$this->parent = null;
    		
    	} elseif (is_numeric($value)) {
    		
    		$this->parent = intval($value);
    		
    	} else {
    		
    		$this->parent = $value->getID();
    		
    	}
    	
    	return $this;
    	
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
     * Get post mime type
     *
     * @return string     $value
     *
     * @return WPPostData $this
     */
    protected function setMimeType($value) {
    	
    	$this->mime_type = $value;
    	
    	return $this;
    	
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
     * Set post link
     *
     * @return string     $value
     *
     * @return WPPostData $this
     */
    protected function setLink($value) {
    	
    	$this->link = $value;
    	
    	return $this;
    	
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
     * Set post guid
     *
     * @return string     $value
     *
     * @return WPPostData $this
     */
    protected function setGUID($value) {
    	
    	$this->guid = $value;
    	
    	return $this;
    	
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
     * Set post menu order
     *
     * @param  int        $value
     *
     * @return WPPostData $this
     */
    public function setMenuOrder($value) {
    	
    	$this->menu_order = intval($value);
    	
    	return $this;
    	
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
     * Set comment status
     *
     * @param  string     $value
     *
     * @return WPPostData $this
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
     * @return string $ping
     */
    public function getPingStatus() {
    	
    	return $this->ping;
    	
    }
    
    /**
     * Set ping status
     *
     * @param  string     $value
     *
     * @return WPPostData $this
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
     * @return boolean $sticky
     */
    public function isSticky() {
    	
    	return $this->sticky;
    	
    }
    
    /**
     * Set sticky
     *
     * @param  boolean    $value
     *
     * @return WPPostData $this
     */
    public function setSticky($value) {
    	
    	$this->sticky = $value;
    	
    	return $this;
    	
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
     * Clean custom fields
     *
     * @return WPPostData $this
     */
    public function cleanCustomFields() {
    	
    	$this->custom = array();
    	
    	return $this;
    	
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
     * Set custom field
     *
     * @param  string     $field
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setCustomField($field, $value) {
    	
    	foreach ($this->custom as $id => $custom) {
    		
    		if ($custom['key'] == $field) {
    			
    			$this->custom[$id]['value'] = $value;
    			
    			return $this;
    			
    		}
    		
    	}
    	
    	// If the custom field requested does not exists, a new one will be created
    	array_push($this->custom, array(
    		'key'   => $field,
    		'value' => $value
    	));
    	
    	return $this;
    	
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
     * Set enclosure url
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setEnclosureURL($value) {
    	
    	$this->enclosure['url'] = $value;
    	
    	return $this;
    	
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
     * Set enclosure length
     *
     * @param  int        $value
     *
     * @return WPPostData $this
     */
    public function setEnclosureLength($value) {
    	
    	$this->enclosure['length'] = $value;
    	
    	return $this;
    	
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
     * Set enclosure type
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setEnclosureType($value) {
    	
    	$this->enclosure['type'] = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Get terms
     *
     * @return array $terms
     */
    public function getTerms() {
    	
    	return $this->terms;
    	
    }
    
    /**
     * Get categories
     *
     * @return array $categories
     */
    public function getCategories() {
    	
    	$categories = array();
    	
    	foreach ($this->terms as $term) {
    		
    		if ($term->getTaxonomy()->getName() == "category") array_push($categories, $term);
    		
    	}
    	
    	return $categories;
    	
    }
    
    /**
     * Remove category
     *
     * @param  string     $category Category name
     *
     * @return WPPostData $this
     */
    public function removeCategory($category) {
    	
    	foreach ($this->getCategories() as $c) {
    		
    		if ($c->getName() == $category) $this->removeTerm($c);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Add category
     *
     * @param  string     $category Category name
     *
     * @return WPPostData $this
     */
    public function addCategory($category) {
    	
    	if ($this->getBlog()->hasCategory($category)) {
    		
    		$term = $this->getBlog()->getCategory($category);
    		
    	} else {
    		
    		$taxonomy = $this->getBlog()->getTaxonomy("category");
    		$term = new WPTerm($taxonomy);
    		$term->setName($category)->save();
    		
    		$this->getBlog()->addCategory($term);
    	}
    	
    	return $this->addTerm($term);
    	
    }
    
    /**
     * Has category
     *
     * @param  string  $category Category name
     *
     * @return boolean $hasCategory
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
     * @return array $tags
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
     * @param  string  $tag Tag name
     *
     * @return boolean $hasTag
     */
    public function hasTag($tag) {
    	
    	foreach ($this->getTags() as $t) {
    		
    		if ($t->getName() == $tag) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Remove tag
     *
     * @param  string     $tag Tag name
     *
     * @return WPPostData $this
     */
    public function removeTag($tag) {
    	
    	foreach ($this->getTags() as $t) {
    		
    		if ($t->getName() == $tag) $this->removeTerm($t);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Add tag
     *
     * @param  string     $tag Tag name
     *
     * @return WPPostData $this
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
     * @param  WPTerm     $term Term reference
     *
     * @return WPPostData $this
     */
    public function addTerm($term) {
    	
    	if (!$this->hasTerm($term)) {
    		
    		array_push($this->terms, $term);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Remove term
     *
     * @param  WPTerm     $term Term reference
     *
     * @return WPPostData $this
     */
    public function removeTerm($term) {
    	
    	foreach ($this->terms as $id => $t) {
    		
    		if ($t->getID() == $term) unset($this->terms[$id]);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Has term
     *
     * @param  mixed   $term Term ID or WPTerm object
     *
     * @return boolean $hasTerm
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
     * Clean all terms
     *
     * @return WPPostData $this
     */
    public function cleanTerms() {
    	
    	$this->terms = array();
    	
    	return false;
    	
    }
    
    /**
     * Get comments for current post
     *
     * @return  WPCommentIterator $commentIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getComments() {
    	
    	return $this->getCommentsByStatus();
    	
    }
    
    /**
     * Get comments for current post filtered by status
     *
     * @param  string $status    Comment status (check WPBlog::getSupportedCommentStatus)
     *
     * @return WPCommentIterator $commentIterator
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
     * @return WPPostData $thumbnail
     */
    public function getThumbnail() {
    	
    	return $this->thumbnail;
    	
    }
    
    /**
     * Set post thumbnail
     *
     * @param  mixed      $thumb Media ID or WPMedia object
     *
     * @return WPPostData $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setThumbnail($thumb = null) {
    	
    	if (is_null($thumb)) {
    		
    		$this->thumbnail = null;
    	
    	} elseif (is_numeric($thumb)) {
    	
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
     * @param  string          $mime The mime-type of the media you want to fetch
     *
     * @return WPMediaIterator $mediaIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getAttachments($mime = null) {
    	
    	return $this->getBlog()->getMediaLibrary($mime, $this->getID());
    	
    }
    
}