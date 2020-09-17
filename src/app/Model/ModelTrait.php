<?php

namespace Emamie\Atom\Model;

use Illuminate\Support\Str;

trait ModelTrait
{
    public function getConnectionName()
    {
        $classname = get_class($this);

        $classname = explode('\\', $classname);

        if (isset($classname[1])) dd(strtolower($classname[1]));
        return $pos;

        dd();
        die;
        //$table_prefix = config();
        return $this->connection;
    }

    public function getTable()
    {
        return $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));
    }
}