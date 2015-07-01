<?php namespace Comodojo\WP;
use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress blog
 *
 * It allows to retrive and edit posts from a wordpress blog.
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
     * Class constructor
     *
     * @param   Object  $wp       Reference to the wordpress connection
     * @param   int     $id       ID of the blog
     * @param   string  $name     Name of the blog
     * @param   string  $url      URL of the blog
     * @param   string  $endpoint End point to the XML-RPC server
     * @param   boolean $admin    Administration privileges
     *
     * @return  Object  $this
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
    	
    	try {
    		
            $this->loadBlogOptions()->loadPostFormats()->loadPostTypes()->loadPostStatus();
            
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
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
    	
    	return $this->supportedFormats;
    	
    }
    
    /**
     * Get supported post types
     *
     * @return  array  $types
     */
    public function getSupportedTypes() {
    	
    	return $this->supportedTypes;
    	
    }
    
    /**
     * Get supported post status
     *
     * @return  array  $status
     */
    public function getSupportedStatus() {
    	
    	return $this->supportedStatus;
    	
    }
    
    /**
     * Get available options list
     *
     * @return  array  $options
     */
    public function getAvailableOptions() {
    	
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
            
            $rpc_client->addRequest("wp.setOptions", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                array(
                	$name => $opt_info
                )
            ));
            
            $options = $rpc_client->send();
            
            foreach ($options as $name => $option) {
            	
            	$this->options[$name] = array(
            		"descr"    => $option['desc'],
            		"value"    => $option['value'],
            		"readonly" => filter_var($option['readonly'], FILTER_VALIDATE_BOOLEAN)
            	);
            	
            }
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to set value for option '$name' - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to set value for option '$name' - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to set value for option '$name' - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to set value for option '$name' - Generic Exception (".$e->getMessage().")");
    		
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
            
            $rpc_client->addRequest("wp.getProfile", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                array('user_id')
            ));
            
            $user = $rpc_client->send();
            
            return new WPProfile(
            	$this,
            	$user['user_id']
            );
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive user's profile - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive user's profile - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive user's profile - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive user's profile - Generic Exception (".$e->getMessage().")");
    		
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
     * @return  array  $users
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
     * @return  array  $users
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
     * @param   int     $limit   Number of users retrived
     * @param   int     $offset  Number of users to skip
     * @param   string  $orderby Field to use for ordering
     * @param   string  $order   Type of ordering (asd or desc)
     *
     * @return  array  $users
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUsers($role = null, $who = null, $limit = null, $offset = 0, $orderby = "username", $order = "ASC") {
    	
    	$users  = array();
    	
    	$filter = array(
        	"offset" => $offset,
        	"orderby" => $orderby,
        	"order" => $order
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
            
            $rpc_client->addRequest("wp.getUsers", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $filter,
                array('user_id')
            ));
            
            $users_list = $rpc_client->send();
            
            foreach ($users_list as $user) {
            	
            	array_push(
            		$users,
            		new WPUser(
		            	$this,
		            	$user['user_id']
		            )
            	);
            	
            }
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive user's informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive user's informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive user's informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive user's informations - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $users;
    	
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
     * @return  array  $posts
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
     * @param   int    $count  Number of posts retrived
     *
     * @return  array  $posts
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
     * @param   int     $number  Number of posts retrived
     * @param   int     $offset  Number of posts to skip
     * @param   string  $orderby Field to use for ordering
     * @param   string  $order   Type of ordering (asd or desc)
     *
     * @return  array  $posts
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPosts($type = null, $status = null, $number = null, $offset = 0, $orderby = "post_date", $order = "DESC") {
    	
    	$posts  = array();
    	
    	$filter = array(
        	"offset" => $offset,
        	"orderby" => $orderby,
        	"order" => $order
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
            
            $rpc_client->addRequest("wp.getPosts", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $filter,
                array('post_id')
            ));
            
            $post_list = $rpc_client->send();
            
            foreach ($post_list as $post) {
            	
            	array_push(
            		$posts,
            		new WPPost(
		            	$this,
		            	$post['post_id']
		            )
            	);
            	
            }
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive user's informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive user's informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive user's informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive user's informations - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $posts;
    	
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
    		
            $rpc_client->addRequest("wp.getPostFormats", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword()
            ));
            
            $formats = $rpc_client->send();
            
            $this->supportedFormats = $formats;
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive post formats - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive post formats - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive post formats - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive post formats - Generic Exception (".$e->getMessage().")");
    		
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
    		
            $rpc_client->addRequest("wp.getPostTypes", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword()
            ));
            
            $types = $rpc_client->send();
            
            foreach ($types as $name => $type) {
            
            	$this->supportedTypes[$name] = $type['label'];
            	
            }
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive post types - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive post types - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive post types - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive post types - Generic Exception (".$e->getMessage().")");
    		
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
    		
            $rpc_client->addRequest("wp.getPostStatusList", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword()
            ));
            
            $this->supportedStatus = $rpc_client->send();
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive post status - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive post status - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive post status - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive post status - Generic Exception (".$e->getMessage().")");
    		
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
            
            $rpc_client->addRequest("wp.getOptions", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword()
            ));
            
            $options = $rpc_client->send();
            
            foreach ($options as $name => $option) {
            	
            	$this->options[$name] = array(
            		"descr"    => $option['desc'],
            		"value"    => $option['value'],
            		"readonly" => filter_var($option['readonly'], FILTER_VALIDATE_BOOLEAN)
            	);
            	
            }
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive blog options - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive blog options - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive blog options - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive blog options - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
    
    
}