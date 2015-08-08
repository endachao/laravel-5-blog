<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

class CommentSendEmail extends Event {

	use SerializesModels;

	public $parentId;
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($parentId)
	{
		//
		$this->parentId = $parentId;
	}

}
