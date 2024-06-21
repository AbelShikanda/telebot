<?php

namespace App\Http\Controllers;

use App\Models\Replies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replies = Replies::orderByDesc('id')->get();
        return view('reply.index', with([
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
        return view('reply.create', with([
            // 'replies' => Replies::all()
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
            'keywords' => 'required',
            'reply' => 'required',
        ]);
        // dd($replies);

        try {
            DB::beginTransaction();
            // Logic For Save User Data

            $replies = Replies::create([
                'keyword' => $request->keywords,
                'response' => $request->reply,
            ]);


            if (!$replies) {
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('replies.index')->with('success', 'Values Stored Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Replies  $replies
     * @return \Illuminate\Http\Response
     */
    public function show(Replies $replies)
    {
        // return view('reply.show', with([
        //     // 'replies' => Replies::all()
        // ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Replies  $replies
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $replies = Replies::findOrFail($id);
        return view('reply.edit', with([
            'replies' => $replies,
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Replies  $replies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $replies = Replies::findOrFail($id);
        $request->validate([
            'keywords' => '',
            'reply' => '',
        ]);
        // dd($replies);

        try {
            DB::beginTransaction();
            // Logic For Save User Data

            if ($replies) {
                if ($request->keywords) {
                    $words = $request->keywords;
                    $replies->keyword = $words;
                }
                if ($request->reply) {
                    $reply = $request->reply;
                    $replies->response = $reply;
                }
                $replies->save();
            }


            if (!$replies) {
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('replies.index')->with('success', 'Values Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Replies  $replies
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Attempt to delete the group reply
        $replies = Replies::findOrFail($id);
        $replies->delete();

        // Redirect back with a success message
        return redirect()->route('replies.index')->with('success', 'Reply deleted successfully.');
    }
}
