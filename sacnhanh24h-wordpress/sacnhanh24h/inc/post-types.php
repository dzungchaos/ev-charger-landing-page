<?php
/**
 * Các loại nội dung: Trạm sạc, Dự án, Đối tác, Ban lãnh đạo, Hãng xe, Yêu cầu tư vấn.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	$base = array(
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => false, // dùng trình soạn thảo cổ điển cho gọn
		'publicly_queryable' => false,
		'exclude_from_search'=> true,
		'has_archive'        => false,
		'rewrite'            => false,
		'supports'           => array( 'title', 'page-attributes' ),
	);
	$types = array(
		'tram_sac' => array( 'Trạm sạc', 'Trạm sạc', 'dashicons-location-alt', 5, array( 'title', 'page-attributes' ) ),
		'du_an'    => array( 'Dự án', 'Dự án', 'dashicons-building', 6, array( 'title', 'editor', 'thumbnail', 'page-attributes' ) ),
		'doi_tac'  => array( 'Đối tác', 'Đối tác', 'dashicons-groups', 7, array( 'title', 'editor', 'thumbnail', 'page-attributes' ) ),
		'lanh_dao' => array( 'Ban lãnh đạo', 'Thành viên', 'dashicons-businessperson', 8, array( 'title', 'editor', 'thumbnail', 'page-attributes' ) ),
		'hang_xe'  => array( 'Hãng xe', 'Hãng xe', 'dashicons-car', 9, array( 'title', 'thumbnail', 'page-attributes' ) ),
	);
	foreach ( $types as $slug => $t ) {
		register_post_type( $slug, array_merge( $base, array(
			'labels'        => s24_labels( $t[0], $t[1] ),
			'menu_icon'     => $t[2],
			'menu_position' => $t[3],
			'supports'      => $t[4],
		) ) );
	}

	register_post_type( 'lien_he', array_merge( $base, array(
		'labels'        => s24_labels( 'Yêu cầu tư vấn', 'Yêu cầu' ),
		'menu_icon'     => 'dashicons-email-alt',
		'menu_position' => 4,
		'supports'      => array( 'title' ),
		'capability_type' => 'post',
		'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'  => true,
	) ) );
} );

function s24_labels( $plural, $single ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $single,
		'menu_name'          => $plural,
		'add_new'            => 'Thêm mới',
		'add_new_item'       => 'Thêm ' . mb_strtolower( $single ),
		'edit_item'          => 'Sửa ' . mb_strtolower( $single ),
		'new_item'           => $single . ' mới',
		'all_items'          => 'Tất cả',
		'search_items'       => 'Tìm ' . mb_strtolower( $single ),
		'not_found'          => 'Chưa có mục nào',
		'not_found_in_trash' => 'Thùng rác trống',
	);
}

/* Đổi chữ gợi ý ô tiêu đề cho dễ hiểu */
add_filter( 'enter_title_here', function ( $text, $post ) {
	$map = array(
		'tram_sac' => 'Tên trạm sạc, ví dụ: Trạm sạc nhanh 120kW Thạch Đà',
		'du_an'    => 'Tên dự án',
		'doi_tac'  => 'Tiêu đề hợp tác',
		'lanh_dao' => 'Họ và tên',
		'hang_xe'  => 'Tên hãng xe',
	);
	return $map[ $post->post_type ] ?? $text;
}, 10, 2 );

