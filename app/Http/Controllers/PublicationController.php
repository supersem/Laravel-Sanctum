<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Requests\PublicationUpdateRequest;
use App\Models\Publication;
use App\Http\Resources\PublicationResource;
use App\Http\Requests\PublicationStoreRequest;
use Illuminate\Support\Facades\Auth;

class PublicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $publications = $user->publications;

        return PublicationResource::collection($publications);
    }

    public function show(Publication $publication)
    {
        if (Auth::user()->id !== $publication->user_id) {
            return response()->json(['message' => 'You do not have permission to show this publication'], 403);
        }

        return new PublicationResource($publication);
    }

    public function store(PublicationStoreRequest $request)
    {
        $validated = $request->validated();
        $publication = Auth::user()->publications()->create($validated);

        return new PublicationResource($publication);
    }

    public function update(PublicationUpdateRequest $request, Publication $publication)
    {
        if (Auth::user()->id !== $publication->user_id) {
            return response()->json(['message' => 'You do not have permission to update this publication'], 403);
        }

        $validated = $request->validated();
        $publication->update($validated);

        return new PublicationResource($publication);
    }

    public function destroy(Publication $publication)
    {
        if (Auth::user()->id !== $publication->user_id) {
            return response()->json(['message' => 'You do not have permission to delete this publication'], 403);
        }

        $publication->delete();

        return response()->json(['message' => 'Publication deleted successfully']);
    }

    public function activate(Request $request, Publication $publication)
    {
        $user = Auth::user();

        if(!$user->subscription_id) {

            return response()->json(['message' => 'You do not have any subscription yet. Purchase a subscription!'], 403);
        }

        if ($publication->user_id !== $user->id) {

            return response()->json(['message' => 'You do not have permission to activate this publication'], 403);
        }

        if (now() > $user->subscription_active_until) {

            return response()->json(['message' => 'Your subscription has expired, purchase a new subscription!'], 403);
        }

        if ($user->publications_left === 0) {

            return response()->json(['message' => 'Your subscription does not allow you to activate more publications'], 403);
        }

        $user->publications_left -= 1;
        $user->update(['publications_left']);
        $publication->update(['active' => true]);

        return response()->json([$user, 'message' => 'Publication has activated successfully'], 200);
    }
}
