<?php
/**
 * Bốn trang chính sách công khai: Điều khoản, Quyền riêng tư, Xoá dữ liệu, Hoàn tiền & khiếu nại.
 * Nội dung ban đầu nằm ở inc/policies/*.html, được tạo MỘT LẦN thành Trang thường;
 * sau đó chủ đầu tư sửa trong Trang → Tất cả trang, theme không ghi đè.
 * Các URL này được khai ở App Store, Google Play và Facebook Developers nên đường dẫn bị khoá.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** slug => array( tiêu đề trang, chữ ở chân trang ) */
function s24_policy_pages() {
	return array(
		'dieu-khoan-su-dung'   => array( 'Điều khoản sử dụng', 'Điều khoản sử dụng' ),
		'chinh-sach-bao-mat'   => array( 'Chính sách quyền riêng tư và bảo mật', 'Quyền riêng tư và bảo mật' ),
		'xoa-du-lieu'          => array( 'Hướng dẫn xoá tài khoản và dữ liệu', 'Xoá tài khoản và dữ liệu' ),
		'chinh-sach-hoan-tien' => array( 'Chính sách hoàn tiền và khiếu nại', 'Hoàn tiền và khiếu nại' ),
	);
}

add_action( 'after_switch_theme', 's24_seed_policy_pages' );

/* Theme cập nhật bằng cách tải zip trong wp-admin (không qua install.sh) vẫn có đủ trang */
add_action( 'admin_init', function () {
	if ( ! get_option( 's24_policy_seeded' ) && current_user_can( 'publish_pages' ) ) {
		s24_seed_policy_pages();
	}
} );

function s24_seed_policy_pages() {
	$ids = array();
	foreach ( s24_policy_pages() as $slug => $p ) {
		$page = get_page_by_path( $slug );
		if ( $page ) { // đã có thì giữ nguyên bản chủ đầu tư đã sửa
			$ids[ $slug ] = $page->ID;
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_name'      => $slug,
			'post_title'     => $p[0],
			'post_content'   => (string) file_get_contents( S24_DIR . '/inc/policies/' . $slug . '.html' ),
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		) );
		if ( $id && ! is_wp_error( $id ) ) { $ids[ $slug ] = $id; }
	}
	update_option( 's24_policy_pages', $ids );
	update_option( 's24_policy_seeded', 1 );

	// Trang quyền riêng tư của WordPress (Cài đặt → Quyền riêng tư) trỏ về trang của mình
	$cur = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( ! empty( $ids['chinh-sach-bao-mat'] ) && ( ! $cur || 'publish' !== get_post_status( $cur ) ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['chinh-sach-bao-mat'] );
	}
}

/* Khoá đường dẫn (slug) và trang cha: đổi đi sẽ làm hỏng URL đã khai với các nền tảng */
add_filter( 'wp_insert_post_data', function ( $data, $postarr ) {
	if ( 'page' !== $data['post_type'] || empty( $postarr['ID'] ) || 'trash' === $data['post_status'] ) { return $data; }
	$slug = array_search( (int) $postarr['ID'], array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) ), true );
	if ( false !== $slug ) {
		$data['post_name']   = $slug;
		$data['post_parent'] = 0;
	}
	return $data;
}, 10, 2 );

/* Menu chân trang mặc định khi chưa gán menu cho vị trí "Chân trang – Chính sách" */
function s24_policy_menu_fallback() {
	echo '<ul>';
	foreach ( s24_policy_pages() as $slug => $p ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $p[1] ) . '</a></li>';
		}
	}
	echo '</ul>';
}
