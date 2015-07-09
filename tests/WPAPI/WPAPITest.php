<?php

class WPAPITest extends \PHPUnit_Framework_TestCase {
	
    protected $wp   = null;
    
    protected $blog = null;
    
    protected function setUp() {
    	
        $address = "http://localhost/";
        $user = "admin";
        $pass = "admin";
        
        $this->wp = new \Comodojo\WPAPI\WP($address);
    	
    	if ($this->wp->login($user, $pass)) {
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			foreach ($blog->getPosts() as $id => $post) {
    				
		    		$post->delete();
		    		
		    	}
    			
    			foreach ($blog->getPages() as $id => $post) {
    				
		    		$post->delete();
		    		
		    	}
    		
    			$this->blog = $blog;
    			
    			break;
		    	
    		}
    		
    	}
    
    }
    
    public function testWordpress() {
    	
    	if ($this->wp->isLogged()) {
    		
    		$this->assertSame("admin", $this->wp->getUsername());
    		
    		$this->assertSame("admin", $this->wp->getPassword());
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			$b1 = $this->wp->getBlogByID($blog->getID());
    			
    			$b2 = $this->wp->getBlogByName($blog->getName());
    			
    			$this->assertSame($b1->getName(), $blog->getName());
    			
    			$this->assertSame($b2->getName(), $blog->getName());
    			
    			$blog->getSupportedFormats();
    			
    			$blog->getSupportedTypes();
    			
    			$blog->getSupportedPostStatus();
    			
    			$blog->getSupportedCommentStatus();
    			
    			$options = $blog->getAvailableOptions();
    			
    			foreach ($options as $opt) {
    				
    				if (!$blog->isReadOnlyOption($opt)) {
            
            			$blog->setOption($opt, "Test " . $blog->getOptionValue($opt), "Test " . $blog->getOptionDescription($opt));

            
    				}

    			}
    			
    			
    		}
    		
    	}
    	
    	$endpoint = "http://localhost/xmlrpc.php";
    			
    	$this->assertSame($endpoint, $this->wp->getEndPoint());
    	
    	$this->wp->setEndPoint("test");
    			
    	$this->assertSame("test", $this->wp->getEndPoint());
    	
    	$this->wp->setEndPoint($endpoint);
    			
    	$this->assertSame($endpoint, $this->wp->getEndPoint());
    	
    }
    
    public function testPosts() {
    	
    	$post_ids  = array();
    	
    	$timestamp = time();
    	
    	if ($this->wp->isLogged()) {
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			$post_ids[$blog->getID()] = array();
    			
    			for ($i=0; $i<10; $i++) {
    				
    				$post = new \Comodojo\WPAPI\WPPost($blog);
    				
    				$post->setTitle("Test post n." . $i)
    					->setCreationDate($timestamp - ($i * 60))
    					->setStatus("draft")
    					->setType("post")
    					->setFormat("standard")
    					->setAuthor($blog->getProfile())
    					->setPassword("")
    					->setExcerpt("Test " . $i)
    					->setContent("Post content N." . $i)
    					->setMenuOrder(0)
    					->setCommentStatus("open")
    					->setPingStatus("open")
    					->setSticky(false)
    					->setCustomField("test_custom_field", $i)
    					->setPingStatus("open")
    					->addCategory("wptest")
    					->addTag("wpapi")
    					->save();
    					
    				array_push($post_ids[$blog->getID()], intval($post->getID()));
    				
    			}
    			
    			break;
    			
    		}
    		
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			$posts = $blog->getPosts()->reverse();
    			
    			while ($posts->hasPrevious()) {
    				
    				$post  = $posts->getPrevious();
    				
    				$check = $blog->getPostByID($posts->getCurrentID());
    				
    				$this->assertSame($post->getTitle(), $check->getTitle());
    				
    			}
    			
    			break;
    			
    		}
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			foreach ($blog->getPosts("post", "draft", 10) as $id => $post) {
    				
    				$this->assertTrue(in_array(intval($id), $post_ids[$blog->getID()]));
    				
    				$i = intval($post->getCustomField("test_custom_field"));
    				
					//$this->assertSame($post->getCreationDate(), $timestamp - ($i * 60 * 60));
					$this->assertSame($post->getTitle(), "Test post n." . $i);
					$this->assertSame($post->getStatus(), "draft");
					$this->assertSame($post->getType(), "post");
					$this->assertSame($post->getFormat(), "standard");
					$this->assertSame($post->getAuthor()->getID(), $blog->getProfile()->getID());
					$this->assertSame($post->getPassword(), false);
					$this->assertSame($post->getExcerpt(), "Test " . $i);
					$this->assertSame($post->getContent(), "Post content N." . $i);
					$this->assertSame($post->getMenuOrder(), 0);
					$this->assertSame($post->getCommentStatus(), "open");
					$this->assertSame($post->getPingStatus(), "open");
					$this->assertSame($post->isSticky(), false);
					$this->assertSame($post->getPingStatus(), "open");
    				
    			}
    			
    			break;
    			
    		}
    		
    		foreach ($post_ids as $blog_id => $posts) {
    			
    			$blog = $this->wp->getBlogByID($blog_id);
    			
    			foreach ($posts as $id) {
    				
    				$post = new \Comodojo\WPAPI\WPPost($blog);
    				
    				$post->loadFromID($id);
    				
    				$post->setTitle("Ready to be deleted")->save();
    				
					$this->assertSame($post->getTitle(), "Ready to be deleted");
    				
    				$this->assertTrue($post->delete());
    				
    			}
    			
    		}
    		
    	}
    	
    }
    
    public function testComments() {
    	
    	$comment_ids = array();
    	
    	$timestamp   = time();
    	
    	if ($this->wp->isLogged()) {
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			$comment_ids[$blog->getID()] = array();
    			
    			$post = new \Comodojo\WPAPI\WPPost($blog);
    			
    			$post->setTitle("Test comments")
    				->setContent("Test")
    				->setStatus("publish")
    				->addTag("comments")
    				->save();
    				
    			$comment_ids[$blog->getID()][$post->getID()] = array();
    			
    			$this->assertSame($post->getComments()->getTotal(), 0);
    			
    			for ($i=0; $i<10; $i++) {
    				
    				$comment = new \Comodojo\WPAPI\WPComment($post);
    				
    				$parent = -1;
    				
    				$comment_count = count($comment_ids[$blog->getID()][$post->getID()]);
    				
    				if ($comment_count > 0) {
    					
    					$parent = new \Comodojo\WPAPI\WPComment($post);
    					
    					$parent->loadFromID($comment_ids[$blog->getID()][$post->getID()][$comment_count - 1]);
    					
    				}
    				
    				$comment->setContent("Test comment")
    					->setParent($parent)
    					->save();
    					
    				array_push($comment_ids[$blog->getID()][$post->getID()], intval($comment->getID()));
    				
    				$comment->setContent($comment->getContent() . " N." . $comment->getID())->save();
    				
    			}
    			
    			$this->assertSame($post->getCommentsByStatus()->getTotal(), 10);
    			
    			$post->getCommentsByStatus()->getApproved();
    			
    			$post->getCommentsByStatus()->getSpam();
    			
    			$post->getCommentsByStatus()->getAwaiting();
    			
    			break;
    			
    		}
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			foreach ($blog->getLatestPosts(1) as $pid => $post) {
    			
    				foreach ($post->getComments() as $id => $comment) {
    				
	    				$this->assertTrue(in_array(intval($id), $comment_ids[$blog->getID()][$pid]));
	    				
						$this->assertSame($comment->getContent(), "Test comment N." . $id);
						$this->assertSame($comment->getPost()->getID(), intval($pid));

						if (!is_null($comment->getUser())) $this->assertSame($comment->getUser()->getID(), $blog->getProfile()->getID());
						//if (!is_null($comment->getParent())) $this->assertTrue(in_array(intval($comment->getParent()->getID()), $comment_ids[$blog->getID()][$pid]));
						
    				}
    				
    			}
    			
    			break;
    			
    		}
    		
    		foreach ($comment_ids as $blog_id => $posts) {
    			
    			$blog = $this->wp->getBlogByID($blog_id);
    		
    			foreach ($posts as $post_id => $comments) {
    				
	    			$post = new \Comodojo\WPAPI\WPPost($blog, $post_id);
    			
    				foreach ($comments as $id) {
    					
    					$comment = new \Comodojo\WPAPI\WPComment($post);
	    				
	    				$comment->loadFromID($id);
	    				
	    				$comment->setContent("Ready to be deleted")->save();
	    				
						$this->assertSame($comment->getContent(), "Ready to be deleted");
	    				
	    				$this->assertTrue($comment->delete());
	    				
    				}
    				
    				$post->delete();
    				
    			}
    			
    		}
    		
    	}
    	
    }
    
    public function testUsers() {
    	
    	if ($this->wp->isLogged()) {
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    	
			    // Get profile
			    $profile = $blog->getProfile();
			    
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
		    	
		    	foreach ($blog->getUsers() as $id => $user) {
		    		
		    		$u = new \Comodojo\WPAPI\WPUser($blog);
		    		
		    		$u->loadFromID($id);
			    
		    		$this->assertSame($user->getFirstname(), $u->getFirstname());
			    
		    		$this->assertSame($user->getNickname(), $u->getNickname());
			    
		    		$this->assertSame($user->getLastname(), $u->getLastname());
			    
		    		$this->assertSame($user->getDisplayname(), $u->getDisplayname());
			    
		    		$this->assertSame($user->getNicename(), $u->getNicename());
			    
		    		$this->assertSame($user->getBiography(), $u->getBiography());
			    
		    		$this->assertSame($user->getRegistration("Y/m/d"), $u->getRegistration("Y/m/d"));
		    		
		    	}
		    	
		    	$admins = $blog->getUsersByRole("administrator")->reverse();
		    	
		    	while ($admins->hasPrevious()) {
		    		
		    		$user = $admins->getPrevious();
		    		
		    		if ($admins->getCurrentID() == $profile->getID()) {
		    			
		    			$this->assertTrue($blog->isAdmin());
		    			
		    		}
			    
		    		$this->assertTrue(in_array("administrator", $user->getRoles()));
		    		
		    	}
    			
    			break;
		    	
    		}
    		
    	}
    	
    }
    
    public function testMedia() {
    	
    	$ids = array();
    	
    	if ($this->wp->isLogged()) {
    		
    		foreach ($this->wp->getBlogs() as $blog) {
    			
    			for ($i=0; $i<3; $i++) {
    			
	    			$post = new \Comodojo\WPAPI\WPPost($blog);
	    			
	    			$post->setTitle("Test media")
	    				->setContent("Test")
    					->setStatus("draft")
	    				->addTag("images")
	    				->save();
    				
    				for ($k=0; $k<3; $k++) {
    					
    					$image = new \Comodojo\WPAPI\WPMedia($blog);
    					
    					$image->setPostID($post->getID());
    					
    					$image->upload(__DIR__."/../resources/keepcalm.png");
    					
    					array_push($ids, intval($image->getID()));
    					
    				}
    				
    				$images = $post->getAttachments();
    				
    				$thumb  = $images->getNext();
    				
    				$post->setThumbnail($thumb)->save();
    				
    				$this->assertSame($post->getThumbnail()->getID(), $thumb->getID());
    				
    			}
    			
    			$library = $blog->getMediaLibrary("image/png");
    			
    			foreach($library as $id => $media) {
    				
    				$this->assertTrue(in_array(intval($id), $ids));
    					
    				$image = new \Comodojo\WPAPI\WPMedia($blog);
    				
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
					
					if ($library->getFetchedItems() == 9) break;
    				
    			}
    			
    			$this->assertSame($library->getFetchedItems(), 9);
    			
    			break;
    			
    		}
    	
    	}
    	
    }
    
    public function testGetLatestPosts() {
    	
    	$posts = $this->blog->getLatestPosts();
    	
    	foreach ($posts as $post) {
    		
    		$this->assertSame('publish', $post->getStatus());
    		
    	}
    	
    }
    
    public function testGetPages() {
    	
    	$posts = $this->blog->getPages();
    	
    	foreach ($posts as $post) {
    		
    		$this->assertSame('page', $post->getType());
    		
    	}
    	
    }
    
    public function testNewPost() {
    	
	    // Let's create a new post on the main blog
	    $new_post = new \Comodojo\WPAPI\WPPost($this->blog);
	    
	    // Chain like there's no tomorrow
	    $new_post->setTitle("Awesome new post")
	      ->setContent("This is a really awesome test post")
	      ->addTag("test") // Add a few tags and a category
	      ->addTag("awesomeness")
	      ->addTag("wpapi")
	      ->addCategory("wpapi")
	      ->setStatus("draft") // By default, all posts are saved as "draft"
	      ->save();
	      
	    $this->assertGreaterThan(0, intval($new_post->getID()));
    	
    }
    
    public function testNewPage() {
    	
	    // Let's create a new page on the main blog
	    $new_page = new \Comodojo\WPAPI\WPPost($this->blog);
	    
	    // A page is basically a post with a different 'type' value
	    $new_page->setTitle("Awesome new page")
	      ->setContent("This is a really awesome test page")
	      ->setType("page") // Yep, in order to create a page you just need to set the type
	      ->setStatus("draft")
	      ->save(); // Don't forget to save when you've done chaining
	      
	    $this->assertGreaterThan(0, intval($new_page->getID()));
	      
    	$this->assertSame('page', $new_page->getType());
    	
    }
    
}