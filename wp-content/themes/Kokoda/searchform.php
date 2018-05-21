<?php
/**
 * Created by PhpStorm.
 * User: sonnguyen
 * Date: 21/5/18
 * Time: 10:44 PM
 */


$form = '<form role="search" method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
				<div>
					<label class="screen-reader-text" for="s">' . _x( 'Search for:', 'label' ) . '</label>
					<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="What are you looking for?"/>
					<input type="submit" id="searchsubmit" value="'. esc_attr_x( '', 'submit button' ) .'" />
				</div>
			</form>';


$result = apply_filters( 'get_search_form', $form );

if ( null === $result )
    $result = $form;


echo $result;