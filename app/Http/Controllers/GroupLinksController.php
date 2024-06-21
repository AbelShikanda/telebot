<?php

namespace App\Http\Controllers;

use App\Models\GroupLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replies = GroupLinks::orderByDesc('id')->get();
        return view('groupLinks.index', with([
            'replies' => $replies,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groupLinks.create', with([
            // 'replies' => GroupLinks::all()
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $replies = $request->validate([
            'platform' => 'required',
            'link' => 'required',
        ]);
        // dd($replies);

        try {
            DB::beginTransaction();
            // Logic For Save User Data

            $replies = GroupLinks::create([
                'platform' => $request->platform,
                'link' => $request->link,
            ]);


            if (!$replies) {
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('grouplinks.index')->with('success', 'Values Stored Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoupLinks  $goupLinks
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GoupLinks  $goupLinks
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $replies = GroupLinks::findOrFail($id);
        return view('groupLinks.edit', with([
            'replies' => $replies,
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GoupLinks  $goupLinks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $replies = GroupLinks::findOrFail($id);
        $request->validate([
            'platform' => '',
            'link' => '',
        ]);
        // dd($replies);

        try {
            DB::beginTransaction();
            // Logic For Save User Data

            if ($replies) {
                if ($request->platform) {
                    $words = $request->platform;
                    $replies->platform = $words;
                }
                if ($request->link) {
                    $link = $request->link;
                    $replies->link = $link;
                }
                $replies->save();
            }


            if (!$replies) {
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('grouplinks.index')->with('success', 'Values Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoupLinks  $goupLinks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Attempt to delete the group reply
        $replies = GroupLinks::findOrFail($id);
        $replies->delete();

        // Redirect back with a success message
        return redirect()->route('grouplinks.index')->with('success', 'Reply deleted successfully.');
    }
}
