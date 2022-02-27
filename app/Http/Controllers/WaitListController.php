<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaitListRequest;
use App\Waitlist;
use Exception;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Http\Request;

class WaitListController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('waitlist.form');
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
                'FR',
                $validated['note']
            );
            return back()->with('status', 'saved');
        } catch(Exception $e) {
            return back()->with('status', 'exists');
        }
    }

    public function demo() {

        $member = Waitlist::instance()->get_members()->first();

        // (514) 718-6649

        $added = Waitlist::instance()->add('Ginette', '(514) 718-6649', '', '', '');


        return '<pre>' . print_r($added, true ) . '</pre>';

    }
}
