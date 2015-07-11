<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. Abstract class to identify Wordpress entities
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
abstract class WPObject {
	
	/**
     * Wordpress reference
     *
     * @var WP
     */
	protected $wp = null;
	
	/**
     * ID of the post
     *
     * @var int
     */
	protected $id = 0;
	
    /**
     * Class constructor
     *
     * @param   WP  $wp Reference to Wordpress
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($wp) {
    	
        if ( is_null($wp) || !$wp->isLogged() ) {
        	
        	throw new WPException("You must be logged to access post informations");
        	
        }
        
        $this->wp   = $wp;
        
    }
    
    /**
     * Get wordpress reference
     *
     * @return WP $wordpress
     */
    public function getWordpress() {
    	
    	return $this->wp;
    	
    }
    
    /**
     * Get post id
     *
     * @return int $id
     */
    public function getID() {
    	
    	return $this->id;
    	
    }
    
    /**
     * Set post id
     *
     * @return int      $id
     *
     * @return WPObject $this
     */
    protected function setID($id) {
    	
    	$this->id = intval($id);
    	
    	return $this;
    	
    }
	
    
    /**
     * Load object data
     *
     * @param  array    $data
     *
     * @return WPObject $this
     */
    abstract public function loadData($data);
	
    
    /**
     * Get object data
     *
     * @return array $data
     */
    abstract public function getData();
    
    /**
     * Check if a value is present in the passed array before putting it into the destination variable
     * 
     * @param  array $source Array where to check for the presence of the value
     * @param  mixed $value The value to check
     * @param  mixed $dest The destination variable
     *
     * @return WPObject $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function setCheckedValue($source, $value, &$dest) {
    	
    	if (in_array($value, $source)) {
    		
    		$dest = $value;
    		
    	} else {
    		
    		throw new WPException("The value '$value' is not allowed");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get a Media iterator with the requested filters
     * 
     * @param  WPBlog $blog Blog referens
     * @param  int    $post Post ID
     * @param  string $mime Mime-Typeof the items to fetch
     *
     * @return WPMediaIterator $mediaIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function getMediaIterator($blog, $post, $mime) {
    	
    	try {
    		
            return new WPMediaIterator($this->getBlog(), $this->getID(), $mime);
            
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
    /**
     * Get formatted date
     *
     * @param  mixed  $date   Date to be formatted
     * @param  string $format Date format (optional)
     *
     * @return mixed  $date
     */
    protected function getFormattedDate($date, $format = null) {
    	
    	if (is_null($format)) {
    		
    		return intval($date);
    		
    	} else {
    		
    		return date($format, intval($date));
    		
    	}
    	
    }
    
    /**
     * Get timestamp
     *
     * @param  mixed  $date   Date to parse
     *
     * @return mixed  $dateParsed
     */
    protected function parseTimestamp($date) {
    	
    	if (is_numeric($date)) {
    		
    		return intval($date);
    		
    	} else {
    		
    		return strtotime($date);
    		
    	}
    	
    }
    
}