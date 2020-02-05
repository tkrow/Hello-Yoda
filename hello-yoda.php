<?php
/**
 * @package Hello_Yoda
 * @version 1.0.1
 */
/*
Plugin Name: Hello Yoda
Plugin URI: http://wordpress.org/plugins/hello-yoda/
Description: A random quote from Yoda will be displayed.
Author: Timothy Krow
Version: 1.0.1
*/

function hello_yoda_get_quote() {
	/** These are the lyrics to Hello Dolly */
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

// This just echoes the chosen line, we'll position it later.
function hello_yoda() {
	$chosen = hello_yoda_get_quote();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="yoda"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Yoda quotes:', 'hello-yoda' ),
		$lang,
		$chosen
	);
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'hello_yoda' );

// We need some CSS to position the paragraph.
function yoda_css() {
	echo "
	<style type='text/css'>
	#yoda {
		float: right;
		padding: 5px 10px;
		text-color: green;
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

add_action( 'admin_head', 'yoda_css' );
