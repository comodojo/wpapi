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
     * @param   string  $url  URL of the Wordpress installation
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $id, $username, $firstname, $lastname, $bio, $email, $nickname, $nicename, $url, $displayname, $registration, $roles) {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access user informations");
        	
        }
        
        $this->blog         = $blog;
        
        $this->id           = intval($id);
        
        $this->username     = $username;
        
        $this->firstname    = $firstname;
        
        $this->lastname     = $lastname;
        
        $this->bio          = $bio;
        
        $this->email        = $email;
        
        $this->nickname     = $nickname;
        
        $this->nicename     = $nicename;
        
        $this->url          = $url;
        
        $this->displayname  = $displayname;
        
        $this->registration = strtotime($registration);
        
        $this->roles        = $roles;
        
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
    public function getDisplayname() {
    	
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