<?php
/**
 * Giá trị mặc định của mọi nội dung chỉnh được trong Tùy biến.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function s24_defaults() {
	static $d = null;
	if ( null !== $d ) { return $d; }
	$img = S24_URI . '/assets/img/';
	$d   = array(
		// Màu sắc
		'color_forest' => '#0b4a2a',
		'color_leaf'   => '#2f9a2c',
		'color_lime'   => '#8fd400',
		'color_bg'     => '#eeefed',

		// Đầu trang
		'brand_name'      => 'Sacnhanh24h',
		'brand_tagline'   => 'Trạm sạc nhanh 24h',
		'header_btn_text' => 'Liên hệ',
		'header_btn_link' => '#lien-he',
		'seo_description' => 'Sacnhanh24h – Công ty Cổ phần Trạm sạc nhanh 24h. Hệ thống trạm sạc nhanh xe điện, giải pháp sạc cho gia đình, doanh nghiệp và không gian công cộng.',

		// Banner
		'show_hero'      => true,
		'hero_banner'    => $img . 'banner.jpg',
		'hero_title_1'   => 'Sạc nhanh xe điện,',
		'hero_title_2'   => 'mọi lúc 24/7',
		'hero_text'      => 'Hệ thống trạm sạc nhanh DC phủ khắp các tỉnh thành. Chúng tôi nhận cung cấp, lắp đặt và bảo trì trạm sạc trọn gói cho chủ đầu tư, tòa nhà và gia đình.',
		'hero_btn1_text' => 'Tìm trạm sạc',
		'hero_btn1_link' => '#tram-sac',
		'hero_btn2_text' => 'Nhận tư vấn',
		'hero_btn2_link' => '#lien-he',

		// Bảng giá
		'show_pricing'   => true,
		'price_kicker'   => 'Ưu đãi tháng 10',
		'price_title'    => 'Giá sạc theo khung giờ',
		'price_text'     => 'Sạc vào giờ thấp điểm để tiết kiệm hơn. Giá áp dụng cho tất cả trạm sạc nhanh DC của Sacnhanh24h.',
		'price_btn_text' => 'Xem trạm gần bạn',
		'price_btn_link' => '#tram-sac',
		'price_unit'     => 'đ/kWh',
		'tier1_name'     => 'Thấp điểm',
		'tier1_time'     => '22:00 – 04:00',
		'tier1_price'    => '3.500',
		'tier2_name'     => 'Bình thường',
		'tier2_time'     => '04:00 – 09:30 · 11:30 – 17:00 · 20:00 – 22:00',
		'tier2_price'    => '4.200',
		'tier3_name'     => 'Cao điểm',
		'tier3_time'     => '09:30 – 11:30 · 17:00 – 20:00',
		'tier3_price'    => '4.900',
		'price_note'     => '* Giá đã bao gồm VAT.',

		// Trạm sạc
		'show_stations'   => true,
		'stations_title1' => 'Hệ thống',
		'stations_title2' => 'trạm sạc',

		// Giới thiệu
		'show_about'   => true,
		'about_kicker' => 'Về chúng tôi',
		'about_title1' => 'Sacnhanh24h',
		'about_title2' => 'tại Việt Nam',
		'about_text'   => 'Công ty Cổ phần Trạm sạc nhanh 24h hướng đến trở thành nhà cung cấp giải pháp sạc xe điện tích hợp hàng đầu tại Việt Nam, với hệ thống sạc toàn diện cho nhà ở, chung cư, tòa nhà thương mại và dọc các tuyến cao tốc chính.',
		'about_image'  => '',
		'stat1_num'    => '24/7',
		'stat1_label'  => 'trạm sạc luôn sẵn sàng',
		'stat2_num'    => '40+',
		'stat2_label'  => 'trạm sạc đang hoạt động',
		'stat3_num'    => '120kW',
		'stat3_label'  => 'công suất sạc nhanh DC',

		// Đối tác
		'show_partners'   => true,
		'partners_kicker' => 'Hợp tác chiến lược',
		'partners_title'  => 'Đối tác chính',

		// Dự án
		'show_projects'   => true,
		'projects_kicker' => 'Đã triển khai',
		'projects_title'  => 'Dự án chính',
		'projects_tab1'   => 'Thương mại & Công nghiệp',
		'projects_tab2'   => 'Căn hộ cao cấp & Nhà riêng',

		// Hãng xe
		'show_cars'   => true,
		'cars_kicker' => 'Tương thích',
		'cars_title'  => 'Hãng xe',

		// Giá trị
		'show_values'  => true,
		'value1_title' => 'Mạng lưới rộng khắp',
		'value1_text'  => 'Trạm sạc trải khắp các tỉnh thành, giúp chủ xe điện dễ dàng tìm điểm sạc công cộng.',
		'value2_title' => 'Giải pháp toàn diện',
		'value2_text'  => 'Từ bộ sạc tại nhà đến trạm sạc công cộng, kèm phần mềm quản lý và bảo trì định kỳ.',
		'value3_title' => 'Sạc nhanh, an toàn',
		'value3_text'  => 'Bộ sạc DC công suất cao, đạt chuẩn an toàn, vận hành ổn định suốt 24 giờ.',

		// Ban lãnh đạo
		'show_leaders'   => true,
		'leaders_kicker' => 'Đội ngũ',
		'leaders_title'  => 'Ban lãnh đạo',

		// Liên hệ
		'show_contact'    => true,
		'contact_kicker'  => 'Kết nối',
		'contact_title'   => 'Liên hệ với chúng tôi',
		'company'         => 'Công ty Cổ phần Trạm sạc nhanh 24h',
		'brand'           => 'Sacnhanh24h',
		'email'           => 'sacnhanh24h@gmail.com',
		'website'         => 'sacnhanh24h.vn',
		'address'         => '',
		'hotline'         => '',
		'bank_name'       => 'Techcombank',
		'bank_account'    => '69688788',
		'bank_holder'     => 'Công ty Cổ phần Trạm sạc nhanh 24h',
		'form_title'      => 'Nhận tư vấn lắp đặt',
		'form_success'    => 'Cảm ơn bạn! Chúng tôi đã nhận được yêu cầu và sẽ liên hệ lại sớm.',

		// Chân trang
		'footer_text'  => 'Sạc nhanh, an toàn, mọi lúc 24/7.',
		'play_link'    => '',
		'appstore_link'=> '',
		'facebook'     => '',
		'zalo'         => '',
		'youtube'      => '',
		'copyright'    => 'Sacnhanh24h. Bảo lưu mọi quyền.',
	);
	return $d;
}

/** Lấy một giá trị đã chỉnh (hoặc mặc định) */
function s24_mod( $key ) {
	$d = s24_defaults();
	return get_theme_mod( $key, $d[ $key ] ?? '' );
}

