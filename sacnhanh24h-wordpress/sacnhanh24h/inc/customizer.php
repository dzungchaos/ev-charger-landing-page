<?php
/**
 * Giao diện → Tùy biến: mọi chữ, ảnh, màu trên trang chủ.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'customize_register', function ( WP_Customize_Manager $wp ) {

	$d = s24_defaults();

	if ( ! class_exists( 'S24_Note_Control' ) ) {
		class S24_Note_Control extends WP_Customize_Control {
			public $type = 's24_note';
			public function render_content() {
				echo '<p style="background:#eaf5e4;border-left:3px solid #2f9a2c;padding:8px 10px;margin:0">' . wp_kses_post( $this->description ) . '</p>';
			}
		}
	}

	/** Thêm một ô chỉnh */
	$add = function ( $section, $key, $label, $type = 'text', $desc = '' ) use ( $wp, $d ) {
		$sanitize = array(
			'text'     => 'sanitize_text_field',
			'textarea' => 'sanitize_textarea_field',
			'url'      => 's24_sanitize_link',
			'link'     => 's24_sanitize_link',
			'checkbox' => 's24_sanitize_bool',
			'image'    => 'esc_url_raw',
			'color'    => 'sanitize_hex_color',
			'email'    => 'sanitize_email',
		);
		$wp->add_setting( $key, array(
			'default'           => $d[ $key ] ?? '',
			'sanitize_callback' => $sanitize[ $type ] ?? 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$args = array( 'label' => $label, 'section' => $section, 'description' => $desc );
		if ( 'image' === $type ) {
			$wp->add_control( new WP_Customize_Image_Control( $wp, $key, $args ) );
		} elseif ( 'color' === $type ) {
			$wp->add_control( new WP_Customize_Color_Control( $wp, $key, $args ) );
		} else {
			$args['type'] = 'link' === $type ? 'text' : $type;
			$wp->add_control( $key, $args );
		}
	};

	$wp->add_panel( 's24_home', array(
		'title'       => 'Trang chủ Sacnhanh24h',
		'priority'    => 1,
		'description' => 'Chỉnh từng phần của trang chủ. Bên phải sẽ hiện bản xem trước; bấm "Đăng" ở trên cùng để lưu. Ô link có thể nhập "#tram-sac", "#bang-gia", "#lien-he"… để trỏ về một mục trên trang.',
	) );
	$sec = function ( $id, $title, $prio ) use ( $wp ) {
		$wp->add_section( $id, array( 'title' => $title, 'panel' => 's24_home', 'priority' => $prio ) );
	};

	/* Màu sắc */
	$sec( 's24_colors', 'Màu sắc thương hiệu', 1 );
	$add( 's24_colors', 'color_forest', 'Màu chính (xanh đậm – chữ, nút, nền khối)', 'color' );
	$add( 's24_colors', 'color_leaf', 'Màu phụ (xanh lá – tiêu đề phụ, biểu tượng)', 'color' );
	$add( 's24_colors', 'color_lime', 'Màu nhấn (xanh chanh – nút nổi bật)', 'color' );
	$add( 's24_colors', 'color_bg', 'Màu nền trang', 'color' );

	/* Đầu trang */
	$sec( 's24_header', 'Đầu trang & logo', 2 );
	$wp->add_control( new S24_Note_Control( $wp, 's24_logo_hint', array(
		'section' => 's24_header', 'settings' => array(),
		'description' => 'Đổi logo: vào mục "Nhận dạng trang" (Site Identity) ở menu Tùy biến chính → Logo. Ở đó cũng đổi được tên website trên tab trình duyệt và biểu tượng trang (favicon).',
	) ) );
	$add( 's24_header', 'brand_name', 'Tên thương hiệu cạnh logo' );
	$add( 's24_header', 'brand_tagline', 'Dòng chữ nhỏ dưới tên' );
	$add( 's24_header', 'header_btn_text', 'Chữ nút bên phải' );
	$add( 's24_header', 'header_btn_link', 'Link nút bên phải', 'link' );
	$add( 's24_header', 'seo_description', 'Mô tả website (hiện trên Google, Facebook)', 'textarea' );

	/* Banner */
	$sec( 's24_hero', 'Banner đầu trang', 3 );
	$add( 's24_hero', 'show_hero', 'Hiện phần này', 'checkbox' );
	$add( 's24_hero', 'hero_banner', 'Ảnh pano / banner', 'image', 'Nên dùng ảnh ngang, tỉ lệ khoảng 2.4 : 1 (ví dụ 1400 × 590 px).' );
	$add( 's24_hero', 'hero_title_1', 'Tiêu đề – dòng 1 (màu đậm)' );
	$add( 's24_hero', 'hero_title_2', 'Tiêu đề – dòng 2 (màu xanh lá)' );
	$add( 's24_hero', 'hero_text', 'Đoạn giới thiệu', 'textarea' );
	$add( 's24_hero', 'hero_btn1_text', 'Nút 1 – chữ' );
	$add( 's24_hero', 'hero_btn1_link', 'Nút 1 – link', 'link' );
	$add( 's24_hero', 'hero_btn2_text', 'Nút 2 – chữ' );
	$add( 's24_hero', 'hero_btn2_link', 'Nút 2 – link', 'link' );

	/* Bảng giá */
	$sec( 's24_pricing', 'Bảng giá theo khung giờ', 4 );
	$add( 's24_pricing', 'show_pricing', 'Hiện phần này', 'checkbox' );
	$add( 's24_pricing', 'price_kicker', 'Dòng chữ nhỏ phía trên' );
	$add( 's24_pricing', 'price_title', 'Tiêu đề' );
	$add( 's24_pricing', 'price_text', 'Mô tả', 'textarea' );
	$add( 's24_pricing', 'price_btn_text', 'Chữ trên nút' );
	$add( 's24_pricing', 'price_btn_link', 'Link nút', 'link' );
	$add( 's24_pricing', 'price_unit', 'Đơn vị giá' );
	foreach ( array( 1 => 'Khung 1 (nổi bật)', 2 => 'Khung 2', 3 => 'Khung 3' ) as $i => $lbl ) {
		$add( 's24_pricing', "tier{$i}_name", "$lbl – tên" );
		$add( 's24_pricing', "tier{$i}_time", "$lbl – giờ áp dụng" );
		$add( 's24_pricing', "tier{$i}_price", "$lbl – giá" );
	}
	$add( 's24_pricing', 'price_note', 'Ghi chú dưới bảng giá' );

	/* Trạm sạc */
	$sec( 's24_stations', 'Hệ thống trạm sạc', 5 );
	$wp->add_control( new S24_Note_Control( $wp, 's24_st_hint', array(
		'section' => 's24_stations', 'settings' => array(),
		'description' => 'Thêm / sửa / xoá trạm sạc: ra trang quản trị → mục "Trạm sạc" ở menu bên trái.',
	) ) );
	$add( 's24_stations', 'show_stations', 'Hiện phần này', 'checkbox' );
	$add( 's24_stations', 'stations_title1', 'Tiêu đề – phần màu đậm' );
	$add( 's24_stations', 'stations_title2', 'Tiêu đề – phần màu xanh lá' );

	/* Giới thiệu */
	$sec( 's24_about', 'Giới thiệu', 6 );
	$add( 's24_about', 'show_about', 'Hiện phần này', 'checkbox' );
	$add( 's24_about', 'about_kicker', 'Dòng chữ nhỏ phía trên' );
	$add( 's24_about', 'about_title1', 'Tiêu đề – phần màu đậm' );
	$add( 's24_about', 'about_title2', 'Tiêu đề – phần màu xanh lá' );
	$add( 's24_about', 'about_text', 'Nội dung', 'textarea' );
	$add( 's24_about', 'about_image', 'Ảnh bên phải (để trống sẽ dùng logo)', 'image' );
	for ( $i = 1; $i <= 3; $i++ ) {
		$add( 's24_about', "stat{$i}_num", "Con số $i" );
		$add( 's24_about', "stat{$i}_label", "Chú thích con số $i" );
	}

	/* Đối tác, Dự án, Hãng xe, Lãnh đạo: tiêu đề phần */
	$sec( 's24_partners', 'Đối tác', 7 );
	$add( 's24_partners', 'show_partners', 'Hiện phần này', 'checkbox', 'Nội dung từng đối tác: trang quản trị → mục "Đối tác".' );
	$add( 's24_partners', 'partners_kicker', 'Dòng chữ nhỏ phía trên' );
	$add( 's24_partners', 'partners_title', 'Tiêu đề' );

	$sec( 's24_projects', 'Dự án', 8 );
	$add( 's24_projects', 'show_projects', 'Hiện phần này', 'checkbox', 'Từng dự án: trang quản trị → mục "Dự án".' );
	$add( 's24_projects', 'projects_kicker', 'Dòng chữ nhỏ phía trên' );
	$add( 's24_projects', 'projects_title', 'Tiêu đề' );
	$add( 's24_projects', 'projects_tab1', 'Tên tab 1' );
	$add( 's24_projects', 'projects_tab2', 'Tên tab 2' );

	$sec( 's24_cars', 'Hãng xe', 9 );
	$add( 's24_cars', 'show_cars', 'Hiện phần này', 'checkbox', 'Logo từng hãng: trang quản trị → mục "Hãng xe".' );
	$add( 's24_cars', 'cars_kicker', 'Dòng chữ nhỏ phía trên' );
	$add( 's24_cars', 'cars_title', 'Tiêu đề' );

	$sec( 's24_values', 'Ba giá trị cốt lõi (khối xanh đậm)', 10 );
	$add( 's24_values', 'show_values', 'Hiện phần này', 'checkbox' );
	for ( $i = 1; $i <= 3; $i++ ) {
		$add( 's24_values', "value{$i}_title", "Giá trị $i – tiêu đề" );
		$add( 's24_values', "value{$i}_text", "Giá trị $i – mô tả", 'textarea' );
	}

	$sec( 's24_leaders', 'Ban lãnh đạo', 11 );
	$add( 's24_leaders', 'show_leaders', 'Hiện phần này', 'checkbox', 'Từng thành viên: trang quản trị → mục "Ban lãnh đạo".' );
	$add( 's24_leaders', 'leaders_kicker', 'Dòng chữ nhỏ phía trên' );
	$add( 's24_leaders', 'leaders_title', 'Tiêu đề' );

	/* Liên hệ */
	$sec( 's24_contact', 'Liên hệ & tài khoản ngân hàng', 12 );
	$add( 's24_contact', 'show_contact', 'Hiện phần này', 'checkbox' );
	$add( 's24_contact', 'contact_kicker', 'Dòng chữ nhỏ phía trên' );
	$add( 's24_contact', 'contact_title', 'Tiêu đề' );
	$add( 's24_contact', 'company', 'Tên công ty' );
	$add( 's24_contact', 'brand', 'Thương hiệu' );
	$add( 's24_contact', 'email', 'Email (cũng là nơi nhận yêu cầu tư vấn)', 'email' );
	$add( 's24_contact', 'website', 'Website' );
	$add( 's24_contact', 'address', 'Địa chỉ trụ sở', 'textarea' );
	$add( 's24_contact', 'hotline', 'Hotline' );
	$add( 's24_contact', 'bank_name', 'Ngân hàng' );
	$add( 's24_contact', 'bank_account', 'Số tài khoản' );
	$add( 's24_contact', 'bank_holder', 'Chủ tài khoản' );
	$add( 's24_contact', 'form_title', 'Tiêu đề form tư vấn' );
	$add( 's24_contact', 'form_success', 'Thông báo khi khách gửi form thành công', 'textarea' );

	/* Chân trang */
	$sec( 's24_footer', 'Chân trang & mạng xã hội', 13 );
	$add( 's24_footer', 'footer_text', 'Dòng giới thiệu ngắn', 'textarea' );
	$add( 's24_footer', 'play_link', 'Link ứng dụng Google Play', 'url', 'Để trống sẽ ẩn nút.' );
	$add( 's24_footer', 'appstore_link', 'Link ứng dụng App Store', 'url', 'Để trống sẽ ẩn nút.' );
	$add( 's24_footer', 'facebook', 'Link Facebook', 'url' );
	$add( 's24_footer', 'zalo', 'Link Zalo', 'url' );
	$add( 's24_footer', 'youtube', 'Link YouTube', 'url' );
	$add( 's24_footer', 'copyright', 'Dòng bản quyền (sau © năm)' );
} );

function s24_sanitize_bool( $v ) { return (bool) $v; }

/** Cho phép link đầy đủ hoặc "#muc-tren-trang" */
function s24_sanitize_link( $v ) {
	$v = trim( (string) $v );
	if ( '' === $v ) { return ''; }
	if ( '#' === $v[0] ) { return '#' . sanitize_title( substr( $v, 1 ) ); }
	return esc_url_raw( $v );
}
