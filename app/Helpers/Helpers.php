<?php

if (! function_exists('toMinorAmount')) {
    function toMinorAmount(int|float|null $value): int|float|null
    {
        if (is_null($value)) {
            return null;
        }

        return $value * 100;
    }
}

if (! function_exists('toMajorAmount')) {
    function toMajorAmount(int|float|null $value): int|float|null
    {
        if (is_null($value)) {
            return null;
        }

        return $value / 100;
    }
}