/** Link: cho phép nhập "#tram-sac" để trỏ về một mục trên trang chủ */
function s24_link( $key ) {
	$v = trim( (string) s24_mod( $key ) );
	if ( '' === $v ) { return '#'; }
	if ( '#' === $v[0] ) { return s24_anchor( substr( $v, 1 ) ); }
	return $v;
}

/* Màu thương hiệu → biến CSS */
add_action( 'wp_head', function () {
	$map = array( 'forest' => 'color_forest', 'leaf' => 'color_leaf', 'lime' => 'color_lime', 'concrete' => 'color_bg' );
	$css = '';
	foreach ( $map as $var => $key ) {
		$c = sanitize_hex_color( s24_mod( $key ) );
		if ( $c ) { $css .= "--{$var}:{$c};"; }
	}
	$f = sanitize_hex_color( s24_mod( 'color_forest' ) );
	if ( $f ) { $css .= '--forest-d:color-mix(in srgb,' . $f . ' 75%,#000);'; }
	$l = sanitize_hex_color( s24_mod( 'color_lime' ) );
	if ( $l ) { $css .= '--lime-d:color-mix(in srgb,' . $l . ' 85%,#000);'; }
	echo '<style id="s24-colors">:root{' . $css . '}</style>' . "\n";
}, 20 );
