<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class `Litho_Carousel_Slider` doesn't exists yet.
if ( ! class_exists( 'Litho_Carousel_Slider' ) ) {
	/**
	* Define Class Litho_Carousel_Slider
	*/
	class Litho_Carousel_Slider extends WPML_Elementor_Module_With_Items {

		/**
		* @return string
		*/
		public function get_items_field() {
			return 'litho_carousel_slider';
		}

		/**
		* @return array
		*/
		public function get_fields() {
			return array(
				'litho_carousel_title',
				'litho_link' => array( 'url' ),
				'litho_carousel_subtitle',
				'litho_button_text',
				'litho_button_link' => array( 'url' ),
				'litho_second_button_text',
				'litho_second_button_link' => array( 'url' )
			);
		}

		/**
		* @param string $field
		*
		* @return string
		*/
		protected function get_title( $field ) {
			switch( $field ) {
				case 'litho_carousel_title':
					return esc_html__( 'Title', 'litho-addons' );
				case 'litho_link':
					return esc_html__( 'Link', 'litho-addons' );
				case 'litho_carousel_subtitle':
					return esc_html__( 'Subtitle', 'litho-addons' );
				case 'litho_button_text':
					return esc_html__( 'Button Text', 'litho-addons' );
				case 'litho_button_link':
					return esc_html__( 'Button Link', 'litho-addons' );
				case 'litho_second_button_text':
					return esc_html__( 'Second Button Text', 'litho-addons' );
				case 'litho_second_button_link':
					return esc_html__( 'Second Button Link', 'litho-addons' );
				default:
					return '';
			}
		}

		/**
		* @param string $field
		*
		* @return string
		*/
		protected function get_editor_type( $field ) {
			switch( $field ) {
				case 'litho_carousel_title':
				case 'litho_carousel_subtitle':
				case 'litho_button_text':
				case 'litho_second_button_text':
					return 'LINE';
				case 'litho_link':
				case 'litho_button_link':
				case 'litho_second_button_link':
					return 'LINK';
				default:
					return '';
			}
		}

		/**
		 * @param string|int $node_id
		 * @param array $element
		 * @param WPML_PB_String[] $strings
		 *
		 * @return WPML_PB_String[]
		 */
		public function get( $node_id, $element, $strings ) {
			foreach ( $this->get_items( $element ) as $item ) {
				foreach( $this->get_fields() as $key => $field ) {
					if ( ! is_array( $field ) ) {

						if ( ! isset( $item[ $field ] ) ) {
							continue;
						}

						$strings[] = new WPML_PB_String(
							$item[ $field ],
							$this->get_string_name( $node_id, $item[ $field ], $field, $element['widgetType'], $item['_id'] ),
							$this->get_title( $field ),
							$this->get_editor_type( $field )
						);
					} else {
						foreach ( $field as $inner_field ) {

							if ( ! isset( $item[ $key ][ $inner_field ] ) ) {
								continue;
							}

							$strings[] = new WPML_PB_String(
								$item[ $key ][ $inner_field ],
								$this->get_string_name( $node_id, $item[ $key ][ $inner_field ], $key, $element['widgetType'], $item['_id'] ),
								$this->get_title( $key ),
								$this->get_editor_type( $key )
							);
						}
					}
				}
			}
			return $strings;
		}

		/**
		 * @param int|string $node_id
		 * @param mixed $element
		 * @param WPML_PB_String $string
		 *
		 * @return mixed
		 */
		public function update( $node_id, $element, WPML_PB_String $string ) {
			foreach ( $this->get_items( $element ) as $key => $item ) {
				foreach( $this->get_fields() as $field_key => $field ) {
					if ( ! is_array( $field ) ) {

						if ( ! isset( $item[ $field ] ) ) {
							continue;
						}

						if ( $this->get_string_name( $node_id, $item[ $field ], $field, $element['widgetType'], $item['_id'] ) === $string->get_name() ) {
							$item[ $field ] = $string->get_value();
							$item['index'] = $key;
							return $item;
						}
					} else {
						foreach ( $field as $inner_field ) {
							if ( ! isset( $item[ $field_key ][ $inner_field ] ) ) {
								continue;
							}

							if ( $this->get_string_name( $node_id, $item[ $field_key ][ $inner_field ], $key, $element['widgetType'], $item['_id'] ) === $string->get_name() ) {
								$item[ $field_key ][ $inner_field ] = $string->get_value();
								$item['index'] = $key;
								return $item;
							}
						}
					}
				}
			}
		}
		
		/**
		 * @param string $node_id
		 * @param string $value
		 * @param string $type
		 * @param string $key
		 * @param string $item_id
		 *
		 * @return string
		 */
		private function get_string_name( $node_id, $value, $type, $key = '', $item_id = '' ) {
			return $key . '-' . $type . '-' . $node_id . '-' . $item_id;
		}
	}
}
