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
class WPBlog {
	
	/**
     * Wordpress connection
     *
     * @var Object
     */
	private $wp = null;
	
	/**
     * ID of the wordpress blog
     *
     * @var int
     */
	private $id = 0;
	
	/**
     * Name of the wordpress blog
     *
     * @var string
     */
	private $name = "";
	
	/**
     * URL to access the blog
     *
     * @var string
     */
	private $url = "";
	
	/**
     * XML-RPC endpoint
     *
     * @var string
     */
	private $endpoint = "";
	
	/**
     * True if the user is admin of the blog, false otherwise
     *
     * @var boolean
     */
	private $admin = false;
	
	/**
     * List of options available for the user
     *
     * @var array
     */
	private $options = array();
	
	/**
     * List of all available taxonomies
     *
     * @var array
     */
	private $taxonomies = array();
	
	/**
     * List of all available tags
     *
     * @var array
     */
	private $tags = array();
	
	/**
     * List of all available categories
     *
     * @var array
     */
	private $categories = array();
	
	/**
     * List of all supported post formats
     *
     * @var array
     */
	private $supportedFormats = array();
	
	/**
     * List of all supported post types
     *
     * @var array
     */
	private $supportedTypes = array();
	
	/**
     * List of all supported post status
     *
     * @var array
     */
	private $supportedPostStatus = array();
	
	/**
     * List of all supported comment status
     *
     * @var array
     */
	private $supportedCommentStatus = array();
	
    /**
     * Class constructor
     *
     * @param   Object  $wp       Reference to the wordpress connection
     * @param   int     $id       ID of the blog
     * @param   string  $name     Name of the blog
     * @param   string  $url      URL of the blog
     * @param   string  $endpoint End point to the XML-RPC server
     * @param   boolean $admin    Administration privileges
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($wp, $id, $name, $url, $endpoint, $admin) {
    	
        if ( is_null($wp) || !$wp->isLogged() ) {
        	
        	throw new WPException("You must be logged to access this blog");
        	
        }
        
        $this->wp       = $wp;
        
        $this->id       = intval($id);
        
        $this->name     = $name;
        
        $this->url      = $url;
        
        $this->endpoint = $endpoint;
        
        $this->admin    = $admin;
        
        if (!$this->checkEndPoint()) {
        	
        	$this->id = -1;
        	
        }
        
    }
    
    /**
     * Get wordpress connection
     *
     * @return  int  $this->wp
     */
    public function getWordpress() {
    	
    	return $this->wp;
    	
    }
    
    /**
     * Get blog ID
     *
     * @return  int  $this->id
     */
    public function getID() {
    	
    	return $this->id;
    	
    }
    
    /**
     * Get blog name
     *
     * @return  string  $this->name
     */
    public function getName() {
    	
    	return $this->name;
    	
    }
    
    /**
     * Get blog URL
     *
     * @return  string  $this->url
     */
    public function getURL() {
    	
    	return $this->url;
    	
    }
    
    /**
     * Get blog XML-RPC endpoint
     *
     * @return  string  $this->endpoint
     */
    public function getEndPoint() {
    	
    	return $this->endpoint;
    	
    }
    
    /**
     * Get supported post formats
     *
     * @return  array  $formats
     */
    public function getSupportedFormats() {
    	
    	if (empty($this->supportedFormats)) $this->loadPostFormats();
    	
    	return $this->supportedFormats;
    	
    }
    
    /**
     * Get supported post types
     *
     * @return  array  $types
     */
    public function getSupportedTypes() {
    	
    	if (empty($this->supportedTypes)) $this->loadPostTypes();
    	
    	return $this->supportedTypes;
    	
    }
    
    /**
     * Get supported post status
     *
     * @return  array  $status
     */
    public function getSupportedPostStatus() {
    	
    	if (empty($this->supportedPostStatus)) $this->loadPostStatus();
    	
    	return $this->supportedPostStatus;
    	
    }
    
    /**
     * Get supported comment status
     *
     * @return  array  $status
     */
    public function getSupportedCommentStatus() {
    	
    	if (empty($this->supportedCommentStatus)) $this->loadCommentStatus();
    	
    	return $this->supportedCommentStatus;
    	
    }
    
    /**
     * Get available options list
     *
     * @return  array  $options
     */
    public function getAvailableOptions() {
    	
    	if (empty($this->options)) $this->loadBlogOptions();
    	
    	return array_keys($this->options);
    	
    }
    
