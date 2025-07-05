<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LoadConfigHelper
{
    static $yheaders = array(
        'tý' => 'Người mang tuổi Tý rất duyên dáng và hấp dẫn người khác phái. Tuy nhiên, họ rất sợ ánh sáng và tiếng động. Người mang tuổi này rất tích cực và năng động nhưng họ cũng thường gặp lắm chuyện vặt vãnh. Người mang tuổi Tý cũng có mặt mạnh vì nếu Chuột xuất hiện có nghĩa là phải có lúa trong bồ.',
        'sửu' => 'Trâu tượng trưng cho sự siêng năng và lòng kiên nhẫn. Năm này có tiến triển vững vàng nhưng chậm và một sức mạnh bền bỉ; người mang tuổi Sửu thường có tính cách thích hợp để trở thành một nhà khoa học. Trâu biểu tượng cho mùa Xuân và nông nghiệp vì gắn liền với cái cày và thích đầm mình trong bùn. Người mang tuổi này thường điềm tĩnh và rất kiên định nhưng rất bướng bỉnh.',
        'dần' => 'Những người mang tuổi hổ thường rất dễ nổi giận, thiếu lập trường nhưng họ có thể rất mềm mỏng và xoay chuyển cá tính cho thích nghi với hoàn cảnh. Hổ là chúa tể rừng xanh, thường sống về đêm và gợi lên những hình ảnh về bóng đen và giông tố. Giờ Dần bắt đầu từ 3 giờ đến 5 giờ khi cọp trở về hang sau khi đi rình mò trong đêm.',
        'mão' => 'Mèo tượng trưng cho những người ăn nói nhẹ nhàng, nhiều tài năng, nhiều tham vọng và sẽ thành công trên con đường học vấn. Họ rất xung khắc với người tuổi Tý. Người tuổi Mão có tinh thần mềm dẻo, tính kiên nhẫn và biết chờ thời cơ trước khi hành động.',
        'thìn' => 'Con rồng trong huyền thoại của người phương Đông là tính Dương của vũ trụ, biểu tượng uy quyền hoàng gia. Theo đó, rồng hiện diện ở khắp mọi nơi, dưới nước, trên mặt đất và không trung. Rồng là biểu tượng của nước và là dấu hiệu thuận lợi cho nông nghiệp. Người tuổi Rồng rất trung thực, năng nổ nhưng rất nóng tính và bướng bỉnh. Họ là biểu tượng của quyền lực, sự giàu có, thịnh vượng và của hoàng tộc.',
        'tỵ' => 'Người tuổi rắn nói ít nhưng rất thông thái. Họ thích hợp với vùng đất ẩm ướt. Rắn tượng trưng cho sự tiến hóa vĩnh cửu của tuổi tác và sự kế vị, sự phân hủy và sự nối tiếp các thế hệ của nhân loại. Người tuổi rắn rất điềm tĩnh, hiền lành, sâu sắc và cảm thông nhưng thỉnh thoảng cũng hay nổi giận. Họ rất kiên quyết và cố chấp.',
        'ngọ' => 'Người tuổi Ngọ thường ăn nói dịu dàng, thoải mái và rộng lượng. Do đó, họ dễ được nhiều người mến chuộng nhưng họ ít khi nghe lời khuyên can. Người tuổi này thường có tính khí rất nóng nảy. Tốc độ chạy của ngựa làm người ta liên tưởng đến mặt trời rọi đến trái đất hàng ngày. Trong thần thoại, mặt trời được cho là liên quan đến những con ngựa đang nổi cơn cuồng nộ. Tuổi này thường được cho là có tính thanh sạch, cao quý và thông thái. Người tuổi này thường được quý trọng do thông minh, mạnh mẽ và đầy thân ái tình người.',
        'mùi' => 'Người mang tuổi Mùi thường rất điềm tĩnh nhưng nhút nhát, rất khiêm tốn nhưng không có lập trường. Họ ăn nói rất vụng về, vì thế họ không thể là người bán hàng giỏi nhưng họ rất cảm thương người hoạn nạn và thường hay giúp đỡ mọi người. Họ thường có lợi thế vì tính tốt bụng và nhút nhát tự nhiên của họ.',
        'thân' => 'Người tuổi Thân thường là một nhân tài có tính cách thất thường. Họ rất tài ba và khéo léo trong các vụ giao dịch tiền bạc. Người tuổi này thường rất vui vẻ, khéo tay, tò mò và nhiều sáng kiến, nhưng họ lại nói quá nhiều nên dễ bị người khác xem thường và khinh ghét. Khuyết điểm của họ nằm trong tính khí thất thường và không nhất quán.',
        'dậu' => 'Năm Dậu tượng trưng cho một giai đoạn hoạt động lao động cần cù siêng năng vì gà phải bận rộn từ sáng đến tối. Cái mào của nó là một dấu hiệu của sự cực kỳ thông minh và một trí tuệ bác học. Người sinh vào năm Dậu được xem là người có tư duy sâu sắc. Đồng thời, gà được coi là sự bảo vệ chống lại lửa. Người sinh vào năm Dậu thường kiếm sống nhờ kinh doanh nhỏ, làm ăn cần cù như một chú gà bới đất tìm sâu.',
        'tuất' => 'Năm Tuất cho biết một tương lai thịnh vượng. Trên khắp thế giới, chó được dùng để giữ nhà chống lại những kẻ xâm nhập. Những cặp chó đá thường được đặt hai bên cổng làng để bảo vệ. Năm Tuất được tin là năm rất an toàn.',
        'hợi' => 'Lợn tượng trưng cho sự giàu có vì loài lợn rừng thường làm hang trong những khu rừng. Người tuổi Hợi rất hào hiệp, galăng, tốt bụng và dũng cảm nhưng thường rất bướng bỉnh, nóng tính nhưng siêng năng và chịu lắng nghe.',
    );

    static $mheaders = array(
        1 => 'Tháng đầu tiên của năm, tháng mở đầu của mùa xuân. Loài hoa tượng trưng cho tháng 1 là hoa Cúc Trường Sinh. Ý nghĩa tháng 1 biểu tượng rằng bạn là người mạnh mẽ, có nghị lực vượt qua mọi khó khăn. Thời điểm của tháng 1 là bước giao mùa giữa mùa Đông và mùa Xuân. Tháng 1 là tháng in dấu với thời tiết mưa phùn, mưa xuân ẩm ướt kéo dãi đằng đẵng.',
        2 => 'Tháng của những lễ hội, thế gian vui chơi, sự chuyển mình rõ rệt nhất của mùa xuân. Cây cối đâm chồi nảy lộc, người người vui vẻ chơi xuân. Thời tiết tháng 2 cũng dễ chịu nhất, không có cái nắng gay gắt của hè, cũng không có cái lạnh cắt da cắt thịt của mùa đông, trời bắt đầu có nắng và tiết trời chỉ se se lạnh. Loài hòa tượng trưng cho tháng 2 là hoa trinh nữ. Ý nghĩa của tháng 2 thể hiện con người mẫn cảm, bạn rất nhạy cảm trước những lời nói của người khác. Trong công việc bạn luôn tận tụy hết lòng hết sức.',
        3 => 'Tháng kết thúc của mùa xuân, mọi người trở lại hăng say với công việc và cuộc sống hằng ngày. Với những bạn sinh vào tháng 3 thì loài hòa tượng trưng cho ý nghĩa của tháng 3 chính là hoa Bách Hợp. Thể hiến sự tinh tế, cũng như sức hấp dẫn kín đáo bí ẩn. Nhưng tính cách của người sinh vào tháng 3 cũng rất quyết liệt và khá độc đoán, quyết đoán.',
        4 => 'Mang ý nghĩa giống như loài hoa Mộc Lan. Những người sinh tháng 4 thể hiện bạn là người tham vọng, luôn muốn thể hiện mình là người nổi bật, trong nhiều trường hợp bạn nên thể hiện sự khiêm tốn của mình. Ý nghĩa của tháng 4 là tháng đại diện cho những nguyện vọng lâu dài, những loài hoa tháng 4 thường gợi cảm giác ưu phiền, tựa nỗi buồn ngây thơ.',
        5 => 'Được tượng trưng bởi loại hoa Lan Chuông một loại hoa cao quý. Hoa Lan Chuông thể hiện rằng bạn rất ngọt ngào và khá cầu toàn, tỉ mỉ. Ý nghĩa tháng 5 là cầu nối bước sang mùa hè, khi đất trời đã bắt đầu ngập tràn những ngày nắng. Đôi khi người sinh tháng 5 là kẻ cố chấp, nóng lòng hay đổi thay, rồi mọi chuyện cũng qua nhanh như những đêm ngắn ngủi của tháng 5.',
        6 => '"Ai mang cho tháng 6 nỗi buồn Hoa Phượng, để mùa hè đến lòng ai chợt ngẩn ngơ", tháng 6 luôn có những cuộc chia tay lớn và đầy những trang lưu bút được viết lên trên những cuốn sổ ủ đầy cánh hoa. Người sinh tháng 6 trong lòng rất lãng mạn, giàu tình cảm và cả ưu phiền. Loài hoa tượng trưng cho tháng 6 là hoa tuy - lip, nhưng nhiều người cho rằng, tháng 6 là mùa của loài hoa phượng, nở đỏ rực sân trường. Tạo nên ý nghĩa của tháng 6 là mùa của chia tay và nước mắt, một tháng đong đầy với những kỷ niệm khó quên của biết bao người.',
        7 => 'Loài hoa tượng trưng cho tháng 7 là hoa Phi Yến. Những bạn sinh vào tháng 7 thường là người hay mơ mộng, thích tượng tưởng, có khả năng suy xét vấn đề tốt. Bạn cách sống khá lạ mà nhiều người cho rằng lập dị. Ý nghĩa tháng 7 là điểm giữa của mùa hè, mặt trời lên cao và nắng chói chang trên mặt đất. Tháng 7 nước lên con lũ lớn, tháng 7 mưa rào không ngớt suốt ngày đêm. Bản tính người sinh tháng 7 cũng mạnh mẽ ngang tàn và cố chấp khi không ngăn kịp lúc.',
        8 => 'Tháng của hoa hồng, chúa của các loài hoa. Vẻ đẹp của hoa hồng được tượng trưng cho tình yêu, cho sự mãnh liệt. Ý nghĩa của tháng 8 là khoảng chớm thu có những ngày nắng dịu mát mẻ rất tuyệt, tháng 8 mang đến miền Bắc hình hài của miền Nam. Tháng 8 cũng là tháng sinh ra những người đặc biệt.',
        9 => 'Tháng mang ý nghĩa của hoa Cẩm Chướng. Thể hiện rằng bạn là con người bộc trực, hăng hái . Ý nghĩa của tháng 9 là tháng trở mình giữa mùa hạ và mua thu. Vẫn còn những ngày nắng gắt nhưng có chút man mát của mùa thu, của mùi hoa nở rộ. Tháng 9 ngập tràn những khám phá mới, những khởi đầu mới cho người ta nhiều cảm xúc.',
        10 => 'Ý nghĩa tháng 10 cũng giống như ý nghĩa của loài hoa tượng trưng cho tháng đó, chính là hoa Hải Đường. Những người sinh tháng 10 thường có một tâm hồn tinh tế, nhưng cũng rất dũng cảm và gan dạ. Tính cách của người sinh tháng 10 luôn tươi trẻ và đầy nhiệt huyết. Tháng 10 là tháng để thêu dệt ước mơ, vun đắp dự định và thực hiện những cuộc cải cách lớn, tháng 10 không chỉ lãng mạn mà còn ẩn chứa vô vàn bí ẩn khiến người đối diện thu hút mãi không thể rời mắt.',
        11 => 'Loài hoa tượng trưng cho ý nghĩa tháng 11 là hoa Lay Ơn. Những bạn sinh vào tháng 11 luôn có một vẻ bề ngoài hấp dấn người khác, bạn luôn bí ẩn và tinh tế, luôn thể hiện mình là người thông mình chịu khó. Tháng 11 cũng bắt đầu với cái rét đầu mùa, cái rét mong manh như tâm hồn luôn đi tìm 1 chỗ dựa. Mùa thu sẽ kết thúc trong tháng 11 với nhiều tiếc nuối ở lại để đón chào tháng 12 lạnh giá.',
        12 => 'Hoa Thủy tiên là loài hoa tượng trưng cho ý nghĩa tháng 12, cho một tháng cuối năm nhẹ nhành mà thanh thoát. Những người sinh vào tháng 12 thường thân thiện cởi mở, họ thường gặp được nhưng điều may mắn trong cuộc sống. Ý nghĩa của tháng 12 còn là điểm giữa mùa đông, vào 1 ngày cuối tháng 12 lạnh giá và nhợt nhạt. Mọi vận động bị cái lạnh kìm hãm, có thứ chết đi và có thứ vẫn gắng gượng chống chọi.',
    );

    static $ledl = array(
        array(
            'dd' => 1,
            'mm' => 1,
            'name' => 'Tết Dương lịch.'
        ),
        array(
            'dd' => 14,
            'mm' => 2,
            'name' => 'Lễ tình nhân (Valentine).'
        ),
        array(
            'dd' => 27,
            'mm' => 2,
            'name' => 'Ngày thầy thuốc Việt Nam.'
        ),
        array(
            'dd' => 8,
            'mm' => 3,
            'name' => 'Ngày Quốc tế Phụ nữ.'
        ),
        array(
            'dd' => 26,
            'mm' => 3,
            'name' => 'Ngày thành lập Đoàn TNCS Hồ Chí Minh.'
        ),
        array(
            'dd' => 1,
            'mm' => 4,
            'name' => 'Ngày Cá tháng Tư.'
        ),
        array(
            'dd' => 30,
            'mm' => 4,
            'name' => 'Ngày giải phóng miền Nam.'
        ),
        array(
            'dd' => 1,
            'mm' => 5,
            'name' => 'Ngày Quốc tế Lao động.'
        ),
        array(
            'dd' => 7,
            'mm' => 5,
            'name' => 'Ngày chiến thắng Điện Biên Phủ.'
        ),
        array(
            'dd' => 13,
            'mm' => 5,
            'name' => 'Ngày của mẹ.'
        ),
        array(
            'dd' => 19,
            'mm' => 5,
            'name' => 'Ngày sinh chủ tịch Hồ Chí Minh.'
        ),
        array(
            'dd' => 1,
            'mm' => 6,
            'name' => 'Ngày Quốc tế thiếu nhi.'
        ),
        array(
            'dd' => 17,
            'mm' => 6,
            'name' => 'Ngày của cha.'
        ),
        array(
            'dd' => 21,
            'mm' => 6,
            'name' => 'Ngày báo chí Việt Nam.'
        ),
        array(
            'dd' => 28,
            'mm' => 6,
            'name' => 'Ngày gia đình Việt Nam.'
        ),
        array(
            'dd' => 11,
            'mm' => 7,
            'name' => 'Ngày dân số thế giới.'//
        ),
        array(
            'dd' => 27,
            'mm' => 7,
            'name' => 'Ngày Thương binh liệt sĩ.'
        ),
        array(
            'dd' => 28,
            'mm' => 7,
            'name' => 'Ngày thành lập công đoàn Việt Nam.'
        ),
        array(
            'dd' => 19,
            'mm' => 8,
            'name' => 'Ngày tổng khởi nghĩa.'
        ),
        array(
            'dd' => 2,
            'mm' => 9,
            'name' => 'Ngày Quốc Khánh.'
        ),
        array(
            'dd' => 10,
            'mm' => 9,
            'name' => 'Ngày thành lập Mặt trận Tổ quốc Việt Nam.'
        ),
        array(
            'dd' => 1,
            'mm' => 10,
            'name' => 'Ngày quốc tế người cao tuổi.'
        ),
        array(
            'dd' => 10,
            'mm' => 10,
            'name' => 'Ngày giải phóng thủ đô.'
        ),
        array(
            'dd' => 13,
            'mm' => 10,
            'name' => 'Ngày doanh nhân Việt Nam.'
        ),
        array(
            'dd' => 20,
            'mm' => 10,
            'name' => 'Ngày Phụ nữ Việt Nam.'
        ),
        array(
            'dd' => 31,
            'mm' => 10,
            'name' => 'Ngày Hallowen.'
        ),
        array(
            'dd' => 9,
            'mm' => 11,
            'name' => 'Ngày pháp luật Việt Nam.'
        ),
        array(
            'dd' => 20,
            'mm' => 11,
            'name' => 'Ngày Nhà giáo Việt Nam.'
        ),
        array(
            'dd' => 23,
            'mm' => 11,
            'name' => 'Ngày thành lập Hội chữ thập đỏ Việt Nam.'
        ),
        array(
            'dd' => 1,
            'mm' => 12,
            'name' => 'Ngày thế giới phòng chống AIDS.'
        ),
        array(
            'dd' => 19,
            'mm' => 12,
            'name' => 'Ngày toàn quốc kháng chiến.'
        ),
        array(
            'dd' => 24,
            'mm' => 12,
            'name' => 'Ngày lễ Giáng sinh.'
        ),
        array(
            'dd' => 22,
            'mm' => 12,
            'name' => 'Ngày thành lập quân đội nhân dân Việt Nam.'
        ),
    );

    static $leal = array(
        array(
            'dd' => 1,
            'mm' => 1,
            'name' => 'Tết Nguyên Đán.',
        ),
        array(
            'dd' => 15,
            'mm' => 1,
            'name' => 'Tết Nguyên Tiêu (Lễ Thượng Nguyên).',
        ),
        array(
            'dd' => 3,
            'mm' => 3,
            'name' => 'Tết Hàn Thực.',
        ),
        array(
            'dd' => 10,
            'mm' => 3,
            'name' => 'Giỗ Tổ Hùng Vương.',
        ),
        array(
            'dd' => 15,
            'mm' => 4,
            'name' => 'Lễ Phật Đản.',
        ),
        array(
            'dd' => 5,
            'mm' => 5,
            'name' => 'Tết Đoan Ngọ.',
        ),
        array(
            'dd' => 15,
            'mm' => 7,
            'name' => 'Lễ Vu Lan.',
        ),
        array(
            'dd' => 15,
            'mm' => 8,
            'name' => 'Tết Trung Thu.',
        ),
        array(
            'dd' => 9,
            'mm' => 9,
            'name' => 'Tết Trùng Cửu.',
        ),
        array(
            'dd' => 10,
            'mm' => 10,
            'name' => 'Tết Thường Tân.',
        ),
        array(
            'dd' => 15,
            'mm' => 10,
            'name' => 'Tết Hạ Nguyên.',
        ),
        array(
            'dd' => 23,
            'mm' => 12,
            'name' => 'Tiễn Táo Quân về trời.',
        ),
    );

    static $sukien = array(
        1 => array(
            '06/01/1946 : Tổng tuyển cử bầu Quốc hội đầu tiên của nước Việt Nam dân chủ cộng hòa',
            '07/01/1979 : Chiến thắng biên giới Tây Nam chống quân xâm lược',
            '09/01/1950 : Ngày truyền thống học sinh, sinh viên Việt nam.',
            '13/01/1941 : Khởi nghĩa Đô Lương',
            '11/01/2007 : Việt Nam gia nhập WTO',
            '27/01/1973 : Ký hiệp định Paris',
        ),
        2 => array(
            '03/02/1930 : Thành lập Đảng cộng sản Việt Nam',
            '08/02/1941 : Lãnh tụ Hồ Chí Minh trở về nước trực tiếp lãnh đạo cách mạng Việt Nam',
            '27/02/1955 : Ngày thầy thuốc Việt Nam',
            '14/02 : Ngày lễ tình yêu',
        ),
        3 => array(
            '08/03/1910 : Ngày Quốc tế Phụ nữ',
            '11/03/1945 : Khởi nghĩa Ba Tơ',
            '18/03/1979 : Chiến thắng quân Trung Quốc xâm lược trên biên giới phía Bắc',
            '26/03/1931 : Ngày thành lập Đoàn TNCS Hồ Chí Minh',
        ),
        4 => array(
            '25/4/1976: Ngày tổng tuyển cử bầu quốc hội chung của cả nước',
            '30/4/1975: Giải phóng Miền Nam, thống nhất tổ quốc',
        ),
        5 => array(
            '01/05/1886: Ngày quốc tế lao động',
            '07/05/1954: Chiến thắng Điện Biên Phủ',
            '09/05/1945: Chiến thắng chủ nghĩa Phát xít',
            '13/05 : Ngày của Mẹ',
            '15/05/1941: Thành lập Đội TNTP Hồ Chí Minh',
            '19/05/1890: Ngày sinh Chủ tịch Hồ Chí Minh',
            '19/05/1941: Thành lập mặt trận Việt Minh',
        ),
        6 => array(
            '01/06: Quốc tế thiếu nhi',
            '05/06/1911: Nguyễn Tất Thành rời cảng Nhà Rồng ra đi tìm đường cứu nước',
            '17/06 : Ngày của Bố',
            '21/06/1925: Ngày báo chí Việt Nam',
            '28/06/2011: Ngày gia đình Việt Nam',
        ),
        7 => array(
            '02/07/1976: Nước ta đổi quốc hiệu từ Việt Nam dân chủ cộng hòa thành Cộng hòa XHCN Việt Nam',
            '17/07/1966: Hồ chủ tịch ra lời kêu gọi “Không có gì quý hơn độc lập, tự do”',
            '27/07: Ngày thương binh, liệt sĩ',
            '28/07: Thành lập công đoàn Việt Nam(1929)/Ngày Việt Nam gia nhập Asean(1995)',
        ),
        8 => array(
            '01/08/1930: Ngày truyền thống công tác tư tưởng văn hoá của Đảng',
            '19/08/1945: Cách mạng tháng 8 (Ngày Công an nhân dân)',
            '20/08/1888: Ngày sinh chủ tịch Tôn Đức Thắng',
        ),
        9 => array(
            '02/09: Quốc khánh (1945)/ Ngày Chủ tịch Hồ Chí Minh qua đời (1969)',
            '10/09/1955: Thành lập Mặt trận Tổ quốc Việt Nam',
            '12/09/1930: Xô Viết Nghệ Tĩnh',
            '20/09/1977: Việt Nam trở thành thành viên Liên hiệp quốc',
            '23/09/1945: Nam Bộ kháng chiến',
            '27/09/1940: Khởi nghĩa Bắc Sơn',
        ),
        10 => array(
            '01/10/1991: Ngày quốc tế người cao tuổi',
            '10/10/1954: Giải phóng thủ đô',
            '14/10/1930: Ngày hội Nông dân Việt Nam',
            '15/10/1956: Ngày truyền thống Hội thanh niên Việt Nam',
            '20/10/1930: Thành lập Hội liên hiệp phụ nữ Việt Nam',

        ),
        11 => array(
            '20/11: Ngày nhà giáo Việt Nam',
            '23/11/1940: Khởi nghĩa Nam Kỳ',
            '23/11/1946: Thành lập Hội chữ thập đỏ Việt Nam',
        ),
        12 => array(
            '01/12 : Ngày thế giới phòng chống AIDS',
            '19/12/1946: Toàn quốc kháng chiến',
            '22/12/1944: Thành lập quân đội nhân dân Việt Nam',
        ),
    );
}