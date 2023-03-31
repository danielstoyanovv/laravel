<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserLoggerdIn
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        try {
            $user = User::find($event->user->id);
            if ($user && $user->getRoleNames()->count() == 0) {
                $adminUsers = $user->getAllADminUsers();
                if ($adminUsers) {
                    foreach ($adminUsers as $admin) {
                        $user->sendAssignRoleEmail($admin, $user);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
