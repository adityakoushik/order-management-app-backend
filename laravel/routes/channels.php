<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
// Customer channel
Broadcast::channel('customer.{customerId}', function ($user, $customerId) {
	\Log::info('Broadcast auth attempt', [
		'auth_user_id' => optional($user)->id,
		'customerId' => $customerId,
	]);
	return (int) optional($user)->id === (int) $customerId;
});


// Admin channel
Broadcast::channel('admin', function ($user) {
	// Allow only admins to listen
	return $user->role === 'admin'; // adjust depending on your roles system
});
