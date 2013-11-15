<?php

class TimeHelper {

    /**
     * Get SQL-style YYYY-MM-DD hh:mm:ss formatted time at current time.
     *
     * If current time is not desired, use the $time argument so specify
     * a past, present, or future date.
     *
     * @param int|null $time Unix seconds timestamp.
     *
     * @return string YYYY-MM-DD hh:mm:ss formatted date.
     */
    public static function formattedUtcDatetime($time=null)
    {
        $time = $time === null ? time() : $time;

        return date('Y-m-d h:i:s', $time);
    }

}