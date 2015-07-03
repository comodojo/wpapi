<?php namespace Comodojo\WP;
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
     * Comment IDs
     *
     * @var array
     */
	private $comments = array();
	
	/**
     * Comment count
     *
     * @var array
     */
	private $count = 0;
	
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
    public function __construct($post, $ids=null) {
    	
        if ( is_null($post) || is_null($post->getWordpress()) || !$post->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to fetch comments");
        	
        }
        
        $this->post = $post;
        
        if (!is_null($ids)) {
        
        	$this->loadIDs($ids);
        	
        }
        
    }
    
    /**
     * Load comment list
     *
     * @param   array   $ids  List of Comment IDs
     *
     * @return  Object  $this
     */
    public function loadIDs($ids) {
        
    	$this->comments = $ids;
    	
    	$this->count    = count($ids);
    	
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
    	
    	if ($this->current < $this->count) {
    	
    		return true;
    		
    	} else {
    		
    		return false;
    		
    	}
    	
    }
	
    /**
     * Check if there is another element before the current one in the comment list
     *
     * @return  boolean $hasPrevious
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function hasPrevious() {
    	
    	if ($this->current > 0) {
    	
    		return true;
    		
    	} else {
    		
    		return false;
    		
    	}
    	
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
    		
	    	if ($this->hasNext()) {
	    		
	    		$comment = new WPComment($this->getPost(), $this->getCurrentID());
            	
            	$this->current++;
	    		
	    		return $comment;
	    		
	    	}
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Get previous element in the comment list
     *
     * @return  Object $previous
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPrevious() {
    	
    	try {
    		
	    	if ($this->hasPrevious()) {
            	
            	$this->current--;
	    		
	    		$comment = new WPComment($this->getPost(), $this->getCurrentID());
	    		
	    		return $comment;
	    		
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
    	
    	if (isset($this->comments[$this->current])) {
    		
    		return $this->comments[$this->current];
    		
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
     * @return  int  $this->count
     */
    public function getLength() {
    	
    	return $this->count;
    	
    }
	
    /**
     * Reverse the iterator
     *
     * @return  Object  $this
     */
    public function reverse() {
			
		$this->current = $this->count;
    	
    	return $this;
        
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
    	
    	return new WPComment($this->getPost(), $this->getCurrentID());
        
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
    	
    	$this->current++;
    	
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
    
}