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
abstract class WPTaxonomyData extends WPBlogObject {
	
	/**
     * Taxonomy id (eg: 'category', 'post_tag', etc...)
     *
     * @var string
     */
	protected $id = "";
	
	/**
     * Taxonomy name (eg: 'category', 'post_tag', etc...)
     *
     * @var string
     */
	protected $name = "";
	
	/**
     * Taxonomy label
     *
     * @var string
     */
	protected $label = "";
	
	/**
     * Whether the taxonomy is hierarchical or not
     *
     * @var boolean
     */
	protected $hierarchical = false;
	
	/**
     * Public or private
     *
     * @var boolean
     */
	protected $public_tax = true;
	
	/**
     * Show UI
     *
     * @var boolean
     */
	protected $show_ui = true;
	
	/**
     * Whether the taxonomy is built-in or user-created
     *
     * @var boolean
     */
	protected $builtin = true;
	
	/**
     * Taxonomy labels
     *
     * @var array
     */
	protected $labels = array();
	
	/**
     * Taxonomy cap
     *
     * @var array
     */
	protected $cap = array();
	
	/**
     * Taxonomy object type
     *
     * @var array
     */
	protected $object_type = array();
	
    /**
     * Get taxonomy name
     *
     * @return  string  $this->name
     */
    public function getName() {
    	
    	return $this->name;
    	
    }
	
    /**
     * Get taxonomy label
     *
     * @return  string  $this->label
     */
    public function getLabel() {
    	
    	return $this->label;
    	
    }
	
    /**
     * Whether the taxonomy is hierarchical or not 
     *
     * @return  boolean  $this->hierarchical
     */
    public function isHierarchical() {
    	
    	return $this->hierarchical;
    	
    }
	
    /**
     * Public or private
     *
     * @return  boolean  $this->public_tax
     */
    public function isPublic() {
    	
    	return $this->public_tax;
    	
    }
	
    /**
     * Show UI
     *
     * @return  boolean  $this->show_ui
     */
    public function isShowUI() {
    	
    	return $this->show_ui;
    	
    }
	
    /**
     * Whether the taxonomy is built-in or user-created
     *
     * @return  boolean  $this->builtin
     */
    public function isBuiltIn() {
    	
    	return $this->builtin;
    	
    }
	
    /**
     * Taxonomy labels
     *
     * @return  array  $this->labels
     */
    public function getLabels() {
    	
    	return $this->labels;
    	
    }
	
    /**
     * Taxonomy cap
     *
     * @return  array  $this->cap
     */
    public function getCapabilities() {
    	
    	return $this->cap;
    	
    }
	
    /**
     * Taxonomy object type
     *
     * @return  array  $this->object_type
     */
    public function getObjectType() {
    	
    	return $this->object_type;
    	
    }
    
}