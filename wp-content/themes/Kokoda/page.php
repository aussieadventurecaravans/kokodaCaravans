<?php 

get_header();

if (is_page('home')) :

	get_template_part('content','home');
	
else :

	get_template_part('content','page');

endif;

get_footer(); 

?>