<?php

class WPTest extends \PHPUnit_Framework_TestCase {
	
    public static $wp      = null;
    
    public static $address = "http://localhost/";
    
    public static $user    = "admin";
    
    public static $pass    = "admin";
    
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
		    	
    		}
    		
    	}
    
    }
    
    public function testWPData() {
    	
    	$this->assertTrue(self::$wp->isLogged());
    	
    	$this->assertTrue(self::$wp->login(self::$user, self::$pass));
    	
    	if (self::$wp->isLogged()) {
    		
    		$this->assertSame("admin", self::$wp->getUsername());
    		
    		$this->assertSame("admin", self::$wp->getPassword());
    	
	    	$endpoint = self::$address . "xmlrpc.php";
	    			
	    	$this->assertSame($endpoint, self::$wp->getEndPoint());
	    	
	    	self::$wp->setEndPoint("test");
	    			
	    	$this->assertSame("test", self::$wp->getEndPoint());
	    	
	    	self::$wp->setEndPoint($endpoint);
	    			
	    	$this->assertSame($endpoint, self::$wp->getEndPoint());
    		
    	}
    	
    }
    
    public function testWPBlogs() {
    	
    	if (self::$wp->isLogged()) {
    		
    		foreach (self::$wp->getBlogs() as $blog) {
    			
    			$b1 = self::$wp->getBlogByID($blog->getID());
    			
    			$b2 = self::$wp->getBlogByName($blog->getName());
    			
    			$this->assertSame($b1->getName(), $blog->getName());
    			
    			$this->assertSame($b2->getName(), $blog->getName());
    			
    		}
    		
    	}
    	
    }
    
    public function testWPOptions() {
    	
    	if (self::$wp->isLogged()) {
    		
    		foreach (self::$wp->getBlogs() as $blog) {
    			
    			$options = $blog->getAvailableOptions();
    			
    			$this->assertGreaterThan(0, count($options));
    			
    			foreach ($options as $opt) {
    				
    				$this->assertInternalType('boolean', $blog->isReadOnlyOption($opt));
    				
    				if (!$blog->isReadOnlyOption($opt)) {
            
            			$blog->getOptionValue($opt);
            			$blog->getOptionDescription($opt);
            
    				}

    			}
    			
    			$blog->setOption("blog_title", "This is a TEST blog", "This is a TEST");
    			
    			$this->assertSame($blog->getOptionValue("blog_title"), "This is a TEST blog");
    			
    		}
    		
    	}
    	
    }
    
}