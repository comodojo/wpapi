<?php

class WPAPITest extends \PHPUnit_Framework_TestCase {
	
    protected $wp = null;
    
    protected function setUp() {
    	
        $address = "http://localhost/";
        $user = "admin";
        $pass = "admin";
        
        $this->wp = new \Comodojo\WPAPI\WP($address);
        
        $this->wp->login($user, $pass);
    
    }
    
    public function testGetLatestPosts() {
    	
    	$posts = $this->wp->getBlogByID(1)->getLatestPosts();
    	
    	foreach ($posts as $post) {
    		
    		$this->assertSame('publish', $post->getStatus());
    		
    	}
    	
    }
    
    public function testGetPages() {
    	
    	$posts = $this->wp->getBlogByID(1)->getPages();
    	
    	foreach ($posts as $post) {
    		
    		$this->assertSame('page', $post->getType());
    		
    	}
    	
    }
    
    public function testProfile() {
    	
	    // Get profile
	    $profile = $this->wp->getBlogByID(1)->getProfile();
	    
	    // You can edit your profile informations
	    $profile->setFirstname("Arthur")->setLastname("Dent")->save();
	    
    	$this->assertSame('Arthur', $profile->getFirstname());
	    
    	$this->assertSame('Dent', $profile->getLastname());
    	
    }
    
    public function testNewPost() {
    	
	    // Let's create a new post on the main blog
	    $new_post = new \Comodojo\WPAPI\WPPost($this->wp->getBlogByID(1));
	    
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
	    $new_page = new \Comodojo\WPAPI\WPPost($this->wp->getBlogByID(1));
	    
	    // A page is basically a post with a different 'type' value
	    $new_page->setTitle("Awesome new page")
	      ->setContent("This is a really awesome test page")
	      ->setType("page") // Yep, in order to create a page you just need to set the type
	      ->setStatus("draft")
	      ->save(); // Don't forget to save when you've done chaining
	      
	    $this->assertGreaterThan(0, intval($new_page->getID()));
	      
    	$this->assertSame('page', $new_page->getType());
    	
    }
    
    public function testAddImage() {
    	
	    // Let's create a new post on the main blog
	    $new_post = new \Comodojo\WPAPI\WPPost($this->wp->getBlogByID(1));
	    
	    // Chain like there's no tomorrow
	    $new_post->setTitle("Awesome new post")
	      ->setContent("This is a really awesome test post")
	      ->addTag("test") // Add a few tags and a category
	      ->addTag("awesomeness")
	      ->addTag("wpapi")
	      ->addTag("image")
	      ->addCategory("wpapi")
	      ->setStatus("draft") // By default, all posts are saved as "draft"
	      ->save();
	    
	    // Create an image
	    $image = new \Comodojo\WPAPI\WPMedia($this->wp->getBlogByID(1));
	    
	    // You can set the image object as an attachment to the post
	    // This only works BEFORE calling the 'upload' method
	    $image->setPostID($new_post->getID());
	    
	    // Upload a file directly to Wordpress
	    // if you have a buffer of data, you can use the 'uploadData' method instead
	    $image->upload(__DIR__."/../resources/keepcalm.png");
	    
	    // You can both add the image as post thumbnail and append it to the content
	    $new_post->setTitle( $new_post->getTitle() . " (with image)" )
	    	->setThumbnail($image)
	    	->setContent( $new_post->getContent() . "<p><img src='" . $image->getLink() . "'/></p>" )
	    	->save();
	    	
	    $this->assertGreaterThan(0, intval($image->getID()));
	    	
	    $this->assertSame($new_post->getThumbnail()->getID(), intval($image->getID()));
    	
    }
    
    public function testAddComment() {
    	
	    // Let's create a new post on the main blog
	    $new_post = new \Comodojo\WPAPI\WPPost($this->wp->getBlogByID(1));
	    
	    // Chain like there's no tomorrow
	    $new_post->setTitle("Awesome new post with comments")
	      ->setContent("This is a really awesome test post")
	      ->addTag("test") // Add a few tags and a category
	      ->addTag("awesomeness")
	      ->addTag("wpapi")
	      ->addTag("image")
	      ->addCategory("wpapi")
	      ->setStatus("draft") // By default, all posts are saved as "draft"
	      ->save();
	    
	    // Create a a comment
	    $comment = new \Comodojo\WPAPI\WPComment($new_post);
	    
	    $comment->setContent("Test comment")->save();
	    	
	    $this->assertGreaterThan(0, intval($comment->getID()));
	      
    	$this->assertSame('Test comment', $comment->getContent());
    	
    }
    
}