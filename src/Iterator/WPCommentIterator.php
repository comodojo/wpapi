<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class is an iterator for WPComment class
 *
 * It allows to fetch through comments.
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
class WPCommentIterator extends WPIteratorObject {
	
	/**
     * Post reference
     *
     * @var WPPost
     */
	private $post = null;
	
	/**
     * Comment status
     *
     * @var string
     */
	private $status = "";
	
	
	/**
     * Comments count
     *
     * @var int
     */
	private $comment_approved = -1;
	private $comment_awaiting = -1;
	private $comment_spam     = -1;
	private $comment_total    = -1;
	
    /**
     * Class constructor
     *
     * @param   Object  $post   Reference to a post object
     * @param   string  $status Filter on the comment status
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($post, $status="") {
    	
        if ( is_null($post) || is_null($post->getWordpress()) || !$post->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to fetch comments");
        	
        }
        
        $this->post   = $post;
        
        $this->blog   = $post->getBlog();
        
        $this->status = $status;
        
    }
    
    /**
     * Post reference
     *
     * @return WPPost $this->post
     */
    public function getPost() {
    	
    	return $this->post;
    	
    }
	
    /**
     * Check if there is another element in the comment list
     *
     * @return boolean $hasNext
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function hasNext() {
    	
    	if (!$this->has_next) {
	    		
    		$comment = new WPComment($this->getPost());
	    		
    		$this->object = $comment->loadFromList($this->current, $this->status);
            
            if (!is_null($this->object)) {
            	
            	$this->has_next = true;
            	
            }
    	
    	}
    	
    	return $this->has_next;
    	
    }
    
    /**
     * Get next element in the comment list
     *
     * @return WPComment $next
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getNext() {
    		
    	if ($this->has_next || $this->hasNext()) {
        	
        	$this->current++;
        	
        	$this->has_next = false;
    		
    		return $this->object;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Get total items
     *
     * @return  int  $comment_total
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getTotal() {
    	
    	if ($this->comment_total == -1) $this->loadCommentCount();
    	
    	return $this->comment_total;
    	
    }
    
    /**
     * Get approved
     *
     * @return  int  $comment_approved
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getApproved() {
    	
    	if ($this->comment_approved == -1) $this->loadCommentCount();
    	
    	return $this->comment_approved;
    	
    }
    
    /**
     * Get spam
     *
     * @return int $comment_spam
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getSpam() {
    	
    	if ($this->comment_spam == -1) $this->loadCommentCount();
    	
    	return $this->comment_spam;
    	
    }
    
    /**
     * Get awaiting
     *
     * @return  int  $comment_awaiting
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getAwaiting() {
    	
    	if ($this->comment_awaiting == -1) $this->loadCommentCount();
    	
    	return $this->comment_awaiting;
    	
    }
    
    /**
     * Get total items
     *
     * @return  int  $count
     */
    public function getLength() {
    	
    	return $this->getTotal();
    	
    }
    
    /**
     * Get comment count for current post
     *
     * @return WPCommentIterator $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function loadCommentCount() {
    	
    	if ($this->getPost()->getID() > 0) {
    		
	    	try {
	    		
	            $count = $this->getWordpress()->sendMessage("wp.getCommentCount", array(
		            $this->getPost()->getID()
	            ), $this->getBlog());
	            
	            $this->comment_approved = intval($count['approved']);
	            
	            $this->comment_awaiting = intval($count['awaiting_moderation']);
	            
	            $this->comment_spam     = intval($count['spam']);
	            
	            $this->comment_total    = intval($count['total_comments']);
	            
	            $this->count            = intval($count['total_comments']);
            
	    	} catch (WPException $wpe) {
	    		
	    		throw new WPException("Unable to retrieve comment count (".$wpe->getMessage().")");
	    		
	    	}
	            
    	}
    	
    	return $this;
    	
    }
    
}