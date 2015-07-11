<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class add terms support to post data object
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
abstract class WPPostDataTerms extends WPPostLoader {
    
    /**
     * Get categories
     *
     * @return array $categories
     */
    public function getCategories() {
    	
    	$categories = array();
    	
    	foreach ($this->terms as $term) {
    		
    		if ($term->getTaxonomy()->getName() == "category") array_push($categories, $term);
    		
    	}
    	
    	return $categories;
    	
    }
    
    /**
     * Remove category
     *
     * @param  string     $category Category name
     *
     * @return WPPostData $this
     */
    public function removeCategory($category) {
    	
    	foreach ($this->getCategories() as $c) {
    		
    		if ($c->getName() == $category) $this->removeTerm($c);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Add category
     *
     * @param  string     $category Category name
     *
     * @return WPPostData $this
     */
    public function addCategory($category) {
    	
    	if ($this->getBlog()->hasCategory($category)) {
    		
    		$term = $this->getBlog()->getCategory($category);
    		
    	} else {
    		
    		$taxonomy = $this->getBlog()->getTaxonomy("category");
    		$term = new WPTerm($taxonomy);
    		$term->setName($category)->save();
    		
    		$this->getBlog()->addCategory($term);
    	}
    	
    	return $this->addTerm($term);
    	
    }
    
    /**
     * Has category
     *
     * @param  string  $category Category name
     *
     * @return boolean $hasCategory
     */
    public function hasCategory($category) {
    	
    	return $this->hasTerm($category);
    	
    }
    
    /**
     * Get tags
     *
     * @return array $tags
     */
    public function getTags() {
    	
    	$tags = array();
    	
    	foreach ($this->terms as $term) {
    		
    		if ($term->getTaxonomy()->getName() == "post_tag") array_push($tags, $term);
    		
    	}
    	
    	return $tags;
    	
    }
    
    /**
     * Has tag
     *
     * @param  string  $tag Tag name
     *
     * @return boolean $hasTag
     */
    public function hasTag($tag) {
    	
    	return $this->hasTerm($tag);
    	
    }
    
    /**
     * Remove tag
     *
     * @param  string     $tag Tag name
     *
     * @return WPPostData $this
     */
    public function removeTag($tag) {
    	
    	foreach ($this->getTags() as $t) {
    		
    		if ($t->getName() == $tag) $this->removeTerm($t);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Add tag
     *
     * @param  string     $tag Tag name
     *
     * @return WPPostData $this
     */
    public function addTag($tag) {
    	
    	if ($this->getBlog()->hasTag($tag)) {
    		
    		$term = $this->getBlog()->getTag($tag);
    		
    	} else {
    		
    		$taxonomy = $this->getBlog()->getTaxonomy("post_tag");
    		$term = new WPTerm($taxonomy);
    		$term->setName($tag)->save();
    		
    		$this->getBlog()->addTag($term);
    	}
    	
    	return $this->addTerm($term);
    	
    }
    
    /**
     * Get terms
     *
     * @return array $terms
     */
    public function getTerms() {
    	
    	return $this->terms;
    	
    }
    
    /**
     * Add term
     *
     * @param  WPTerm     $term Term reference
     *
     * @return WPPostData $this
     */
    public function addTerm($term) {
    	
    	if (!$this->hasTerm($term)) {
    		
    		array_push($this->terms, $term);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Remove term
     *
     * @param  WPTerm     $term Term reference
     *
     * @return WPPostData $this
     */
    public function removeTerm($term) {
    	
    	foreach ($this->terms as $id => $t) {
    		
    		if ($t->getID() == $term) unset($this->terms[$id]);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Has term
     *
     * @param  mixed   $term Term ID or WPTerm object
     *
     * @return boolean $hasTerm
     */
    public function hasTerm($term) {
    	
    	if (is_numeric($term)) {
    		
    		$term = intval($term);
    		
    	} else {
    		
    		$term = $term->getID();
    		
    	}
    	
    	foreach ($this->terms as $t) {
    		
    		if ($t->getID() == $term) {
    			
    			return true;
    			
    		}
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Clean all terms
     *
     * @return WPPostData $this
     */
    public function cleanTerms() {
    	
    	$this->terms = array();
    	
    	return false;
    	
    }
    
}