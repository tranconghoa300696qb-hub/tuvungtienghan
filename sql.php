parent_id 0: từ vựng: 37, giải đề:38, nghe:39, đặt câu: 40, ngữ pháp: id 47  ||||||||| danh mục gốc : id: 0: từ vựng,giải đề, nghe, đặt câu
// /////////////////// TU VUNG /////////////////////////////////////
INSERT INTO `categories` (`title`,`parent_id`,`type`,`slug`) VALUES ('EPS TOPIK BÀI 36: 출하 관리 | Quản lý xuất hàng','37','từ vựng','tu-vung');
INSERT INTO `vocabulary` (`category_id`, `ko`, `vi`, `img`) VALUES 
-- Ảnh 1: Packaging and Loading Work (포장 및 적재 작업 관련 어휘)
(51, '싸다/포장하다', 'Đóng gói/Bao bọc', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332474/xw6kq1oz7fefybxm1zxv.png'),
(51, '담다/넣다', 'Bỏ vào/Chứa đựng', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332475/ilohmbknhjj7s1os8whg.png'),
(51, '분류하다', 'Phân loại', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332473/d6fhmkxuhmgc555v9cfj.png'),
(51, '묶다', 'Buộc/Cột', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332473/bwbxesij9nggr4dokkja.png'),
(51, '밀봉하다', 'Niêm phong/Đóng kín', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332472/bg8dzldzfdm5gtu4j3hb.png'),
(51, '라벨을 붙이다', 'Dán nhãn', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332472/q61zlgkfdcvzzmsspriw.png'),
(51, '나르다', 'Vận chuyển/Bưng bê', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332472/szt5bkvvyxsrmrzrftde.png'),
(51, '쌓다', 'Chất đống/Xếp chồng', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332473/bqurepsqoobhhpgnvjnl.png'),
(51, '포장 기준서', 'Bản hướng dẫn đóng gói', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332485/xoqpbs8osnbrqyksr9b4.png'),
(51, '완충재', 'Vật liệu đệm/Chống sốc', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332474/kk7hma0xshyueugt4hgm.png'),
(51, '방청지', 'Giấy chống gỉ', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332473/ovmfkv2xrzn1zfjwyf6d.png'),
(51, '파렛트(팰릿)', 'Pa-lét', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332486/ikrs4ablyuvkchye4q3x.png'),

-- Từ vựng bổ sung ảnh 1
(51, '깨지다', 'Bị vỡ', ''),
(51, '녹슬다', 'Bị gỉ sét', ''),
(51, '업체', 'Công ty/Doanh nghiệp', ''),
(51, '지시하다', 'Chỉ thị/Hướng dẫn', ''),
(51, '갖다 놓다', 'Mang đến đặt vào', ''),
(51, '꼼꼼하다', 'Tỉ mỉ/Cẩn thận', ''),
(51, '운송하다', 'Vận tải/Chuyên chở', ''),
(51, '손상되다', 'Bị hư hỏng/Thiệt hại', ''),
(51, '신경을 쓰다', 'Để tâm/Chú ý', ''),
(51, '크기', 'Kích thước', ''),
(51, '별', 'Theo từng (loại)', ''),

-- Ảnh 2: Shipment Management (출하 관리 관련 어휘)
(51, '입고하다', 'Nhập kho', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332474/hnzamxjxxv0lvh3xbtf3.png'),
(51, '출고하다', 'Xuất kho', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332474/rxcvc1a4jneylos2liij.png'),
(51, '검수하다', 'Kiểm hàng', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332472/k2kgtp1cgsy0exhyqh7j.png'),
(51, '운반하다', 'Vận chuyển', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332474/gwrkx7gqrvi9549vfiru.png'),
(51, '싣다/적재하다', 'Chất hàng/Xếp lên xe', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332473/mobzm1u25c9hgwlhumoh.png'),
(51, '내리다', 'Dỡ hàng/Xuống hàng', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332472/xauxuebpqrxrpcyznrjb.png'),
(51, '출하하다', 'Gửi hàng đi', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332484/g3lrquj1yvdgkxqytzxw.png'),
(51, '납품하다', 'Giao hàng (cho khách)', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332472/hqo5qmfpifk5ilzok54q.png'),
(51, '지게차', 'Xe nâng', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332474/wmfnkj98vaha224irmnn.png'),
(51, '핸드 파렛트', 'Xe nâng tay', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332485/kngdey03leiav3b3ckyk.png'),
(51, '핸드 카트', 'Xe đẩy tay', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332485/kqrjowvuohweqlbflkib.png'),
(51, '대차', 'Xe đẩy hàng', 'https://res.cloudinary.com/dyavurhxs/image/upload/v1772332472/sooubpebxemxivbby2zr.png'),

-- Từ vựng bổ sung ảnh 2
(51, '출하량', 'Lượng hàng xuất', ''),
(51, '수량', 'Số lượng', ''),
(51, '빠짐없이', 'Không sai sót/Đầy đủ', ''),
(51, '수고하다', 'Nỗ lực/Vất vả làm việc', ''),
(51, '맞추다', 'Điều chỉnh/Làm cho khớp', ''),
(51, '떨어지다', 'Rơi/Rớt', ''),
(51, '밧줄', 'Dây thừng', ''),
(51, '중량', 'Trọng lượng', ''),
(51, '주의하다', 'Chú ý/Cẩn trọng', '');



