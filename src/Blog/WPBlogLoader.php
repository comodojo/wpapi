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
abstract class WPBlogLoader extends WPBlogDataSetter {
	
    /**
     * Load blog data
     *
     * @param  array        $data Info about blog
     *
     * @return WPBlogLoader $this
     */
    public function loadData($data) {
    	
    	if (!isset($data['blogid'])) return null;
        
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
    		'xmlrpc'   => $this->getEndPoint(),
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
     * Load supported formats
     *
     * @return WPBlogLoader $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadPostFormats() {
    	
    	$this->supportedFormats = $this->loadAllowedData("wp.getPostFormats");
    	
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
    		
        $types = $this->loadAllowedData("wp.getPostTypes");
        
        foreach ($types as $name => $type) {
        
        	$this->supportedTypes[$name] = $type['label'];
        	
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
    	
    	$this->supportedPostStatus = $this->loadAllowedData("wp.getPostStatusList");
    	
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
    	
    	$this->supportedCommentStatus = array_keys($this->loadAllowedData("wp.getCommentStatusList"));
    	
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
            
        $this->options = $this->loadAllowedData("wp.getOptions");
    	
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
    
    /**
     * Load allowed data
     *
     * @param  string $method The method to call in order to get infos
     *
     * @return array  $info
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadAllowedData($method) {
    	
    	try {
    	
	    	return (array) $this->getWordpress()->sendMessage($method, array(), $this);
            
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    }
    
}