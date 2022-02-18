<?php

namespace App\Scheduling;

use App\Models\User;

class NoneRoleUsersSchedule
{
    /**
     * @var User
     */
    private $userModel;

    public function __construct(
        User $userModel
    ) {
        $this->userModel = $userModel;
    }
    
    /**
     * process job for sending no role user information to admin users
     * @return void
     */
    public function process()
    {
        $users = $this->userModel->get();
        if ($users && $this->userModel->getAllADminUsers()->count() > 0) {
            foreach ($users as $user) {
                if ($user && $user->getRoleNames()->count() == 0) {
                    foreach ($this->userModel->getAllADminUsers() as $adminUser) {
                        $this->userModel->sendAssignRoleEmail($adminUser, $user);
                    }
                }
            }
        }
    }
}