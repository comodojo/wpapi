# comodojo/wpapi
A [XML-RPC Wordpress API](https://codex.wordpress.org/XML-RPC_WordPress_API) wrapper. It uses the [comodojo/rpcclient](https://github.com/comodojo/rpcclient) to send requests.

This lib is intended to be used as a remote Wordpress handler.

## Installation

Install [composer](https://getcomposer.org/), then:

`` composer require comodojo/wpapi dev-master ``

## Usage example

Getting recent posts from a blog and adding a new page:

```php
try {

    // Create a new Wordpress instance
    $wp = new \Comodojo\WP\WP( "www.example.org" );
    
    // Log in to the server
    $wp->login("awesome_user", "awesome_password");
    
    // Get last 10 posts (methods are chainable)
    $posts = $wp->getBlogByID(0)->getLatestPosts();
    
    // Let's create a new post on the main blog
    $new_post = new \Comodojo\WP\WPPost($wp->getBlogByID(0));
    
    // Chain like there's no tomorrow
    $new_post->setTitle("Awesome new page")
      ->setContent("This is a really awesome test page")
      ->setType("page") // Yep, I'm creating a page
      ->save();

} catch (\Exception $e) {
	
	/* something did not work :( */

}

```
