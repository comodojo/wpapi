<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class is an iterator for WPMedia class
 *
 * It allows to fetch through media object into the Wordpress media library.
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
class WPMediaIterator implements \Iterator {
	
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
     * Reference to the next object
     *
     * @var Object
     */
	private $next = null;
	
	/**
     * Whether the iterator has at least one more object
     *
     * @var boolean
     */
	private $has_next = false;
	
	/**
     * Post ID
     *
     * @var int
     */
	private $post = 0;
	
	/**
     * Mime-Type of the media object to fetch
     *
     * @var int
     */
	private $mime = "";
	
    /**
     * Class constructor
     *
     * @param   Object  $blog Reference to a blog object
     * @param   int     $post Post ID (optional)
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $post=0, $mime=null) {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to fetch media items");
        	
        }
        
        $this->blog = $blog;
        
        $this->mime = $mime;
        
        $this->post = intval($id);
        
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
     * Check if there is another element in the media library
     *
     * @return  boolean $this->hasNext
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function hasNext() {
    	
    	try {
    		
            $image = new WPMedia($this->getBlog());
            
            $image->setPostID($this->post);
            
            $this->next = $image->loadFromLibrary($this->current, $this->mime);
            
            if (!is_null($this->next)) {
            	
            	$this->has_next = true;
            	
            }
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this->has_next;
    	
    }
    
    /**
     * Get next element in the media library
     *
     * @return  Object $this->next
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getNext() {
    	
    	try {
    		
	    	if ($this->has_next || $this->hasNext()) {
            	
            	$this->current++;
            	
            	$this->has_next = false;
	    		
	    		return $this->next;
	    		
	    	}
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Get fetched media items
     *
     * @return  int  $this->current
     */
    public function getFetchedItems() {
    	
    	return $this->current + 1;
    	
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
		
		$this->next     = null;
		
		$this->has_next = false;
    	
    	return $this;
        
    }
	
    /**
     * Return the current object
     *
     * @return  Object  $media
     */
    public function current() {
    	
    	return $this->next;
        
    }
	
    /**
     * Return the current index
     *
     * @return  int  $id
     */
    public function key() {
    	
    	return $this->next->getID();
        
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