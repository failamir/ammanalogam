<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* For WPML Compatibility */
if ( file_exists( LITHO_ADDONS_ROOT . '/lib/wpml/class-team-member-carousel.php' ) ) {
	require_once LITHO_ADDONS_ROOT . '/lib/wpml/class-team-member-carousel.php';
}

if ( file_exists( LITHO_ADDONS_ROOT . '/lib/wpml/class-carousel-slider.php' ) ) {
	require_once LITHO_ADDONS_ROOT . '/lib/wpml/class-carousel-slider.php';
}

// Apply filter to translate repeater field
if ( ! function_exists( 'litho_widgets_to_translate_filter_repeater' ) ) {
	function litho_widgets_to_translate_filter_repeater( $widgets ) {
		/* Litho Team Member Carousel */
		$widgets['litho-team-memeber-carousel'] = [
			'conditions'        => ['widgetType' => 'litho-team-memeber-carousel'],
			'fields'            => [],
			'integration-class' => 'Litho_Team_Member_Carousel'
		];
		/* Litho Slider */
		$widgets['litho-slider'] = [
			'conditions'        => ['widgetType' => 'litho-slider'],
			'fields'            => [],
			'integration-class' => 'Litho_Carousel_Slider'
		];
		return $widgets;
	}
}
add_filter( 'wpml_elementor_widgets_to_translate', 'litho_widgets_to_translate_filter_repeater' );

// Apply filter to translate field
if ( ! function_exists( 'litho_widgets_to_translate_filter' ) ) {
	function litho_widgets_to_translate_filter( $widgets ) {

		/* Litho Heading */
		$widgets['litho-heading'] = [
			'conditions' => ['widgetType' => 'litho-heading'],
			'fields'     => [
				[
					'field'       => 'litho_title',
					'type'        => __( 'Primary Title', 'litho-addons' ),
					'editor_type' => 'AREA'
				],
				'litho_link' =>[
					'field'       => 'url',
					'field_id'    => 'litho_link',
					'type'        => __( 'Primary Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_secondary_title',
					'type'        => __( 'Secondary Title', 'litho-addons' ),
					'editor_type' => 'AREA'
				],
				'litho_secondary_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_secondary_link',
					'type'        => __( 'Secondary Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				]
			]
		];

		/* Litho Icon Box */
		$widgets['litho-icon-box'] = [
			'conditions' => ['widgetType' => 'litho-icon-box'],
			'fields'     => [
				[
					'field'       => 'litho_title_text',
					'type'        => __( 'Title', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'litho_description_text',
					'type'        => __( 'Description', 'litho-addons' ),
					'editor_type' => 'AREA'
				],
					'litho_link'  =>[
					'field'       => 'url',
					'field_id'    => 'litho_link',
					'type'        => __( 'Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_primary_button_text',
					'type'        => __( 'Button Text', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_primary_button_link' =>[
					'field'       => 'url',
					'field_id'    => 'litho_primary_button_link',
					'type'        => __( 'Button Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				]
			]
		];

		/* Litho Fancy Text Box */
		$widgets['litho-fancy-text-box'] = [
			'conditions' => ['widgetType' => 'litho-fancy-text-box'],
			'fields'     => [
				'litho_image_link' =>[
					'field'       => 'url',
					'field_id'    => 'litho_image_link',
					'type'        => __( 'Image Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_fancy_text_box_title',
					'type'        => __( 'Title', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'litho_fancy_text_box_strong_title',
					'type'        => __( 'Strong Title', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_title_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_title_link',
					'type'        => __( 'Title Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				'litho_icon_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_icon_link',
					'type'        => __( 'Icon Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_fancy_text_box_subtitle',
					'type'        => __( 'Subtitle', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_subtitle_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_subtitle_link',
					'type'        => __( 'Subtitle Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_fancy_text_box_content',
					'type'        => __( 'Content', 'litho-addons' ),
					'editor_type' => 'VISUAL'
				],
				[
					'field'       => 'litho_button_text',
					'type'        => __( 'Button Text', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'litho_button_subtext',
					'type'        => __( 'Button Subtext', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_button_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_button_link',
					'type'        => __( 'Button Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				]
			]
		];

		/* Litho Feature Box */
		$widgets['litho-feature-box'] = [
			'conditions' => ['widgetType' => 'litho-feature-box'],
			'fields'     => [
				[
					'field'       => 'litho_feature_box_title',
					'type'        => __( 'Title', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_title_link' =>[
					'field'       => 'url',
					'field_id'    => 'litho_title_link',
					'type'        => __( 'Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_feature_box_subtitle',
					'type'        => __( 'Subtitle', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_subtitle_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_subtitle_link',
					'type'        => __( 'Subtitle Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_feature_box_content',
					'type'        => __( 'Content', 'litho-addons' ),
					'editor_type' => 'VISUAL'
				],
				[
					'field'       => 'litho_feature_box_button_text',
					'type'        => __( 'Button Text', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_link',
					'type'        => __( 'Button Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				]
			]
		];

		/* Litho Content Block */
		$widgets['litho-content-block'] = [
			'conditions' => ['widgetType' => 'litho-content-block'],
			'fields'     => [
				[
					'field'       => 'litho_content_block_title',
					'type'        => __( 'Title', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'litho_content_block_label',
					'type'        => __( 'Label', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'litho_content_block_subtitle',
					'type'        => __( 'Subtitle', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'litho_content_block_content',
					'type'        => __( 'Content', 'litho-addons' ),
					'editor_type' => 'VISUAL'
				],
				'litho_content_block_title_link' =>[
					'field'       => 'url',
					'field_id'    => 'litho_content_block_title_link',
					'type'        => __( 'Title Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'litho_primary_button_text',
					'type'        => __( 'Button Text', 'litho-addons' ),
					'editor_type' => 'LINE'
				],
				'litho_primary_button_link' => [
					'field'       => 'url',
					'field_id'    => 'litho_primary_button_link',
					'type'        => __( 'Button Link', 'litho-addons' ),
					'editor_type' => 'LINK'
				]
			]
		];

		return $widgets;
	}
}
add_filter( 'wpml_elementor_widgets_to_translate', 'litho_widgets_to_translate_filter' );
