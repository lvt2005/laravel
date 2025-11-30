<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SystemSettingsController extends Controller
{
    /**
     * Get all system settings
     */
    public function getSettings(Request $request): JsonResponse
    {
        try {
            $settings = SystemSetting::getAllSettings();
            
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy cấu hình: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        try {
            $settings = $request->all();
            $userId = $request->user()->id ?? null;
            
            $oldSettings = SystemSetting::getAllSettings();
            
            foreach ($settings as $key => $value) {
                if ($key === 'blocked_ips' && is_array($value)) {
                    SystemSetting::set($key, $value, 'json');
                } else {
                    SystemSetting::set($key, $value);
                }
                
                // Log the change
                if (isset($oldSettings[$key]) && $oldSettings[$key] !== $value) {
                    SystemLog::logSettingChange($userId, $key, $oldSettings[$key], $value);
                }
            }
            
            // Clear all settings cache
            SystemSetting::clearCache();
            
            SystemLog::logAdminAction('UPDATE_SETTINGS', $userId, ['keys' => array_keys($settings)]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật cấu hình thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật cấu hình: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system logs
     */
    public function getLogs(Request $request): JsonResponse
    {
        try {
            $type = $request->query('type', 'all');
            $limit = (int) $request->query('limit', 50);
            
            $logs = SystemLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit($limit);
            
            if ($type !== 'all') {
                $logs->where('action', 'like', strtoupper($type) . '%');
            }
            
            $logs = $logs->get()->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'user' => $log->user ? [
                        'id' => $log->user->id,
                        'name' => $log->user->fullname ?? $log->user->email
                    ] : null,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'metadata' => is_string($log->metadata) ? json_decode($log->metadata, true) : $log->metadata,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s')
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get blocked IPs list
     */
    public function getBlockedIps(): JsonResponse
    {
        try {
            $blockedIps = SystemSetting::getBlockedIps();
            
            // Get details for each blocked IP
            $details = [];
            foreach ($blockedIps as $ip) {
                $lastLog = SystemLog::where('ip_address', $ip)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                $details[] = [
                    'ip' => $ip,
                    'last_activity' => $lastLog ? $lastLog->created_at->format('Y-m-d H:i:s') : null,
                    'last_action' => $lastLog ? $lastLog->action : null
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $details
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách IP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Block an IP
     */
    public function blockIp(Request $request): JsonResponse
    {
        try {
            $ip = $request->input('ip');
            
            if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Địa chỉ IP không hợp lệ'
                ], 400);
            }
            
            SystemSetting::blockIp($ip);
            
            $userId = $request->user()->id ?? null;
            SystemLog::logAdminAction('BLOCK_IP', $userId, ['ip' => $ip]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã chặn IP: ' . $ip
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi chặn IP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unblock an IP
     */
    public function unblockIp(Request $request): JsonResponse
    {
        try {
            $ip = $request->input('ip');
            
            SystemSetting::unblockIp($ip);
            
            $userId = $request->user()->id ?? null;
            SystemLog::logAdminAction('UNBLOCK_IP', $userId, ['ip' => $ip]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã bỏ chặn IP: ' . $ip
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi bỏ chặn IP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics for admin
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            // Login stats
            $loginStats = SystemLog::getLoginStats(7);
            
            // Get negative reviews (rating <= 2)
            $negativeReviews = DB::table('review')
                ->where('rating', '<=', 2)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Get recent feedback/reports
            $feedbacks = DB::table('forum_report')
                ->where('status', 'PENDING')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // System activity stats
            $activityByHour = SystemLog::getActivityByHour(1);
            
            // Get recent logs
            $recentLogs = SystemLog::getRecentLogs(20);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'login_stats' => $loginStats,
                    'negative_reviews' => $negativeReviews,
                    'pending_reports' => $feedbacks,
                    'activity_by_hour' => $activityByHour,
                    'recent_logs' => $recentLogs->map(function ($log) {
                        return [
                            'id' => $log->id,
                            'action' => $log->action,
                            'user' => $log->user ? $log->user->fullname : 'System',
                            'ip' => $log->ip_address,
                            'time' => $log->created_at->diffForHumans()
                        ];
                    }),
                    'blocked_ips_count' => count(SystemSetting::getBlockedIps())
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thống kê: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear system cache
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            SystemSetting::clearCache();
            
            $userId = $request->user()->id ?? null;
            SystemLog::logAdminAction('CLEAR_CACHE', $userId);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa cache hệ thống'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get negative reviews
     */
    public function getNegativeReviews(Request $request): JsonResponse
    {
        try {
            $limit = (int) $request->query('limit', 20);
            
            $reviews = DB::table('review')
                ->join('user', 'review.user_id', '=', 'user.id')
                ->leftJoin('doctor', 'review.doctor_id', '=', 'doctor.id')
                ->leftJoin('user as doctor_user', 'doctor.user_id', '=', 'doctor_user.id')
                ->select(
                    'review.*',
                    'user.full_name as user_name',
                    'user.email as user_email',
                    'doctor_user.full_name as doctor_name'
                )
                ->where('review.rating', '<=', 2)
                ->orderBy('review.created_at', 'desc')
                ->limit($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get feedback/forum reports
     */
    public function getFeedbacks(Request $request): JsonResponse
    {
        try {
            $status = $request->query('status', 'PENDING');
            $limit = (int) $request->query('limit', 20);
            
            $reports = DB::table('forum_report')
                ->join('user', 'forum_report.user_id', '=', 'user.id')
                ->leftJoin('forum_post', 'forum_report.post_id', '=', 'forum_post.id')
                ->select(
                    'forum_report.*',
                    'user.full_name as reporter_name',
                    'forum_post.title as post_title'
                )
                ->when($status !== 'all', function ($query) use ($status) {
                    return $query->where('forum_report.status', $status);
                })
                ->orderBy('forum_report.created_at', 'desc')
                ->limit($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $reports
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update report status
     */
    public function updateReportStatus(Request $request, $id): JsonResponse
    {
        try {
            $status = $request->input('status');
            
            DB::table('forum_report')
                ->where('id', $id)
                ->update([
                    'status' => $status,
                    'updated_at' => now()
                ]);
            
            $userId = $request->user()->id ?? null;
            SystemLog::logAdminAction('UPDATE_REPORT', $userId, ['report_id' => $id, 'status' => $status]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật trạng thái báo cáo'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a review (admin only)
     */
    public function deleteReview(Request $request, $id): JsonResponse
    {
        try {
            $deleted = DB::table('review')->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa đánh giá'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a report (admin only)
     */
    public function deleteReport(Request $request, $id): JsonResponse
    {
        try {
            $deleted = DB::table('forum_report')->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa báo cáo'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy báo cáo'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a system log (admin only)
     */
    public function deleteLog(Request $request, $id): JsonResponse
    {
        try {
            $deleted = DB::table('system_logs')->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa log'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy log'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
