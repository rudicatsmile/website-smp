<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\InternalAnnouncement;
use App\Models\StaffMember;
use App\Models\User;
use Filament\Notifications\Notification;

class InternalAnnouncementObserver
{
    public function created(InternalAnnouncement $announcement): void
    {
        if ($this->isPublishable($announcement)) {
            $this->notifyTargets($announcement);
        }
    }

    public function updated(InternalAnnouncement $announcement): void
    {
        // Trigger only when transitioning from unpublished to published
        if (
            $announcement->wasChanged('published_at')
            && $announcement->getOriginal('published_at') === null
            && $this->isPublishable($announcement)
        ) {
            $this->notifyTargets($announcement);
        }
    }

    private function isPublishable(InternalAnnouncement $a): bool
    {
        return $a->is_active
            && $a->published_at
            && $a->published_at->lessThanOrEqualTo(now())
            && (! $a->expires_at || $a->expires_at->greaterThanOrEqualTo(now()));
    }

    private function notifyTargets(InternalAnnouncement $a): void
    {
        $userIds = collect();

        $roles = $a->target_roles ?: [];
        $staffIds = $a->target_staff_ids ?: [];

        // No target = broadcast to all panel users (teachers + admin staff)
        if (empty($roles) && empty($staffIds)) {
            $userIds = $userIds->merge(
                User::role(['teacher', 'admin', 'editor', 'super_admin'])->pluck('id')
            );
        } else {
            if (in_array('semua_guru', $roles, true)) {
                $userIds = $userIds->merge(User::role('teacher')->pluck('id'));
            }
            if (in_array('staf', $roles, true)) {
                $userIds = $userIds->merge(User::role(['admin', 'editor', 'super_admin'])->pluck('id'));
                $userIds = $userIds->merge(StaffMember::whereNotNull('user_id')->pluck('user_id'));
            }
            if (in_array('wali_kelas', $roles, true)) {
                // Heuristik: staff dengan posisi mengandung "wali kelas"
                $userIds = $userIds->merge(
                    StaffMember::whereNotNull('user_id')
                        ->where('position', 'like', '%wali kelas%')
                        ->pluck('user_id')
                );
            }
            if (! empty($staffIds)) {
                $userIds = $userIds->merge(
                    StaffMember::whereIn('id', $staffIds)
                        ->whereNotNull('user_id')
                        ->pluck('user_id')
                );
            }
        }

        $userIds = $userIds->unique()->filter()->values();
        if ($userIds->isEmpty()) {
            return;
        }

        $recipients = User::whereIn('id', $userIds)->get();
        if ($recipients->isEmpty()) {
            return;
        }

        $url = '/admin/internal-announcements/' . $a->id;

        $notification = Notification::make()
            ->title('Pengumuman Baru: ' . $a->title)
            ->body(\Illuminate\Support\Str::limit(strip_tags((string) $a->body), 120) . ' — ' . $url)
            ->icon('heroicon-o-megaphone')
            ->iconColor($a->priority_color === 'gray' ? 'primary' : $a->priority_color);

        foreach ($recipients as $recipient) {
            $notification->sendToDatabase($recipient);
        }
    }
}
