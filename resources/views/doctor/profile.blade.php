<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
  <title>Trang C� Nh�n B�c S?</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      background: #f0f5fd;
      overflow: hidden;
    }

    .sidebar {
      background: #fff;
      width: 260px;
      display: flex;
      flex-direction: column;
      height: 100%;
      padding: 25px 20px;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #f0f5fd;
    }

    .sidebar-header i {
      font-size: 32px;
      color: #4a69bd;
    }

    .sidebar-header h2 {
      color: #2c3e50;
      font-size: 20px;
      font-weight: 700;
    }

    .nav-menu {
      display: flex;
      flex-direction: column;
      gap: 8px;
      flex: 1;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      color: #5a6c7d;
      cursor: pointer;
      border-radius: 12px;
      transition: all 0.3s ease;
      font-size: 15px;
      font-weight: 500;
    }

    .nav-item i {
      font-size: 20px;
    }

    .nav-item:hover,
    .nav-item.active {
      background: #4a69bd;
      color: #fff;
    }

    .nav-item {
      position: relative;
    }

    .nav-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      background: #e74c3c;
      color: #fff;
      min-width: 18px;
      height: 18px;
      border-radius: 50%;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }

    .logout-btn {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      color: #e74c3c;
      cursor: pointer;
      border-radius: 12px;
      transition: all 0.3s ease;
      font-size: 15px;
      font-weight: 500;
      margin-top: auto;
    }

    .logout-btn i {
      font-size: 20px;
    }

    .logout-btn:hover {
      background: #ffe5e5;
    }

    .main-content {
      flex: 1;
      padding: 25px;
      overflow-y: auto;
    }

    .top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;
      background: #fff;
      padding: 20px 25px;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .page-title {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .page-title i {
      font-size: 28px;
      color: #4a69bd;
    }

    .page-title h1 {
      font-size: 24px;
      color: #2c3e50;
      font-weight: 700;
    }

    .top-actions {
      display: flex;
      gap: 12px;
    }

    .icon-btn {
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #4a69bd;
      color: #fff;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
    }

    .icon-btn:hover {
      background: #3c5aa6;
      transform: translateY(-2px);
    }

    .icon-btn i {
      font-size: 20px;
    }

    .badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #e74c3c;
      color: #fff;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      font-size: 11px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }

    .content-section {
      display: none;
    }

    .content-section.active {
      display: block;
    }

    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 25px;
    }

    .section-card {
      background: #fff;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f5fd;
    }

    .section-title {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      font-weight: 700;
      color: #2c3e50;
    }

    .section-title i {
      color: #4a69bd;
    }

    .btn-primary {
      padding: 10px 20px;
      background: #4a69bd;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary:hover {
      background: #3c5aa6;
      transform: translateY(-2px);
    }

    .profile-info {
      display: flex;
      gap: 25px;
      align-items: flex-start;
    }

    .profile-avatar {
      position: relative;
    }

    .profile-avatar img {
      width: 150px;
      height: 150px;
      border-radius: 20px;
      object-fit: cover;
      background: #e0e0e0;
    }

    .edit-avatar {
      position: absolute;
      bottom: 175px;
      right: 120px;
      width: 25px;
      height: 25px;
      background: #4a69bd;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .edit-avatar:hover {
      background: #3c5aa6;
    }

    .profile-details {
      flex: 1;
    }

    .info-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 15px;
    }

    .info-field {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .info-field label {
      font-size: 13px;
      color: #7f8c8d;
      font-weight: 600;
    }

    .info-field input,
    .info-field select,
    .info-field textarea {
      padding: 12px;
      border: 2px solid #e8eaf6;
      border-radius: 10px;
      font-size: 14px;
      color: #2c3e50;
      outline: none;
      transition: all 0.3s ease;
    }

    .info-field input:focus,
    .info-field select:focus,
    .info-field textarea:focus {
      border-color: #4a69bd;
    }

    .notification-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .notification-item {
      display: flex;
      gap: 15px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .notification-item:hover {
      background: #e8eaf6;
    }

    .notification-icon {
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      font-size: 20px;
    }

    .notif-schedule {
      background: #d4edda;
      color: #155724;
    }

    .notif-change {
      background: #fff3cd;
      color: #856404;
    }

    .notif-system {
      background: #d1ecf1;
      color: #0c5460;
    }

    .notification-content {
      flex: 1;
    }

    .notification-content h4 {
      font-size: 14px;
      color: #2c3e50;
      margin-bottom: 4px;
      font-weight: 600;
    }

    .notification-content p {
      font-size: 13px;
      color: #7f8c8d;
    }

    .notification-time {
      font-size: 12px;
      color: #95a5a6;
      white-space: nowrap;
    }

    .full-width {
      grid-column: 1 / -1;
    }

    .dw-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 15px;
      margin-bottom: 15px;
    }

    .dw-controls .left {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .dw-date {
      padding: 12px 14px;
      border: 2px solid #e8eaf6;
      border-radius: 12px;
      font-size: 14px;
      background: #fff;
    }

    .dw-btn {
      padding: 10px 16px;
      background: #4a69bd;
      color: #fff;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
    }

    .dw-btn:hover {
      background: #3c5aa6;
      transform: translateY(-2px);
    }

    .dw-cal-wrap {
      border: 1px solid #e8eaf6;
      border-radius: 12px;
      overflow: hidden;
      background: #fff;
    }

    .dw-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 1200px;
    }

    .dw-table thead {
      background: #4a69bd;
      color: #fff;
    }

    .dw-table th {
      padding: 16px;
      text-align: center;
      font-weight: 700;
      font-size: 15px;
    }

    .dw-table th:first-child {
      text-align: left;
      min-width: 180px;
    }

    .dw-table th {
      min-width: 220px;
    }

    .dw-table td {
      padding: 16px;
      border-bottom: 1px solid #eef2ff;
      border-right: 1px solid #eef2ff;
      vertical-align: top;
      min-height: 120px;
    }

    .dw-table td:last-child {
      border-right: none;
    }

    .dw-slot {
      background: #eef2ff;
      color: #1f2a44;
      font-weight: 700;
      padding: 18px;
      font-size: 15px;
    }

    .dw-slot .rng {
      font-size: 12px;
      opacity: 0.85;
      font-weight: 500;
      margin-top: 6px;
    }

    .dw-card {
      border-left: 4px solid #2196f3;
      background: #eaf3ff;
      border-radius: 10px;
      padding: 14px;
      margin-bottom: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .dw-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .dw-card.pending {
      border-left-color: #fbc02d;
      background: #fff9e6;
    }

    .dw-card.completed {
      border-left-color: #4caf50;
      background: #e8f5e9;
    }

    .dw-card.cancelled {
      border-left-color: #f44336;
      background: #ffebee;
    }

    .dw-card.confirmed {
      border-left-color: #2196f3;
      background: #eaf3ff;
    }

    /* Schedule status labels */
    .schedule-status-label {
      position: absolute;
      bottom: 8px;
      right: 8px;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .schedule-status-label.ended {
      background: #e0e0e0;
      color: #666;
    }

    .schedule-status-label.ongoing {
      background: #4caf50;
      color: #fff;
      animation: pulse 2s infinite;
    }

    .schedule-status-label.upcoming {
      background: #ff9800;
      color: #fff;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.7;
      }
    }

    .dw-card {
      position: relative;
      cursor: pointer;
    }

    .dw-card h4 {
      margin: 0 0 10px 0;
      font-size: 15px;
      font-weight: 700;
      color: #2c3e50;
    }

    .dw-meta {
      font-size: 13px;
      color: #556;
      line-height: 1.8;
    }

    .dw-meta div {
      margin-bottom: 4px;
    }

    .dw-meta strong {
      color: #2c3e50;
      margin-right: 4px;
    }

    .dw-empty {
      color: #999;
      font-size: 13px;
      font-style: italic;
      text-align: center;
      padding: 20px;
    }

    .patient-notes-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .patient-note-card {
      background: #fff;
      border: 2px solid #e8eaf6;
      border-radius: 12px;
      padding: 20px;
      transition: all 0.3s ease;
    }

    .patient-note-card:hover {
      border-color: #4a69bd;
      box-shadow: 0 4px 12px rgba(74, 105, 189, 0.1);
    }

    .note-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 15px;
    }

    .note-patient-info h3 {
      color: #2c3e50;
      font-size: 18px;
      margin-bottom: 5px;
    }

    .note-patient-info p {
      color: #7f8c8d;
      font-size: 13px;
    }

    .note-date {
      background: #4a69bd;
      color: #fff;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
    }

    .note-content {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    .note-content h4 {
      color: #2c3e50;
      font-size: 14px;
      margin-bottom: 8px;
    }

    .note-content p {
      color: #5a6c7d;
      line-height: 1.6;
      font-size: 14px;
    }

    .note-actions {
      display: flex;
      gap: 10px;
    }

    .btn-edit,
    .btn-delete {
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.3s ease;
    }

    .btn-edit {
      background: #d4edda;
      color: #155724;
    }

    .btn-edit:hover {
      background: #c3e6cb;
    }

    .btn-delete {
      background: #f8d7da;
      color: #721c24;
    }

    .btn-delete:hover {
      background: #f5c6cb;
    }

    .forum-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .forum-thread {
      background: #fff;
      border: 2px solid #e8eaf6;
      border-radius: 12px;
      padding: 20px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .forum-thread:hover {
      border-color: #4a69bd;
      box-shadow: 0 4px 12px rgba(74, 105, 189, 0.1);
    }

    .thread-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 12px;
    }

    .thread-title {
      color: #2c3e50;
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .thread-meta {
      display: flex;
      gap: 15px;
      font-size: 13px;
      color: #7f8c8d;
    }

    .thread-meta span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .thread-content {
      color: #5a6c7d;
      line-height: 1.6;
      margin-bottom: 15px;
    }

    .thread-tags {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .tag {
      background: #e8eaf6;
      color: #4a69bd;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .forum-bridge {
      background: #eef2ff;
      border: 1px dashed #4a69bd;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 20px;
      display: flex;
      gap: 12px;
      align-items: center;
      color: #3c5aa6;
    }

    .forum-bridge i {
      font-size: 24px;
    }

    .tab-buttons {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .tab-btn {
      padding: 10px 18px;
      border-radius: 999px;
      border: 1px solid #dfe7ff;
      background: #fff;
      color: #4a69bd;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .tab-btn.active,
    .tab-btn:hover {
      background: #4a69bd;
      color: #fff;
      box-shadow: 0 10px 20px rgba(74, 105, 189, 0.2);
    }

    .forum-post {
      background: #fff;
      border-radius: 16px;
      padding: 20px;
      border: 1px solid #e8eaf6;
      margin-bottom: 20px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .forum-posts {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .forum-new-question {
      background: #f8f9ff;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      border: 2px dashed #dfe6ff;
    }

    .forum-form-group {
      margin-bottom: 15px;
    }

    .forum-form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #2c3e50;
    }

    .forum-form-group input,
    .forum-form-group textarea {
      width: 100%;
      border: 2px solid #e0e7ff;
      border-radius: 10px;
      padding: 12px;
      font-size: 14px;
      font-family: inherit;
      resize: vertical;
    }

    .forum-form-group textarea {
      min-height: 120px;
    }

    .forum-empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #7f8c8d;
      border: 2px dashed #dfe6ff;
      border-radius: 12px;
      background: #fff;
    }

    .forum-empty-state i {
      font-size: 48px;
      margin-bottom: 15px;
      display: inline-block;
      color: #4a69bd;
    }

    .forum-answer {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      border-left: 4px solid #4a69bd;
      margin-top: 15px;
    }

    .forum-reply-form {
      margin-top: 15px;
      background: #fff;
      border-radius: 12px;
      border: 1px solid #dfe6ff;
      padding: 15px;
    }

    .forum-reply-form textarea {
      width: 100%;
      border: 2px solid #e0e7ff;
      border-radius: 10px;
      padding: 12px;
      min-height: 100px;
      font-family: inherit;
      resize: vertical;
      margin-bottom: 12px;
    }

    .reply-form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .appointment-status {
      padding: 6px 16px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 600;
    }

    .status-confirmed {
      background: #d4edda;
      color: #155724;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .post-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .post-author {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .author-avatar {
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: #4a69bd;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 18px;
    }

    .post-content {
      color: #5a6c7d;
      line-height: 1.7;
      margin-bottom: 16px;
    }

    .post-stats {
      display: flex;
      gap: 16px;
      font-size: 13px;
      color: #95a5a6;
    }

    .post-stats span {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .settings-grid {
      display: grid;
      gap: 20px;
    }

    .settings-section {
      background: #fff;
      padding: 25px;
      border-radius: 16px;
      border: 2px solid #e8eaf6;
    }

    .settings-section h3 {
      color: #2c3e50;
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .settings-section h3 i {
      color: #4a69bd;
    }

    #feedbackTextarea:focus {
      border-color: #4a69bd;
      box-shadow: 0 0 0 3px rgba(74, 105, 189, 0.1);
    }

    .setting-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px solid #f0f5fd;
    }

    .setting-item:last-child {
      border-bottom: none;
    }

    .setting-info h4 {
      color: #2c3e50;
      font-size: 15px;
      margin-bottom: 5px;
    }

    .setting-info p {
      color: #7f8c8d;
      font-size: 13px;
    }

    .toggle-switch {
      position: relative;
      width: 50px;
      height: 26px;
      background: #ccc;
      border-radius: 13px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .toggle-switch.active {
      background: #4a69bd;
    }

    .toggle-switch::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      background: #fff;
      border-radius: 50%;
      top: 3px;
      left: 3px;
      transition: all 0.3s ease;
    }

    .toggle-switch.active::after {
      left: 27px;
    }

    /* Toast notification */
    .toast-notification {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: linear-gradient(135deg, #4CAF50, #45a049);
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 5px 20px rgba(76, 175, 80, 0.3);
      z-index: 9999;
      transform: translateX(120%);
      transition: transform 0.3s ease;
    }

    .toast-notification.show {
      transform: translateX(0);
    }

    .toast-notification.toast-error {
      background: linear-gradient(135deg, #f44336, #e53935);
      box-shadow: 0 5px 20px rgba(244, 67, 54, 0.3);
    }

    .toast-notification.toast-info {
      background: linear-gradient(135deg, #2196F3, #1976D2);
      box-shadow: 0 5px 20px rgba(33, 150, 243, 0.3);
    }

    /* Dark mode styles */
    body.dark-mode {
      background-color: #1a1a2e;
      color: #e0e0e0;
    }

    body.dark-mode .dashboard-container {
      background-color: #16213e;
    }

    body.dark-mode .sidebar {
      background: linear-gradient(180deg, #0f3460, #1a1a2e);
    }

    body.dark-mode .main-content {
      background-color: #1a1a2e;
    }

    body.dark-mode .content-section {
      background-color: #16213e;
    }

    body.dark-mode .settings-section,
    body.dark-mode .stat-card,
    body.dark-mode .info-card,
    body.dark-mode .activity-item,
    body.dark-mode .notification-item {
      background-color: #0f3460;
      border-color: #1f4287;
    }

    body.dark-mode .setting-item {
      border-color: #1f4287;
    }

    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode h3,
    body.dark-mode h4,
    body.dark-mode h5 {
      color: #e0e0e0;
    }

    body.dark-mode p,
    body.dark-mode span,
    body.dark-mode label {
      color: #b0b0b0;
    }

    body.dark-mode input,
    body.dark-mode select,
    body.dark-mode textarea {
      background-color: #16213e;
      border-color: #1f4287;
      color: #e0e0e0;
    }

    body.dark-mode .modal-content {
      background-color: #16213e;
      border: 1px solid #1f4287;
    }

    /* Autocomplete suggestions */
    .autocomplete-suggestions {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border: 2px solid #e8eaf6;
      border-top: none;
      border-radius: 0 0 10px 10px;
      max-height: 200px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .autocomplete-suggestions.show {
      display: block;
    }

    .autocomplete-item {
      padding: 12px 15px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.2s;
    }

    .autocomplete-item:last-child {
      border-bottom: none;
    }

    .autocomplete-item:hover {
      background-color: #e3f2fd;
    }

    .autocomplete-item .patient-name {
      font-weight: 600;
      color: #333;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .autocomplete-item .status-badge {
      font-size: 10px;
      padding: 2px 6px;
      border-radius: 4px;
      font-weight: 500;
    }

    .autocomplete-item .status-badge.in-progress {
      background: #fff3cd;
      color: #856404;
    }

    .autocomplete-item .status-badge.completed {
      background: #d4edda;
      color: #155724;
    }

    .autocomplete-item .patient-info {
      font-size: 12px;
      color: #666;
    }

    .autocomplete-item .appointment-date {
      font-size: 11px;
      color: #5c6bc0;
      background: #e8eaf6;
      padding: 4px 8px;
      border-radius: 4px;
    }

    .autocomplete-loading {
      padding: 15px;
      text-align: center;
      color: #888;
    }

    /* Login history styles */
    .login-history-list {
      max-height: 400px;
      overflow-y: auto;
    }

    .login-history-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px;
      border-bottom: 1px solid #e8eaf6;
      transition: background-color 0.3s ease;
    }

    .login-history-item:hover {
      background-color: #f5f5f5;
    }

    .login-history-item:last-child {
      border-bottom: none;
    }

    .history-device {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .history-device i {
      font-size: 24px;
      color: #5c6bc0;
    }

    .history-device strong {
      display: block;
      color: #333;
      font-weight: 600;
    }

    .history-device small {
      color: #888;
      font-size: 12px;
    }

    .history-time {
      text-align: right;
    }

    .history-time span {
      display: block;
      color: #333;
      font-weight: 500;
    }

    .history-time small {
      color: #888;
      font-size: 12px;
    }

    body.dark-mode .login-history-item {
      border-color: #1f4287;
    }

    body.dark-mode .login-history-item:hover {
      background-color: #0f3460;
    }

    body.dark-mode .history-device strong,
    body.dark-mode .history-time span {
      color: #e0e0e0;
    }

    /* Spin animation */
    @keyframes spin {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      overflow: auto;
    }

    .modal.active {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background-color: #fff;
      margin: auto;
      padding: 0;
      border-radius: 16px;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 30px;
      border-bottom: 2px solid #f0f5fd;
      background: linear-gradient(135deg, #4a69bd 0%, #3c5aa6 100%);
      color: #fff;
      border-radius: 16px 16px 0 0;
    }

    .modal-header h3 {
      font-size: 20px;
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .close-modal {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      font-size: 28px;
      color: #fff;
      cursor: pointer;
      transition: all 0.3s;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .close-modal:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: rotate(90deg);
    }

    .modal-body {
      padding: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      color: #2c3e50;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 12px;
      border: 2px solid #e8eaf6;
      border-radius: 10px;
      font-size: 14px;
      color: #2c3e50;
      outline: none;
      transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: #4a69bd;
    }

    .form-group textarea {
      min-height: 120px;
      resize: vertical;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      padding: 20px 30px;
      border-top: 2px solid #f0f5fd;
    }

    .btn-secondary {
      padding: 10px 20px;
      background: #95a5a6;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      background: #7f8c8d;
    }

    .tag-input-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      padding: 8px;
      border: 2px solid #e8eaf6;
      border-radius: 10px;
      min-height: 46px;
    }

    .tag-input-wrapper input {
      border: none;
      outline: none;
      flex: 1;
      min-width: 120px;
      padding: 4px;
    }

    .tag-item {
      background: #4a69bd;
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .tag-item .remove-tag {
      cursor: pointer;
      font-weight: bold;
    }

    .history-table {
      width: 100%;
      border-collapse: collapse;
    }

    .history-table th {
      background: #f8f9fa;
      padding: 12px;
      text-align: left;
      font-size: 13px;
      color: #7f8c8d;
      font-weight: 600;
      border-bottom: 2px solid #e8eaf6;
    }

    .history-table td {
      padding: 12px;
      border-bottom: 1px solid #f0f5fd;
      font-size: 14px;
      color: #2c3e50;
    }

    .history-table tr:hover {
      background: #f8f9fa;
    }

    .status-login {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-success {
      background: #d4edda;
      color: #155724;
    }

    .status-failed {
      background: #f8d7da;
      color: #721c24;
    }

    .notification-item {
      position: relative;
    }

    .unread-badge {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      width: 10px;
      height: 10px;
      background: #e74c3c;
      border-radius: 50%;
      display: none;
    }

    .notification-item.unread .unread-badge {
      display: block;
    }

    .notification-item.unread {
      background: #fff3e6;
      border-left: 3px solid #ff9800;
    }

    /* Notification drag effect */
    .notification-item {
      transition: all 0.3s ease;
      user-select: none;
    }

    .notification-item.dragging {
      opacity: 0.8;
      transform: scale(1.02);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      cursor: grabbing;
    }

    .notification-item.delete-preview {
      background: #ffebee !important;
      border-left-color: #f44336 !important;
    }

    .notification-actions-bar {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-bottom: 15px;
    }

    .notification-actions-bar .btn-action {
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.3s ease;
    }

    .notification-actions-bar .btn-read-all {
      background: #d4edda;
      color: #155724;
    }

    .notification-actions-bar .btn-read-all:hover {
      background: #c3e6cb;
    }

    .notification-actions-bar .btn-delete-all {
      background: #f8d7da;
      color: #721c24;
    }

    .notification-actions-bar .btn-delete-all:hover {
      background: #f5c6cb;
    }

    /* Appointment detail modal */
    .appointment-detail-modal .detail-row {
      display: flex;
      padding: 12px 0;
      border-bottom: 1px solid #f0f5fd;
    }

    .appointment-detail-modal .detail-row:last-child {
      border-bottom: none;
    }

    .appointment-detail-modal .detail-label {
      width: 140px;
      color: #7f8c8d;
      font-weight: 600;
      font-size: 14px;
    }

    .appointment-detail-modal .detail-value {
      flex: 1;
      color: #2c3e50;
      font-size: 14px;
    }

    .appointment-detail-modal .amount-due {
      font-size: 18px;
      font-weight: 700;
      color: #e74c3c;
    }

    .appointment-detail-modal .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .appointment-detail-modal .status-paid {
      background: #d4edda;
      color: #155724;
    }

    .appointment-detail-modal .status-unpaid {
      background: #fff3cd;
      color: #856404;
    }

    .appointment-actions {
      display: flex;
      gap: 12px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid #f0f5fd;
    }

    .btn-complete {
      flex: 1;
      padding: 12px 20px;
      background: #27ae60;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.3s ease;
    }

    .btn-complete:hover {
      background: #219a52;
    }

    .btn-cancel-appointment {
      flex: 1;
      padding: 12px 20px;
      background: #e74c3c;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.3s ease;
    }

    .btn-cancel-appointment:hover {
      background: #c0392b;
    }

    .cancel-reason-form {
      margin-top: 15px;
      padding: 15px;
      background: #fff3e6;
      border-radius: 10px;
      display: none;
    }

    .cancel-reason-form.active {
      display: block;
    }

    .cancel-reason-form label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #2c3e50;
    }

    .cancel-reason-form select,
    .cancel-reason-form input {
      width: 100%;
      padding: 10px;
      border: 2px solid #e8eaf6;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 10px;
    }

    .notif-like {
      background: #ffe0e6;
      color: #c0392b;
    }

    .notif-comment {
      background: #e3f2fd;
      color: #1565c0;
    }

    .notif-view {
      background: #e8f5e9;
      color: #2e7d32;
    }

    /* Chat styles */
    .chat-message {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .patient-message {
      flex-direction: row;
    }

    .doctor-message {
      flex-direction: row-reverse;
    }

    .message-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }

    .patient-message .message-avatar {
      background: #e3f2fd;
      color: #1976d2;
    }

    .doctor-message .message-avatar {
      background: #4a69bd;
      color: #fff;
    }

    .message-content {
      display: flex;
      flex-direction: column;
      max-width: 70%;
    }

    .doctor-message .message-content {
      align-items: flex-end;
    }

    .message-bubble {
      padding: 12px 16px;
      border-radius: 12px;
      word-wrap: break-word;
    }

    .patient-message .message-bubble {
      background: #fff;
      border: 2px solid #e8eaf6;
      border-radius: 12px 12px 12px 4px;
    }

    .doctor-message .message-bubble {
      background: #4a69bd;
      color: #fff;
      border-radius: 12px 12px 4px 12px;
    }

    .message-bubble p {
      margin: 0;
      font-size: 14px;
      line-height: 1.5;
    }

    .message-time {
      font-size: 11px;
      color: #95a5a6;
      margin-top: 4px;
      padding: 0 4px;
    }

    .quick-reply-btn {
      padding: 8px 14px;
      background: #f0f5fd;
      color: #4a69bd;
      border: 2px solid #e8eaf6;
      border-radius: 8px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }

    .quick-reply-btn:hover {
      background: #4a69bd;
      color: #fff;
      border-color: #4a69bd;
      transform: translateY(-2px);
    }

    .quick-reply-btn i {
      font-size: 16px;
    }

    #chatMessagesArea::-webkit-scrollbar {
      width: 8px;
    }

    #chatMessagesArea::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    #chatMessagesArea::-webkit-scrollbar-thumb {
      background: #4a69bd;
      border-radius: 10px;
    }

    #chatMessagesArea::-webkit-scrollbar-thumb:hover {
      background: #3c5aa6;
    }

    @media (max-width: 1200px) {
      .content-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        left: -260px;
        z-index: 1000;
      }

      .sidebar.active {
        left: 0;
      }

      .info-row {
        grid-template-columns: 1fr;
      }
    }

    /* Forum Post Card Styles */
    .forum-post-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
      margin-bottom: 24px;
      padding: 20px 24px;
      transition: box-shadow 0.2s;
      border: 1px solid #f0f0f0;
      position: relative;
    }

    .forum-post-card:hover {
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
      border-color: #b3c6ff;
    }

    .forum-post-header {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
    }

    .forum-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 14px;
      border: 2px solid #e0e7ff;
    }

    .forum-author h4 {
      margin: 0 0 2px 0;
      font-size: 1.1rem;
      color: #334155;
      font-weight: 600;
    }

    .forum-author small {
      color: #94a3b8;
      font-size: 0.92rem;
    }

    .forum-post-content h3 {
      margin: 0 0 6px 0;
      font-size: 1.15rem;
      color: #2563eb;
      font-weight: 600;
    }

    .forum-post-content p {
      margin: 0 0 8px 0;
      color: #334155;
      font-size: 1rem;
      line-height: 1.5;
    }

    .forum-post-stats {
      display: flex;
      gap: 18px;
      margin: 10px 0 0 0;
      color: #64748b;
      font-size: 0.98rem;
    }

    .forum-post-stats i {
      margin-right: 4px;
      color: #94a3b8;
    }

    .forum-post-card .forum-post-stats span {
      display: flex;
      align-items: center;
    }

    .empty-state {
      text-align: center;
      color: #b91c1c;
      font-size: 1.1rem;
      margin: 30px 0;
      padding: 20px 0;
      background: #fff0f0;
      border-radius: 8px;
    }

    .forum-bridge {
      background: #eef2ff;
      border: 1px dashed #4a69bd;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 20px;
      display: flex;
      gap: 12px;
      align-items: center;
      color: #3c5aa6;
    }

    .forum-bridge i {
      font-size: 24px;
    }

    .forum-new-question {
      background: #f8f9ff;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      border: 2px dashed #dfe6ff;
    }

    .forum-form-group {
      margin-bottom: 15px;
    }

    .forum-form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #2c3e50;
    }

    .forum-form-group input,
    .forum-form-group textarea {
      width: 100%;
      border: 2px solid #e0e7ff;
      border-radius: 10px;
      padding: 12px;
      font-size: 14px;
      font-family: inherit;
      resize: vertical;
    }

    .forum-form-group textarea {
      min-height: 120px;
    }

    .forum-empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #7f8c8d;
      border: 2px dashed #dfe6ff;
      border-radius: 12px;
      background: #fff;
    }

    .forum-empty-state i {
      font-size: 48px;
      margin-bottom: 15px;
      display: inline-block;
      color: #4a69bd;
    }

    .forum-answer {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      border-left: 4px solid #4a69bd;
      margin-top: 15px;
    }

    .forum-reply-form {
      margin-top: 15px;
      background: #fff;
      border-radius: 12px;
      border: 1px solid #dfe6ff;
      padding: 15px;
    }

    .forum-reply-form textarea {
      width: 100%;
      border: 2px solid #e0e7ff;
      border-radius: 10px;
      padding: 12px;
      min-height: 100px;
      font-family: inherit;
      resize: vertical;
      margin-bottom: 12px;
    }

    .reply-form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .forum-posts {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('frontend/img/logomau.jpg') }}" style="
            width: 280px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
          " />
    </div>

    <nav class="nav-menu">
      <div class="nav-item active" data-section="profile">
        <i class="ri-user-line"></i>
        <span>Th�ng tin c� nh�n</span>
      </div>
      <div class="nav-item" data-section="schedule">
        <i class="ri-calendar-line"></i>
        <span>L?ch l�m vi?c</span>
      </div>
      <div class="nav-item" data-section="notifications">
        <i class="ri-notification-3-line"></i>
        <span>Th�ng b�o</span>
        <span class="nav-badge" id="navNotifBadge" style="display: none;">0</span>
      </div>
      <div class="nav-item" data-section="notes">
        <i class="ri-file-text-line"></i>
        <span>Ghi ch� kh�m b?nh</span>
      </div>
      <div class="nav-item" data-section="forum">
        <i class="ri-question-answer-line"></i>
        <span>Ph?n h?i ng�?i d�ng</span>
      </div>
      <div class="nav-item" data-section="settings">
        <i class="ri-settings-3-line"></i>
        <span>C�i �?t</span>
      </div>
    </nav>

    <div class="logout-btn">
      <i class="ri-logout-box-r-line"></i>
      <span>��ng xu?t</span>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
      <div class="page-title">
        <i class="ri-user-3-fill" id="pageTitleIcon"></i>
        <h1 id="pageTitleText">Th�ng Tin C� Nh�n</h1>
      </div>
      <div class="top-actions">
        <a href="/dat-lich/bieu-mau" class="btn-primary" style="margin-right: 15px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <i class="ri-calendar-check-line"></i> �?t l?ch ngay
        </a>
        <div class="icon-btn" onclick="openNotificationsModal()">
          <i class="ri-notification-3-line"></i>
          <span class="badge">5</span>
        </div>
      </div>
    </div>

    <!-- Profile Section -->
    <div class="content-section active" id="profileSection">
      <div class="content-grid">
        <div class="section-card full-width">
          <div class="section-header">
            <div class="section-title">
              <i class="ri-user-settings-line"></i>
              Th�ng Tin C� Nh�n
            </div>
            <button class="btn-primary" onclick="saveProfileChanges()">
              <i class="ri-save-line"></i>
              L�u thay �?i
            </button>
          </div>

          <div class="profile-info">
            <div class="profile-avatar">
              <img src="{{ asset('frontend/img/logocanhan.jpg') }}" alt="?nh �?i di?n" id="profileImage">
              <div class="edit-avatar" onclick="document.getElementById('avatarInput').click()">
                <i class="ri-camera-line"></i>
              </div>
              <input type="file" id="avatarInput" accept="image/*" style="display: none;"
                onchange="changeAvatar(event)">

              <!-- Rating display under avatar -->
              <div class="rating-summary" onclick="openReviewsModal()" style="cursor: pointer; margin-top: 10px; text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                  <span id="doctorRatingStars" style="color: #ffc107; font-size: 16px;">?????</span>
                  <span id="doctorRatingAvg" style="font-weight: 600; color: #333;">0.0</span>
                </div>
                <div style="font-size: 13px; color: #666; margin-top: 3px;">
                  <span id="doctorReviewCount">0</span> ��nh gi�
                </div>
              </div>
            </div>

            <div class="profile-details">
              <div class="info-row">
                <div class="info-field">
                  <label>H? v� t�n</label>
                  <input type="text" id="profileName" value="TS.BS Nguy?n V�n A">
                </div>
                <div class="info-field">
                  <label>Gi?i t�nh</label>
                  <select id="profileGender">
                    <option value="MALE">Nam</option>
                    <option value="FEMALE">N?</option>
                    <option value="OTHER">Kh�c</option>
                  </select>
                </div>
              </div>

              <div class="info-row">
                <div class="info-field">
                  <label>Ng�y sinh</label>
                  <input type="date" id="profileBirthday" value="1985-03-15">
                </div>
                <div class="info-field">
                  <label>S? �i?n tho?i</label>
                  <input type="tel" id="profilePhone" value="0901234567">
                </div>
              </div>

              <div class="info-row">
                <div class="info-field">
                  <label>Email</label>
                  <input type="email" id="profileEmail" value="" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                </div>
                <div class="info-field">
                  <label>Chuy�n khoa</label>
                  <select id="profileSpecialty">
                    <option value="">-- Ch?n chuy�n khoa --</option>
                  </select>
                </div>
              </div>

              <div class="info-row">
                <div class="info-field" style="flex: 1;">
                  <label>D?ch v? cung c?p</label>
                  <div id="servicesContainer" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 80px; padding: 12px; border: 2px solid #e8eaf6; border-radius: 10px; background: #f8f9fa;">
                    <span style="color: #999; font-size: 14px;">Ch?n chuy�n khoa tr�?c �? hi?n th? d?ch v?</span>
                  </div>
                  <select id="profileServices" multiple style="display: none;">
                  </select>
                </div>
              </div>

              <div class="info-row">
                <div class="info-field">
                  <label>B?ng c?p</label>
                  <input type="text" id="profileDegree" placeholder="Nh?p b?ng c?p">
                </div>
                <div class="info-field">
                  <label>Kinh nghi?m</label>
                  <input type="text" id="profileExperience" placeholder="Nh?p s? n�m kinh nghi?m">
                </div>
              </div>

              <div class="info-row">
                <div class="info-field">
                  <label>Ph?ng kh�m l�m vi?c</label>
                  <select id="profileClinic">
                    <option value="">-- Ch?n ph?ng kh�m --</option>
                  </select>
                </div>
                <div class="info-field">
                  <label>�?a ch? li�n h?</label>
                  <input type="text" id="profileAddress" placeholder="Nh?p �?a ch? li�n h?">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Schedule Section -->
    <div class="content-section" id="scheduleSection">
      <div class="section-card">
        <div class="section-header">
          <div class="section-title">
            <i class="ri-calendar-check-line"></i>
            L?ch l�m vi?c tu?n
          </div>
        </div>

        <div class="dw-controls">
          <div class="left">
            <input type="date" id="dwDateInput" class="dw-date">
            <button class="dw-btn" id="dwTodayBtn">Hi?n t?i</button>
          </div>
          <div class="right" style="display: flex; gap: 8px;">
            <button class="dw-btn" id="dwPrevBtn">?</button>
            <button class="dw-btn" id="dwNextBtn">?</button>
          </div>
        </div>

        <div class="dw-cal-wrap">
          <div style="overflow-x: auto;">
            <table class="dw-table" id="dwCalendarTable"></table>
          </div>
        </div>
      </div>
    </div>

    <!-- Notifications Section -->
    <div class="content-section" id="notificationsSection">
      <div class="section-card">
        <div class="section-header">
          <div class="section-title">
            <i class="ri-notification-3-line"></i>
            T?t c? th�ng b�o
          </div>
        </div>

        <div class="notification-actions-bar">
          <button class="btn-action btn-read-all" onclick="markAllNotificationsRead()">
            <i class="ri-check-double-line"></i>
            �?c t?t c?
          </button>
          <button class="btn-action btn-delete-all" onclick="deleteAllNotifications()">
            <i class="ri-delete-bin-line"></i>
            X�a t?t c?
          </button>
        </div>

        <div class="notification-list" id="notificationListContainer">
          <!-- Notifications will be loaded dynamically -->
          <div style="text-align: center; padding: 40px; color: #999;">
            <i class="ri-loader-4-line" style="font-size: 32px; animation: spin 1s linear infinite;"></i>
            <p>�ang t?i th�ng b�o...</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Patient Notes Section -->
    <div class="content-section" id="notesSection">
      <div class="section-card">
        <div class="section-header">
          <div class="section-title">
            <i class="ri-file-text-line"></i>
            Ghi ch� kh�m b?nh
          </div>
          <button class="btn-primary" onclick="openNewNoteModal()">
            <i class="ri-add-line"></i>
            Th�m ghi ch� m?i
          </button>
        </div>

        <div class="patient-notes-list" id="patientNotesList">
          <!-- Notes will be loaded from API or show empty state -->
          <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <i class="ri-file-text-line" style="font-size: 64px; opacity: 0.3;"></i>
            <p style="margin-top: 15px; font-size: 16px;">Ch�a c� ghi ch� kh�m b?nh n�o</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal: New Note -->
    <div id="newNoteModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3><i class="ri-add-line"></i> Th�m ghi ch� kh�m b?nh m?i</h3>
          <button class="close-modal" onclick="closeModal('newNoteModal')">&times;</button>
        </div>
        <div class="modal-body">
          <form id="newNoteForm">
            <div class="form-group" style="position: relative;">
              <label>T�n b?nh nh�n * (t? l?ch �ang kh�m ho?c �? ho�n th�nh)</label>
              <input type="text" id="newPatientName" placeholder="Nh?p t�n �? t?m ki?m..." autocomplete="off" required>
              <input type="hidden" id="newPatientUserId">
              <input type="hidden" id="newAppointmentId">
              <div id="patientSuggestions" class="autocomplete-suggestions"></div>
            </div>
            <div class="form-group">
              <label>M? b?nh nh�n</label>
              <input type="text" id="newPatientId" placeholder="T? �?ng �i?n khi ch?n b?nh nh�n" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
            </div>
            <div class="form-group">
              <label>Ng�y kh�m *</label>
              <input type="date" id="newNoteDate" required>
            </div>
            <div class="form-group">
              <label>Tri?u ch?ng *</label>
              <textarea id="newSymptoms" placeholder="Nh?p tri?u ch?ng c?a b?nh nh�n..." required></textarea>
            </div>
            <div class="form-group">
              <label>Ch?n �o�n *</label>
              <textarea id="newDiagnosis" placeholder="Nh?p ch?n �o�n..." required></textarea>
            </div>
            <div class="form-group">
              <label>��n thu?c / Ghi ch� �i?u tr? *</label>
              <textarea id="newPrescription" placeholder="Nh?p ��n thu?c ho?c ghi ch� �i?u tr?..." required></textarea>
            </div>
            <div style="background: #e8f4fd; padding: 12px; border-radius: 8px; margin-top: 15px;">
              <p style="font-size: 13px; color: #0c5460; margin: 0;">
                <i class="ri-information-line"></i>
                C� th? t?o ghi ch� cho c�c l?ch h?n <strong>�ang di?n ra</strong> ho?c <strong>�? ho�n th�nh</strong>.
              </p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn-secondary" onclick="closeModal('newNoteModal')">H?y</button>
          <button class="btn-primary" onclick="submitNewNote()">
            <i class="ri-save-line"></i>
            L�u ghi ch�
          </button>
        </div>
      </div>
    </div>

    <!-- Forum Section -->
    <div class="content-section" id="forumSection">
      <div class="section-card">
        <div class="section-header">
          <div class="section-title">
            <i class="ri-question-answer-line"></i>
            Ph?n h?i ng�?i d�ng
          </div>
          <button class="btn-primary" type="button" data-forum-refresh>
            <i class="ri-refresh-line"></i>
            �?ng b? m?i
          </button>
        </div>

        <div class="shared-forum" id="doctorForumContainer" data-forum-app data-user-type="doctor" data-user-name=""
          data-user-id="" data-user-avatar="" data-default-filter="pending">

          <div class="forum-bridge">
            <i class="ri-link-m"></i>
            <div>
              <strong>Ph?n h?i c�u h?i t? ng�?i d�ng</strong>
              <p style="margin: 4px 0 0; color: #5a6c7d;">
                M?i c�u h?i m� b?nh nh�n �?t ? trang c� nh�n s? xu?t hi?n t?i ��y �? b�c s? ph?n h?i.
              </p>
            </div>
          </div>

          <div class="tab-buttons" data-forum-filters>
            <button class="tab-btn active" data-filter="pending">Ch? ph?n h?i</button>
            <button class="tab-btn" data-filter="all">T?t c? c�u h?i</button>
            <button class="tab-btn" data-filter="answered">�? tr? l?i</button>
            <button class="tab-btn" data-filter="mine">C�u tr? l?i c?a t�i</button>
          </div>

          <div class="forum-posts" data-forum-list></div>
        </div>
      </div>
    </div>

    <!-- Settings Section -->
    <div class="content-section" id="settingsSection">
      <div class="settings-grid">
        <div class="settings-section">
          <h3>
            <i class="ri-feedback-line"></i>
            G�p ? & H? tr?
          </h3>
          <div class="setting-item" style="flex-direction: column; align-items: stretch; gap: 15px; padding: 20px 0;">
            <div class="setting-info">
              <h4>G?i g�p ? t?i Admin</h4>
              <p>Th�ng �i?p s? xu?t hi?n trong h?p log g�p ? ? trang Admin</p>
            </div>
            <textarea id="feedbackTextarea"
              placeholder="V� d?: C?n b?t g?i mail t? �?ng cho l?ch t�i kh�m v�o cu?i tu?n..."
              style="width: 100%; min-height: 120px; padding: 12px; border: 2px solid #e8eaf6; border-radius: 10px; font-size: 14px; resize: vertical; outline: none; transition: all 0.3s ease;"></textarea>
            <button class="btn-primary" onclick="submitFeedback()" style="align-self: flex-start;">
              <i class="ri-send-plane-2-line"></i>
              G?i g�p ?
            </button>
          </div>
        </div>
        <div class="settings-section">
          <h3>
            <i class="ri-notification-3-line"></i>
            Th�ng b�o
          </h3>
          <div class="setting-item">
            <div class="setting-info">
              <h4>Th�ng b�o l?ch h?n m?i</h4>
              <p>Nh?n th�ng b�o khi c� l?ch h?n m?i t? b?nh nh�n</p>
            </div>
            <div class="toggle-switch active" id="toggleNewAppointment" data-setting="notifyNewAppointment"></div>
          </div>
          <div class="setting-item">
            <div class="setting-info">
              <h4>Th�ng b�o thay �?i l?ch</h4>
              <p>Nh?n th�ng b�o khi b?nh nh�n y�u c?u thay �?i l?ch h?n</p>
            </div>
            <div class="toggle-switch active" id="toggleScheduleChange" data-setting="notifyScheduleChange"></div>
          </div>
        </div>

        <div class="settings-section">
          <h3>
            <i class="ri-lock-line"></i>
            B?o m?t
          </h3>
          <div class="setting-item">
            <div class="setting-info">
              <h4>X�c th?c 2 y?u t?</h4>
              <p>B?o v? t�i kho?n v?i x�c th?c 2 y?u t?</p>
            </div>
            <div class="toggle-switch active" id="toggle2FA" data-setting="twoFactorAuth"></div>
          </div>
          <div class="setting-item">
            <div class="setting-info">
              <h4>�?i m?t kh?u</h4>
              <p>Thay �?i m?t kh?u ��ng nh?p c?a b?n</p>
            </div>
            <button class="btn-primary" onclick="openChangePasswordModal()">�?i m?t kh?u</button>
          </div>
          <div class="setting-item">
            <div class="setting-info">
              <h4>L?ch s? ��ng nh?p</h4>
              <p>Xem l?ch s? c�c l?n ��ng nh?p g?n ��y</p>
            </div>
            <button class="btn-primary" onclick="openLoginHistoryModal()">Xem l?ch s?</button>
          </div>
        </div>

        <div class="settings-section">
          <h3>
            <i class="ri-calendar-line"></i>
            L?ch l�m vi?c
          </h3>
          <div class="setting-item">
            <div class="setting-info">
              <h4>T? �?ng ch?p nh?n l?ch h?n</h4>
              <p>T? �?ng x�c nh?n l?ch h?n trong khung gi? l�m vi?c</p>
            </div>
            <div class="toggle-switch" id="toggleAutoAccept" data-setting="autoAcceptAppointment"></div>
          </div>
          <div class="setting-item">
            <div class="setting-info">
              <h4>Gi?i h?n s? l�?ng b?nh nh�n/ng�y</h4>
              <p>S? l�?ng b?nh nh�n t?i �a c� th? kh�m trong m?t ng�y</p>
            </div>
            <input type="number" id="inputMaxPatients" value="20" min="1" max="100"
              style="width: 80px; padding: 8px; border: 2px solid #e8eaf6; border-radius: 8px;">
          </div>
        </div>

        <div class="settings-section">
          <h3>
            <i class="ri-global-line"></i>
            Giao di?n & Ng�n ng?
          </h3>
          <div class="setting-item">
            <div class="setting-info">
              <h4>Ng�n ng?</h4>
              <p>Ch?n ng�n ng? hi?n th?</p>
            </div>
            <select id="selectLanguage" style="padding: 8px 12px; border: 2px solid #e8eaf6; border-radius: 8px; min-width: 150px;">
              <option value="vi">Ti?ng Vi?t</option>
              <option value="en">English</option>
            </select>
          </div>
          <div class="setting-item">
            <div class="setting-info">
              <h4>Ch? �? t?i</h4>
              <p>S? d?ng giao di?n t?i cho m?t</p>
            </div>
            <div class="toggle-switch" id="toggleDarkMode" data-setting="darkMode"></div>
          </div>
        </div>

        <div class="settings-section" style="text-align: center; padding: 20px;">
          <button class="btn-primary" onclick="saveAllSettings()" style="padding: 12px 30px; font-size: 16px;">
            <i class="ri-save-line"></i> L�u t?t c? c�i �?t
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Edit Note -->
  <div id="editNoteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="ri-edit-line"></i> Ch?nh s?a ghi ch� kh�m b?nh</h3>
        <button class="close-modal" onclick="closeModal('editNoteModal')">&times;</button>
      </div>
      <div class="modal-body">
        <form id="editNoteForm">
          <div class="form-group">
            <label>T�n b?nh nh�n</label>
            <input type="text" id="editPatientName" value="Nguy?n Th? B" readonly>
          </div>
          <div class="form-group">
            <label>M? b?nh nh�n</label>
            <input type="text" id="editPatientId" value="BN001234" readonly>
          </div>
          <div class="form-group">
            <label>Chuy�n khoa</label>
            <select id="editSpecialty">
              <option>N?i t?ng qu�t</option>
              <option>Ngo?i khoa</option>
              <option>Tim m?ch</option>
              <option>Da li?u</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tri?u ch?ng</label>
            <textarea id="editSymptoms"
              placeholder="Nh?p tri?u ch?ng c?a b?nh nh�n...">�au �?u, ch�ng m?t, m?t m?i</textarea>
          </div>
          <div class="form-group">
            <label>Ch?n �o�n</label>
            <textarea id="editDiagnosis" placeholder="Nh?p ch?n �o�n...">Thi?u m�u nh?, huy?t �p th?p</textarea>
          </div>
          <div class="form-group">
            <label>��n thu?c / Ghi ch� �i?u tr?</label>
            <textarea id="editPrescription"
              placeholder="Nh?p ��n thu?c ho?c ghi ch� �i?u tr?...">Vi�n b? sung s?t - 1 vi�n/ng�y sau �n, Vitamin C - 1 vi�n/ng�y</textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeModal('editNoteModal')">H?y</button>
        <button class="btn-primary" onclick="saveEditNote()">
          <i class="ri-save-line"></i>
          L�u thay �?i
        </button>
      </div>
    </div>
  </div>

  <!-- Modal: New Thread -->
  <div id="newThreadModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="ri-add-line"></i> T?o ch? �? m?i</h3>
        <button class="close-modal" onclick="closeModal('newThreadModal')">&times;</button>
      </div>
      <div class="modal-body">
        <form id="newThreadForm">
          <div class="form-group">
            <label>Ti�u �? ch? �? *</label>
            <input type="text" id="threadTitle" placeholder="Nh?p ti�u �? ch? �?..." required>
          </div>
          <div class="form-group">
            <label>N?i dung *</label>
            <textarea id="threadContent" placeholder="Nh?p n?i dung chi ti?t..." required></textarea>
          </div>
          <div class="form-group">
            <label>Danh m?c</label>
            <select id="threadCategory">
              <option>Th?o lu?n chung</option>
              <option>Chia s? kinh nghi?m</option>
              <option>H?i ��p chuy�n m�n</option>
              <option>Ph?n h?i b?nh nh�n</option>
              <option>Th�ng b�o</option>
            </select>
          </div>
          <div class="form-group">
            <label>Th? tags (nh?n Enter �? th�m)</label>
            <div class="tag-input-wrapper" id="tagInputWrapper">
              <input type="text" id="tagInput" placeholder="Nh?p th? v� nh?n Enter...">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeModal('newThreadModal')">H?y</button>
        <button class="btn-primary" onclick="submitNewThread()">
          <i class="ri-send-plane-fill"></i>
          ��ng b�i
        </button>
      </div>
    </div>
  </div>

  <!-- Modal: Change Password -->
  <div id="changePasswordModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="ri-lock-password-line"></i> �?i m?t kh?u</h3>
        <button class="close-modal" onclick="closeModal('changePasswordModal')">&times;</button>
      </div>
      <div class="modal-body">
        <form id="changePasswordForm">
          <div class="form-group">
            <label>M?t kh?u hi?n t?i *</label>
            <input type="password" id="currentPassword" placeholder="Nh?p m?t kh?u hi?n t?i..." required>
          </div>
          <div class="form-group">
            <label>M?t kh?u m?i *</label>
            <input type="password" id="newPassword" placeholder="Nh?p m?t kh?u m?i..." required>
          </div>
          <div class="form-group">
            <label>X�c nh?n m?t kh?u m?i *</label>
            <input type="password" id="confirmPassword" placeholder="Nh?p l?i m?t kh?u m?i..." required>
          </div>
          <div style="background: #fff3cd; padding: 12px; border-radius: 8px; margin-top: 15px;">
            <p style="font-size: 13px; color: #856404; margin: 0;">
              <i class="ri-information-line"></i>
              M?t kh?u ph?i c� �t nh?t 8 k? t?, bao g?m ch? hoa, ch? th�?ng v� s?.
            </p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" onclick="closeModal('changePasswordModal')">H?y</button>
        <button class="btn-primary" onclick="submitChangePassword()">
          <i class="ri-check-line"></i>
          �?i m?t kh?u
        </button>
      </div>
    </div>
  </div>

  <!-- Modal: Login History -->
  <div id="loginHistoryModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
      <div class="modal-header">
        <h3><i class="ri-history-line"></i> L?ch s? ��ng nh?p</h3>
        <button class="close-modal" onclick="closeModal('loginHistoryModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="login-history-list">
          <!-- Login history items will be loaded dynamically -->
          <div style="text-align: center; padding: 30px; color: #888;">
            <i class="ri-loader-4-line" style="font-size: 30px; animation: spin 1s linear infinite;"></i>
            <p>�ang t?i l?ch s? ��ng nh?p...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-primary" onclick="closeModal('loginHistoryModal')">��ng</button>
      </div>
    </div>
  </div>

  <!-- Modal: Notifications -->
  <div id="notificationsModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
      <div class="modal-header">
        <h3><i class="ri-notification-3-line"></i> Th�ng b�o</h3>
        <button class="close-modal" onclick="closeModal('notificationsModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="notification-list">
          <div class="notification-item unread" onclick="markAsRead(this)">
            <div class="notification-icon notif-schedule">
              <i class="ri-calendar-line"></i>
            </div>
            <div class="notification-content">
              <h4>L?ch kh�m m?i</h4>
              <p>B?nh nh�n Nguy?n V�n E �?t l?ch kh�m l�c 14:00</p>
            </div>
            <div class="notification-time">10 ph�t tr�?c</div>
            <span class="unread-badge"></span>
          </div>
          <div class="notification-item unread" onclick="markAsRead(this)">
            <div class="notification-icon notif-change">
              <i class="ri-time-line"></i>
            </div>
            <div class="notification-content">
              <h4>Thay �?i l?ch h?n</h4>
              <p>B?nh nh�n Tr?n Th? F y�u c?u �?i l?ch</p>
            </div>
            <div class="notification-time">1 gi? tr�?c</div>
            <span class="unread-badge"></span>
          </div>
          <div class="notification-item unread" onclick="markAsRead(this)">
            <div class="notification-icon notif-system">
              <i class="ri-information-line"></i>
            </div>
            <div class="notification-content">
              <h4>C?p nh?t h? th?ng</h4>
              <p>H? th?ng s? b?o tr? v�o 23:00 t?i nay</p>
            </div>
            <div class="notification-time">2 gi? tr�?c</div>
            <span class="unread-badge"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-primary" onclick="closeModal('notificationsModal')">��ng</button>
      </div>
    </div>
  </div>

  <!-- Modal: Appointment Detail -->
  <div id="appointmentDetailModal" class="modal">
    <div class="modal-content appointment-detail-modal" style="max-width: 600px;">
      <div class="modal-header">
        <h3><i class="ri-calendar-check-line"></i> Chi ti?t l?ch h?n</h3>
        <button class="close-modal" onclick="closeModal('appointmentDetailModal')">&times;</button>
      </div>
      <div class="modal-body" id="appointmentDetailBody">
        <!-- Content will be loaded dynamically -->
      </div>
    </div>
  </div>

  <!-- Modal: Delete Notification Confirm -->
  <div id="deleteNotifModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
      <div class="modal-header" style="background: #e74c3c;">
        <h3><i class="ri-delete-bin-line"></i> X�c nh?n x�a</h3>
        <button class="close-modal" onclick="closeModal('deleteNotifModal')">&times;</button>
      </div>
      <div class="modal-body" style="text-align: center; padding: 30px;">
        <i class="ri-question-line" style="font-size: 48px; color: #e74c3c; margin-bottom: 15px;"></i>
        <p style="font-size: 16px; color: #2c3e50; margin-bottom: 0;">B?n c� mu?n x�a th�ng b�o n�y?</p>
      </div>
      <div class="modal-footer" style="justify-content: center;">
        <button class="btn-secondary" onclick="closeModal('deleteNotifModal')">Kh�ng</button>
        <button class="btn-primary" onclick="confirmDeleteNotification()" style="background: #e74c3c;">
          <i class="ri-delete-bin-line"></i> X�a
        </button>
      </div>
    </div>
  </div>

  <!-- Modal: Reviews List (Read-Only) -->
  <div id="reviewsModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
      <div class="modal-header" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
        <h3><i class="ri-star-line"></i> ��nh gi� t? b?nh nh�n</h3>
        <button class="close-modal" onclick="closeModal('reviewsModal')">&times;</button>
      </div>
      <div class="modal-body" style="max-height: 500px; overflow-y: auto; padding: 15px;">
        <div id="reviewsSummary" style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
          <div style="font-size: 36px; font-weight: 700; color: #333;" id="modalRatingAvg">0.0</div>
          <div id="modalRatingStars" style="color: #ffc107; font-size: 24px;">?????</div>
          <div style="color: #666; margin-top: 5px;"><span id="modalReviewCount">0</span> ��nh gi�</div>
        </div>
        <div id="reviewsList" style="display: flex; flex-direction: column; gap: 12px;">
          <!-- Reviews will be loaded here -->
          <div style="text-align: center; color: #999; padding: 30px;">
            �ang t?i ��nh gi�...
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // ============ GLOBAL VARIABLES ============
    let doctorAppointments = [];
    let notifications = [];
    let pendingDeleteNotifId = null;
    let doctorReviews = [];
    let currentDoctorId = null;

    // ============ EARLY FUNCTION DECLARATIONS (for onclick handlers) ============
    // Open notifications modal - defined early for onclick handlers
    function openNotificationsModal() {
      loadNotifications();
      document.getElementById('notificationsModal').classList.add('active');
    }

    // Save profile changes - defined early for onclick handlers  
    async function saveProfileChanges() {
      const token = localStorage.getItem('access_token');
      if (!token) {
        alert('Vui l?ng ��ng nh?p l?i!');
        return;
      }

      const profileData = {
        name: document.getElementById('profileName').value,
        gender: document.getElementById('profileGender').value,
        birthday: document.getElementById('profileBirthday').value,
        phone: document.getElementById('profilePhone').value,
        email: document.getElementById('profileEmail').value,
        specialty: document.getElementById('profileSpecialty').value,
        clinic: document.getElementById('profileClinic').value,
        degree: document.getElementById('profileDegree').value,
        experience: document.getElementById('profileExperience').value,
        address: document.getElementById('profileAddress').value
      };

      // Get selected services from checkboxes
      const checkboxes = document.querySelectorAll('input[name="profileService"]:checked');
      const selectedServices = Array.from(checkboxes).map(cb => parseInt(cb.value));

      try {
        // Update user profile (name, phone, gender, dob, address)
        const userResponse = await fetch('/api/profile/me', {
          method: 'PATCH',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            full_name: profileData.name,
            gender: profileData.gender,
            dob: profileData.birthday,
            phone: profileData.phone,
            address: profileData.address
          })
        });

        if (!userResponse.ok) {
          throw new Error('Kh�ng th? c?p nh?t th�ng tin c� nh�n');
        }

        // Update doctor profile (degree, experience, specialization, clinic, services)
        const doctorResponse = await fetch('/api/profile/doctor', {
          method: 'PATCH',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            degree: profileData.degree,
            experience: profileData.experience ? parseInt(profileData.experience) : null,
            specialization_id: profileData.specialty ? parseInt(profileData.specialty) : null,
            clinic_id: profileData.clinic ? parseInt(profileData.clinic) : null,
            service_ids: selectedServices
          })
        });

        if (!doctorResponse.ok) {
          console.warn('Kh�ng th? c?p nh?t th�ng tin b�c s?');
        }

        // Also save to localStorage as backup
        localStorage.setItem('doctorProfile', JSON.stringify(profileData));
        showToast('Th�ng tin c� nh�n �? ��?c l�u th�nh c�ng!', 'success');
      } catch (err) {
        console.error('Error saving profile:', err);
        showToast('C� l?i x?y ra khi l�u th�ng tin: ' + err.message, 'error');
      }
    }

    // Toast notification - defined early for use everywhere
    function showToast(message, type = 'info') {
      const toast = document.createElement('div');
      toast.className = `toast-notification toast-${type}`;
      toast.innerHTML = `
        <i class="ri-${type === 'success' ? 'check-line' : type === 'error' ? 'error-warning-line' : 'information-line'}"></i>
        <span>${message}</span>
      `;
      document.body.appendChild(toast);

      setTimeout(() => toast.classList.add('show'), 100);
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    // Navigation system
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');
    const pageTitleText = document.getElementById('pageTitleText');
    const pageTitleIcon = document.getElementById('pageTitleIcon');

    const sectionTitles = {
      profile: {
        text: 'Th�ng Tin C� Nh�n',
        icon: 'ri-user-3-fill'
      },
      schedule: {
        text: 'L?ch L�m Vi?c',
        icon: 'ri-calendar-line'
      },
      notifications: {
        text: 'Th�ng B�o',
        icon: 'ri-notification-3-line'
      },
      notes: {
        text: 'Ghi Ch� Kh�m B?nh',
        icon: 'ri-file-text-line'
      },
      forum: {
        text: 'Ph?n H?i Ng�?i D�ng',
        icon: 'ri-question-answer-line'
      },
      settings: {
        text: 'C�i �?t',
        icon: 'ri-settings-3-line'
      }
    };

    navItems.forEach(item => {
      item.addEventListener('click', () => {
        const section = item.getAttribute('data-section');
        navItems.forEach(nav => nav.classList.remove('active'));
        item.classList.add('active');
        contentSections.forEach(sec => sec.classList.remove('active'));
        const targetSection = document.getElementById(section + 'Section');
        if (targetSection) {
          targetSection.classList.add('active');
        }
        if (sectionTitles[section]) {
          pageTitleText.textContent = sectionTitles[section].text;
          pageTitleIcon.className = sectionTitles[section].icon;
        }
        // Load notifications when section is clicked
        if (section === 'notifications') {
          loadNotifications();
        }
        // Load schedule when section is clicked
        if (section === 'schedule') {
          if (typeof loadDoctorAppointments === 'function') {
            loadDoctorAppointments();
          }
        }
        // Load notes when section is clicked
        if (section === 'notes') {
          if (typeof loadMedicalNotes === 'function') {
            loadMedicalNotes();
          }
        }
        // Load settings when section is clicked
        if (section === 'settings') {
          loadSavedSettings();
        }
      });
    });

    // ============ SETTINGS FUNCTIONS ============
    // Load saved settings from localStorage
    async function loadSavedSettings() {
      const settings = JSON.parse(localStorage.getItem('doctorSettings') || '{}');

      // Load 2FA from API (database)
      const token = localStorage.getItem('access_token');
      if (token) {
        try {
          const response = await fetch('/api/profile/me', {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            const userData = await response.json();
            const toggle2FA = document.getElementById('toggle2FA');
            if (toggle2FA) {
              if (userData.two_factor_enabled) {
                toggle2FA.classList.add('active');
              } else {
                toggle2FA.classList.remove('active');
              }
            }
          }
        } catch (e) {
          console.error('Error loading 2FA setting:', e);
        }
      }

      // Load toggle states from localStorage (except 2FA which comes from DB)
      const toggleMappings = {
        'toggleNewAppointment': 'notifyNewAppointment',
        'toggleScheduleChange': 'notifyScheduleChange',
        'toggleNewMessage': 'notifyNewMessage',
        'toggleAutoAccept': 'autoAcceptAppointment',
        'toggleDarkMode': 'darkMode'
      };

      Object.entries(toggleMappings).forEach(([id, key]) => {
        const toggle = document.getElementById(id);
        if (toggle && settings[key] !== undefined) {
          if (settings[key]) {
            toggle.classList.add('active');
          } else {
            toggle.classList.remove('active');
          }
        }
      });

      // Load input values
      if (settings.maxPatients) {
        document.getElementById('inputMaxPatients').value = settings.maxPatients;
      }
      if (settings.language) {
        document.getElementById('selectLanguage').value = settings.language;
      }

      // Apply dark mode if enabled
      if (settings.darkMode) {
        document.body.classList.add('dark-mode');
      }
    }

    // Save all settings
    async function saveAllSettings() {
      const settings = {
        notifyNewAppointment: document.getElementById('toggleNewAppointment')?.classList.contains('active') || false,
        notifyScheduleChange: document.getElementById('toggleScheduleChange')?.classList.contains('active') || false,
        notifyNewMessage: document.getElementById('toggleNewMessage')?.classList.contains('active') || false,
        twoFactorAuth: document.getElementById('toggle2FA')?.classList.contains('active') || false,
        autoAcceptAppointment: document.getElementById('toggleAutoAccept')?.classList.contains('active') || false,
        darkMode: document.getElementById('toggleDarkMode')?.classList.contains('active') || false,
        maxPatients: parseInt(document.getElementById('inputMaxPatients')?.value) || 20,
        language: document.getElementById('selectLanguage')?.value || 'vi'
      };

      localStorage.setItem('doctorSettings', JSON.stringify(settings));

      // Save 2FA setting to database
      const token = localStorage.getItem('access_token');
      if (token) {
        try {
          await fetch('/api/profile/two-factor', {
            method: 'PATCH',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              two_factor_enabled: settings.twoFactorAuth
            })
          });
        } catch (e) {
          console.error('Error saving 2FA setting:', e);
        }
      }

      // Apply dark mode
      if (settings.darkMode) {
        document.body.classList.add('dark-mode');
      } else {
        document.body.classList.remove('dark-mode');
      }

      // Show success message
      showToast('C�i �?t �? ��?c l�u th�nh c�ng!', 'success');
    }

    // Toggle switches with auto-save preview
    const toggleSwitches = document.querySelectorAll('.toggle-switch');
    toggleSwitches.forEach(toggle => {
      toggle.addEventListener('click', () => {
        toggle.classList.toggle('active');

        // Special handling for dark mode - instant preview
        if (toggle.id === 'toggleDarkMode') {
          document.body.classList.toggle('dark-mode');
        }
      });
    });

    // Load settings on page load
    document.addEventListener('DOMContentLoaded', () => {
      loadSavedSettings();
    });

    // ============ SCHEDULE CALENDAR ============
    (function() {
      let dwCurrent = new Date();
      const dwTimeSlots = [{
          label: 'S�ng',
          key: 'morning',
          startHour: 7,
          endHour: 11
        },
        {
          label: 'Tr�a',
          key: 'noon',
          startHour: 11,
          endHour: 13
        },
        {
          label: 'Chi?u',
          key: 'afternoon',
          startHour: 13,
          endHour: 17
        },
        {
          label: 'T?i',
          key: 'evening',
          startHour: 17,
          endHour: 21
        }
      ];

      function dwKey(d) {
        const y = d.getFullYear();
        const m = (d.getMonth() + 1).toString().padStart(2, '0');
        const da = d.getDate().toString().padStart(2, '0');
        return `${y}-${m}-${da}`;
      }

      function dwFmt(d) {
        return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
      }

      function dwWeek(d) {
        const c = new Date(d);
        const f = c.getDate() - c.getDay() + 1;
        const arr = [];
        for (let i = 0; i < 7; i++) {
          const day = new Date(c);
          day.setDate(f + i);
          arr.push(day);
        }
        return arr;
      }

      function isToday(d) {
        const t = new Date();
        return d.toDateString() === t.toDateString();
      }

      // Get appointment status based on time
      function getAppointmentTimeStatus(dateStr, startTime, endTime) {
        const now = new Date();
        const appointmentDate = new Date(dateStr);

        // Parse times
        const [startH, startM] = (startTime || '00:00').split(':').map(Number);
        const [endH, endM] = (endTime || '23:59').split(':').map(Number);

        const startDateTime = new Date(appointmentDate);
        startDateTime.setHours(startH, startM, 0, 0);

        const endDateTime = new Date(appointmentDate);
        endDateTime.setHours(endH, endM, 0, 0);

        const oneHourBefore = new Date(startDateTime.getTime() - 60 * 60 * 1000);

        if (now > endDateTime) {
          return {
            status: 'ended',
            label: '�? k?t th�c',
            class: 'ended'
          };
        } else if (now >= startDateTime && now <= endDateTime) {
          return {
            status: 'ongoing',
            label: '�ang di?n ra',
            class: 'ongoing'
          };
        } else if (now >= oneHourBefore && now < startDateTime) {
          return {
            status: 'upcoming',
            label: 'S?p di?n ra',
            class: 'upcoming'
          };
        } else {
          return {
            status: 'scheduled',
            label: 'Ch�a �?n gi?',
            class: ''
          };
        }
      }

      // Parse time slot to start/end times
      function parseTimeSlot(timeSlot, slotInfo) {
        if (timeSlot && timeSlot.includes('-')) {
          const [start, end] = timeSlot.split('-').map(t => t.trim());
          return {
            startTime: start,
            endTime: end
          };
        }
        // Fallback to slot default times
        return {
          startTime: `${slotInfo.startHour.toString().padStart(2, '0')}:00`,
          endTime: `${slotInfo.endHour.toString().padStart(2, '0')}:00`
        };
      }

      // Calculate end time based on start time and duration
      function calculateEndTime(startTime, durationMinutes) {
        if (!startTime) return '';
        const [hours, mins] = startTime.split(':').map(Number);
        const totalMins = hours * 60 + mins + durationMinutes;
        const endHours = Math.floor(totalMins / 60) % 24;
        const endMins = totalMins % 60;
        return `${endHours.toString().padStart(2, '0')}:${endMins.toString().padStart(2, '0')}`;
      }

      function organizeAppointmentsBySlot(appointments) {
        const organized = {};

        appointments.forEach(apt => {
          const dateKey = apt.date;
          if (!organized[dateKey]) {
            organized[dateKey] = {
              morning: [],
              noon: [],
              afternoon: [],
              evening: []
            };
          }

          // Determine slot based on start_time or time_slot
          let slotKey = 'morning';
          const startTime = apt.start_time || '';
          const hour = parseInt(startTime.split(':')[0]) || 8;

          if (hour >= 7 && hour < 11) slotKey = 'morning';
          else if (hour >= 11 && hour < 13) slotKey = 'noon';
          else if (hour >= 13 && hour < 17) slotKey = 'afternoon';
          else if (hour >= 17 && hour < 21) slotKey = 'evening';

          organized[dateKey][slotKey].push(apt);
        });

        return organized;
      }

      async function loadDoctorAppointments() {
        try {
          const token = localStorage.getItem('access_token');
          if (!token) {
            console.warn('No access token found');
            renderDw(); // Still render empty calendar
            return;
          }

          const response = await fetch('/api/doctor/appointments/confirmed', {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });

          if (response.ok) {
            const data = await response.json();
            // Handle both array and {data: [...]} format
            doctorAppointments = Array.isArray(data) ? data : (data.data || []);
            } else {
            const errorText = await response.text();
            console.error('API error:', response.status, errorText);
            doctorAppointments = [];
          }
        } catch (err) {
          console.error('Error loading appointments:', err);
          doctorAppointments = [];
        }
        renderDw(); // Always render calendar
      }

      function renderDw() {
        const table = document.getElementById('dwCalendarTable');
        if (!table) {
          console.error('dwCalendarTable not found!');
          return;
        }

        const days = dwWeek(dwCurrent);
        const organizedData = organizeAppointmentsBySlot(doctorAppointments);

        let head = '<thead><tr><th>Bu?i kh�m</th>';
        days.forEach(day => {
          head += `<th${isToday(day) ? ' style="background:#ffb020;color:#fff"' : ''}>Th? ${day.getDay() === 0 ? 'CN' : day.getDay() + 1}<div style="font-weight:500;opacity:.9">${dwFmt(day)}</div></th>`;
        });
        head += '</tr></thead>';

        let body = '<tbody>';
        dwTimeSlots.forEach(slot => {
          body += '<tr>';
          body += `<td class="dw-slot">${slot.label}<div class="rng">${slot.startHour.toString().padStart(2,'0')}:00 - ${slot.endHour.toString().padStart(2,'0')}:00</div></td>`;

          days.forEach(day => {
            const key = dwKey(day);
            const dayData = organizedData[key] || {};
            const items = dayData[slot.key] || [];
            body += `<td${isToday(day) ? ' style="background:#fff6e6"' : ''}>`;

            if (items.length) {
              items.forEach(apt => {
                const times = parseTimeSlot(apt.time_slot, slot);
                const duration = apt.service_duration || 60;
                // Format start time (remove seconds if present)
                let startTime = (apt.start_time || times.startTime || '').replace(/:00$/, '').substring(0, 5);
                if (!startTime || startTime === '00:00') startTime = times.startTime;

                // Calculate end time from start + duration (ignore apt.end_time if same as start)
                let endTime = apt.end_time ? apt.end_time.substring(0, 5) : '';
                if (!endTime || endTime === startTime.substring(0, 5) || endTime === '00:00') {
                  endTime = calculateEndTime(startTime, duration);
                }

                const timeStatus = getAppointmentTimeStatus(apt.date, startTime, endTime);
                const statusClass = apt.status || 'confirmed';

                body += `
                  <div class="dw-card ${statusClass}" onclick="openAppointmentDetail(${JSON.stringify(apt).replace(/"/g, '&quot;')}, '${timeStatus.status}')">
                    <h4 style="font-weight: 700; font-size: 14px; margin-bottom: 8px;">${apt.patient_name || 'B?nh nh�n'}</h4>
                    <div class="dw-meta" style="font-size: 11px; line-height: 1.6;">
                      <div><i class="ri-hospital-line"></i> ${apt.clinic_name || 'Ch�a x�c �?nh'}</div>
                      <div><i class="ri-time-line"></i> ${startTime} - ${endTime} (${duration} ph�t)</div>
                      ${apt.specialization_name ? `<div><i class="ri-stethoscope-line"></i> ${apt.specialization_name}</div>` : ''}
                      ${apt.service_name ? `<div><i class="ri-service-line"></i> ${apt.service_name}</div>` : ''}
                    </div>
                    ${timeStatus.class ? `<span class="schedule-status-label ${timeStatus.class}">${timeStatus.label}</span>` : ''}
                  </div>
                `;
              });
            } else {
              body += '<div class="dw-empty">Kh�ng c� l?ch</div>';
            }
            body += '</td>';
          });
          body += '</tr>';
        });
        body += '</tbody>';
        table.innerHTML = head + body;

        const di = document.getElementById('dwDateInput');
        if (di) {
          di.value = `${dwCurrent.getFullYear()}-${(dwCurrent.getMonth() + 1).toString().padStart(2, '0')}-${dwCurrent.getDate().toString().padStart(2, '0')}`;
        }
      }

      // Make loadDoctorAppointments globally accessible
      window.loadDoctorAppointments = loadDoctorAppointments;
      window.renderDw = renderDw;

      document.addEventListener('DOMContentLoaded', () => {
        const prev = document.getElementById('dwPrevBtn');
        const next = document.getElementById('dwNextBtn');
        const today = document.getElementById('dwTodayBtn');
        const di = document.getElementById('dwDateInput');
        const table = document.getElementById('dwCalendarTable');
        // Render empty calendar immediately
        renderDw();

        if (prev) prev.addEventListener('click', () => {
          dwCurrent.setDate(dwCurrent.getDate() - 7);
          renderDw();
        });
        if (next) next.addEventListener('click', () => {
          dwCurrent.setDate(dwCurrent.getDate() + 7);
          renderDw();
        });
        if (today) today.addEventListener('click', () => {
          dwCurrent = new Date();
          renderDw();
        });
        if (di) di.addEventListener('change', (e) => {
          dwCurrent = new Date(e.target.value);
          renderDw();
        });

        loadDoctorAppointments();

        // Auto refresh every minute
        setInterval(() => {
          renderDw();
        }, 60000);
      });
    })();

    // ============ APPOINTMENT DETAIL MODAL ============
    let currentAppointmentData = null;

    function openAppointmentDetail(aptData, timeStatus) {
      currentAppointmentData = aptData;
      const modal = document.getElementById('appointmentDetailModal');
      const body = document.getElementById('appointmentDetailBody');

      const isPaid = aptData.payment_status === 'paid' || aptData.payment_status === 'PAID';
      const amount = aptData.fee_amount || aptData.price || aptData.amount || 500000; // Default fee

      let statusDisplay = '';
      let actionsHtml = '';

      if (timeStatus === 'ended') {
        // Show doctor's choice status
        statusDisplay = `<span class="status-badge" style="background:#e0e0e0;color:#666;">�? ho�n th�nh</span>`;
        actionsHtml = `<p style="text-align:center;color:#7f8c8d;margin-top:20px;">L?ch h?n n�y �? k?t th�c</p>`;
      } else if (timeStatus === 'upcoming') {
        statusDisplay = `<span class="status-badge" style="background:#fff3cd;color:#856404;">Ch�a �?n gi?</span>`;
        actionsHtml = `<p style="text-align:center;color:#7f8c8d;margin-top:20px;">L?ch h?n s? di?n ra s?m</p>`;
      } else if (timeStatus === 'ongoing') {
        statusDisplay = `<span class="status-badge" style="background:#d4edda;color:#155724;">�ang di?n ra</span>`;
        actionsHtml = `
          <div class="appointment-actions">
            <button class="btn-complete" onclick="completeAppointment()">
              <i class="ri-check-line"></i> X�c nh?n ho�n th�nh
            </button>
            <button class="btn-cancel-appointment" onclick="showCancelForm()">
              <i class="ri-close-line"></i> H?y l?ch h?n
            </button>
          </div>
          <div class="cancel-reason-form" id="cancelReasonForm">
            <label>Ch?n l? do h?y:</label>
            <select id="cancelReasonSelect" onchange="toggleOtherReason()">
              <option value="">-- Ch?n l? do --</option>
              <option value="Kh�ch h�ng kh�ng �?n">Kh�ch h�ng kh�ng �?n</option>
              <option value="Kh�ch h�ng ph?n �?i">Kh�ch h�ng ph?n �?i</option>
              <option value="Kh�ch h�ng �?p b�c s? :))">Kh�ch h�ng �?p b�c s? :))</option>
              <option value="Kh�ch h�ng thi?u ? th?c">Kh�ch h�ng thi?u ? th?c</option>
              <option value="other">Kh�c (vui l?ng nh?p)</option>
            </select>
            <input type="text" id="cancelReasonOther" placeholder="Nh?p l? do kh�c..." style="display:none;">
            <div style="display:flex;gap:10px;margin-top:10px;">
              <button class="btn-secondary" onclick="hideCancelForm()">H?y b?</button>
              <button class="btn-primary" onclick="confirmCancelAppointment()" style="background:#e74c3c;">X�c nh?n h?y</button>
            </div>
          </div>
        `;
      } else {
        statusDisplay = `<span class="status-badge" style="background:#e3f2fd;color:#1565c0;">�? l�n l?ch</span>`;
        actionsHtml = '';
      }

      // Patient avatar - use default if not available
      const defaultAvatar = '{{ asset("frontend/img/logocanhan.jpg") }}';
      const patientAvatar = aptData.patient_avatar || defaultAvatar;
      
      body.innerHTML = `
        <div class="patient-avatar-section" style="text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
          <img src="${patientAvatar}" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);" onerror="this.onerror=null; this.src='${defaultAvatar}'">
          <h3 style="margin: 10px 0 5px; color: #333; font-size: 18px;">${aptData.patient_name || 'Ch�a c� th�ng tin'}</h3>
        </div>
        <div class="detail-row">
          <div class="detail-label">S? �i?n tho?i:</div>
          <div class="detail-value">${aptData.patient_phone || 'Ch�a c�'}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Email:</div>
          <div class="detail-value">${aptData.patient_email || 'Ch�a c�'}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Ph?ng kh�m:</div>
          <div class="detail-value">${aptData.clinic_name || 'Ch�a x�c �?nh'}${aptData.room_number ? ' - Ph?ng ' + aptData.room_number : ''}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">�?a ch?:</div>
          <div class="detail-value">${aptData.clinic_address || 'Ch�a c�'}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Chuy�n khoa:</div>
          <div class="detail-value">${aptData.specialization_name || 'Ch�a x�c �?nh'}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">D?ch v?:</div>
          <div class="detail-value">${aptData.service_name || 'Ch�a x�c �?nh'}${aptData.service_duration ? ' (' + aptData.service_duration + ' ph�t)' : ''}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Y�u c?u b?nh nh�n:</div>
          <div class="detail-value" style="font-style: ${aptData.notes ? 'normal' : 'italic'}; color: ${aptData.notes ? '#333' : '#999'};">${aptData.notes || 'Kh�ng c� y�u c?u �?c bi?t'}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Tr?ng th�i:</div>
          <div class="detail-value">${statusDisplay}</div>
        </div>
        ${!isPaid ? `
        <div class="detail-row">
          <div class="detail-label">S? ti?n ph?i thu:</div>
          <div class="detail-value amount-due">${new Intl.NumberFormat('vi-VN').format(amount)}�</div>
        </div>
        ` : `
        <div class="detail-row">
          <div class="detail-label">Thanh to�n:</div>
          <div class="detail-value"><span class="status-badge status-paid">�? thanh to�n</span></div>
        </div>
        `}
        ${actionsHtml}
      `;

      modal.classList.add('active');
    }

    function showCancelForm() {
      document.getElementById('cancelReasonForm').classList.add('active');
    }

    function hideCancelForm() {
      document.getElementById('cancelReasonForm').classList.remove('active');
      document.getElementById('cancelReasonSelect').value = '';
      document.getElementById('cancelReasonOther').style.display = 'none';
    }

    function toggleOtherReason() {
      const select = document.getElementById('cancelReasonSelect');
      const other = document.getElementById('cancelReasonOther');
      other.style.display = select.value === 'other' ? 'block' : 'none';
    }

    async function completeAppointment() {
      if (!currentAppointmentData) return;

      if (confirm('X�c nh?n ho�n th�nh l?ch h?n n�y?')) {
        try {
          const token = localStorage.getItem('access_token');
          const response = await fetch(`/api/appointments/${currentAppointmentData.id}`, {
            method: 'PUT',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              status: 'completed'
            })
          });

          if (response.ok) {
            alert('�? x�c nh?n ho�n th�nh l?ch h?n!');
            closeModal('appointmentDetailModal');
            loadDoctorAppointments();
          } else {
            alert('C� l?i x?y ra. Vui l?ng th? l?i.');
          }
        } catch (err) {
          console.error(err);
          alert('C� l?i x?y ra. Vui l?ng th? l?i.');
        }
      }
    }

    async function confirmCancelAppointment() {
      if (!currentAppointmentData) return;

      const select = document.getElementById('cancelReasonSelect');
      let reason = select.value;

      if (reason === 'other') {
        reason = document.getElementById('cancelReasonOther').value.trim();
      }

      if (!reason) {
        alert('Vui l?ng ch?n ho?c nh?p l? do h?y!');
        return;
      }

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch(`/api/appointments/${currentAppointmentData.id}`, {
          method: 'PUT',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            status: 'cancelled',
            cancel_reason: reason
          })
        });

        if (response.ok) {
          alert('�? h?y l?ch h?n!');
          closeModal('appointmentDetailModal');
          loadDoctorAppointments();
        } else {
          alert('C� l?i x?y ra. Vui l?ng th? l?i.');
        }
      } catch (err) {
        console.error(err);
        alert('C� l?i x?y ra. Vui l?ng th? l?i.');
      }
    }

    // ============ DOCTOR RATING & REVIEWS ============
    async function loadDoctorRating() {
      try {
        const token = localStorage.getItem('access_token');
        if (!token) return;

        // Get doctor profile to get doctor ID
        const doctorRes = await fetch('/api/profile/doctor', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        if (!doctorRes.ok) return;
        const doctorData = await doctorRes.json();
        if (!doctorData.doctor) return;

        currentDoctorId = doctorData.doctor.id;

        // Fetch reviews for this doctor
        const reviewsRes = await fetch(`/api/public/doctors/${currentDoctorId}/reviews`);
        if (reviewsRes.ok) {
          const reviewsData = await reviewsRes.json();
          doctorReviews = reviewsData.reviews || [];
          const avgRating = reviewsData.average_rating || 0;
          const reviewCount = reviewsData.total_reviews || doctorReviews.length;

          // Update display
          updateRatingDisplay(avgRating, reviewCount);
        }
      } catch (err) {
        console.error('Error loading doctor rating:', err);
      }
    }

    function updateRatingDisplay(avgRating, reviewCount) {
      const avgRounded = parseFloat(avgRating).toFixed(1);
      document.getElementById('doctorRatingAvg').textContent = avgRounded;
      document.getElementById('doctorReviewCount').textContent = reviewCount;
      document.getElementById('doctorRatingStars').innerHTML = generateStars(avgRating);
    }

    function generateStars(rating) {
      const fullStars = Math.floor(rating);
      const halfStar = rating % 1 >= 0.5 ? 1 : 0;
      const emptyStars = 5 - fullStars - halfStar;
      return '?'.repeat(fullStars) + (halfStar ? '?' : '') + '?'.repeat(emptyStars);
    }

    async function openReviewsModal() {
      const modal = document.getElementById('reviewsModal');
      if (!modal) return;

      openModal('reviewsModal');

      const reviewsList = document.getElementById('reviewsList');
      reviewsList.innerHTML = '<div style="text-align: center; color: #999; padding: 30px;">�ang t?i ��nh gi�...</div>';

      try {
        // Reload reviews to get fresh data
        if (currentDoctorId) {
          const reviewsRes = await fetch(`/api/public/doctors/${currentDoctorId}/reviews`);
          if (reviewsRes.ok) {
            const reviewsData = await reviewsRes.json();
            doctorReviews = reviewsData.reviews || [];
            const avgRating = reviewsData.average_rating || 0;
            const reviewCount = reviewsData.total_reviews || doctorReviews.length;

            // Update modal summary
            document.getElementById('modalRatingAvg').textContent = parseFloat(avgRating).toFixed(1);
            document.getElementById('modalReviewCount').textContent = reviewCount;
            document.getElementById('modalRatingStars').innerHTML = generateStars(avgRating);

            // Render reviews
            renderReviewsList();
          }
        } else {
          reviewsList.innerHTML = '<div style="text-align: center; color: #999; padding: 30px;">Kh�ng t?m th?y th�ng tin b�c s?</div>';
        }
      } catch (err) {
        console.error('Error loading reviews:', err);
        reviewsList.innerHTML = '<div style="text-align: center; color: #999; padding: 30px;">Kh�ng th? t?i ��nh gi�</div>';
      }
    }

    function renderReviewsList() {
      const reviewsList = document.getElementById('reviewsList');

      if (!doctorReviews || doctorReviews.length === 0) {
        reviewsList.innerHTML = '<div style="text-align: center; color: #999; padding: 30px;"><i class="ri-star-line" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>Ch�a c� ��nh gi� n�o</div>';
        return;
      }

      reviewsList.innerHTML = doctorReviews.map(review => `
        <div style="background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 15px;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
            <div>
              <strong style="color: #333;">${review.patient_name || 'B?nh nh�n'}</strong>
              <div style="color: #ffc107; font-size: 14px; margin-top: 3px;">
                ${'?'.repeat(review.rating || 0)}${'?'.repeat(5 - (review.rating || 0))}
              </div>
            </div>
            <span style="color: #999; font-size: 12px;">${formatReviewDate(review.created_at)}</span>
          </div>
          ${review.comment ? `<p style="color: #666; margin: 0; line-height: 1.5;">${review.comment}</p>` : '<p style="color: #999; font-style: italic; margin: 0;">Kh�ng c� nh?n x�t</p>'}
        </div>
      `).join('');
    }

    function formatReviewDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });
    }

    // ============ NOTIFICATIONS ============
    async function loadNotifications() {
      const container = document.getElementById('notificationListContainer');
      if (!container) return;

      try {
        const token = localStorage.getItem('access_token');
        if (!token) {
          container.innerHTML = '<p style="text-align:center;color:#999;padding:20px;">Vui l?ng ��ng nh?p �? xem th�ng b�o</p>';
          return;
        }

        const response = await fetch('/api/doctor/notifications', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        if (response.ok) {
          notifications = await response.json();
          renderNotifications();
          updateNavBadge();
        }
      } catch (err) {
        console.error('Error loading notifications:', err);
        container.innerHTML = '<p style="text-align:center;color:#999;padding:20px;">Kh�ng th? t?i th�ng b�o</p>';
      }
    }

    function renderNotifications() {
      const container = document.getElementById('notificationListContainer');
      if (!container) return;

      if (!notifications || notifications.length === 0) {
        container.innerHTML = `
          <div style="text-align:center;padding:40px;color:#999;">
            <i class="ri-notification-off-line" style="font-size:48px;color:#ccc;"></i>
            <p style="margin-top:15px;">Ch�a c� th�ng b�o n�o</p>
          </div>
        `;
        return;
      }

      container.innerHTML = notifications.map(notif => {
        let iconClass = 'notif-schedule';
        let iconName = 'ri-notification-3-line';

        // Type: 1 = l?ch h?n, 2 = ��nh gi�, 3 = forum, 4 = admin
        switch (notif.type) {
          case 1:
            iconClass = 'notif-schedule';
            iconName = 'ri-calendar-line';
            break;
          case 2:
            iconClass = 'notif-change';
            iconName = 'ri-star-line';
            break;
          case 3:
            // Forum notifications - check message content
            if (notif.message && notif.message.includes('like')) {
              iconClass = 'notif-like';
              iconName = 'ri-heart-line';
            } else if (notif.message && notif.message.includes('b?nh lu?n')) {
              iconClass = 'notif-comment';
              iconName = 'ri-chat-3-line';
            } else if (notif.message && notif.message.includes('l�?t xem')) {
              iconClass = 'notif-view';
              iconName = 'ri-eye-line';
            } else {
              iconClass = 'notif-system';
              iconName = 'ri-message-3-line';
            }
            break;
          case 4:
            iconClass = 'notif-system';
            iconName = 'ri-information-line';
            break;
        }

        return `
          <div class="notification-item ${notif.is_read ? '' : 'unread'}" 
               data-id="${notif.id}"
               onmousedown="startDragNotification(event, ${notif.id})"
               onclick="markNotificationRead(${notif.id})">
            <div class="notification-icon ${iconClass}">
              <i class="${iconName}"></i>
            </div>
            <div class="notification-content">
              <h4>${notif.title || 'Th�ng b�o'}</h4>
              <p>${notif.message || ''}</p>
            </div>
            <div class="notification-time">${notif.created_at_human || ''}</div>
            <span class="unread-badge"></span>
          </div>
        `;
      }).join('');

      // Initialize drag for notifications
      initNotificationDrag();
    }

    function updateNavBadge() {
      const navBadge = document.getElementById('navNotifBadge');
      const topBadge = document.querySelector('.icon-btn .badge');

      const unreadCount = notifications.filter(n => !n.is_read).length;

      if (navBadge) {
        if (unreadCount > 0) {
          navBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
          navBadge.style.display = 'flex';
        } else {
          navBadge.style.display = 'none';
        }
      }

      if (topBadge) {
        if (unreadCount > 0) {
          topBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
          topBadge.style.display = 'flex';
        } else {
          topBadge.style.display = 'none';
        }
      }
    }

    async function markNotificationRead(id) {
      try {
        const token = localStorage.getItem('access_token');
        await fetch(`/api/profile/notifications/${id}/read`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        const notif = notifications.find(n => n.id === id);
        if (notif) {
          notif.is_read = true;
          
          // Navigate to the related content based on notification type
          navigateToNotificationTarget(notif);
        }

        renderNotifications();
        updateNavBadge();
      } catch (err) {
        console.error('Error marking notification read:', err);
      }
    }
    
    // Navigate to the appropriate page based on notification type
    function navigateToNotificationTarget(notif) {
      const relatedId = notif.related_id;
      
      switch (notif.type) {
        case 1: // L?ch h?n (Appointment)
          if (relatedId) {
            // Find the appointment in doctorAppointments and open detail modal
            const appointment = doctorAppointments.find(apt => apt.id === relatedId);
            if (appointment) {
              closeModal('notificationsModal');
              // Determine time status
              const now = new Date();
              const aptDate = new Date(appointment.date + 'T' + (appointment.start_time || '00:00'));
              const endTime = new Date(appointment.date + 'T' + (appointment.end_time || '23:59'));
              
              let timeStatus = 'scheduled';
              if (now > endTime) {
                timeStatus = 'ended';
              } else if (now >= aptDate && now <= endTime) {
                timeStatus = 'ongoing';
              } else if (now < aptDate) {
                timeStatus = 'upcoming';
              }
              
              openAppointmentDetail(appointment, timeStatus);
            } else {
              // Appointment not in current list, switch to schedule section
              closeModal('notificationsModal');
              const scheduleNav = document.querySelector('.nav-item[data-section="schedule"]');
              if (scheduleNav) scheduleNav.click();
              showToast('L?ch h?n c� th? �? ��?c c?p nh?t. Vui l?ng ki?m tra l?ch l�m vi?c.', 'info');
            }
          } else {
            // No related_id, just switch to schedule section
            closeModal('notificationsModal');
            const scheduleNav = document.querySelector('.nav-item[data-section="schedule"]');
            if (scheduleNav) scheduleNav.click();
          }
          break;
          
        case 2: // ��nh gi� (Review)
          closeModal('notificationsModal');
          // Open reviews modal or switch to reviews section
          if (typeof openReviewsModal === 'function') {
            openReviewsModal();
          } else {
            showToast('Xem ��nh gi� trong ph?n th�ng tin c� nh�n', 'info');
          }
          break;
          
        case 3: // Forum
          if (relatedId) {
            closeModal('notificationsModal');
            // Navigate to forum section within doctor profile
            const forumNav = document.querySelector('.nav-item[data-section="forum"]');
            if (forumNav) {
              forumNav.click();
              // Try to open the specific post after a delay
              setTimeout(() => {
                if (typeof openForumPostDetail === 'function') {
                  openForumPostDetail(relatedId);
                }
              }, 500);
            }
          }
          break;
          
        case 4: // Admin/System notification
          // Just close the modal, no specific navigation
          closeModal('notificationsModal');
          break;
          
        default:
          closeModal('notificationsModal');
          break;
      }
    }

    async function markAllNotificationsRead() {
      try {
        const token = localStorage.getItem('access_token');
        await fetch('/api/doctor/notifications/read-all', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        notifications.forEach(n => n.is_read = true);
        renderNotifications();
        updateNavBadge();
        alert('�? ��nh d?u t?t c? th�ng b�o l� �? �?c!');
      } catch (err) {
        console.error('Error:', err);
        alert('C� l?i x?y ra!');
      }
    }

    async function deleteAllNotifications() {
      if (!confirm('B?n c� ch?c mu?n x�a t?t c? th�ng b�o?')) return;

      try {
        const token = localStorage.getItem('access_token');
        await fetch('/api/profile/notifications/all', {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        notifications = [];
        renderNotifications();
        updateNavBadge();
        alert('�? x�a t?t c? th�ng b�o!');
      } catch (err) {
        console.error('Error:', err);
        alert('C� l?i x?y ra!');
      }
    }

    // ============ NOTIFICATION DRAG TO DELETE ============
    let draggedNotifId = null;
    let isDragging = false;
    let startY = 0;
    let startX = 0;
    let notifContainer = null;

    function initNotificationDrag() {
      notifContainer = document.getElementById('notificationListContainer');
    }

    function startDragNotification(e, id) {
      if (e.button !== 0) return; // Only left click

      draggedNotifId = id;
      startX = e.clientX;
      startY = e.clientY;

      document.addEventListener('mousemove', onDragNotification);
      document.addEventListener('mouseup', endDragNotification);
    }

    function onDragNotification(e) {
      if (!draggedNotifId) return;

      const diffX = Math.abs(e.clientX - startX);
      const diffY = Math.abs(e.clientY - startY);

      if (diffX > 10 || diffY > 10) {
        isDragging = true;
        const item = document.querySelector(`.notification-item[data-id="${draggedNotifId}"]`);
        if (item) {
          item.classList.add('dragging');
          item.style.transform = `translate(${e.clientX - startX}px, ${e.clientY - startY}px)`;

          // Check if outside container
          const containerRect = notifContainer.getBoundingClientRect();
          if (e.clientX < containerRect.left || e.clientX > containerRect.right ||
            e.clientY < containerRect.top || e.clientY > containerRect.bottom) {
            item.classList.add('delete-preview');
          } else {
            item.classList.remove('delete-preview');
          }
        }
      }
    }

    function endDragNotification(e) {
      document.removeEventListener('mousemove', onDragNotification);
      document.removeEventListener('mouseup', endDragNotification);

      if (isDragging && draggedNotifId) {
        const item = document.querySelector(`.notification-item[data-id="${draggedNotifId}"]`);
        if (item) {
          item.classList.remove('dragging');
          item.style.transform = '';

          // Check if was dragged outside
          if (item.classList.contains('delete-preview')) {
            item.classList.remove('delete-preview');
            pendingDeleteNotifId = draggedNotifId;
            document.getElementById('deleteNotifModal').classList.add('active');
          }
        }
      }

      draggedNotifId = null;
      isDragging = false;
    }

    async function confirmDeleteNotification() {
      if (!pendingDeleteNotifId) return;

      try {
        const token = localStorage.getItem('access_token');
        await fetch(`/api/doctor/notifications/${pendingDeleteNotifId}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        notifications = notifications.filter(n => n.id !== pendingDeleteNotifId);
        renderNotifications();
        updateNavBadge();
        closeModal('deleteNotifModal');
        pendingDeleteNotifId = null;
      } catch (err) {
        console.error('Error:', err);
        alert('C� l?i x?y ra!');
      }
    }

    // Logout button
    document.querySelector('.logout-btn').addEventListener('click', () => {
      if (confirm('B?n c� ch?c mu?n ��ng xu?t?')) {
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
        window.location.href = '{{ route("dang-nhap") }}';
      }
    });

    // Modal functions
    function openModal(modalId) {
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.add('active');
      }
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.remove('active');
    }

    function openEditNoteModal(noteId) {
      const note = medicalNotes.find(n => n.id === noteId);
      if (!note) return;

      currentEditingNoteId = noteId;
      document.getElementById('editPatientName').value = note.patient_name || '';
      document.getElementById('editPatientId').value = note.patient_id || '';
      // document.getElementById('editSpecialty').value = ''; // Not used in API currently
      document.getElementById('editSymptoms').value = note.symptoms || '';
      document.getElementById('editDiagnosis').value = note.diagnosis || '';
      document.getElementById('editPrescription').value = note.prescription || '';

      document.getElementById('editNoteModal').classList.add('active');
    }

    async function saveEditNote() {
      if (!currentEditingNoteId) return;

      const symptoms = document.getElementById('editSymptoms').value;
      const diagnosis = document.getElementById('editDiagnosis').value;
      const prescription = document.getElementById('editPrescription').value;

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch(`/api/doctor/medical-notes/${currentEditingNoteId}`, {
          method: 'PATCH',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            symptoms: symptoms,
            diagnosis: diagnosis,
            prescription: prescription
          })
        });

        if (response.ok) {
          showToast('Ghi ch� �? ��?c c?p nh?t!', 'success');
          closeModal('editNoteModal');
          loadMedicalNotes();
        } else {
          const data = await response.json();
          showToast(data.message || 'C� l?i x?y ra!', 'error');
        }
      } catch (err) {
        console.error(err);
        showToast('L?i k?t n?i!', 'error');
      }
    }

    function openNewThreadModal() {
      document.getElementById('newThreadModal').classList.add('active');
    }

    function submitNewThread() {
      const title = document.getElementById('threadTitle').value;
      const content = document.getElementById('threadContent').value;

      if (!title || !content) {
        alert('Vui l?ng �i?n �?y �? ti�u �? v� n?i dung!');
        return;
      }

      alert('Ch? �? m?i �? ��?c ��ng th�nh c�ng!');
      closeModal('newThreadModal');
    }

    function openChangePasswordModal() {
      document.getElementById('changePasswordModal').classList.add('active');
    }

    async function submitChangePassword() {
      const current = document.getElementById('currentPassword').value;
      const newPass = document.getElementById('newPassword').value;
      const confirmPass = document.getElementById('confirmPassword').value;

      if (!current || !newPass || !confirmPass) {
        showToast('Vui l?ng �i?n �?y �? th�ng tin!', 'error');
        return;
      }

      if (newPass !== confirmPass) {
        showToast('M?t kh?u x�c nh?n kh�ng kh?p!', 'error');
        return;
      }

      if (newPass.length < 8) {
        showToast('M?t kh?u ph?i c� �t nh?t 8 k? t?!', 'error');
        return;
      }

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch('/api/profile/change-password', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            current_password: current,
            new_password: newPass,
            new_password_confirmation: confirmPass
          })
        });

        const data = await response.json();

        if (response.ok && data.success) {
          showToast('�?i m?t kh?u th�nh c�ng!', 'success');
          document.getElementById('currentPassword').value = '';
          document.getElementById('newPassword').value = '';
          document.getElementById('confirmPassword').value = '';
          closeModal('changePasswordModal');
        } else {
          showToast(data.message || 'C� l?i x?y ra!', 'error');
        }
      } catch (err) {
        console.error(err);
        showToast('Kh�ng th? �?i m?t kh?u. Vui l?ng th? l?i.', 'error');
      }
    }

    async function openLoginHistoryModal() {
      document.getElementById('loginHistoryModal').classList.add('active');
      await loadLoginHistory();
    }

    async function loadLoginHistory() {
      const historyContainer = document.querySelector('#loginHistoryModal .login-history-list');
      if (!historyContainer) return;

      historyContainer.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="ri-loader-4-line" style="font-size: 30px; animation: spin 1s linear infinite;"></i></div>';

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch('/api/profile/login-history', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        const data = await response.json();

        if (response.ok && data.success && data.data.length > 0) {
          historyContainer.innerHTML = data.data.map(item => `
            <div class="login-history-item">
              <div class="history-device">
                <i class="ri-computer-line"></i>
                <div>
                  <strong>${item.device}</strong>
                  <small>${item.ip_address}</small>
                </div>
              </div>
              <div class="history-time">
                <span>${item.created_at}</span>
                <small>${item.last_used}</small>
              </div>
            </div>
          `).join('');
        } else if (data.data && data.data.length === 0) {
          historyContainer.innerHTML = '<p style="text-align: center; padding: 20px; color: #888;">Ch�a c� l?ch s? ��ng nh?p</p>';
        } else {
          historyContainer.innerHTML = '<p style="text-align: center; padding: 20px; color: #f44336;">Kh�ng th? t?i l?ch s? ��ng nh?p</p>';
        }
      } catch (err) {
        console.error(err);
        historyContainer.innerHTML = '<p style="text-align: center; padding: 20px; color: #f44336;">L?i k?t n?i</p>';
      }
    }

    // Tag input functionality
    const tagInput = document.getElementById('tagInput');
    const tagWrapper = document.getElementById('tagInputWrapper');
    const tags = [];

    if (tagInput) {
      tagInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && tagInput.value.trim()) {
          e.preventDefault();
          const tagValue = tagInput.value.trim();

          if (!tags.includes(tagValue)) {
            tags.push(tagValue);
            const tagElement = document.createElement('span');
            tagElement.className = 'tag-item';
            tagElement.innerHTML = `${tagValue} <span class="remove-tag" onclick="removeTag('${tagValue}')">&times;</span>`;
            tagWrapper.insertBefore(tagElement, tagInput);
          }
          tagInput.value = '';
        }
      });
    }

    function removeTag(tagValue) {
      const index = tags.indexOf(tagValue);
      if (index > -1) tags.splice(index, 1);

      const tagElements = document.querySelectorAll('.tag-item');
      tagElements.forEach(el => {
        if (el.textContent.includes(tagValue)) el.remove();
      });
    }

    // Load services for selected specialty - checkbox style
    async function loadServicesForSpecialty(specId) {
      const servicesContainer = document.getElementById('servicesContainer');
      const servicesSelect = document.getElementById('profileServices');
      if (!servicesContainer) return;

      // Clear hidden select
      if (servicesSelect) servicesSelect.innerHTML = '';

      if (!specId) {
        servicesContainer.innerHTML = '<span style="color: #999; font-size: 14px;">Ch?n chuy�n khoa tr�?c �? hi?n th? d?ch v?</span>';
        return;
      }

      servicesContainer.innerHTML = '<span style="color: #999; font-size: 14px;"><i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> �ang t?i...</span>';

      try {
        const response = await fetch(`/api/public/services?specialization_id=${specId}`);
        if (response.ok) {
          const result = await response.json();
          const services = result.data || result; // Handle both {data:[]} and [] formats
          if (services && Array.isArray(services) && services.length > 0) {
            servicesContainer.innerHTML = '';
            services.forEach(service => {
              const checkbox = document.createElement('label');
              checkbox.style.cssText = 'display: flex; align-items: center; gap: 6px; padding: 8px 12px; background: #fff; border: 2px solid #e8eaf6; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 14px;';
              checkbox.innerHTML = `
                <input type="checkbox" name="profileService" value="${service.id}" style="width: 16px; height: 16px; accent-color: #4a69bd;">
                <span>${service.name}</span>
                <small style="color: #4a69bd; font-weight: 500;">${service.price ? parseInt(service.price).toLocaleString() + '�' : ''}</small>
              `;
              checkbox.addEventListener('mouseenter', () => {
                checkbox.style.borderColor = '#4a69bd';
                checkbox.style.background = '#f0f5ff';
              });
              checkbox.addEventListener('mouseleave', () => {
                const input = checkbox.querySelector('input');
                if (!input.checked) {
                  checkbox.style.borderColor = '#e8eaf6';
                  checkbox.style.background = '#fff';
                }
              });
              checkbox.querySelector('input').addEventListener('change', (e) => {
                if (e.target.checked) {
                  checkbox.style.borderColor = '#4a69bd';
                  checkbox.style.background = '#e8f4ff';
                } else {
                  checkbox.style.borderColor = '#e8eaf6';
                  checkbox.style.background = '#fff';
                }
                updateHiddenSelect();
              });
              servicesContainer.appendChild(checkbox);
            });
          } else {
            servicesContainer.innerHTML = '<span style="color: #999; font-size: 14px;">Ch�a c� d?ch v? cho chuy�n khoa n�y</span>';
          }
        }
      } catch (error) {
        console.error('Error loading services:', error);
        servicesContainer.innerHTML = '<span style="color: #e74c3c; font-size: 14px;">L?i t?i d?ch v?</span>';
      }
    }

    // Update hidden select from checkboxes
    function updateHiddenSelect() {
      const servicesSelect = document.getElementById('profileServices');
      const checkboxes = document.querySelectorAll('input[name="profileService"]:checked');

      servicesSelect.innerHTML = '';
      checkboxes.forEach(cb => {
        const option = document.createElement('option');
        option.value = cb.value;
        option.selected = true;
        servicesSelect.appendChild(option);
      });
    }

    async function changeAvatar(event) {
      const file = event.target.files[0];
      if (!file) return;

      const token = localStorage.getItem('access_token');
      if (!token) {
        alert('Vui l?ng ��ng nh?p l?i!');
        return;
      }

      // Show preview immediately
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('profileImage').src = e.target.result;
      };
      reader.readAsDataURL(file);

      // Upload to server
      try {
        const formData = new FormData();
        formData.append('avatar', file);

        const response = await fetch('/api/profile/avatar', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`
          },
          body: formData
        });

        if (response.ok) {
          const data = await response.json();
          if (data.avatar_url) {
            document.getElementById('profileImage').src = data.avatar_url;
          }
          alert('?nh �?i di?n �? ��?c c?p nh?t!');
        } else {
          const err = await response.json();
          alert('L?i: ' + (err.message || 'Kh�ng th? upload ?nh'));
        }
      } catch (err) {
        console.error('Avatar upload error:', err);
        alert('C� l?i x?y ra khi upload ?nh');
      }
    }

    // Load saved profile on page load
    document.addEventListener('DOMContentLoaded', async function() {
      let doctorName = 'B�c s?';
      let doctorId = '';
      let doctorAvatar = '/frontend/img/logocanhan.jpg';

      // Try to load from API first
      const token = localStorage.getItem('access_token');
      if (!token) {
        // No token, redirect to login
        window.location.href = '/dang-nhap';
        return;
      }

      try {
        // First check if user is actually a DOCTOR
        const checkResponse = await fetch('/api/profile/me', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        if (!checkResponse.ok) {
          // Token invalid, redirect to login
          localStorage.removeItem('access_token');
          localStorage.removeItem('refresh_token');
          localStorage.removeItem('session_id');
          window.location.href = '/dang-nhap';
          return;
        }

        const userData = await checkResponse.json();

        // Check user type - redirect if not DOCTOR
        if (userData.type !== 'DOCTOR') {
          if (userData.type === 'ADMIN') {
            window.location.href = '/quan-tri';
          } else {
            window.location.href = '/ho-so';
          }
          return;
        }

        // User is DOCTOR, continue loading profile
        document.getElementById('profileName').value = userData.full_name || '';
        document.getElementById('profileGender').value = userData.gender || 'MALE';
        document.getElementById('profileBirthday').value = userData.dob || '';
        document.getElementById('profilePhone').value = userData.phone || '';
        document.getElementById('profileEmail').value = userData.email || '';
        document.getElementById('profileAddress').value = userData.address || '';

        doctorName = userData.full_name || 'B�c s?';
        doctorId = userData.email || 'doctor-' + Date.now();

        if (userData.avatar_url) {
          document.getElementById('profileImage').src = userData.avatar_url;
          doctorAvatar = userData.avatar_url;
        }

        // Load specializations for the select dropdown
        const specResponse = await fetch('/api/public/specializations');
        if (specResponse.ok) {
          const specResult = await specResponse.json();
          const specializations = specResult.data || specResult; // Handle both {data:[]} and [] formats
          const specSelect = document.getElementById('profileSpecialty');
          if (Array.isArray(specializations)) {
            specializations.forEach(spec => {
              const option = document.createElement('option');
              option.value = spec.id;
              option.textContent = spec.name;
              specSelect.appendChild(option);
            });
          }

          // Add event listener for specialty change to load services
          specSelect.addEventListener('change', function() {
            loadServicesForSpecialty(this.value);
          });
        }

        // Load clinics for the select dropdown
        const clinicResponse = await fetch('/api/clinics', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        if (clinicResponse.ok) {
          const clinicResult = await clinicResponse.json();
          const clinics = clinicResult.data || clinicResult;
          const clinicSelect = document.getElementById('profileClinic');
          if (clinicSelect && Array.isArray(clinics)) {
            clinics.forEach(clinic => {
              const option = document.createElement('option');
              option.value = clinic.id;
              option.textContent = clinic.name;
              clinicSelect.appendChild(option);
            });
          }
        }

        // Load doctor profile (specialization, degree, experience, services, clinic)
        const doctorResponse = await fetch('/api/profile/doctor', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        if (doctorResponse.ok) {
          const doctorData = await doctorResponse.json();
          if (doctorData.doctor) {
            // Set specialty by ID after loading options
            const specId = doctorData.doctor.specialization_id;
            if (specId) {
              document.getElementById('profileSpecialty').value = specId;
              // Load services for the selected specialty
              await loadServicesForSpecialty(specId);
            }
            
            // Set clinic by ID after loading options
            const clinicId = doctorData.doctor.clinic_id;
            if (clinicId) {
              const clinicSelect = document.getElementById('profileClinic');
              if (clinicSelect) clinicSelect.value = clinicId;
            }
            
            document.getElementById('profileDegree').value = doctorData.doctor.degree || '';
            document.getElementById('profileExperience').value = doctorData.doctor.experience || '';

            // Set selected services (checkbox style)
            if (doctorData.doctor.service_ids && doctorData.doctor.service_ids.length > 0) {
              const serviceIds = doctorData.doctor.service_ids;
              // Wait a bit for checkboxes to be rendered
              setTimeout(() => {
                const checkboxes = document.querySelectorAll('input[name="profileService"]');
                checkboxes.forEach(cb => {
                  if (serviceIds.includes(parseInt(cb.value))) {
                    cb.checked = true;
                    cb.closest('label').style.borderColor = '#4a69bd';
                    cb.closest('label').style.background = '#e8f4ff';
                  }
                });
                updateHiddenSelect();
              }, 300);
            }
          }
        }

        // Save to localStorage as backup
        localStorage.setItem('doctorProfile', JSON.stringify({
          name: document.getElementById('profileName').value,
          gender: document.getElementById('profileGender').value,
          birthday: document.getElementById('profileBirthday').value,
          phone: document.getElementById('profilePhone').value,
          email: document.getElementById('profileEmail').value,
          address: document.getElementById('profileAddress').value,
          specialty: document.getElementById('profileSpecialty').value,
          degree: document.getElementById('profileDegree').value,
          experience: document.getElementById('profileExperience').value
        }));

      } catch (err) {
        console.error('Error loading profile from API:', err);
      }

      // Fallback to localStorage if needed (shouldn't happen with role check)
      const savedProfile = localStorage.getItem('doctorProfile');
      if (savedProfile && !document.getElementById('profileEmail').value) {
        const profileData = JSON.parse(savedProfile);
        document.getElementById('profileName').value = profileData.name || '';
        document.getElementById('profileGender').value = profileData.gender || 'MALE';
        document.getElementById('profileBirthday').value = profileData.birthday || '';
        document.getElementById('profilePhone').value = profileData.phone || '';
        document.getElementById('profileEmail').value = profileData.email || '';
        document.getElementById('profileSpecialty').value = profileData.specialty || '';
        document.getElementById('profileDegree').value = profileData.degree || '';
        document.getElementById('profileExperience').value = profileData.experience || '';
        document.getElementById('profileAddress').value = profileData.address || '';

        doctorName = profileData.name || 'B�c s?';
        doctorId = profileData.email || 'doctor-' + Date.now();
      }

      const savedAvatar = localStorage.getItem('doctorAvatar');
      if (savedAvatar && !document.getElementById('profileImage').src.includes('http')) {
        document.getElementById('profileImage').src = savedAvatar;
        doctorAvatar = savedAvatar;
      }

      // Update forum container v?i th�ng tin b�c s? th?c
      const forumContainer = document.getElementById('doctorForumContainer');
      if (forumContainer) {
        forumContainer.dataset.userId = doctorId;
        forumContainer.dataset.userName = doctorName;
        forumContainer.dataset.userAvatar = doctorAvatar;
        forumContainer.dataset.userType = 'doctor';
      }

      // Load notifications initially
      loadNotifications();

      // Load doctor rating and review count
      loadDoctorRating();

      // Kh?i t?o forum sau khi �? c� th�ng tin b�c s?
      if (typeof window.initForumSync === 'function') {
        window.initForumSync();
      }
    });

    async function deleteNote(buttonElement, noteId) {
      if (confirm('B?n c� ch?c ch?n mu?n x�a ghi ch� kh�m b?nh n�y?\n\nH�nh �?ng n�y kh�ng th? ho�n t�c!')) {
        try {
          const token = localStorage.getItem('access_token');
          const response = await fetch(`/api/doctor/medical-notes/${noteId}`, {
            method: 'DELETE',
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });

          if (response.ok) {
            showToast('Ghi ch� kh�m b?nh �? ��?c x�a th�nh c�ng!', 'success');
            loadMedicalNotes();
          } else {
            const data = await response.json();
            showToast(data.message || 'C� l?i x?y ra!', 'error');
          }
        } catch (err) {
          console.error(err);
          showToast('L?i k?t n?i!', 'error');
        }
      }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
      }
    };

    // ============ PATIENT AUTOCOMPLETE FOR MEDICAL NOTES ============
    let completedPatients = [];
    let patientSearchTimeout = null;

    async function loadCompletedPatients() {
      try {
        const token = localStorage.getItem('access_token');
        if (!token) {
          console.warn('No access token for loading patients');
          return;
        }
        const response = await fetch('/api/doctor/completed-patients', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        if (!response.ok) {
          // API might not exist or user not authorized - fail silently
          console.warn('Could not load completed patients:', response.status);
          return;
        }
        const data = await response.json();
        if (data.success && data.data) {
          completedPatients = data.data;
        } else if (Array.isArray(data)) {
          completedPatients = data;
        }
      } catch (err) {
        console.warn('Error loading completed patients:', err.message);
      }
    }

    // Initialize patient autocomplete
    const patientNameInput = document.getElementById('newPatientName');
    const patientSuggestions = document.getElementById('patientSuggestions');

    if (patientNameInput && patientSuggestions) {
      patientNameInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();

        if (query.length < 1) {
          patientSuggestions.classList.remove('show');
          return;
        }

        clearTimeout(patientSearchTimeout);
        patientSearchTimeout = setTimeout(() => {
          searchPatients(query);
        }, 200);
      });

      patientNameInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 1) {
          searchPatients(this.value.trim().toLowerCase());
        }
      });

      document.addEventListener('click', function(e) {
        if (!patientNameInput.contains(e.target) && !patientSuggestions.contains(e.target)) {
          patientSuggestions.classList.remove('show');
        }
      });
    }

    async function searchPatients(query) {
      const suggestionsContainer = document.getElementById('patientSuggestions');

      // If we have cached data, filter locally
      if (completedPatients.length > 0) {
        const filtered = completedPatients.filter(p =>
          p.patient_name.toLowerCase().includes(query) ||
          (p.patient_id && p.patient_id.toString().includes(query))
        ).slice(0, 10);

        displayPatientSuggestions(filtered);
        return;
      }

      // Otherwise fetch from API
      suggestionsContainer.innerHTML = '<div class="autocomplete-loading"><i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> �ang t?m...</div>';
      suggestionsContainer.classList.add('show');

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch(`/api/doctor/completed-patients?search=${encodeURIComponent(query)}`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        const data = await response.json();

        if (data.success && data.data) {
          completedPatients = data.data;
          displayPatientSuggestions(data.data.slice(0, 10));
        } else {
          suggestionsContainer.innerHTML = '<div class="autocomplete-loading">Kh�ng t?m th?y b?nh nh�n</div>';
        }
      } catch (err) {
        console.error(err);
        suggestionsContainer.innerHTML = '<div class="autocomplete-loading">L?i t?i d? li?u</div>';
      }
    }

    function displayPatientSuggestions(patients) {
      const suggestionsContainer = document.getElementById('patientSuggestions');

      if (patients.length === 0) {
        suggestionsContainer.innerHTML = '<div class="autocomplete-loading">Kh�ng t?m th?y b?nh nh�n (�? ho�n th�nh ho?c �ang kh�m)</div>';
        suggestionsContainer.classList.add('show');
        return;
      }

      suggestionsContainer.innerHTML = patients.map(p => {
        const statusBadge = p.status === 'IN_PROGRESS' ?
          '<span class="status-badge in-progress">�ang kh�m</span>' :
          '<span class="status-badge completed">Ho�n th�nh</span>';
        return `
        <div class="autocomplete-item" onclick="selectPatient('${p.patient_name}', '${p.patient_id || ''}', '${p.user_id || ''}', '${p.appointment_date || ''}', '${p.appointment_id || ''}')">
          <div>
            <div class="patient-name">${p.patient_name} ${statusBadge}</div>
            <div class="patient-info">M?: ${p.patient_id || 'BN' + (p.user_id || '').toString().padStart(6, '0')}</div>
          </div>
          ${p.appointment_date ? `<span class="appointment-date">${formatDate(p.appointment_date)}</span>` : ''}
        </div>
      `
      }).join('');

      suggestionsContainer.classList.add('show');
    }

    function selectPatient(name, patientId, userId, appointmentDate, appointmentId) {
      document.getElementById('newPatientName').value = name;
      document.getElementById('newPatientId').value = patientId || 'BN' + (userId || '').toString().padStart(6, '0');
      document.getElementById('newPatientUserId').value = userId;
      if (document.getElementById('newAppointmentId')) {
        document.getElementById('newAppointmentId').value = appointmentId || '';
      }
      document.getElementById('patientSuggestions').classList.remove('show');

      // If appointment date exists, set it
      if (appointmentDate) {
        document.getElementById('newNoteDate').value = appointmentDate.split(' ')[0];
      }
    }

    function formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
    }

    // ============ MEDICAL NOTES FUNCTIONS ============
    let medicalNotes = [];
    let currentEditingNoteId = null;

    async function loadMedicalNotes() {
      const container = document.getElementById('patientNotesList');
      if (!container) return;

      container.innerHTML = '<div style="text-align: center; padding: 40px; color: #7f8c8d;"><i class="ri-loader-4-line" style="font-size: 32px; animation: spin 1s linear infinite;"></i><p>�ang t?i ghi ch�...</p></div>';

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch('/api/doctor/medical-notes', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        if (response.ok) {
          const result = await response.json();
          medicalNotes = result.data || [];
          renderMedicalNotes();
        } else {
          container.innerHTML = '<div style="text-align: center; padding: 40px; color: #e74c3c;"><p>Kh�ng th? t?i ghi ch�</p></div>';
        }
      } catch (err) {
        console.error('Error loading notes:', err);
        container.innerHTML = '<div style="text-align: center; padding: 40px; color: #e74c3c;"><p>L?i k?t n?i</p></div>';
      }
    }

    function renderMedicalNotes() {
      const container = document.getElementById('patientNotesList');
      if (!container) return;

      if (medicalNotes.length === 0) {
        container.innerHTML = `
          <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <i class="ri-file-text-line" style="font-size: 64px; opacity: 0.3;"></i>
            <p style="margin-top: 15px; font-size: 16px;">Ch�a c� ghi ch� kh�m b?nh n�o</p>
          </div>
        `;
        return;
      }

      container.innerHTML = medicalNotes.map(note => `
        <div class="patient-note-card" data-id="${note.id}">
          <div class="note-header">
            <div class="note-patient-info">
              <h3>${note.patient_name || 'B?nh nh�n'}</h3>
              <p>M? BN: ${note.patient_id || 'N/A'}</p>
            </div>
            <div class="note-date">${formatDate(note.visit_date)}</div>
          </div>
          <div class="note-content">
            <h4>Tri?u ch?ng:</h4>
            <p>${note.symptoms || ''}</p>
            <h4 style="margin-top: 10px;">Ch?n �o�n:</h4>
            <p>${note.diagnosis || ''}</p>
            <h4 style="margin-top: 10px;">��n thu?c / �i?u tr?:</h4>
            <p>${note.prescription || ''}</p>
          </div>
          <div class="note-actions">
            <button class="btn-edit" onclick="openEditNoteModal(${note.id})">
              <i class="ri-edit-line"></i> S?a
            </button>
            <button class="btn-delete" onclick="deleteNote(this, ${note.id})">
              <i class="ri-delete-bin-line"></i> X�a
            </button>
          </div>
        </div>
      `).join('');
    }

    function openNewNoteModal() {
      const today = new Date();
      const dateStr = `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
      document.getElementById('newNoteDate').value = dateStr;
      document.getElementById('newPatientName').value = '';
      document.getElementById('newPatientId').value = '';
      document.getElementById('newPatientUserId').value = '';
      if (document.getElementById('newAppointmentId')) {
        document.getElementById('newAppointmentId').value = '';
      }
      document.getElementById('newSymptoms').value = '';
      document.getElementById('newDiagnosis').value = '';
      document.getElementById('newPrescription').value = '';

      // Load completed patients for autocomplete
      loadCompletedPatients();

      document.getElementById('newNoteModal').classList.add('active');
    }

    async function submitNewNote() {
      const appointmentId = document.getElementById('newAppointmentId').value;
      const noteDate = document.getElementById('newNoteDate').value;
      const symptoms = document.getElementById('newSymptoms').value;
      const diagnosis = document.getElementById('newDiagnosis').value;
      const prescription = document.getElementById('newPrescription').value;

      if (!appointmentId) {
        showToast('Vui l?ng ch?n b?nh nh�n t? danh s�ch g?i ?!', 'error');
        return;
      }

      if (!symptoms || !diagnosis) {
        showToast('Vui l?ng nh?p tri?u ch?ng v� ch?n �o�n!', 'error');
        return;
      }

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch('/api/doctor/medical-notes', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            appointment_id: appointmentId,
            visit_date: noteDate,
            symptoms: symptoms,
            diagnosis: diagnosis,
            prescription: prescription
          })
        });

        const data = await response.json();

        if (response.ok) {
          showToast('Ghi ch� kh�m b?nh m?i �? ��?c t?o th�nh c�ng!', 'success');
          closeModal('newNoteModal');
          loadMedicalNotes();
        } else {
          showToast(data.message || 'C� l?i x?y ra!', 'error');
        }
      } catch (err) {
        console.error(err);
        showToast('L?i k?t n?i!', 'error');
      }
    }

    // ============ FEEDBACK TO ADMIN ============
    async function submitFeedback() {
      const feedback = document.getElementById('feedbackTextarea').value.trim();

      if (!feedback) {
        showToast('Vui l?ng nh?p n?i dung g�p ?!', 'error');
        return;
      }

      try {
        const token = localStorage.getItem('access_token');
        const response = await fetch('/api/profile/feedback', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            content: feedback,
            type: 'feedback'
          })
        });

        const data = await response.json();

        if (response.ok && data.success) {
          showToast('C?m �n b?n �? g�p ?! Th�ng �i?p �? ��?c g?i t?i Admin.', 'success');
          document.getElementById('feedbackTextarea').value = '';
        } else {
          showToast(data.message || 'C� l?i x?y ra. Vui l?ng th? l?i.', 'error');
        }
      } catch (err) {
        console.error(err);
        showToast('C� l?i x?y ra. Vui l?ng th? l?i.', 'error');
      }
    }

    // Add CSS keyframe for spin animation
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
      @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(styleSheet);
  </script>
  <script src="{{ asset('frontend/js/forum-sync.js') }}"></script>
</body>

</html>