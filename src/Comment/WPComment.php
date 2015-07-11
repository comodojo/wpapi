<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress comment
 *
 * It allows to retrieve and edit a comment to a wordpress post.
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
class WPComment extends WPCommentLoader {
	
    /**
     * Load comment from ID
     *
     * @param   int       $id Comment ID
     *
     * @return  WPComment $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromID($id) {
    	
    	return $this->callMethotFromID("wp.getComment", $id);
    	
    }
	
    /**
     * Load comment from list
     *
     * @param   int       $count Comment count
     *
     * @return  WPComment $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromList($count, $status="") {
    	
    	try {
    		
    		$filter = array(
    			'post_id' => $this->getPost()->getID(),
    			'offset'  => $count,
    			'number'  => 1
    			
    		);
    		
    		if (!empty($status)) {
    			$filter['status'] = $status;
    		}
    		
            $comments = $this->getWordpress()->sendMessage("wp.getComments", array(
                $filter
            ), $this->getBlog());
            
            if (count($comments) > 0) {
            	
            	$this->loadData($comments[0]);
            	
            } else {
            	
            	$this->resetData();
            	
            	return null;
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve comment informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Save comment
     *
     * @return WPComment $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function save() {
    	
    	try {
    	
	    	if ($this->getID() == 0) {
	    		
	    		$this->createComment();
	    		
	    	} else {
	    		
	    		$this->editComment();
	    		
	    	}
	    	
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Create comment
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function createComment() {
    	
    	$content = $this->getData();
    	
    	try {
            
            $id = $this->getWordpress()->sendMessage("wp.newComment", array(
                $this->getPost()->getID(),
                $content
            ), $this->getBlog());
            
            $this->loadFromID($id);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to create new comment (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Edit comment
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function editComment() {
    	
    	$content = $this->getData();
    	
    	try {
    		
            $this->getWordpress()->sendMessage("wp.editComment", array(
                $this->getID(),
                $content
            ), $this->getBlog());
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to edit comment (".$wpe->getMessage().")");
    		
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
    		
            $return = $this->getWordpress()->sendMessage("wp.deleteComment", array(
                $this->getID()
            ), $this->getBlog());
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to delete comment (".$wpe->getMessage().")");
    		
    	}
    	
    	$this->resetData();
    	
    	return filter_var($return, FILTER_VALIDATE_BOOLEAN);
    	
    }
	
}