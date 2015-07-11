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
class WPMediaIterator extends WPIteratorObject {
	
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
        
        $this->post = intval($post);
        
    }
	
    /**
     * Check if there is another element in the media library
     *
     * @return  boolean $hasNext
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function hasNext() {
    	
    	if (!$this->has_next) {
    		
	    	try {
	    		
	            $image = new WPMedia($this->getBlog());
	            
	            $image->setPostID($this->post);
	            
	            $this->object = $image->loadFromLibrary($this->current, $this->mime);
	            
	            if (!is_null($this->object)) {
	            	
	            	$this->has_next = true;
	            	
	            }
	    		
	    	} catch (WPException $wpe) {
	    		
	    		throw $wpe;
	    		
	    	}
	    	
    	}
    	
    	return $this->has_next;
    	
    }
    
    /**
     * Get next element in the media library
     *
     * @return WPMedia $object
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getNext() {
    	
    	try {
    		
	    	if ($this->has_next || $this->hasNext()) {
            	
            	$this->current++;
            	
            	$this->has_next = false;
	    		
	    		return $this->object;
	    		
	    	}
    		
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return null;
    	
    }
    
}