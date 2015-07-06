<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

/** 
 * Comodojo Wordpress API Wrapper. This class is an iterator for WPComment class
 *
 * It allows to fetch through comments.
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
class WPCommentIterator implements \Iterator {
	
	/**
     * Post reference
     *
     * @var Object
     */
	private $post = null;
	
	/**
     * Actual count ID
     *
     * @var int
     */
	private $current = 0;
	
	/**
     * Comment status
     *
     * @var string
     */
	private $status = "";
	
	/**
     * Current comment
     *
     * @var Object
     */
	private $comment = null;
	
	/**
     * Whether the iterator has at least one more object
     *
     * @var boolean
     */
	private $has_next = false;
	
	/**
     * Comments count
     *
     * @var int
     */
	private $comment_approved = 0;
	private $comment_awaiting = 0;
	private $comment_spam     = 0;
	private $comment_total    = 0;
	
    /**
     * Class constructor
     *
     * @param   Object  $blog Reference to a blog object
     * @param   array   $ids  List of Comment IDs (optional)
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($post, $status="") {
    	
        if ( is_null($post) || is_null($post->getWordpress()) || !$post->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to fetch comments");
        	
        }
        
        $this->post   = $post;
        
        $this->status = $status;
        
        try {
        
        	$this->loadCommentCount();
        	
        } catch (WPException $wpe) {
        	
        	throw $wpe;
        	
        }
        
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
     * Post reference
     *
     * @return  Object  $this->post
     */
    public function getPost() {
    	
    	return $this->post;
    	
    }
	
    /**
     * Check if there is another element in the comment list
     *
     * @return  boolean $hasNext
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function hasNext() {
    	
    	try {
	    		
    		$this->comment = new WPComment($this->getPost());
    		
    		$this->comment->loadFromList($this->current, $this->status);
            
            if (!is_null($this->comment)) {
            	
            	$this->has_next = true;
            	
            }
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this->has_next;
    	
    }
    
    /**
     * Get next element in the comment list
     *
     * @return  Object $next
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getNext() {
    	
    	try {
    		
	    	if ($this->has_next || $this->hasNext()) {
            	
            	$this->current++;
            	
            	$this->has_next = false;
	    		
	    		return $this->comment;
	    		
	    	}
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Get current id in the comment list
     *
     * @return  int $id
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getCurrentID() {
    	
    	if (!is_null($this->comment)) {
    		
    		return $this->comment->getID();
    		
    	} else {
    		
    		throw new WPException("Comment ID not available");
    		
    	}
    	
    }
    
    /**
     * Get fetched items
     *
     * @return  int  $this->current
     */
    public function getFetchedItems() {
    	
    	return $this->current;
    	
    }
    
    /**
     * Get total items
     *
     * @return  int  $this->comment_total
     */
    public function getTotal() {
    	
    	return $this->comment_total;
    	
    }
    
    /**
     * Get approved
     *
     * @return  int  $this->comment_approved
     */
    public function getApproved() {
    	
    	return $this->comment_approved;
    	
    }
    
    /**
     * Get spam
     *
     * @return  int  $this->comment_spam
     */
    public function getSpam() {
    	
    	return $this->comment_spam;
    	
    }
    
    /**
     * Get awaiting
     *
     * @return  int  $this->comment_awaiting
     */
    public function getAwaiting() {
    	
    	return $this->comment_awaiting;
    	
    }
	
    /**
     * The following methods implement the Iterator interface
     */
	
    /**
     * Reset the iterator
     *
     * @return  Object  $this
     */
    public function rewind() {
			
		$this->current  = 0;
    	
    	return $this;
        
    }
	
    /**
     * Return the current object
     *
     * @return  Object  $comment
     */
    public function current() {
    	
    	return $this->comment;
        
    }
	
    /**
     * Return the current index
     *
     * @return  int  $id
     */
    public function key() {
    	
    	return $this->getCurrentID();
        
    }
	
    /**
     * Return the current index
     *
     * @return  Object  $this
     */
    public function next() {
    	
    	$this->getNext();
    	
    	return $this;
        
    }
	
    /**
     * Check if there's a next value
     *
     * @return  boolean  $hasNext
     */
    public function valid() {
    	
    	return $this->hasNext();
        
    }
    
    /**
     * Get comment count for current post
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function loadCommentCount() {
    	
    	if ($this->getID() > 0) {
	    	try {
	    		
	            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
	            
	            $rpc_client->setAutoclean()->addRequest("wp.getCommentCount", array( 
	                $this->getBlog()->getID(), 
	                $this->getWordpress()->getUsername(), 
	                $this->getWordpress()->getPassword(),
	                $this->getPost()->getID()
	            ));
	            
	            $count = $rpc_client->send();
	            
	            $this->comment_approved = $count[0]['approved'];
	            
	            $this->comment_awaiting = $count[0]['awaiting_moderation'];
	            
	            $this->comment_spam     = $count[0]['spam'];
	            
	            $this->comment_total    = $count[0]['approved'];
            
	    	} catch (RpcException $rpc) {
	    		
	    		throw new WPException("Unable to retrieve comment count - RPC Exception (".$rpc->getMessage().")");
	    		
	    	} catch (XmlrpcException $xml) {
	    		
	    		throw new WPException("Unable to retrieve comment count - XMLRPC Exception (".$xml->getMessage().")");
	    		
	    	} catch (HttpException $http) {
	    		
	    		throw new WPException("Unable to retrieve comment count - HTTP Exception (".$http->getMessage().")");
	    		
	    	} catch (Exception $e) {
	    		
	    		throw new WPException("Unable to retrieve comment count - Generic Exception (".$e->getMessage().")");
	    		
	    	}
	            
    	}
    	
    	
    	return $this;
    	
    }
    
}