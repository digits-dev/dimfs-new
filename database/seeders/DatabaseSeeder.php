<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Action;
use App\Models\Customer;
use App\Models\DepStatus;
use App\Models\Device;
use App\Models\EnrollmentList;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([AdmSeeders::class]);
        $this->call([AdmModules::class]);
        $this->call([AdmMenus::class]);
        // $this->call([AdmPrivileges::class]);
        $this->call([AdmMenuPrivileges::class]);
        // $this->call([AdmUsersSeeders::class]);
    }
}