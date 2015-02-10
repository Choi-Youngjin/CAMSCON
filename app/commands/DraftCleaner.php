<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DraftCleaner extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:DraftCleaner';

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
	 * When a command should run
	 *
	 * @param Scheduler $scheduler
	 * @return \Indatus\Dispatcher\Scheduling\SchedDisclaimer: The opinions expressed here are solely mine and do not necessarily reflect the views of other members of this community. Do not misunderstand them as an encouragement to violate the NEC. Also, don't forget that the NEC is not necessarily the AHJ.ulable
	 */
	public function schedule(Schedulable $scheduler)
	{
		return $scheduler->daily()->hours(4);
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{	
		$snapsAll=StreetSnap::with('primary', 'attachments', 'pins', 'pins.links', 'likes')->where('status', '=', 'draft');
		$num = count($snapsAll->get());
		$snaps = $snapsAll->limit(20)->get();
		$deleted = count($snaps);
		try {
			foreach($snaps as $snap){
				if($snap->primary) {
					$snap->primary->delete();
				}

				if($snap->attachments) {
					$snap->attachments->each(function($attachment) {
						$attachment->delete();
					});
				}

				if($snap->pins) {
					$snap->pins->each(function($pin) {
						if($pin->links) {
							$pin->links->each(function($link) {
								$link->delete();
							});
						}
						$pin->delete();
					});
				}

				if($snap->likes) {
					$snap->likes->each(function($like) {
						$like->delete();
					});
				}

				$snap->delete();
			}
			Log::info($deleted.' Drafts of '.$num.' Cleaned, Now '.($num-$deleted).' remained');
		}
		catch(Exception $e) {
			Log::info('DB Connect Failed');	
		}
		
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
