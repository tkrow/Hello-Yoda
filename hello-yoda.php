<?php
/**
 * @package Hello_Yoda
 * @version 1.2.2 */
/*
Plugin Name: Hello Yoda
Plugin URI: https://github.com/tkrow/Hello-Yoda.git
Description: A random quote from Yoda will be displayed.
Author: Timothy Krow
Version: 1.2.2
*/

//On activiation, create the hello_yoda_quotes table
function hello_yoda_activation(){
	global $wpdb;
	$wpdb->query("CREATE TABLE {$wpdb->prefix}hello_yoda_quotes (
		id BIGINT AUTO_INCREMENT NOT NULL,
		quote LONGTEXT,
		quotee LONGTEXT,
		PRIMARY KEY (id))");
}
register_activation_hook(__FILE__, 'hello_yoda_activation');

// determine what the user's permission is
function hello_yoda_load_for_user(){
	if (current_user_can('manage_options')){
		return true;
	} else {
		return false;
	}
}

//prints database query results depending on user's permissions
function hello_yoda_display_quote(){
	global $wpdb;
	$resultItems;

	if(hello_yoda_load_for_user()){
		$results = $wpdb->get_results("SELECT id, quotee, quote FROM {$wpdb->prefix}hello_yoda_quotes WHERE quotee LIKE '%vader%'");
		foreach($results as $item){
			$result = $item->result;
			array_push($resultItems, $result);
		}
		echo "<pre>";
		print_r($resultItems);
		echo "</pre>";
	} else {
		$results = $wpdb->get_results("SELECT id, quotee, quote FROM {$wpdb->prefix}hello_yoda_quotes WHERE quotee LIKE '%yoda%'");
		foreach($results as $item){
			$result = $item->result;
			array_push($resultItems, $result);
		}
		echo "<pre>";
		print_r($resultItems);
		echo "</pre>";
	}
}

//Insert quote if neither box is empty and the button is pressed
function hello_yoda_quote_submit(){
	if('POST' === $_SERVER['REQUEST_METHOD']){
		global $wpdb;
		$quote = sanitize_text_field($_POST['quote']);
		$quotee = sanitize_text_field($_POST['quotee']);
		
		if($quote != "" || $quotee != ""){
			$wpdb->query( $wpdb->prepare(
				"INSERT INTO {$wpdb->prefix}hello_yoda_quotes 
				(quote, quotee) 
				VALUES (%s, %s)",
				$quote,
				$quotee
				) );		
		}		
	}
}

//Remove quote that is specified
function hello_yoda_quote_remove(){
	if('POST' === $_SERVER['REQUEST_METHOD']){
		global $wpdb;
		$id = sanitize_text_field($_POST['id']);

		if($id != ""){
			$wpdb->query($wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}hello_yoda_quotes WHERE id = '%d'", $id
			));
		}
	}
}

//add the main menu page
add_action('admin_menu','hello_yoda_menu_pages');
function hello_yoda_menu_pages(){
	add_menu_page('Hello Yoda', 'Yoda', 'read', 'hello-yoda-menu','hello_yoda_menu','dashicons-admin-site-alt3');
}

//HTML output to build the page
function hello_yoda_menu() {
	echo '<h1>The Hello Yoda Plugin by Timothy Krow</h1>
		  <p>"Pass on what you have learned."--Yoda</p>';
}

//add the remove quote subpage to the menu bar
if(!function_exists('hello_yoda_remove_quote_menu')){
	function hello_yoda_remove_quote_menu(){
		$hookname = add_submenu_page(
			'hello-yoda-menu', 
			'Hello Yoda Remove Quote', 
			'Remove Quote', 'read', 
			'hello-yoda-remove-quote', 
			'hello_yoda_remove_quote_page'
		);
		remove_submenu_page('hello-yoda-menu','hello-yoda-menu');
	add_action('load-' . $hookname, 'hello_yoda_quote_remove');
	}
	add_action('admin_menu', 'hello_yoda_remove_quote_menu');
}

//HTML output to build remove quote subpage
function hello_yoda_remove_quote_page(){
	echo '	<h1>Remove A Quote</h1>
		  	<form class="quote" action="" method="post">
				<p>Quote ID</p>
				<input name="id" id="id" type="number"><br /><br /><br />
				<input type="submit">
				<br /><br /><br /><br /><br />
			  </form>';
	echo hello_yoda_display_quote();
}

