<?php
/**
 * @package Hello_Yoda
 * @version 1.1.1
 */
/*
Plugin Name: Hello Yoda
Plugin URI: https://github.com/tkrow/Hello-Yoda.git
Description: A random quote from Yoda will be displayed.
Author: Timothy Krow
Version: 1.1.1
*/

function hello_yoda_load_for_user(){
	if (current_user_can('manage_options')){
		return true;
	} else {
		return false;
	}
}

function hello_yoda_plugin_html(){
	<div class="wrap">
		<h1>esc_html(hello_yoda_menu();</h1>
	</div>
}

add_action('admin_menu', 'hello_yoda_menu');
function hello_yoda_menu() {
	add_menu_page(
		'Hello Yoda',
		'Hello Yoda Menu',
		'read',
		'Hello Yoda Menu'
		plugin_dir_url(__FILE__).'public\images\yodaIcon.jpg'
	);
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
