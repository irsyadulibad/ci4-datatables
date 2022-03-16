<?php

namespace Irsyadulibad\DataTables\Contracts;

interface DataTableContract
{
    /**
     * Execute all queries and make response
     */
    public function make(bool $dump = false);
}
