<?php namespace Comodojo\WP;
use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress taxonomy
 *
 * It allows to retrive taxonomy informations.
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
class WPTaxonomy {
	
	/**
     * Wordpress blog
     *
     * @var Object
     */
	private $blog = null;
	
	/**
     * Taxonomy name (eg: 'category', 'post_tag', etc...)
     *
     * @var string
     */
	private $name = "";
	
	/**
     * Taxonomy label
     *
     * @var string
     */
	private $label = "";
	
	/**
     * Whether the taxonomy is hierarchical or not
     *
     * @var boolean
     */
	private $hierarchical = false;
	
	/**
     * Public or private
     *
     * @var boolean
     */
	private $public_tax = true;
	
	/**
     * Show UI
     *
     * @var boolean
     */
	private $show_ui = true;
	
	/**
     * Whether the taxonomy is built-in or user-created
     *
     * @var boolean
     */
	private $builtin = true;
	
	/**
     * Taxonomy labels
     *
     * @var array
     */
	private $labels = array();
	
	/**
     * Taxonomy cap
     *
     * @var array
     */
	private $cap = array();
	
	/**
     * Taxonomy object type
     *
     * @var array
     */
	private $object_type = array();
	
    /**
     * Class constructor
     *
     * @param   Object  $blog     Reference to the wordpress blog
     * @param   int     $name     Taxonomy name (optional)
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($blog, $name="") {
    	
        if ( is_null($blog) || is_null($blog->getWordpress()) || !$blog->getWordpress()->isLogged() ) {
        	
        	throw new WPException("You must be logged to access taxonomy informations");
        	
        }
        
        $this->blog         = $blog;
        
        $this->name         = $name;
        
        if (empty($name)) {
        	
        	try {
        		
        		$this->loadFromName($name);
        		
        	} catch (WPException $wpe) {
        		
        		throw $wpe;
        		
        	}
        	
        }
        
    }
	
    /**
     * Load taxonomy for name
     *
     * @param   string  $name Taxonomy name
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function loadFromName($name) {
    	
    	try {
    		
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.getTaxonomy", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $name
            ));
            
            $taxonomy = $rpc_client->send();
            
            $this->loadData($taxonomy);
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive taxonomy informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive taxonomy informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive taxonomy informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive taxonomy informations - Generic Exception (".$e->getMessage().")");
    		
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
     * @return  Object  $this->blog
     */
    public function getBlog() {
    	
    	return $this->blog;
    	
    }
	
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
    
    public function getCap() {
    	
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
    
    /**
     * Get terms from this taxonomy
     *
     * @param   string  $search     Restrict to terms with names that contain (case-insensitive) this value
     * @param   boolean $hide_empty Hide empty terms (count=0)
     * @param   int     $number     Number of terms retrived
     * @param   int     $offset     Number of terms to skip
     * @param   string  $orderby    Field to use for ordering
     * @param   string  $order      Type of ordering (asd or desc)
     *
     * @return  array   $terms
     * 
     * @throws \Comodojo\Exception\WPException
     */
    
    public function getTerms($search="", $hide_empty=true, $number = null, $offset = 0, $orderby = "name", $order = "ASC") {
    	
    	$terms  = array();
    	
    	$filter = array(
        	"hide_empty" => $hide_empty,
        	"offset"     => $offset,
        	"orderby"    => $orderby,
        	"order"      => $order
        );
        
        if (!empty($search)) {
        	$filter["search"] = $search;
        }
        
        if (!is_null($number)) {
        	$filter["number"] = intval($number);
        }
        
    	try {
    		
            $rpc_client = new RpcClient($this->getBlog()->getEndPoint());
            
            $rpc_client->addRequest("wp.getTerms", array( 
                $this->getBlog()->getID(), 
                $this->getWordpress()->getUsername(), 
                $this->getWordpress()->getPassword(),
                $this->name
            ));
            
            $list = $rpc_client->send();
            
            foreach ($list as $term) {
            	
            	array_push(
            		$terms,
            		new WPTerm(
            			$this,
            			$term['term_id']
            		)
            	);
            	
            }
            
    	} catch (RpcException $rpc) {
    		
    		throw new WPException("Unable to retrive term informations - RPC Exception (".$rpc->getMessage().")");
    		
    	} catch (XmlrpcException $xml) {
    		
    		throw new WPException("Unable to retrive term informations - XMLRPC Exception (".$xml->getMessage().")");
    		
    	} catch (HttpException $http) {
    		
    		throw new WPException("Unable to retrive term informations - HTTP Exception (".$http->getMessage().")");
    		
    	} catch (Exception $e) {
    		
    		throw new WPException("Unable to retrive term informations - Generic Exception (".$e->getMessage().")");
    		
    	}
    	
    	return $terms;
    	
    }
	
    /**
     * Load taxonomy data
     *
     * @param   array   $taxonomy
     *
     * @return  Object  $this
     */
    
    public function loadData($taxonomy) {
		
        $this->name         = $taxonomy['name'];
        
        $this->label        = $taxonomy['label'];
        
        $this->hierarchical = filter_var($taxonomy['hierarchical'], FILTER_VALIDATE_BOOLEAN);
        
        $this->public_tax   = filter_var($taxonomy['public'], FILTER_VALIDATE_BOOLEAN);
        
        $this->show_ui      = filter_var($taxonomy['show_ui'], FILTER_VALIDATE_BOOLEAN);
        
        $this->builtin      = filter_var($taxonomy['_builtin'], FILTER_VALIDATE_BOOLEAN);
        
        $this->labels       = $taxonomy['labels'];
        
        $this->cap          = $taxonomy['cap'];
        
        $this->object_type  = $taxonomy['object_type'];
    	
    	return $this;
        
    }
    
}