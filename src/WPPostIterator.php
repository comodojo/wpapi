<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class is an iterator for WPPost class
 *
 * It allows to fetch through posts.
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
class WPPostIterator implements \Iterator {
	
	/**
     * Blog reference
     *
     * @var Object
     */
	private $blog = null;
	
	/**
     * Actual count ID
     *
     * @var int
     */
	private $current = 0;
	
	/**
     * Post IDs
     *
     * @var array
     */
	private $posts = array();
	
	/**
     * Post count
     *
     * @var int
     */
	private $count = 0;
	
    /**
     * Class constructor
     *
     * @param   Object  $blog Reference to a blog object
     * @param   array   $ids  List of Post IDs (optional)
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $ids=null) {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to fetch posts");
        	
        }
        
        $this->blog = $blog;
        
        if (!is_null($ids)) {
        
        	$this->loadIDs($ids);
        	
        }
        
    }
    
    /**
     * Load post list
     *
     * @param   array   $ids  List of Post IDs
     *
     * @return  Object  $this
     */
    public function loadIDs($ids) {
        
    	$this->posts = $ids;
    	
    	$this->count = count($ids);
    	
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
    	
    	return $this->blog;
    	
    }
	
    /**
     * Check if there is another element in the post list
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
     * Check if there is another element before the current one in the post list
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
     * Get next element in the post list
     *
     * @return  Object $next
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getNext() {
    	
    	try {
    		
	    	if ($this->hasNext()) {
	    		
	    		$post = new WPPost($this->getBlog(), $this->getCurrentID());
            	
            	$this->current++;
	    		
	    		return $post;
	    		
	    	}
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Get previous element in the post list
     *
     * @return  Object $previous
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPrevious() {
    	
    	try {
    		
	    	if ($this->hasPrevious()) {
            	
            	$this->current--;
	    		
	    		$post = new WPPost($this->getBlog(), $this->getCurrentID());
	    		
	    		return $post;
	    		
	    	}
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Get current id in the post list
     *
     * @return  int $id
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getCurrentID() {
    	
    	if (isset($this->posts[$this->current])) {
    		
    		return $this->posts[$this->current];
    		
    	} else {
    		
    		throw new WPException("Post ID not available");
    		
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
     * @return  Object  $post
     */
    public function current() {
    	
    	return new WPPost($this->getBlog(), $this->getCurrentID());
        
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