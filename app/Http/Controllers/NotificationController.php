<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Hapus 1 notifikasi PER AKUN
     */
    public function deleteOne($id)
    {
        $userId = auth()->id();

        // Tambahkan user ini ke deleted_by
        ActivityLog::where('id', $id)
            ->where(function ($query) use ($userId) {
                $query->whereNull('deleted_by')
                      ->orWhereJsonDoesntContain('deleted_by', $userId);
            })
            ->update([
                'deleted_by' => DB::raw(
                    "JSON_ARRAY_APPEND(
                        COALESCE(deleted_by, JSON_ARRAY()),
                        '$',
                        $userId
                    )"
                )
            ]);

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Hapus semua notifikasi PER AKUN
     */
    public function clearAll()
    {
        $userId = auth()->id();

        ActivityLog::where(function ($query) use ($userId) {
                $query->whereNull('deleted_by')
                      ->orWhereJsonDoesntContain('deleted_by', $userId);
            })
            ->update([
                'deleted_by' => DB::raw(
                    "JSON_ARRAY_APPEND(
                        COALESCE(deleted_by, JSON_ARRAY()),
                        '$',
                        $userId
                    )"
                )
            ]);

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }

    /**
     * Tandai notifikasi dibaca PER AKUN
     */
    public function markRead()
    {
        $userId = auth()->id();

        ActivityLog::where(function ($query) use ($userId) {
            $query->whereNull('read_by')
                  ->orWhereJsonDoesntContain('read_by', $userId);
        })
        ->update([
            'read_by' => DB::raw(
                "JSON_ARRAY_APPEND(
                    COALESCE(read_by, JSON_ARRAY()),
                    '$',
                    $userId
                )"
            ),
            'is_read' => 1
        ]);

        return response()->json(['status' => 'ok']);
    }
}
