<?php
/**
 * Sacnhanh24h theme – functions.php
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'S24_VER', '1.1.0' );
define( 'S24_DIR', get_template_directory() );
define( 'S24_URI', get_template_directory_uri() );

require S24_DIR . '/inc/defaults.php';
require S24_DIR . '/inc/post-types.php';
require S24_DIR . '/inc/customizer.php';
require S24_DIR . '/inc/contact.php';
require S24_DIR . '/inc/seed.php';
require S24_DIR . '/inc/policies.php';

/* ---------- Thiết lập theme ---------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 200, 'width' => 200, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'site-icon' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array(
		'primary'       => 'Menu chính (đầu trang)',
		'footer_policy' => 'Chân trang – Chính sách',
	) );
} );

/* ---------- CSS / JS ---------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 's24-fonts', 'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap', array(), null );
	wp_enqueue_style( 's24-main', S24_URI . '/assets/css/main.css', array(), S24_VER );

	if ( is_front_page() ) {
		wp_enqueue_style( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css', array(), '1.9.4' );
		wp_enqueue_script( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js', array(), '1.9.4', true );
		wp_enqueue_script( 's24-main', S24_URI . '/assets/js/main.js', array( 'leaflet' ), S24_VER, true );
		wp_localize_script( 's24-main', 'S24', array(
			'stations' => s24_get_stations(),
			'pin'      => S24_URI . '/assets/img/logo.png',
			'ajax'     => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 's24_contact' ),
			'bank'     => s24_mod( 'bank_account' ),
		) );
	} else {
		wp_enqueue_script( 's24-menu', S24_URI . '/assets/js/main.js', array(), S24_VER, true );
	}
} );

/* Favicon mặc định khi chưa chọn "Biểu tượng trang" trong Tùy biến */
add_action( 'wp_head', function () {
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" type="image/png" href="' . esc_url( S24_URI . '/assets/img/favicon.png' ) . '">' . "\n";
	}
	if ( is_front_page() ) {
		echo '<meta name="description" content="' . esc_attr( s24_mod( 'seo_description' ) ) . '">' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( s24_mod( 'seo_description' ) ) . '">' . "\n";
		echo '<meta property="og:image" content="' . esc_url( s24_mod( 'hero_banner' ) ) . '">' . "\n";
	}
}, 1 );

/* ---------- Hàm tiện ích ---------- */

/** Logo: ưu tiên logo chọn trong Tùy biến, nếu chưa có dùng logo của theme */
function s24_logo_url() {
	$id = get_theme_mod( 'custom_logo' );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'medium' );
		if ( $src ) { return $src; }
	}
	return S24_URI . '/assets/img/logo.png';
}

/** Link tới một mục trên trang chủ, dùng được từ mọi trang */
function s24_anchor( $id ) {
	return is_front_page() ? '#' . $id : home_url( '/#' . $id );
}

/** Danh sách trạm sạc cho bản đồ */
function s24_get_stations() {
	$q   = get_posts( array( 'post_type' => 'tram_sac', 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ) ) );
	$out = array();
	foreach ( $q as $p ) {
		$coords = s24_parse_coords( get_post_meta( $p->ID, 'toa_do', true ) );
		if ( ! $coords ) { continue; }
		$out[] = array(
			'id'       => count( $out ),
			'name'     => get_the_title( $p ),
			'address'  => (string) get_post_meta( $p->ID, 'dia_chi', true ),
			'province' => (string) get_post_meta( $p->ID, 'tinh', true ),
			'district' => (string) get_post_meta( $p->ID, 'quan', true ),
			'lat'      => $coords[0],
			'lng'      => $coords[1],
			'rating'   => (float) ( get_post_meta( $p->ID, 'danh_gia', true ) ?: 5 ),
			'free'     => (int) get_post_meta( $p->ID, 'cong_trong', true ),
			'total'    => (int) ( get_post_meta( $p->ID, 'tong_cong', true ) ?: 1 ),
		);
	}
	return $out;
}

