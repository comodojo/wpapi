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
	private $wp       = null;
	
	/**
     * ID of the wordpress blog
     *
     * @var int
     */
	private $id       = 0;
	
	/**
     * Name of the wordpress blog
     *
     * @var string
     */
	private $name     = "";
	
	/**
     * URL to access the blog
     *
     * @var string
     */
	private $url      = "";
	
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
	private $admin    = false;
	
	/**
     * List of options available for the user
     *
     * @var array
     */
	private $options  = array();
	
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
                $this->getWordpress()->getPassword()
            ));
            
            $user = $rpc_client->send();
            
            return new WPProfile(
            	$this,
            	$user['user_id'],
            	$user['username'],
            	$user['first_name'],
            	$user['last_name'],
            	$user['bio'],
            	$user['email'],
            	$user['nickname'],
            	$user['nicename'],
            	$user['url'],
            	$user['display_name'],
            	$user['registered'],
            	$user['roles']
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
    		
            $rpc_client = new RpcClient($this->getEndPoint());
            
            $rpc_client->addRequest("wp.getProfile", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $id
            ));
            
            $user = $rpc_client->send();
            
            return new WPUser(
            	$this,
            	$user['user_id'],
            	$user['username'],
            	$user['first_name'],
            	$user['last_name'],
            	$user['bio'],
            	$user['email'],
            	$user['nickname'],
            	$user['nicename'],
            	$user['url'],
            	$user['display_name'],
            	$user['registered'],
            	$user['roles']
            );
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive user's informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive user's informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive user's informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive user's informations - Generic Exception (".$e->getMessage().")");
    		
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
     * Get users list
     *
     * @param   int  $id User's ID
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
            
            $rpc_client->addRequest("wp.getProfile", array( 
                $this->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $filter
            ));
            
            $users_list = $rpc_client->send();
            
            foreach ($users_list as $user) {
            	
            	array_push(
            		$users,
            		new WPUser(
		            	$this,
		            	$user['user_id'],
		            	$user['username'],
		            	$user['first_name'],
		            	$user['last_name'],
		            	$user['bio'],
		            	$user['email'],
		            	$user['nickname'],
		            	$user['nicename'],
		            	$user['url'],
		            	$user['display_name'],
		            	$user['registered'],
		            	$user['roles']
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
    
    
}