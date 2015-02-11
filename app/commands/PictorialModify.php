<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PictorialModify extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:PictorialModify';

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
	public function toFire($i)
	{
		$findID = SimplePictorial::find($i)->toArray();
		DB::table('simple_pictorials')->insert(array('user_id' => $findID['user_id'],'title' => $findID['title'],'excerpt' => $findID['excerpt'], 'text' => $findID['text'],'cached_total_likes' => $findID['cached_total_likes'],'cached_total_comments' => $findID['cached_total_comments'],
		'status' => $findID['status'], 'created_at' => $findID['created_at'],	'updated_at' => $findID['updated_at']));
		$getID = SimplePictorial::orderBy('id','DESC')->first()->id;
		DB::table('simple_pictorial_attachments')->where('pictorial_id',$i)->update(array('pictorial_id'=>$getID));

	}

	public function fire() {
		$this->toFire(1);
		$this->toFire(2);
		$this->toFire(3);
		$this->toFire(5);
		$this->toFire(10);
		$this->toFire(12);
		$this->toFire(14);
		$this->toFire(7);
		$this->toFire(4);
		$this->toFire(6);
		$this->toFire(11);
		$this->toFire(9);
		$this->toFire(13);
		DB::table('simple_pictorials')->where('id','<',15)->delete();
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
