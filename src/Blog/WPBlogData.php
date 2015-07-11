<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress blog data
 *
 * It allows to retrieve data of a wordpress blog.
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
     * Get blog URL
     *
     * @return  string  $url
     */
    public function getURL() {
    	
    	return $this->url;
    	
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
     * True if the user is admin on the blog, false otherwise
     *
     * @return  boolean  $admin
     */
    public function isAdmin() {
    	
    	return $this->admin;
    	
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
    	
    	return (string) $this->getOptionField($name, 'value');
    	
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
    	
    	return (string) $this->getOptionField($name, 'desc');
    	
    }
    
    /**
     * True if the option is read only
     *
     * @param   string  $name Option name
     *
     * @return  boolean  $readonly
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function isReadOnlyOption($name) {
    	
    	return filter_var($this->getOptionField($name, 'readonly'), FILTER_VALIDATE_BOOLEAN);
    	
    }
    
    /**
     * Get option field data
     *
     * @param   string $name Option name
     * @param   string $field Option fieldname
     *
     * @return  mixed  $value
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function getOptionField($name, $field) {
    	
    	if (empty($this->options)) $this->loadBlogOptions();
    	
    	if (!isset($this->options[$name]))
    		throw new WPException("There isn't any option called '$name'");
    	
    	return $this->options[$name][$field];
    	
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