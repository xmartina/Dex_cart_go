<?php

namespace App\Repositories\Dispute;

use App\Models\Dispute;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EloquentDispute extends EloquentRepository implements BaseRepository, DisputeRepository
{
    protected $model;

    public function __construct(Dispute $dispute)
    {
        $this->model = $dispute;
    }

    public function open()
    {
        // $query = $this->model->with('dispute_type', 'order', 'customer.avatarImage', 'shop')
        //     ->withCount('replies')->orderBy('created_at', 'desc');

        // if (Auth::user()->isFromPlatform()) {
        //     return $query->appealed()->get();
        // }

        // return $query->mine()->open()->get();
    }

    public function closed()
    {
        // if (Auth::user()->isFromPlatform()) {
        //     return $this->model->closed()->with('dispute_type', 'customer', 'shop')
        //         ->withCount('replies')->orderBy('created_at', 'desc')->get();
        // }

        // return $this->model->mine()->closed()->with('customer', 'dispute_type')
        //     ->withCount('replies')->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $dispute = $this->model->create($request->all());

        if ($request->hasFile('attachments')) {
            $dispute->saveAttachments($request->file('attachments'));
        }

        return $dispute;
    }

    public function show($id)
    {
        return $this->model->with(['replies' => function ($query) {
            $query->with('attachments', 'user')->orderBy('id', 'desc');
        }])->find($id);
    }

    public function storeResponse(Request $request, $dispute)
    {
        if (!$dispute instanceof Dispute) {
            $dispute = $this->model->find($dispute);
        }

        $dispute->update($request->all());

        $response = $dispute->replies()->create($request->all());

        if ($request->hasFile('attachments')) {
            $response->saveAttachments($request->file('attachments'));
        }

        return $response;
    }

    public function recentlyUpdated()
    {
        return $this->model->whereRaw("disputes.updated_at > '" . Carbon::parse('-1 days')->toDateTimeString() . "'")->get();
    }
}
