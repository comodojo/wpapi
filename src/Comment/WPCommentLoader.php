<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class is a Wordpress comment data loader
 *
 * It allows to handle multiple data updates.
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
abstract class WPCommentLoader extends WPCommentData {
    
    /**
     * Load term data
     *
     * @return array $data
     */
    public function getData() {
    	
    	$profile = $this->getBlog()->getProfile();
    	  	
    	$data = array(
    		'content'      => $this->content,
    		'author'       => (empty($this->author))?$profile->getDisplayName():$this->getAuthor(),
    		'author_url'   => (empty($this->author_url))?$profile->getURL():$this->getAuthorURL(),
    		'author_email' => (empty($this->author_email))?$profile->getEmail():$this->getAuthorEmail()
    	);
    	
    	if (!is_null($this->getParent())) {
    		
    		$data['comment_parent'] = $this->parent;
    		
    	}
    	
    	if (!empty($this->status)) {
    		
    		$data['status'] = $this->status;
    		
    	}
    	
    	return $data;
    		
    }
	
    /**
     * Load comment data
     *
     * @return WPCommentLoader $this
     */
    
    public function loadData($comment) {
    	
    	$this->resetData();
    	
    	if (!isset($comment['comment_id'])) return null;
        
	    $this->id           = intval($comment['comment_id']);
	    
	    $this->parent       = intval($comment['parent']);
	    
	    $this->user         = intval($comment['user_id']);
	    
	    $this->date         = (isset($comment['dateCreated']))?strtotime($comment['dateCreated']):time();
	
	    $this->status       = $comment['status'];
	    
	    $this->content      = $comment['content'];
	    
	    $this->link         = $comment['link'];
	    
	    $this->type         = $comment['type'];
	    
	    $this->author       = $comment['author'];
	    
	    $this->author_url   = $comment['author_url'];
	    
	    $this->author_email = $comment['author_email'];
	    
	    $this->author_ip    = $comment['author_ip'];
    	
    	return $this;
        
    }
	
    /**
     * Reset data of the object, it can still be used calling the methods 'loadFromID' or 'loadFromList'
     *
     * @return WPCommentLoader $this
     */
    
    protected function resetData() {
        
        $this->id           = 0;
        
        $this->parent       = 0;
        
        $this->user         = 0;
        
        $this->date         = 0;
    
        $this->status       = "";
        
        $this->content      = "";
        
        $this->link         = "";
        
        $this->type         = "";
        
        $this->author       = "";
        
        $this->author_url   = "";
        
        $this->author_email = "";
        
        $this->author_ip    = "";
    	
    	return $this;
        
    }
    
}