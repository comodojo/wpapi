<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress user
 *
 * It allows to retrieve user's informations.
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
class WPUser extends WPUserLoader {
	
    /**
     * Load user for ID
     *
     * @param   int     $id       User ID
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromID($id) {
    	
    	try {
    		
            $user = $this->getWordpress()->sendMessage("wp.getUser", array(
                $id
            ), $this->getBlog());
        
        	$this->loadData($user);
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve user's informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
	
    /**
     * Wordpress XML-RPC APIs do not allow you to modify users informations
     *
     * @return  WPUser $this
     */
    public function save() {
    	
    	return $this;
    		
    }
    
    /**
     * Wordpress XML-RPC APIs do not allow you to delete users informations
     * 
     * @return boolean $isDeleted
     */
    public function delete() {
    	
    	return false;
    	
    }
    
}