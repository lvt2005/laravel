<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Services\MailService;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    // Helper to wrap all controller logic and catch exceptions, always returning JSON
    private function safeJson(callable $fn) {
        try {
            return $fn();
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        return $this->safeJson(function () use ($request) {
            $user = $request->user();
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
            $post = ForumPost::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'content' => $validated['content'],
                'status' => 'ACTIVE',
                'category' => 'GENERAL_HEALTH',
            ]);
            return response()->json(['success' => true, 'post' => $post], 201);
        });
    }

    public function index()
    {
        return $this->safeJson(function () {
            $posts = ForumPost::with('user:id,full_name,avatar_url,type')
                ->orderByDesc('created_at')
                ->get();
            
            $posts->transform(function ($post) {
                $post->is_doctor = ($post->user && $post->user->type === 'DOCTOR');
                return $post;
            });

            return response()->json(['posts' => $posts]);
        });
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $post = ForumPost::find($id);
        if (!$post || $post->user_id !== $user->id) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $post->update($validated);
        return response()->json(['success' => true, 'post' => $post]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $post = ForumPost::find($id);
        if (!$post || $post->user_id !== $user->id) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $post->delete();
        return response()->json(['success' => true]);
    }

    // Tăng view cho bài viết
    public function incrementViews(Request $request, $id)
    {
        $post = ForumPost::find($id);
        if (!$post) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $ip = $request->ip();
        $userId = $request->user() ? $request->user()->id : null;
        
        // Check if viewed in last 5 minutes
        $view = \App\Models\ForumView::where('post_id', $id)
            ->where(function($q) use ($ip, $userId) {
                $q->where('user_ip', $ip);
                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
            })
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if (!$view) {
            // Create new view record
            \App\Models\ForumView::create([
                'post_id' => $id,
                'user_ip' => $ip,
                'user_id' => $userId,
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $post->increment('view_count');
            
            // Check for 20 views milestone
            if ($post->view_count % 20 === 0) {
                \App\Models\Notification::create([
                    'user_id' => $post->user_id,
                    'title' => 'Bài viết được quan tâm',
                    'message' => "Câu hỏi của bạn đã đạt {$post->view_count} lượt xem.",
                    'type' => 3, // Forum
                    'related_id' => $post->id,
                    'is_read' => false,
                    'sent_via' => 1
                ]);
            }
        }

        return response()->json(['success' => true, 'views' => $post->view_count]);
    }

    // Lấy danh sách bình luận cho bài viết
    public function getComments($id)
    {
        $post = ForumPost::find($id);
        if (!$post) {
            return response()->json(['comments' => []]);
        }
        $comments = $post->comments()->with('author:id,full_name,avatar_url,type')->orderBy('created_at')->get();
        
        // Add is_doctor flag and like count
        $comments->transform(function ($c) {
            $c->is_doctor = ($c->author && $c->author->type === 'DOCTOR');
            // Count likes for this comment
            $c->like_count = \DB::table('forum_like')->where('comment_id', $c->id)->count();
            return $c;
        });
        
        $commentCount = $comments->count();
        $post->comment_count = $commentCount;
        $post->save();
        return response()->json(['comments' => $comments, 'comment_count' => $commentCount]);
    }

    // Thêm bình luận cho bài viết
    public function addComment(Request $request, $id)
    {
        $user = $request->user();
        $post = ForumPost::find($id);
        if (!$post) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_comment_id' => 'nullable|integer|exists:forum_comments,id',
        ]);
        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'content' => $validated['content'],
            'parent_comment_id' => $validated['parent_comment_id'] ?? null,
        ]);
        
        // Notify post owner (if not self)
        if ($post->user_id !== $user->id) {
            $commenterName = $user->full_name;
            if ($user->type === 'DOCTOR') {
                $commenterName = "Bác sĩ " . $commenterName;
            }
            
            \App\Models\Notification::create([
                'user_id' => $post->user_id,
                'title' => 'Bình luận mới',
                'message' => "Bạn nhận được 1 bình luận từ {$commenterName}.",
                'type' => 3,
                'related_id' => $post->id,
                'is_read' => false,
                'sent_via' => 1
            ]);
            
            // Send email notification
            $postOwner = \App\Models\User::find($post->user_id);
            if ($postOwner && $postOwner->email) {
                try {
                    $mailService = new MailService();
                    $mailService->sendForumActivityNotification(
                        $postOwner->email,
                        $postOwner->full_name ?? 'Bạn',
                        'comment',
                        $commenterName,
                        $post->title,
                        $validated['content']
                    );
                } catch (\Exception $e) {
                    // Log error but don't fail the request
                    \Log::error('Failed to send forum email: ' . $e->getMessage());
                }
            }
        }

        $comments = $post->comments()->with('author:id,full_name,avatar_url,type')->orderBy('created_at')->get();
        // Add is_doctor to comments
        $comments->transform(function ($c) {
            $c->is_doctor = ($c->author && $c->author->type === 'DOCTOR');
            return $c;
        });

        $commentCount = $comments->count();
        $post->comment_count = $commentCount;
        $post->save();
        return response()->json(['success' => true, 'comments' => $comments, 'comment_count' => $commentCount]);
    }

    public function updateComment(Request $request, $postId, $commentId)
    {
        $user = $request->user();
        $comment = ForumComment::where('post_id', $postId)->where('id', $commentId)->first();
        if (!$comment || $comment->user_id !== $user->id) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $comment->update(['content' => $validated['content']]);
        $comments = ForumComment::where('post_id', $postId)->with('author:id,full_name,avatar_url')->orderBy('created_at')->get();
        $commentCount = $comments->count();
        $post = ForumPost::find($postId);
        if ($post) {
            $post->comment_count = $commentCount;
            $post->save();
        }
        return response()->json(['success' => true, 'comments' => $comments, 'comment_count' => $commentCount]);
    }

    public function destroyComment(Request $request, $postId, $commentId)
    {
        $user = $request->user();
        $comment = ForumComment::where('post_id', $postId)->where('id', $commentId)->first();
        if (!$comment || $comment->user_id !== $user->id) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $comment->delete();
        $comments = ForumComment::where('post_id', $postId)->with('author:id,full_name,avatar_url')->orderBy('created_at')->get();
        $commentCount = $comments->count();
        $post = ForumPost::find($postId);
        if ($post) {
            $post->comment_count = $commentCount;
            $post->save();
        }
        return response()->json(['success' => true, 'comments' => $comments, 'comment_count' => $commentCount]);
    }

    // Like a forum post
    public function likePost(Request $request, $id)
    {
        return $this->safeJson(function () use ($request, $id) {
            $user = $request->user();
            $post = ForumPost::find($id);
            if (!$post) {
                return response()->json(['error' => 'Not found'], 404);
            }
            $like = \App\Models\ForumLike::firstOrCreate([
                'user_id' => $user->id,
                'post_id' => $id,
                'type' => 'POST',
            ]);
            
            if ($like->wasRecentlyCreated && $post->user_id !== $user->id) {
                // Notify post owner
                $likerName = $user->full_name;
                if ($user->type === 'DOCTOR') {
                    $likerName = "Bác sĩ " . $likerName;
                }
                
                \App\Models\Notification::create([
                    'user_id' => $post->user_id,
                    'title' => 'Lượt thích mới',
                    'message' => "Bạn nhận được 1 like từ {$likerName} ở câu hỏi.",
                    'type' => 3,
                    'related_id' => $post->id,
                    'is_read' => false,
                    'sent_via' => 1
                ]);
            }

            // Update like_count
            $post->like_count = \App\Models\ForumLike::where('post_id', $id)->where('type', 'POST')->count();
            $post->save();
            return response()->json(['success' => true, 'like_count' => $post->like_count]);
        });
    }

    // Unlike a forum post
    public function unlikePost(Request $request, $id)
    {
        return $this->safeJson(function () use ($request, $id) {
            $user = $request->user();
            $post = ForumPost::find($id);
            if (!$post) {
                return response()->json(['error' => 'Not found'], 404);
            }
            $like = \App\Models\ForumLike::where([
                'user_id' => $user->id,
                'post_id' => $id,
                'type' => 'POST',
            ])->first();
            if ($like) {
                $like->delete();
            }
            // Update like_count
            $post->like_count = \App\Models\ForumLike::where('post_id', $id)->where('type', 'POST')->count();
            $post->save();
            return response()->json(['success' => true, 'like_count' => $post->like_count]);
        });
    }

    // Like a comment
    public function likeComment(Request $request, $postId, $commentId)
    {
        return $this->safeJson(function () use ($request, $postId, $commentId) {
            $user = $request->user();
            $comment = ForumComment::where('post_id', $postId)->where('id', $commentId)->first();
            if (!$comment) {
                return response()->json(['error' => 'Not found'], 404);
            }
            
            $like = \App\Models\ForumLike::firstOrCreate([
                'user_id' => $user->id,
                'comment_id' => $commentId,
                'type' => 'COMMENT',
            ]);
            
            $likeCount = \App\Models\ForumLike::where('comment_id', $commentId)->where('type', 'COMMENT')->count();
            
            return response()->json(['success' => true, 'like_count' => $likeCount, 'liked' => true]);
        });
    }

    // Unlike a comment
    public function unlikeComment(Request $request, $postId, $commentId)
    {
        return $this->safeJson(function () use ($request, $postId, $commentId) {
            $user = $request->user();
            $comment = ForumComment::where('post_id', $postId)->where('id', $commentId)->first();
            if (!$comment) {
                return response()->json(['error' => 'Not found'], 404);
            }
            
            \App\Models\ForumLike::where([
                'user_id' => $user->id,
                'comment_id' => $commentId,
                'type' => 'COMMENT',
            ])->delete();
            
            $likeCount = \App\Models\ForumLike::where('comment_id', $commentId)->where('type', 'COMMENT')->count();
            
            return response()->json(['success' => true, 'like_count' => $likeCount, 'liked' => false]);
        });
    }

    // Report a post or comment
    public function report(Request $request)
    {
        return $this->safeJson(function () use ($request) {
            $user = $request->user();
            
            $validated = $request->validate([
                'post_id' => 'nullable|integer|exists:forum_post,id',
                'comment_id' => 'nullable|integer|exists:forum_comments,id',
                'reason' => 'required|string|max:255',
                'detail' => 'nullable|string'
            ]);
            
            if (!$validated['post_id'] && !$validated['comment_id']) {
                return response()->json(['error' => 'Must specify post_id or comment_id'], 400);
            }
            
            $report = \App\Models\ForumReport::create([
                'user_id' => $user->id,
                'post_id' => $validated['post_id'] ?? null,
                'comment_id' => $validated['comment_id'] ?? null,
                'reason' => $validated['reason'],
                'detail' => $validated['detail'] ?? null,
                'status' => 'PENDING'
            ]);
            
            // Also create a notification for admins
            $admins = \App\Models\User::where('type', 'ADMIN')->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Báo cáo mới từ diễn đàn',
                    'message' => "Người dùng {$user->full_name} báo cáo: {$validated['reason']}",
                    'type' => 4, // Admin notification
                    'related_id' => $report->id,
                    'is_read' => false,
                    'sent_via' => 1
                ]);
            }
            
            return response()->json(['success' => true, 'message' => 'Báo cáo đã được gửi']);
        });
    }
}