/* ---------- Ô nhập thông tin riêng (meta box) ---------- */
function s24_fields() {
	return array(
		'tram_sac' => array(
			'dia_chi'    => array( 'Địa chỉ', 'text', '76/83 đường số 59, phường An Hội Tây, Hồ Chí Minh' ),
			'tinh'       => array( 'Tỉnh/Thành phố', 'text', 'TP. Hồ Chí Minh' ),
			'quan'       => array( 'Quận/Huyện', 'text', 'Gò Vấp' ),
			'toa_do'     => array( 'Tọa độ (vĩ độ, kinh độ)', 'text', '10.8452, 106.6553', 'Mở Google Maps → nhấp chuột phải vào đúng vị trí trạm → bấm vào dòng số đầu tiên để sao chép → dán vào đây.' ),
			'danh_gia'   => array( 'Điểm đánh giá (1–5)', 'number', '5.0' ),
			'cong_trong' => array( 'Số cổng sạc đang trống', 'number', '2' ),
			'tong_cong'  => array( 'Tổng số cổng sạc', 'number', '2' ),
		),
		'du_an' => array(
			'dia_diem' => array( 'Địa điểm', 'text', 'Hà Nội' ),
			'nhom'     => array( 'Nhóm dự án', 'select', '', '', array( 'tm' => 'Tab 1: Thương mại & Công nghiệp', 'ch' => 'Tab 2: Căn hộ cao cấp & Nhà riêng' ) ),
		),
		'doi_tac' => array(
			'nut_text' => array( 'Chữ trên nút', 'text', 'Đọc bài viết' ),
			'nut_link' => array( 'Link bài viết', 'url', 'https://' ),
		),
		'lanh_dao' => array(
			'chuc_vu' => array( 'Chức vụ', 'text', 'Tổng Giám đốc' ),
		),
		'lien_he' => array(
			'sdt'   => array( 'Số điện thoại', 'text', '' ),
			'email' => array( 'Email', 'text', '' ),
			'noi_dung' => array( 'Nội dung', 'textarea', '' ),
		),
	);
}

add_action( 'add_meta_boxes', function () {
	foreach ( s24_fields() as $type => $fields ) {
		add_meta_box( 's24_meta', 'Thông tin', 's24_render_meta', $type, 'normal', 'high' );
	}
} );

