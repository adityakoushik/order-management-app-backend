<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderStatusUpdated implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $orderId;
	public $status;
	public $customerId;

	/**
	 * Create a new event instance.
	 */
	public function __construct($orderId, $status, $customerId)
	{
		$this->orderId = $orderId;
		$this->status = $status;
		$this->customerId = $customerId;

		// Log::info('OrderStatusUpdated event constructed', [
		// 	'orderId' => $orderId,
		// 	'status' => $status,
		// 	'customerId' => $customerId,
		// ]);

	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		Log::info('OrderStatusUpdated broadcastOn', [
			'channel' => 'orders.' . $this->customerId,
		]);
		// Each customer listens on their own private channel
		return new PrivateChannel('orders.' . $this->customerId);
	}

	/**
	 * The event's broadcast name.
	 */
	public function broadcastAs()
	{
		// Log::info('OrderStatusUpdated broadcastAs');
		return 'OrderStatusUpdated';
	}
}
