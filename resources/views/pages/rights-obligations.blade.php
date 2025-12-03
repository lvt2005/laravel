@extends('layouts.public')

@section('title', 'Quyền và Nghĩa Vụ Người Bệnh - Hệ thống đặt lịch hẹn')

@section('styles')
<style>
    /* Banner Section */
    .banner-section {
        position: relative;
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, rgba(30, 91, 168, 0.9), rgba(13, 58, 110, 0.9)), url('/frontend/img/banner1.png');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        padding: 40px 20px;
    }
    .banner-section h1 {
        font-size: 2.5rem;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .banner-section p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 700px;
    }
    .banner-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    .banner-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    .banner-btn.primary {
        background: #ff9800;
        color: white;
    }
    .banner-btn.primary:hover {
        background: #f57c00;
        transform: translateY(-2px);
    }
    .banner-btn.secondary {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid white;
    }
    .banner-btn.secondary:hover {
        background: white;
        color: #1e5ba8;
    }

    /* Content Container */
    .content-container {
        max-width: 1000px;
        margin: -50px auto 50px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }
    .content-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Header */
    .content-header {
        text-align: center;
        padding: 35px 40px;
        border-bottom: 3px solid #1e5ba8;
    }
    .content-header h1 {
        font-size: 2rem;
        color: #1e5ba8;
        margin-bottom: 15px;
    }
    .content-header p {
        color: #666;
        line-height: 1.7;
    }

    /* Content Body */
    .content-body {
        padding: 40px;
    }
    .main-title {
        text-align: center;
        font-size: 1.6rem;
        font-weight: bold;
        color: #1a1a1a;
        margin: 30px 0 15px;
        text-transform: uppercase;
    }
    .subtitle {
        text-align: center;
        font-style: italic;
        color: #666;
        margin-bottom: 40px;
    }

    /* Sections */
    .section {
        margin-bottom: 45px;
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1a1a1a;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 3px solid #1e5ba8;
    }
    .subsection-title {
        font-size: 1.15rem;
        font-weight: bold;
        color: #1e5ba8;
        margin: 28px 0 15px;
    }
    .content-text {
        color: #555;
        margin-bottom: 15px;
        text-align: justify;
        line-height: 1.8;
    }

    /* Lists */
    .content-body ul {
        margin: 15px 0 20px 30px;
    }
    .content-body ul li {
        margin-bottom: 12px;
        color: #555;
        line-height: 1.8;
        padding-left: 8px;
    }
    .content-body ul li::marker {
        color: #1e5ba8;
        font-weight: bold;
    }
    .nested-list {
        margin-left: 35px;
        margin-top: 10px;
    }
    .nested-list li {
        margin-bottom: 8px;
    }

    /* Highlight Section */
    .highlight-section {
        background: #f8fafc;
        padding: 25px;
        border-left: 4px solid #1e5ba8;
        margin: 25px 0;
        border-radius: 0 12px 12px 0;
    }
    .highlight-section h3 {
        color: #1e5ba8;
        margin-bottom: 15px;
        font-size: 1.1rem;
        line-height: 1.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .banner-section {
            height: 350px;
        }
        .banner-section h1 {
            font-size: 1.8rem;
        }
        .banner-buttons {
            flex-direction: column;
        }
        .content-header, .content-body {
            padding: 25px;
        }
        .content-header h1 {
            font-size: 1.5rem;
        }
        .section-title {
            font-size: 1.3rem;
        }
        .subsection-title {
            font-size: 1.05rem;
        }
        .content-body ul {
            margin-left: 20px;
        }
        .nested-list {
            margin-left: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="banner-section">
    <h1>📋 Quyền và Nghĩa Vụ Người Bệnh</h1>
    <p>Quý khách vui lòng đọc kỹ để được đảm bảo quyền lợi khi khám và chữa bệnh</p>
    <div class="banner-buttons">
        <a href="/dat-lich" class="banner-btn primary">
            <i class="fas fa-calendar-check"></i> Đặt lịch hẹn
        </a>
        <a href="/tim-bac-si" class="banner-btn secondary">
            <i class="fas fa-user-md"></i> Tìm bác sĩ
        </a>
    </div>
</div>

<div class="content-container">
    <div class="content-card">
        <div class="content-header">
            <h1>Quyền và Nghĩa Vụ của người bệnh</h1>
            <p>Quý khách vui lòng đọc kỹ Quyền và nghĩa vụ của người bệnh, thân nhân người bệnh theo luật khám chữa bệnh mới để được đảm bảo quyền lợi khi khám và chữa bệnh tại Hệ thống đặt lịch hẹn.</p>
        </div>

        <div class="content-body">
            <div class="main-title">QUYỀN VÀ NGHĨA VỤ CỦA NGƯỜI BỆNH</div>
            <p class="subtitle">(Chương II, Luật khám bệnh, chữa bệnh số 15/2023/QH15 ngày 09/01/2023)</p>

            <!-- PHẦN I: QUYỀN CỦA NGƯỜI BỆNH -->
            <div class="section">
                <h2 class="section-title">I. QUYỀN CỦA NGƯỜI BỆNH</h2>

                <!-- Quyền được khám bệnh, chữa bệnh -->
                <h3 class="subsection-title">Quyền được khám bệnh, chữa bệnh</h3>
                <ul>
                    <li>Được thông tin, giải thích về tình trạng sức khỏe; phương pháp, dịch vụ khám bệnh, chữa bệnh, giá dịch vụ khám bệnh, chữa bệnh; được hướng dẫn cách tự theo dõi, chăm sóc, phòng ngừa tai biến.</li>
                    <li>Được khám bệnh, chữa bệnh bằng phương pháp an toàn phù hợp với bệnh, tình trạng sức khỏe của mình và điều kiện thực tế của cơ sở khám bệnh, chữa bệnh.</li>
                </ul>

                <!-- Quyền được tôn trọng -->
                <h3 class="subsection-title">Quyền được tôn trọng danh dự, bảo vệ sức khỏe và tôn trọng bí mật riêng tư trong khám bệnh, chữa bệnh</h3>
                <ul>
                    <li>Được tôn trọng về tuổi, giới tính, dân tộc, tôn giáo, tín ngưỡng, tình trạng sức khỏe, điều kiện kinh tế, địa vị xã hội.</li>
                    <li>Được giữ bí mật thông tin trong hồ sơ bệnh án và thông tin khác về đời tư mà người bệnh đã cung cấp cho người hành nghề trong quá trình khám bệnh, chữa bệnh, trừ trường hợp người bệnh đồng ý chia sẻ thông tin theo quy định của pháp luật hoặc trường hợp quy định tại khoản 3 và khoản 4 Điều 69 của Luật Khám bệnh, chữa bệnh số 15/2023/QH15.</li>
                    <li>Không bị kỳ thị, phân biệt đối xử, ngược đãi, lạm dụng thể chất, lạm dụng tình dục trong quá trình khám bệnh, chữa bệnh.</li>
                    <li>Không bị ép buộc khám bệnh, chữa bệnh, trừ trường hợp bắt buộc chữa bệnh bao gồm: người mắc bệnh truyền nhiễm nhóm A theo quy định của pháp luật về phòng, chống bệnh truyền nhiễm; người mắc bệnh trầm cảm có ý tưởng, hành vi tự sát; người mắc bệnh tâm thần ở trạng thái kích động có khả năng gây nguy hại cho bản thân hoặc có hành vi gây nguy hại cho người khác hoặc phá hoại tài sản và các trường hợp khác theo quy định của pháp luật.</li>
                </ul>

                <!-- Quyền được lựa chọn -->
                <h3 class="subsection-title">Quyền được lựa chọn trong khám bệnh, chữa bệnh</h3>
                <ul>
                    <li>Lựa chọn phương pháp khám bệnh, chữa bệnh sau khi được cung cấp thông tin, giải thích, tư vấn đầy đủ về tình trạng bệnh, kết quả, rủi ro có thể xảy ra, trừ trường hợp người bệnh yêu cầu phương pháp khám bệnh, chữa bệnh không phù hợp với quy định về chuyên môn kỹ thuật.</li>
                    <li>Chấp nhận hoặc từ chối tham gia nghiên cứu y sinh học về khám bệnh, chữa bệnh.</li>
                </ul>

                <!-- Quyền được cung cấp thông tin -->
                <h3 class="subsection-title">Quyền được cung cấp thông tin về hồ sơ bệnh án và chi phí khám bệnh, chữa bệnh</h3>
                <ul>
                    <li>Người bệnh hoặc người đại diện của người bệnh được đọc, xem, sao chụp, ghi chép hồ sơ bệnh án và được cung cấp bản tóm tắt hồ sơ bệnh án khi có yêu cầu bằng văn bản.</li>
                    <li>Được cung cấp và giải thích chi tiết về các khoản chi trả dịch vụ khám bệnh, chữa bệnh khi có yêu cầu.</li>
                </ul>

                <!-- Quyền được từ chối -->
                <h3 class="subsection-title">Quyền được từ chối khám bệnh, chữa bệnh và rời khỏi cơ sở khám bệnh, chữa bệnh</h3>
                <ul>
                    <li>Được từ chối khám bệnh, chữa bệnh nhưng phải cam kết tự chịu trách nhiệm bằng văn bản về việc từ chối của mình sau khi đã được người hành nghề tư vấn, trừ trường hợp bắt buộc chữa bệnh.</li>
                    <li>Được rời khỏi cơ sở khám bệnh, chữa bệnh khi chưa kết thúc chữa bệnh trái với chỉ định của người hành nghề nhưng phải cam kết tự chịu trách nhiệm bằng văn bản về việc rời khỏi cơ sở khám bệnh, chữa bệnh, trừ trường hợp bắt buộc chữa bệnh.</li>
                </ul>

                <!-- Quyền kiến nghị -->
                <h3 class="subsection-title">Quyền kiến nghị và bồi thường</h3>
                <ul>
                    <li>Được kiến nghị về tồn tại, bất cập, khó khăn, vướng mắc và vấn đề khác trong quá trình khám bệnh, chữa bệnh.</li>
                    <li>Trường hợp xảy ra tai biến y khoa đối với người bệnh, cơ sở khám bệnh, chữa bệnh có trách nhiệm bồi thường cho người bệnh theo quy định của pháp luật, trừ trường hợp người hành nghề không có sai sót chuyên môn kỹ thuật khi được Hội đồng chuyên xác định.</li>
                </ul>

                <!-- Phần đặc biệt -->
                <div class="highlight-section">
                    <h3>Việc thực hiện quyền của người bệnh bị mất năng lực hành vi dân sự, có khó khăn trong nhận thức, làm chủ hành vi, hạn chế năng lực hành vi dân sự, người bệnh là người chưa thành niên và người bệnh không có thân nhân</h3>

                    <p class="content-text">Trường hợp người bệnh là người thành niên và rơi vào tình trạng mất năng lực hành vi dân sự, có khó khăn trong nhận thức, làm chủ hành vi, hạn chế năng lực hành vi dân sự nhưng trước đó đã có văn bản thể hiện nguyện vọng hợp pháp về khám bệnh, chữa bệnh của mình thì thực hiện theo nguyện vọng của người bệnh.</p>

                    <p class="content-text">Trường hợp người bệnh là người thành niên và rơi vào tình trạng mất năng lực hành vi dân sự, có khó khăn trong nhận thức, làm chủ hành vi, hạn chế năng lực hành vi dân sự nhưng trước đó không có văn bản thể hiện nguyện vọng hợp pháp về khám bệnh, chữa bệnh của mình thì thực hiện như sau:</p>

                    <ul class="nested-list">
                        <li>Nếu có người đại diện thì thực hiện theo quyết định của người đại diện;</li>
                        <li>Nếu không có người đại diện thì thực hiện theo quyết định của người chịu trách nhiệm chuyên môn hoặc người trực lãnh đạo của cơ sở khám bệnh, chữa bệnh.</li>
                    </ul>

                    <p class="content-text">Trường hợp người bệnh là người chưa thành niên thì thực hiện như sau:</p>

                    <ul class="nested-list">
                        <li>Nếu có người đại diện thì thực hiện theo quyết định của người đại diện;</li>
                        <li>Nếu không có người đại diện thì thực hiện theo quyết định của người chịu trách nhiệm chuyên môn hoặc người trực lãnh đạo của cơ sở khám bệnh, chữa bệnh.</li>
                    </ul>
                </div>
            </div>

            <!-- PHẦN II: NGHĨA VỤ CỦA NGƯỜI BỆNH -->
            <div class="section">
                <h2 class="section-title">II. NGHĨA VỤ CỦA NGƯỜI BỆNH</h2>

                <!-- Nghĩa vụ tôn trọng -->
                <h3 class="subsection-title">Nghĩa vụ tôn trọng người hành nghề và người khác làm việc tại cơ sở khám bệnh, chữa bệnh</h3>
                <p class="content-text">Tôn trọng người hành nghề; không được đe dọa, xâm phạm tính mạng, sức khỏe, xúc phạm danh dự, nhân phẩm của người hành nghề và người khác làm việc tại cơ sở khám bệnh, chữa bệnh.</p>

                <!-- Nghĩa vụ chấp hành -->
                <h3 class="subsection-title">Nghĩa vụ chấp hành các quy định trong khám bệnh, chữa bệnh</h3>
                <p class="content-text">Cung cấp trung thực và chịu trách nhiệm về thông tin liên quan đến nhân thân, tình trạng sức khỏe của mình, hợp tác đầy đủ với người hành nghề và người khác làm việc tại cơ sở khám bệnh, chữa bệnh.</p>
                <p class="content-text">Chấp hành chỉ định về chẩn đoán, phương pháp chữa bệnh của người hành nghề.</p>
                <p class="content-text">Chấp hành và yêu cầu thân nhân, người đến thăm mình chấp hành nội quy của cơ sở khám bệnh, chữa bệnh, quy định của pháp luật về khám bệnh, chữa bệnh.</p>

                <!-- Nghĩa vụ chi trả -->
                <h3 class="subsection-title">Nghĩa vụ chi trả chi phí khám bệnh, chữa bệnh</h3>
                <p class="content-text">Người bệnh tham gia bảo hiểm y tế có nghĩa vụ chi trả chi phí khám bệnh, chữa bệnh ngoài phạm vi được hưởng và mức hưởng theo quy định của pháp luật về bảo hiểm y tế.</p>
                <p class="content-text">Người bệnh không tham gia bảo hiểm y tế có nghĩa vụ chi trả chi phí khám bệnh, chữa bệnh theo quy định của pháp luật.</p>
            </div>
        </div>
    </div>
</div>
@endsection
