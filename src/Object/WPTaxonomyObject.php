<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. Abstract class to identify Wordpress entities related to a particular taxonomy
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
abstract class WPTaxonomyObject extends WPBlogObject {
	
	/**
     * Wordpress taxonomy reference
     *
     * @var WPTaxonomy
     */
	protected $taxonomy = null;
	
    /**
     * Class constructor
     *
     * @param   WPTaxonomy $taxonomy Reference to the wordpress blog post
     * @param   int        $id       Object ID (optional)
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($taxonomy, $id=0) {
    	
        if ( is_null($taxonomy) || is_null($taxonomy->getWordpress()) || !$taxonomy->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access taxonomy informations");
        	
        }
        
        $this->taxonomy = $taxonomy;
        
        $this->blog = $taxonomy->getBlog();
        
        $this->wp   = $taxonomy->getWordpress();
        
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
     * Get taxonomy reference
     *
     * @return WPTaxonomy $taxonomy
     */
    public function getTaxonomy() {
    	
    	return $this->taxonomy;
    	
    }
    
}