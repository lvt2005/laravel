(() => {
  const STORAGE_KEY = 'sharedForumThreads';
  const SAMPLE_POSTS = [
    {
      id: 'post-001',
      title: 'Xin hướng dẫn chuẩn bị nội soi dạ dày?',
      content:
        'Bệnh nhân nữ 28 tuổi lần đầu nội soi dạ dày. Vui lòng tư vấn giúp chuẩn bị ăn uống và những lưu ý cần ký xác nhận.',
      authorName: 'An Nhiên',
      authorId: 'patient-001',
      authorType: 'patient',
      createdAt: '2025-10-20T08:30:00+07:00',
      status: 'answered',
      replies: [
        {
          id: 'reply-001',
          authorName: 'BS. Trần Minh',
          authorId: 'doctor-100',
          authorType: 'doctor',
          content:
            'Nhịn ăn tối thiểu 6 giờ, không uống đồ có màu, ký cam kết gây mê trước 30 phút và mang theo kết quả xét nghiệm máu mới nhất.',
          createdAt: '2025-10-20T08:45:00+07:00'
        }
      ],
      updatedAt: '2025-10-20T08:45:00+07:00'
    },
    {
      id: 'post-002',
      title: 'Chế độ ăn uống cho người bị thiếu máu?',
      content:
        'Tôi vừa được chẩn đoán thiếu máu nhẹ. Xin bác sĩ tư vấn thực phẩm nên bổ sung để cải thiện tình trạng này?',
      authorName: 'Hoàng Minh',
      authorId: 'patient-002',
      authorType: 'patient',
      createdAt: '2025-10-15T10:00:00+07:00',
      status: 'pending',
      replies: [],
      updatedAt: '2025-10-15T10:00:00+07:00'
    },
    {
      id: 'post-003',
      title: 'Đề xuất hướng dẫn chung cho diễn đàn user',
      content:
        'Đề nghị pin bài “Hướng dẫn cung cấp triệu chứng chuẩn” để giảm nhập liệu thiếu thông tin. Cần admin duyệt để hiển thị cho cả 2 bên.',
      authorName: 'BS. Bùi Lan',
      authorId: 'doctor-002',
      authorType: 'doctor',
      createdAt: '2025-10-18T10:05:00+07:00',
      status: 'answered',
      replies: [
        {
          id: 'reply-002',
          authorName: 'Admin phòng khám',
          authorId: 'admin-001',
          authorType: 'staff',
          content: 'Đã ghi nhận và sẽ cập nhật lên banner diễn đàn trong hôm nay.',
          createdAt: '2025-10-18T11:10:00+07:00'
        }
      ],
      updatedAt: '2025-10-18T11:10:00+07:00'
    }
  ];

  const forumApps = document.querySelectorAll('[data-forum-app]');
  if (!forumApps.length) {
    return;
  }

  let forumPosts = loadPosts();
  if (!forumPosts.length) {
    forumPosts = [...SAMPLE_POSTS];
    savePosts(forumPosts);
  }

  const forumInstances = Array.from(forumApps).map(initForumInstance);

  document.querySelectorAll('[data-forum-refresh]').forEach(btn => {
    btn.addEventListener('click', () => {
      forumPosts = loadPosts();
      if (!forumPosts.length) {
        forumPosts = [...SAMPLE_POSTS];
        savePosts(forumPosts);
      }
      forumInstances.forEach(instance => instance.render());
      btn.dataset.justSynced = 'true';
      setTimeout(() => btn.removeAttribute('data-just-synced'), 1500);
    });
  });

  function initForumInstance(app) {
    const currentUser = {
      id: app.dataset.userId || 'guest',
      name: app.dataset.userName || 'Người dùng',
      type: app.dataset.userType || 'patient'
    };

    const listEl = app.querySelector('[data-forum-list]');
    const formEl = app.querySelector('[data-question-form]');
    const filterButtons = app.querySelectorAll('[data-forum-filters] .tab-btn');
    let activeFilter = app.dataset.defaultFilter || 'all';

    if (filterButtons.length) {
      filterButtons.forEach(btn => {
        const btnFilter = btn.dataset.filter;
        if (btnFilter === activeFilter) {
          btn.classList.add('active');
        }
        btn.addEventListener('click', () => {
          filterButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          activeFilter = btnFilter || 'all';
          renderPosts();
        });
      });
    }

    if (formEl) {
      formEl.addEventListener('submit', event => {
        event.preventDefault();
        const titleInput = formEl.querySelector('[data-question-title]');
        const contentInput = formEl.querySelector('[data-question-content]');
        const title = titleInput?.value.trim();
        const content = contentInput?.value.trim();
        if (!title || !content) {
          return;
        }
        const now = new Date().toISOString();
        const newPost = {
          id: generateId('post'),
          title,
          content,
          authorName: currentUser.name,
          authorId: currentUser.id,
          authorType: currentUser.type,
          createdAt: now,
          updatedAt: now,
          status: currentUser.type === 'doctor' ? 'answered' : 'pending',
          replies: currentUser.type === 'doctor'
            ? [
              {
                id: generateId('reply'),
                authorName: currentUser.name,
                authorId: currentUser.id,
                authorType: currentUser.type,
                content:
                  'Thông tin đã được chia sẻ, mời bệnh nhân xem hướng dẫn phía trên.',
                createdAt: now
              }
            ]
            : []
        };

        forumPosts.unshift(newPost);
        savePosts(forumPosts);
        renderAll();
        formEl.reset();
        titleInput?.focus();
      });
    }

    app.addEventListener('submit', event => {
      const form = event.target;
      if (!(form instanceof HTMLFormElement)) return;
      if (!form.matches('[data-reply-form]')) return;
      event.preventDefault();
      const postId = form.dataset.postId;
      const textarea = form.querySelector('textarea');
      const message = textarea?.value.trim();
      if (!postId || !message) return;

      const targetPost = forumPosts.find(item => item.id === postId);
      if (!targetPost) return;

      const now = new Date().toISOString();
      targetPost.replies.push({
        id: generateId('reply'),
        authorName: currentUser.name,
        authorId: currentUser.id,
        authorType: currentUser.type,
        content: message,
        createdAt: now
      });

      if (targetPost.status !== 'answered' && currentUser.type === 'doctor') {
        targetPost.status = 'answered';
      }
      targetPost.updatedAt = now;
      savePosts(forumPosts);
      renderAll();
    });

    function renderPosts() {
      if (!listEl) return;

      const filtered = forumPosts.filter(post => filterPost(post, activeFilter, currentUser));
      if (!filtered.length) {
        listEl.innerHTML = `
          <div class="forum-empty-state">
            <i class="ri-chat-1-line"></i>
            <p>Chưa có chủ đề phù hợp bộ lọc hiện tại.</p>
            <p>Hãy tạo câu hỏi mới hoặc thay đổi bộ lọc.</p>
          </div>
        `;
        return;
      }

      listEl.innerHTML = filtered
        .sort((a, b) => new Date(b.updatedAt || b.createdAt) - new Date(a.updatedAt || a.createdAt))
        .map(post => renderPostCard(post, currentUser))
        .join('');
    }

    renderPosts();

    return {
      render: renderPosts
    };
  }

  function renderAll() {
    forumInstances.forEach(instance => instance.render());
  }

  function filterPost(post, filter, user) {
    switch (filter) {
      case 'mine':
        return (
          post.authorId === user.id ||
          post.replies.some(reply => reply.authorId === user.id)
        );
      case 'pending':
        return post.status === 'pending';
      case 'answered':
        return post.status === 'answered';
      default:
        return true;
    }
  }

  function renderPostCard(post, user) {
    const statusClass =
      post.status === 'answered' ? 'appointment-status status-confirmed' : 'appointment-status status-pending';
    const statusLabel = post.status === 'answered' ? 'Đã trả lời' : 'Chờ phản hồi';
    const repliesHtml = post.replies
      .map(reply => renderReply(reply))
      .join('');

    const canReply = user.type === 'doctor';

    return `
      <div class="forum-post" data-post-id="${post.id}">
        <div class="post-header">
          <div class="post-author">
            <div class="author-avatar">${getAvatarInitial(post.authorName)}</div>
            <div>
              <strong>${escapeHtml(post.authorName)}</strong>
              <p style="color:#95a5a6;font-size:12px;margin-top:2px;">
                ${post.authorType === 'doctor' ? 'Bác sĩ' : 'Người dùng'} · ${formatFullDate(post.createdAt)}
              </p>
            </div>
          </div>
          <span class="${statusClass}">${statusLabel}</span>
        </div>
        <h4 style="margin-bottom:10px;color:#2c3e50;">${escapeHtml(post.title)}</h4>
        <div class="post-content">${formatContent(post.content)}</div>
        ${repliesHtml}
        ${canReply ? renderReplyForm(post.id) : ''}
        <div class="post-stats">
          <span><i class="ri-chat-1-line"></i> ${post.replies.length} phản hồi</span>
          <span><i class="ri-time-line"></i> Cập nhật ${formatRelativeTime(post.updatedAt || post.createdAt)}</span>
        </div>
      </div>
    `;
  }

  function renderReply(reply) {
    return `
      <div class="forum-answer">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
          <div class="author-avatar" style="background:${reply.authorType === 'doctor' ? '#27ae60' : '#4a69bd'};">
            ${reply.authorType === 'doctor' ? '<i class="ri-user-heart-line"></i>' : getAvatarInitial(reply.authorName)}
          </div>
          <div>
            <strong style="color:#2c3e50;">${escapeHtml(reply.authorName)}</strong>
            <p style="color:#95a5a6;font-size:12px;margin-top:2px;">${formatFullDate(reply.createdAt)}</p>
          </div>
        </div>
        <p style="color:#4b5563;margin:0;">${formatContent(reply.content)}</p>
      </div>
    `;
  }

  function renderReplyForm(postId) {
    return `
      <form class="forum-reply-form" data-reply-form data-post-id="${postId}">
        <label style="font-size:13px;font-weight:600;color:#2c3e50;display:block;margin-bottom:8px;">
          Trả lời tới bệnh nhân
        </label>
        <textarea placeholder="Nhập câu trả lời của bạn..." required></textarea>
        <div class="reply-form-actions">
          <button type="submit" class="btn btn-primary">
            <i class="ri-send-plane-fill"></i> Gửi phản hồi
          </button>
        </div>
      </form>
    `;
  }

  function loadPosts() {
    try {
      const stored = localStorage.getItem(STORAGE_KEY);
      if (!stored) return [];
      const parsed = JSON.parse(stored);
      return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
      console.error('Không thể tải dữ liệu diễn đàn:', error);
      return [];
    }
  }

  function savePosts(data) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    } catch (error) {
      console.error('Không thể lưu dữ liệu diễn đàn:', error);
    }
  }

  function formatContent(text) {
    return escapeHtml(text).replace(/\n/g, '<br>');
  }

  function formatFullDate(dateStr) {
    const date = new Date(dateStr);
    if (Number.isNaN(date.getTime())) return '';
    return date.toLocaleString('vi-VN', {
      hour: '2-digit',
      minute: '2-digit',
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  }

  function formatRelativeTime(dateStr) {
    const date = new Date(dateStr);
    if (Number.isNaN(date.getTime())) return '';
    const diff = Date.now() - date.getTime();
    const minutes = Math.floor(diff / (1000 * 60));
    if (minutes < 1) return 'vừa xong';
    if (minutes < 60) return `${minutes} phút trước`;
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours} giờ trước`;
    const days = Math.floor(hours / 24);
    return `${days} ngày trước`;
  }

  function getAvatarInitial(name = '') {
    return escapeHtml(name.trim().charAt(0).toUpperCase() || '?');
  }

  function escapeHtml(text = '') {
    return text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function generateId(prefix) {
    if (typeof crypto !== 'undefined' && crypto.randomUUID) {
      return `${prefix}-${crypto.randomUUID()}`;
    }
    return `${prefix}-${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 8)}`;
  }
})();

