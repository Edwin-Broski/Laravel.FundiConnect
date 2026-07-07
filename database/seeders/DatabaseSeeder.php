<?php

namespace Database\Seeders;

use App\Models\Trade;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $trades = [
            ['name' => 'Plumber',       'icon' => 'ti-droplet'],
            ['name' => 'Electrician',   'icon' => 'ti-bolt'],
            ['name' => 'Carpenter',     'icon' => 'ti-tools'],
            ['name' => 'Mechanic',      'icon' => 'ti-car'],
            ['name' => 'Painter',       'icon' => 'ti-brush'],
            ['name' => 'Welder',        'icon' => 'ti-flame'],
            ['name' => 'Cleaner',       'icon' => 'ti-sparkles'],
        ];

        foreach ($trades as $trade) {
            Trade::firstOrCreate(['name' => $trade['name']], $trade);
        }
    }
}