//HTML output to build add quote subpage
function hello_yoda_add_quote_page(){
	echo '	<h1>Add A Quote</h1>
		  	<form class="quote" action="" method="post">
				<p>Quote</p>
				<input name="quote" id="quote" type="text"><br /><br /><br />
				<p>Quotee</p>
				<input name="quotee" id="quotee" type="text"><br /><br /><br />
				<input type="submit">
		  	</form>';
}

//add the add quote subpage to the menu bar
if(!function_exists('hello_yoda_add_quote_menu')){
	function hello_yoda_add_quote_menu(){
		$hookname = add_submenu_page(
			'hello-yoda-menu', 
			'Hello Yoda Add Quote', 
			'Add Quote', 'read', 
			'hello-yoda-add-quote', 
			'hello_yoda_add_quote_page'
		);
		remove_submenu_page('hello-yoda-menu','hello-yoda-menu');
	add_action('load-' . $hookname, 'hello_yoda_quote_submit');
	}
	add_action('admin_menu', 'hello_yoda_add_quote_menu');
}





function hello_yoda_get_quote() {
	if(hello_yoda_load_for_user()){
		/** Vader Quotes */
		$quotes = "No, I am your father.
		I find your lack of faith disturbing.
		The Force is strong with this one.
		I am altering the deal, pray I do not alter it any further...
		You have failed me for the last time.";

		//Here we split it into lines
		$quotes = explode("\n", $quotes);

		global $wpdb;
		$results = $wpdb->get_results("SELECT quote FROM {$wpdb->prefix}hello_yoda_quotes WHERE quotee LIKE '%vader%'");

		foreach($results as $row){
			$quote = $row->quote;
			array_push($quotes, $quote);
		}

		// Randomly choose a line
		return wptexturize( $quotes[ mt_rand(0, count( $quotes ) -1 )]);
	} else {
		/** These are yoda Quotes */
		$quotes = "Always pass on what you have learned.
		Fear is the path to the dark side. Fear leads to anger. Anger leads to hate. Hate leads to suffering.
		Once you start down the dark path, forever will it dominate your destiny. Consume you, it will.
		In a dark place we find ourselves, and a little more knowledge lights our way.
		Death is a natural part of life. Rejoice for those around you who transform into the Force. Mourn them do not. Miss them do not. Attachment leads to jealously. The shadow of greed, that is.
		Powerful you have become, the dark side I sense in you.
		Train yourself to let go of everything you fear to lose.
		Truly wonderful the mind of a child is.
		Great warrior. Wars not make one great.
		You will find only what you bring in.";
	
		// Here we split it into lines.
		$quotes = explode( "\n", $quotes );
	
		global $wpdb;
		$results = $wpdb->get_results("SELECT quote FROM {$wpdb->prefix}hello_yoda_quotes WHERE quotee LIKE '%yoda%'");

		foreach($results as $row){
			$quote = $row->quote;
			array_push($quotes, $quote);
		}

		// And then randomly choose a line.
		return wptexturize( $quotes[ mt_rand( 0, count( $quotes ) - 1 ) ] );
	}
	
}

// This just echoes the chosen line, we'll position it later.
function hello_yoda() {
	$chosen = hello_yoda_get_quote();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	if(hello_yoda_load_for_user()){
		printf(
			'<p id="vader"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
			__( 'Yoda quotes:', 'hello-yoda' ),
			$lang,
			$chosen
		);
	} else {
		printf(
			'<p id="yoda"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
			__( 'Yoda quotes:', 'hello-yoda' ),
			$lang,
			$chosen
		);
	}
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'hello_yoda' );

// We need some CSS to position the paragraph.
function yoda_css() {
	if(hello_yoda_load_for_user()){
		echo "
	<style type='text/css'>
	.quote{
		text-align:center;
	}
	#vader {
		float: right;
		padding: 5px 10px;
		color: red;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #vader {
		float: left;
	}
	.block-editor-page #yoda {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#vader,
		.rtl #vader {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
	} else {
		echo "
	<style type='text/css'>
	#yoda {
		float: right;
		padding: 5px 10px;
		color: green;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #yoda {
		float: left;
	}
	.block-editor-page #yoda {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#yoda,
		.rtl #yoda {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
	}	
}

add_action( 'admin_head', 'yoda_css' );
