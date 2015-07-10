<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress media item
 *
 * It allows to retrieve informations about a media object.
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
class WPMedia extends WPMediaLoader {
    
    /**
     * Load a media object from its attachment ID
     *
     * @param   int     $id   Attachment ID
     *
     * @return  WPMedia $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function loadFromID($id) {
    	
    	try {
    		
            $data = $this->getWordpress()->sendMessage("wp.getMediaItem", array(
                intval($id)
            ), $this->getBlog());
            
            $this->loadData($data);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve media informations from attachment ID (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Load a media object from its position in the media library
     *
     * @param   int     $count Position in the media library
     *
     * @return  WPMedia $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function loadFromLibrary($count, $mime) {
    	
    	$content = array(
    		"number" => 1,
    		"offset" => $count
    	);
    	
    	if ($this->post > 0) {
    		$content["parent_id"] = $this->post;
    	}
    	
    	if (!is_null($mime)) {
    		$content["mime_type"] = $mime;
    	}
    	
    	try {
            
            $data = $this->getWordpress()->sendMessage("wp.getMediaLibrary", array(
                $content
            ), $this->getBlog());
            
            if (count($data) > 0) {
            
            	$this->loadData($data[0]);
            	
            } else {
            	
            	$this->resetData();
            	
            	return null;
            	
            }
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve media informations from iteration (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Upload a string of data
     *
     * @return  WPMedia $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function save() {
    	
    	try {
            
            $data = $this->getWordpress()->sendMessage("wp.uploadFile", array(
                $this->getData()
            ), $this->getBlog(), array( "bits" => "base64" ) );
            
            $this->loadFromID($data['id']);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to upload file (".$wpe->getMessage().")");
    		
    	}
    	
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