// Frontend local auth script (served alongside login.html)
// Dynamic API base: if current origin port is 8000 assume served by Laravel, else use explicit Laravel dev server.
(function(){
const API_BASE = (location.port === '8000' ? '/api' : 'http://127.0.0.1:8000/api');
let accessToken = null;
let refreshToken = null;
let sessionId = null;
const storage = {
  set(key, value) {
    try { localStorage.setItem(key, value); } catch (_) {}
    try { sessionStorage.setItem(key, value); } catch (_) {}
  },
  get(key) {
    let value = null;
    try { value = localStorage.getItem(key); } catch (_) {}
    if (!value) {
      try { value = sessionStorage.getItem(key); } catch (_) {}
    }
    return value;
  },
  remove(key) {
    try { localStorage.removeItem(key); } catch (_) {}
    try { sessionStorage.removeItem(key); } catch (_) {}
  }
};
const safeJson = async function(resp){ try { return await resp.json(); } catch(e){ return {error:'invalid_json', status: resp.status}; } }
const apiFetch = async function(path, options = {}, retry = true) {
    accessToken = storage.get('access_token');
    refreshToken = storage.get('refresh_token');
    sessionId = storage.get('session_id');
    if (!accessToken) {
        console.warn('[auth.js] Missing access token before calling', path);
    }
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
const register = async function(full_name, email, phone, password){
    const r = await fetch(API_BASE + '/auth/register',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({full_name,email,phone,password})});
    const data = await safeJson(r);
    if(r.ok){ setTokens(data); }
    return {ok:r.ok,data};
}
const login = async function(email,password){
    const r = await fetch(API_BASE + '/auth/login',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email,password})});
    const data = await safeJson(r);
    // Only set tokens if login is complete (no 2FA required)
    if(r.ok && !data.requires_2fa){ setTokens(data); }
    return {ok:r.ok,data};
}
const googleLogin = async function(googleData){
    // Gửi đầy đủ thông tin từ Google
    const payload = {
        email: googleData.email,
        full_name: googleData.full_name || googleData.name,
        avatar_url: googleData.avatar_url || googleData.picture,
        dob: googleData.dob || null,
        gender: googleData.gender || null,
        address: googleData.address || null
    };
    const r = await fetch(API_BASE + '/auth/google',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
    const data = await safeJson(r);
    if(r.ok){ setTokens(data); }
    return {ok:r.ok,data};
}
const refresh = async function(){
    if(!refreshToken||!sessionId) {
        console.warn('[auth.js] Cannot refresh - missing refresh_token or session_id');
        return false;
    }
    try {
        const r = await fetch(API_BASE + '/auth/refresh',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({refresh_token:refreshToken,session_id:sessionId})});
        if(!r.ok) {
            console.warn('[auth.js] Refresh failed with status:', r.status);
            // Only clear tokens if refresh definitely failed (not network error)
            if (r.status === 401 || r.status === 403) {
                clearTokens();
            }
            return false;
        }
        const data = await safeJson(r);
        if (data.access_token) {
            setTokens(data);
            return true;
        }
        return false;
    } catch (error) {
        console.error('[auth.js] Refresh error:', error);
        // Don't clear tokens on network error - might be temporary
        return false;
    }
}
const getProfile = async function(){ const r = await apiFetch('/profile/me'); return safeJson(r); }
const logout = async function(){ if(!sessionId) return; await fetch(API_BASE + '/auth/logout',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({session_id:sessionId})}); clearTokens(); }
function setTokens(data){
  accessToken=data.access_token; refreshToken=data.refresh_token; sessionId=data.session_id;
  if(accessToken) storage.set('access_token', accessToken);
  if(refreshToken) storage.set('refresh_token', refreshToken);
  if(sessionId) storage.set('session_id', sessionId);
  }
function clearTokens(){
  accessToken=null; refreshToken=null; sessionId=null;
  storage.remove('access_token');
  storage.remove('refresh_token');
  storage.remove('session_id');
}
// On page load, restore tokens if present
(function(){
  accessToken = storage.get('access_token');
  refreshToken = storage.get('refresh_token');
  sessionId = storage.get('session_id');
  })();
window.AuthAPI={register,login,googleLogin,refresh,getProfile,logout,apiFetch};
})();
