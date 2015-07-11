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
class WPPostIterator extends WPIteratorObject {
	
	/**
     * Post IDs
     *
     * @var array
     */
	private $posts = array();
	
    /**
     * Class constructor
     *
     * @param  WPBlog  $blog Reference to a blog object
     * @param  array   $ids  List of Post IDs (optional)
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
     * @param  array   $ids  List of Post IDs
     *
     * @return WPPostIteratori $this
     */
    public function loadData($ids) {
        
    	$this->posts = $ids;
    	
    	$this->count = count($ids);
    	
    	return $this;
    	
    }
    
    /**
     * Get post list
     *
     * @return   array   $ids  List of Post IDs
     */
    public function getData() {
    	
    	return $this->posts;
    	
    }
    
    /**
     * Check if there is another element in the user list
     *
     * @return boolean $hasNext
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function hasNext() {
    	
    	if ($this->current < $this->count) {
    	
    		$this->has_next = true;
    		
    	} else {
    		
    		$this->has_next = false;
    		
    	}
    	
    	return $this->has_next;
    	
    }
    
    /**
     * Get next element in the post list
     *
     * @return WPPost $next
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getNext() {
    	
    	try {
    		
	    	if ($this->hasNext()) {
	    		
	    		$this->object = new WPPost($this->getBlog(), $this->getCurrentID());
            	
            	$this->current++;
	    		
	    		return $this->object;
	    		
	    	}
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return null;
    	
    }
    
}