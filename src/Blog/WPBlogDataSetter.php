<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress blog data
 *
 * It allows to retrieve data of a wordpress blog.
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
abstract class WPBlogDataSetter extends WPBlogData {
    
    /**
     * Set blog name
     *
     * @param string      $name
     *
     * @return WPBlogData $this
     */
    protected function setName($name) {
    	
    	$this->name = $name;
    	
    	return $this;
    	
    }
    
    /**
     * Set blog URL
     *
     * @param string      $url
     *
     * @return WPBlogData $this
     */
    protected function setURL($url) {
    	
    	$this->url = $url;
    	
    	return $this;
    	
    }
    
    /**
     * Set blog XML-RPC endpoint
     *
     * @param string      $endpoint
     *
     * @return WPBlogData $this
     */
    protected function setEndPoint($endpoint) {
    	
    	$this->endpoint = $endpoint;
    	
    	return $this;
    	
    }
    
    /**
     * True if the user is admin on the blog, false otherwise
     *
     * @param boolean     $admin
     *
     * @return WPBlogData $this
     */
    protected function setAdmin($admin) {
    	
    	$this->admin = $admin;
    	
    	return $this;
    	
    }
    
    /**
     * Set a value for an option
     *
     * @param   string  $name  Option name
     * @param   string  $value Option value
     *
     * @return  Object  $this
     * 
     * @throws \Comodojo\Exception\WPException
     */
    public function setOption($name, $value) {
    	
    	if (is_null($this->options)) $this->loadBlogOptions();
    	
    	if (!isset($this->options[$name]))
    		throw new WPException("There isn't any option called '$name'");
    	
    	if ($this->options[$name]['readonly'])
    		throw new WPException("The option '$name' is read-only");
    	
    	try {
            
            $options = $this->getWordpress()->sendMessage("wp.setOptions", array(
                array(
                	$name => $value
                )
            ), $this);
            
            foreach ($options as $name => $option) {
            	
            	$this->options[$name] = array(
            		"desc"     => $option['desc'],
            		"value"    => $option['value'],
            		"readonly" => filter_var($option['readonly'], FILTER_VALIDATE_BOOLEAN)
            	);
            	
            }
            
    	} catch (WPException $wpe) {
    		
    		throw new WPException("Unable to set value for option '$name' (".$wpe->getMessage().")");
    		
    	}
    	
    	return $this;
    	
    }
    
}