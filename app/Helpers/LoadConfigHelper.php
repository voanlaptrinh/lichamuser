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
        1 => [
            'name' => 'Tháng 1: Tháng của Sự Khởi Đầu và Sức Sống Nội Tại',
            'weather' => 'Tháng 1 là khúc dạo đầu của năm mới, khi đất trời vẫn còn ngái ngủ trong cái lạnh cuối đông nhưng đã âm thầm cựa mình thức giấc. Những cơn mưa phùn giăng mắc khắp không gian, không lạnh buốt mà như những sợi tơ trời ẩm ướt, gột rửa vạn vật, chuẩn bị cho một cuộc hồi sinh. Đây là thời gian của sự tĩnh lặng, khi năng lượng được tích tụ sâu trong lòng đất, chờ ngày bùng nổ.',
            'symbolism' => 'Hoa Cúc Trường Sinh, loài hoa bất khuất trước sương giá, là biểu tượng hoàn hảo cho tháng 1. Nó tượng trưng cho sức mạnh nội tại, ý chí kiên cường và khả năng vượt qua mọi nghịch cảnh. Người sinh tháng 1 mang trong mình tinh thần của một người tiên phong, trầm tĩnh nhưng quyết liệt. Họ không ồn ào, nhưng bên trong là một nghị lực phi thường, luôn sẵn sàng đối mặt và kiến tạo nên con đường riêng của mình.',
        ],
        2 => [
            'name' => 'Tháng 2: Tháng của Lễ Hội và Sự Giao Hòa',
            'weather' => 'Tháng 2 là trái tim của mùa xuân. Không khí náo nức của lễ hội lan tỏa khắp nơi, từ làng quê đến thành thị. Cây cối không còn e dè mà đồng loạt đâm chồi, nảy lộc, khoe sắc. Tiết trời trở nên dịu dàng, ấm áp, cái lạnh chỉ còn se se như một lời tạm biệt của mùa đông. Nắng xuân vàng như mật, rót xuống trần gian, mời gọi vạn vật giao hòa.',
            'symbolism' => 'Hoa Trinh Nữ (hoa Mắc Cỡ) tượng trưng cho người sinh tháng 2. Bề ngoài có vẻ e ấp, nhạy cảm, dễ rung động trước những va chạm nhỏ nhất của cuộc sống, nhưng bản chất bên trong lại vô cùng tận tụy và chân thành. Họ là những người có khả năng kết nối cảm xúc mạnh mẽ, là chất keo gắn kết các mối quan hệ, mang lại sự ấm áp và vui vẻ cho những người xung quanh, giống như không khí của mùa lễ hội.',
        ],
        3 => [
            'name' => 'Tháng 3: Tháng của Sức Trẻ và Khát Vọng Bứt Phá',
            'weather' => 'Là tháng cuối cùng của mùa xuân, tháng 3 mang trong mình nguồn năng lượng căng tràn nhất. Những cơn mưa rào bất chợt gột rửa đi những dấu vết cuối cùng của mùa cũ, nhường chỗ cho một sức sống mãnh liệt. Đây là thời điểm vạn vật phát triển mạnh mẽ nhất, là lúc con người trở lại với guồng quay công việc với một tinh thần hăng say, đầy nhiệt huyết sau những ngày du xuân.',
            'symbolism' => 'Hoa Bách Hợp (hoa Loa Kèn) với vẻ đẹp tinh khôi nhưng kiêu hãnh, đại diện cho tháng 3. Người sinh tháng này sở hữu một sức hấp dẫn bí ẩn, một sự tinh tế trong tâm hồn nhưng cũng không thiếu phần quyết đoán, thậm chí là độc đoán khi cần thiết. Họ là những người có hoài bão, luôn muốn vươn lên và khẳng định giá trị bản thân, giống như những đóa loa kèn vươn mình kiêu hãnh dưới ánh nắng.',
        ],
        4 => [
            'name' => 'Tháng 4: Tháng của Sự Chuyển Giao và Những Nỗi Niềm Sâu Lắng',
            'weather' => 'Tháng 4 là cây cầu mỏng manh nối giữa mùa xuân và mùa hạ. Những cơn nắng đầu hè bắt đầu xuất hiện, chưa gay gắt nhưng đủ để hong khô những giọt mưa xuân còn sót lại. Không gian mang một vẻ đẹp dịu dàng, có chút man mác buồn của sự chia ly, như những cánh hoa loa kèn cuối mùa hay những đóa gạo rực lửa chỉ nở trong khoảnh khắc.',
            'symbolism' => 'Hoa Mộc Lan, loài hoa nở sớm tàn nhanh, tượng trưng cho tháng 4. Nó gợi lên hình ảnh của những nguyện vọng lớn lao, những khát khao cháy bỏng nhưng đôi khi lại phảng phất một nỗi ưu phiền, tiếc nuối. Người sinh tháng 4 có tham vọng, muốn là trung tâm của sự chú ý, nhưng sâu thẳm trong họ là một tâm hồn nhạy cảm, dễ bị tổn thương. Họ cần học cách cân bằng giữa việc thể hiện bản thân và sự khiêm tốn để tìm thấy sự bình yên.',
        ],
        5 => [
            'name' => 'Tháng 5: Tháng của Sự Tỏa Sáng Rực Rỡ',
            'weather' => 'Tháng 5 chính thức mở ra cánh cửa mùa hè. Mặt trời ngự trị trên bầu trời, ban phát nguồn năng lượng dồi dào. Cây cối xanh mướt, sum suê, vạn vật đều ở trạng thái sung mãn nhất. Đêm trở nên ngắn hơn, nhường chỗ cho những ngày dài đầy nắng và gió. Đây là thời gian của sự trưởng thành và tỏa sáng.',
            'symbolism' => 'Hoa Lan Chuông, với vẻ đẹp ngọt ngào, tinh khiết nhưng quý phái, là biểu tượng của tháng 5. Người sinh tháng này có xu hướng cầu toàn, tỉ mỉ và luôn muốn mọi thứ phải hoàn hảo. Họ có thể hơi cố chấp và nóng vội, nhưng sự nhiệt huyết và đam mê của họ có sức lan tỏa mạnh mẽ, giống như ánh nắng rực rỡ của tháng 5 có thể sưởi ấm vạn vật.',
        ],
        6 => [
            'name' => 'Tháng 6: Tháng của Kỷ Niệm và Những Cung Bậc Cảm Xúc',
            'weather' => 'Tháng 6 gắn liền với hình ảnh hoa phượng đỏ rực một góc trời và tiếng ve ran trong vòm lá. Đây là tháng của những cuộc chia tay tuổi học trò, của những trang lưu bút ướp đầy kỷ niệm. Nắng hè trở nên gay gắt hơn, và những cơn mưa rào bất chợt đến rồi đi, như những cảm xúc vui buồn lẫn lộn của thời khắc chia ly.',
            'symbolism' => 'Dù hoa Tulip được nhắc đến, nhưng ở Việt Nam, Hoa Phượng mới là linh hồn của tháng 6. Nó tượng trưng cho sự nồng cháy của tuổi trẻ, cho những mối tình đầu trong sáng và cả những giọt nước mắt của sự nuối tiếc. Người sinh tháng 6 mang một tâm hồn lãng mạn, đa cảm và sâu sắc. Họ sống bằng tình cảm, trân trọng ký ức và luôn mang trong mình một nỗi ưu phiền man mác, đẹp như một bài thơ.',
        ],
        7 => [
            'name' => 'Tháng 7: Tháng của Sự Dữ Dội và Sức Mạnh Đối Mặt',
            'weather' => 'Tháng 7 là đỉnh điểm của mùa hè, nơi mặt trời và những cơn mưa đối đầu một cách dữ dội nhất. Nắng chói chang như thiêu như đốt, rồi đột ngột bị thay thế bởi những trận mưa rào xối xả, mang theo sấm chớp và những con lũ dâng đầy. Đây là tháng của sự tương phản cực độ, của sức mạnh thiên nhiên không thể khuất phục.',
            'symbolism' => 'Hoa Phi Yến, với dáng vẻ kiêu sa và độc đáo, tượng trưng cho người sinh tháng 7. Họ là những tâm hồn mơ mộng nhưng cũng rất mạnh mẽ và ngang tàn. Họ có khả năng suy xét sâu sắc, thường có những ý tưởng khác biệt mà người khác cho là lập dị. Giống như thời tiết tháng 7, tính cách của họ có thể thay đổi đột ngột, mãnh liệt và cố chấp, nhưng đó cũng chính là sức mạnh giúp họ đứng vững trước những giông bão của cuộc đời.',
        ],
        8 => [
            'name' => 'Tháng 8: Tháng của Sự Dịu Dàng Chớm Thu',
            'weather' => 'Tháng 8 là bản giao hưởng êm dịu giữa mùa hạ và mùa thu. Cái nắng gay gắt đã lùi bước, nhường chỗ cho những ngày dịu mát, gió heo may bắt đầu thổi về mang theo hương ổi, hương cốm. Bầu trời như cao và trong xanh hơn. Miền Bắc lúc này mang một chút dáng dấp của thời tiết ôn hòa miền Nam, một sự giao thoa ngọt ngào và lãng mạn.',
            'symbolism' => 'Hoa Hồng, nữ hoàng của các loài hoa, là biểu tượng của tháng 8. Nó không chỉ tượng trưng cho tình yêu mãnh liệt mà còn là vẻ đẹp của sự trưởng thành, đằm thắm. Người sinh tháng 8 thường có sức hấp dẫn đặc biệt, vừa nồng nàn, quyến rũ, vừa tinh tế, sâu sắc. Họ là những người biết cân bằng giữa lý trí và tình cảm, mang lại cảm giác bình yên và tin cậy cho người đối diện.',
        ],
        9 => [
            'name' => 'Tháng 9: Tháng của Sự Khởi Đầu Mới và Hy Vọng',
            'weather' => 'Tháng 9 là mùa tựu trường, là mùa của những khởi đầu mới. Mùa thu đã hiện diện rõ rệt hơn qua những chiếc lá vàng rơi và không khí se lạnh vào buổi sớm. Nắng không còn gắt, chỉ đủ để làm vàng óng những cánh đồng lúa đang chờ gặt. Đây là tháng của hy vọng, của những kế hoạch và dự định mới được ươm mầm.',
            'symbolism' => 'Hoa Cẩm Chướng, loài hoa của sự bộc trực và lòng hăng hái, đại diện cho tháng 9. Người sinh tháng này thường thẳng thắn, nhiệt tình và tràn đầy năng lượng. Họ không ngại thử thách, luôn sẵn sàng cho những khám phá mới. Tâm hồn họ giống như mùa thu, vừa lãng mạn, sâu lắng nhưng cũng đầy ắp những khát khao và đam mê được cống hiến.',
        ],
        10 => [
            'name' => 'Tháng 10: Tháng của Vẻ Đẹp Lãng Mạn và Sâu Lắng',
            'weather' => 'Tháng 10 là tháng mùa thu đẹp và lãng mạn nhất. Hà Nội nồng nàn mùi hoa sữa, không khí trong lành và se lạnh. Đây là thời gian lý tưởng để đi dạo dưới những hàng cây lá vàng, để suy ngẫm và chiêm nghiệm. Vẻ đẹp của tháng 10 không phô trương mà sâu lắng, khiến lòng người tĩnh lại và trở nên tinh tế hơn.',
            'symbolism' => 'Hoa Hải Đường, loài hoa của sự tinh tế, dũng cảm và may mắn, là biểu tượng của tháng 10. Người sinh tháng này có một tâm hồn nghệ sĩ, một trái tim nồng hậu và một ý chí mạnh mẽ. Họ có khả năng nhìn thấy vẻ đẹp trong những điều bình dị nhất. Tháng 10 không chỉ là tháng của sự lãng mạn mà còn ẩn chứa một sức mạnh nội tâm, thôi thúc người ta thực hiện những cải cách lớn lao từ chính những điều nhỏ bé.',
        ],
        11 => [
            'name' => 'Tháng 11: Tháng của Sự Lắng Đọng và Trầm Tư',
            'weather' => 'Tháng 11 mang đến những cơn gió mùa đông bắc đầu tiên, cái rét không cắt da cắt thịt mà chỉ đủ để người ta cảm nhận được sự chuyển mùa rõ rệt. Mùa thu lưu luyến ở lại trong những ngày nắng hanh hao cuối cùng trước khi nhường chỗ hoàn toàn cho mùa đông. Đây là thời gian của sự lắng đọng, khi vạn vật bắt đầu thu mình lại, chuẩn bị cho một kỳ nghỉ dài.',
            'symbolism' => 'Hoa Lay Ơn, với vẻ đẹp thanh lịch và bí ẩn, tượng trưng cho tháng 11. Người sinh tháng này thường có vẻ ngoài hấp dẫn, một tâm hồn sâu sắc và một trí tuệ sắc sảo. Họ thông minh, chịu khó và luôn đi tìm một chỗ dựa tinh thần vững chắc, giống như không khí mong manh của tháng 11 khiến người ta muốn tìm về một nơi ấm áp.',
        ],
        12 => [
            'name' => 'Tháng 12: Tháng của Sự Kết Thúc và Chiêm Nghiệm',
            'weather' => 'Tháng 12 là tháng cuối cùng của năm, là điểm giữa của mùa đông lạnh giá. Thời tiết khô và lạnh, cây cối trơ trọi, dường như mọi vận động đều chậm lại. Không gian tĩnh lặng, nhợt nhạt, nhưng chính trong sự tĩnh lặng đó, người ta lại có thời gian để nhìn lại một năm đã qua, để chiêm nghiệm, tổng kết và chuẩn bị cho một vòng quay mới.',
            'symbolism' => 'Hoa Thủy Tiên, loài hoa có thể nở trong giá lạnh, mang vẻ đẹp thanh thoát và tinh khôi, là biểu tượng của tháng 12. Nó tượng trưng cho sự tái sinh và hy vọng ngay trong hoàn cảnh khắc nghiệt nhất. Người sinh tháng 12 thường thân thiện, cởi mở và mang trong mình một sự lạc quan đáng ngạc nhiên. Họ là những người kết thúc một chu kỳ cũ để mở ra một chu kỳ mới, mang lại niềm tin và may mắn cho mọi người xung quanh.',
        ],
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
            'name' => 'Ngày dân số thế giới.' //
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
