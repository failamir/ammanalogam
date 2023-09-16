<?php
namespace LithoAddons\Template_Library;

/**
 * Template Library Manager
 *
 * @package Litho
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Litho_Elements_Templates_Manager' ) ) {

	/**
	 * Define Litho_Elements_Templates_Manager class
	 */
	class Litho_Elements_Templates_Manager {

		/**
		 * Categories option name
		 *
		 * @var string
		 */
		protected $option = 'litho_elements_categories';

		/**
		 * Templates option name
		 *
		 * @var string
		 */
		protected $templates_option = 'litho_elements_templates';

		/**
		 * Litho templates API server
		 *
		 * @var string
		 */
		public static $api_server = 'https://litholib.themezaa.com/';

		/**
		 * Litho templates API route
		 *
		 * @var string
		 */
		public static $api_route = 'wp-json/litho/v1';

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			// Register litho-templates source.
			add_action( 'elementor/init', array( $this, 'register_templates_source' ) );

			if ( defined( '\Elementor\Api::LIBRARY_OPTION_KEY' ) ) {
				// Add Litho templates to Elementor categories list.
				add_filter( 'option_' . \Elementor\Api::LIBRARY_OPTION_KEY, array( $this, 'prepend_categories' ) );
			}

			// Process Litho template request.
			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.2.8', '>' ) ) {
				add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ), 20 );
			} else {
				add_action( 'wp_ajax_elementor_get_template_data', array( $this, 'force_litho_template_source' ), 0 );
			}

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
				add_filter( 'option_' . \Elementor\Api::LIBRARY_OPTION_KEY, array( $this, 'prepend_templates' ) );
			}
		}

		/**
		 * Register.
		 */
		public function register_templates_source() {

			include 'class-elementor-template-library-source.php';

			$elementor = \Elementor\Plugin::instance();
			$elementor->templates_manager->register_source( 'Litho_Elements_Templates_Source' );
		}

		/**
		 * Return transient key
		 *
		 * @return [type] [description]
		 */
		public function transient_key() {
			return $this->option . '_' . LITHO_ADDONS_PLUGIN_VERSION;
		}
		/**
		 * Return template transient key
		 *
		 * @return [type] [description]
		 */
		public function templates_transient_key() {
			return $this->templates_option . '_' . LITHO_ADDONS_PLUGIN_VERSION;
		}

		/**
		 * Retrieves categories list
		 *
		 * @return [type] [description]
		 */
		public function get_categories() {

			$categories = get_transient( $this->transient_key() );

			if ( ! $categories ) {
				$categories = $this->remote_get_categories();
				set_transient( $this->transient_key(), $categories, WEEK_IN_SECONDS );
			}

			return $categories;
		}

		/**
		 * Get categories from LithoElements API
		 *
		 * @return array
		 */
		public function remote_get_categories() {

			$url      = self::$api_server . self::$api_route . '/categories/';
			$response = wp_remote_get( $url, array( 'timeout' => 60 ) );
			$body     = wp_remote_retrieve_body( $response );
			$body     = json_decode( $body, true );

			return ! empty( $body['data'] ) ? $body['data'] : array();

		}

		/**
		 * Add Litho categories to Elementor templates list
		 *
		 * @param  [type] $library_data [description].
		 * @return [type]            [description].
		 */
		public function prepend_categories( $library_data ) {

			$litho_elementor_template_library_hide_default = get_theme_mod( 'litho_elementor_template_library_hide_default', '0' );

			$categories = $this->get_categories();

			if ( ! empty( $categories ) ) {

				if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.3.9', '>' ) ) {

					if ( '1' === $litho_elementor_template_library_hide_default ) {
						$library_data['types_data']['block']['categories'] = array_merge( $categories, $library_data['types_data']['block']['categories'] );
					} else {
						$library_data['types_data']['block']['categories'] = $categories;
					}
				} else {

					$library_data['categories'] = array_merge( $categories, $library_data['categories'] );
				}

				return $library_data;

			} else {

				return $library_data;
			}

		}
		/**
		 * Prepend Litho templates to Elementor templates list
		 *
		 * @param  [type] $library_data [description].
		 * @return [type]            [description].
		 */
		public function prepend_templates( $library_data ) {

			$litho_elementor_template_library_hide_default = get_theme_mod( 'litho_elementor_template_library_hide_default', '0' );

			$templates = $this->get_templates();

			if ( ! empty( $templates ) ) {

				if ( '1' === $litho_elementor_template_library_hide_default ) {

					$library_data['templates'] = array_merge( $library_data['templates'], $templates );

				} else {

					$library_data['templates'] = $templates;

				}
			}

			return $library_data;
		}
		/**
		 * Return templates
		 *
		 * @return [type] [description]
		 */
		public function get_templates() {

			$templates = get_transient( $this->templates_transient_key() );

			if ( ! $templates ) {
				$source = \Elementor\Plugin::instance()->templates_manager->get_source( 'litho-templates' );

				$templates = $source->get_items();

				if ( ! empty( $templates ) ) {

					$templates = array_map(
						function ( $template ) {
							$template['id']                = $template['template_id'];
							$template['tmpl_created']      = $template['date'];
							$template['tags']              = json_encode( $template['tags'] );
							$template['is_pro']            = $template['isPro'];
							$template['access_level']      = $template['accessLevel'];
							$template['popularity_index']  = $template['popularityIndex'];
							$template['trend_index']       = $template['trendIndex'];
							$template['has_page_settings'] = $template['hasPageSettings'];

							unset( $template['template_id'] );
							unset( $template['date'] );
							unset( $template['isPro'] );
							unset( $template['accessLevel'] );
							unset( $template['popularityIndex'] );
							unset( $template['trendIndex'] );
							unset( $template['hasPageSettings'] );

							return $template;
						},
						$templates
					);

					set_transient( $this->templates_transient_key(), $templates, WEEK_IN_SECONDS );

				} else {

					$templates = array();
				}
			}

			return $templates;
		}

		/**
		 * Register AJAX actions
		 *
		 * @param $ajax
		 */
		public function register_ajax_actions( $ajax ) {

			if ( ! isset( $_REQUEST['actions'] ) ) { // phpcs:ignore.
				return;
			}

			$actions = json_decode( stripslashes( $_REQUEST['actions'] ), true );// phpcs:ignore.
			$data    = false;

			foreach ( $actions as $id => $action_data ) {
				if ( ! isset( $action_data['get_template_data'] ) ) {
					$data = $action_data;
				}
			}

			if ( ! $data ) {
				return;
			}

			if ( ! isset( $data['data'] ) ) {
				return;
			}

			$data = $data['data'];

			if ( empty( $data['template_id'] ) ) {
				return;
			}

			if ( false === strpos( $data['template_id'], 'litho_' ) ) {
				return;
			}

			$ajax->register_ajax_action( 'get_template_data', array( $this, 'get_litho_template_data' ) );
		}

		/**
		 * Get litho template data.
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		public function get_litho_template_data( $args ) {

			$source = \Elementor\Plugin::instance()->templates_manager->get_source( 'litho-templates' );

			$data = $source->get_data( $args );

			return $data;
		}

		/**
		 * Return litho template data insted of elementor template.
		 *
		 * @return [type] [description]
		 */
		public function force_litho_template_source() {

			if ( empty( $_REQUEST['template_id'] ) ) { // phpcs:ignore.
				return;
			}

			if ( false === strpos( $_REQUEST['template_id'], 'litho_' ) ) {// phpcs:ignore.
				return;
			}

			$_REQUEST['source'] = 'litho-templates';
		}
	}

	$litho_elements_templates_manager = new Litho_Elements_Templates_Manager();
}
