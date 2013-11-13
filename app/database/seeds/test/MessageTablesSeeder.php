<?php

use MessageModel as M;

class MessageTablesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(M::TABLE_MESSAGE_SEND)->insert([
            [
                M::ATTR_MESSAGE_ID => 1,
                M::ATTR_FROM_USER => 1,
                M::ATTR_SUBJECT => 'First Message Subject',
                M::ATTR_CREATED => TimeHelper::formattedUtcDatetime()
            ],
            [
                M::ATTR_MESSAGE_ID => 2,
                M::ATTR_FROM_USER => 1,
                M::ATTR_SUBJECT => 'Second Message Subject',
                M::ATTR_CREATED => TimeHelper::formattedUtcDatetime()
            ],
            [
                M::ATTR_MESSAGE_ID => 3,
                M::ATTR_FROM_USER => 1,
                M::ATTR_SUBJECT => 'Third Message Subject',
                M::ATTR_CREATED => TimeHelper::formattedUtcDatetime()
            ]
        ]);

        DB::table(M::TABLE_MESSAGE_BODY)->insert([
            [
                M::ATTR_MESSAGE_ID => 1,
                M::ATTR_BODY => 'First body.'
            ],
            [
                M::ATTR_MESSAGE_ID => 2,
                M::ATTR_BODY => 'Second body.'
            ],
            [
                M::ATTR_MESSAGE_ID => 3,
                M::ATTR_BODY => 'Third body.'
            ]
        ]);

        DB::table(M::TABLE_MESSAGE_RECEIVE)->insert([
            [
                M::ATTR_MESSAGE_ID => 1,
                M::ATTR_TO_USER => 2,
                M::ATTR_READ => false
            ],
            [
                M::ATTR_MESSAGE_ID => 2,
                M::ATTR_TO_USER => 2,
                M::ATTR_READ => true
            ],
            [
                M::ATTR_MESSAGE_ID => 2,
                M::ATTR_TO_USER => 3,
                M::ATTR_READ => false
            ],
            [
                M::ATTR_MESSAGE_ID => 3,
                M::ATTR_TO_USER => 2,
                M::ATTR_READ => false
            ]
        ]);
    }
}