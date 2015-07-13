<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress user
 *
 * It allows to get data of a blog user.
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
abstract class WPUserData extends WPBlogObject {
	
	/**
     * Username
     *
     * @var string
     */
	protected $username = "";
	
	/**
     * First name
     *
     * @var string
     */
	protected $firstname = "";
	
	/**
     * Last name
     *
     * @var string
     */
	protected $lastname = "";
	
	/**
     * User's biography
     *
     * @var string
     */
	protected $bio = "";
	
	/**
     * Email address
     *
     * @var string
     */
	protected $email = "";
	
	/**
     * Nickname
     *
     * @var string
     */
	protected $nickname = "";
	
	/**
     * Nicename
     *
     * @var string
     */
	protected $nicename = "";
	
	/**
     * URL to user personal website
     *
     * @var string
     */
	protected $url = "";
	
	/**
     * Display name
     *
     * @var string
     */
	protected $displayname = "";
	
	/**
     * Timestamp of registration
     *
     * @var int
     */
	protected $registration = "";
	
	/**
     * Array of roles
     *
     * @var array
     */
	protected $roles = array();
    
    /**
     * Get username
     *
     * @return  string  $username
     */
    public function getUsername() {
    	
    	return $this->username;
    	
    }
    
    /**
     * Set username
     *
     * @param string      $username
     *
     * @return WPUserData $this
     */
    protected function setUsername($username) {
    	
    	$this->username = $username;
    	
    	return $this;
    	
    }
    
    /**
     * Get first name
     *
     * @return  string  $firstname
     */
    public function getFirstname() {
    	
    	return $this->firstname;
    	
    }
    
    /**
     * Set first name
     *
     * @param string      $firstname
     *
     * @return WPUserData $this
     */
    protected function setFirstname($firstname) {
    	
    	$this->firstname = $firstname;
    	
    	return $this;
    	
    }
    
    /**
     * Get last name
     *
     * @return  string  $lastname
     */
    public function getLastname() {
    	
    	return $this->lastname;
    	
    }
    
    /**
     * Set last name
     *
     * @param string      $lastname
     *
     * @return WPUserData $this
     */
    protected function setLastname($lastname) {
    	
    	$this->lastname = $lastname;
    	
    	return $this;
    	
    }
    
    /**
     * Get biography
     *
     * @return  string  $bio
     */
    public function getBiography() {
    	
    	return $this->bio;
    	
    }
    
    /**
     * Set biography
     *
     * @param string      $biography
     *
     * @return WPUserData $this
     */
    protected function setBiography($biography) {
    	
    	$this->bio = $biography;
    	
    	return $this;
    	
    }
    
    /**
     * Get email address
     *
     * @return  string  $email
     */
    public function getEmail() {
    	
    	return $this->email;
    	
    }
    
    /**
     * Set email address
     *
     * @param string      $email
     *
     * @return WPUserData $this
     */
    protected function setEmail($email) {
    	
    	$this->email = $email;
    	
    	return $this;
    	
    }
    
    /**
     * Get nickname
     *
     * @return  string  $nickname
     */
    public function getNickname() {
    	
    	return $this->nickname;
    	
    }
    
    /**
     * Set nickname
     *
     * @param string      $nickname
     *
     * @return WPUserData $this
     */
    protected function setNickname($nickname) {
    	
    	$this->nickname = $nickname;
    	
    	return $this;
    	
    }
    
    /**
     * Get nicename
     *
     * @return  string  $nicename
     */
    public function getNicename() {
    	
    	return $this->nicename;
    	
    }
    
    /**
     * Set nicename
     *
     * @param string      $nicename
     *
     * @return WPUserData $this
     */
    protected function setNicename($nicename) {
    	
    	$this->nicename = $nicename;
    	
    	return $this;
    	
    }
    
    /**
     * Get URL to user personal website
     *
     * @return  string  $url
     */
    public function getURL() {
    	
    	return $this->url;
    	
    }
    
    /**
     * Set url to user personal website
     *
     * @param string      $url
     *
     * @return WPUserData $this
     */
    protected function setURL($url) {
    	
    	$this->url = $url;
    	
    	return $this;
    	
    }
    
    /**
     * Get display name
     *
     * @return  string  $displayname
     */
    public function getDisplayname() {
    	
    	return $this->displayname;
    	
    }
    
    /**
     * Set display name
     *
     * @param string      $displayname
     *
     * @return WPUserData $this
     */
    protected function setDisplayname($displayname) {
    	
    	$this->displayname = $displayname;
    	
    	return $this;
    	
    }
    
    /**
     * Get registration date formatted
     *
     * @return  mixed  $registration
     */
    public function getRegistration($format = null) {
    	
    	return $this->getFormattedDate($this->registration, $format);
    	
    }
    
    /**
     * Set registration date
     *
     * @param int      $registration
     *
     * @return WPUserData $this
     */
    protected function setRegistration($registration) {
    	
    	if (is_numeric($registration))
    		$this->registration = intval($registration);
    	else
    		$this->registration = strtotime($registration);
    	
    	return $this;
    	
    }
    
    /**
     * Get user roles
     *
     * @return  array  $roles
     */
    public function getRoles() {
    	
    	return $this->roles;
    	
    }
    
    /**
     * User has a specified role
     *
     * @param  string  $role Role name
     *
     * @return  boolean  $hasRole
     */
    public function hasRole($role) {
    	
    	foreach ($this->getRoles() as $r) {
    		
    		if (strtoupper($r) == strtoupper($role)) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Set user roles
     *
     * @param array      $roles
     *
     * @return WPUserData $this
     */
    protected function setRoles($roles = array()) {
    	
    	$this->roles = $roles;
    	
    	return $this;
    	
    }
    
    
}