<?php
class ResetQuery{
	static function mi_my_query_post_type($query) {
		if ( (is_category() || is_tag()) && false == $query->query_vars['suppress_filters'] )
			$query->set( 'post_type', array( 'post', 'mi_portfolio' ) );
		return $query;
	}
}