# comodojo/wpapi

[![Build Status](https://api.travis-ci.org/comodojo/wpapi.png)](http://travis-ci.org/comodojo/wpapi) [![Latest Stable Version](https://poser.pugx.org/comodojo/wpapi/v/stable)](https://packagist.org/packages/comodojo/wpapi) [![Total Downloads](https://poser.pugx.org/comodojo/wpapi/downloads)](https://packagist.org/packages/comodojo/wpapi) [![License](https://poser.pugx.org/comodojo/wpapi/license)](https://packagist.org/packages/comodojo/wpapi) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/comodojo/wpapi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/comodojo/wpapi/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/comodojo/wpapi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/comodojo/wpapi/?branch=master)

A [XML-RPC Wordpress API](https://codex.wordpress.org/XML-RPC_WordPress_API) wrapper. It uses the [comodojo/rpcclient](https://github.com/comodojo/rpcclient) to send requests.

This lib is intended to be used as a remote Wordpress handler.

## Installation

Install [composer](https://getcomposer.org/), then:

`` composer require comodojo/wpapi dev-master ``

## Basic usage

Getting recent posts from a blog:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WPAPI\WP( "www.example.org" );
    
    // Log in to the server
    if ($wp->login("awesome_user", "awesome_password")) {
    
	    // Get last 10 posts (methods are chainable)
	    $posts = $wp->getBlogByID(1)->getLatestPosts();
	    
	    // $posts is an Iterator object, so it can be used in a foreach statement
	    foreach ($posts as $p) {
	    
	    	echo "<h1>" . $p->getTitle()   . "</h1>";
	    	echo "<p>"  . $p->getContent() . "</p>";
	    	
	    }
	    
	}

} catch (\Exception $e) {
	
	/* something went wrong :( */

}

```

Retrieving profile informations:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WPAPI\WP( "www.example.org" );
    
    // Log in to the server
    if ($wp->login("awesome_user", "awesome_password")) {
    
	    // Get profile
	    $profile = $wp->getBlogByID(1)->getProfile();
	    
	    echo "<p>Hello " . $profile->getDisplayName() . "!</p>";
	    
	    // You can edit your profile informations
	    $profile->setFirstname("Arthur")->setLastname("Dent")->save();
	    
	    echo "<p>Don't forget your towel " . 
	    	$profile->getFirstname() . " " . $profile->getLastname() . "!</p>";
	    
	}

} catch (\Exception $e) {
	
	/* something went wrong :( */

}

```

Adding a new post:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WPAPI\WP( "www.example.org" );
    
    // Log in to the server
    if ($wp->login("awesome_user", "awesome_password")) {
    
	    // Let's create a new post on the main blog
	    $new_post = new \Comodojo\WPAPI\WPPost($wp->getBlogByID(1));
	    
	    // Chain like there's no tomorrow
	    $new_post->setTitle("Awesome new post")
	      ->setContent("This is a really awesome test post")
	      ->addTag("test") // Add a few tags and a category
	      ->addTag("awesomeness")
	      ->addTag("wpapi")
	      ->addCategory("wpapi")
	      ->setStatus("publish") // By default, all posts are saved as "draft"
	      ->save();
	      
	}

} catch (\Exception $e) {
	
	/* something went wrong :( */

}

```

Adding a new page:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WPAPI\WP( "www.example.org" );
    
    // Log in to the server
    if ($wp->login("awesome_user", "awesome_password")) {
    
	    // Let's create a new page on the main blog
	    $new_page = new \Comodojo\WPAPI\WPPost($wp->getBlogByID(1));
	    
	    // A page is basically a post with a different 'type' value
	    $new_page->setTitle("Awesome new page")
	      ->setContent("This is a really awesome test page")
	      ->setType("page") // Yep, in order to create a page you just need to set the type
	      ->setStatus("publish")
	      ->save(); // Don't forget to save when you've done chaining
	      
	}

} catch (\Exception $e) {
	
	/* something went wrong :( */

}

```

Retrieving comments for a specific post:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WPAPI\WP( "www.example.org" );
    
    // Log in to the server
    if ($wp->login("awesome_user", "awesome_password")) {
    
	    // Initialize a post object
	    $post = new \Comodojo\WPAPI\WPPost($wp->getBlogByID(1));
	    
	    // You can load a post starting from its ID
	    // and then retrieve its comments
	    $comments = $post->loadFromID(42)->getComments();
	    
	    // Show how many comments are available
	    echo "<h2>Total comment(s): ". $comments->getTotal() ."</h2>";
	    
	    // $comments is an Iterator object, so it can be used in a foreach statement
	    foreach ($comments as $c) {
	    
	    	echo "<h3>" . $c->getAuthor()  . "</h3>";
	    	echo "<p>"  . $c->getContent() . "</p>";
	    
	    }
	      
	}

} catch (\Exception $e) {
	
	/* something went wrong :( */

}

```

Fetching the media library:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WPAPI\WP( "www.example.org" );
    
    // Log in to the server
    if ($wp->login("awesome_user", "awesome_password")) {
    
	    // Initialize a media gallery iterator
	    // You can filter the results by the mime-type
	    $library = $wp->getBlogByID(1)->getMediaLibrary("image/jpeg");
	    
	    /* The iterator is meant to load each object on demand during iteration.
	     * This means that it won't do any query to Wordpress until you start
	     * fetching through the media library. It should save time and resources,
	     * but it won't allow you to know how many objects you have in the library
	     * until you've fetched them all.
	     */
	    foreach ($library as $img) {
	    
	    	echo "<img src='" . $img->getLink()  . "'/>";
	    
	    }
	    
	    echo "<p>Total images: " . $library->getFetchedItems() . "</p>";
	      
	}

} catch (\Exception $e) {
	
	/* something went wrong :( */

}

```

Add an image to a post:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WPAPI\WP( "www.example.org" );
    
    // Log in to the server
    if ($wp->login("awesome_user", "awesome_password")) {
    
	    // Load a post (you can use the 'loadFromID' method or
	    // set the post ID directly into the constructor)
	    $post = new \Comodojo\WPAPI\WPPost($wp->getBlogByID(1), 42);
	    
	    // Create an image
	    $image = new \Comodojo\WPAPI\WPMedia($wp->getBlogByID(1));
	    
	    // You can set the image object as an attachment to the post
	    // This only works BEFORE calling the 'upload' method
	    $image->setPostID($post->getID());
	    
	    // Upload a file directly to Wordpress
	    // if you have a buffer of data, you can use the 'uploadData' method instead
	    $image->upload("/path/to/file.jpg")->save();
	    
	    // You can both add the image as post thumbnail and append it to the content
	    $post->setTitle( $post->getTitle() . " (with image)" )
	    	->setThumbnail($image)
	    	->setContent( $post->getContent() . "<p><img src='" . $image->getLink() . "'/></p>" )
	    	->save();
	      
	}

} catch (\Exception $e) {
	
	/* something went wrong :( */

}

```

## Contributing

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

`` comodojo/wpapi `` is released under the MIT License (MIT). Please see [License File](LICENSE) for more information.