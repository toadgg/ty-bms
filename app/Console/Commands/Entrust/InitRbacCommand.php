<?php

namespace App\Console\Commands\Entrust;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

use App\User;
use App\Role;
use App\Permission;


class InitRbacCommand extends Command
{
    // Entrust is an RBAC library... RBAC = "Role Based Access Control"
    protected $signature = 'entrust:init-rbac';

    protected $description = 'Initialize Role Based Access Control(基本权限初始化)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $user = User::first();
        if (!$user) {
            $this->error("Users table is empty");
            return;
        }
        $founder     = Role::addRole('Founder', '创建者');
        $maintainer  = Role::addRole('Maintainer', '维护者');
        $contributor = Role::addRole('Contributor', '贡献者');

        $visit_admin   = Permission::addPermission('visit_admin', 'Visit Admin');
        $manage_users  = Permission::addPermission('manage_users', 'Manage Users');
        $manage_topics = Permission::addPermission('manage_topics', 'Manage Topics');
        $compose_announcement = Permission::addPermission('compose_announcement', 'Composing Announcement');

        $this->attachPermissions($founder, [
            $visit_admin,
            $manage_users,
            $manage_topics,
            $compose_announcement,
        ]);

        $this->attachPermissions($maintainer, [
            $visit_admin,
            $manage_topics,
            $compose_announcement,
        ]);

        if (!$user->hasRole($founder->name)) {
            $user->attachRole($founder);
        }

        $this->info('--');
        $this->info("Initialize RABC success -- ID: 1 and Name “{$user->name}” has founder permission");
        $this->info('--');
    }

    /**
     * @param Role         $role
     * @param Permission[] $permissions
     */
    public function attachPermissions(Role $role, array $permissions)
    {
        $attach = [];

        $permissions = new Collection($permissions);

        $detach = [];
        foreach ($role->perms()->get() as $permission) {
            if ($permissions->where('name', $permission->name)->isEmpty()) {
                $detach[] = $permission;
            }
        }

        foreach ($permissions as $permission) {
            if (!$role->hasPermission($permission->name)) {
                $attach[] = $permission;
            }
        }

        $role->detachPermissions($detach);
        $role->attachPermissions($attach);
    }
}
