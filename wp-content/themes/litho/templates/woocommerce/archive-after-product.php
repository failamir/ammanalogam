<?php
/**
 * Displaying right sidebar for archive product
 *
 * @package Litho
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$litho_product_layout_setting = get_theme_mod( 'litho_product_layout_setting_archive', 'litho_layout_right_sidebar' );
$litho_product_right_sidebar  = get_theme_mod( 'litho_product_right_sidebar_archive', 'litho-shop-1' );
$litho_product_left_sidebar   = get_theme_mod( 'litho_product_left_sidebar_archive', 'litho-shop-1' );

/**
 * Filter for change layout style for ex. ?sidebar=right_sidebar
 *
 * @since 1.0
 */
$litho_product_layout_setting = apply_filters( 'litho_page_layout_style', $litho_product_layout_setting );

/**
 * Filter for add no padding top
 *
 * @since 1.0
 */
$litho_product_archive_no_padding_top = apply_filters( 'litho_page_no_padding_top', '' );

// Left Sidebar.
if ( 'litho_layout_left_sidebar' == $litho_product_layout_setting ) {

	if ( ! empty( $litho_product_left_sidebar ) && ! is_active_sidebar( $litho_product_left_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_no_sidebar';

	} elseif ( empty( $litho_product_left_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_no_sidebar';
	}
}

// Right Sidebar.
if ( 'litho_layout_right_sidebar' == $litho_product_layout_setting ) {

	if ( ! empty( $litho_product_right_sidebar ) && ! is_active_sidebar( $litho_product_right_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_no_sidebar';

	} elseif ( empty( $litho_product_right_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_no_sidebar';
	}
}

// Both Sidebar.
if ( 'litho_layout_both_sidebar' == $litho_product_layout_setting ) {

	if ( ! empty( $litho_product_left_sidebar ) && ! is_active_sidebar( $litho_product_left_sidebar ) && ! empty( $litho_product_right_sidebar ) && ! is_active_sidebar( $litho_product_right_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_no_sidebar';

	} elseif ( ( ! empty( $litho_product_left_sidebar ) && is_active_sidebar( $litho_product_left_sidebar ) ) && ! empty( $litho_product_right_sidebar ) && is_active_sidebar( $litho_product_right_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_both_sidebar';

	} elseif ( ! empty( $litho_product_left_sidebar ) && is_active_sidebar( $litho_product_left_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_left_sidebar';

	} elseif ( ! empty( $litho_product_right_sidebar ) && is_active_sidebar( $litho_product_right_sidebar ) ) {

		$litho_product_layout_setting = 'litho_layout_right_sidebar';

	} else {

		$litho_product_layout_setting = 'litho_layout_no_sidebar';
	}
}

switch ( $litho_product_layout_setting ) {
	case 'litho_layout_no_sidebar':
		?>
			</div>
		<?php
		break;
	case 'litho_layout_left_sidebar':
		?>
			</div>
			<aside id="secondary" class="col-12 col-xl-3 col-lg-4 col-md-12 sidebar litho-product-sidebar litho-blog-sidebar<?php echo esc_attr( $litho_product_archive_no_padding_top ); ?>" itemtype="http://schema.org/WPSideBar" itemscope="itemscope">
				<?php
				/**
				 * Fires immediately before siderbar content start
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_start' );

				litho_page_sidebar_style( $litho_product_left_sidebar );

				/**
				 * Fires immediately after siderbar content end
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_end' );
				?>
			</aside>
		<?php
		break;
	case 'litho_layout_right_sidebar':
		?>
			</div>
			<aside id="secondary" class="col-12 col-xl-3 col-lg-4 col-md-12 sidebar litho-product-sidebar litho-blog-sidebar<?php echo esc_attr( $litho_product_archive_no_padding_top ); ?>" itemtype="http://schema.org/WPSideBar" itemscope="itemscope">
				<?php
				/**
				 * Fires immediately before siderbar content start
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_start' );

				litho_page_sidebar_style( $litho_product_right_sidebar );

				/**
				 * Fires immediately after siderbar content end
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_end' );
				?>
			</aside>
		<?php
		break;
	case 'litho_layout_both_sidebar':
		?>
			</div>
			<aside id="primary" class="col-12 col-lg-3 sidebar litho-product-sidebar both-sidebar-left litho-blog-sidebar<?php echo esc_attr( $litho_product_archive_no_padding_top ); ?>" itemtype="http://schema.org/WPSideBar" itemscope="itemscope">
				<?php
				/**
				 * Fires immediately before siderbar content start
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_start' );

				litho_page_sidebar_style( $litho_product_left_sidebar );

				/**
				 * Fires immediately after siderbar content end
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_end' );
				?>
			</aside>
			<aside id="secondary" class="col-12 col-lg-3 sidebar litho-product-sidebar both-sidebar-right litho-blog-sidebar<?php echo esc_attr( $litho_product_archive_no_padding_top ); ?>" itemtype="http://schema.org/WPSideBar" itemscope="itemscope">
			<?php

				/**
				 * Fires immediately before siderbar content start
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_start' );

				litho_page_sidebar_style( $litho_product_right_sidebar );

				/**
				 * Fires immediately after siderbar content end
				 *
				 * @since 1.0
				 */
				do_action( 'litho_sidebar_content_end' );
			?>
			</aside>
		<?php
		break;
}