    /**
     * Get option value
     *
     * @param   string  $name Option name
     *
     * @return  mixed  $value
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getOptionValue($name) {
    	
    	if (empty($this->options)) $this->loadBlogOptions();
    	
    	if (!isset($this->options[$name]))
    		throw new WPException("There isn't any option called '$name'");
    	
    	return $this->options[$name]['value'];
    	
    }
    
    /**
     * Get option description
     *
     * @param   string  $name Option name
     *
     * @return  string  $description
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getOptionDescription($name) {
    	
    	if (empty($this->options)) $this->loadBlogOptions();
    	
    	if (!isset($this->options[$name]))
    		throw new WPException("There isn't any option called '$name'");
    	
    	return $this->options[$name]['desc'];
    	
    }
    
    /**
     * Get option description
     *
     * @param   string  $name Option name
     *
     * @return  boolean  $readonly
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function isReadOnlyOption($name) {
    	
    	if (empty($this->options)) $this->loadBlogOptions();
    	
    	if (!isset($this->options[$name]))
    		throw new WPException("There isn't any option called '$name'");
    	
    	return $this->options[$name]['readonly'];
    	
    }
    
    /**
     * Check if the endpoint is valid
     *
     * @return  boolean  $valid
     */
    public function checkEndPoint() {
    	
    	try {
        
            $this->getWordpress()->sendMessage("wp.getUsersBlogs", array(), $this);
            
    	} catch (WPException $wpe) {
    		
    		return false;
    		
    	}
    	
    	return true;
    	
    }
    
    /**
     * Set a value for an option
     *
     * @param   string  $name  Option name
     * @param   string  $value Option value
     * @param   string  $value Option description (optional)
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setOption($name, $value, $desc = null) {
    	
    	if (is_null($this->options)) $this->loadBlogOptions();
    	
    	if (!isset($this->options[$name]))
    		throw new WPException("There isn't any option called '$name'");
    	
    	if ($this->options[$name]['readonly'])
    		throw new WPException("The option '$name' is read-only");
    		
    	$opt_info = array(
    		"value"    => $value,
    		"desc"     => (is_null($desc))?$this->options[$name]['desc']:$desc,
    		"readonly" => $this->options[$name]['readonly']
    	);
    	
    	try {
            
            $options = $this->getWordpress()->sendMessage("wp.setOptions", array(
                array(
                	$name => $opt_info
                )
            ), $this);
            
            foreach ($options as $name => $option) {
            	
            	$this->options[$name] = array(
            		"descr"    => $option['desc'],
            		"value"    => $option['value'],
            		"readonly" => filter_var($option['readonly'], FILTER_VALIDATE_BOOLEAN)
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to set value for option '$name' (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * True if the user is admin on the blog, false otherwise
     *
     * @return  boolean  $this->admin
     */
    public function isAdmin() {
    	
    	return $this->admin;
    	
    }
    
