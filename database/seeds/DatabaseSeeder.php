<?php

use App\Task;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        /*
         * Faker instance.
         */
        $faker = Factory::create();

        /*
         * Truncate all tables.
         */
        Schema::disableForeignKeyConstraints();

        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tableNames as $name) {

            if ($name == 'migrations') {
                continue;
            }

            DB::table($name)->delete();
        }

        /*
         * Tasks seeder.
         */
        for ($i = 40; $i > 0; $i--) {
            Task::create([
                'content' => $faker->sentence,
                'done' => rand(0,3) == 0,
                'type' => ['work', 'shopping'][rand(0,1)]
            ]);
        }
    }
}
