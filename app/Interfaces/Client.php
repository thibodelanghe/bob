<?php

namespace App\Interfaces;

interface Client
{
    public function get(string $url, array $query = []);
    public function post();
}
