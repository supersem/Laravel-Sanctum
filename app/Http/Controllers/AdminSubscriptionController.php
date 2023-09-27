<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::all();

        return SubscriptionResource::collection($subscriptions);
    }

    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);

        return new SubscriptionResource($subscription);
    }

    public function store(SubscriptionStoreRequest $request)
    {
        $validated = $request->validated();
        $subscription = new Subscription($validated);
        $subscription->save();

        return new SubscriptionResource($subscription);
    }

    public function update(SubscriptionUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $subscription = Subscription::findOrFail($id);
        $subscription->update($validated);

        return new SubscriptionResource($subscription);
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return response()->json(['message' => 'Subscription has deleted successfully']);
    }
}
