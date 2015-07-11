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
abstract class WPPostDataSetter extends WPPostData {
    
    /**
     * Set post title
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setTitle($value) {
    	
    	$this->title = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set creation date
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setCreationDate($value) {
    	
    	$this->created = $this->parseTimestamp($value);
    	
    	return $this;
    	
    }
    
    /**
     * Set modified date
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    protected function setLastModifiedDate($value) {
    	
    	$this->modified = $this->parseTimestamp($value);
    	
    	return $this;
    	
    }
    
    /**
     * Set post name
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    protected function setName($value) {
    	
    	$this->name = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set post password
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setPassword($value) {
    	
    	$this->password = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set post excerpt
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setExcerpt($value) {
    	
    	$this->excerpt = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set post content
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setContent($value) {
    	
    	$this->content = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set post parent
     *
     * @param  mixed      $value Parent post ID or WPPost reference
     *
     * @return WPPostData $this
     */
    public function setParent($value = null) {
    	
    	if (is_null($value)) {
    		
    		$this->parent = null;
    		
    	} elseif (is_numeric($value)) {
    		
    		$this->parent = intval($value);
    		
    	} else {
    		
    		$this->parent = $value->getID();
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get post mime type
     *
     * @return string     $value
     *
     * @return WPPostData $this
     */
    protected function setMimeType($value) {
    	
    	$this->mime_type = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set post link
     *
     * @return string     $value
     *
     * @return WPPostData $this
     */
    protected function setLink($value) {
    	
    	$this->link = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set post guid
     *
     * @return string     $value
     *
     * @return WPPostData $this
     */
    protected function setGUID($value) {
    	
    	$this->guid = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set post menu order
     *
     * @param  int        $value
     *
     * @return WPPostData $this
     */
    public function setMenuOrder($value) {
    	
    	$this->menu_order = intval($value);
    	
    	return $this;
    	
    }
    
    /**
     * Set sticky
     *
     * @param  boolean    $value
     *
     * @return WPPostData $this
     */
    public function setSticky($value) {
    	
    	$this->sticky = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set enclosure url
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setEnclosureURL($value) {
    	
    	$this->enclosure['url'] = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set enclosure length
     *
     * @param  int        $value
     *
     * @return WPPostData $this
     */
    public function setEnclosureLength($value) {
    	
    	$this->enclosure['length'] = $value;
    	
    	return $this;
    	
    }
    
    /**
     * Set enclosure type
     *
     * @param  string     $value
     *
     * @return WPPostData $this
     */
    public function setEnclosureType($value) {
    	
    	$this->enclosure['type'] = $value;
    	
    	return $this;
    	
    }
    
}