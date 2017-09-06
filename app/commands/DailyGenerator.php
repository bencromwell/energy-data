<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DailyGenerator extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'daily:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // @todo factory
        if ((int) $this->argument('type') === \Energy\Etl\IDataStore::TYPE_ELECTRICITY) {
            $calculator = new \Energy\Etl\DailyReadings\DailyReadingsElectricity();
        } else {
            $calculator = new \Energy\Etl\DailyReadings\DailyReadingsGas();
        }

        $calculator->go();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['type', InputArgument::REQUIRED, 'Type (1 - elec, 2 - gas)'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        ];
    }

}
