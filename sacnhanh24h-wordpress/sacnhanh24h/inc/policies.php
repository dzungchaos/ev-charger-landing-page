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
define( 'S24_POLICY_VER', 4 );

/** đường dẫn => array( tiêu đề trang, chữ ở chân trang, ngôn ngữ, đường dẫn bản ngôn ngữ kia )
 *  Tên trang theo Quyết định 01/2026/QĐ-TSN24H (bộ hồ sơ đăng ký với Bộ Công Thương) */
function s24_policy_pages() {
	return array(
		// Trang cha phải đứng trước các trang con
		'chinh-sach'                            => array( 'Chính sách', 'Chính sách', 'vi', '' ),
		'chinh-sach/dieu-khoan-su-dung'         => array( 'Điều khoản sử dụng chung và giới hạn trách nhiệm', 'Điều khoản sử dụng', 'vi', 'chinh-sach/en/terms-of-use' ),
		'chinh-sach/chinh-sach-bao-mat'         => array( 'Chính sách bảo vệ dữ liệu cá nhân và quyền riêng tư', 'Bảo vệ dữ liệu cá nhân', 'vi', 'chinh-sach/en/privacy-policy' ),
		'chinh-sach/xoa-du-lieu'                => array( 'Hướng dẫn xoá tài khoản và dữ liệu', 'Xoá tài khoản và dữ liệu', 'vi', 'chinh-sach/en/account-deletion' ),
		'chinh-sach/chinh-sach-hoan-tien'       => array( 'Phương thức cung cấp dịch vụ, chính sách chấm dứt dịch vụ và hoàn tiền', 'Cung cấp dịch vụ và hoàn tiền', 'vi', 'chinh-sach/en/refund-policy' ),
		'chinh-sach/giai-quyet-khieu-nai'       => array( 'Phương thức tiếp nhận và giải quyết phản ánh, yêu cầu, khiếu nại', 'Giải quyết khiếu nại', 'vi', '' ),
		'chinh-sach/chinh-sach-gia'             => array( 'Chính sách giá dịch vụ sạc xe điện', 'Chính sách giá', 'vi', '' ),
		'chinh-sach/chinh-sach-thanh-toan'      => array( 'Chính sách về thanh toán trực tuyến', 'Thanh toán', 'vi', '' ),
		'chinh-sach/dieu-kien-cung-cap-dich-vu' => array( 'Các điều kiện và hạn chế trong việc cung cấp dịch vụ', 'Điều kiện cung cấp dịch vụ', 'vi', '' ),
		'chinh-sach/ho-tro-truc-tuyen'          => array( 'Hình thức hỗ trợ trực tuyến và cam kết chất lượng dịch vụ', 'Hỗ trợ trực tuyến', 'vi', '' ),
		'chinh-sach/cau-hoi-thuong-gap'         => array( 'Câu hỏi thường gặp', 'Câu hỏi thường gặp', 'vi', '' ),
		'chinh-sach/en'                         => array( 'Policies', 'English', 'en', '' ),
		'chinh-sach/en/terms-of-use'            => array( 'Terms of Use', 'Terms of Use', 'en', 'chinh-sach/dieu-khoan-su-dung' ),
		'chinh-sach/en/privacy-policy'          => array( 'Privacy and Security Policy', 'Privacy and Security', 'en', 'chinh-sach/chinh-sach-bao-mat' ),
		'chinh-sach/en/account-deletion'        => array( 'Account and Data Deletion', 'Account and Data Deletion', 'en', 'chinh-sach/xoa-du-lieu' ),
		'chinh-sach/en/refund-policy'           => array( 'Service Delivery, Termination and Refund Policy', 'Service and Refunds', 'en', 'chinh-sach/chinh-sach-hoan-tien' ),
	);
}

/** File nội dung gốc của trang: inc/policies/<đường dẫn bỏ "chinh-sach/", "/" đổi thành "-">.html */
function s24_policy_file( $path ) {
	$rel = preg_replace( '#^chinh-sach/?#', '', $path );
	return S24_DIR . '/inc/policies/' . ( '' === $rel ? 'chinh-sach' : str_replace( '/', '-', $rel ) ) . '.html';
}

