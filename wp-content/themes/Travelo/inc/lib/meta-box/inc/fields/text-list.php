<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'RWMB_Text_List_Field' ) )
{
	class RWMB_Text_List_Field extends RWMB_Field
	{
		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static function html( $meta, $field )
		{
			$html = '';
			if ( ! empty( $field['title'] ) ) $html .= '<h4>' . $field['title'] . '</h4>';
			$input = '<label><input type="text" class="rwmb-text-list" name="%s" id="%s" value="%s" placeholder="%s" />  %s</label><br />';
			$textarea = '<label><textarea class="rwmb-text-list" name="%s" id="%s"/>%s</textarea>  %s</label><br />';

			$i = 0;
			foreach ( $field['options'] as $value => $label )
			{				
				$meta_value = isset( $meta[ $i ] )?$meta[ $i ]:'';
				if ( $value != 'testimonial' ) {
					$html .= sprintf(
						$input,
						$field['field_name'],
						$field['id'],
						$meta_value,
						$value,
						$label
					);
				} else {
					$html .= sprintf(
						$textarea,
						$field['field_name'],
						$field['id'],
						$meta_value,
						$label
					);
				}
				$i++;		
			}
			return $html;
		}

		/**
		 * Get meta value
		 * If field is cloneable, value is saved as a single entry in DB
		 * Otherwise value is saved as multiple entries (for backward compatibility)
		 *
		 * @see "save" method for better understanding
		 *
		 * TODO: A good way to ALWAYS save values in single entry in DB, while maintaining backward compatibility
		 *
		 * @param $post_id
		 * @param $saved
		 * @param $field
		 *
		 * @return array
		 */
		static function meta( $post_id, $saved, $field )
		{
			$single = $field['clone'] || !$field['multiple'];
			$meta = get_post_meta( $post_id, $field['id'], $single );
			$meta = ( !$saved && '' === $meta || array() === $meta ) ? $field['std'] : $meta;

			$meta_temp = array(array());
			if ( is_array( $meta ) ) {
				foreach ( $meta as $meta_key=>$meta_value ) {
					$meta_temp[$meta_key] = array_map( 'esc_attr', (array) $meta_value );
				}
			}

			return $meta_temp;
		}

		/**
		 * Save meta value
		 * If field is cloneable, value is saved as a single entry in DB
		 * Otherwise value is saved as multiple entries (for backward compatibility)
		 *
		 * TODO: A good way to ALWAYS save values in single entry in DB, while maintaining backward compatibility
		 *
		 * @param $new
		 * @param $old
		 * @param $post_id
		 * @param $field
		 */
		static function save( $new, $old, $post_id, $field )
		{
			if ( !$field['clone'] )
			{
				parent::save( $new, $old, $post_id, $field );
				return;
			}

			if ( empty( $new ) )
				delete_post_meta( $post_id, $field['id'] );
			else
				update_post_meta( $post_id, $field['id'], $new );
		}

		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function normalize_field( $field )
		{
			$field['multiple'] = true;
			$field['field_name'] = $field['id'];
			if ( !$field['clone'] )
				$field['field_name'] .= '[]';
			return $field;
		}
	}
}
