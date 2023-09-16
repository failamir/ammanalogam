<?php
/**
 * Post Like Unlike
 *
 * @package Litho
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Processes like/unlike
 *
 * @since 0.5
 */
add_action( 'wp_ajax_nopriv_process_simple_like', 'litho_process_simple_like' );
add_action( 'wp_ajax_process_simple_like', 'litho_process_simple_like' );

if ( ! function_exists( 'litho_process_simple_like' ) ) {
	function litho_process_simple_like() {
		// Security
		$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
		if ( ! wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
			exit( esc_html__( 'Not permitted', 'litho-addons' ) );
		}
		// Test if javascript is disabled
		$disabled   = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
		// Test if this is a comment
		$is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
		// Base variables
		$post_id    = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$result     = array();
		$post_users = NULL;
		$like_count = 0;
		// Get plugin options
		if ( $post_id != '' ) {
			$count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_comment_like_count", true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
			$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
			if ( ! litho_already_liked( $post_id, $is_comment ) ) { // Like the post
				if ( is_user_logged_in() ) { // user is logged in
					$user_id    = get_current_user_id();
					$post_users = litho_post_user_likes( $user_id, $post_id, $is_comment );
					if ( $is_comment == 1 ) {
						// Update User & Comment
						$user_like_count = get_user_option( "_comment_like_count", $user_id );
						$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
						update_user_option( $user_id, "_comment_like_count", ++$user_like_count );
						if ( $post_users ) {
							update_comment_meta( $post_id, "_user_comment_liked", $post_users );
						}
					} else {
						// Update User & Post
						$user_like_count = get_user_option( "_user_like_count", $user_id );
						$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
						update_user_option( $user_id, "_user_like_count", ++$user_like_count );
						if ( $post_users ) {
							update_post_meta( $post_id, "_user_liked", $post_users );
						}
					}
				} else { // user is anonymous
					$user_ip    = litho_sl_get_ip();
					$post_users = litho_post_ip_likes( $user_ip, $post_id, $is_comment );
					// Update Post
					if ( $post_users ) {
						if ( $is_comment == 1 ) {
							update_comment_meta( $post_id, "_user_comment_IP", $post_users );
						} else {
							update_post_meta( $post_id, "_user_IP", $post_users );
						}
					}
				}
				$like_count = ++$count;
				$response['status'] = "liked";
				$response['icon']   = litho_get_liked_icon();
			} else { // Unlike the post
				if ( is_user_logged_in() ) { // user is logged in
					$user_id    = get_current_user_id();
					$post_users = litho_post_user_likes( $user_id, $post_id, $is_comment );
					// Update User
					if ( $is_comment == 1 ) {
						$user_like_count = get_user_option( "_comment_like_count", $user_id );
						$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
						if ( $user_like_count > 0 ) {
							update_user_option( $user_id, "_comment_like_count", --$user_like_count );
						}
					} else {
						$user_like_count = get_user_option( "_user_like_count", $user_id );
						$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
						if ( $user_like_count > 0 ) {
							update_user_option( $user_id, '_user_like_count', --$user_like_count );
						}
					}
					// Update Post.
					if ( $post_users ) {
						$uid_key = array_search( $user_id, $post_users );
						unset( $post_users[ $uid_key ] );
						if ( $is_comment == 1 ) {
							update_comment_meta( $post_id, "_user_comment_liked", $post_users );
						} else { 
							update_post_meta( $post_id, "_user_liked", $post_users );
						}
					}
				} else { // user is anonymous.
					$user_ip    = litho_sl_get_ip();
					$post_users = litho_post_ip_likes( $user_ip, $post_id, $is_comment );
					// Update Post.
					if ( $post_users ) {
						$uip_key = array_search( $user_ip, $post_users );
						unset( $post_users[ $uip_key ] );
						if ( $is_comment == 1 ) {
							update_comment_meta( $post_id, "_user_comment_IP", $post_users );
						} else {
							update_post_meta( $post_id, "_user_IP", $post_users );
						}
					}
				}
				$like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number.
				$response['status'] = "unliked";
				$response['icon']   = litho_get_unliked_icon();
			}
			if ( $is_comment == 1 ) {
				update_comment_meta( $post_id, "_comment_like_count", $like_count );
				update_comment_meta( $post_id, "_comment_like_modified", date( 'Y-m-d H:i:s' ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			} else {
				update_post_meta( $post_id, "_post_like_count", $like_count );
				update_post_meta( $post_id, "_post_like_modified", date( 'Y-m-d H:i:s' ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			}
			$response['count']      = litho_get_like_count( $like_count );
			$response['testing']    = $is_comment;
			if ( $disabled == true ) {
				if ( $is_comment == 1 ) {
					wp_redirect( get_permalink( get_the_ID() ) );
					exit();
				} else {
					wp_redirect( get_permalink( $post_id ) );
					exit();
				}
			} else {
				wp_send_json( $response );
			}
		}
	}
}
/**
 * Utility to test if the post is already liked
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_already_liked' ) ) {
	function litho_already_liked( $post_id, $is_comment ) {
		$post_users = NULL;
		$user_id    = NULL;
		if ( is_user_logged_in() ) { // user is logged in
			$user_id = get_current_user_id();
			$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
			if ( count( $post_meta_users ) != 0 ) {
				$post_users = $post_meta_users[0];
			}
		} else { // user is anonymous
			$user_id = litho_sl_get_ip();
			$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" ); 
			if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
				$post_users = $post_meta_users[0];
			}
		}
		if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
			return true;
		} else {
			return false;
		}
	} // litho_already_liked().
}
/**
 * Output the like button
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_get_simple_likes_button' ) ) {
	function litho_get_simple_likes_button( $post_id, $is_comment = NULL, $font_class = NULL ) {

		$output = '';
		$is_comment = ( NULL == $is_comment ) ? 0 : 1;
		$nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security.
		if ( $is_comment == 1 ) {
			$comment_class = esc_attr( ' sl-comment' );
			$post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
			$like_count    = get_comment_meta( $post_id, "_comment_like_count", true );
			$like_count    = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
		} else {
			$comment_class = '';
			$post_id_class = esc_attr( ' sl-button-' . $post_id );
			$like_count    = get_post_meta( $post_id, "_post_like_count", true );
			$like_count    = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
		}
		$count      = litho_get_like_count( $like_count );
		$icon_empty = litho_get_unliked_icon();
		$icon_full  = litho_get_liked_icon();
		// Loader.
		$loader = '<span id="sl-loader"></span>';
		// Liked/Unliked Variables.
		if ( litho_already_liked( $post_id, $is_comment ) ) {
			$class = ' liked' . $font_class;
			$title = esc_html__( 'Unlike', 'litho-addons' );
			$icon  = $icon_full;
		} else {
			$class = $font_class;
			$title = esc_html__( 'Like', 'litho-addons' );
			$icon  = $icon_empty;
		}
		$output = '<a href="' . admin_url( 'admin-ajax.php?action=process_simple_like' . '&nonce=' . $nonce . '&post_id=' . $post_id . '&disabled=true&is_comment=' . $is_comment ) . '" class="sl-button alt-font blog-like' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . esc_attr( $title ) . '">' . $icon . $count . '</a>';
		return $output;
	} // litho_get_simple_likes_button().
}
/**
 * Processes shortcode to manually add the button to posts
 *
 * @since 0.5
 */
add_shortcode( 'jmliker', 'litho_sl_shortcode' );
if ( ! function_exists( 'litho_sl_shortcode' ) ) {
	function litho_sl_shortcode() {
		return litho_get_simple_likes_button( get_the_ID(), 0, '' );
	} // shortcode().
}
/**
 * Utility retrieves post meta user likes (user id array),
 * then adds new user id to retrieved array
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_post_user_likes' ) ) {
	function litho_post_user_likes( $user_id, $post_id, $is_comment ) {
		$post_users = '';
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
		if ( ! is_array( $post_users ) ) {
			$post_users = array();
		}
		if ( ! in_array( $user_id, $post_users ) ) {
			$post_users['user-' . $user_id] = $user_id;
		}
		return $post_users;
	} // litho_post_user_likes().
}
/**
 * Utility retrieves post meta ip likes (ip array),
 * then adds new ip to retrieved array
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_post_ip_likes' ) ) {
	function litho_post_ip_likes( $user_ip, $post_id, $is_comment ) {

		$post_users      = '';
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );

		// Retrieve post information
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
		if ( ! is_array( $post_users ) ) {
			$post_users = array();
		}
		if ( ! in_array( $user_ip, $post_users ) ) {
			$post_users['ip-' . $user_ip] = $user_ip;
		}
		return $post_users;
	} // litho_post_ip_likes().
}
/**
 * Utility to retrieve IP address
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_sl_get_ip' ) ) {
	function litho_sl_get_ip() {
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		} else {
			$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}
		$ip = filter_var( $ip, FILTER_VALIDATE_IP );
		$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
		return $ip;
	} // litho_sl_get_ip().
}
/**
 * Utility returns the button icon for "like" action
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_get_liked_icon' ) ) {
	function litho_get_liked_icon() {
		/* If already using Font Awesome with your theme, replace svg with: <i class="far fa-heart"></i> */
		$icon = '<i class="fas fa-heart"></i>';
		return $icon;
	} // litho_get_liked_icon().
}
/**
 * Utility returns the button icon for "unlike" action
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_get_unliked_icon' ) ) {
	function litho_get_unliked_icon() {
		/* If already using Font Awesome with your theme, replace svg with: <i class="far fa-heart"></i> */
		$icon = '<i class="far fa-heart"></i>';
		return $icon;
	} // litho_get_unliked_icon().
}
/**
 * Utility function to format the button count,
 * appending "K" if one thousand or greater,
 * "M" if one million or greater,
 * and "B" if one billion or greater (unlikely).
 * $precision = how many decimal points to display (1.25K)
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_sl_format_count' ) ) {
	function litho_sl_format_count( $number ) {
		$precision = 2;
		if ( $number >= 1000 && $number < 1000000 ) {
			$formatted = number_format( $number/1000, $precision ).'K';
		} elseif ( $number >= 1000000 && $number < 1000000000 ) {
			$formatted = number_format( $number/1000000, $precision ).'M';
		} elseif ( $number >= 1000000000 ) {
			$formatted = number_format( $number/1000000000, $precision ).'B';
		} else {
			$formatted = $number; // Number is less than 1000
		}
		$formatted = str_replace( '.00', '', $formatted );
		return $formatted;
	} // litho_sl_format_count().
}
/**
 * Utility retrieves count plus count options,
 * returns appropriate format based on options
 *
 * @since 0.5
 */
