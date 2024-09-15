<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
                'role-view',
                'aircraft-type-view',
                'department-view',
                'designation-view',
                'section-view',
                'job-function-view',
                'amp-view',
                'ata-view',
                'ata-category-view',
                'tbo-view',
                'sortie',
                'my-sortie',
                'flying',
                'my-flying',
                'fdtl',
                'my-fdtl',
                'statistic',
                'my-statistic',
                'voilations',
                'my-voilations',
                'sfa-generate',
                'my-sfa-generate',
                'sfa-list',
                'my-sfa-list',
                'contract',
                'licence',
                'my-licence',
                'training',
                'my-training',
                'medical',
                'my-medical',
                'qualification',
                'my-qualification',
                'ground-training',
                'my-ground-training',
                'curency-view',
                'external-flying',
                'pilot-flying-hours',
                'pilot-ground-training',
                'vip-recency',
                'flight-statistics',
                'pilot-flying-currency',
                'sfa-report',
                'fdtl-report',
                'violations-summary',
                'violations-report',
                'aai-reports',
                'fuel-view',
                'incidence-view',
                'leave-view',
                'load-trim',
                'employee-view',
                'aircraft-view'
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'guard_name' => 'web' // Adjust the guard name if necessary
            ]);
        }
    }
}