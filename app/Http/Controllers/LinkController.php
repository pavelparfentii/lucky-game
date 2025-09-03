<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function show(Request $request)
    {
        $link = $request->get('link');

        if (!$link || !$link->is_active || $link->expires_at < now()) {
            return redirect()->route('home')
                ->with('error', 'Link is missing or expired');
        }

        return view('page.active_link_page', compact('link'));
    }

    public function regenerate(Request $request)
    {
        $link = $request->get('link');

        if (!$link) {
            return redirect()->route('home')
                ->with('error', 'Link is missing or expired');
        }

        $link->update(['is_active' => false]);

        try {

            $newLink = $link->user->generateLink();;

            return redirect()->route('link.show', ['token' => $newLink->token])
                ->with('success', 'Your link has been successfully regenerated');
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Failed to regenerate link. Please try again.');
        }
    }

    public function deactivate(Request $request)
    {
        $link = $request->get('link');

        if (!$link) {
            return redirect()->route('home')
                ->with('error', 'Link is missing or expired');
        }

        $link->update(['is_active' => false]);

        return redirect()->route('home')
            ->with('info', 'Your link has been successfully deactivated');
    }
}
