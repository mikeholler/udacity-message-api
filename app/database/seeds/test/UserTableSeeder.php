<?php

use UserModel as U;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(U::TABLE)->insert([
            [
                U::USER_ID => 1,
                U::USERNAME => 'sender',
                U::CREATED => TimeHelper::formattedUtcDatetime(time() - 200)
            ],
            [
                U::USER_ID => 2,
                U::USERNAME => 'recipient',
                U::CREATED => TimeHelper::formattedUtcDatetime(time() - 200)
            ],
            [
                U::USER_ID => 3,
                U::USERNAME => 'other',
                U::CREATED => TimeHelper::formattedUtcDatetime(time() - 200)
            ]
        ]);

    }

}