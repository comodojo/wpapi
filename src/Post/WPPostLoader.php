<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class is a Wordpress post data loader
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
abstract class WPPostLoader extends WPPostDataSetter {
	
    /**
     * Load data for a post
     *
     * @param  array        $data Post data
     *
     * @return WPPostLoader $this
     */
    public function loadData($data) {
    	
    	$this->resetData();
			
		$this->setID($data['post_id']);
		
		$this->setTitle($data['post_title']);
		
		$this->setCreationDate((is_numeric($data['post_date']))?$data['post_date']:strtotime($data['post_date']));
		
		$this->setLastModifiedDate((is_numeric($data['post_modified']))?$data['post_modified']:strtotime($data['post_modified']));
		
		$this->setStatus($data['post_status']);
		
		$this->setType($data['post_type']);
		
		$this->setFormat($data['post_format']);
		
		$this->setName($data['post_name']);
		
		$this->setAuthor(new WPUser($this->getBlog(), $data['post_author']));
		
		$this->setPassword($data['post_password']);
		
		$this->setExcerpt($data['post_excerpt']);
		
		$this->setContent($data['post_content']);
		
		$this->setParent($data['post_parent']);
		
		$this->setMimeType($data['post_mime_type']);
		
		$this->setLink($data['link']);
		
		$this->setGUID($data['guid']);
		
		$this->setMenuOrder(intval($data['menu_order']));
		
		$this->setCommentStatus($data['comment_status']);
		
		$this->setPingStatus($data['ping_status']);
		
		$this->setSticky(filter_var($data['sticky'], FILTER_VALIDATE_BOOLEAN));
        
        if (isset($data['custom_fields'])) {
        	
        	foreach ($data['custom_fields'] as $value)
        		$this->setCustomField($value['key'], $value['value']);
        	
        }
        
        if (isset($data['enclosure'])) {
        	
        	$this->setEnclosureURL($data['enclosure']['url']);
        	
        	$this->setEnclosureLength($data['enclosure']['length']);
        	
        	$this->setEnclosureType($data['enclosure']['type']);
        	
        }
		
		if ( isset($data['post_thumbnail']['attachment_id']) ) {
			
			$thumbnail = new WPMedia($this->getBlog());
			
			$thumbnail->loadData($data['post_thumbnail']);
			
			$this->setThumbnail($thumbnail);
			
		}
		
		foreach ($data['terms'] as $term) {
			
			$taxonomy = $this->getBlog()->getTaxonomy($term['taxonomy']);
			
			$termObj  = new WPTerm($taxonomy);
			
			$termObj->loadData($term);
			
			$this->addTerm($termObj);
			
		}
    	
    	return $this;
        
    }
    
    /**
     * Get post data
     *
     * @return array $data
     */
    public function getData() {
    	 	
    	$data = array(
    		'post_type'      => $this->getType(),
    		'post_status'    => $this->getStatus(),
    		'post_title'     => $this->getTitle(),
    		'post_author'    => $this->getAuthor()->getID(),
    		'post_content'   => $this->getContent(),
    		'post_date'      => $this->getCreationDate(),
    		'post_format'    => $this->getFormat(),
    		'comment_status' => $this->getCommentStatus(),
    		'menu_order'     => $this->getMenuOrder(),
    		'ping_status'    => $this->getPingStatus(),
    		'sticky'         => ($this->isSticky())?1:0
    	);
    	
    	if (count($this->custom) > 0) {
    		
    		$data['custom_fields'] = $this->getCustomFields();
    		
    	}
    	
    	$parent = $this->getParent();
    	if (!is_null($parent)) {
    		
    		$data['post_parent'] = $parent;
    		
    	}
    	
    	if (!is_null($this->getThumbnail())) {
    		
    		$data['post_thumbnail'] = $this->getThumbnail()->getID();
    		
    	}
    	
    	$enclosure = $this->getEnclosure();
    	if (!empty($enclosure)) {
    		
    		$data['enclosure'] = $enclosure;
    		
    	}
    	
    	if ($this->getPassword() != "") {
    		
    		$data['post_password'] = $this->getPassword();
    		
    	}
    	
    	if ($this->getExcerpt() != "") {
    		
    		$data['post_excerpt'] = $this->getExcerpt();
    		
    	}
    	
    	if (count($this->getTerms()) > 0) {
    		
    		$data['terms'] = array();
    		
    		foreach ($this->getTerms() as $term) {
    			
    			$key = $term->getTaxonomy()->getName();
    			
    			if (!isset($data['terms'][$key])) $data['terms'][$key] = array();
    			
    			array_push($data['terms'][$key], $term->getID());
    			
    		}
    		
    		
    	}
    	
    	return $data;
    		
    }
	
    /**
     * Reset data of the object, it can still be used calling the loadFromID method
     *
     * @return  WPPostLoader $this
     */
    protected function resetData() {
			
		$this->setID(0);
		
		$this->setTitle("");
		
		$this->setCreationDate(0);
		
		$this->setLastModifiedDate(0);
		
		$this->setStatus("draft");
		
		$this->setType("post");
		
		$this->setFormat("standard");
		
		$this->setName("");
		
		$this->setAuthor($this->getBlog()->getProfile());
		
		$this->setPassword("");
		
		$this->setExcerpt("");
		
		$this->setContent("");
		
		$this->setParent();
		
		$this->setMimeType("");
		
		$this->setLink("");
		
		$this->setGUID("");
		
		$this->setMenuOrder("");
		
		$this->setCommentStatus("open");
		
		$this->setPingStatus("open");
		
		$this->setSticky(false);
		
		$this->setThumbnail();
		
		$this->cleanTerms();
		
		$this->cleanCustomFields();
		
        $this->setEnclosureURL("");
        
        $this->setEnclosureLength("");
        
        $this->setEnclosureType("");
    	
    	return $this;
        
    }
    
}