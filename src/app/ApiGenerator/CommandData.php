<?php 

namespace Emamie\Atom\ApiGenerator;

use InfyOm\Generator\Common\CommandData as InfyOmCommandData;
use Illuminate\Console\Command;

class CommandData extends InfyOmCommandData
{
	/**
     * @param Command $commandObj
     * @param string  $commandType
     *
     * @return CommandData
     */
    public function __construct(Command $commandObj, $commandType, $package)
    {
        parent::__construct($commandObj, $commandType);

        $this->config = new GeneratorConfig($package);
    }
}