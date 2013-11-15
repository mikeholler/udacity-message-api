<?php

use GroupModel as G;

class GroupTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(G::TABLE)->insert([
            [
                G::GROUP_ID => 1,
                G::GROUP_NAME => 'apple',
                G::CREATED => TimeHelper::formattedUtcDatetime(time() - 200)
            ],
            [
                G::GROUP_ID => 2,
                G::GROUP_NAME => 'zebra',
                G::CREATED => TimeHelper::formattedUtcDatetime(time() - 200)
            ],
            [
                G::GROUP_ID => 3,
                G::GROUP_NAME => 'all',
                G::CREATED => TimeHelper::formattedUtcDatetime(time() - 200)
            ]
        ]);
    }
}
