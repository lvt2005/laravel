<footer>
    <div class="container">
        <div class="footer-content">
            <!-- Logo & Info -->
            <div class="footer-logo-section">
                <div class="footer-logo">
                    <img src="{{ asset('frontend/img/logomau.jpg') }}" alt="DoctorHub Logo" />
                </div>
                <p>
                    Địa chỉ: 70 Đ. Tô Ký, Tân Chánh Hiệp, Quận 12, Thành phố Hồ Chí
                    Minh, Việt Nam
                </p>
                <p>Email: nhom5@gmail.com</p>
                <p>
                    GPDKKD: 0312088602 cấp ngày 14/12/2012 bởi Sở Kế hoạch và Đầu tư
                    TPHCM. Giấy phép hoạt động khám bệnh, chữa bệnh số 230/BYT-GPHD do
                    Bộ Y Tế cấp.
                </p>
            </div>

            <!-- About -->
            <div class="footer-section">
                <h4>Về chúng tôi</h4>
                <ul>
                    <li><a href="/tim-bac-si">Đội ngũ bác sĩ</a></li>
                    <li><a href="#">Cơ sở vật chất</a></li>
                    <li><a href="#">Câu chuyện khách hàng</a></li>
                    <li><a href="#">Tuyển dụng</a></li>
                    <li><a href="#">Cẩm nang bệnh</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
                
                <!-- Weather Widget - Dự báo thời tiết -->
                <div class="weather-widget" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 12px; margin-top: 20px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                    <div style="font-size: 12px; opacity: 0.9; margin-bottom: 10px; font-weight: 600;">
                        <i class="fas fa-cloud-sun"></i> Dự báo thời tiết TP.HCM
                    </div>
                    <div id="weatherCurrent" style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <span id="weatherIcon" style="font-size: 36px;">⛅</span>
                        <div>
                            <div id="weatherTemp" style="font-size: 24px; font-weight: 700;">--°C</div>
                            <div id="weatherDesc" style="font-size: 12px; opacity: 0.9;">Đang tải...</div>
                        </div>
                        <div style="margin-left: auto; text-align: right;">
                            <div id="weatherHumidity" style="font-size: 11px;"><i class="fas fa-tint"></i> --%</div>
                            <div id="weatherWind" style="font-size: 11px;"><i class="fas fa-wind"></i> -- km/h</div>
                        </div>
                    </div>
                    <div id="weatherForecast" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.2);">
                        <div class="forecast-day" style="text-align:center;padding:5px;">
                            <div style="font-size:10px;opacity:0.8;">--</div>
                            <div style="font-size:16px;">--</div>
                            <div style="font-size:11px;font-weight:600;">--°</div>
                        </div>
                        <div class="forecast-day" style="text-align:center;padding:5px;">
                            <div style="font-size:10px;opacity:0.8;">--</div>
                            <div style="font-size:16px;">--</div>
                            <div style="font-size:11px;font-weight:600;">--°</div>
                        </div>
                        <div class="forecast-day" style="text-align:center;padding:5px;">
                            <div style="font-size:10px;opacity:0.8;">--</div>
                            <div style="font-size:16px;">--</div>
                            <div style="font-size:11px;font-weight:600;">--°</div>
                        </div>
                        <div class="forecast-day" style="text-align:center;padding:5px;">
                            <div style="font-size:10px;opacity:0.8;">--</div>
                            <div style="font-size:16px;">--</div>
                            <div style="font-size:11px;font-weight:600;">--°</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Working Hours -->
            <div class="footer-section">
                <h4>Giờ làm việc</h4>
                <div class="working-hours">
                    <p><strong>Từ thứ 2 đến thứ 7</strong></p>
                    <p>Buổi sáng:<br />7:00 - 12:00</p>
                    <p>Buổi chiều:<br />13:30 - 17:00</p>
                </div>
                
                <!-- Realtime Clock Widget from logwork.com -->
                <div class="realtime-clock" style=" padding: 15px; border-radius: 12px; margin: 15px 0; text-align: center; min-height: 140px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(30, 91, 168, 0.3);">
                    <script src="https://cdn.logwork.com/widget/clock.js"></script>
                    <a href="https://logwork.com/current-time-in-viet-kieu-vietnam-ba-ria-vung-tau" class="clock-time" data-style="old-roman" data-size="250" data-timezone="Asia/Ho_Chi_Minh" style="text-decoration: none; color: white;">Nhóm 5</a>
                </div>
                
                <a href="tel:18006767" class="hotline-btn">Hotline: 1800 6767</a>
            </div>

            <!-- Contact -->
            <div class="contact-section">
                <h4>Liên hệ</h4>
                <div class="social-icons">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
                <div class="newsletter-section">
                    <p><strong>Theo dõi bản tin chúng tôi</strong></p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Email" required />
                        <button type="submit">Đăng ký</button>
                    </form>
                </div>
                <div class="dmca-badge">
                    <img src="{{ asset('frontend/img/dmca_protected_16_120.png') }}" alt="DMCA Protected" />
                </div>
            </div>
        </div>

        <hr class="footer-divider" />

        <div class="footer-bottom">
            <p>&copy; Hệ thống đặt lịch hẹn. Tất cả các quyền được bảo vệ.</p>
            <ul class="footer-links">
                <li><a href="#">Chính sách bảo mật</a></li>
                <li><a href="#">Điều khoản sử dụng</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
        </div>
    </div>
