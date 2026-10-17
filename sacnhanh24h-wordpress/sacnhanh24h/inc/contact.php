<?php
/**
 * Form "Nhận tư vấn": lưu vào mục Yêu cầu tư vấn và gửi email thông báo.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_ajax_s24_contact', 's24_handle_contact' );
add_action( 'wp_ajax_nopriv_s24_contact', 's24_handle_contact' );

function s24_handle_contact() {
	if ( ! check_ajax_referer( 's24_contact', 'nonce', false ) ) {
		wp_send_json_error( array( 'msg' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và gửi lại.' ), 403 );
	}
	// Ô bẫy chống spam: người thật không nhìn thấy ô này
	if ( ! empty( $_POST['website_url'] ) ) {
		wp_send_json_success( array( 'msg' => s24_mod( 'form_success' ) ) );
	}
	$name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$msg   = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( '' === $name || '' === $phone ) {
		wp_send_json_error( array( 'msg' => 'Vui lòng nhập họ tên và số điện thoại.' ), 400 );
	}
	if ( ! preg_match( '/^[0-9+\s().-]{8,20}$/', $phone ) ) {
		wp_send_json_error( array( 'msg' => 'Số điện thoại chưa đúng. Vui lòng kiểm tra lại.' ), 400 );
	}

	// Giới hạn: tối đa 5 lần gửi / 10 phút cho mỗi địa chỉ IP
	$ip  = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$key = 's24_rl_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= 5 ) {
		wp_send_json_error( array( 'msg' => 'Bạn đã gửi nhiều lần. Vui lòng thử lại sau ít phút.' ), 429 );
	}
	set_transient( $key, $n + 1, 10 * MINUTE_IN_SECONDS );

	$id = wp_insert_post( array(
		'post_type'   => 'lien_he',
		'post_status' => 'publish',
		'post_title'  => $name,
	) );
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, 'sdt', $phone );
		update_post_meta( $id, 'email', $email );
		update_post_meta( $id, 'noi_dung', $msg );
	}

	$to = s24_mod( 'email' ) ?: get_option( 'admin_email' );
	$body = "Yêu cầu tư vấn mới từ website:\n\nHọ tên: $name\nSố điện thoại: $phone\nEmail: $email\n\nNội dung:\n$msg\n\nXem tất cả: " . admin_url( 'edit.php?post_type=lien_he' );
	$headers = array();
	if ( $email ) { $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>'; }
	wp_mail( $to, '[Sacnhanh24h] Yêu cầu tư vấn – ' . $name, $body, $headers );

	wp_send_json_success( array( 'msg' => s24_mod( 'form_success' ) ) );
}

/* Số yêu cầu chưa xem hiện cạnh menu */
add_action( 'admin_menu', function () {
	global $menu;
	$last  = (int) get_user_meta( get_current_user_id(), 's24_leads_seen', true );
	$count = count( get_posts( array( 'post_type' => 'lien_he', 'numberposts' => 99, 'fields' => 'ids', 'date_query' => array( array( 'after' => gmdate( 'Y-m-d H:i:s', $last ), 'column' => 'post_date_gmt' ) ) ) ) );
	if ( ! $count ) { return; }
	foreach ( $menu as $i => $item ) {
		if ( 'edit.php?post_type=lien_he' === $item[2] ) {
			$menu[ $i ][0] .= ' <span class="awaiting-mod">' . $count . '</span>';
		}
	}
}, 99 );
add_action( 'load-edit.php', function () {
	if ( 'lien_he' === ( $_GET['post_type'] ?? '' ) ) {
		update_user_meta( get_current_user_id(), 's24_leads_seen', time() );
	}
} );
