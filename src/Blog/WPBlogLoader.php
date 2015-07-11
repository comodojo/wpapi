<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress blog
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
abstract class WPBlogLoader extends WPBlogData {
	
    /**
     * Load blog data
     *
     * @param  array        $data Info about blog
     *
     * @return WPBlogLoader $this
     */
    public function loadData($data) {
        
        $this->setID(intval($data['blogid']));
        
        $this->setName($data['blogName']);
        
        $this->setURL($data['url']);
        
        $this->setEndPoint($data['xmlrpc']);
        
        $this->setAdmin(filter_var($data['isAdmin'], FILTER_VALIDATE_BOOLEAN));
        
        if (!$this->checkEndPoint()) {
        	
        	$this->setID(-1);
        	
        }
        
        return $this;
        
    }
	
    /**
     * Get info about blog
     *
     * @return array $data Info about blog
     */
    public function getData() {
    	
    	return array(
    		'blogid'   => $this->getID(),
    		'blogName' => $this->getName(),
    		'url'      => $this->getURL(),
    		'url'      => $this->getEndPoint(),
    		'isAdmin'  => $this->isAdmin()
    	);
        
    }
    
    /**
     * Check if the endpoint is valid
     *
     * @return  boolean  $valid
     */
    public function checkEndPoint() {
    	
    	try {
        
            $this->getWordpress()->sendMessage("wp.getProfile", array(), $this);
            
    	} catch (WPException $wpe) {
    		
    		return false;
    		
    	}
    	
    	return true;
    	
    }
    
    /**
     * Load taxonomy list
     *
     * @return WPBlogLoader $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadTaxonomies() {
    	
    	try {
    		
            $tax_list = $this->getWordpress()->sendMessage("wp.getTaxonomies", array(), $this);
            
            foreach ($tax_list as $taxonomy) {
            	
            	$tax = new WPTaxonomy($this);
            	
            	$tax->loadData($taxonomy);
            	
            	array_push(
            		$this->taxonomies,
            		$tax
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve taxonomy informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
	
    /**
     * Load supported formats
     *
     * @return WPBlogLoader $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadPostFormats() {
    	
    	try {
            
            $this->supportedFormats = $this->getWordpress()->sendMessage("wp.getPostFormats", array(), $this);
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post formats (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load supported post types
     *
     * @return WPBlogLoader $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadPostTypes() {
    	
    	try {
    		
            $types = $this->getWordpress()->sendMessage("wp.getPostTypes", array(), $this);
            
            foreach ($types as $name => $type) {
            
            	$this->supportedTypes[$name] = $type['label'];
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post types (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load supported post status
     *
     * @return WPBlogLoader $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadPostStatus() {
    	
    	try {
            
            $this->supportedPostStatus = $this->getWordpress()->sendMessage("wp.getPostStatusList", array(), $this);
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post status (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load supported comment status
     *
     * @return WPBlogLoader $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadCommentStatus() {
    	
    	try {
            
            $status = $this->getWordpress()->sendMessage("wp.getCommentStatusList", array(), $this);
            
            foreach ($status as $s) {
            
            	array_push($this->supportedCommentStatus, $s);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve comment status (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load blog options
     *
     * @return WPBlogLoader $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadBlogOptions() {
    	
    	try {
            
            $options = $this->getWordpress()->sendMessage("wp.getOptions", array(), $this);
            
            foreach ($options as $name => $option) {
            	
            	$this->options[$name] = array(
            		"desc"     => $option['desc'],
            		"value"    => $option['value'],
            		"readonly" => filter_var($option['readonly'], FILTER_VALIDATE_BOOLEAN)
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve blog options (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
        
    }
	
    /**
     * Load blog terms
     *
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadBlogTerms() {
    	
    	try {
    	
    		if (empty($this->taxonomies)) $this->loadTaxonomies();
			
			$this->tags = $this->getTaxonomy("post_tag")->getTerms();
			
			$this->categories = $this->getTaxonomy("category")->getTerms();
            
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this;
        
    }
    
}