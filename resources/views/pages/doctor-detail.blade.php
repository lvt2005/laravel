@extends('layouts.public')

@section('title', $doctor['name'] . ' - Bệnh viện Nam Sài Gòn')

@section('content')
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-user-md"></i> Thông tin bác sĩ</h1>
        <p>Chi tiết hồ sơ và lịch làm việc của bác sĩ</p>
    </div>
</div>

<div class="main-content">
    <div class="breadcrumb">
        <a href="/">Trang chủ</a>
        <span>/</span>
        <a href="/tim-bac-si">Tìm bác sĩ</a>
        <span>/</span>
        <span>{{ $doctor['name'] }}</span>
    </div>

    <div class="doctor-profile-section">
        <div class="card doctor-info-card">
            <div class="doctor-avatar-large">
                <img src="{{ $doctor['avatar'] ?? '/frontend/img/Screenshot 2025-10-17 201418.png' }}" alt="{{ $doctor['name'] }}" id="doctor-avatar" onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'">
            </div>
            <div class="doctor-details">
                <h1 id="doctor-name">{{ $doctor['degree'] ? $doctor['degree'] . ' ' : '' }}{{ $doctor['name'] }}</h1>
                <p class="specialty" id="doctor-specialty">{{ $doctor['specialty'] }}</p>
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <span id="doctor-rating">{{ $doctor['rating'] ?? '0' }}</span>
                    <span class="review-count">(<span id="doctor-reviews">{{ $doctor['reviews'] ?? '0' }}</span> đánh giá)</span>
                </div>
                <div class="experience">
                    <i class="fas fa-briefcase-medical"></i>
                    <span><span id="doctor-experience">{{ $doctor['experience'] ?? '0' }}</span> năm kinh nghiệm</span>
                </div>
                <div class="consultation-fee" style="margin-top: 10px; color: #e74c3c; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-money-bill-wave" style="margin-right: 8px;"></i>
                    Phí khám: <span id="doctor-fee">{{ number_format($doctor['consultation_fee'] ?? 200000, 0, ',', '.') }}</span> VNĐ
                </div>
            </div>
        </div>

        <div class="card">
            <h2><i class="fas fa-info-circle"></i> Giới thiệu</h2>
            <div id="doctor-bio">
                <p>{{ $doctor['bio'] ?? 'Bác sĩ có nhiều năm kinh nghiệm trong lĩnh vực y khoa, luôn tận tâm với bệnh nhân.' }}</p>
            </div>
        </div>

        <div class="card">
            <h2><i class="fas fa-graduation-cap"></i> Chuyên môn & Đào tạo</h2>
            <ul id="doctor-qualifications">
                @if(isset($doctor['qualifications']))
                    @foreach($doctor['qualifications'] as $qualification)
                    <li>{{ $qualification }}</li>
                    @endforeach
                @else
                    <li>Tốt nghiệp Đại học Y Dược TP.HCM</li>
                    <li>Bằng chuyên khoa cấp II</li>
                @endif
            </ul>
        </div>

        <div class="card">
            <h2><i class="fas fa-hospital"></i> Nơi công tác</h2>
            <div class="clinic-info">
                <p><strong id="doctor-clinic">{{ $doctor['clinic'] ?? 'Bệnh viện Nam Sài Gòn' }}</strong></p>
                <p><i class="fas fa-map-marker-alt"></i> <span id="doctor-address">{{ $doctor['address'] ?? '70 Đ. Tô Ký, Tân Chánh Hiệp, Quận 12, TP.HCM' }}</span></p>
            </div>
        </div>

        <div class="card">
            <h2><i class="fas fa-calendar-alt"></i> Lịch làm việc</h2>
            <div class="schedule-summary" id="doctor-schedule">
                <div class="loading-schedule">Đang tải lịch làm việc...</div>
            </div>
        </div>

        <div class="card booking-schedule-card">
            <h2><i class="fas fa-clock"></i> Đặt lịch khám</h2>
            <p class="schedule-hint">Click vào ô <span class="hint-available">còn trống</span> để đặt lịch khám với bác sĩ</p>
            
            <!-- Week Navigation -->
            <div class="week-navigation">
                <button class="week-nav-btn" id="prevWeekBtn" onclick="changeWeek(-1)">
                    <i class="fas fa-chevron-left"></i> Tuần trước
                </button>
                <span class="week-label" id="weekLabel">Tuần này</span>
                <button class="week-nav-btn" id="nextWeekBtn" onclick="changeWeek(1)">
                    Tuần sau <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Weekly Schedule Table -->
            <div class="weekly-schedule-container">
                <table class="weekly-schedule-table">
                    <thead>
                        <tr>
                            <th class="time-header">Giờ</th>
                            <th class="day-header" data-day="Monday">
                                <div class="day-name">Thứ 2</div>
                                <div class="day-date" id="date-Monday"></div>
                            </th>
                            <th class="day-header" data-day="Tuesday">
                                <div class="day-name">Thứ 3</div>
                                <div class="day-date" id="date-Tuesday"></div>
                            </th>
                            <th class="day-header" data-day="Wednesday">
                                <div class="day-name">Thứ 4</div>
                                <div class="day-date" id="date-Wednesday"></div>
                            </th>
                            <th class="day-header" data-day="Thursday">
                                <div class="day-name">Thứ 5</div>
                                <div class="day-date" id="date-Thursday"></div>
                            </th>
                            <th class="day-header" data-day="Friday">
                                <div class="day-name">Thứ 6</div>
                                <div class="day-date" id="date-Friday"></div>
                            </th>
                            <th class="day-header" data-day="Saturday">
                                <div class="day-name">Thứ 7</div>
                                <div class="day-date" id="date-Saturday"></div>
                            </th>
                            <th class="day-header" data-day="Sunday">
                                <div class="day-name">CN</div>
                                <div class="day-date" id="date-Sunday"></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="schedule-body">
                        <tr><td colspan="8" class="loading-schedule">Đang tải lịch...</td></tr>
                    </tbody>
                </table>
            </div>
            
            <div class="slots-legend">
                <div class="legend-item"><span class="slot-indicator available"></span> Còn trống</div>
                <div class="legend-item"><span class="slot-indicator booked"></span> Đã có lịch</div>
                <div class="legend-item"><span class="slot-indicator past"></span> Đã qua</div>
                <div class="legend-item"><span class="slot-indicator not-working"></span> Không làm việc</div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="/dat-lich/bieu-mau?doctor={{ $doctor['id'] }}" class="btn-primary"><i class="fas fa-calendar-check"></i> Đặt lịch khám</a>
            <a href="tel:19001234" class="btn-secondary"><i class="fas fa-phone"></i> Gọi tư vấn: 1900 1234</a>
        </div>
    </div>
