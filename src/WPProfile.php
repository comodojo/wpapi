<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress user
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
    	
    	$this->firstname = $firstname;
    	
    	return $this;
    	
    }
    
    /**
     * Set last name
     *
     * @param   string  $lastname User's last name
     *
     * @return  Object  $this
     */
    public function setLastname($lastname) {
    	
    	$this->lastname = $lastname;
    	
    	return $this;
    	
    }
    
    /**
     * Set user biography
     *
     * @param   string  $bio User's biography
     *
     * @return  Object  $this
     */
    public function setBiography($bio) {
    	
    	$this->bio = $bio;
    	
    	return $this;
    	
    }
    
    /**
     * Set nickname
     *
     * @param   string  $nickname User's nickname
     *
     * @return  Object  $this
     */
    public function setNickname($nickname) {
    	
    	$this->nickname = $nickname;
    	
    	return $this;
    	
    }
    
    /**
     * Get nicename
     *
     * @param   string  $nicename User's nicename
     *
     * @return  Object  $this
     */
    public function setNicename($nicename) {
    	
    	$this->nicename = $nicename;
    	
    	return $this;
    	
    }
    
    /**
     * Set URL to user personal website
     *
     * @param   string  $url URL to user personal website
     *
     * @return  Object  $this
     */
    public function setURL($url) {
    	
    	$this->url = $url;
    	
    	return $this;
    	
    }
    
    /**
     * Set display name
     *
     * @param   string  $displayname User's display name
     *
     * @return  Object  $this
     */
    public function setDisplayname($displayname) {
    	
    	$this->displayname = $displayname;
    	
    	return $this;
    	
    }
	
    /**
     * Save changes to user profile
     *
     * @return  boolean  $result
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function save() {
    	
        if ( is_null($this->blog) || is_null($this->blog->getWordpress()) || !$this->blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to modify user informations");
        	
        }
        
    	try {
    		
            $rpc_client = new RpcClient($this->blog->getEndPoint());
            
            $rpc_client->addRequest("wp.editProfile", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                array(
                	"first_name"   => $this->getFirstname(),
                	"last_name"    => $this->getLastname(),
                	"url"          => $this->getURL(),
                	"display_name" => $this->getDisplayname(),
                	"nickname"     => $this->getNickname(),
                	"nicename"     => $this->getNicename(),
                	"bio"          => $this->getBiography()
                )
            ));
            
            $result = filter_var($rpc_client->send(), FILTER_VALIDATE_BOOLEAN);
            
            $this->loadFromID($this->id);
    		
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to save user informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to save user informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to save user informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to save user informations - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $result;
    	
    }
    
}