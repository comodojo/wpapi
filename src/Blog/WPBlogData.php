<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress blog data
 *
 * It allows to retrieve set and get data of a wordpress blog.
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
abstract class WPBlogData extends WPObject {
	
	/**
     * Name of the wordpress blog
     *
     * @var string
     */
	protected $name = "";
	
	/**
     * URL to access the blog
     *
     * @var string
     */
	protected $url = "";
	
	/**
     * XML-RPC endpoint
     *
     * @var string
     */
	protected $endpoint = "";
	
	/**
     * User profile
     *
     * @var WPProfile
     */
	protected $profile = null;
	
	/**
     * True if the user is admin of the blog, false otherwise
     *
     * @var boolean
     */
	protected $admin = false;
	
	/**
     * List of options available for the user
     *
     * @var array
     */
	protected $options = array();
	
	/**
     * List of all available taxonomies
     *
     * @var array
     */
	protected $taxonomies = array();
	
	/**
     * List of all available tags
     *
     * @var array
     */
	protected $tags = array();
	
	/**
     * List of all available categories
     *
     * @var array
     */
	protected $categories = array();
	
	/**
     * List of all supported post formats
     *
     * @var array
     */
	protected $supportedFormats = array();
	
	/**
     * List of all supported post types
     *
     * @var array
     */
	protected $supportedTypes = array();
	
	/**
     * List of all supported post status
     *
     * @var array
     */
	protected $supportedPostStatus = array();
	
	/**
     * List of all supported comment status
     *
     * @var array
     */
	protected $supportedCommentStatus = array();
    
    /**
     * Get blog name
     *
     * @return  string  $name
     */
    public function getName() {
    	
    	return $this->name;
    	
    }
    
    /**
     * Set blog name
     *
     * @param string      $name
     *
     * @return WPBlogData $this
     */
    protected function setName($name) {
    	
    	$this->name = $name;
    	
    	return $this;
    	
    }
    
    /**
     * Get blog URL
     *
     * @return  string  $url
     */
    public function getURL() {
    	
    	return $this->url;
    	
    }
    
    /**
     * Set blog URL
     *
     * @param string      $url
     *
     * @return WPBlogData $this
     */
    protected function setURL($url) {
    	
    	$this->url = $url;
    	
    	return $this;
    	
    }
    
    /**
     * Get blog XML-RPC endpoint
     *
     * @return  string  $endpoint
     */
    public function getEndPoint() {
    	
    	return $this->endpoint;
    	
    }
    
    /**
     * Set blog XML-RPC endpoint
     *
     * @param string      $endpoint
     *
     * @return WPBlogData $this
     */
    protected function setEndPoint($endpoint) {
    	
    	$this->endpoint = $endpoint;
    	
    	return $this;
    	
    }
    
    /**
     * True if the user is admin on the blog, false otherwise
     *
     * @return  boolean  $admin
     */
    public function isAdmin() {
    	
    	return $this->admin;
    	
    }
    
    /**
     * True if the user is admin on the blog, false otherwise
     *
     * @param boolean     $admin
     *
     * @return WPBlogData $this
     */
    protected function setAdmin($admin) {
    	
    	$this->admin = $admin;
    	
    	return $this;
    	
    }
    
    /**
     * Get supported post formats
     *
     * @return  array  $formats
     */
    public function getSupportedFormats() {
    	
    	if (empty($this->supportedFormats)) $this->loadPostFormats();
    	
    	return array_keys($this->supportedFormats);
    	
    }
    
    /**
     * Get supported post types
     *
     * @return  array  $types
     */
    public function getSupportedTypes() {
    	
    	if (empty($this->supportedTypes)) $this->loadPostTypes();
    	
    	return array_keys($this->supportedTypes);
    	
    }
    
    /**
     * Get supported post status
     *
     * @return  array  $status
     */
    public function getSupportedPostStatus() {
    	
    	if (empty($this->supportedPostStatus)) $this->loadPostStatus();
    	
    	return array_keys($this->supportedPostStatus);
    	
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
    	
    	try {
            
            $options = $this->getWordpress()->sendMessage("wp.setOptions", array(
                array(
                	$name => $value
                )
            ), $this);
            
            foreach ($options as $name => $option) {
            	
            	$this->options[$name] = array(
            		"desc"     => $option['desc'],
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
     * Load taxonomy list
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract protected function loadTaxonomies();
	
    /**
     * Load supported formats
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract protected function loadPostFormats();
	
    /**
     * Load supported post types
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract protected function loadPostTypes();
	
    /**
     * Load supported post status
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract protected function loadPostStatus();
	
    /**
     * Load supported comment status
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract protected function loadCommentStatus();
    
    /**
     * Load blog options
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract protected function loadBlogOptions();
	
    /**
     * Load blog terms
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract protected function loadBlogTerms();
    
}