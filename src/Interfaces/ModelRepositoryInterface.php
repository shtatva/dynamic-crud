<?php

namespace Shtatva\DynamicCrud\Interfaces;

interface ModelRepositoryInterface
{
    public function create($data);
    public function fetchFromName($name);
}