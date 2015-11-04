<?php

namespace SMP3Bundle\Entity;

trait EntitySettings {

    public function setNN($name, $value) {
        if (!empty($value)) {
            $this->{$name} = $value;
        }
    }

}
