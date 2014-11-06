<?php

namespace Zdrw\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZdrwUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
