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
abstract class WPUserLoader extends WPUserData {
	
    /**
     * Load data for an user
     *
     * @param  array        $data User data
     *
     * @return WPUserLoader $this
     */
    public function loadData($data) {
    	
    	$this->resetData();
        
    	$this->setID(intval($data['user_id']));
    
        $this->setUsername($data['username']);
        
        $this->setFirstname($data['first_name']);
        
        $this->setLastname($data['last_name']);
        
        $this->setBiography($data['bio']);
        
        $this->setEmail($data['email']);
        
        $this->setNickname($data['nickname']);
        
        $this->setNicename($data['nicename']);
        
        $this->setURL($data['url']);
        
        $this->setDisplayname($data['display_name']);
        
        $this->setRegistration($data['registered']);
        
        $this->setRoles($data['roles']);
    	
    	return $this;
    	
    }
    
    /**
     * Get user data
     *
     * @return array $data
     */
    public function getData() {
    	
        return array(
        	"first_name"   => $this->getFirstname(),
        	"last_name"    => $this->getLastname(),
        	"url"          => $this->getURL(),
        	"display_name" => $this->getDisplayname(),
        	"nickname"     => $this->getNickname(),
        	"nicename"     => $this->getNicename(),
        	"bio"          => $this->getBiography()
        );
    	
    }
    
    /**
     * Reset data of the object, it can still be used calling the loadFromID method
     *
     * @return  WPPostLoader $this
     */
    protected function resetData() {
        
    	$this->setID(0);
    
        $this->setUsername("");
        
        $this->setFirstname("");
        
        $this->setLastname("");
        
        $this->setBiography("");
        
        $this->setEmail("");
        
        $this->setNickname("");
        
        $this->setNicename("");
        
        $this->setURL("");
        
        $this->setDisplayname("");
        
        $this->setRegistration(0);
        
        $this->setRoles();
    	
    	return $this;
    	
    }
    
}