    /**
     * Get user's profile
     *
     * @return  Object  $profile
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getProfile() {
    	
    	try {
            
            $user = $this->getWordpress()->sendMessage("wp.getProfile", array(
                array('user_id')
            ), $this);
            
            return new WPProfile(
            	$this,
            	$user['user_id']
            );
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve user's profile (".$wpe->getMessage().")");
    		
    	}
    	
    }
    
    /**
     * Get user's information by id
     *
     * @param   int  $id User's ID
     *
     * @return  Object  $user
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUserByID($id) {
    	
    	try {
    		
            return new WPUser($this, $id);
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get users by role
     *
     * @param   string  $role User's role
     *
     * @return  Object  $userIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUsersByRole($role) {
    	
    	try {
    		
    		return $this->getUsers($role);
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get authors
     *
     * @return  Object  $userIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getAuthors() {
    	
    	try {
    		
    		return $this->getUsers(null, "authors");
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get user list
     *
     * @param   string  $role    User's role
     * @param   string  $who     Who is
     * @param   int     $limit   Number of users retrieved
     * @param   int     $offset  Number of users to skip
     * @param   string  $orderby Field to use for ordering
     * @param   string  $order   Type of ordering (asd or desc)
     *
     * @return  Object  $userIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUsers($role = null, $who = null, $limit = null, $offset = 0, $orderby = "username", $order = "ASC") {
    	
    	$users  = array();
    	
    	$filter = array(
        	"offset"  => $offset,
        	"orderby" => $orderby,
        	"order"   => $order
        );
        
        if (!is_null($role)) {
        	$filter["role"] = $role;
        }
        
        if (!is_null($who)) {
        	$filter["who"] = $who;
        }
        
        if (!is_null($limit)) {
        	$filter["limit"] = intval($limit);
        }
    	
    	try {
    		
            $users_list = $this->getWordpress()->sendMessage("wp.getUsers", array(
                $filter,
                array('user_id')
            ), $this);
            
            foreach ($users_list as $user) {
            	
            	array_push(
            		$users,
            		$user['user_id']
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve user's informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return new WPUserIterator($this, $users);
    	
    }
    
    /**
     * Get post's information by id
     *
     * @param   int  $id Post's ID
     *
     * @return  Object  $post
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostByID($id) {
    	
    	try {
    		
            return new WPPost($this, $id);
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get pages
     *
     * @return  Object $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPages() {
    	
    	try {
    		
    		return $this->getPosts("page");
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get latest posts
     * 
     * @param   int    $count  Number of posts retrieved
     *
     * @return  Object $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getLatestPosts($count = 10) {
    	
    	try {
    		
    		return $this->getPosts("post", "publish", $count);
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get post list
     *
     * @param   string  $type    Type of posts
     * @param   string  $status  Status of posts
     * @param   int     $number  Number of posts retrieved
     * @param   int     $offset  Number of posts to skip
     * @param   string  $orderby Field to use for ordering
     * @param   string  $order   Type of ordering (asd or desc)
     *
     * @return  Object $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPosts($type = null, $status = null, $number = null, $offset = 0, $orderby = "post_date", $order = "DESC") {
    	
    	$posts  = array();
    	
    	$filter = array(
        	"offset"  => $offset,
        	"orderby" => $orderby,
        	"order"   => $order
        );
        
        if (!is_null($type)) {
        	$filter["post_type"] = $type;
        }
        
        if (!is_null($status)) {
        	$filter["post_status"] = $status;
        }
        
        if (!is_null($number)) {
        	$filter["number"] = intval($number);
        }
    	
    	try {
    		
            $post_list = $this->getWordpress()->sendMessage("wp.getPosts", array(
                $filter,
                array('post_id')
            ), $this);
            
            foreach ($post_list as $post) {
            	
            	array_push(
            		$posts,
		            $post['post_id']
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return new WPPostIterator($this, $posts);
    	
    }
    
    /**
     * Get post list by category
     *
     * @param   string $category Category name or description
     * @param   string $number   Number of posts (optional)
     *
     * @return  Object $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostsByCategory($category, $number = null) {
    	
    	try {
    	
    		return $this->getPostsByTerm("category", $category, $number);
    	
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get post list by tag
     *
     * @param   string $tag      Tag name or description
     * @param   string $number   Number of posts (optional)
     *
     * @return  Object $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostsByTag($tag, $number = null) {
    	
    	try {
    	
    		return $this->getPostsByTerm("post_tag", $tag, $number);
    	
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get post list by term
     *
     * @param   string $taxonomy Taxonomy name
     * @param   string $value    Term name or description
     * @param   int    $number   Number of posts to fetch
     *
     * @return  Object $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostsByTerm($taxonomy, $value, $number = null) {
    	
    	$posts  = array();
    	
    	$filter = array(
        	"offset"      => 0,
        	"orderby"     => "post_date",
        	"order"       => "DESC",
        	"post_type"   => "post",
        	"post_status" => "publish",
        );
        
        if (!is_null($number)) {
        	$filter["number"] = intval($number);
        }
    	
    	try {
    		
            $post_list = $this->getWordpress()->sendMessage("wp.getPosts", array(
                $filter,
                array('post_id', 'terms')
            ), $this);
            
            foreach ($post_list as $post) {
            	
            	foreach ($post['terms'] as $term) {
	            	
	            	if ($term['taxonomy'] == $taxonomy) {
	            		
	            		if ($term['name'] == $value || $term['description'] == $value) {
	            			
			            	array_push(
			            		$posts,
			            		$post['post_id']
			            	);
			            	
			            	break;
	            			
	            		}
	            	
	            	}
	            	
            	}
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return new WPPostIterator($this, $posts);
    	
    }
    
    /**
     * Get taxonomies
     *
     * @return  array  $taxonomies
     */
    public function getTaxonomies() {
    	
    	if (empty($this->taxonomies)) $this->loadTaxonomies();
    	
    	return $this->taxonomies;
    	
    }
    
