<?php

class WPPostTest extends \PHPUnit_Framework_TestCase {
	
    public static $wp      = null;
	
    public static $blog    = null;
    
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
    			
    			break;
		    	
    		}
    		
    	}
    
    }
    
    public function testWPPostCreate() {
    	
    	$timestamp = time();
    	
    	if (self::$wp->isLogged()) {
			
			for ($i=0; $i<3; $i++) {
				
				$post = new \Comodojo\WPAPI\WPPost(self::$blog);
				
				$post->setTitle("Test POST n." . $i)
					->setCreationDate($timestamp - ($i * 60))
					->setStatus("draft")
					->setType("post")
					->setFormat("standard")
					->setAuthor(self::$blog->getProfile())
					->setPassword("")
					->setExcerpt("Test " . $i)
					->setContent("Post content N." . $i)
					->setMenuOrder($i)
					->setCommentStatus("open")
					->setPingStatus("open")
					->setSticky(false)
					->setCustomField("test_custom_field", $i)
					->setCustomField("test_custom_data",  date("Y-m-d H:i:s",$timestamp))
					->setPingStatus("open")
					->addCategory("wptest")
					->addTag("wpapi")
					->save();
					
				array_push(self::$ids, $post->getID());
				
			}
    		
    		$posts = self::$blog->getPosts("post", "draft", 3);
			
			$this->assertSame($posts->getLength(), 3);
			
    	}
    	
    }

   /**
    * @depends testWPPostCreate 
    */    
    public function testWPPostData() {
		
    	if (self::$wp->isLogged()) {
    		
    		$posts = self::$blog->getPosts("post", "draft", 3);
			
			$this->assertSame($posts->getLength(), 3);
			
			foreach ($posts as $id => $post) {
				
				$this->assertTrue(in_array(intval($id), self::$ids));
				
				$i = intval($post->getCustomField("test_custom_field"));
				
				$timestamp = strtotime($post->getCustomField("test_custom_data"));
				
				$this->assertSame($post->getCreationDate(), $timestamp - ($i * 60));
				
				$this->assertSame($post->getTitle(), "Test POST n." . $i);
				
				$this->assertSame($post->getStatus(), "draft");
				
				$this->assertSame($post->getType(), "post");
				
				$this->assertSame($post->getFormat(), "standard");
				
				$this->assertSame($post->getAuthor()->getID(), self::$blog->getProfile()->getID());
				
				$this->assertSame($post->getPassword(), false);
				
				$this->assertSame($post->getExcerpt(), "Test " . $i);
				
				$this->assertSame($post->getContent(), "Post content N." . $i);
				
				$this->assertSame($post->getMenuOrder(), $i);
				
				$this->assertSame($post->getCommentStatus(), "open");
				
				$this->assertSame($post->getPingStatus(), "open");
				
				$this->assertSame($post->isSticky(), false);
				
				$this->assertSame($post->getPingStatus(), "open");
				
				$this->assertTrue($post->hasCategory("wptest"));
				
				$this->assertTrue($post->hasTag("wpapi"));
				
			}
    		
    	}
    	
    }

   /**
    * @depends testWPPostData 
    */    
    public function testWPPostModify() {
		
    	if (self::$wp->isLogged()) {
    		
    		$posts = self::$blog->getPosts("post", "draft", 3);
			
			$this->assertSame($posts->getLength(), 3);
    			
			foreach ($posts as $id => $post) {
				
				$this->assertTrue(in_array(intval($id), self::$ids));
				
				$i = intval($post->getCustomField("test_custom_field"));
				
				$post->setTitle("Ready to be deleted");
				
				if ($i > 0) {
					
					$parent = new \Comodojo\WPAPI\WPPost(self::$blog);
					
					$parent->loadFromID(self::$ids[$i-1]);
					
					$post->setParent($parent);
					
				}
				
				$post->setStatus("publish")
					->cleanCustomFields()
					->removeCategory("wptest")
					->removeTag("wpapi")
					->save();
				var_dump($post);
				$parent = $post->getParent();
				
				if (!is_null($parent)) {
					
					$this->assertTrue(in_array($parent->getID(), self::$ids));
					
				}
				
				$this->assertSame($post->getStatus(), "publish");
				
				$this->assertFalse($post->hasCategory("wptest"));
				
				$this->assertFalse($post->hasTag("wpapi"));
				
				$this->assertFalse($post->hasCustomField("test_custom_field"));
				
			}
    		
    	}
    	
    }

   /**
    * @depends testWPPostModify 
    */    
    public function testWPPostDelete() {
		
    	if (self::$wp->isLogged()) {
    		
    		$posts = self::$blog->getLatestPosts(3);
			var_dump($posts);
			$this->assertSame($posts->getLength(), 3);
    		
			while ($posts->hasNext()) {
				
				$post = $posts->getNext();
				
				$this->assertTrue(in_array(intval($posts->getCurrentID()), self::$ids));
				
				$this->assertTrue($post->delete());
				
				$this->assertSame($post->getID(), 0);
				
			}
			
			$this->assertSame($posts->getFetchedItems(), 3);
			
			$count = self::$blog->getLatestPosts()->getLength();
			
			$this->assertSame($count, 0);
    		
    	}
    	
    }
    
}