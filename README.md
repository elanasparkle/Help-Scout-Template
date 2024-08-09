# HelpScoutDesk Theme Override steps

Copy the hsd_templates folder into your child themes directory.

# HelpScoutDesk form submission on seperate page

Add the following page id's to the below lines to the location of where your [helpscout_desk] shorcode is. 
File is located under /hsd_templates/conversation_form
https://github.com/elanasparkle/Help-Scout-Template/blob/cbda4f6eeb4d8ffcc167660d073a9bdc9343dd42/hsd_templates/shortcodes/conversation_form.php#L8

If you don't want the form fields to be on the page you have your tickets displayed remove Line 7,8,9 and 10 from that file. 

# CSS File Enque
If you want to add the CSS you need to enque that file in your functions.php file in your theme directory

````php
function HSD_scripts() {
	// custom stylesheet
	wp_enqueue_style( 'hsd-apps-style', get_template_directory_uri() . '/hsd_templates/style.css', array(), filemtime(get_template_directory() . '/hsd_templates/style.css'));
}
add_action( 'wp_enqueue_scripts', 'HSD_scripts' );
````
# Checkout how to add custom fields

https://gist.github.com/elanasparkle/980c24d7196239a2d2449365d7177f8c

Or more advanced check out: 

https://gist.github.com/elanasparkle/eb0eac7b70bf4c1f6bef7ed5b8632255
