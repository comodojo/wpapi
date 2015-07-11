<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class is a Wordpress taxonomy data loader
 *
 * It allows to retrieve taxonomy informations.
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
abstract class WPTaxonomyLoader extends WPTaxonomyData {
	
    /**
     * Load taxonomy data
     *
     * @param   array $taxonomy
     *
     * @return  WPTaxonomyLoader $this
     */
    public function loadData($taxonomy) {
		
        $this->id           = $taxonomy['name'];
		
        $this->name         = $taxonomy['name'];
        
        $this->label        = $taxonomy['label'];
        
        $this->hierarchical = filter_var($taxonomy['hierarchical'], FILTER_VALIDATE_BOOLEAN);
        
        $this->public_tax   = filter_var($taxonomy['public'], FILTER_VALIDATE_BOOLEAN);
        
        $this->show_ui      = filter_var($taxonomy['show_ui'], FILTER_VALIDATE_BOOLEAN);
        
        $this->builtin      = filter_var($taxonomy['_builtin'], FILTER_VALIDATE_BOOLEAN);
        
        $this->labels       = $taxonomy['labels'];
        
        $this->cap          = $taxonomy['cap'];
        
        $this->object_type  = $taxonomy['object_type'];
    	
    	return $this;
        
    }
	
    /**
     * Get taxonomy data
     *
     * @return  array $data
     */
    public function getData() {
    	
    	return array(
    		'name'         => $this->name,
    		'label'        => $this->label,
    		'hierarchical' => $this->hierarchical,
    		'public'       => $this->public_tax,
    		'show_ui'      => $this->show_ui,
    		'_builtin'     => $this->builtin,
    		'labels'       => $this->labels,
    		'cap'          => $this->cap,
    		'object_type'  => $this->object_type
    	);
        
    }
	
    /**
     * Reset taxonomy data
     *
     * @return WPTaxonomyLoader $this
     */
    public function resetData() {
		
        $this->id           = "";
		
        $this->name         = "";
        
        $this->label        = "";
        
        $this->hierarchical = false;
        
        $this->public_tax   = false;
        
        $this->show_ui      = false;
        
        $this->builtin      = false;
        
        $this->labels       = array();
        
        $this->cap          = array();
        
        $this->object_type  = array();
    	
    	return $this;
        
    }
    
}