/** Link tới một trang chính sách theo đường dẫn trong s24_policy_pages() */
function s24_policy_url( $path ) {
	$ids = (array) get_option( 's24_policy_pages', array() );
	$url = empty( $ids[ $path ] ) ? '' : get_permalink( (int) $ids[ $path ] );
	return $url ?: home_url( '/' . $path . '/' );
}

/* Trước 1.4.0 các trang nằm ngay dưới gốc (/dieu-khoan-su-dung/, /en/terms-of-use/…).
   Chủ dự án đổi sang /chinh-sach/<loại>/. URL cũ đã khai ở App, kho ứng dụng, hồ sơ Bộ Công Thương
   nên luôn chuyển hướng 301 sang URL mới. Đường dẫn cũ = đường dẫn mới bỏ "chinh-sach/". */
function s24_policy_old_paths() {
	$out = array();
	foreach ( array_keys( s24_policy_pages() ) as $path ) {
		if ( 'chinh-sach' !== $path ) { $out[ substr( $path, strlen( 'chinh-sach/' ) ) ] = $path; }
	}
	return $out; // đường dẫn cũ => đường dẫn mới
}

/* Chuyển trang đang có vào dưới trang cha /chinh-sach/ MỘT LẦN, giữ nguyên nội dung (có thể đã sửa tay).
   Chạy trước bước tạo trang còn thiếu (ưu tiên 10), để không tạo trùng trang ở đường dẫn mới. */
add_action( 'admin_init', function () {
	if ( get_option( 's24_policy_moved' ) || ! current_user_can( 'publish_pages' ) ) { return; }
	$old = array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) );
	if ( ! $old || isset( $old['chinh-sach'] ) ) { update_option( 's24_policy_moved', 1 ); return; } // site mới
	$parent = get_page_by_path( 'chinh-sach' );
	$pid    = $parent ? $parent->ID : wp_insert_post( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'post_name'      => 'chinh-sach',
		'post_title'     => 'Chính sách',
		'post_content'   => (string) file_get_contents( s24_policy_file( 'chinh-sach' ) ),
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
	) );
	if ( ! $pid || is_wp_error( $pid ) ) { return; }
	$ids = array( 'chinh-sach' => (int) $pid );
	foreach ( s24_policy_old_paths() as $from => $to ) {
		if ( ! empty( $old[ $from ] ) ) { $ids[ $to ] = $old[ $from ]; }
	}
	// Ghi đường dẫn mới trước: bộ lọc khoá đường dẫn bên dưới đặt trang cha theo option này
	update_option( 's24_policy_pages', $ids );
	foreach ( $ids as $path => $id ) {
		if ( 'chinh-sach' === dirname( $path ) ) { wp_update_post( array( 'ID' => $id, 'post_parent' => $pid ) ); }
	}
	// Link giữa các trang trong nội dung trỏ thẳng URL mới (URL cũ vẫn chuyển hướng được)
	foreach ( $ids as $id ) {
		$post = get_post( $id );
		$html = $post ? preg_replace_callback( '#href="/([a-z0-9/-]+?)/(["\#])#', function ( $m ) {
			$paths = s24_policy_old_paths();
			return isset( $paths[ $m[1] ] ) ? 'href="/' . $paths[ $m[1] ] . '/' . $m[2] : $m[0];
		}, $post->post_content ) : '';
		if ( $post && $html !== $post->post_content ) { wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $html ) ) ); }
	}
	update_option( 's24_policy_moved', 1 );
}, 5 );

/* URL cũ → URL mới, 301 */
add_action( 'template_redirect', function () {
	$req  = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$base = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
	if ( $base && 0 === strpos( $req, $base . '/' ) ) { $req = substr( $req, strlen( $base ) + 1 ); }
	$paths = s24_policy_old_paths();
	$ids   = (array) get_option( 's24_policy_pages', array() );
	// Chỉ chuyển khi trang đã nằm ở đường dẫn mới (sau khi quản trị viên vào wp-admin lần đầu)
	if ( isset( $paths[ $req ] ) && ! empty( $ids[ $paths[ $req ] ] ) ) {
		$qs = (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY );
		wp_safe_redirect( s24_policy_url( $paths[ $req ] ) . ( $qs ? '?' . $qs : '' ), 301 );
		exit;
	}
}, 1 );

