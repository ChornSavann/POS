<?php

namespace KHQR\Models;

class Timestamp extends TagLengthString
{
    public function __construct(string $tag)
    {
        $millisecondTimestamp = sprintf('%d', (int)(microtime(true) * 1000));
        $timestamp = new TimestampMillisecond('00', $millisecondTimestamp);
        $value = (string) $timestamp;
        parent::__construct($tag, $value);
    }
}

class TimestampMillisecond extends TagLengthString
{
    public function __construct(string $tag, string $value)
    {
        parent::__construct($tag, $value);
    }
}
