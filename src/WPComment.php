<?php namespace Comodojo\WP;
use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress comment
 *
 * It allows to retrive and edit a comment to a wordpress post.
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
class WPComment {
	
	/**
     * Post reference
     *
     * @var Object
     */
	private $post = null;
	
	/**
     * Comment ID
     *
     * @var int
     */
	private $id = -1;
	
	/**
     * Parent comment ID
     *
     * @var int
     */
	private $parent = -1;
	
	/**
     * User ID
     *
     * @var int
     */
	private $user = -1;
	
	/**
     * Comment's creation date in Unix timestamp'
     *
     * @var int
     */
	private $date = 0;
	
	/**
     * Comment status
     *
     * @var string
     */
	private $status = "";
	private $supportedStatus = array('hold', 'approve', 'spam');
	
	/**
     * Comment content
     *
     * @var string
     */
	private $content = "";
	
	/**
     * Comment link
     *
     * @var string
     */
	private $link = "";
	
	/**
     * Comment type
     *
     * @var string
     */
	private $type = "";
	
	/**
     * Comment author
     *
     * @var string
     */
	private $author = "";
	
	/**
     * Comment author url
     *
     * @var string
     */
	private $author_url = "";
	
	/**
     * Comment author email
     *
     * @var string
     */
	private $author_email = "";
	
	/**
     * Comment author IP
     *
     * @var string
     */
	private $author_ip = "";
	
    /**
     * Class constructor
     *
     * @param   Object  $post Reference to a post object
     * @param   int     $id   Comment ID (optional)
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($post, $id=-1) {
    	
        if ( is_null($post) || is_null($post->getWordpress()) || !$post->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access comment informations");
        	
        }
        
        $this->post = $post;
        
        $this->id   = intval($id);
            
        $this->supportedStatus = array_keys($this->getBlog()->getSupportedCommentStatus());
        
        if ($id > -1) {
        	
        	try {
        		
        		$this->loadFromID($id);
        		
        	} catch (WPException $wpe) {
        		
        		throw $wpe;
        		
        	}
        	
        }
        
    }
	
    /**
     * Load comment from ID
     *
     * @param   int    $id Comment ID
     *
     * @return  Object $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromID($id) {
    	
    	try {
    		
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.getComment", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                intval($id)
            ));
            
            $comment = $rpc_client->send();
        
	        $this->id           = intval($comment['term_id']);
	        
	        $this->parent       = intval($comment['parent']);
	        
	        $this->user         = intval($comment['user_id']);
	        
	        $this->date         = strtotime($comment['dateCreated']);
        
	        $this->status       = $comment['status'];
	        
	        $this->content      = $comment['content'];
	        
	        $this->link         = $comment['link'];
	        
	        $this->type         = $comment['type'];
	        
	        $this->author       = $comment['author'];
	        
	        $this->author_url   = $comment['author_url'];
	        
	        $this->author_email = $comment['author_email'];
	        
	        $this->author_ip    = $comment['author_ip'];
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive comment informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive comment informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive comment informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive comment informations - Generic Exception (".$e->getMessage().")");
    		
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
     * @return  Object  $blog
     */
    public function getBlog() {
    	
    	return $this->getPost()->getBlog();
    	
    }
    
    /**
     * Get post reference
     *
     * @return  Object  $this->taxonomy
     */
    public function getPost() {
    	
    	return $this->post;
    	
    }
    
    /**
     * Get ID
     *
     * @return  int  $this->id
     */
    public function getID() {
    	
    	return $this->id;
    	
    }
    
    /**
     * Get user
     *
     * @return  Object  $parent
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUser() {
    	
    	$parent = null;
    	
    	if (!is_null($this->user) && $this->user > -1) {
    		
    		try {
    		
    			$parent = new WPUser($this->getPost(), $this->user);
    			
    		} catch (WPException $wpe) {
    			
    			throw $wpe;
    			
    		}
    		
    	}
    	
    	return $parent;
    	
    }
    
    /**
     * Get parent
     *
     * @return  Object  $parent
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getParent() {
    	
    	$parent = null;
    	
    	if (!is_null($this->parent) && $this->parent > -1) {
    		
    		try {
    		
    			$parent = new WPComment($this->getPost(), $this->parent);
    			
    		} catch (WPException $wpe) {
    			
    			throw $wpe;
    			
    		}
    		
    	}
    	
    	return $parent;
    	
    }
    
    /**
     * Set parent
     *
     * @param   mixed   $parent Comment parent (it accepts a WPComment object or a numeric id)
     *
     * @return  Object  $this
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
     * @return  string  $this->link
     */
    public function getLink() {
    	
    	return $this->link;
    	
    }
    
