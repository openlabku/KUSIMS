<?php

namespace App\Http\Controllers;

use App\Models\Notice;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreJobtypesRequest;
use App\Http\Requests\Settings\UpdateJobtypesRequest;

class NoticeController extends Controller
{
    /**
     * Display a listing of notice.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('notice_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('notice_delete')) {
                return abort(401);
            }
            $notice = Notice::onlyTrashed()->get();
        } else {
            $notice = Notice::all();
        }

        return view('notice.index', compact('notice'));
    }

    /**
     * Show the form for creating new notice.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (! Gate::allows('notice_create')) {
        //     return abort(401);
        // }
        $enum_user_type = User::$enum_user_type;

        return view('notice.create', compact('enum_user_type'));
    }

    /**
     * Store a newly created notice in storage.
     *
     * @param  \App\Http\Requests\StoreJobtypesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobtypesRequest $request)
    {
        if (! Gate::allows('notice_create')) {
            return abort(401);
        }
        $notice = Notice::create($request->all());



        return redirect()->route('notice.index');
    }


    /**
     * Show the form for editing notice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('notice_edit')) {
            return abort(401);
        }
        $notice = notice::findOrFail($id);

        return view('notice.edit', compact('notice'));
    }

    /**
     * Update notice in storage.
     *
     * @param  \App\Http\Requests\UpdateJobtypesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJobtypesRequest $request, $id)
    {
        if (! Gate::allows('notice_edit')) {
            return abort(401);
        }
        $notice = notice::findOrFail($id);
        $notice->update($request->all());



        return redirect()->route('setting.notice.index');
    }


    /**
     * Display notice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('notice_view')) {
            return abort(401);
        }
       

        $notice = Notice::findOrFail($id);

        return view('notice.show', compact('notice'));
    }


    /**
     * Remove notice from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('notice_delete')) {
            return abort(401);
        }
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return redirect()->route('setting.notice.index');
    }

    /**
     * Delete all selected notice at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('notice_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Notice::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore notice from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('notice_delete')) {
            return abort(401);
        }
        $notice = notice::onlyTrashed()->findOrFail($id);
        $notice->restore();

        return redirect()->route('setting.notice.index');
    }

    /**
     * Permanently delete notice from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('notice_delete')) {
            return abort(401);
        }
        $notice = notice::onlyTrashed()->findOrFail($id);
        $notice->forceDelete();

        return redirect()->route('setting.notice.index');
    }
}