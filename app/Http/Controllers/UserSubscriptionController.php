<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::all();

        return SubscriptionResource::collection($subscriptions);
    }

    public function chooseSubscription(Request $request, $subscriptionId)
    {
        $user = Auth::user();
        $subscription = Subscription::findOrFail($subscriptionId);

        if (!$subscription->isActive()) {

            return response()->json(['message' => 'Selected subscription is not active'], 400);
        }

        if ($user->balance < $subscription->price) {

            return response()->json(['message' => 'Insufficient funds to purchase this subscription'], 403);
        }

        $user->subscription_id = $subscription->id;
        $user->balance -= $subscription->price;
        $user->subscription_active_until = now()->addMonths(1);
        $user->save();

        return response()->json([$user, 'message' => 'Subscription chosen successfully'], 200);
    }
}

