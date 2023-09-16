<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Litho_Elements_Templates_Source extends Elementor\TemplateLibrary\Source_Base {

	/**
	 * Template prefix
	 *
	 * @var string
	 */
	protected $template_prefix = 'litho_';
	/**
	 * API Url
	 *
	 * @var string
	 */
	public static $litho_api_server = 'https://litholib.themezaa.com/wp-json/litho/v1';

	/**
	 * Return Litho templates prefix
	 *
	 * @return [type] [description]
	 */
	public function get_prefix() {
		return $this->template_prefix;
	}

	public function get_id() {
		return 'litho-templates';
	}

	public function get_title() {
		return __( 'Litho Templates', 'litho-addons' );
	}

	public function register_data() {}

	public function get_items( $args = array() ) {

		$url            = self::$litho_api_server . '/templates/';
		$response       = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body           = wp_remote_retrieve_body( $response );
		$body           = json_decode( $body, true );
		$templates_data = ! empty( $body['data'] ) ? $body['data'] : false;
		$templates      = array();

		if ( ! empty( $templates_data ) ) {
			foreach ( $templates_data as $template_data ) {
				$templates[] = $this->get_item( $template_data );
			}
		}

		if ( ! empty( $args ) ) {
			$templates = wp_list_filter( $templates, $args );
		}

		return $templates;
	}

	/**
	 * Return template data
	 *
	 * @param array $template_data Template data.
	 *
	 * @return array
	 */
	public function get_item( $template_data ) {
		return array(
			'template_id'     => $this->get_prefix() . $template_data['template_id'],
			'source'          => 'remote',
			'type'            => $template_data['type'],
			'subtype'         => $template_data['subtype'],
			'title'           => $template_data['title'],
			'thumbnail'       => $template_data['thumbnail'],
			'date'            => $template_data['date'],
			'author'          => $template_data['author'],
			'tags'            => $template_data['tags'],
			'isPro'           => ( 1 == $template_data['isPro'] ),
			'accessLevel'     => 0,
			'popularityIndex' => (int) $template_data['popularityIndex'],
			'trendIndex'      => (int) $template_data['trendIndex'],
			'hasPageSettings' => ( 1 == $template_data['hasPageSettings'] ),
			'url'             => $template_data['url'],
			'favorite'        => ( 1 == isset( $template_data['favorite'] ) ),
		);
	}

	public function save_item( $template_data ) {
		return false;
	}

	public function update_item( $new_data ) {
		return false;
	}

	public function delete_template( $template_id ) {
		return false;
	}

	public function export_template( $template_id ) {
		return false;
	}

	public function get_data( array $args, $context = 'display' ) {

		$id       = str_replace( $this->template_prefix, '', $args['template_id'] );
		$url      = self::$litho_api_server . '/template/' . $id;
		$response = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body     = wp_remote_retrieve_body( $response );
		$body     = json_decode( $body, true );
		$data     = ! empty( $body['data'] ) ? $body['data'] : false;

		$result = array();

		$result['content']       = $this->replace_elements_ids( $data );
		$result['content']       = $this->process_export_import_content( $result['content'], 'on_import' );
		$result['page_settings'] = array();

		return $result;
	}
}
