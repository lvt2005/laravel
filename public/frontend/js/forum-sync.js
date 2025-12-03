(() => {
  const STORAGE_KEY = 'sharedForumThreads';
  const LIKED_POSTS_KEY = 'forumLikedPosts';
  const LIKED_COMMENTS_KEY = 'forumLikedComments';
  let forumPosts = [];
  let forumInstances = [];
  let initialized = false;
  let useAPI = false; // Flag to determine if we should use API
  let likedPosts = new Set();
  let likedComments = new Set();

  // Load liked items from localStorage
  function loadLikedItems() {
    try {
      const storedPosts = localStorage.getItem(LIKED_POSTS_KEY);
      const storedComments = localStorage.getItem(LIKED_COMMENTS_KEY);
      if (storedPosts) likedPosts = new Set(JSON.parse(storedPosts));
      if (storedComments) likedComments = new Set(JSON.parse(storedComments));
    } catch (e) {
      console.error('Error loading liked items:', e);
    }
  }

  // Save liked items to localStorage
  function saveLikedItems() {
    try {
      localStorage.setItem(LIKED_POSTS_KEY, JSON.stringify([...likedPosts]));
      localStorage.setItem(LIKED_COMMENTS_KEY, JSON.stringify([...likedComments]));
    } catch (e) {
      console.error('Error saving liked items:', e);
    }
  }

  // Check if API is available
  function checkAPIAvailable() {
    const token = localStorage.getItem('access_token');
    return !!token;
  }

  // Export global function để user_profile.js có thể gọi khi đã load xong data
  window.initForumSync = function() {
    if (initialized) return;
    initialized = true;
    
    const forumApps = document.querySelectorAll('[data-forum-app]');
    if (!forumApps.length) return;

    useAPI = checkAPIAvailable();
    loadLikedItems();
    
    if (useAPI) {
      loadPostsFromAPI().then(() => {
        forumInstances = Array.from(forumApps).map(initForumInstance);
      });
    } else {
      forumPosts = loadPostsFromStorage();
      forumInstances = Array.from(forumApps).map(initForumInstance);
    }

    document.querySelectorAll('[data-forum-refresh]').forEach(btn => {
      btn.addEventListener('click', async () => {
        if (useAPI) {
          await loadPostsFromAPI();
        } else {
          forumPosts = loadPostsFromStorage();
        }
        forumInstances.forEach(instance => instance.render());
        btn.dataset.justSynced = 'true';
        setTimeout(() => btn.removeAttribute('data-just-synced'), 1500);
      });
    });
  };

  // Load posts from API
  async function loadPostsFromAPI() {
    try {
      const token = localStorage.getItem('access_token');
      if (!token) {
        forumPosts = loadPostsFromStorage();
        return;
      }

      const response = await fetch('/api/forum/posts', {
        headers: { 'Authorization': `Bearer ${token}` }
      });

      if (response.ok) {
        const data = await response.json();
        forumPosts = (data.posts || []).map(post => {
          // Check if user has liked this post
          if (post.is_liked) {
            likedPosts.add(post.id);
          }
          return {
            id: post.id,
            title: post.title,
            content: post.content,
            authorName: post.user?.full_name || 'Người dùng',
            authorId: post.user_id,
            authorType: post.is_doctor ? 'doctor' : 'patient',
            authorAvatar: post.user?.avatar_url || '',
            createdAt: post.created_at,
            updatedAt: post.updated_at,
            status: post.comment_count > 0 ? 'answered' : 'pending',
            viewCount: post.view_count || 0,
            likeCount: post.like_count || 0,
            commentCount: post.comment_count || 0,
            isLiked: post.is_liked || likedPosts.has(post.id),
            replies: [] // Will be loaded when expanded
          };
        });
        saveLikedItems();
      } else {
        forumPosts = loadPostsFromStorage();
      }
    } catch (error) {
      console.error('Error loading posts from API:', error);
      forumPosts = loadPostsFromStorage();
    }
  }

  // Load comments for a specific post
  async function loadCommentsForPost(postId) {
    try {
      const token = localStorage.getItem('access_token');
      if (!token) return [];

      const response = await fetch(`/api/forum/posts/${postId}/comments`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });

      if (response.ok) {
        const data = await response.json();
        return (data.comments || []).map(comment => {
          // Track liked comments
          if (comment.is_liked) {
            likedComments.add(comment.id);
            saveLikedItems();
          }
          return {
            id: comment.id,
            content: comment.content,
            authorName: comment.author?.full_name || 'Người dùng',
            authorId: comment.user_id,
            authorType: comment.is_doctor ? 'doctor' : 'patient',
            authorAvatar: comment.author?.avatar_url || '',
            createdAt: comment.created_at,
            likeCount: comment.like_count || 0,
            isLiked: comment.is_liked || likedComments.has(comment.id)
          };
        });
      }
      return [];
    } catch (error) {
      console.error('Error loading comments:', error);
      return [];
    }
  }

  // Like/unlike post
  async function toggleLikePost(postId) {
    const isLiked = likedPosts.has(postId);
    const post = forumPosts.find(p => p.id === postId);
    
    try {
      const token = localStorage.getItem('access_token');
      const method = isLiked ? 'DELETE' : 'POST';
      const response = await fetch(`/api/forum/posts/${postId}/likes`, {
        method,
        headers: { 'Authorization': `Bearer ${token}` }
      });

      if (response.ok) {
        if (isLiked) {
          likedPosts.delete(postId);
          if (post) post.likeCount = Math.max(0, (post.likeCount || 1) - 1);
        } else {
          likedPosts.add(postId);
          if (post) post.likeCount = (post.likeCount || 0) + 1;
        }
        if (post) post.isLiked = !isLiked;
        saveLikedItems();
        return true;
      }
      return false;
    } catch (error) {
      console.error('Error toggling like:', error);
      return false;
    }
  }

  // Like/unlike comment
  async function toggleLikeComment(postId, commentId) {
    const isLiked = likedComments.has(commentId);
    
    try {
      const token = localStorage.getItem('access_token');
      const method = isLiked ? 'DELETE' : 'POST';
      const response = await fetch(`/api/forum/posts/${postId}/comments/${commentId}/likes`, {
        method,
        headers: { 'Authorization': `Bearer ${token}` }
      });

      if (response.ok) {
        if (isLiked) {
          likedComments.delete(commentId);
        } else {
          likedComments.add(commentId);
        }
        saveLikedItems();
        return { success: true, isLiked: !isLiked };
      }
      return { success: false };
    } catch (error) {
      console.error('Error toggling comment like:', error);
      return { success: false };
    }
  }

  // Hàm lấy user data mới nhất từ dataset
  function getCurrentUser(app) {
    return {
      id: app.dataset.userId || 'guest',
      name: app.dataset.userName || 'Người dùng',
      type: app.dataset.userType || 'patient',
      avatar: app.dataset.userAvatar || ''
    };
  }

  function initForumInstance(app) {
    const listEl = app.querySelector('[data-forum-list]');
    const formEl = app.querySelector('[data-question-form]');
    const filterButtons = app.querySelectorAll('[data-forum-filters] .tab-btn');
    let activeFilter = app.dataset.defaultFilter || 'all';
    let expandedPostId = null;
    let loadedComments = new Map();

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
          expandedPostId = null;
          renderPosts();
        });
      });
    }

    if (formEl) {
      formEl.addEventListener('submit', async event => {
        event.preventDefault();
        const currentUser = getCurrentUser(app);
        const titleInput = formEl.querySelector('[data-question-title]');
        const contentInput = formEl.querySelector('[data-question-content]');
        const title = titleInput?.value.trim();
        const content = contentInput?.value.trim();
        if (!title || !content) return;
        
        if (useAPI) {
          try {
            const token = localStorage.getItem('access_token');
            const response = await fetch('/api/forum/posts', {
              method: 'POST',
              headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ title, content })
            });

            if (response.ok) {
              await loadPostsFromAPI();
              renderAll();
              formEl.reset();
              titleInput?.focus();
            } else {
              alert('Không thể đăng bài. Vui lòng thử lại.');
            }
          } catch (error) {
            console.error('Error posting:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
          }
        } else {
          // Fallback to localStorage
          const now = new Date().toISOString();
          const newPost = {
            id: generateId('post'),
            title,
            content,
            authorName: currentUser.name,
            authorId: currentUser.id,
            authorType: currentUser.type,
            authorAvatar: currentUser.avatar,
            createdAt: now,
            updatedAt: now,
            status: 'pending',
            replies: []
          };

          forumPosts.unshift(newPost);
          savePostsToStorage(forumPosts);
          renderAll();
          formEl.reset();
          titleInput?.focus();
        }
      });
    }

    // Handle reply form submission
    app.addEventListener('submit', async event => {
      const form = event.target;
      if (!(form instanceof HTMLFormElement)) return;
      if (!form.matches('[data-reply-form]')) return;
      event.preventDefault();
      
      const currentUser = getCurrentUser(app);
      const postId = form.dataset.postId;
      const textarea = form.querySelector('textarea');
      const message = textarea?.value.trim();
      if (!postId || !message) return;

      if (useAPI) {
        try {
          const token = localStorage.getItem('access_token');
          const response = await fetch(`/api/forum/posts/${postId}/comments`, {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ content: message })
          });

          if (response.ok) {
            const data = await response.json();
            // Update loaded comments
            loadedComments.set(parseInt(postId), (data.comments || []).map(comment => ({
              id: comment.id,
              content: comment.content,
              authorName: comment.author?.full_name || 'Người dùng',
              authorId: comment.user_id,
              authorType: comment.is_doctor ? 'doctor' : 'patient',
              authorAvatar: comment.author?.avatar_url || '',
              createdAt: comment.created_at
            })));
            
            // Update post in forumPosts
            const post = forumPosts.find(p => p.id == postId);
            if (post) {
              post.commentCount = data.comment_count || loadedComments.get(parseInt(postId))?.length || 0;
              post.status = post.commentCount > 0 ? 'answered' : 'pending';
            }
            
            expandedPostId = parseInt(postId);
            renderAll();
          } else {
            alert('Không thể gửi bình luận. Vui lòng thử lại.');
          }
        } catch (error) {
          console.error('Error posting comment:', error);
          alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
      } else {
        // Fallback to localStorage
        const targetPost = forumPosts.find(item => item.id === postId);
        if (!targetPost) return;

        const now = new Date().toISOString();
        targetPost.replies.push({
          id: generateId('reply'),
          authorName: currentUser.name,
          authorId: currentUser.id,
          authorType: currentUser.type,
          authorAvatar: currentUser.avatar,
          content: message,
          createdAt: now
        });

        if (targetPost.status !== 'answered' && currentUser.type === 'doctor') {
          targetPost.status = 'answered';
        }
        targetPost.updatedAt = now;
        savePostsToStorage(forumPosts);
        expandedPostId = postId;
        renderAll();
      }
    });

    // Handle click on post to toggle reply form
    app.addEventListener('click', async event => {
      // Handle like button click
      const likeBtn = event.target.closest('[data-like-post], [data-like-comment]');
      if (likeBtn) {
        event.preventDefault();
        event.stopPropagation();
        
        // Prevent double-click - check if button is already processing
        if (likeBtn.classList.contains('processing')) {
          return;
        }
        likeBtn.classList.add('processing');
        likeBtn.style.pointerEvents = 'none';
        
        try {
          if (likeBtn.dataset.likePost) {
            const postId = parseInt(likeBtn.dataset.likePost);
            const success = await toggleLikePost(postId);
            if (success) {
              renderPosts();
            }
          } else if (likeBtn.dataset.likeComment) {
            const commentId = parseInt(likeBtn.dataset.likeComment);
            const postId = parseInt(likeBtn.dataset.postId);
            const result = await toggleLikeComment(postId, commentId);
            if (result.success) {
              // Update comment in loadedComments
              const comments = loadedComments.get(postId);
              if (comments) {
                const comment = comments.find(c => c.id === commentId);
                if (comment) {
                  comment.isLiked = result.isLiked;
                  comment.likeCount = result.isLiked ? (comment.likeCount || 0) + 1 : Math.max(0, (comment.likeCount || 1) - 1);
                }
              }
              renderPosts();
            }
          }
        } finally {
          // Re-enable button after a short delay
          setTimeout(() => {
            likeBtn.classList.remove('processing');
            likeBtn.style.pointerEvents = '';
          }, 500);
        }
        return;
      }
      
      const postEl = event.target.closest('.forum-post');
      if (!postEl) return;
      
      // Don't toggle if clicking on form elements
      if (event.target.closest('form, button, textarea, a')) return;
      
      const postId = parseInt(postEl.dataset.postId);
      if (expandedPostId === postId) {
        expandedPostId = null;
      } else {
        expandedPostId = postId;
        
        // Load comments if using API and not already loaded
        if (useAPI && !loadedComments.has(postId)) {
          const comments = await loadCommentsForPost(postId);
          loadedComments.set(postId, comments);
          
          // Increment view count
          try {
            const token = localStorage.getItem('access_token');
            await fetch(`/api/forum/posts/${postId}/views`, {
              method: 'POST',
              headers: { 'Authorization': `Bearer ${token}` }
            });
          } catch (e) {
            console.error('Error incrementing views:', e);
          }
        }
      }
      renderPosts();
    });

    async function renderPosts() {
      if (!listEl) return;
      
      const currentUser = getCurrentUser(app);
      const filtered = forumPosts.filter(post => filterPost(post, activeFilter, currentUser));
      
      if (!filtered.length) {
        listEl.innerHTML = `
          <div class="forum-empty-state" style="text-align:center;padding:40px;color:#999;">
            <i class="fas fa-comments" style="font-size:48px;color:#ccc;margin-bottom:15px;display:block;"></i>
            <p>Chưa có chủ đề phù hợp bộ lọc hiện tại.</p>
            <p>Hãy tạo câu hỏi mới hoặc thay đổi bộ lọc.</p>
          </div>
        `;
        return;
      }

      listEl.innerHTML = filtered
        .sort((a, b) => new Date(b.updatedAt || b.createdAt) - new Date(a.updatedAt || a.createdAt))
        .map(post => {
          const isExpanded = expandedPostId === post.id;
          const comments = useAPI ? (loadedComments.get(post.id) || []) : (post.replies || []);
          return renderPostCard(post, currentUser, isExpanded, comments);
        })
        .join('');
    }

    renderPosts();

    return { render: renderPosts };
  }

  function renderAll() {
    forumInstances.forEach(instance => instance.render());
  }

  function filterPost(post, filter, user) {
    switch (filter) {
      case 'mine':
        return post.authorId == user.id;
      case 'pending':
        return post.status === 'pending';
      case 'answered':
        return post.status === 'answered' || post.commentCount > 0;
      default:
        return true;
    }
  }

  function renderPostCard(post, user, isExpanded, comments) {
    const hasComments = (comments && comments.length > 0) || post.commentCount > 0;
    const statusClass = hasComments ? 'appointment-status status-confirmed' : 'appointment-status status-pending';
    const statusLabel = hasComments ? 'Đã trả lời' : 'Chờ phản hồi';
    const repliesHtml = isExpanded ? comments.map(reply => renderReply(reply, post.id)).join('') : '';
    
    const authorAvatarHtml = post.authorAvatar 
      ? `<img src="${escapeHtml(post.authorAvatar)}" alt="${escapeHtml(post.authorName)}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">`
      : `<div style="width:40px;height:40px;border-radius:50%;background:${post.authorType === 'doctor' ? '#27ae60' : '#667eea'};color:white;display:flex;align-items:center;justify-content:center;font-weight:bold;">${getAvatarInitial(post.authorName)}</div>`;
    
    const authorBadge = post.authorType === 'doctor' 
      ? '<span style="background:#27ae60;color:white;font-size:10px;padding:2px 6px;border-radius:10px;margin-left:8px;">Bác sĩ</span>' 
      : '';

    const replyFormHtml = isExpanded ? renderReplyForm(post.id, user) : `
      <div class="reply-toggle" style="margin-top:15px;padding-top:15px;border-top:1px solid #eee;text-align:center;">
        <span style="color:#667eea;font-size:13px;cursor:pointer;">
          <i class="fas fa-reply"></i> Click để xem bình luận và phản hồi
        </span>
      </div>
    `;

    const commentCount = comments.length || post.commentCount || 0;
    const viewCount = post.viewCount || 0;
    const likeCount = post.likeCount || 0;
    
    // Check if post is liked (from localStorage or API response)
    const isPostLiked = post.isLiked || likedPosts.has(post.id);
    const likeButtonStyle = isPostLiked 
      ? 'color:#e74c3c;font-weight:bold;' 
      : 'color:#95a5a6;';
    const likeIconClass = isPostLiked ? 'fas fa-heart' : 'far fa-heart';

    return `
      <div class="forum-post" data-post-id="${post.id}" style="background:#fff;border-radius:12px;padding:20px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);cursor:pointer;transition:box-shadow 0.2s;">
        <div class="post-header" style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:15px;">
          <div class="post-author" style="display:flex;align-items:center;gap:12px;">
            ${authorAvatarHtml}
            <div>
              <strong style="color:#2c3e50;">${escapeHtml(post.authorName)}${authorBadge}</strong>
              <p style="color:#95a5a6;font-size:12px;margin-top:2px;">
                ${post.authorType === 'doctor' ? 'Bác sĩ' : 'Người dùng'} · ${formatFullDate(post.createdAt)}
              </p>
            </div>
          </div>
          <span class="${statusClass}" style="padding:4px 12px;border-radius:20px;font-size:12px;">${statusLabel}</span>
        </div>
        <h4 style="margin-bottom:10px;color:#2c3e50;font-size:16px;">${escapeHtml(post.title)}</h4>
        <div class="post-content" style="color:#4b5563;line-height:1.6;margin-bottom:15px;">${formatContent(post.content)}</div>
        ${repliesHtml}
        ${replyFormHtml}
        <div class="post-stats" style="display:flex;gap:20px;color:#95a5a6;font-size:13px;margin-top:15px;padding-top:15px;border-top:1px solid #eee;align-items:center;">
          <span><i class="fas fa-comment"></i> ${commentCount} phản hồi</span>
          <span><i class="fas fa-eye"></i> ${viewCount} lượt xem</span>
          <button data-like-post="${post.id}" style="background:none;border:none;cursor:pointer;padding:5px 10px;border-radius:20px;transition:all 0.2s;${likeButtonStyle}${isPostLiked ? 'background:rgba(231,76,60,0.1);' : ''}">
            <i class="${likeIconClass}"></i> ${likeCount} thích
          </button>
          <span><i class="fas fa-clock"></i> Cập nhật ${formatRelativeTime(post.updatedAt || post.createdAt)}</span>
        </div>
      </div>
    `;
  }

  function renderReply(reply, postId) {
    const replyAvatarHtml = reply.authorAvatar 
      ? `<img src="${escapeHtml(reply.authorAvatar)}" alt="${escapeHtml(reply.authorName)}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">`
      : `<div style="width:36px;height:36px;border-radius:50%;background:${reply.authorType === 'doctor' ? '#27ae60' : '#4a69bd'};color:white;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:bold;">
          ${reply.authorType === 'doctor' ? '<i class="fas fa-user-md"></i>' : getAvatarInitial(reply.authorName)}
        </div>`;
    
    const doctorBadge = reply.authorType === 'doctor' 
      ? '<span style="background:#27ae60;color:white;font-size:10px;padding:2px 6px;border-radius:10px;margin-left:8px;">Bác sĩ</span>' 
      : '';

    // Check if comment is liked
    const isCommentLiked = reply.isLiked || likedComments.has(reply.id);
    const likeButtonStyle = isCommentLiked 
      ? 'color:#e74c3c;font-weight:bold;' 
      : 'color:#95a5a6;';
    const likeIconClass = isCommentLiked ? 'fas fa-heart' : 'far fa-heart';
    const likeCount = reply.likeCount || 0;

    return `
      <div class="forum-answer" style="background:#f8f9fa;border-radius:10px;padding:15px;margin:10px 0;border-left:3px solid ${reply.authorType === 'doctor' ? '#27ae60' : '#667eea'};">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          ${replyAvatarHtml}
          <div style="flex:1;">
            <strong style="color:#2c3e50;">${escapeHtml(reply.authorName)}${doctorBadge}</strong>
            <p style="color:#95a5a6;font-size:12px;margin-top:2px;">${formatFullDate(reply.createdAt)}</p>
          </div>
          <button data-like-comment="${reply.id}" data-post-id="${postId}" style="background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:15px;font-size:12px;transition:all 0.2s;${likeButtonStyle}${isCommentLiked ? 'background:rgba(231,76,60,0.1);' : ''}">
            <i class="${likeIconClass}"></i> ${likeCount}
          </button>
        </div>
        <p style="color:#4b5563;margin:0;line-height:1.6;">${formatContent(reply.content)}</p>
      </div>
    `;
  }

  function renderReplyForm(postId, user) {
    const replyLabel = user.type === 'doctor' ? 'Trả lời tới bệnh nhân' : 'Thêm bình luận';
    
    return `
      <form class="forum-reply-form" data-reply-form data-post-id="${postId}" style="margin-top:15px;padding-top:15px;border-top:1px solid #eee;">
        <label style="font-size:13px;font-weight:600;color:#2c3e50;display:block;margin-bottom:8px;">
          ${replyLabel}
        </label>
        <textarea placeholder="Nhập nội dung bình luận..." required style="width:100%;min-height:80px;border:1px solid #ddd;border-radius:8px;padding:12px;resize:vertical;font-family:inherit;box-sizing:border-box;"></textarea>
        <div class="reply-form-actions" style="display:flex;justify-content:flex-end;margin-top:10px;">
          <button type="submit" class="btn btn-primary" style="background:#667eea;color:white;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;">
            <i class="fas fa-paper-plane"></i> Gửi
          </button>
        </div>
      </form>
    `;
  }

  function loadPostsFromStorage() {
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

  function savePostsToStorage(data) {
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

