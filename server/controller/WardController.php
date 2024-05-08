<?php

namespace app\controller;

use app\services\WardService;

class WardController
{

    private $teamService;

    function __construct()
    {
        $this->teamService = new WardService();
    }

    function addWard(array $payload)
    {
        return $this->teamService->setWard($payload);
    }

    function fetchWards()
    {
        return $this->teamService->getWards();
    }

    function getWardCounts()
    {
        return $this->teamService->getTotalWard();
    }
}