/** "10.8452, 106.6553" → array(10.8452, 106.6553) */
function s24_parse_coords( $str ) {
	if ( ! preg_match( '/(-?\d+(?:\.\d+)?)\s*[,; ]\s*(-?\d+(?:\.\d+)?)/', (string) $str, $m ) ) { return false; }
	$lat = (float) $m[1]; $lng = (float) $m[2];
	if ( abs( $lat ) > 90 || abs( $lng ) > 180 ) { return false; }
	return array( $lat, $lng );
}

/** Truy vấn một loại nội dung theo thứ tự */
function s24_items( $type, $extra = array() ) {
	return get_posts( array_merge( array(
		'post_type'   => $type,
		'numberposts' => -1,
		'post_status' => 'publish',
		'orderby'     => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	), $extra ) );
}

/** Ảnh đại diện hoặc ô giữ chỗ */
function s24_media( $post_id, $placeholder, $size = 'large' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return '<div class="ph has-img">' . get_the_post_thumbnail( $post_id, $size, array( 'loading' => 'lazy' ) ) . '</div>';
	}
	return '<div class="ph">' . esc_html( $placeholder ) . '</div>';
}

/** Hiển thị/ẩn một phần trên trang chủ */
function s24_show( $section ) {
	return (bool) s24_mod( 'show_' . $section );
}

/* Menu mặc định nếu chưa tạo menu trong Giao diện → Menu */
function s24_menu_fallback() {
	$items = array(
		array( home_url( '/' ), 'Trang chủ' ),
		array( s24_anchor( 'tram-sac' ), 'Trạm sạc' ),
		array( s24_anchor( 'bang-gia' ), 'Bảng giá' ),
		array( s24_anchor( 'du-an' ), 'Dự án' ),
		array( s24_anchor( 'doi-tac' ), 'Đối tác' ),
		array( s24_anchor( 'gioi-thieu' ), 'Giới thiệu' ),
	);
	echo '<ul>';
	foreach ( $items as $i ) {
		echo '<li><a href="' . esc_url( $i[0] ) . '">' . esc_html( $i[1] ) . '</a></li>';
	}
	echo '</ul>';
}

/* Lối tắt "Sửa giao diện trang chủ" trên thanh quản trị */
add_action( 'admin_bar_menu', function ( $bar ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) { return; }
	$bar->add_node( array(
		'id'    => 's24-customize',
		'title' => '✎ Sửa giao diện trang chủ',
		'href'  => admin_url( 'customize.php?url=' . urlencode( home_url( '/' ) ) ),
	) );
}, 80 );

/* Bảng điều khiển: hộp hướng dẫn nhanh */
add_action( 'wp_dashboard_setup', function () {
	wp_add_dashboard_widget( 's24_help', 'Sacnhanh24h – Hướng dẫn nhanh', function () {
		$c = admin_url( 'customize.php?url=' . urlencode( home_url( '/' ) ) );
		echo '<ul style="list-style:disc;padding-left:18px;line-height:1.9">';
		echo '<li><a href="' . esc_url( $c ) . '"><b>Sửa chữ, ảnh, màu, bảng giá, liên hệ</b></a> – Giao diện → Tùy biến</li>';
		echo '<li><a href="' . esc_url( admin_url( 'edit.php?post_type=tram_sac' ) ) . '"><b>Trạm sạc</b></a> – thêm, sửa, xoá trạm trên bản đồ</li>';
		echo '<li><a href="' . esc_url( admin_url( 'edit.php?post_type=du_an' ) ) . '">Dự án</a> · <a href="' . esc_url( admin_url( 'edit.php?post_type=doi_tac' ) ) . '">Đối tác</a> · <a href="' . esc_url( admin_url( 'edit.php?post_type=lanh_dao' ) ) . '">Ban lãnh đạo</a> · <a href="' . esc_url( admin_url( 'edit.php?post_type=hang_xe' ) ) . '">Hãng xe</a></li>';
		echo '<li><a href="' . esc_url( admin_url( 'edit.php?post_type=lien_he' ) ) . '"><b>Yêu cầu tư vấn</b></a> – khách gửi từ form liên hệ</li>';
		echo '</ul>';
	} );
} );
