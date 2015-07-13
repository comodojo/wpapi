<?php

class WPUserTest extends \PHPUnit_Framework_TestCase {
	
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
    
    public function testWPProfile() {
    	
    	if (self::$wp->isLogged()) {
    
    		$profile = self::$blog->getProfile();
			    
		    // You can edit your profile informations
		    $profile->setNickname("42")
		    	->setFirstname("Arthur")
		    	->setLastname("Dent")
		    	->setDisplayname("Arthur Dent")
		    	->setNicename("Arthy")
		    	->setBiography("Hitchhiking through the galaxy")
		    	->setURL("http://localhost/")
		    	->save();
		    
	    	$this->assertSame('Arthur', $profile->getFirstname());
		    
	    	$this->assertSame('Dent', $profile->getLastname());
    		
    	}
    	
    }

   /**
    * @depends testWPProfile 
    */    
    public function testWPUser() {
    	
    	if (self::$wp->isLogged()) {
    
    		$profile = self::$blog->getProfile();
    		
    		$user = new \Comodojo\WPAPI\WPUser(self::$blog);
		    		
		    $user->loadFromID($profile->getID());
			    
    		$this->assertSame($user->getFirstname(), $profile->getFirstname());
	    
    		$this->assertSame($user->getNickname(), $profile->getNickname());
	    
    		$this->assertSame($user->getLastname(), $profile->getLastname());
	    
    		$this->assertSame($user->getDisplayname(), $profile->getDisplayname());
	    
    		$this->assertSame($user->getNicename(), $profile->getNicename());
	    
    		$this->assertSame($user->getBiography(), $profile->getBiography());
	    
    		$this->assertSame($user->getRegistration("Y/m/d"), $profile->getRegistration("Y/m/d"));
    		
    	}
    	
    }
    
   /**
    * @depends testWPUser 
    */    
    public function testWPUserIterator() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$users = self::$blog->getUsers();
    		
    		$this->assertGreaterThan(0, $users->getLength());
    		
    		foreach ($users as $id => $user) {
    			
    			$this->assertSame($id, $user->getID());
    			
    		}
    		
    		$this->assertSame($users->getLength(), $users->getFetchedItems());
    		
    	}
    	
    }
    
}