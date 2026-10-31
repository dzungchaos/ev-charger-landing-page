<?php
/**
 * Trang chính sách công khai: Điều khoản, Quyền riêng tư, Xoá dữ liệu, Hoàn tiền & khiếu nại,
 * bản tiếng Việt và bản tiếng Anh (dưới /en/).
 * Nội dung ban đầu nằm ở inc/policies/*.html, được tạo MỘT LẦN thành Trang thường;
 * sau đó chủ đầu tư sửa trong Trang → Tất cả trang, theme không ghi đè.
 * Các URL này được khai ở App Store, Google Play và Facebook Developers nên đường dẫn bị khoá.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Tăng số này khi thêm trang, để site đã cài tự tạo trang còn thiếu */
define( 'S24_POLICY_VER', 2 );

/** đường dẫn => array( tiêu đề trang, chữ ở chân trang, ngôn ngữ, đường dẫn bản ngôn ngữ kia ) */
function s24_policy_pages() {
	return array(
		'dieu-khoan-su-dung'   => array( 'Điều khoản sử dụng', 'Điều khoản sử dụng', 'vi', 'en/terms-of-use' ),
		'chinh-sach-bao-mat'   => array( 'Chính sách quyền riêng tư và bảo mật', 'Quyền riêng tư và bảo mật', 'vi', 'en/privacy-policy' ),
		'xoa-du-lieu'          => array( 'Hướng dẫn xoá tài khoản và dữ liệu', 'Xoá tài khoản và dữ liệu', 'vi', 'en/account-deletion' ),
		'chinh-sach-hoan-tien' => array( 'Chính sách hoàn tiền và khiếu nại', 'Hoàn tiền và khiếu nại', 'vi', 'en/refund-policy' ),
		// Trang cha /en/ phải đứng trước các trang con
		'en'                   => array( 'Policies', 'English', 'en', '' ),
		'en/terms-of-use'      => array( 'Terms of Use', 'Terms of Use', 'en', 'dieu-khoan-su-dung' ),
		'en/privacy-policy'    => array( 'Privacy and Security Policy', 'Privacy and Security', 'en', 'chinh-sach-bao-mat' ),
		'en/account-deletion'  => array( 'Account and Data Deletion', 'Account and Data Deletion', 'en', 'xoa-du-lieu' ),
		'en/refund-policy'     => array( 'Refund and Complaints Policy', 'Refunds and Complaints', 'en', 'chinh-sach-hoan-tien' ),
	);
}

add_action( 'after_switch_theme', 's24_seed_policy_pages' );

/* Theme cập nhật bằng cách tải zip trong wp-admin (không qua install.sh) vẫn có đủ trang */
add_action( 'admin_init', function () {
	if ( (int) get_option( 's24_policy_seeded' ) < S24_POLICY_VER && current_user_can( 'publish_pages' ) ) {
		s24_seed_policy_pages();
	}
} );

function s24_seed_policy_pages() {
	$ids = array();
	foreach ( s24_policy_pages() as $path => $p ) {
		$page = get_page_by_path( $path );
		if ( $page ) { // đã có thì giữ nguyên bản chủ đầu tư đã sửa
			$ids[ $path ] = $page->ID;
			continue;
		}
		$parent = dirname( $path );
		$id     = wp_insert_post( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_name'      => basename( $path ),
			'post_parent'    => '.' === $parent ? 0 : (int) ( $ids[ $parent ] ?? 0 ),
			'post_title'     => $p[0],
			'post_content'   => (string) file_get_contents( S24_DIR . '/inc/policies/' . str_replace( '/', '-', $path ) . '.html' ),
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		) );
		if ( $id && ! is_wp_error( $id ) ) { $ids[ $path ] = $id; }
	}
	update_option( 's24_policy_pages', $ids );
	update_option( 's24_policy_seeded', S24_POLICY_VER );

	// Trang quyền riêng tư của WordPress (Cài đặt → Quyền riêng tư) trỏ về trang của mình
	$cur = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( ! empty( $ids['chinh-sach-bao-mat'] ) && ( ! $cur || 'publish' !== get_post_status( $cur ) ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['chinh-sach-bao-mat'] );
	}
}

