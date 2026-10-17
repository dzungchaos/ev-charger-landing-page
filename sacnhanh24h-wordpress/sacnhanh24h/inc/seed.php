<?php
/**
 * Nội dung mẫu: tạo một lần khi kích hoạt theme, để website hiện đầy đủ ngay.
 * Sau đó bạn sửa / xoá trong trang quản trị.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'after_switch_theme', 's24_seed_content' );

function s24_seed_content() {
	if ( get_option( 's24_seeded' ) ) { return; }
	update_option( 's24_seeded', 1 );

	/* Trạm sạc: tên, địa chỉ, tỉnh, quận, tọa độ, đánh giá, cổng trống, tổng cổng */
	$stations = array(
		array( 'Trạm sạc nhanh 120kW Thạch Đà', '76/83 đường số 59, phường An Hội Tây, Hồ Chí Minh', 'TP. Hồ Chí Minh', 'Gò Vấp', '10.8452, 106.6553', '5.0', '2', '2' ),
		array( 'Trạm sạc Phan Văn Trị', '12 Phan Văn Trị, phường 7, Gò Vấp', 'TP. Hồ Chí Minh', 'Gò Vấp', '10.8274, 106.6893', '4.8', '3', '4' ),
		array( 'Trạm sạc Lê Đức Thọ', '345 Lê Đức Thọ, phường 17, Gò Vấp', 'TP. Hồ Chí Minh', 'Gò Vấp', '10.8486, 106.6703', '4.6', '1', '2' ),
		array( 'Trạm sạc Điện Biên Phủ', '720 Điện Biên Phủ, phường 22, Bình Thạnh', 'TP. Hồ Chí Minh', 'Bình Thạnh', '10.7948, 106.7219', '4.9', '4', '6' ),
		array( 'Trạm sạc Hàng Xanh', '18 Xô Viết Nghệ Tĩnh, phường 19, Bình Thạnh', 'TP. Hồ Chí Minh', 'Bình Thạnh', '10.8012, 106.7108', '4.5', '0', '2' ),
		array( 'Trạm sạc Tân Định', '31 Nguyễn Văn Thủ, phường Tân Định, Quận 1', 'TP. Hồ Chí Minh', 'Quận 1', '10.7891, 106.6958', '4.7', '2', '2' ),
		array( 'Trạm sạc Bến Thành', '135 Lê Thánh Tôn, phường Bến Thành, Quận 1', 'TP. Hồ Chí Minh', 'Quận 1', '10.7745, 106.7006', '4.4', '1', '4' ),
		array( 'Trạm sạc Thảo Điền', '25 Quốc Hương, phường Thảo Điền, TP. Thủ Đức', 'TP. Hồ Chí Minh', 'Thủ Đức', '10.8037, 106.7365', '5.0', '2', '2' ),
		array( 'Trạm sạc An Phú', '360 Giồng Ông Tố, phường An Phú, TP. Thủ Đức', 'TP. Hồ Chí Minh', 'Thủ Đức', '10.7959, 106.7552', '4.8', '2', '2' ),
		array( 'Trạm sạc Xa lộ Hà Nội', '210 Xa lộ Hà Nội, phường Phước Long A, TP. Thủ Đức', 'TP. Hồ Chí Minh', 'Thủ Đức', '10.8231, 106.7618', '4.6', '3', '4' ),
		array( 'Trạm sạc Tôn Thất Thuyết', '46 Tôn Thất Thuyết, phường 15, Quận 4', 'TP. Hồ Chí Minh', 'Quận 4', '10.7605, 106.7069', '4.7', '1', '2' ),
		array( 'Trạm sạc Phú Mỹ Hưng', '105 Tôn Dật Tiên, phường Tân Phong, Quận 7', 'TP. Hồ Chí Minh', 'Quận 7', '10.7295, 106.7186', '4.9', '4', '4' ),
		array( 'Trạm sạc Nguyễn Thị Thập', '469 Nguyễn Thị Thập, phường Tân Phong, Quận 7', 'TP. Hồ Chí Minh', 'Quận 7', '10.7383, 106.7067', '4.3', '0', '2' ),
		array( 'Trạm sạc Cộng Hòa', '364 Cộng Hòa, phường 13, Tân Bình', 'TP. Hồ Chí Minh', 'Tân Bình', '10.8015, 106.6467', '4.6', '2', '4' ),
		array( 'Trạm sạc Trường Sơn', '58 Trường Sơn, phường 2, Tân Bình', 'TP. Hồ Chí Minh', 'Tân Bình', '10.8132, 106.6656', '4.8', '5', '6' ),
		array( 'Trạm sạc Phạm Văn Đồng', '1 Phạm Văn Đồng, phường Linh Tây, TP. Thủ Đức', 'TP. Hồ Chí Minh', 'Thủ Đức', '10.8495, 106.7432', '4.5', '2', '2' ),
		array( 'Trạm sạc Dương Nội', 'Lê Trọng Tấn, phường Dương Nội, Hà Đông', 'Hà Nội', 'Hà Đông', '20.9817, 105.7552', '5.0', '2', '2' ),
		array( 'Trạm sạc Hai Bà Trưng', '49 Hai Bà Trưng, phường Trần Hưng Đạo, Hoàn Kiếm', 'Hà Nội', 'Hoàn Kiếm', '21.0265, 105.8467', '4.9', '2', '2' ),
		array( 'Trạm sạc Trần Đại Nghĩa', '190 Trần Đại Nghĩa, phường Tương Mai, Hai Bà Trưng', 'Hà Nội', 'Hai Bà Trưng', '20.9968, 105.8455', '4.7', '1', '2' ),
		array( 'Trạm sạc Minh Khai', '458 Minh Khai, phường Vĩnh Tuy, Hai Bà Trưng', 'Hà Nội', 'Hai Bà Trưng', '20.9953, 105.8681', '4.6', '3', '4' ),
		array( 'Trạm sạc Nguyễn Văn Linh', '7 Nguyễn Văn Linh, phường Gia Thụy, Long Biên', 'Hà Nội', 'Long Biên', '21.0466, 105.9011', '4.8', '2', '2' ),
		array( 'Trạm sạc Nguyễn Trãi', '72A Nguyễn Trãi, phường Thượng Đình, Thanh Xuân', 'Hà Nội', 'Thanh Xuân', '21.0029, 105.8158', '4.5', '0', '4' ),
		array( 'Trạm sạc Phạm Hùng', 'Phạm Hùng, phường Mễ Trì, Nam Từ Liêm', 'Hà Nội', 'Nam Từ Liêm', '21.0168, 105.7839', '4.7', '2', '2' ),
		array( 'Trạm sạc Xuân Thủy', '241 Xuân Thủy, phường Dịch Vọng Hậu, Cầu Giấy', 'Hà Nội', 'Cầu Giấy', '21.0369, 105.7834', '4.4', '1', '2' ),
		array( 'Trạm sạc Quảng An', '1 Đặng Thai Mai, phường Quảng An, Tây Hồ', 'Hà Nội', 'Tây Hồ', '21.0645, 105.8256', '4.9', '2', '2' ),
		array( 'Trạm sạc Ba Vì', 'Quốc lộ 32, thị trấn Tây Đằng, Ba Vì', 'Hà Nội', 'Ba Vì', '21.2028, 105.4243', '4.6', '2', '2' ),
		array( 'Trạm sạc Thủ Dầu Một', 'Đại lộ Bình Dương, phường Hòa Phú, Thủ Dầu Một', 'Bình Dương', 'Thủ Dầu Một', '11.0532, 106.6927', '4.8', '3', '4' ),
		array( 'Trạm sạc Thuận An', 'Đại lộ Bình Dương, phường Lái Thiêu, Thuận An', 'Bình Dương', 'Thuận An', '10.9118, 106.7038', '4.5', '1', '2' ),
		array( 'Trạm sạc Dĩ An', 'Quốc lộ 1K, phường Đông Hòa, Dĩ An', 'Bình Dương', 'Dĩ An', '10.8898, 106.7756', '4.6', '2', '2' ),
		array( 'Trạm sạc Long Thành', 'KCN Long Thành, xã Tam An, Long Thành', 'Đồng Nai', 'Long Thành', '10.8075, 106.9325', '4.7', '2', '2' ),
		array( 'Trạm sạc Biên Hòa', '1096 Phạm Văn Thuận, phường Tân Mai, Biên Hòa', 'Đồng Nai', 'Biên Hòa', '10.9562, 106.8436', '4.4', '0', '2' ),
		array( 'Trạm sạc Phú Mỹ', 'KCN Mỹ Xuân A, Phú Mỹ', 'Bà Rịa - Vũng Tàu', 'Phú Mỹ', '10.6098, 107.0396', '4.8', '2', '2' ),
		array( 'Trạm sạc Bãi Sau', 'Thùy Vân, phường Thắng Tam, Vũng Tàu', 'Bà Rịa - Vũng Tàu', 'Vũng Tàu', '10.3406, 107.0956', '4.6', '1', '2' ),
		array( 'Trạm sạc Mỹ Khê', 'Võ Nguyên Giáp, phường Phước Mỹ, Sơn Trà', 'Đà Nẵng', 'Sơn Trà', '16.0627, 108.2468', '4.9', '3', '4' ),
		array( 'Trạm sạc Hải Châu', '255 Hùng Vương, phường Vĩnh Trung, Hải Châu', 'Đà Nẵng', 'Hải Châu', '16.0663, 108.2109', '4.5', '1', '2' ),
		array( 'Trạm sạc Nha Trang', '50 Trần Phú, phường Lộc Thọ, Nha Trang', 'Khánh Hòa', 'Nha Trang', '12.2388, 109.1967', '4.7', '2', '2' ),
		array( 'Trạm sạc Cam Ranh', 'Quốc lộ 1A, phường Cam Nghĩa, Cam Ranh', 'Khánh Hòa', 'Cam Ranh', '11.9585, 109.1617', '4.6', '2', '2' ),
		array( 'Trạm sạc Bãi Cháy', 'Đường Hạ Long, phường Bãi Cháy, Hạ Long', 'Quảng Ninh', 'Hạ Long', '20.9532, 107.0485', '4.8', '1', '2' ),
		array( 'Trạm sạc Ninh Kiều', '2 Hai Bà Trưng, phường Tân An, Ninh Kiều', 'Cần Thơ', 'Ninh Kiều', '10.0341, 105.7884', '4.5', '2', '2' ),
		array( 'Trạm sạc Lê Hồng Phong', '10 Lê Hồng Phong, phường Đông Khê, Ngô Quyền', 'Hải Phòng', 'Ngô Quyền', '20.8462, 106.6944', '4.7', '2', '4' ),
	);
	foreach ( $stations as $i => $s ) {
		s24_seed_post( 'tram_sac', $s[0], '', $i, array(
			'dia_chi' => $s[1], 'tinh' => $s[2], 'quan' => $s[3], 'toa_do' => $s[4],
			'danh_gia' => $s[5], 'cong_trong' => $s[6], 'tong_cong' => $s[7],
		) );
	}

	$projects = array(
		array( 'Trung tâm thương mại', 'Hà Nội', 'tm', 'Trạm sạc nhanh DC 120kW phục vụ khách mua sắm và nhân viên tòa nhà.' ),
		array( 'Khu đô thị xanh', 'Bình Dương', 'tm', 'Hai trạm sạc DC công suất cao tại dự án phức hợp đa công năng.' ),
		array( 'Khách sạn trung tâm', 'Hà Nội', 'tm', 'Hai cổng sạc DC 60kW ngay khu phố cổ, thuận tiện cho khách lưu trú.' ),
		array( 'Nhà máy xanh', 'Đồng Nai', 'tm', 'Hệ thống sạc cho đội xe điện và nhân viên, góp phần đạt chứng nhận công trình xanh.' ),
		array( 'Khu công nghiệp', 'Bà Rịa - Vũng Tàu', 'tm', 'Bộ sạc hai cổng lắp tại bãi đỗ xe, vận hành 24/7.' ),
		array( 'Showroom ô tô', 'Hà Nội', 'tm', 'Trạm sạc phục vụ khách hàng lái thử và nhận xe điện.' ),
		array( 'Khu biệt thự ven sông', 'TP. Hồ Chí Minh', 'ch', 'Bộ sạc AC tại nhà, khảo sát, lắp đặt và bảo trì trọn gói.' ),
		array( 'Căn hộ cao cấp', 'TP. Hồ Chí Minh', 'ch', 'Trạm sạc tầng hầm cho cư dân, thanh toán qua ứng dụng.' ),
		array( 'Khu nhà phố', 'Hà Nội', 'ch', 'Hệ thống sạc chia sẻ, tính tiền điện theo từng hộ.' ),
	);
	foreach ( $projects as $i => $p ) {
		s24_seed_post( 'du_an', $p[0], $p[3], $i, array( 'dia_diem' => $p[1], 'nhom' => $p[2] ) );
	}

	$partners = array(
		array( 'Mạng lưới sạc dọc tuyến cao tốc Bắc – Nam', 'Triển khai chuỗi trạm sạc DC công suất cao dọc hành trình liên tỉnh, giúp các chuyến đi đường dài bằng xe điện trở nên liền mạch và yên tâm.' ),
		array( 'Hợp tác cùng hãng xe điện', 'Đồng hành cùng các hãng xe để chủ sở hữu xe điện có thể tiếp cận điểm sạc nhanh, thuận tiện ngay từ ngày nhận xe.' ),
		array( 'Chủ đầu tư bất động sản', 'Trang bị trạm sạc cho các dự án mới, giúp tòa nhà sẵn sàng cho xu hướng di chuyển xanh.' ),
	);
	foreach ( $partners as $i => $p ) {
		s24_seed_post( 'doi_tac', $p[0], $p[1], $i, array( 'nut_text' => 'Đọc bài viết', 'nut_link' => '' ) );
	}

	$leaders = array(
		array( '[Họ và tên]', 'Chủ tịch HĐQT' ),
		array( '[Họ và tên]', 'Tổng Giám đốc' ),
		array( '[Họ và tên]', 'Giám đốc Kỹ thuật' ),
	);
	foreach ( $leaders as $i => $l ) {
		s24_seed_post( 'lanh_dao', $l[0], 'Mô tả ngắn về kinh nghiệm và vai trò.', $i, array( 'chuc_vu' => $l[1] ) );
	}

	foreach ( array( 'VinFast', 'BYD', 'Tesla', 'Hyundai', 'Kia', 'Mercedes-Benz' ) as $i => $c ) {
		s24_seed_post( 'hang_xe', $c, '', $i, array() );
	}
}

function s24_seed_post( $type, $title, $content, $order, $meta ) {
	$id = wp_insert_post( array(
		'post_type'    => $type,
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_content' => $content,
		'menu_order'   => $order,
	) );
	if ( $id && ! is_wp_error( $id ) ) {
		foreach ( $meta as $k => $v ) { update_post_meta( $id, $k, $v ); }
	}
	return $id;
}
