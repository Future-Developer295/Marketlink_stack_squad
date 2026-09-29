<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsTableSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('role', 'admin')->value('id');

        $reports = [
            ['Sales Report',   7,  'reports/sales_report.pdf'],
            ['Farmer Report',  30, 'reports/farmer_report.pdf'],
            ['Product Report', 30, 'reports/product_report.pdf'],
            ['Order Report',   14, 'reports/order_report.pdf'],
        ];

        foreach ($reports as [$type, $days, $path]) {
            DB::table('report')->updateOrInsert(
                ['file_path' => $path],
                [
                    'admin_id'     => $adminId,
                    'report_type'  => $type,
                    'date_from'    => now()->subDays($days)->toDateString(),
                    'date_to'      => now()->toDateString(),
                    'generated_at' => now(),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
