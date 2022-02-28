<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaitListRequest;
use App\Waitlist;
use Exception;
use Illuminate\Contracts\View\View;

class WaitListController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('waitlist.form', ['title' => "Liste d'attente - Waitlist"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(WaitListRequest $request)
    {
        $validated = $request->validated();
        try {
            Waitlist::instance()->add(
                $validated['name'],
                $validated['phone'],
                $validated['email'],
                $validated['lang'],
                $validated['note']
            );
            return back()->with('status', 'saved');
        } catch(Exception $e) {
            return back()->with('status', 'already on list');
        }
    }

    public function demo() {

        $member = Waitlist::instance()->get_members()->first();

        // (514) 718-6649

        $added = Waitlist::instance()->add('Ginette', '(514) 718-6649', '', '', '');


        return '<pre>' . print_r($added, true ) . '</pre>';

    }
}
