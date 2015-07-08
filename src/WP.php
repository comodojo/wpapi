<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;
use \Comodojo\Exception\RpcException;
use \Comodojo\Exception\HttpException;
use \Comodojo\Exception\XmlrpcException;
use \Exception;
use \Comodojo\RpcClient\RpcClient;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress connection
 *
 * It allows to log into a wordpress blog and can be used by the other classe of 
 * the Comodojo/WP namespace in order to perform authenticated queries to the wordpress engine.
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
class WP {
  
    /**
     * URL of the Wordpress installation
     *
     * @var string
     */
    private $url      = "";
  
    /**
     * URL of the Wordpress XML-RPC endpoint
     *
     * @var string
     */
    private $endpoint = "";
    
    /**
     * Username
     *
     * @var string
     */
    private $username = "";
    
    /**
     * Password
     *
     * @var string
     */
    private $password = "";
    
    /**
     * List of blogs accessible for the logged user
     *
     * @var mixed
     */
    private $blogs    = array();
    
    /**
     * Login status
     *
     * @var boolean
     */
    private $logged   = false;
  
    /**
     * Class constructor
     *
     * @param   string  $url  URL of the Wordpress installation
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function __construct($url) {
      
        if ( empty($url) ) {
          
            throw new WPException("Invalid Wordpress address");
          
        }
        
        if (!preg_match("/^http/", $url)) {
          
            $url = "http://".$url;
          
        }
        
        $this->url = preg_replace('/\/$/', "", $url);
    }
    
    /**
     * Login to wordpress
     *
     * @param   string  $username  Username
     * @param   string  $password  Password
     *
     * @return  boolean  $this->logged
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function login($username, $password) {
      
        $this->username = $username;
        $this->password = $password;
        
        $this->setEndPoint($this->url."/xmlrpc.php");
      
        try {
        
            $rpc_client = new RpcClient($this->getEndPoint());
            
            $rpc_client->addRequest("wp.getUsersBlogs", array( 
                $username, 
                $password
            ));
            
            $blogs = $rpc_client->send();
            
	        if (count($blogs) > 0) {
	        
	            $this->logged = true;
	        
		        if (count($blogs) == 1) {
		        	
		        	$this->blogs = array(
		        		new WPBlog(
							$this,
							$blogs[0]['blogid'],
							$blogs[0]['blogName'],
							$blogs[0]['url'],
							$this->getEndPoint(),
							$blogs[0]['isAdmin']
						)
		        	);
		        	
		        } else {
	            
		            foreach ($blogs as $blog) {
		            	
		            	$b = new WPBlog(
							$this,
							$blog['blogid'],
							$blog['blogName'],
							$blog['url'],
							$blog['xmlrpc'],
							$blog['isAdmin']
						);
		                
		                if ($b->getID() > -1) {
							array_push(
								$this->blogs,
								$b
							);
		                }
		              
		            }
		            
		        }
	        
	        }
        
        } catch (RpcException $rpc) {
        
            throw new WPException("Unable to login - RPC Exception (".$rpc->getMessage().")");
        
        } catch (XmlrpcException $xml) {
        
            throw new WPException("Unable to login - XMLRPC Exception (".$xml->getMessage().")");
        
        } catch (HttpException $http) {
        
            throw new WPException("Unable to login - HTTP Exception (".$http->getMessage().")");
        
        } catch (Exception $e) {
        
            throw new WPException("Unable to login - Generic Exception (".$e->getMessage().")");
        
        }
      
        return $this->logged;
      
    }
    
    /**
     * Get login status
     *
     * @return  boolean  $this->logged
     */
    public function isLogged() {
      
        return $this->logged;
      
    }
    
    /**
     * Set endpoint XML-RPX
     * 
     * @param   string  $endpoint Set the endpoint to the XML-RPC server. It should only be used when the endpoint differs from the default one
     *
     * @return  Object  $this
     */
    public function setEndPoint($endpoint) {
      
        $this->endpoint = $endpoint;
        
        return $this;
      
    }
    
    /**
     * Get endpoint XML-RPX
     *
     * @return  string  $this->endpoint
     */
    public function getEndPoint() {
      
        return $this->endpoint;
      
    }
    
    /**
     * Get login username
     *
     * @return  string  $this->username
     */
    public function getUsername() {
      
        return $this->username;
      
    }
    
    /**
     * Get login password
     *
     * @return  string  $this->password
     */
    public function getPassword() {
      
        return $this->password;
      
    }
    
    /**
     * Get user's blogs
     *
     * @return  array  $this->blogs
     */
    public function getBlogs() {
      
        return $this->blogs;
      
    }
    
    /**
     * Get blog with a specified ID
     * 
     * @param   string  $id  ID of the requested blog
     *
     * @return  Object  $blog
     */
    public function getBlogByID($id) {
      
        $id = intval($id);
      
        foreach ($this->blogs as $blog) {
        
            if ($blog->getID() == $id) return $blog;
        
        }
      
        return null;
      
    }
    
    /**
     * Get blog with a specified name
     * 
     * @param   string  $name  Name of the requested blog
     *
     * @return  Object  $blog
     */
    public function getBlogByName($name) {
      
        foreach ($this->blogs as $blog) {
        
            if ($blog->getName() == $name) return $blog;
        
        }
      
        return null;
      
    }
    
    
}