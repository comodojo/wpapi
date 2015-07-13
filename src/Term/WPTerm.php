<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress term
 *
 * It allows to retrieve terms informations like tags or categories.
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
class WPTerm extends WPTermLoader {
	
    /**
     * Load term from ID
     *
     * @param   int    $id Term ID
     *
     * @return  Object $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function loadFromID($id) {
    	
    	try {
    		
            $term = $this->getWordpress()->sendMessage("wp.getTerm", array(
                $this->getTaxonomy()->getName(),
                intval($id)
            ), $this->getBlog());
            
            $this->loadData($term);
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve term informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
	
    /**
     * Save term
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function save() {
    	
    	if ($this->getID() == 0) {
    		
    		$this->createTerm();
    		
    	} else {
    		
    		$this->editTerm();
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Create term
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function createTerm() {
    	
    	$content = $this->getData();
    	
    	try {
    		
            $id = $this->getWordpress()->sendMessage("wp.newTerm", array(
                $content
            ), $this->getBlog());
            
            $this->loadFromID($id);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to create term (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Edit term
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function editTerm() {
    	
    	$content = $this->getData();
    	
    	try {
    		
            $this->getWordpress()->sendMessage("wp.editTerm", array(
                $this->getID(),
                $content
            ), $this->getBlog());
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to edit term (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Delete term
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function delete() {
    	
    	try {
            
            $return = $this->getWordpress()->sendMessage("wp.deleteTerm", array(
                $this->getTaxonomy()->getName(),
                $this->getID()
            ), $this->getBlog());
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to delete term (".$wpe->getMessage().")");
    		
    	}
    	
    	$this->resetData();
    	
    	return filter_var($return, FILTER_VALIDATE_BOOLEAN);
    	
    }
    
}