<?php

namespace App\Libraries;

class KHQRTimestampFix
{
    public static function fixQRString(string $qr): string
    {
        // Remove space នៅ Tag 99 timestamp field
        // Pattern: 9917 + length(4) + space + digits
        $fixed = preg_replace('/(\d{4})\s(\d{13})(\d{4})$/', '$1$2$3', $qr);

        if ($fixed === $qr) {
            // Try broader replace — remove ALL spaces except known fields
            $parts = explode('9917', $qr, 2);
            if (count($parts) === 2) {
                $before = $parts[0]; // keep spaces (merchant name, city)
                $after  = str_replace(' ', '', $parts[1]); // strip space after 9917
                $fixed  = $before . '9917' . $after;
            }
        }

        // Recalculate CRC
        $noCrc    = substr($fixed, 0, -4);
        $crcInput = $noCrc;
        $newCrc   = \KHQR\Helpers\Utils::crc16($crcInput);

        return $noCrc . $newCrc;
    }
}
