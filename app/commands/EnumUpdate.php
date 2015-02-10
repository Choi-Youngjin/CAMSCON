<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EnumUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'EnumUpdate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
 		DB::table('users')->update(array('enum' => 'email'));
 		DB::table('users')->join('facebook_accounts', 'users.id', '=', 'facebook_accounts.user_id')->update(array('users.enum' => 'facebook'));
 		DB::table('users')->where('enum','=','facebook')->where('password', '!=', 'NULL')->update(array('enum' => NULL));
 		DB::table('users')->where('enum','=','facebook')->update(array('email' => NULL));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