</div>

<style>
.doctor-profile-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.doctor-info-card {
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

.doctor-avatar-large {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 4px solid #1e5ba8;
}

.doctor-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-details {
    flex: 1;
}

.doctor-details h1 {
    font-size: 28px;
    color: #1e5ba8;
    margin-bottom: 10px;
}

.doctor-details .specialty {
    font-size: 18px;
    color: #27ae60;
    font-weight: 600;
    margin-bottom: 15px;
}

.doctor-details .rating {
    color: #f1c40f;
    font-size: 16px;
    margin-bottom: 10px;
}

.doctor-details .rating .review-count {
    color: #666;
}

.doctor-details .experience {
    color: #555;
    font-size: 16px;
}

.doctor-details .experience i {
    color: #1e5ba8;
    margin-right: 8px;
}

.clinic-info p {
    margin-bottom: 8px;
}

.clinic-info i {
    color: #1e5ba8;
    margin-right: 8px;
}

/* Schedule Summary */
.schedule-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.schedule-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.schedule-item .day {
    font-weight: 600;
    color: #333;
}

.schedule-item .time {
    color: #1e5ba8;
    font-weight: 500;
}

.loading-schedule {
    grid-column: 1 / -1;
    text-align: center;
    color: #666;
    padding: 20px;
}

/* Booking Schedule Card */
.booking-schedule-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.schedule-hint {
    color: #666;
    margin-bottom: 20px;
    font-size: 14px;
}

.hint-available {
    background: #d4edda;
    color: #155724;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: 500;
}

/* Week Navigation */
.week-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 10px 0;
}

.week-nav-btn {
    background: #1e5ba8;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.week-nav-btn:hover {
    background: #164a8a;
    transform: translateY(-1px);
}

.week-nav-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.week-label {
    font-weight: 600;
    color: #333;
    font-size: 16px;
}

/* Weekly Schedule Table */
.weekly-schedule-container {
    overflow-x: auto;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.weekly-schedule-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    min-width: 800px;
}

.weekly-schedule-table th,
.weekly-schedule-table td {
    border: 1px solid #e0e0e0;
    padding: 8px;
    text-align: center;
    vertical-align: middle;
}