/* Theo hồ sơ Bộ Công Thương (QĐ 01/2026/QĐ-TSN24H, hiệu lực 20/11/2026): 9 trang đã tạo trên site được thay
   nội dung một lần bằng bản mới trong inc/policies/. Chỉ thay trang còn nguyên bản theme đã tạo (so mã băm
   nội dung cũ); trang chủ đầu tư đã tự sửa thì giữ nguyên và báo trong wp-admin để sửa tay. */
function s24_policy_old_hashes() {
	return array(
		'chinh-sach/dieu-khoan-su-dung'         => array( 'f62857d3e66e0d17f9f5baf51c9a9c2c', 'Điều khoản sử dụng' ),
		'chinh-sach/chinh-sach-bao-mat'         => array( '917677def9655f42067326e4df5b8117', 'Chính sách quyền riêng tư và bảo mật' ),
		'chinh-sach/xoa-du-lieu'                => array( '7d7fc95192adc2d6c688c8557425b823', '' ),
		'chinh-sach/chinh-sach-hoan-tien'       => array( 'abb0ced6591c6545afa97855de4d70e5', 'Chính sách hoàn tiền và khiếu nại' ),
		'chinh-sach/en'                         => array( 'a238fc8a0d525c0ec515370dcd7ede07', '' ),
		'chinh-sach/en/terms-of-use'            => array( '7bd436c9b761099ea66a7ac145872bce', '' ),
		'chinh-sach/en/privacy-policy'          => array( '442f336b7714137df8e744bcffd44a73', '' ),
		'chinh-sach/en/account-deletion'        => array( '01bd8f978dfce78bf79d8df725e9b193', '' ),
		'chinh-sach/en/refund-policy'           => array( 'a6d26b190a1dbd8b8064e1dc8c5c6f2c', 'Refund and Complaints Policy' ),
	);
}

add_action( 'admin_init', function () {
	if ( get_option( 's24_policy_bct' ) || ! current_user_can( 'publish_pages' ) ) { return; }
	$ids   = array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) );
	$pages = s24_policy_pages();
	$kept  = array();
	foreach ( s24_policy_old_hashes() as $path => $old ) {
		$post = empty( $ids[ $path ] ) ? null : get_post( $ids[ $path ] );
		if ( ! $post ) { continue; }
		$data = array( 'ID' => $post->ID );
		$new  = (string) file_get_contents( s24_policy_file( $path ) );
		$cur  = md5( trim( str_replace( "\r", '', $post->post_content ) ) );
		if ( $cur === $old[0] ) {
			$data['post_content'] = $new;
		} elseif ( md5( trim( str_replace( "\r", '', $new ) ) ) !== $cur ) { // trang tạo từ bản mới thì đã đúng
			$kept[] = $path;
		}
		if ( $old[1] && $old[1] === $post->post_title ) { $data['post_title'] = $pages[ $path ][0]; }
		if ( count( $data ) > 1 ) { wp_update_post( wp_slash( $data ) ); }
	}
	update_option( 's24_policy_bct_kept', $kept );
	update_option( 's24_policy_bct', 1 );
}, 15 );

add_action( 'admin_notices', function () {
	$kept = (array) get_option( 's24_policy_bct_kept', array() );
	if ( ! $kept || ! current_user_can( 'publish_pages' ) ) { return; }
	echo '<div class="notice notice-warning"><p><b>Trang chính sách chưa được cập nhật theo hồ sơ Bộ Công Thương</b> vì đã được sửa trong wp-admin: /'
		. esc_html( implode( '/, /', $kept ) ) . '/. Hãy sửa tay theo nội dung trong theme (inc/policies/), xem HUONG-DAN.txt.</p></div>';
} );

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
			'post_content'   => (string) file_get_contents( s24_policy_file( $path ) ),
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		) );
		if ( $id && ! is_wp_error( $id ) ) { $ids[ $path ] = $id; }
	}
	update_option( 's24_policy_pages', $ids );
	update_option( 's24_policy_seeded', S24_POLICY_VER );

	// Trang quyền riêng tư của WordPress (Cài đặt → Quyền riêng tư) trỏ về trang của mình
	$cur = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( ! empty( $ids['chinh-sach/chinh-sach-bao-mat'] ) && ( ! $cur || 'publish' !== get_post_status( $cur ) ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['chinh-sach/chinh-sach-bao-mat'] );
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
		if ( 'vi' === $p[2] && 'chinh-sach' !== $path ) { $out[ $path ] = $p[1]; }
	}
	$out['chinh-sach/en'] = $pages['chinh-sach/en'][1];
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