function s24_render_meta( $post ) {
	$fields = s24_fields()[ $post->post_type ] ?? array();
	wp_nonce_field( 's24_meta', 's24_meta_nonce' );
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $fields as $key => $f ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<tr><th scope="row"><label for="s24_' . esc_attr( $key ) . '">' . esc_html( $f[0] ) . '</label></th><td>';
		if ( 'select' === $f[1] ) {
			echo '<select id="s24_' . esc_attr( $key ) . '" name="s24[' . esc_attr( $key ) . ']">';
			foreach ( $f[4] as $k => $label ) {
				echo '<option value="' . esc_attr( $k ) . '"' . selected( $val, $k, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		} elseif ( 'textarea' === $f[1] ) {
			echo '<textarea id="s24_' . esc_attr( $key ) . '" name="s24[' . esc_attr( $key ) . ']" rows="5" class="large-text">' . esc_textarea( $val ) . '</textarea>';
		} else {
			$step = 'number' === $f[1] ? ' step="any" min="0"' : '';
			echo '<input type="' . esc_attr( $f[1] ) . '"' . $step . ' id="s24_' . esc_attr( $key ) . '" name="s24[' . esc_attr( $key ) . ']" value="' . esc_attr( $val ) . '" placeholder="' . esc_attr( $f[2] ) . '" class="regular-text">';
		}
		if ( ! empty( $f[3] ) ) { echo '<p class="description">' . esc_html( $f[3] ) . '</p>'; }
		if ( 'toa_do' === $key && $val && s24_parse_coords( $val ) ) {
			$c = s24_parse_coords( $val );
			echo '<p><a href="https://www.google.com/maps?q=' . esc_attr( $c[0] . ',' . $c[1] ) . '" target="_blank" rel="noopener">Kiểm tra vị trí trên Google Maps ↗</a></p>';
		} elseif ( 'toa_do' === $key && $val ) {
			echo '<p style="color:#b32d2e"><b>Tọa độ chưa đúng định dạng</b> – trạm này sẽ chưa hiện trên bản đồ.</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
	if ( in_array( $post->post_type, array( 'du_an', 'doi_tac', 'lanh_dao', 'hang_xe' ), true ) ) {
		echo '<p class="description">Ảnh: dùng hộp <b>Ảnh đại diện</b> ở cột bên phải. Thứ tự hiển thị: ô <b>Thứ tự</b> trong hộp Thuộc tính (số nhỏ hiện trước).</p>';
	}
	if ( 'tram_sac' === $post->post_type ) {
		echo '<p class="description">Thứ tự trong danh sách: ô <b>Thứ tự</b> trong hộp Thuộc tính (số nhỏ hiện trước).</p>';
	}
}

add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['s24_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['s24_meta_nonce'] ), 's24_meta' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	$type   = get_post_type( $post_id );
	$fields = s24_fields()[ $type ] ?? array();
	$in     = isset( $_POST['s24'] ) && is_array( $_POST['s24'] ) ? wp_unslash( $_POST['s24'] ) : array();
	foreach ( $fields as $key => $f ) {
		if ( ! array_key_exists( $key, $in ) ) { continue; }
		$v = $in[ $key ];
		if ( 'url' === $f[1] ) { $v = esc_url_raw( $v ); }
		elseif ( 'textarea' === $f[1] ) { $v = sanitize_textarea_field( $v ); }
		else { $v = sanitize_text_field( $v ); }
		update_post_meta( $post_id, $key, $v );
	}
} );

/* ---------- Cột trong danh sách quản trị ---------- */
add_filter( 'manage_tram_sac_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'title' => 'Tên trạm', 's24_tinh' => 'Tỉnh/Thành', 's24_quan' => 'Quận/Huyện', 's24_cong' => 'Cổng trống', 's24_map' => 'Bản đồ' );
} );
add_action( 'manage_tram_sac_posts_custom_column', function ( $col, $id ) {
	if ( 's24_tinh' === $col ) { echo esc_html( get_post_meta( $id, 'tinh', true ) ); }
	if ( 's24_quan' === $col ) { echo esc_html( get_post_meta( $id, 'quan', true ) ); }
	if ( 's24_cong' === $col ) { echo esc_html( (int) get_post_meta( $id, 'cong_trong', true ) . '/' . (int) get_post_meta( $id, 'tong_cong', true ) ); }
	if ( 's24_map' === $col ) {
		$c = s24_parse_coords( get_post_meta( $id, 'toa_do', true ) );
		echo $c ? '<a href="https://www.google.com/maps?q=' . esc_attr( $c[0] . ',' . $c[1] ) . '" target="_blank" rel="noopener">Xem ↗</a>' : '<span style="color:#b32d2e">Thiếu tọa độ</span>';
	}
}, 10, 2 );

add_filter( 'manage_du_an_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'title' => 'Dự án', 's24_dd' => 'Địa điểm', 's24_nhom' => 'Nhóm', 's24_img' => 'Ảnh' );
} );
add_action( 'manage_du_an_posts_custom_column', function ( $col, $id ) {
	if ( 's24_dd' === $col ) { echo esc_html( get_post_meta( $id, 'dia_diem', true ) ); }
	if ( 's24_nhom' === $col ) { echo 'ch' === get_post_meta( $id, 'nhom', true ) ? 'Căn hộ & Nhà riêng' : 'Thương mại & Công nghiệp'; }
	if ( 's24_img' === $col ) { echo has_post_thumbnail( $id ) ? get_the_post_thumbnail( $id, array( 60, 60 ) ) : '—'; }
}, 10, 2 );

add_filter( 'manage_lien_he_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'title' => 'Họ tên', 's24_sdt' => 'Số điện thoại', 's24_email' => 'Email', 's24_msg' => 'Nội dung', 'date' => 'Ngày gửi' );
} );
add_action( 'manage_lien_he_posts_custom_column', function ( $col, $id ) {
	if ( 's24_sdt' === $col ) { echo esc_html( get_post_meta( $id, 'sdt', true ) ); }
	if ( 's24_email' === $col ) { echo esc_html( get_post_meta( $id, 'email', true ) ); }
	if ( 's24_msg' === $col ) { echo esc_html( wp_trim_words( get_post_meta( $id, 'noi_dung', true ), 20 ) ); }
}, 10, 2 );

/* Danh sách quản trị sắp theo Thứ tự */
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() || ! $q->is_main_query() ) { return; }
	$t = $q->get( 'post_type' );
	if ( in_array( $t, array( 'tram_sac', 'du_an', 'doi_tac', 'lanh_dao', 'hang_xe' ), true ) && ! $q->get( 'orderby' ) ) {
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'ASC' ) );
	}
} );
