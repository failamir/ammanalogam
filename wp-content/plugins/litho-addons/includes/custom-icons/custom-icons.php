<?php
namespace LithoAddons\Custom_icons;

/**
 * Custom Icons initialize
 *
 * @package Litho
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class `Custom_icons` doesn't exists yet.
if ( ! class_exists( 'Custom_icons' ) ) {

	/**
	 * Define Custom_icons class
	 */
	class Custom_icons {

		public function __construct() {
			$this->add_hooks();
		}

		private function add_hooks() {
			// Bind custom icons with elementor.
			add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'litho_custom_icons' ] );
			// Editor enqueue scripts.
			add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'litho_editor_custom_styles_scripts' ] );
			// Frontend enqueue scripts.
			add_action( 'elementor/frontend/after_register_scripts', [ $this, 'litho_frontend_custom_styles_scripts' ], 999 );
		}

		public function litho_editor_custom_styles_scripts() {

			wp_register_style(
				'themify-icons',
				LITHO_ADDONS_INCLUDES_DIR . '/assets/css/themify-icons.css',
				array(),
				LITHO_ADDONS_PLUGIN_VERSION
			);
			wp_enqueue_style( 'themify-icons' );

			wp_register_style(
				'simple-line-icons',
				LITHO_ADDONS_INCLUDES_DIR . '/assets/css/simple-line-icons.css',
				array(),
				LITHO_ADDONS_PLUGIN_VERSION
			);
			wp_enqueue_style( 'simple-line-icons' );

			wp_register_style(
				'et-line-icons',
				LITHO_ADDONS_INCLUDES_DIR . '/assets/css/et-line-icons.css',
				array(),
				LITHO_ADDONS_PLUGIN_VERSION
			);
			wp_enqueue_style( 'et-line-icons' );

			wp_register_style(
				'iconsmind-line-icons',
				LITHO_ADDONS_INCLUDES_DIR . '/assets/css/iconsmind-line.css',
				array(),
				LITHO_ADDONS_PLUGIN_VERSION
			);
			wp_enqueue_style( 'iconsmind-line-icons' );

			wp_register_style(
				'iconsmind-solid-icons',
				LITHO_ADDONS_INCLUDES_DIR . '/assets/css/iconsmind-solid.css',
				array(),
				LITHO_ADDONS_PLUGIN_VERSION
			);
			wp_enqueue_style( 'iconsmind-solid-icons' );

			wp_register_style(
				'feather-icons',
				LITHO_ADDONS_INCLUDES_DIR . '/assets/css/feather-icons.css',
				array(),
				LITHO_ADDONS_PLUGIN_VERSION
			);
			wp_enqueue_style( 'feather-icons' );
		}

		public function litho_frontend_custom_styles_scripts() {

			if ( litho_load_stylesheet_by_key( 'themify-icons' ) ) {
				wp_register_style(
					'themify-icons',
					LITHO_ADDONS_INCLUDES_DIR . '/assets/css/themify-icons.css',
					array(),
					LITHO_ADDONS_PLUGIN_VERSION
				);
				wp_enqueue_style( 'themify-icons' );
			}

			if ( litho_load_stylesheet_by_key( 'simple-line-icons' ) ) {
				wp_register_style(
					'simple-line-icons',
					LITHO_ADDONS_INCLUDES_DIR . '/assets/css/simple-line-icons.css',
					array(),
					LITHO_ADDONS_PLUGIN_VERSION
				);
				wp_enqueue_style( 'simple-line-icons' );
			}

			if ( litho_load_stylesheet_by_key( 'et-line-icons' ) ) {
				wp_register_style(
					'et-line-icons',
					LITHO_ADDONS_INCLUDES_DIR . '/assets/css/et-line-icons.css',
					array(),
					LITHO_ADDONS_PLUGIN_VERSION
				);
				wp_enqueue_style( 'et-line-icons' );
			}

			if ( litho_load_stylesheet_by_key( 'iconsmind-line-icons' ) ) {
				wp_register_style(
					'iconsmind-line-icons',
					LITHO_ADDONS_INCLUDES_DIR . '/assets/css/iconsmind-line.css',
					array(),
					LITHO_ADDONS_PLUGIN_VERSION
				);
				wp_enqueue_style( 'iconsmind-line-icons' );
			}

			if ( litho_load_stylesheet_by_key( 'iconsmind-solid-icons' ) ) {
				wp_register_style(
					'iconsmind-solid-icons',
					LITHO_ADDONS_INCLUDES_DIR . '/assets/css/iconsmind-solid.css',
					array(),
					LITHO_ADDONS_PLUGIN_VERSION
				);
				wp_enqueue_style( 'iconsmind-solid-icons' );
			}

			if ( litho_load_stylesheet_by_key( 'feather-icons' ) ) {
				wp_register_style(
					'feather-icons',
					LITHO_ADDONS_INCLUDES_DIR . '/assets/css/feather-icons.css',
					array(),
					LITHO_ADDONS_PLUGIN_VERSION
				);
				wp_enqueue_style( 'feather-icons' );
			}
		}

		public function litho_custom_icons( $settings ) {

			$config                    = [];
			$set_config                = [];
			$themify_json_path         = LITHO_ADDONS_INCLUDES_DIR . '/assets/font_json/themify.json';
			$etline_json_path          = LITHO_ADDONS_INCLUDES_DIR . '/assets/font_json/etline.json';
			$simpleline_json_path      = LITHO_ADDONS_INCLUDES_DIR . '/assets/font_json/simpleline.json';
			$iconsmind_line_json_path  = LITHO_ADDONS_INCLUDES_DIR . '/assets/font_json/iconsmind-line.json';
			$iconsmind_solid_json_path = LITHO_ADDONS_INCLUDES_DIR . '/assets/font_json/iconsmind-solid.json';
			$feather_json_path         = LITHO_ADDONS_INCLUDES_DIR . '/assets/font_json/feather.json';

			$themify_icon_label         = esc_html__( 'Themify', 'litho-addons' );
			$etline_icon_label          = esc_html__( 'Et line', 'litho-addons' );
			$simpleline_icon_label      = esc_html__( 'Simple line', 'litho-addons' );
			$iconsmind_line_icon_label  = esc_html__( 'Iconsmind line', 'litho-addons' );
			$iconsmind_icon_solid_label = esc_html__( 'Iconsmind solid', 'litho-addons' );
			$feather_icon_label         = esc_html__( 'Feather', 'litho-addons' );

			$icons_array['themify']         = [ $themify_icon_label, 'ti-', 'fab fa-font-awesome-flag', LITHO_ADDONS_PLUGIN_VERSION, $themify_json_path ];
			$icons_array['etline']          = [ $etline_icon_label, '', 'fab fa-font-awesome-flag', LITHO_ADDONS_PLUGIN_VERSION, $etline_json_path ];
			$icons_array['simpleline']      = [ $simpleline_icon_label, '', 'fab fa-font-awesome-flag', LITHO_ADDONS_PLUGIN_VERSION, $simpleline_json_path ];
			$icons_array['iconsmind_line']  = [ $iconsmind_line_icon_label, '', 'fab fa-font-awesome-flag', LITHO_ADDONS_PLUGIN_VERSION, $iconsmind_line_json_path ];
			$icons_array['iconsmind_solid'] = [ $iconsmind_icon_solid_label, '', 'fab fa-font-awesome-flag', LITHO_ADDONS_PLUGIN_VERSION, $iconsmind_solid_json_path ];
			$icons_array['feather']         = [ $feather_icon_label, '', 'fab fa-font-awesome-flag', LITHO_ADDONS_PLUGIN_VERSION, $feather_json_path ];

			$litho_custom_icons_list = apply_filters( 'litho_add_custom_icons', array() );
			$icons_array             = array_merge( $icons_array, $litho_custom_icons_list );

			foreach ( $icons_array as $icon_name => $icon_val ) {

				$set_config['name']                = $icon_name . '_icons';
				$set_config['label']               = $icon_val[0];
				$set_config['url']                 = '';
				$set_config['enqueue']             = '';
				$set_config['prefix']              = $icon_val[1];
				$set_config['displayPrefix']       = '';
				$set_config['labelIcon']           = $icon_val[2];
				$set_config['ver']                 = $icon_val[3];
				$set_config['fetchJson']           = $icon_val[4];
				$set_config['native']              = true;
				$config    [ $set_config['name'] ] = $set_config;
			}
			return array_merge( $settings, $config );
		}
	}
}
