<?php

use MemberModel as M;

class MemberTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(M::TABLE)->insert([
            [
                M::GROUP_ID => 1,
                M::USER_ID => 1
            ],
            [
                M::GROUP_ID => 2,
                M::USER_ID => 2,
            ],
            [
                M::GROUP_ID => 2,
                M::USER_ID => 3
            ]
        ]);
    }
}