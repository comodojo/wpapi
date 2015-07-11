<?php namespace Comodojo\WPAPI;

use \Comodojo\Exception\WPException;

/** 
 * Comodojo Wordpress API Wrapper. This class maps a Wordpress media item
 *
 * It allows to retrieve informations about a media object.
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
abstract class WPMediaLoader extends WPMediaData {
	
    /**
     * Load media object data
     *
     * @param   array   $data
     *
     * @return  Object  $this
     */
    
    public function loadData($data) {
    	
    	$this->resetData();
			
		$this->id          = intval($data['attachment_id']);
			
		$this->post        = (isset($data['parent_id']))?intval($data['parent_id']):null;
			
		$this->date        = strtotime($data['date_created_gmt']);
		
		$this->link        = $data['link'];
		
		$this->title       = $data['title'];
		
		$this->caption     = $data['caption'];
		
		$this->description = $data['description'];
		
		$this->thumbnail   = $data['thumbnail'];
		
		if (isset($data['metadata']) && is_array($data['metadata'])) {
			
			$this->width   = intval($data['metadata']['width']);
			
			$this->height  = intval($data['metadata']['height']);
			
			$this->file    = $data['metadata']['file'];
			
			$this->sizes   = $data['metadata']['sizes'];
			
		}
		
		if (isset($data['image_meta'])) {
			
			$this->meta_timestamp     = strtotime($data['image_meta']['created_timestamp']);
			
			$this->meta_focal_length  = intval($data['image_meta']['focal_length']);
			
			$this->meta_iso           = intval($data['image_meta']['iso']);
			
			$this->meta_shutter_speed = intval($data['image_meta']['shutter_speed']);
			
			$this->meta_credit        = $data['image_meta']['credit'];
			
			$this->meta_camera        = $data['image_meta']['camera'];
			
			$this->meta_caption       = $data['image_meta']['caption'];
			
			$this->meta_copyright     = $data['image_meta']['copyright'];
			
			$this->meta_title         = $data['image_meta']['title'];
			
		}
    	
    	return $this;
        
    }
    
    /**
     * Get media data
     *
     * @return array $data
     */
    public function getData() {
    	 	
    	$content = array(
    		"name" => $this->filename,
    		"type" => $this->mime,
    		"bits" => $this->buffer
    	);
    	
    	if ($this->getPostID() > 0) {
    		$content["post_id"] = $this->post;
    	}
    	
    	return $content;
    	
    }
	
    /**
     * Reset object
     *
     * @return  Object  $this
     */
    protected function resetData() {
			
		$this->id                  = 0;

		$this->post                = 0;

		$this->date                = 0;

		$this->link                = "";

		$this->title               = "";

		$this->caption             = "";

		$this->description         = "";

		$this->thumbnail           = "";

		$this->width               = 0;

		$this->height              = 0;

		$this->file                = "";

		$this->meta_timestamp      = 0;

		$this->meta_focal_length   = 0;

		$this->meta_iso            = 0;

		$this->meta_shutter_speed  = 0;

		$this->meta_credit         = "";

		$this->meta_camera         = "";

		$this->meta_caption        = "";

		$this->meta_copyright      = "";

		$this->meta_title          = "";

		$this->sizes               = array();
    	
    	return $this;
        
    }
    
}