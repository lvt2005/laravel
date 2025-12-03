// ...existing code...
function setTokens(data){
  accessToken=data.access_token; refreshToken=data.refresh_token; sessionId=data.session_id;
  if(accessToken) localStorage.setItem('access_token', accessToken);
  if(refreshToken) localStorage.setItem('refresh_token', refreshToken);
  if(sessionId) localStorage.setItem('session_id', sessionId);
  }
function clearTokens(){
  accessToken=null; refreshToken=null; sessionId=null;
  localStorage.removeItem('access_token');
  localStorage.removeItem('refresh_token');
  localStorage.removeItem('session_id');
}
// On page load, restore tokens if present
(function(){
  accessToken = localStorage.getItem('access_token');
  refreshToken = localStorage.getItem('refresh_token');
  sessionId = localStorage.getItem('session_id');
  })();

const apiFetch = async function(path, options = {}, retry = true) {
    // Lấy token mới nhất từ localStorage mỗi lần gọi
    const accessToken = localStorage.getItem('access_token');
    const refreshToken = localStorage.getItem('refresh_token');
    const sessionId = localStorage.getItem('session_id');
    const opts = { ...options };
    const headers = { ...(options.headers || {}) };
    const isFormData = (options.body instanceof FormData);
    if (accessToken) headers['Authorization'] = 'Bearer ' + accessToken;
    if (isFormData) {
        delete headers['Content-Type'];
    } else {
        headers['Content-Type'] = headers['Content-Type'] || 'application/json';
    }
    opts.headers = headers;
    const resp = await fetch(API_BASE + path, { ...opts, credentials: 'omit' });
    if (resp.status === 401 && retry && refreshToken && sessionId) {
        const refreshed = await refresh();
        if (refreshed) return apiFetch(path, options, false);
    }
    return resp;
}
// ...existing code...
