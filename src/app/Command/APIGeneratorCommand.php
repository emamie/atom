<?php

namespace Emamie\Atom\Command;

use InfyOm\Generator\Commands\API\APIGeneratorCommand as InfyOmAPIGeneratorCommand;
use Emamie\Atom\ApiGenerator\CommandData;
use Emamie\Atom\ApiGenerator\GeneratorConfig;
use Symfony\Component\Console\Input\InputOption;

class APIGeneratorCommand extends InfyOmAPIGeneratorCommand
{
	/**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'atom:api';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $options = $this->options();
        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_API, $options['package']);
        parent::handle();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        $custom_option = [
            ['package', null, InputOption::VALUE_REQUIRED, 'package name : vendor\package'],
        ];

        return array_merge(parent::getOptions(), $custom_option);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        $custom_argument = [];

        return array_merge(parent::getArguments(), $custom_argument);
    }
}