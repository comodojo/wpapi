<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress profile
 *
 * It allows to retrieve and edit informations about the logged user.
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
class WPProfile extends WPUser {
    
    /**
     * Set first name
     *
     * @param   string  $firstname User's first name
     *
     * @return  Object  $this
     */
    public function setFirstname($firstname) {
    	
    	return parent::setFirstname($firstname);
    	
    }
    
    /**
     * Set last name
     *
     * @param   string  $lastname User's last name
     *
     * @return  Object  $this
     */
    public function setLastname($lastname) {
    	
    	return parent::setLastname($lastname);
    	
    }
    
    /**
     * Set user biography
     *
     * @param   string  $bio User's biography
     *
     * @return  Object  $this
     */
    public function setBiography($bio) {
    	
    	return parent::setBiography($bio);
    	
    }
    
    /**
     * Set nickname
     *
     * @param   string  $nickname User's nickname
     *
     * @return  Object  $this
     */
    public function setNickname($nickname) {
    	
    	return parent::setNickname($nickname);
    	
    }
    
    /**
     * Get nicename
     *
     * @param   string  $nicename User's nicename
     *
     * @return  Object  $this
     */
    public function setNicename($nicename) {
    	
    	return parent::setNicename($nicename);
    	
    }
    
    /**
     * Set URL to user personal website
     *
     * @param   string  $url URL to user personal website
     *
     * @return  Object  $this
     */
    public function setURL($url) {
    	
    	return parent::setURL($url);
    	
    }
    
    /**
     * Set display name
     *
     * @param   string  $displayname User's display name
     *
     * @return  Object  $this
     */
    public function setDisplayname($displayname) {
    	
    	return parent::setDisplayname($displayname);
    	
    }
	
    /**
     * Save changes to user profile
     *
     * @return  boolean  $result
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function save() {
    	
        if ( is_null($this->getBlog()) || is_null($this->getWordpress()) || !$this->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to modify user informations");
        	
        }
        
    	try {
            
            $result = $this->getWordpress()->sendMessage("wp.editProfile", array(
                $this->getData()
            ), $this->getBlog());
            
            $this->loadFromID($this->getID());
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to save user informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return filter_var($result, FILTER_VALIDATE_BOOLEAN);
    	
    }
    
}