/* Chủ dự án cho đăng trước khi pháp chế duyệt (31/10/2026): trang đã tạo từ bản có ô "BẢN NHÁP"
   được gỡ ô đó một lần. Chỉ gỡ đúng phần theme đã chèn, phần đã sửa trong wp-admin giữ nguyên. */
add_action( 'admin_init', function () {
	if ( get_option( 's24_policy_undrafted' ) || ! current_user_can( 'publish_pages' ) ) { return; }
	foreach ( (array) get_option( 's24_policy_pages', array() ) as $id ) {
		$post = get_post( (int) $id );
		if ( ! $post ) { continue; }
		$html = s24_policy_undraft( $post->post_content );
		if ( $html !== $post->post_content ) {
			wp_update_post( wp_slash( array( 'ID' => $post->ID, 'post_content' => $html ) ) );
		}
	}
	update_option( 's24_policy_undrafted', 1 );
}, 20 );

function s24_policy_undraft( $html ) {
	// Bản Việt: bỏ cả ô
	$html = preg_replace( '#<!-- wp:paragraph \{"className":"s24-note"\} -->\s*<p class="s24-note"><strong>BẢN NHÁP – CHƯA CÓ HIỆU LỰC\.</strong>.*?</p>\s*<!-- /wp:paragraph -->\s*#su', '', $html );
	// Bản Anh: giữ câu "bản tiếng Việt được ưu tiên áp dụng" thành đoạn thường
	$html = preg_replace( '#<!-- wp:paragraph \{"className":"s24-note"\} -->(\s*)<p class="s24-note"><strong>DRAFT – NOT YET IN EFFECT\.</strong> This document is pending review by the legal team of [^.]*\. #su', '<!-- wp:paragraph -->$1<p>', $html );
	return str_replace( array( 'Phiên bản 1.1 (bản nháp)', 'Version 1.1 (draft)' ), array( 'Phiên bản 1.1', 'Version 1.1' ), $html );
}

/* Khoá đường dẫn (slug) và trang cha: đổi đi sẽ làm hỏng URL đã khai với các nền tảng */
add_filter( 'wp_insert_post_data', function ( $data, $postarr ) {
	if ( 'page' !== $data['post_type'] || empty( $postarr['ID'] ) || 'trash' === $data['post_status'] ) { return $data; }
	$ids  = array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) );
	$path = array_search( (int) $postarr['ID'], $ids, true );
	if ( false !== $path ) {
		$parent              = dirname( $path );
		$data['post_name']   = basename( $path );
		$data['post_parent'] = '.' === $parent ? 0 : ( $ids[ $parent ] ?? 0 );
	}
	return $data;
}, 10, 2 );

/** Đường dẫn chính sách của trang đang xem, '' nếu không phải trang chính sách */
function s24_current_policy() {
	if ( ! is_page() ) { return ''; }
	$path = array_search( get_queried_object_id(), array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) ), true );
	return false === $path ? '' : $path;
}

/* Trang chính sách khai đúng ngôn ngữ của nó cho trình duyệt và bot, không phụ thuộc ngôn ngữ của site */
add_filter( 'language_attributes', function ( $output ) {
	$path  = s24_current_policy();
	$pages = s24_policy_pages();
	return $path ? 'lang="' . $pages[ $path ][2] . '"' : $output;
} );

/* Liên kết hai bản ngôn ngữ của cùng một trang (hreflang) */
add_action( 'wp_head', function () {
	$path  = s24_current_policy();
	$pages = s24_policy_pages();
	if ( ! $path || ! $pages[ $path ][3] ) { return; }
	foreach ( array( $path, $pages[ $path ][3] ) as $p ) {
		$page = get_page_by_path( $p );
		if ( $page && 'publish' === $page->post_status ) {
			echo '<link rel="alternate" hreflang="' . esc_attr( $pages[ $p ][2] ) . '" href="' . esc_url( get_permalink( $page ) ) . '">' . "\n";
		}
	}
} );

/** Menu con của mục "Chính sách" ở đầu trang: 4 trang tiếng Việt và trang mục lục tiếng Anh. đường dẫn => chữ */
function s24_policy_nav_pages() {
	$pages = s24_policy_pages();
	$out   = array();
	foreach ( $pages as $path => $p ) {
		if ( 'vi' === $p[2] ) { $out[ $path ] = $p[1]; }
	}
	$out['en'] = $pages['en'][1];
	return $out;
}

