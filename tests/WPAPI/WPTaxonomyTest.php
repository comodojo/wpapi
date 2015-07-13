<?php

class WPTaxonomyTest extends \PHPUnit_Framework_TestCase {
	
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
    
    public function testWPTaxonomy() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$taxonomy = new \Comodojo\WPAPI\WPTaxonomy(self::$blog, "category");
    		
    		$tax_copy = new \Comodojo\WPAPI\WPTaxonomy(self::$blog);
    		
    		$tax_copy->loadData($taxonomy->getData());
    		
    		$this->assertSame($taxonomy->getID(), $tax_copy->getID());
    		
    		$this->assertSame($taxonomy->getName(), $tax_copy->getName());
    		
    		$this->assertSame($taxonomy->getLabel(), $tax_copy->getLabel());
    		
    		$this->assertSame($taxonomy->isHierarchical(), $tax_copy->isHierarchical());
    		
    		$this->assertSame($taxonomy->isPublic(), $tax_copy->isPublic());
    		
    		$this->assertSame($taxonomy->isShowUI(), $tax_copy->isShowUI());
    		
    		$this->assertSame($taxonomy->isBuiltIn(), $tax_copy->isBuiltIn());
    		
    		$this->assertSame($taxonomy->isBuiltIn(), $tax_copy->isBuiltIn());
    		
    		foreach ($taxonomy->getLabels() as $id => $value) {
    			
    			$this->assertTrue(in_array($value, array_values($tax_copy->getLabels())));
    			
    		}
    		
    		foreach ($taxonomy->getCapabilities() as $id => $value) {
    			
    			$this->assertTrue(in_array($value, array_values($tax_copy->getCapabilities())));
    			
    		}
    		
    		foreach ($taxonomy->getObjectType() as $id => $value) {
    			
    			$this->assertTrue(in_array($value, array_values($tax_copy->getObjectType())));
    			
    		}
    		
    		// Unused methods
    		$this->assertFalse($tax_copy->save()->delete());
			
    	}
    	
    }
    
    public function testWPTermCreate() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$taxonomy = new \Comodojo\WPAPI\WPTaxonomy(self::$blog, "category");
    		
    		$term = new \Comodojo\WPAPI\WPTerm($taxonomy);
    		
    		$term->setName("WPTerm test")
    			->setDescription("Creating a new term")
    			->setSlug("wpterm")
    			->save();
    			
    		$this->assertGreaterThan(0, $term->getID());
			
    	}
    	
    }
    
    public function testWPTermData() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$taxonomy = new \Comodojo\WPAPI\WPTaxonomy(self::$blog, "category");
    		
    		$terms = $taxonomy->getTerms("WPTerm test");
    		
    		$this->assertSame(1, count($terms));
    		
    		// Check term data
    		
    		$term = $terms[0];
    		
    		$this->assertSame($term->getName(), "WPTerm test");
    		
    		$this->assertSame($term->getSlug(), "wpterm");
    		
    		$this->assertSame($term->getDescription(), "Creating a new term");
    		
    		// Check with a copy
    		
    		$copy = new \Comodojo\WPAPI\WPTerm($taxonomy, $term->getID());
    		
    		$this->assertSame($term->getID(), $copy->getID());
    		
    		$this->assertSame($term->getName(), $copy->getName());
    		
    		$this->assertSame($term->getSlug(), $copy->getSlug());
    		
    		$this->assertSame($term->getDescription(), $copy->getDescription());
    		
    		$this->assertSame($term->getTaxonomyRelationID(), $copy->getTaxonomyRelationID());
    		
    		$this->assertSame($term->getCount(), $copy->getCount());
			
    	}
    	
    }
    
    public function testWPTermModify() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$taxonomy = new \Comodojo\WPAPI\WPTaxonomy(self::$blog, "category");
    		
    		$terms = $taxonomy->getTerms("WPTerm test");
    		
    		$this->assertSame(1, count($terms));
    		
    		// Check term data
    		
    		$term = $terms[0];
    		
    		$term->setName("WPTerm test modified")->save();
    		
    		$term = new \Comodojo\WPAPI\WPTerm($taxonomy, $term->getID());
    		
    		$this->assertSame($term->getName(), "WPTerm test modified");
			
    	}
    	
    }
    
    public function testWPTermParent() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$taxonomy = new \Comodojo\WPAPI\WPTaxonomy(self::$blog, "category");
    		
    		$terms = $taxonomy->getTerms("WPTerm test");
    		
    		$this->assertSame(1, count($terms));
    		
    		$term = $terms[0];
    		
    		$child = new \Comodojo\WPAPI\WPTerm($taxonomy);
    		
    		$child->setName("WPTerm test child")
    			->setParent($term)
    			->save();
    		
    		$this->assertSame($child->getName(), "WPTerm test child");
    		
    		$this->assertSame($child->getParent()->getID(), $term->getID());
			
    	}
    	
    }
    
    public function testWPTermDelete() {
    	
    	if (self::$wp->isLogged()) {
    		
    		$taxonomy = new \Comodojo\WPAPI\WPTaxonomy(self::$blog, "category");
    		
    		$terms = $taxonomy->getTerms("WPTerm test");
    		
    		$this->assertSame(2, count($terms));
    		
    		foreach ($terms as $term) {
    			
    			$this->assertTrue($term->delete());
    			
    		}
    		
    		$terms = $taxonomy->getTerms("WPTerm test");
    		
    		$this->assertSame(0, count($terms));
			
    	}
    	
    }
    
}