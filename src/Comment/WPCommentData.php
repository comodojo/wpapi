<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress comment data object
 *
 * It allows to get and set data about a comment to a wordpress post.
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
abstract class WPCommentData extends WPPostObject {
	
	/**
     * Parent comment ID
     *
     * @var int
     */
	protected $parent = 0;
	
	/**
     * User ID
     *
     * @var int
     */
	protected $user = 0;
	
	/**
     * Comment's creation date in Unix timestamp'
     *
     * @var int
     */
	protected $date = 0;
	
	/**
     * Comment status
     *
     * @var string
     */
	protected $status = "";
	protected $supportedCommentStatus = array();
	
	/**
     * Comment content
     *
     * @var string
     */
	protected $content = "";
	
	/**
     * Comment link
     *
     * @var string
     */
	protected $link = "";
	
	/**
     * Comment type
     *
     * @var string
     */
	protected $type = "";
	
	/**
     * Comment author
     *
     * @var string
     */
	protected $author = "";
	
	/**
     * Comment author url
     *
     * @var string
     */
	protected $author_url = "";
	
	/**
     * Comment author email
     *
     * @var string
     */
	protected $author_email = "";
	
	/**
     * Comment author IP
     *
     * @var string
     */
	protected $author_ip = "";
    
    /**
     * Get user
     *
     * @return WPUser $user
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUser() {
    	
    	$parent = null;
    	
    	if (!is_null($this->user) && $this->user > 0) {
    		
    		$parent = new WPUser($this->getBlog(), $this->user);
    		
    	}
    	
    	return $parent;
    	
    }
    
    /**
     * Get parent
     *
     * @return WPComment $parent
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getParent() {
    	
    	$parent = null;
    	
    	if (!is_null($this->parent) && $this->parent > 0) {
    		
    		$parent = new WPComment($this->getPost(), $this->parent);
    		
    	}
    	
    	return $parent;
    	
    }
    
    /**
     * Set parent
     *
     * @param  mixed $parent Comment parent (it accepts a WPComment object or a numeric id)
     *
     * @return WPCommentData $this
     */
    public function setParent($parent) {
    	
    	if (is_numeric($parent)) {
    		
    		$this->parent = intval($parent);
    		
    	} else {
    		
    		$this->parent = $parent->getID();
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get link
     *
     * @return  string  $link
     */
    public function getLink() {
    	
    	return $this->link;
    	
    }
    
    /**
     * Get author
     *
     * @return  string  $author
     */
    public function getAuthor() {
    	
    	return $this->author;
    	
    }
    
    /**
     * Get author url
     *
     * @return  string  $author_url
     */
    public function getAuthorURL() {
    	
    	return $this->author_url;
    	
    }
    
    /**
     * Get author email
     *
     * @return  string  $author_email
     */
    public function getAuthorEmail() {
    	
    	return $this->author_email;
    	
    }
    
    /**
     * Get author ip
     *
     * @return  string  $author_ip
     */
    public function getAuthorIP() {
    	
    	return $this->author_ip;
    	
    }
    
    /**
     * Get creation date
     *
     * @param   string $format Date format
     *
     * @return  mixed  $date
     */
    public function getDate($format = null) {
    	
    	return $this->getFormattedDate($this->date, $format);
    	
    }
    
    /**
     * Get status
     *
     * @return  string  $status
     */
    public function getStatus() {
    	
    	return $this->status;
    	
    }
    
    /**
     * Set status
     *
     * @param  string $status
     *
     * @return WPCommentData $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setStatus($status) {
            
        if (empty($this->supportedCommentStatus)) 
        	$this->supportedCommentStatus = $this->getBlog()->getSupportedCommentStatus();
    	
    	return $this->setCheckedValue($this->supportedCommentStatus, $status, $this->status);
    	
    }
    
    /**
     * Get comment content
     *
     * @return  string  $content
     */
    public function getContent() {
    	
    	return $this->content;
    	
    }
    
    /**
     * Set comment content
     *
     * @param  string $content
     *
     * @return WPCommentData $this
     */
    public function setContent($content) {
    	
    	$this->content = $content;
    	
    	return $this;
    	
    }
    
    /**
     * Get comment type
     *
     * @return  string  $type
     */
    public function getType() {
    	
    	return $this->type;
    	
    }
    
}