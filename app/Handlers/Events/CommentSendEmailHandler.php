<?php namespace App\Handlers\Events;

use App\Events\CommentSendEmail;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Model\Comment;
use Mail;
class CommentSendEmailHandler {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  CommentSendEmail  $event
	 * @return void
	 */
	public function handle(CommentSendEmail $event)
	{
		//
		if($event->parentId != 0){
			$parent = Comment::find($event->parentId);
			if(!empty($parent)){
				Mail::send('emails.comment', ['username' => $parent->username,'id'=>$parent->el_id], function($message) use($parent)
				{
					$message->to($parent->email,$parent->username)->subject('您的评论被回复了');
				});
			}
		}
	}

}
