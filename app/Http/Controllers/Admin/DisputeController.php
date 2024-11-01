<?php

namespace App\Http\Controllers\Admin;

use App\Models\System;
use App\Models\Dispute;
use App\Common\Authorizable;
use App\Http\Controllers\Controller;
// use App\Events\Dispute\DisputeCreated;
use Illuminate\Support\Facades\Auth;
use App\Events\Dispute\DisputeUpdated;
use App\Repositories\Dispute\DisputeRepository;
use App\Http\Requests\Validations\ResponseDisputeRequest;
use App\Notifications\SuperAdmin\DisputeAppealed as DisputeAppealedNotification;
use App\Notifications\SuperAdmin\AppealedDisputeReplied as AppealedDisputeRepliedNotification;

class DisputeController extends Controller
{
    use Authorizable;

    private $model_name;

    private $dispute;

    /**
     * construct
     */
    public function __construct(DisputeRepository $dispute)
    {
        parent::__construct();

        $this->model_name = trans('app.model.dispute');

        $this->dispute = $dispute;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Dispute::with('dispute_type', 'order', 'customer.avatarImage', 'shop')
            ->withCount('replies')->orderBy('created_at', 'desc');

        if (Auth::user()->isFromPlatform()) {
            $disputes = $query->appealed()->get();
            $closed = $query->closed()->get();
        } else {
            $disputes = $query->mine()->open()->get();
            $closed = $query->mine()->closed()->get();
        }

        return view('admin.dispute.index', compact('disputes', 'closed'));
    }

    /**
     * Display the specified resource.
     *
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dispute = Dispute::with('activities.causer')->find($id);

        return view('admin.dispute.show', compact('dispute'));
    }

    /**
     * Display the response form.
     *
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function response($id)
    {
        $dispute = $this->dispute->find($id);

        return view('admin.dispute._response', compact('dispute'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function storeResponse(ResponseDisputeRequest $request, $id)
    {
        $dispute = $this->dispute->find($id);

        $old_status = $dispute->status;

        $response = $this->dispute->storeResponse($request, $dispute);

        $current_status = $response->repliable->status;

        // Send notification to Admin
        if (config('system_settings.notify_when_dispute_appealed') && ($current_status == Dispute::STATUS_APPEALED)) {
            $system = System::orderBy('id', 'asc')->first();

            if ($current_status != $old_status) {
                $system->superAdmin()->notify(new DisputeAppealedNotification($response));
            } else {
                $system->superAdmin()->notify(new AppealedDisputeRepliedNotification($response));
            }
        }

        event(new DisputeUpdated($response));

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }
}
