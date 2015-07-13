<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class allow to set values for a post data object
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
abstract class WPPostDataObjectSetter extends WPPostDataSetter {
    
    /**
     * Set post status
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setStatus($value) {
            
        if (empty($this->supportedStatus)) 
        	$this->supportedStatus  = $this->getBlog()->getSupportedPostStatus();
    	
    	return $this->setCheckedValue($this->supportedStatus, $value, $this->status);
    	
    }
    
    /**
     * Set ping post type
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setType($value) {
            
        if (empty($this->supportedTypes)) 
        	$this->supportedTypes = $this->getBlog()->getSupportedTypes();
    	
    	return $this->setCheckedValue($this->supportedTypes, $value, $this->type);
    	
    }
    
    /**
     * Set post format
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setFormat($value) {
            
        if (empty($this->supportedFormats)) 
        	$this->supportedFormats = $this->getBlog()->getSupportedFormats();
    	
    	return $this->setCheckedValue($this->supportedFormats, $value, $this->format);
    	
    }
    
    /**
     * Set author
     *
     * @param  mixed      $value Author ID or WPUser reference
     *
     * @return WPPostData $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setAuthor($value) {
    	
    	if (is_numeric($value)) {
    		
	    	$this->author = new WPUser($this->getBlog(), $value);
    		
    	} else {
    		
    		$this->author = $value;
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Set comment status
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setCommentStatus($value) {
    	
    	return $this->setCheckedValue($this->supportedCommentStatus, $value, $this->comment);
    	
    }
    
    /**
     * Set ping status
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setPingStatus($value) {
    	
    	return $this->setCheckedValue($this->supportedPingStatus, $value, $this->ping);
    	
    }
    
    /**
     * Clean custom fields
     *
     * @return WPPostData $this
     */
    public function cleanCustomFields() {
    	
    	$this->custom = array();
    	
    	return $this;
    	
    }
    
    /**
     * Set custom field
     *
     * @param  string     $field
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setCustomField($field, $value) {
    	
    	foreach ($this->custom as $id => $custom) {
    		
    		if ($custom['key'] == $field) {
    			
    			$this->custom[$id]['value'] = $value;
    			
    			return $this;
    			
    		}
    		
    	}
    	
    	// If the custom field requested does not exists, a new one will be created
    	array_push($this->custom, array(
    		'key'   => $field,
    		'value' => $value
    	));
    	
    	return $this;
    	
    }
    
    /**
     * Set post thumbnail
     *
     * @param  mixed      $thumb Media ID or WPMedia object
     *
     * @return WPPostData $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setThumbnail($thumb = null) {
    	
    	if (is_null($thumb)) {
    		
    		$this->thumbnail = null;
    	
    	} elseif (is_numeric($thumb)) {
    	
	    	$this->thumbnail = new WPMedia($this->getBlog(), $thumb);
    		
    	} else {
    		
    		$this->thumbnail = $thumb;
    		
    	}
    	
    	return $this;
    	
    }
    
}