<?php

class TimeHelperTest extends TestCase {

    public function testFormatUtcDatetime() {
        $actual = TimeHelper::formattedUtcDatetime(0);

        $this->assertEquals('1970-01-01 12:00:00', $actual);
    }
}