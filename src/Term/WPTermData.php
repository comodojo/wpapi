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
abstract class WPTermData extends WPTaxonomyObject {
	
	/**
     * Term name
     *
     * @var string
     */
	protected $name = "";
	
	/**
     * Term slug
     *
     * @var string
     */
	protected $slug = "";
	
	/**
     * Term group
     *
     * @var string
     */
	protected $group = "";
	
	/**
     * Term taxonomy ID
     *
     * @var int
     */
	protected $term_taxonomy_id = 0;
	
	/**
     * Term description
     *
     * @var string
     */
	protected $description = "";
	
	/**
     * Term parent
     *
     * @var int
     */
	protected $parent = 0;
	
	/**
     * Term post count
     *
     * @var int
     */
	protected $count = 0;
    
    /**
     * Get name
     *
     * @return  string  $this->name
     */
    public function getName() {
    	
    	return $this->name;
    	
    }
    
    /**
     * Set name
     *
     * @param   string  $name Term name
     *
     * @return  Object  $this
     */
    public function setName($name) {
    	
    	$this->name = $name;
    	
    	return $this;
    	
    }
    
    /**
     * Get slug
     *
     * @return  string  $this->slug
     */
    public function getSlug() {
    	
    	return $this->slug;
    	
    }
    
    /**
     * Set slug
     *
     * @param   string  $slug Term slug
     *
     * @return  Object  $this
     */
    public function setSlug($slug) {
    	
    	$this->slug = $slug;
    	
    	return $this;
    	
    }
    
    /**
     * Get description
     *
     * @return  string  $this->description
     */
    public function getDescription() {
    	
    	return $this->description;
    	
    }
    
    /**
     * Set description
     *
     * @param   string  $description Term description
     *
     * @return  Object  $this
     */
    public function setDescription($description) {
    	
    	$this->description = $description;
    	
    	return $this;
    	
    }
    
    /**
     * Get term taxonomy id
     *
     * @return  int  $this->term_taxonomy_id
     */
    public function getTaxonomyRelationID() {
    	
    	return $this->term_taxonomy_id;
    	
    }
    
    /**
     * Get parent
     *
     * @return  Object  $parent
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getParent() {
    	
    	$parent = null;
    	
    	if (!is_null($this->parent) && $this->parent > 0) {
    		
    		$parent = new WPTerm($this->getTaxonomy(), $this->parent);
    		
    	}
    	
    	return $parent;
    	
    }
    
    /**
     * Set parent
     *
     * @param   mixed   $parent Term parent (it accepts a WPTerm object or a numeric id)
     *
     * @return  Object  $this
     */
    public function setParent($parent) {
    	
    	if (is_numeric($parent)) {
    		
    		$this->parent = intval($parent);
    		
    	} else {
    		
    		$this->parent = $parent->getID();
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get count
     *
     * @return  int  $this->count
     */
    public function getCount() {
    	
    	return $this->count;
    	
    }
    
}