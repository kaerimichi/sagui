<?php
declare(strict_types=1);

namespace Plugin\Admin;

use Infrastructure\Plugin\Plugin;

class Admin extends Plugin
{
    public function getName(): string
    {
        return 'admin';
    }
}