/* Bổ sung các trang mới (theo hồ sơ Bộ Công Thương) vào mục "Chính sách" đã có trên menu đầu trang, một lần;
   đổi chữ hai mục cũ nếu chủ đầu tư chưa sửa; "English" giữ ở cuối */
add_action( 'admin_init', function () {
	if ( get_option( 's24_policy_nav_v2' ) || ! current_user_can( 'edit_theme_options' ) ) { return; }
	update_option( 's24_policy_nav_v2', 1 );
	$ids  = array_map( 'intval', (array) get_option( 's24_policy_pages', array() ) );
	$menu = (int) ( get_nav_menu_locations()['primary'] ?? 0 );
	if ( ! $menu || ! $ids || ! wp_get_nav_menu_object( $menu ) ) { return; }
	$items  = (array) wp_get_nav_menu_items( $menu );
	$parent = 0;
	$have   = array();
	$order  = 0;
	$en     = null;
	foreach ( $items as $item ) {
		$order = max( $order, (int) $item->menu_order );
		if ( 'post_type' !== $item->type ) { continue; }
		$path = array_search( (int) $item->object_id, $ids, true );
		if ( false === $path ) { continue; }
		$have[ $path ] = true;
		$parent        = $parent ?: (int) $item->menu_item_parent;
		if ( 'chinh-sach/en' === $path ) { $en = $item; }
		$rename = array( 'Hoàn tiền và khiếu nại' => 'Cung cấp dịch vụ và hoàn tiền', 'Quyền riêng tư và bảo mật' => 'Bảo vệ dữ liệu cá nhân' );
		$short  = s24_policy_nav_pages();
		if ( isset( $rename[ $item->post_title ] ) ) {
			wp_update_post( array( 'ID' => $item->ID, 'post_title' => $rename[ $item->post_title ] ) );
		} elseif ( '' === $item->post_title && isset( $short[ $path ] ) ) {
			// mục không đặt chữ riêng thì theo tên trang, mà tên trang vừa đổi sang tên dài theo hồ sơ
			wp_update_post( array( 'ID' => $item->ID, 'post_title' => $short[ $path ] ) );
		}
	}
	if ( ! $parent ) { return; } // không có mục "Chính sách" do theme thêm: để chủ đầu tư tự sắp xếp
	foreach ( s24_policy_nav_pages() as $path => $label ) {
		if ( empty( $have[ $path ] ) && ! empty( $ids[ $path ] ) ) {
			wp_update_nav_menu_item( $menu, 0, array(
				'menu-item-title'     => $label,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $ids[ $path ],
				'menu-item-type'      => 'post_type',
				'menu-item-parent-id' => $parent,
				'menu-item-position'  => ++$order,
				'menu-item-status'    => 'publish',
			) );
		}
	}
	if ( $en && (int) $en->menu_order < $order ) {
		wp_update_post( array( 'ID' => $en->ID, 'menu_order' => ++$order ) );
	}
}, 31 );

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
		s24_policy_link( $path, $label, 'chinh-sach/en' === $path ? 'en' : '' );
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
		if ( $p[2] === $lang && ! in_array( $path, array( 'chinh-sach', 'chinh-sach/en' ), true ) ) { s24_policy_link( $path, $p[1] ); }
	}
	if ( 'vi' === $lang ) {
		s24_policy_link( 'chinh-sach/en', $pages['chinh-sach/en'][1], 'en' );
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
