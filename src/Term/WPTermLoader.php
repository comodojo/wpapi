<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress term
 *
 * It allows to load terms informations like tags or categories.
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
abstract class WPTermLoader extends WPTermData {
    
    /**
     * Load term data
     *
     * @return  array  $data
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getData() {
    	  	
    	$data = array(
    		'taxonomy'    => $this->getTaxonomy()->getName(),
    		'name'        => $this->name,
    		'description' => $this->description
    	);
    	
    	if (!is_null($this->getParent())) {
    		
    		$data['parent'] = $this->parent;
    		
    	}
    	
    	if (!empty($this->slug)) {
    		
    		$data['slug'] = $this->slug;
    		
    	}
    	
    	return $data;
    		
    }
	
    /**
     * Load term data
     *
     * @param   array   $term
     *
     * @return  Object  $this
     */
    public function loadData($term) {
		
        $this->id               = intval($term['term_id']);
    
        $this->name             = $term['name'];
        
        $this->slug             = $term['slug'];
        
        $this->group            = $term['term_group'];
        
        $this->term_taxonomy_id = intval($term['term_taxonomy_id']);
        
        $this->description      = $term['description'];
        
        $this->parent           = intval($term['parent']);
        
        $this->count            = intval($term['count']);
    	
    	return $this;
        
    }
	
    /**
     * Reset data of the object, it can still be used calling the loadFromID method
     *
     * @return  Object  $this
     */
    protected function resetData() {
			
		$this->id               = -1;
		
		$this->name             = "";
		
		$this->slug             = "";
		
		$this->group            = "";
		
		$this->term_taxonomy_id = -1;
		
		$this->description      = "";
		
		$this->parent           = -1;
		
		$this->count            = 0;
    	
    	return $this;
        
    }
    
}