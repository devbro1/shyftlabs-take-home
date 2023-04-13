<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class DevbroUpdateSuperAdminPerms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devbro:SADUpdatePerms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all permissions to SAD user';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $role = Role::where(['name' => 'super-admin'])->first()
            ->givePermissionTo(Permission::all())
        ;

        $this->comment('super-admin now has all permissions');

        return 0;
    }
}
