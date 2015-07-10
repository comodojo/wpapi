<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. Abstract class to identify Wordpress entities related to a particular blog
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
abstract class WPBlogObject extends WPObject {
	
	/**
     * Wordpress blog reference
     *
     * @var WPBlog
     */
	private $blog = null;
	
    /**
     * Class constructor
     *
     * @param   WPBlog $blog Reference to the wordpress blog
     * @param   int    $id   Object ID (optional)
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $id=0) {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access post informations");
        	
        }
        
        $this->blog = $blog;
        
        $this->wp   = $blog->getWordpress();
        
        $this->id   = intval($id);
        
        if ($id > 0) {
        	
        	try {
        		
        		$this->loadFromID($id);
        		
        	} catch (WPException $wpe) {
        		
        		throw $wpe;
        		
        	}
        	
        }
        
    }
    
    /**
     * Get blog reference
     *
     * @return WPBlog $blog
     */
    public function getBlog() {
    	
    	return $this->blog;
    	
    }
	
    /**
     * Load data for an object
     *
     * @param  int      $id    
     *
     * @return WPObject $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract public function loadFromID($id);
	
    /**
     * Save object data
     *
     * @return WPObject $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract public function save();
    
    /**
     * Delete object
     * 
     * @throws \Comodojo\Exception\WPException
     */
    abstract public function delete();
	
    /**
     * Reset data of the object, it can still be used calling the loadFromID method
     *
     * @return  Object  $this
     */
    abstract protected function resetData();
    
}