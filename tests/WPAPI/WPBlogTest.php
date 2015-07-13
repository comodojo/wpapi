<?php

class WPBlogTest extends \PHPUnit_Framework_TestCase {
	
    public static $wp      = null;
	
    public static $blog    = null;
    
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
    		
    			self::$blog = $blog;
    			
    			break;
		    	
    		}
    		
    	}
    
    }
    
    public function testWPBlogData() {
    	
    	$data = self::$blog->getData();
    	
    	$this->assertSame($data['blogName'], self::$blog->getName());
    	
    	$this->assertSame(self::$address, self::$blog->getURL());
    	
    	$this->assertTrue(self::$blog->isAdmin());
    	
    	
    }
    
    public function testWPBlogEndPoint() {
    	
    	$endpoint = self::$blog->getEndPoint();
    	
    	$this->assertSame(self::$address . "xmlrpc.php", $endpoint);
    	
    	$this->assertTrue(self::$blog->checkEndPoint());
    	
    	
    }
    
    public function testWPBlogDataSupported() {
    	
    	$this->assertGreaterThan(0, count(self::$blog->getSupportedFormats()));
    	
    	$this->assertGreaterThan(0, count(self::$blog->getSupportedTypes()));
    	
    	$this->assertGreaterThan(0, count(self::$blog->getSupportedPostStatus()));
    	
    	$this->assertGreaterThan(0, count(self::$blog->getSupportedCommentStatus()));
    	
    	
    }
    
    public function testWPBlogDataLoader() {
    	
    	$data = self::$blog->getData();
    	
    	$data['blogName'] = "TEST BLOG";
    	
    	$blog = new \Comodojo\WPAPI\WPBlog(self::$wp);
    	
    	$blog->loadData($data);
    	
    	$this->assertGreaterThan(0, $blog->getID());
    	
    	$this->assertSame("TEST BLOG", $blog->getName());
    	
    }
    
    public function testWPBlogTaxonomies() {
    	
    	$taxonomies = self::$blog->getTaxonomies();
    	
    	$this->assertGreaterThan(0, count($taxonomies));
    	
    	foreach ($taxonomies as $taxonomy) {
    		
    		$t = self::$blog->getTaxonomy($taxonomy->getName());
    		
    		$this->assertSame($t->getName(), $taxonomy->getName());
    		
    		$this->assertTrue(self::$blog->hasTaxonomy($t->getName()));
    		
    	}
    	
    }
    
    public function testWPBlogTags() {
    	
    	// Add at least one tag
    	$taxonomy = self::$blog->getTaxonomy("post_tag");
    	
    	$new_term = new \Comodojo\WPAPI\WPTerm($taxonomy);
    	
    	$new_term->setName("Test tag")->save();
    	
    	self::$blog->addTag($new_term);
    	
    	// Check the tags
    	$tags = self::$blog->getTags();
    	
    	$this->assertGreaterThan(0, count($tags));
    	
    	foreach ($tags as $tag) {
    		
    		$t = self::$blog->getTag($tag->getName());
    		
    		$this->assertSame($t->getName(), $tag->getName());
    		
    		$this->assertTrue(self::$blog->hasTag($t->getName()));
    		
    	}
    	
    }
    
    public function testWPBlogCategories() {
    	
    	// Add at least one category
    	$taxonomy = self::$blog->getTaxonomy("category");
    	
    	$new_term = new \Comodojo\WPAPI\WPTerm($taxonomy);
    	
    	$new_term->setName("Test category")->save();
    	
    	self::$blog->addCategory($new_term);
    	
    	// Check the categories
    	$categories = self::$blog->getCategories();
    	
    	$this->assertGreaterThan(0, count($categories));
    	
    	foreach ($categories as $category) {
    		
    		$c = self::$blog->getCategory($category->getName());
    		
    		$this->assertSame($c->getName(), $category->getName());
    		
    		$this->assertTrue(self::$blog->hasCategory($c->getName()));
    		
    	}
    	
    }
    
    public function testWPBlogUsers() {
    	
    	$profile = self::$blog->getProfile();
    	
    	$this->assertSame(self::$user, $profile->getUsername());
    	
    	$user = self::$blog->getUserByID($profile->getID());
    	
    	$this->assertSame($user->getUsername(), $profile->getUsername());
    	
    	foreach ($profile->getRoles() as $role) {
    	
	    	$users = self::$blog->getUsersByRole($role);
	    	
	    	$this->assertGreaterThan(0, count($users));
    		
    	}
    	
    	$authors = self::$blog->getAuthors();
    	
    	foreach ($authors as $author) {
    		
    		$this->assertTrue($author->hasRole("administrator"));
    	
    	}
    	
    	$admins = self::$blog->getUsers("administrator");
    	
    	foreach ($admins as $admins) {
    		
    		$this->assertTrue($author->hasRole("administrator"));
    	
    	}
    	
    }
    
    public function testWPBlogPosts() {
    	
    	for ($i=0; $i<3; $i++) {
    		
    		$new_post = new \Comodojo\WPAPI\WPPost(self::$blog);
    		
    		$new_post->setTitle("Test post n." . $i)
    			->setContent("TEST")
    			->addTag("Test Tag " . $i)
    			->addCategory("Test Category " . $i)
    			->setStatus("publish")
    			->save();
    			    	
	    	$this->assertGreaterThan(0, $new_post->getID());
    	}
    	
    	for ($i=0; $i<3; $i++) {
    		
    		$posts = self::$blog->getPostsByTag("Test Tag " . $i);
	    	
	    	$this->assertGreaterThan(0, $posts->getLength());
    		
    		foreach ($posts as $post) {
    			
    			$this->assertSame($post->getTitle(), "Test post n." . $i);
    			
    		}
    		
    	}
    	
    	for ($i=0; $i<3; $i++) {
    		
    		$posts = self::$blog->getPostsByCategory("Test Category " . $i);
	    	
	    	$this->assertGreaterThan(0, $posts->getLength());
    		
    		foreach ($posts as $post) {
    			
    			$this->assertSame($post->getTitle(), "Test post n." . $i);
    			
    		}
    		
    	}
    	
    	foreach (self::$blog->getLatestPosts() as $post) {
    		
    		$this->assertSame($post->getContent(), "TEST");
    		
    		$p = self::$blog->getPostByID($post->getID());
    		
    		$this->assertSame($post->getTitle(), $p->getTitle());
    		
    		$this->assertTrue($post->delete());
    		
    	}
    	
    }
    
}