    /**
     * Get author
     *
     * @return  string  $this->author
     */
    public function getAuthor() {
    	
    	return $this->author;
    	
    }
    
    /**
     * Get author url
     *
     * @return  string  $this->author_url
     */
    public function getAuthorURL() {
    	
    	return $this->author_url;
    	
    }
    
    /**
     * Get author email
     *
     * @return  string  $this->author_email
     */
    public function getAuthorEmail() {
    	
    	return $this->author_email;
    	
    }
    
    /**
     * Get author ip
     *
     * @return  string  $this->author_ip
     */
    public function getAuthorIP() {
    	
    	return $this->author_ip;
    	
    }
    
    /**
     * Get creation date
     *
     * @param   string $format Date format
     *
     * @return  mixed  $this->date
     */
    public function getDate($format = null) {
    	
    	if (is_null($format)) {
    		
    		return $this->date;
    		
    	} else {
    		
    		return date($format, $this->date);
    		
    	}
    	
    }
    
    /**
     * Get status
     *
     * @return  string  $this->status
     */
    public function getStatus() {
    	
    	return $this->status;
    	
    }
    
    /**
     * Set status
     *
     * @param   string  $status
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setStatus($status) {
    	
    	if (in_array($status, $this->supportedStatus)) {
    		
    		$this->status = $status;
    	
    		return $this;
    		
    	} else {
    		
    		throw new WPException("Unsupported comment status");
    		
    	}
    	
    }
    
    /**
     * Get comment content
     *
     * @return  string  $this->content
     */
    public function getContent() {
    	
    	return $this->content;
    	
    }
    
    /**
     * Set comment content
     *
     * @param   string  $content
     *
     * @return  Object  $this
     */
    public function setContent($content) {
    	
    	$this->content = $content;
    	
    	return $this;
    	
    }
    
    /**
     * Get comment type
     *
     * @return  string  $this->type
     */
    public function getType() {
    	
    	return $this->type;
    	
    }
	
    /**
     * Save comment
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function save() {
    	
    	try {
    	
	    	if ($this->getID() == -1) {
	    		
	    		$this->createComment();
	    		
	    	} else {
	    		
	    		$this->editComment();
	    		
	    	}
	    	
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Create comment
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function createComment() {
    	
    	$content = $this->getCommentData();
    	
    	try {
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.newComment", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $this->getPost()->getID(),
                $content
            ));
            
            $id = $rpc_client->send();
            
            $this->loadFromID($id);
    		
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to create new comment - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to create new comment - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to create new comment - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to create new comment - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Edit comment
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function editComment() {
    	
    	$content = $this->getCommentData();
    	
    	try {
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.editComment", array( 
                $this->getBlog()->getID(),
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $this->getID(),
                $content
            ));
            
            $rpc_client->send();
    		
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to edit comment - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to edit comment - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to edit comment - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to edit comment - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Load term data
     *
     * @return  array  $data
     */
    private function getCommentData() {
    	
    	$profile = $this->getBlog()->getProfile();
    	  	
    	$data = array(
    		'content'      => $this->content,
    		'author'       => (empty($this->author))?$profile->getDisplayName():$this->author,
    		'author_url'   => (empty($this->author_url))?$profile->getURL():$this->author_url,
    		'author_email' => (empty($this->author_email))?$profile->getEmail():$this->author_email
    	);
    	
    	if (!is_null($this->getParent())) {
    		
    		$data['comment_parent'] = $this->parent;
    		
    	}
    	
    	if (!empty($this->status)) {
    		
    		$data['status'] = $this->status;
    		
    	}
    	
    	return $data;
    		
    }
    
    /**
     * Delete term
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function delete() {
    	
    	try {
            $rpc_client = new RpcClient($this->blog->getEndPoint());
            
            $rpc_client->addRequest("wp.deleteComment", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $this->getID()
            ));
            
            $return = $rpc_client->send();
    		
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to delete comment - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to delete comment - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to delete comment - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to delete comment - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	$this->resetData();
    	
    	return filter_var($return, FILTER_VALIDATE_BOOLEAN);
    	
    }
	
    /**
     * Reset data of the object, it can still be used calling the loadFromID method
     *
     * @return  Object  $this
     */
    
    private function resetData() {
        
        $this->id           = -1;
        
        $this->parent       = -1;
        
        $this->user         = -1;
        
        $this->date         = 0;
    
        $this->status       = "";
        
        $this->content      = "";
        
        $this->link         = "";
        
        $this->type         = "";
        
        $this->author       = "";
        
        $this->author_url   = "";
        
        $this->author_email = "";
        
        $this->author_ip    = "";
    	
    	return $this;
        
    }
    
}