if ( ! function_exists( 'litho_get_like_count' ) ) {
	function litho_get_like_count( $like_count ) {

		if ( $like_count > 1 ) {
			$like_text = '<span class="posts-like">' . esc_html__( 'Likes', 'litho-addons' ) . '</span>';
		} else {
			$like_text = '<span class="posts-like">' . esc_html__( 'Like', 'litho-addons' ) . '</span>';
		}

		if ( is_numeric( $like_count ) ) { 
			$number = '<span class="posts-like-count">' . litho_sl_format_count( $like_count ) . '</span> ' . $like_text;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			$number = $like_text;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		$count = $number;
		return $count;
	} // litho_get_like_count().
}
// User Profile List.
add_action( 'show_user_profile', 'litho_show_user_likes' );
add_action( 'edit_user_profile', 'litho_show_user_likes' );
if ( ! function_exists( 'litho_show_user_likes' ) ) {
	function litho_show_user_likes( $user ) { ?>
		<table class="form-table">
			<tr>
				<th><label for="user_likes"><?php esc_html_e( 'You Like:', 'litho-addons' ); ?></label></th>
				<td>
				<?php
					$types = get_post_types( array( 'public' => true ) );
					$args = array(
						'numberposts' => -1,
						'post_type'   => $types,
						'meta_query'  => array(
							array(
								'key'     => '_user_liked',
								'value'   => $user->ID,
								'compare' => 'LIKE',
							)
						)
					);

					$sep = '';

					$like_query = new WP_Query( $args );
					if ( $like_query->have_posts() ) :
						?>
						<p>
							<?php
							while ( $like_query->have_posts() ) :
								$like_query->the_post();
								echo sprintf( '%s', $sep ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								<?php
								$sep = ' &middot; ';
							endwhile;
							?>
						</p>
					<?php else : ?>
						<p><?php esc_html_e( 'You do not like anything yet.', 'litho-addons' ); ?></p>
						<?php
					endif;
					wp_reset_postdata();
					?>
				</td>
			</tr>
		</table>
		<?php
	} // litho_show_user_likes()
}
