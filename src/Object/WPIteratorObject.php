<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class is an abstract class for all the iterator objcects
 *
 * It allows to fetch through objects.
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
abstract class WPIteratorObject extends WPObject implements \Iterator {
	
	/**
     * Blog reference
     *
     * @var Object
     */
	protected $blog = null;
	
	/**
     * Actual count ID
     *
     * @var int
     */
	protected $current = 0;
	
	/**
     * Current object
     *
     * @var WPObject
     */
	protected $object = null;
	
	/**
     * Whether the iterator has at least one more object
     *
     * @var boolean
     */
	protected $has_next = false;
	
	/**
     * Post count
     *
     * @var int
     */
	protected $count = 0;
    
    /**
     * Get wordpress reference
     *
     * @return WP $wordpress
     */
    public function getWordpress() {
    	
    	return $this->getBlog()->getWordpress();
    	
    }
    
    /**
     * Get user's blog
     *
     * @return WPBlog $blog
     */
    public function getBlog() {
    	
    	return $this->blog;
    	
    }
    
    /**
     * Nothing to load here
     *
     * @param  array $data
     *
     * @return WPIteratorObject $this
     */
    public function loadData($ids) {
    	
    	return $this;
    	
    }
    
    /**
     * Nothing to get here
     *
     * @return array $array
     */
    public function getData() {
    	
    	return array();
    	
    }
	
    /**
     * Check if there is another element in the post list
     *
     * @return  boolean $hasNext
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract public function hasNext();
    
    /**
     * Get next element in the post list
     *
     * @return WPObject $next
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract public function getNext();
    
    /**
     * Get current id in the post list
     *
     * @return  int $id
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getCurrentID() {
    	
    	return $this->object->getID();
    	
    }
    
    /**
     * Get fetched items
     *
     * @return  int  $fetched
     */
    public function getFetchedItems() {
    	
    	return $this->current + 1;
    	
    }
    
    /**
     * Get total items
     *
     * @return  int  $count
     */
    public function getLength() {
    	
    	return $this->count;
    	
    }
	
    /**
     * The following methods implement the Iterator interface
     */
	
    /**
     * Reset the iterator
     *
     * @return WPIteratorObject $this
     */
    public function rewind() {
			
		$this->current  = 0;
		
		$this->object   = null;
		
		$this->has_next = false;
    	
    	return $this->next();
        
    }
	
    /**
     * Return the current object
     *
     * @return  Object  $post
     */
    public function current() {
    	
    	return $this->object;
        
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
    
}