</footer>

<script>
// Weather API - Sử dụng Open-Meteo API (miễn phí, không cần API key)
async function loadWeather() {
    try {
        // TP.HCM coordinates
        const lat = 10.8231;
        const lon = 106.6297;
        
        // Fetch current weather and forecast
        const response = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&daily=weather_code,temperature_2m_max,temperature_2m_min&timezone=Asia%2FHo_Chi_Minh&forecast_days=5`);
        
        if (!response.ok) throw new Error('Weather API error');
        
        const data = await response.json();
        
        // Weather code to icon and description mapping
        const weatherCodes = {
            0: { icon: '☀️', desc: 'Trời quang' },
            1: { icon: '🌤️', desc: 'Ít mây' },
            2: { icon: '⛅', desc: 'Có mây' },
            3: { icon: '☁️', desc: 'Nhiều mây' },
            45: { icon: '🌫️', desc: 'Sương mù' },
            48: { icon: '🌫️', desc: 'Sương giá' },
            51: { icon: '🌧️', desc: 'Mưa phùn nhẹ' },
            53: { icon: '🌧️', desc: 'Mưa phùn' },
            55: { icon: '🌧️', desc: 'Mưa phùn dày' },
            61: { icon: '🌧️', desc: 'Mưa nhẹ' },
            63: { icon: '🌧️', desc: 'Mưa vừa' },
            65: { icon: '🌧️', desc: 'Mưa to' },
            80: { icon: '🌦️', desc: 'Mưa rào nhẹ' },
            81: { icon: '🌦️', desc: 'Mưa rào' },
            82: { icon: '⛈️', desc: 'Mưa rào to' },
            95: { icon: '⛈️', desc: 'Giông bão' },
            96: { icon: '⛈️', desc: 'Giông có mưa đá' },
            99: { icon: '⛈️', desc: 'Giông mưa đá lớn' }
        };
        
        // Update current weather
        const current = data.current;
        const weatherInfo = weatherCodes[current.weather_code] || { icon: '🌡️', desc: 'Không xác định' };
        
        document.getElementById('weatherIcon').textContent = weatherInfo.icon;
        document.getElementById('weatherTemp').textContent = `${Math.round(current.temperature_2m)}°C`;
        document.getElementById('weatherDesc').textContent = weatherInfo.desc;
        document.getElementById('weatherHumidity').innerHTML = `<i class="fas fa-tint"></i> ${current.relative_humidity_2m}%`;
        document.getElementById('weatherWind').innerHTML = `<i class="fas fa-wind"></i> ${Math.round(current.wind_speed_10m)} km/h`;
        
        // Update forecast
        const daily = data.daily;
        const dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        const forecastContainer = document.getElementById('weatherForecast');
        
        let forecastHtml = '';
        for (let i = 1; i <= 4; i++) {
            const date = new Date(daily.time[i]);
            const dayName = dayNames[date.getDay()];
            const maxTemp = Math.round(daily.temperature_2m_max[i]);
            const minTemp = Math.round(daily.temperature_2m_min[i]);
            const icon = (weatherCodes[daily.weather_code[i]] || { icon: '🌡️' }).icon;
            
            forecastHtml += `
                <div class="forecast-day" style="text-align:center;padding:5px;background:rgba(255,255,255,0.1);border-radius:8px;">
                    <div style="font-size:10px;opacity:0.8;">${dayName}</div>
                    <div style="font-size:18px;">${icon}</div>
                    <div style="font-size:11px;font-weight:600;">${maxTemp}°/${minTemp}°</div>
                </div>
            `;
        }
        forecastContainer.innerHTML = forecastHtml;
        
    } catch (error) {
        console.error('Error loading weather:', error);
        document.getElementById('weatherDesc').textContent = 'Không thể tải thời tiết';
    }
}

// Load weather on page load
document.addEventListener('DOMContentLoaded', loadWeather);
// Refresh weather every 30 minutes
setInterval(loadWeather, 30 * 60 * 1000);
</script>
