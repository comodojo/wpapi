<?php namespace Comodojo\WP;
use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress user
 *
 * It allows to retrive user's informations.
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
class WPUser {
	
	/**
     * Wordpress blog
     *
     * @var Object
     */
	private $blog = null;
	
	/**
     * User ID
     *
     * @var int
     */
	private $id = -1;
	
	/**
     * Username
     *
     * @var string
     */
	private $username = "";
	
	/**
     * First name
     *
     * @var string
     */
	private $firstname = "";
	
	/**
     * Last name
     *
     * @var string
     */
	private $lastname = "";
	
	/**
     * User's biography
     *
     * @var string
     */
	private $bio = "";
	
	/**
     * Email address
     *
     * @var string
     */
	private $email = "";
	
	/**
     * Nickname
     *
     * @var string
     */
	private $nickname = "";
	
	/**
     * Nicename
     *
     * @var string
     */
	private $nicename = "";
	
	/**
     * URL to user personal website
     *
     * @var string
     */
	private $url = "";
	
	/**
     * Display name
     *
     * @var string
     */
	private $displayname = "";
	
	/**
     * Timestamp of registration
     *
     * @var int
     */
	private $registration = "";
	
	/**
     * Array of roles
     *
     * @var string
     */
	private $roles = array();
	
    /**
     * Class constructor
     *
     * @param   Object  $blog     Reference to the wordpress blog
     * @param   int     $id       User ID (optional)
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $id=-1) {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access user informations");
        	
        }
        
        $this->blog         = $blog;
        
        $this->id           = intval($id);
        
        if ($id > -1) {
        	
        	try {
        		
        		$this->loadFromID($id);
        		
        	} catch (WPException $wpe) {
        		
        		throw $wpe;
        		
        	}
        	
        }
        
    }
	
    /**
     * Load user for ID
     *
     * @param   int     $id       User ID
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromID($id) {
    	
    	try {
    		
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.getUser", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $id
            ));
            
            $user = $rpc_client->send();
        
        	$this->id           = intval($user['user_id']);
        
	        $this->username     = $user['username'];
	        
	        $this->firstname    = $user['first_name'];
	        
	        $this->lastname     = $user['last_name'];
	        
	        $this->bio          = $user['bio'];
	        
	        $this->email        = $user['email'];
	        
	        $this->nickname     = $user['nickname'];
	        
	        $this->nicename     = $user['nicename'];
	        
	        $this->url          = $user['url'];
	        
	        $this->displayname  = $user['display_name'];
	        
	        $this->registration = strtotime($user['registered']);
	        
	        $this->roles        = $user['roles'];
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive user's informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive user's informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive user's informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive user's informations - Generic Exception (".$e->getMessage().")");
    		
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
     * Get user's blog
     *
     * @return  Object  $this->blog
     */
    public function getBlog() {
    	
    	return $this->blog;
    	
    }
    
    /**
     * Get user id
     *
     * @return  string  $this->id
     */
    public function getID() {
    	
    	return $this->id;
    	
    }
    
    /**
     * Get username
     *
     * @return  string  $this->username
     */
    public function getUsername() {
    	
    	return $this->username;
    	
    }
    
    /**
     * Get first name
     *
     * @return  string  $this->firstname
     */
    public function getFirstname() {
    	
    	return $this->firstname;
    	
    }
    
    /**
     * Get last name
     *
     * @return  string  $this->lastname
     */
    public function getLastname() {
    	
    	return $this->lastname;
    	
    }
    
    /**
     * Get user biography
     *
     * @return  string  $this->bio
     */
    public function getBiography() {
    	
    	return $this->bio;
    	
    }
    
    /**
     * Get email address
     *
     * @return  string  $this->email
     */
    public function getEmail() {
    	
    	return $this->email;
    	
    }
    
    /**
     * Get nickname
     *
     * @return  string  $this->nickname
     */
    public function getNickname() {
    	
    	return $this->nickname;
    	
    }
    
    /**
     * Get nicename
     *
     * @return  string  $this->nicename
     */
    public function getNicename() {
    	
    	return $this->nicename;
    	
    }
    
    /**
     * Get URL to user personal website
     *
     * @return  string  $this->url
     */
    public function getURL() {
    	
    	return $this->url;
    	
    }
    
    /**
     * Get display name
     *
     * @return  string  $this->displayname
     */
    public function getDisplayName() {
    	
    	return $this->displayname;
    	
    }
    
    /**
     * Get registration date formatted
     *
     * @return  mixed  $this->registration
     */
    public function getRegistration($format = null) {
    	
    	if (is_null($format))
    		return $this->registration;
    	else
    		return date($format, $this->registration);
    	
    }
    
    /**
     * Get user roles
     *
     * @return  array  $this->roles
     */
    public function getRoles() {
    	
    	return $this->roles;
    	
    }
    
    
}