.time-header {
    width: 80px;
    background: #1e5ba8;
    color: white;
    font-weight: 600;
}

.day-header {
    background: #1e5ba8;
    color: white;
    min-width: 90px;
}

.day-header .day-name {
    font-weight: 600;
    font-size: 14px;
}

.day-header .day-date {
    font-size: 12px;
    opacity: 0.9;
    margin-top: 2px;
}

.day-header.today {
    background: #27ae60;
}

.session-header {
    background: #f0f7ff;
}

.session-header td {
    font-weight: 600;
    color: #1e5ba8;
    text-align: left;
    padding: 10px 15px;
}

.session-header i {
    margin-right: 8px;
}

.time-cell {
    background: #f8f9fa;
    font-weight: 500;
    color: #333;
    font-size: 13px;
}

/* Slot cells */
.slot-cell {
    padding: 6px;
    min-height: 40px;
    cursor: default;
    transition: all 0.2s;
}

.slot-cell.available {
    background: #d4edda;
    cursor: pointer;
}

.slot-cell.available:hover {
    background: #28a745;
    color: white;
    transform: scale(1.02);
}

.slot-cell.booked {
    background: #f8d7da;
    color: #721c24;
}

.slot-cell.past {
    background: #e9ecef;
    color: #6c757d;
}

.slot-cell.not-working {
    background: #f5f5f5;
    color: #999;
}

.slot-cell .slot-status {
    font-size: 11px;
    display: block;
}

.slot-cell.available .slot-status {
    color: #155724;
}

.slot-cell.available:hover .slot-status {
    color: white;
}

/* Legend */
.slots-legend {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    padding-top: 15px;
    border-top: 1px solid #ddd;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #666;
}

