<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'available_publications' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $subscription = new Subscription();
        $subscription->name = $request->name;
        $subscription->price = $request->price;
        $subscription->available_publications = $request->available_publications;
        $subscription->active = $request->active;
        $subscription->save();

        return new SubscriptionResource($subscription);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'available_publications' => 'sometimes|integer',
            'active' => 'sometimes|boolean',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->fill($request->only([
            'name',
            'price',
            'available_publications',
            'active',
        ]));
        $subscription->save();

        return new SubscriptionResource($subscription);
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return response()->noContent();
    }
}
