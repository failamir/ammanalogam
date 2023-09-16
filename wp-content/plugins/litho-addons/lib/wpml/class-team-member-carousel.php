<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class `Litho_Team_Member_Carousel` doesn't exists yet.
if ( ! class_exists( 'Litho_Team_Member_Carousel' ) ) {
	/**
	* Define Class Litho_Team_Member_Carousel
	*/
	class Litho_Team_Member_Carousel extends WPML_Elementor_Module_With_Items {
	 
		/**
		* @return string
		*/
		public function get_items_field() {
			return 'litho_team_member_carousel';
		}

	  	/**
		* @return array
		*/
		public function get_fields() {
			return array(
				'litho_team_member_full_name',
				'litho_team_member_position',
				'litho_team_member_social_facebook_link' => array( 'url' ),
				'litho_team_member_social_facebook_label',
				'litho_team_member_social_instagram_link' => array( 'url' ),
				'litho_team_member_social_instagram_label',
				'litho_team_member_social_twitter_link' => array( 'url' ),
				'litho_team_member_social_twitter_label',
				'litho_team_member_social_pinterest_link' => array( 'url' ),
				'litho_team_member_social_pinterest_label',
				'litho_team_member_social_linkedin_link' => array( 'url' ),
				'litho_team_member_social_linkedin_label'
			);
		}
	 
	  	/**
		* @param string $field
		*
		* @return string
		*/
		protected function get_title( $field ) {
			switch( $field ) {
				case 'litho_team_member_full_name':
					return esc_html__( 'Full Name', 'litho-addons' );
				case 'litho_team_member_position':
					return esc_html__( 'Member Position', 'litho-addons' );
				case 'litho_team_member_social_facebook_link':
					return esc_html__( 'Facebook URL', 'litho-addons' );
				case 'litho_team_member_social_facebook_label':
					return esc_html__( 'Facebook Label', 'litho-addons' );
				case 'litho_team_member_social_instagram_link':
					return esc_html__( 'Instagram URL', 'litho-addons' );
				case 'litho_team_member_social_instagram_label':
					return esc_html__( 'Instagram Label', 'litho-addons' );
				case 'litho_team_member_social_twitter_link':
					return esc_html__( 'Twitter URL', 'litho-addons' );
				case 'litho_team_member_social_twitter_label':
					return esc_html__( 'Twitter Label', 'litho-addons' );
				case 'litho_team_member_social_pinterest_link':
					return esc_html__( 'Pinterest URL', 'litho-addons' );
				case 'litho_team_member_social_pinterest_label':
					return esc_html__( 'Pinterest Label', 'litho-addons' );
				case 'litho_team_member_social_linkedin_link':
					return esc_html__( 'Linkedin URL', 'litho-addons' );
				case 'litho_team_member_social_linkedin_label':
					return esc_html__( 'Twitter Label', 'litho-addons' );
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
				case 'litho_team_member_full_name':
				case 'litho_team_member_position':
				case 'litho_team_member_social_facebook_label':
				case 'litho_team_member_social_instagram_label':
				case 'litho_team_member_social_twitter_label':
				case 'litho_team_member_social_pinterest_label':
				case 'litho_team_member_social_linkedin_label':
					return 'LINE';
				case 'litho_team_member_social_facebook_link':
				case 'litho_team_member_social_instagram_link':
				case 'litho_team_member_social_twitter_link':
				case 'litho_team_member_social_pinterest_link':
				case 'litho_team_member_social_linkedin_link':
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
