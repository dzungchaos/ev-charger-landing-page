/* =========================================================
   DỮ LIỆU TRẠM SẠC — Sacnhanh24h
   Đây là dữ liệu MẪU. Thay bằng danh sách trạm thật của bạn.
   Thứ tự mỗi dòng:
   [tên trạm, địa chỉ, tỉnh/thành, quận/huyện, vĩ độ, kinh độ,
    điểm đánh giá, số cổng đang trống, tổng số cổng]
   Lấy vĩ độ/kinh độ: mở Google Maps → nhấp phải vào vị trí → bấm dòng toạ độ để sao chép.
   ========================================================= */
window.STATIONS = [
 ["Trạm sạc nhanh 120kW Thạch Đà","76/83 đường số 59, phường An Hội Tây, Hồ Chí Minh","TP. Hồ Chí Minh","Gò Vấp",10.8452,106.6553,5.0,2,2],
 ["Trạm sạc Phan Văn Trị","12 Phan Văn Trị, phường 7, Gò Vấp","TP. Hồ Chí Minh","Gò Vấp",10.8274,106.6893,4.8,3,4],
 ["Trạm sạc Lê Đức Thọ","345 Lê Đức Thọ, phường 17, Gò Vấp","TP. Hồ Chí Minh","Gò Vấp",10.8486,106.6703,4.6,1,2],
 ["Trạm sạc Điện Biên Phủ","720 Điện Biên Phủ, phường 22, Bình Thạnh","TP. Hồ Chí Minh","Bình Thạnh",10.7948,106.7219,4.9,4,6],
 ["Trạm sạc Hàng Xanh","18 Xô Viết Nghệ Tĩnh, phường 19, Bình Thạnh","TP. Hồ Chí Minh","Bình Thạnh",10.8012,106.7108,4.5,0,2],
 ["Trạm sạc Tân Định","31 Nguyễn Văn Thủ, phường Tân Định, Quận 1","TP. Hồ Chí Minh","Quận 1",10.7891,106.6958,4.7,2,2],
 ["Trạm sạc Bến Thành","135 Lê Thánh Tôn, phường Bến Thành, Quận 1","TP. Hồ Chí Minh","Quận 1",10.7745,106.7006,4.4,1,4],
 ["Trạm sạc Thảo Điền","25 Quốc Hương, phường Thảo Điền, TP. Thủ Đức","TP. Hồ Chí Minh","Thủ Đức",10.8037,106.7365,5.0,2,2],
 ["Trạm sạc An Phú","360 Giồng Ông Tố, phường An Phú, TP. Thủ Đức","TP. Hồ Chí Minh","Thủ Đức",10.7959,106.7552,4.8,2,2],
 ["Trạm sạc Xa lộ Hà Nội","210 Xa lộ Hà Nội, phường Phước Long A, TP. Thủ Đức","TP. Hồ Chí Minh","Thủ Đức",10.8231,106.7618,4.6,3,4],
 ["Trạm sạc Tôn Thất Thuyết","46 Tôn Thất Thuyết, phường 15, Quận 4","TP. Hồ Chí Minh","Quận 4",10.7605,106.7069,4.7,1,2],
 ["Trạm sạc Phú Mỹ Hưng","105 Tôn Dật Tiên, phường Tân Phong, Quận 7","TP. Hồ Chí Minh","Quận 7",10.7295,106.7186,4.9,4,4],
 ["Trạm sạc Nguyễn Thị Thập","469 Nguyễn Thị Thập, phường Tân Phong, Quận 7","TP. Hồ Chí Minh","Quận 7",10.7383,106.7067,4.3,0,2],
 ["Trạm sạc Cộng Hòa","364 Cộng Hòa, phường 13, Tân Bình","TP. Hồ Chí Minh","Tân Bình",10.8015,106.6467,4.6,2,4],
 ["Trạm sạc Trường Sơn","58 Trường Sơn, phường 2, Tân Bình","TP. Hồ Chí Minh","Tân Bình",10.8132,106.6656,4.8,5,6],
 ["Trạm sạc Phạm Văn Đồng","1 Phạm Văn Đồng, phường Linh Tây, TP. Thủ Đức","TP. Hồ Chí Minh","Thủ Đức",10.8495,106.7432,4.5,2,2],
 ["Trạm sạc Dương Nội","Lê Trọng Tấn, phường Dương Nội, Hà Đông","Hà Nội","Hà Đông",20.9817,105.7552,5.0,2,2],
 ["Trạm sạc Hai Bà Trưng","49 Hai Bà Trưng, phường Trần Hưng Đạo, Hoàn Kiếm","Hà Nội","Hoàn Kiếm",21.0265,105.8467,4.9,2,2],
 ["Trạm sạc Trần Đại Nghĩa","190 Trần Đại Nghĩa, phường Tương Mai, Hai Bà Trưng","Hà Nội","Hai Bà Trưng",20.9968,105.8455,4.7,1,2],
 ["Trạm sạc Minh Khai","458 Minh Khai, phường Vĩnh Tuy, Hai Bà Trưng","Hà Nội","Hai Bà Trưng",20.9953,105.8681,4.6,3,4],
 ["Trạm sạc Nguyễn Văn Linh","7 Nguyễn Văn Linh, phường Gia Thụy, Long Biên","Hà Nội","Long Biên",21.0466,105.9011,4.8,2,2],
 ["Trạm sạc Nguyễn Trãi","72A Nguyễn Trãi, phường Thượng Đình, Thanh Xuân","Hà Nội","Thanh Xuân",21.0029,105.8158,4.5,0,4],
 ["Trạm sạc Phạm Hùng","Phạm Hùng, phường Mễ Trì, Nam Từ Liêm","Hà Nội","Nam Từ Liêm",21.0168,105.7839,4.7,2,2],
 ["Trạm sạc Xuân Thủy","241 Xuân Thủy, phường Dịch Vọng Hậu, Cầu Giấy","Hà Nội","Cầu Giấy",21.0369,105.7834,4.4,1,2],
 ["Trạm sạc Quảng An","1 Đặng Thai Mai, phường Quảng An, Tây Hồ","Hà Nội","Tây Hồ",21.0645,105.8256,4.9,2,2],
 ["Trạm sạc Ba Vì","Quốc lộ 32, thị trấn Tây Đằng, Ba Vì","Hà Nội","Ba Vì",21.2028,105.4243,4.6,2,2],
 ["Trạm sạc Thủ Dầu Một","Đại lộ Bình Dương, phường Hòa Phú, Thủ Dầu Một","Bình Dương","Thủ Dầu Một",11.0532,106.6927,4.8,3,4],
 ["Trạm sạc Thuận An","Đại lộ Bình Dương, phường Lái Thiêu, Thuận An","Bình Dương","Thuận An",10.9118,106.7038,4.5,1,2],
 ["Trạm sạc Dĩ An","Quốc lộ 1K, phường Đông Hòa, Dĩ An","Bình Dương","Dĩ An",10.8898,106.7756,4.6,2,2],
 ["Trạm sạc Long Thành","KCN Long Thành, xã Tam An, Long Thành","Đồng Nai","Long Thành",10.8075,106.9325,4.7,2,2],
 ["Trạm sạc Biên Hòa","1096 Phạm Văn Thuận, phường Tân Mai, Biên Hòa","Đồng Nai","Biên Hòa",10.9562,106.8436,4.4,0,2],
 ["Trạm sạc Phú Mỹ","KCN Mỹ Xuân A, Phú Mỹ","Bà Rịa - Vũng Tàu","Phú Mỹ",10.6098,107.0396,4.8,2,2],
 ["Trạm sạc Bãi Sau","Thùy Vân, phường Thắng Tam, Vũng Tàu","Bà Rịa - Vũng Tàu","Vũng Tàu",10.3406,107.0956,4.6,1,2],
 ["Trạm sạc Mỹ Khê","Võ Nguyên Giáp, phường Phước Mỹ, Sơn Trà","Đà Nẵng","Sơn Trà",16.0627,108.2468,4.9,3,4],
 ["Trạm sạc Hải Châu","255 Hùng Vương, phường Vĩnh Trung, Hải Châu","Đà Nẵng","Hải Châu",16.0663,108.2109,4.5,1,2],
 ["Trạm sạc Nha Trang","50 Trần Phú, phường Lộc Thọ, Nha Trang","Khánh Hòa","Nha Trang",12.2388,109.1967,4.7,2,2],
 ["Trạm sạc Cam Ranh","Quốc lộ 1A, phường Cam Nghĩa, Cam Ranh","Khánh Hòa","Cam Ranh",11.9585,109.1617,4.6,2,2],
 ["Trạm sạc Bãi Cháy","Đường Hạ Long, phường Bãi Cháy, Hạ Long","Quảng Ninh","Hạ Long",20.9532,107.0485,4.8,1,2],
 ["Trạm sạc Ninh Kiều","2 Hai Bà Trưng, phường Tân An, Ninh Kiều","Cần Thơ","Ninh Kiều",10.0341,105.7884,4.5,2,2],
 ["Trạm sạc Lê Hồng Phong","10 Lê Hồng Phong, phường Đông Khê, Ngô Quyền","Hải Phòng","Ngô Quyền",20.8462,106.6944,4.7,2,4]
].map((s,i)=>({id:i,name:s[0],address:s[1],province:s[2],district:s[3],lat:s[4],lng:s[5],rating:s[6],free:s[7],total:s[8]}));
