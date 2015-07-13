<?php

class WPCommentTest extends \PHPUnit_Framework_TestCase {
	
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
				
				self::$post->setTitle("Test COMMENT")
					->setCreationDate(time() - 14400)
					->setStatus("publish")
					->setType("post")
					->setFormat("standard")
					->setContent("Post with comments")
					->addCategory("wptest")
					->addTag("wpapi")
					->save();
    			
    			break;
		    	
    		}
    		
    	}
    
    }
    
    public function testWPCommentCreate() {
    	
    	if (self::$wp->isLogged()) {
				
			for ($i=0; $i<3; $i++) {
				
				$comment = new \Comodojo\WPAPI\WPComment(self::$post);
				
				$comment->setContent("Test comment n." . $i)
    				->setStatus("hold")
					->save();
					
				array_push(self::$ids, $comment->getID());
				
			}
    		
    		$comments = self::$post->getComments();
			
			$this->assertSame($comments->getLength(), 3);
			
    	}
    	
    }

   /**
    * @depends testWPCommentCreate 
    */    
    public function testWPCommentData() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$comments = self::$post->getComments();
			
			$this->assertSame($comments->getApproved(), 0);
			
			$this->assertSame($comments->getAwaiting(), 3);
			
			$this->assertSame($comments->getSpam(), 0);
    	
    		$profile  = self::$blog->getProfile();
			
			foreach ($comments as $id => $comment) {
				
				$this->assertTrue(in_array(intval($id), self::$ids));
				
				$i = array_search($comment->getID(), self::$ids);
				
				$this->assertSame($comment->getContent(), "Test comment n." . $i);
				
				$this->assertSame($comment->getStatus(), "hold");
				
				$this->assertSame($comment->getAuthor(), $profile->getDisplayName());
				
				$this->assertSame($comment->getAuthorURL(), $profile->getURL());
				
				$this->assertSame($comment->getAuthorEmail(), $profile->getEmail());
				
				$this->assertSame($comment->getUser()->getID(), $profile->getID());
				
			}
			
			$this->assertSame($comments->getFetchedItems(), 3);
			
    	}
    	
    }

   /**
    * @depends testWPCommentData 
    */    
    public function testWPCommentModify() {
    	
    	if (self::$wp->isLogged()) {
				
			for ($i=0; $i<3; $i++) {
				
				$comment = new \Comodojo\WPAPI\WPComment(self::$post, self::$ids[$i]);
				
				$text = "Test modified comment n." . $i . 
						" - Created: " . $comment->getDate("Y-m-d H:i:s") . 
						" - Type: "    . $comment->getType() . 
						" - Link: "    . $comment->getLink();
				
				$comment->setContent($text)
    				->setStatus("approve")
					->save();
				
				$this->assertSame($comment->getContent(), $text);
				
			}
    		
    		$comments = self::$post->getComments();
			
			$this->assertSame($comments->getApproved(), 3);
			
			$this->assertSame($comments->getAwaiting(), 0);
			
			$this->assertSame($comments->getSpam(), 0);
			
    	}
    	
    }

   /**
    * @depends testWPCommentModify 
    */    
    public function testWPCommentReply() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$ids = self::$ids;
    		
    		foreach($ids as $id) {
    			
    			$comment = new \Comodojo\WPAPI\WPComment(self::$post);
    			
    			$comment->loadFromID($id);
				
				for ($i=0; $i<3; $i++) {
					
					$text = "Reply n." . $i . " to comment with ID " . $comment->getID();
				
					$reply = new \Comodojo\WPAPI\WPComment(self::$post);
					
					$reply->setContent($text)
	    				->setParent($comment)
						->save();
					
					$this->assertSame($reply->getContent(), $text);
					
					$this->assertSame($reply->getParent()->getID(), $comment->getID());
					
					array_push(self::$ids, $reply->getID());
					
				}
			
    		}
			
    	}
    	
    }

   /**
    * @depends testWPCommentReply 
    */    
    public function testWPCommentDelete() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$comments = self::$post->getComments();
			
			$this->assertSame($comments->getLength(), 12);
    		
    		foreach(self::$ids as $id) {
    			
    			$comment = new \Comodojo\WPAPI\WPComment(self::$post);
    			
    			$comment->loadFromID($id);
				
				if (!is_null($comment->getParent())) {
					
					$this->assertTrue($comment->delete());
					
				}
				
			}
    		
    		$comments = self::$post->getComments();
			
			$this->assertSame($comments->getLength(), 3);
    		
    		foreach(self::$ids as $id) {
    			
    			$comment = new \Comodojo\WPAPI\WPComment(self::$post);
    			
    			$comment = $comment->loadFromID($id);
				
				if (!is_null($comment))
					$this->assertTrue($comment->delete());
				
			}
    		
    		$comments = self::$post->getComments();
			
			$this->assertSame($comments->getLength(), 0);
			
    	}
    	
    }
    
    public static function tearDownAfterClass() {
        
    	if (self::$wp->isLogged()) {
    		
        	self::$post->delete();
        	
    	}
    
    }
    
}