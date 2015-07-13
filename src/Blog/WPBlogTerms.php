<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class add terms support to blog data object
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
abstract class WPBlogTerms extends WPBlogLoader {
    
    /**
     * Get taxonomies
     *
     * @return  array  $taxonomies
     */
    public function getTaxonomies() {
    	
    	if (empty($this->taxonomies)) $this->loadTaxonomies();
    	
    	return $this->taxonomies;
    	
    }
    
    /**
     * Get taxonomy by name
     *
     * @param   string $taxonomy taxonomy name
     *
     * @return  WPTaxonomy $taxonomy
     */
    public function getTaxonomy($taxonomy) {
    	
    	foreach ($this->getTaxonomies() as $t) {
    		
    		if ($t->getName() == $taxonomy) return $t;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Has taxonomy
     *
     * @param   string  $taxonomy Taxonomy name
     *
     * @return  boolean $hasTaxonomy
     */
    public function hasTaxonomy($taxonomy) {
    	
    	foreach ($this->getTaxonomies() as $t) {
    		
    		if ($t->getName() == $taxonomy) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Get tags
     *
     * @return  array  $tags
     */
    public function getTags() {
    	
    	if (empty($this->tags)) $this->loadBlogTerms();
    	
    	return $this->tags;
    	
    }
    
    /**
     * Get tag term by name
     *
     * @param   string $tag Tag name
     *
     * @return  WPTerm $tag
     */
    public function getTag($tag) {
    	
    	foreach ($this->getTags() as $t) {
    		
    		if ($t->getName() == $tag) return $t;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Has tag
     *
     * @param   string  $tag Tag name
     *
     * @return  boolean $hasTag
     */
    public function hasTag($tag) {
    	
    	foreach ($this->getTags() as $t) {
    		
    		if ($t->getName() == $tag) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Has tag
     *
     * @param   Object $tag WPTerm object
     *
     * @return  WPBlogTerms $this
     */
    public function addTag($tag) {
    	
    	if (!$this->hasTag($tag->getName())) {
    		
    		array_push($this->tags, $tag);
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get categories
     *
     * @return  array  $categories
     */
    public function getCategories() {
    	
    	if (empty($this->categories)) $this->loadBlogTerms();
    	
    	return $this->categories;
    	
    }
    
    /**
     * Get category by name
     *
     * @param   string $category Tag name
     *
     * @return  WPTerm $category
     */
    public function getCategory($category) {
    	
    	foreach ($this->getCategories() as $c) {
    		
    		if ($c->getName() == $category) return $c;
    		
    	}
    	
    	return null;
    	
    }
    
    /**
     * Has category
     *
     * @param   string  $category Category name
     *
     * @return  boolean $hasCategory
     */
    public function hasCategory($category) {
    	
    	foreach ($this->getCategories() as $c) {
    		
    		if ($c->getName() == $category) return true;
    		
    	}
    	
    	return false;
    	
    }
    
    /**
     * Add category
     *
     * @param   Object $category WPTerm object
     *
     * @return  WPBlogTerms $this
     */
    public function addCategory($category) {
    	
    	if (!$this->hasCategory($category->getName())) {
    		
    		array_push($this->categories, $category);
    		
    	}
    	
    	return $this;
    	
    }
    
}