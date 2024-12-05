<?php
namespace User\Block\Interfaces;

use User\Block\Services\Router;

interface RouteConfigurable {
    public function registerRoutes(Router $router): void;
}
?>