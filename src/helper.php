<?php

use Irsyadulibad\DataTables\DataTables;

if(!function_exists('datatables')) {
    /**
     * Return created datatables instance
     * 
     * @param mixed $source
     */
    function datatables($source) {
        return DataTables::use($source);
    }
}