.slot-indicator {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.slot-indicator.available {
    background: #d4edda;
    border: 1px solid #c3e6cb;
}

.slot-indicator.booked {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
}

.slot-indicator.past {
    background: #e9ecef;
    border: 1px solid #dee2e6;
}

.slot-indicator.not-working {
    background: #f5f5f5;
    border: 1px solid #ddd;
}

@media (max-width: 768px) {
    .doctor-info-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .doctor-avatar-large {
        width: 150px;
        height: 150px;
    }
    
    .week-navigation {
        flex-direction: column;
        gap: 10px;
    }
    
    .week-nav-btn {
        width: 100%;
        justify-content: center;
    }
    
    .slots-legend {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorId = {{ $doctor['id'] }};
    const API_BASE = '/api';
    
    // Current week offset (0 = this week, 1 = next week, etc.)
    let weekOffset = 0;
    
    // Store doctor data
    let doctorData = {
        id: doctorId,
        specialization_id: null,
        full_name: '{{ $doctor['name'] }}'
    };
    
    // Store work schedule
    let workSchedule = {};
    
    // Store booked slots
    let bookedSlots = {};
    
    // Time slots configuration - khoảng cách 1 tiếng
    const morningSlots = ['08:00', '09:00', '10:00', '11:00'];
    const afternoonSlots = ['13:00', '14:00', '15:00', '16:00'];
    
    const dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    
    // Fetch doctor data from API
    fetch(`${API_BASE}/public/doctors/${doctorId}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.id) {
                doctorData = data;
                updateDoctorInfo(data);
            }
        })
        .catch(error => {
            console.log('Using default doctor data');
        });
    
    // Fetch work schedule
    fetch(`${API_BASE}/public/doctors/${doctorId}/work-schedule`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data) {
                // Convert to lookup object
                result.data.forEach(schedule => {
                    workSchedule[schedule.day_of_week] = schedule;
                });
                renderWorkScheduleSummary(result.data);
                loadWeekSchedule();
            }
        })
        .catch(error => {
            console.error('Error loading work schedule:', error);
            document.getElementById('doctor-schedule').innerHTML = '<p>Không thể tải lịch làm việc</p>';
        });
    
    function updateDoctorInfo(doctor) {
        if (doctor.full_name) {
            const degree = doctor.degree ? doctor.degree + ' ' : '';
            document.getElementById('doctor-name').textContent = degree + doctor.full_name;
        }
        if (doctor.avatar_url) {
            document.getElementById('doctor-avatar').src = doctor.avatar_url;
        }
        if (doctor.specialization && doctor.specialization.name) {
            document.getElementById('doctor-specialty').textContent = doctor.specialization.name;
        }
        if (doctor.rating_avg !== undefined) {
            document.getElementById('doctor-rating').textContent = parseFloat(doctor.rating_avg).toFixed(1);
        }
        if (doctor.experience !== undefined) {
            document.getElementById('doctor-experience').textContent = doctor.experience;
        }
        if (doctor.description) {
            document.getElementById('doctor-bio').innerHTML = '<p>' + doctor.description + '</p>';
        }
        if (doctor.clinic) {
            document.getElementById('doctor-clinic').textContent = doctor.clinic.name;
            document.getElementById('doctor-address').textContent = doctor.clinic.address;
        }
    }
    
    function renderWorkScheduleSummary(schedules) {
        const container = document.getElementById('doctor-schedule');
        
        if (!schedules || schedules.length === 0) {
            container.innerHTML = '<p>Chưa có lịch làm việc</p>';
            return;
        }
        
        const dayNames = {
            'Monday': 'Thứ 2',
            'Tuesday': 'Thứ 3',
            'Wednesday': 'Thứ 4',
            'Thursday': 'Thứ 5',
            'Friday': 'Thứ 6',
            'Saturday': 'Thứ 7',
            'Sunday': 'Chủ nhật'
        };
        
        // Group consecutive days with same schedule
        let groupedSchedules = [];
        let currentGroup = null;
        
        schedules.forEach(schedule => {
            const timeRange = formatTime(schedule.start_time) + ' - ' + formatTime(schedule.end_time);
            
            if (currentGroup && currentGroup.timeRange === timeRange) {
                currentGroup.days.push(schedule.day_of_week);
            } else {
                if (currentGroup) {
                    groupedSchedules.push(currentGroup);
                }
                currentGroup = {
                    days: [schedule.day_of_week],
                    timeRange: timeRange
                };
            }
        });
        
        if (currentGroup) {
            groupedSchedules.push(currentGroup);
        }
        
        let html = '';
        groupedSchedules.forEach(group => {
            const dayLabel = formatDayGroup(group.days, dayNames);
            html += `
                <div class="schedule-item">
                    <span class="day">${dayLabel}</span>
                    <span class="time">${group.timeRange}</span>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    function formatTime(timeStr) {
        if (!timeStr) return '';
        return timeStr.substring(0, 5);
    }
    
    function formatDayGroup(days, dayNames) {
        if (days.length === 1) {
            return dayNames[days[0]];
        }
        
        const indices = days.map(d => dayOrder.indexOf(d)).sort((a, b) => a - b);
        
        let isConsecutive = true;
        for (let i = 1; i < indices.length; i++) {
            if (indices[i] - indices[i-1] !== 1) {
                isConsecutive = false;
                break;
            }
        }
        
        if (isConsecutive && days.length > 2) {
            return dayNames[days[0]] + ' - ' + dayNames[days[days.length - 1]];
        } else {
            return days.map(d => dayNames[d]).join(', ');
        }
    }
    
    function getWeekDates(offset = 0) {
        const today = new Date();
        const currentDay = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
        
        // Calculate Monday of current week
        const monday = new Date(today);
        const daysToMonday = currentDay === 0 ? -6 : 1 - currentDay;
        monday.setDate(today.getDate() + daysToMonday + (offset * 7));
        
        const dates = {};
        dayOrder.forEach((day, index) => {
            const date = new Date(monday);
            date.setDate(monday.getDate() + index);
            dates[day] = date;
        });
        
        return dates;
    }
    
    function formatDateShort(date) {
        return `${date.getDate()}/${date.getMonth() + 1}`;
    }
    
    function formatDateISO(date) {
        return date.toISOString().split('T')[0];
    }
    
    async function loadWeekSchedule() {
        const dates = getWeekDates(weekOffset);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Update week label
        const firstDate = dates['Monday'];
        const lastDate = dates['Sunday'];
        const weekLabel = document.getElementById('weekLabel');
        
        if (weekOffset === 0) {
            weekLabel.textContent = `Tuần này (${formatDateShort(firstDate)} - ${formatDateShort(lastDate)})`;
        } else if (weekOffset === 1) {
            weekLabel.textContent = `Tuần sau (${formatDateShort(firstDate)} - ${formatDateShort(lastDate)})`;
        } else {
            weekLabel.textContent = `${formatDateShort(firstDate)} - ${formatDateShort(lastDate)}`;
        }
        
        // Disable prev button if showing current week
        document.getElementById('prevWeekBtn').disabled = weekOffset <= 0;
        
        // Update header dates and highlight today
        dayOrder.forEach(day => {
            const dateEl = document.getElementById(`date-${day}`);
            const headerEl = dateEl.closest('.day-header');
            const date = dates[day];
            
            dateEl.textContent = formatDateShort(date);
            
            // Highlight today
            if (date.toDateString() === new Date().toDateString()) {
                headerEl.classList.add('today');
            } else {
                headerEl.classList.remove('today');
            }
        });
        
        // Fetch booked slots for the week
        const startDate = formatDateISO(dates['Monday']);
        const endDate = formatDateISO(dates['Sunday']);
        
        try {
            const response = await fetch(`${API_BASE}/public/doctors/${doctorId}/booked-slots?start_date=${startDate}&end_date=${endDate}`);
            const result = await response.json();
            
            console.log('Booked slots API response:', result);
            
            if (result.success && result.data) {
                // Convert to lookup object
                bookedSlots = {};
                result.data.forEach(slot => {
                    const key = `${slot.appointment_date}_${slot.start_time.substring(0, 5)}`;
                    bookedSlots[key] = true;
                    console.log('Added booked slot:', key);
                });
                console.log('Total booked slots:', Object.keys(bookedSlots).length, bookedSlots);
            }
        } catch (error) {
            console.error('Error loading booked slots:', error);
        }
        
        // Build schedule table
        renderScheduleTable(dates, today);
    }
    
    function renderScheduleTable(dates, today) {
        const tbody = document.getElementById('schedule-body');
        let html = '';
        
        // Morning header
        html += `<tr class="session-header"><td colspan="8"><i class="fas fa-sun"></i> Buổi sáng (08:00 - 12:00)</td></tr>`;
        
        // Morning slots
        morningSlots.forEach(time => {
            html += renderTimeRow(time, dates, today);
        });
        
        // Afternoon header
        html += `<tr class="session-header"><td colspan="8"><i class="fas fa-cloud-sun"></i> Buổi chiều (13:00 - 17:00)</td></tr>`;
        
        // Afternoon slots
        afternoonSlots.forEach(time => {
            html += renderTimeRow(time, dates, today);
        });
        
        tbody.innerHTML = html;
    }
    
    function renderTimeRow(time, dates, today) {
        const now = new Date();
        let html = `<tr><td class="time-cell">${time}</td>`;
        
        dayOrder.forEach(day => {
            const date = dates[day];
            const dateStr = formatDateISO(date);
            const schedule = workSchedule[day];
            
            let status = 'not-working';
            let statusText = 'Nghỉ';
            let clickable = false;
            
            // Check if doctor works this day
            if (schedule && schedule.is_available) {
                const startTime = schedule.start_time.substring(0, 5);
                const endTime = schedule.end_time.substring(0, 5);
                
                // Check if this time slot is within working hours
                if (time >= startTime && time < endTime) {
                    // Check break time
                    const breakStart = schedule.break_start_time ? schedule.break_start_time.substring(0, 5) : null;
                    const breakEnd = schedule.break_end_time ? schedule.break_end_time.substring(0, 5) : null;
                    
                    if (breakStart && breakEnd && time >= breakStart && time < breakEnd) {
                        status = 'not-working';
                        statusText = 'Nghỉ trưa';
                    } else {
                        // Check if past
                        const slotDateTime = new Date(dateStr + 'T' + time + ':00');
                        if (slotDateTime < now) {
                            status = 'past';
                            statusText = 'Đã qua';
                        } else {
                            // Check if booked
                            const key = `${dateStr}_${time}`;
                            if (bookedSlots[key]) {
                                status = 'booked';
                                statusText = 'Đã đặt';
                            } else {
                                status = 'available';
                                statusText = 'Trống';
                                clickable = true;
                            }
                        }
                    }
                }
            }
            
            html += `<td class="slot-cell ${status}" 
                         ${clickable ? `onclick="bookSlot('${dateStr}', '${time}')" title="Click để đặt lịch"` : `title="${statusText}"`}>
                         <span class="slot-status">${statusText}</span>
                     </td>`;
        });
        
        html += '</tr>';
        return html;
    }
    
    // Make functions available globally
    window.changeWeek = function(direction) {
        weekOffset += direction;
        if (weekOffset < 0) weekOffset = 0;
        loadWeekSchedule();
    };
    
    window.bookSlot = function(date, time) {
        const specId = doctorData.specialization_id || '';
        const url = `/dat-lich/bieu-mau?doctor=${doctorId}&specialization=${specId}&date=${date}&time=${time}`;
        window.location.href = url;
    };
});
</script>
@endsection
