<?php

class OverrideWidgets {

	static function mi_get_search_form($echo=true){

		$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';
		$format = apply_filters( 'search_form_format', $format );

		if ( 'html5' == $format ) {

			$form = '<div class="location-search-widget"><form role="search" method="get" id="searchform" class="location-search" action="' . esc_url( home_url( '/' ) ) . '">
			<div class="search-field">
			<div class="destination-field">
			<input type="text" class="form-control" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" id="destination" />
			
			</div>
			</div>
			</form></div>';
		} else {
			$form = '<div class="location-search-widget"><form role="search" method="get" id="searchform" class="location-search" action="' . esc_url( home_url( '/' ) ) . '">
			<div class="search-field">
			<div class="destination-field">
			<input type="text" class="form-control" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" id="destination" />
			
			</div>
			</div>
			</form></div>';
		}

		return $form;
	}

	
}