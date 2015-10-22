<?php

namespace SMP3Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SMP3Bundle\DependencyInjection\Security\Factory\WsseFactory;

class SMP3Bundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);
    }

}
