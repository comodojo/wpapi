<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

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
class WPTerm {
	
	/**
     * Taxonomy reference
     *
     * @var Object
     */
	private $taxonomy = null;
	
	/**
     * Term ID
     *
     * @var int
     */
	private $id = -1;
	
	/**
     * Term name
     *
     * @var string
     */
	private $name = "";
	
	/**
     * Term slug
     *
     * @var string
     */
	private $slug = "";
	
	/**
     * Term group
     *
     * @var string
     */
	private $group = "";
	
	/**
     * Term taxonomy ID
     *
     * @var int
     */
	private $term_taxonomy_id = -1;
	
	/**
     * Term description
     *
     * @var string
     */
	private $description = "";
	
	/**
     * Term parent
     *
     * @var int
     */
	private $parent = -1;
	
	/**
     * Term post count
     *
     * @var int
     */
	private $count = 0;
	
    /**
     * Class constructor
     *
     * @param   Object  $taxonomy Reference to a taxonomy object
     * @param   int     $id       Term ID (optional)
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($taxonomy, $id=-1) {
    	
        if ( is_null($taxonomy) || is_null($taxonomy->getWordpress()) || !$taxonomy->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access terms informations");
        	
        }
        
        $this->taxonomy = $taxonomy;
        
        $this->id       = intval($id);
        
        if ($id > -1) {
        	
        	try {
        		
        		$this->loadFromID($id);
        		
        	} catch (WPException $wpe) {
        		
        		throw $wpe;
        		
        	}
        	
        }
        
    }
	
    /**
     * Load term from ID
     *
     * @param   int    $id Term ID
     *
     * @return  Object $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromID($id) {
    	
    	$this->resetData();
    	
    	try {
    		
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.getTerm", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $taxonomy->getName(),
                intval($id)
            ));
            
            $term = $rpc_client->send();
            
            $this->loadData($term);
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrieve term informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrieve term informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrieve term informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrieve term informations - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
    /**
     * Get wordpress reference
     *
     * @return  Object  $wordpress
     */
    public function getWordpress() {
    	
    	return $this->getBlog()->getWordpress();
    	
    }
    
    /**
     * Get user's blog
     *
     * @return  Object  $blog
     */
    public function getBlog() {
    	
    	return $this->getTaxonomy()->getBlog();
    	
    }
    
    /**
     * Get taxonomy reference
     *
     * @return  Object  $this->taxonomy
     */
    public function getTaxonomy() {
    	
    	return $this->taxonomy;
    	
    }
    
    /**
     * Get ID
     *
     * @return  int  $this->id
     */
    public function getID() {
    	
    	return $this->id;
    	
    }
    
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
    	
    	if (!is_null($this->parent) && $this->parent > -1) {
    		
    		try {
    		
    			$parent = new WPTerm($this->getTaxonomy(), $this->parent);
    			
    		} catch (WPException $wpe) {
    			
    			throw $wpe;
    			
    		}
    		
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
	
    /**
     * Save term
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function save() {
    	
    	try {
    	
	    	if ($this->getID() == -1) {
	    		
	    		$this->createTerm();
	    		
	    	} else {
	    		
	    		$this->editTerm();
	    		
	    	}
	    	
    	} catch (WPException $wpe) {
    		
    		throw $wpe;
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Create term
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function createTerm() {
    	
    	$content = $this->getTermData();
    	
    	try {
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.newTerm", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $content
            ));
            
            $id = $rpc_client->send();
            
            $this->loadFromID($id);
    		
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to create term - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to create term - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to create term - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to create term - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Edit term
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    private function editTerm() {
    	
    	$content = $this->getTermData();
    	
    	try {
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.editTerm", array( 
                $this->getBlog()->getID(),
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $this->getID(),
                $content
            ));
            
            $rpc_client->send();
    		
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to edit term - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to edit term - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to edit term - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to edit term - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $this;
    		
    }
    
    /**
     * Load term data
     *
     * @return  array  $data
     * 
     * @throws \Comodojo\Exception\WPException
     */
    private function getTermData() {
    	  	
    	$data = array(
    		'taxonomy'    => $this->getTaxonomy()->getName(),
    		'name'        => $this->name,
    		'description' => $this->description
    	);
    	
    	if (!is_null($this->getParent())) {
    		
    		$data['parent'] = $this->parent;
    		
    	}
    	
    	if (!empty($this->slug)) {
    		
    		$data['slug'] = $this->slug;
    		
    	}
    	
    	return $data;
    		
    }
    
    /**
     * Delete term
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function delete() {
    	
    	try {
            $rpc_client = new RpcClient($this->blog->getEndPoint());
            
            $rpc_client->addRequest("wp.deleteTerm", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $this->getTaxonomy()->getName(),
                $this->getID()
            ));
            
            $return = $rpc_client->send();
    		
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to delete term - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to delete term - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to delete term - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to delete term - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	$this->resetData();
    	
    	return filter_var($return, FILTER_VALIDATE_BOOLEAN);
    	
    }
	
    /**
     * Reset data of the object, it can still be used calling the loadFromID method
     *
     * @return  Object  $this
     */
    
    private function resetData() {
			
		$this->id               = -1;
		
		$this->name             = "";
		
		$this->slug             = "";
		
		$this->group            = "";
		
		$this->term_taxonomy_id = -1;
		
		$this->description      = "";
		
		$this->parent           = -1;
		
		$this->count            = 0;
    	
    	return $this;
        
    }
	
    /**
     * Load term data
     *
     * @param   array   $term
     *
     * @return  Object  $this
     */
    
    public function loadData($term) {
		
        $this->id               = intval($term['term_id']);
    
        $this->name             = $term['name'];
        
        $this->slug             = $term['slug'];
        
        $this->group            = $term['term_group'];
        
        $this->term_taxonomy_id = intval($term['term_taxonomy_id']);
        
        $this->description      = $term['description'];
        
        $this->parent           = intval($term['parent']);
        
        $this->count            = intval($term['count']);
    	
    	return $this;
        
    }
    
}