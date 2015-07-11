<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress post
 *
 * It allows to retrieve and edit posts from a wordpress blog.
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
class WPPost extends WPPostLoader {
	
    /**
     * Load data for a post
     *
     * @param  int    $id ID of the post
     *
     * @return WPPost $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function loadFromID($id) {
    	
    	try {
    		
            $post = $this->getWordpress()->sendMessage("wp.getPost", array(
                intval($id)
            ), $this->getBlog());
			
			$this->loadData($post);
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Save post
     *
     * @return WPPost $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function save() {
    	
    	try {
    	
	    	if ($this->getID() == 0) {
	    		
	    		if (is_null($this->getAuthor())) {
	    			
	    			$this->setAuthor($this->getBlog()->getProfile());
	    			
	    		}
	    		
	    		if ($this->getCreationDate() == 0) {
	    			
	    			$this->setCreationDate(time());
	    			
	    		}
	    		
	    		$this->createPost();
	    		
	    	} else {
	    		
	    		$this->editPost();
	    		
	    	}
	    	
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Create post
     *
     * @return WPPost $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function createPost() {
    	
    	$content = $this->getData();
    	
    	try {
    		
            $id = $this->getWordpress()->sendMessage("wp.newPost", array(
                $content
            ), $this->getBlog(), array( "post_date" => "datetime" ));
            
            $this->loadFromID($id);
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to create new post (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Edit post
     *
     * @return WPPost $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function editPost() {
    	
    	$content = $this->getData();
    	
    	try {
    		
            $this->getWordpress()->sendMessage("wp.editPost", array(
                $this->getID(),
                $content
            ), $this->getBlog(), array( "post_date" => "datetime" ));
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to edit post (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Delete post
     * 
     * @return boolean $isDeleted
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function delete() {
    	
    	try {
            
            $return = $this->getWordpress()->sendMessage("wp.deletePost", array(
                $this->getID()
            ), $this->getBlog());
            
    		
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to delete post (".$wpe->getMessage().")");
    		
    	}
    	
    	$this->resetData();
    	
    	return filter_var($return, FILTER_VALIDATE_BOOLEAN);
    	
    }
    
}