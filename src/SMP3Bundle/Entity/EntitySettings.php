<?php

namespace SMP3Bundle\Entity;

trait EntitySettings
{
    public function setNN($name, $value)
    {
        if (!empty($value)) {
            $this->{$name} = $value;
        }
    }

    public function isNN($excludes = [])
    {
        $vars = get_object_vars($this);
        $nn = true;
        foreach ($vars as $name => $var) {
            if (in_array($name, $excludes)) {
                continue;
            }
            $nn &= !empty($var);
            if ($nn == false) {
                return $nn;
            }
        }

        return $nn;
    }
}
