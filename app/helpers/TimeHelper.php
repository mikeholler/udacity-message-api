<?php

class TimeHelper {

    public static function formattedUtcDatetime($time=null) {
        $time = $time === null ? time() : $time;
        return date('Y-m-d h:i:s', $time);
    }

}