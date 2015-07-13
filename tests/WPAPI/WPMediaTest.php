<?php

class WPMediaTest extends \PHPUnit_Framework_TestCase {
	
    public static $wp      = null;
	
    public static $blog    = null;
	
    public static $post    = null;
    
    public static $address = "http://localhost/";
    
    public static $user = "admin";
    
    public static $pass = "admin";
    
    public static $ids  = array();
    
    public static function setUpBeforeClass() {
        
        self::$wp = new \Comodojo\WPAPI\WP(self::$address);
    	
    	if (self::$wp->login(self::$user, self::$pass)) {
    		
    		foreach (self::$wp->getBlogs() as $blog) {
    			
    			foreach ($blog->getPosts() as $id => $post) {
    				
		    		$post->delete();
		    		
		    	}
    			
    			foreach ($blog->getPages() as $id => $post) {
    				
		    		$post->delete();
		    		
		    	}
    		
    			self::$blog = $blog;
    			
				self::$post = new \Comodojo\WPAPI\WPPost(self::$blog);
				
				self::$post->setTitle("Test MEDIA")
					->setStatus("publish")
					->setCreationDate(time() - 14400)
					->setType("post")
					->setFormat("standard")
					->setContent("Post with images")
					->addCategory("wptest")
					->addTag("wpapi")
					->save();
    			
    			break;
		    	
    		}
    		
    	}
    
    }
    
    public function testWPMediaUpload() {
    	
    	if (self::$wp->isLogged()) {
    	
			for ($k=0; $k<3; $k++) {
				
				$image = new \Comodojo\WPAPI\WPMedia(self::$blog);
				
				$image->setPostID(self::$post->getID());
				
				$image->upload(__DIR__."/../resources/image.jpg")->save();
				
				array_push(self::$ids, $image->getID());
				
			}
			
			$library = self::$blog->getMediaLibrary();
			
			foreach ($library as $id => $media) {
				
				$this->assertTrue(in_array($id, self::$ids));
				
			}
				
			$this->assertSame($library->getFetchedItems(), 3);
			
    	}
    	
    }

   /**
    * @depends testWPMediaUpload 
    */    
    public function testWPMediaThumbnail() {
    	
    	if (self::$wp->isLogged()) {
    	
	    	$image = new \Comodojo\WPAPI\WPMedia(self::$blog);
	    	
	    	$image->loadFromID(self::$ids[0]);
	    	
	    	self::$post->setThumbnail($image)->save();
	    	
	    	$this->assertSame(self::$post->getThumbnail()->getID(), $image->getID());
	    	
    	}
    	
    }

   /**
    * @depends testWPMediaThumbnail 
    */    
    public function testWPMediaData() {
    	
    	if (self::$wp->isLogged()) {
    	
	    	$library = self::$post->getAttachments();
	    	
	    	$img1 = $library->getNext();
	    	
	    	$img2 = $library->getNext();
	    	
	    	$this->assertSame($img1->getTitle(), $img2->getTitle());
	    	
	    	$this->assertSame($img1->getCaption(), $img2->getCaption());
	    	
	    	$this->assertSame($img1->getDescription(), $img2->getDescription());
	    	
	    	$this->assertSame($img1->getWidth(), $img2->getWidth());
	    	
	    	$this->assertSame($img1->getHeight(), $img2->getHeight());
	    	
	    	$this->assertSame($img1->getFocalLength(), $img2->getFocalLength());
	    	
	    	$this->assertSame($img1->getISO(), $img2->getISO());
	    	
	    	$this->assertSame($img1->getShutterSpeed(), $img2->getShutterSpeed());
	    	
	    	$this->assertSame($img1->getCredit(), $img2->getCredit());
	    	
	    	$this->assertSame($img1->getCamera(), $img2->getCamera());
	    	
	    	$this->assertSame($img1->getMetaCaption(), $img2->getMetaCaption());
	    	
	    	$this->assertSame($img1->getCopyright(), $img2->getCopyright());
	    	
	    	$this->assertSame($img1->getImageTitle(), $img2->getImageTitle());
	    	
	    	$this->assertSame($img1->getSizeWidth("thumbnail"), $img2->getSizeWidth("thumbnail"));
	    	
	    	$this->assertSame($img1->getSizeHeight("thumbnail"), $img2->getSizeHeight("thumbnail"));
	    	
	    	$this->assertSame($img1->getSizeMimeType("thumbnail"), $img2->getSizeMimeType("thumbnail"));
	    	
	    	$this->assertSame($img1->getMetaDate("Y-m-d"), $img2->getMetaDate("Y-m-d"));
    	
    	}
    	
    }

   /**
    * @depends testWPMediaData 
    */    
    public function testWPMediaIterator() {
    	
    	if (self::$wp->isLogged()) {
    			
			$library = self::$blog->getMediaLibrary("image/jpeg");
			
			foreach($library as $id => $media) {
					
				$image = new \Comodojo\WPAPI\WPMedia(self::$blog);
				
				$image->loadFromID($id);
				
				$this->assertSame($media->getID(), $image->getID());
				
				$this->assertSame($media->getPostID(), $image->getPostID());
				
				$this->assertSame($media->getLink(), $image->getLink());
				
				$this->assertSame($media->getTitle(), $image->getTitle());
				
				$this->assertSame($media->getCaption(), $image->getCaption());
				
				$this->assertSame($media->getDescription(), $image->getDescription());
				
				$this->assertSame($media->getThumbnail(), $image->getThumbnail());
				
				$this->assertSame($media->getSupportedMediaSizes(), $image->getSupportedMediaSizes());
				
				$this->assertSame($media->getFile(), $image->getFile());
				
				$this->assertSame($media->getWidth(), $image->getWidth());
				
				$this->assertSame($media->getHeight(), $image->getHeight());
				
				$this->assertSame($media->getFocalLength(), $image->getFocalLength());
				
				$this->assertSame($media->getISO(), $image->getISO());
				
				$this->assertSame($media->getShutterSpeed(), $image->getShutterSpeed());
				
				$this->assertSame($media->getCredit(), $image->getCredit());
				
				$this->assertSame($media->getCamera(), $image->getCamera());
				
				$this->assertSame($media->getMetaCaption(), $image->getMetaCaption());
				
				$this->assertSame($media->getCopyright(), $image->getCopyright());
				
				$this->assertSame($media->getImageTitle(), $image->getImageTitle());
				
				$this->assertSame($media->getSizeWidth('thumbnail'), $image->getSizeWidth('thumbnail'));
				
				$this->assertSame($media->getSizeHeight('thumbnail'), $image->getSizeHeight('thumbnail'));
				
				$this->assertSame($media->getSizeFile('thumbnail'), $image->getSizeFile('thumbnail'));
				
				$this->assertSame($media->getSizeMimeType('thumbnail'), $image->getSizeMimeType('thumbnail'));
				
				$this->assertSame($media->getCreationDate(), $image->getCreationDate());
				
				$this->assertSame($media->getMetaDate(), $image->getMetaDate());
				
			}
    	
    	}
    	
    }
    
    public static function tearDownAfterClass() {
        
    	if (self::$wp->isLogged()) {
    		
        	self::$post->delete();
        	
    	}
    
    }
    
}