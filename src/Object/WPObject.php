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
	private $wp = null;
	
	/**
     * ID of the post
     *
     * @var int
     */
	private $id = 0;
	
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
    
}