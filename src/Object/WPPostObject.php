<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. Abstract class to identify Wordpress entities related to a particular post
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
abstract class WPPostObject extends WPBlogObject {
	
	/**
     * Wordpress blog reference
     *
     * @var WPPost
     */
	protected $post = null;
	
    /**
     * Class constructor
     *
     * @param   WPBlog $blog Reference to the wordpress blog
     * @param   int    $id   Object ID (optional)
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($post, $id=0) {
    	
        if ( is_null($post) || is_null($post->getWordpress()) || !$post->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access post informations");
        	
        }
        
        $this->post = $post;
        
        $this->blog = $post->getBlog();
        
        $this->wp   = $post->getWordpress();
        
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
     * Get post reference
     *
     * @return WPPost $post
     */
    public function getPost() {
    	
    	return $this->post;
    	
    }
    
}