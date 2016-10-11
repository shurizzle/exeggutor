<?php

namespace Shura\Exeggutor;

class Files
{
    public static function withTemp(callable $fn, $dir = null)
    {
        if (!isset($dir)) {
            $dir = sys_get_temp_dir();
        }

        $file = tempnam($dir, 'exeggutor-');

        try {
            return $fn($file);
        } finally {
            @unlink($file);
        }
    }
}
