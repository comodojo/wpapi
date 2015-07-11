<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress taxonomy
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
class WPTaxonomy extends WPTaxonomyLoader {
	
    /**
     * Load taxonomy by name
     *
     * @param   string  $id Taxonomy name
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function loadFromID($id) {
    	
    	return $this->callMethotFromID("wp.getTaxonomy", $id);
    	
    }
    
    /**
     * Get terms from this taxonomy
     *
     * @param   string  $search     Restrict to terms with names that contain (case-insensitive) this value
     * @param   boolean $hide_empty Hide empty terms (count=0)
     * @param   int     $number     Number of terms retrieved
     * @param   int     $offset     Number of terms to skip
     * @param   string  $orderby    Field to use for ordering
     * @param   string  $order      Type of ordering (asd or desc)
     *
     * @return  array   $terms
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getTerms($search="", $hide_empty=false, $number = null, $offset = 0, $orderby = "name", $order = "ASC") {
    	
    	$terms  = array();
    	
    	$filter = array(
        	"hide_empty" => $hide_empty,
        	"offset"     => $offset,
        	"orderby"    => $orderby,
        	"order"      => $order
        );
        
        if (!empty($search)) {
        	$filter["search"] = $search;
        }
        
        if (!is_null($number)) {
        	$filter["number"] = intval($number);
        }
        
    	try {
    		
            $list = $this->getWordpress()->sendMessage("wp.getTerms", array(
                $this->getName(),
                $filter
            ), $this->getBlog());
            
            foreach ($list as $term) {
            	
            	$t = new WPTerm($this);
            	$t->loadData($term);
            	
            	array_push($terms, $t);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve term informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $terms;
    	
    }
    
    /**
     * Wordpress XML-RPC APIs do not allow you to delete taxonomy informations
     * 
     * @return boolean $isDeleted
     */
    public function delete() {
    	
    	return false;
    	
    }
    
    /**
     * Wordpress XML-RPC APIs do not allow you to modify taxonomy informations
     * 
     * @return WPTaxonomy $this
     */
    public function save() {
    	
    	return $this;
    	
    }
    
}