<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                "label" => "done",
                "key" => "011",
            ],
            [
                "label" => "waiting",
                "key" => "022",
            ],
            [
                "label" => "in-progress",
                "key" => "033",
            ],
            [
                "label" => "cancelled",
                "key" => "000",
            ],
        ];

        Status::insert($statuses);
    }
}
