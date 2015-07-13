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
class WPBlog extends WPBlogTerms {
    
    /**
     * Get user's profile
     *
     * @return WPProfile $profile
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getProfile() {
    	
    	if (is_null($this->profile)) {
    	
	    	try {
	            
	            $user = $this->getWordpress()->sendMessage("wp.getProfile", array(
	                array('user_id')
	            ), $this);
	            
	            $this->profile = new WPProfile(
	            	$this,
	            	$user['user_id']
	            );
	            
	    	} catch (WPException $wpe) {
	    		
	    		throw new WPException("Unable to retrieve user's profile (".$wpe->getMessage().")");
	    		
	    	}
	    	
    	}
    	
    	return $this->profile;
    	
    }
    
    /**
     * Get user's information by id
     *
     * @param  int    $id User's ID
     *
     * @return WPUser $user
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUserByID($id) {
    	
    	return new WPUser($this, $id);
    	
    }
    
    /**
     * Get users by role
     *
     * @param  string         $role User's role
     *
     * @return WPUserIterator $userIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUsersByRole($role) {
    	
    	return $this->getUsers($role);
    	
    }
    
    /**
     * Get authors
     *
     * @return WPUserIterator $userIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getAuthors() {
    	
    	return $this->getUsers(null, "authors");
    	
    }
    
    /**
     * Get user list
     *
     * @param  string  $role    User's role
     * @param  string  $who     Who is
     * @param  int     $limit   Number of users retrieved
     * @param  int     $offset  Number of users to skip
     * @param  string  $orderby Field to use for ordering
     * @param  string  $order   Type of ordering (asd or desc)
     *
     * @return WPUserIterator $userIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getUsers($role = null, $who = null, $limit = null, $offset = 0, $orderby = "username", $order = "ASC") {
    	
    	$users  = array();
    	
    	$filter = array(
        	"offset"  => $offset,
        	"orderby" => $orderby,
        	"order"   => $order
        );
        
        if (!is_null($role)) {
        	$filter["role"] = $role;
        }
        
        if (!is_null($who)) {
        	$filter["who"] = $who;
        }
        
        if (!is_null($limit)) {
        	$filter["limit"] = intval($limit);
        }
    	
    	try {
    		
            $users_list = $this->getWordpress()->sendMessage("wp.getUsers", array(
                $filter,
                array('user_id')
            ), $this);
            
            foreach ($users_list as $user) {
            	
            	array_push(
            		$users,
            		$user['user_id']
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve user's informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return new WPUserIterator($this, $users);
    	
    }
    
    /**
     * Get post's information by id
     *
     * @param  int    $id Post's ID
     *
     * @return WPPost $post
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostByID($id) {
    	
    	return new WPPost($this, $id);
    	
    }
    
    /**
     * Get pages
     *
     * @return WPPostIterator $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPages() {
    	
    	return $this->getPosts("page");
    	
    }
    
    /**
     * Get latest posts
     * 
     * @param  int            $count  Number of posts retrieved
     *
     * @return WPPostIterator $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getLatestPosts($count = 10) {
    	
    	return $this->getPosts("post", "publish", $count);
    	
    }
    
    /**
     * Get post list
     *
     * @param  string  $type    Type of posts
     * @param  string  $status  Status of posts
     * @param  int     $number  Number of posts retrieved
     * @param  int     $offset  Number of posts to skip
     * @param  string  $orderby Field to use for ordering
     * @param  string  $order   Type of ordering (asd or desc)
     *
     * @return WPPostIterator $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPosts($type = null, $status = null, $number = null, $offset = 0, $orderby = "post_date", $order = "DESC") {
    	
    	$posts  = array();
    	
    	$filter = array(
        	"offset"  => $offset,
        	"orderby" => $orderby,
        	"order"   => $order
        );
        
        if (!is_null($type)) {
        	$filter["post_type"] = $type;
        }
        
        if (!is_null($status)) {
        	$filter["post_status"] = $status;
        }
        
        if (!is_null($number)) {
        	$filter["number"] = intval($number);
        }
    	
    	try {
    		
            $post_list = $this->getWordpress()->sendMessage("wp.getPosts", array(
                $filter,
                array('post_id')
            ), $this);
            
            foreach ($post_list as $post) {
            	
            	array_push(
            		$posts,
		            $post['post_id']
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return new WPPostIterator($this, $posts);
    	
    }
    
    /**
     * Get post list by category
     *
     * @param  string $category Category name or description
     * @param  string $number   Number of posts (optional)
     *
     * @return WPPostIterator $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostsByCategory($category, $number = null) {
    	
    	return $this->getPostsByTerm("category", $category, $number);
    	
    }
    
    /**
     * Get post list by tag
     *
     * @param  string $tag    Tag name or description
     * @param  string $number Number of posts (optional)
     *
     * @return WPPostIterator $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostsByTag($tag, $number = null) {
    	
    	return $this->getPostsByTerm("post_tag", $tag, $number);
    	
    }
    
    /**
     * Get post list by term
     *
     * @param  string $taxonomy Taxonomy name
     * @param  string $value    Term name or description
     * @param  int    $number   Number of posts to fetch
     *
     * @return WPPostIterator $postIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getPostsByTerm($taxonomy, $value, $number = null) {
    	
    	$posts  = array();
    	
    	$filter = array(
        	"offset"      => 0,
        	"orderby"     => "post_date",
        	"order"       => "DESC",
        	"post_type"   => "post",
        	"post_status" => "publish",
        );
        
        if (!is_null($number)) {
        	$filter["number"] = intval($number);
        }
    	
    	try {
    		
            $post_list = $this->getWordpress()->sendMessage("wp.getPosts", array(
                $filter,
                array('post_id', 'terms')
            ), $this);
            
            foreach ($post_list as $post) {
            	
            	foreach ($post['terms'] as $term) {
	            	
	            	if ($term['taxonomy'] == $taxonomy) {
	            		
	            		if ($term['name'] == $value || $term['description'] == $value) {
	            			
			            	array_push(
			            		$posts,
			            		$post['post_id']
			            	);
			            	
			            	break;
	            			
	            		}
	            	
	            	}
	            	
            	}
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to retrieve post informations (".$wpe->getMessage().")");
    		
    	}
    	
    	return new WPPostIterator($this, $posts);
    	
    }
    
    /**
     * Get media library
     * 
     * @param  string $mime The mime-type of the media you want to fetch
     * @param  int    $post Post ID
     *
     * @return WPMediaIterator $mediaIterator
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function getMediaLibrary($mime = null, $post = 0) {
    	
    	return $this->getMediaIterator($this, $post, $mime);
    	
    }
    
    /**
     * Load taxonomy list
     *
     * @return WPBlog $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    protected function loadTaxonomies() {
    	
    	$tax_list = $this->loadAllowedData("wp.getTaxonomies");
            
        foreach ($tax_list as $taxonomy) {
        	
        	$tax = new WPTaxonomy($this);
        	
        	$tax->loadData($taxonomy);
        	
        	array_push(
        		$this->taxonomies,
        		$tax
        	);
        	
        }
    	
    	return $this;
    	
    }
    
}