/* Thêm mục "Chính sách" (có menu con) vào cuối menu đầu trang MỘT LẦN, thành mục thường để chủ đầu tư
   sửa, xếp lại hoặc xoá trong Giao diện → Menu. Menu đã có link tới trang chính sách thì không thêm. */
add_action( 'admin_init', function () {
	if ( get_option( 's24_policy_nav' ) || ! current_user_can( 'edit_theme_options' ) ) { return; }
	$ids = array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) );
	if ( ! $ids ) { return; } // chưa tạo trang chính sách
	$menu = (int) ( get_nav_menu_locations()['primary'] ?? 0 );
	if ( $menu && wp_get_nav_menu_object( $menu ) ) {
		foreach ( (array) wp_get_nav_menu_items( $menu ) as $item ) {
			if ( 'post_type' === $item->type && in_array( (int) $item->object_id, $ids, true ) ) {
				update_option( 's24_policy_nav', 1 );
				return;
			}
		}
		$parent = wp_update_nav_menu_item( $menu, 0, array(
			'menu-item-title'  => 'Chính sách',
			'menu-item-url'    => '#',
			'menu-item-type'   => 'custom',
			'menu-item-status' => 'publish',
		) );
		if ( is_wp_error( $parent ) ) { return; }
		foreach ( s24_policy_nav_pages() as $path => $label ) {
			if ( empty( $ids[ $path ] ) ) { continue; }
			wp_update_nav_menu_item( $menu, 0, array(
				'menu-item-title'     => $label,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $ids[ $path ],
				'menu-item-type'      => 'post_type',
				'menu-item-parent-id' => $parent,
				'menu-item-status'    => 'publish',
			) );
		}
	}
	update_option( 's24_policy_nav', 1 ); // chưa gán menu thì menu mặc định đã có sẵn mục này
}, 30 );

/* Mục menu trỏ tới trang chính sách tiếng Anh khai đúng ngôn ngữ, như menu chân trang mặc định */
add_filter( 'nav_menu_link_attributes', function ( $atts, $item ) {
	if ( 'post_type' !== $item->type ) { return $atts; }
	$path = array_search( (int) $item->object_id, array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) ), true );
	if ( false !== $path && 'en' === s24_policy_pages()[ $path ][2] ) {
		$atts['lang'] = $atts['hreflang'] = 'en';
	}
	return $atts;
}, 10, 2 );

/* Mục "Chính sách" trong menu đầu trang mặc định (khi chưa gán menu cho vị trí "Menu chính") */
function s24_policy_nav_fallback() {
	echo '<li class="menu-item-has-children"><a href="#">Chính sách</a><ul class="sub-menu">';
	foreach ( s24_policy_nav_pages() as $path => $label ) {
		s24_policy_link( $path, $label, 'en' === $path ? 'en' : '' );
	}
	echo '</ul></li>';
}

/* Menu chân trang mặc định khi chưa gán menu cho vị trí "Chân trang – Chính sách":
   trên trang tiếng Anh hiện bộ link tiếng Anh, còn lại hiện bộ tiếng Việt kèm link "English" */
function s24_policy_menu_fallback() {
	$pages = s24_policy_pages();
	$cur   = s24_current_policy();
	$lang  = $cur ? $pages[ $cur ][2] : 'vi';
	echo '<ul>';
	foreach ( $pages as $path => $p ) {
		if ( $p[2] === $lang && 'en' !== $path ) { s24_policy_link( $path, $p[1] ); }
	}
	if ( 'vi' === $lang ) {
		s24_policy_link( 'en', $pages['en'][1], 'en' );
	} elseif ( $pages[ $cur ][3] ) {
		s24_policy_link( $pages[ $cur ][3], 'Tiếng Việt', 'vi' );
	} else {
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" lang="vi">Tiếng Việt</a></li>';
	}
	echo '</ul>';
}

/** Một mục menu chân trang, chỉ hiện khi trang đang xuất bản */
function s24_policy_link( $path, $label, $lang = '' ) {
	$page = get_page_by_path( $path );
	if ( $page && 'publish' === $page->post_status ) {
		$attr = $lang ? ' lang="' . esc_attr( $lang ) . '" hreflang="' . esc_attr( $lang ) . '"' : '';
		echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '"' . $attr . '>' . esc_html( $label ) . '</a></li>';
	}
}