    /**
     * Get taxonomy by name
     *
     * @param   string $taxonomy taxonomy name
     *
     * @return  Object $taxonomy
     */
    public function getTaxonomy($taxonomy) {
    	
    	foreach ($this->getTaxonomies() as $t) {
    		
    		if ($t->getName() == $taxonomy) return $t;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Has taxonomy
     *
     * @param   string  $taxonomy Taxonomy name
     *
     * @return  boolean $hasTaxonomy
     */
    public function hasTaxonomy($taxonomy) {
    	
    	foreach ($this->getTaxonomies() as $t) {
    		
    		if ($t->getName() == $taxonomy) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Load taxonomy list
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function loadTaxonomies() {
    	
    	try {
    		
            $tax_list = $this->getWordpress()->sendMessage("wp.getTaxonomies", array(), $this);
            
            foreach ($tax_list as $taxonomy) {
            	
            	$tax = new WPTaxonomy($this);
            	
            	$tax->loadData($taxonomy);
            	
            	array_push(
            		$this->taxonomies,
            		$tax
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve taxonomy informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get tags
     *
     * @return  array  $tags
     */
    public function getTags() {
    	
    	if (empty($this->tags)) $this->loadBlogTerms();
    	
    	return $this->tags;
    	
    }
    
    /**
     * Get tag term by name
     *
     * @param   string $tag Tag name
     *
     * @return  Object $tag
     */
    public function getTag($tag) {
    	
    	foreach ($this->getTags() as $t) {
    		
    		if ($t->getName() == $tag) return $t;
    		
    	}
    	
    	return null;
    	
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
     * Has tag
     *
     * @param   Object $tag WPTerm object
     *
     * @return  Object $this
     */
    public function addTag($tag) {
    	
    	if (!$this->hasTag($tag->getName())) {
    		
    		array_push($this->tags, $tag);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get categories
     *
     * @return  array  $categories
     */
    public function getCategories() {
    	
    	if (empty($this->categories)) $this->loadBlogTerms();
    	
    	return $this->categories;
    	
    }
    
    /**
     * Get category by name
     *
     * @param   string $category Tag name
     *
     * @return  Object $category
     */
    public function getCategory($category) {
    	
    	foreach ($this->getCategories() as $c) {
    		
    		if ($c->getName() == $category) return $c;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Has category
     *
     * @param   string  $category Category name
     *
     * @return  boolean $hasCategory
     */
    public function hasCategory($category) {
    	
    	foreach ($this->getCategories() as $c) {
    		
    		if ($c->getName() == $category) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Add category
     *
     * @param   Object $category WPTerm object
     *
     * @return  Object $this
     */
    public function addCategory($category) {
    	
    	if (!$this->hasCategory($category->getName())) {
    		
    		array_push($this->categories, $category);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get media library
     * 
     * @param   string $mime The mime-type of the media you want to fetch
     *
     * @return  Object  $mediaIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getMediaLibrary($mime = null) {
    	
    	$mediaIterator = null;
    	
    	try {
    		
            $mediaIterator = new WPMediaIterator($this, 0, $mime);
            
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $mediaIterator;
    	
    }
	
    /**
     * Load supported formats
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function loadPostFormats() {
    	
    	try {
            
            $this->supportedFormats = $this->getWordpress()->sendMessage("wp.getPostFormats", array(), $this);
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post formats (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load supported post types
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function loadPostTypes() {
    	
    	try {
    		
            $types = $this->getWordpress()->sendMessage("wp.getPostTypes", array(), $this);
            
            foreach ($types as $name => $type) {
            
            	$this->supportedTypes[$name] = $type['label'];
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post types (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load supported post status
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function loadPostStatus() {
    	
    	try {
            
            $this->supportedPostStatus = $this->getWordpress()->sendMessage("wp.getPostStatusList", array(), $this);
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post status (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load supported comment status
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function loadCommentStatus() {
    	
    	try {
            
            $status = $this->getWordpress()->sendMessage("wp.getCommentStatusList", array(), $this);
            
            foreach ($status as $s) {
            
            	$this->supportedCommentStatus[$s['key']] = $s['value'];
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve comment status (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load blog options
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function loadBlogOptions() {
    	
    	try {
            
            $options = $this->getWordpress()->sendMessage("wp.getOptions", array(), $this);
            
            foreach ($options as $name => $option) {
            	
            	$this->options[$name] = array(
            		"desc"     => $option['desc'],
            		"value"    => $option['value'],
            		"readonly" => filter_var($option['readonly'], FILTER_VALIDATE_BOOLEAN)
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve blog options (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load blog terms
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function loadBlogTerms() {
    	
    	try {
    	
    		if (empty($this->taxonomies)) $this->loadTaxonomies();
			
			$this->tags = $this->getTaxonomy("post_tag")->getTerms();
			
			$this->categories = $this->getTaxonomy("category")->getTerms();
            
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this;
        
    }
    
}