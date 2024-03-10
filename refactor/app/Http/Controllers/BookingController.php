<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;
use DTApi\Http\Requests\StoreBookingRequest;
use DTApi\Http\Requests\DistanceFeedBookingRequest;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller {

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository) {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request) {
        if($user_id = $request->get('user_id')) {
            $response = $this->repository->getUsersJobs($user_id);
        } elseif(
            $request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID')
            || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID')) {
            $response = $this->repository->getAllJobs($request);
        }
        return response($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show(Job $job) {
        return response($job->with('translatorJobRel.user'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(StoreBookingRequest $request) {
        return response($this->repository->store($request->validated()));

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update(Job $job, Request $request) {
        $data = $request->all();
        $cuser = $request->__authenticatedUser;
        $response = $this->repository->updateJob($job, array_except($data, ['_token', 'submit']), $cuser);
        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request) {
        $data = $request->all();
        $response = $this->repository->storeJobEmail($data);
        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request) {
        if($user_id = $request->get('user_id')) {
            $response = $this->repository->getUsersJobsHistory($user_id, $request);
            return response($response);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request) {
        $data = $request->all();
        $user = $request->__authenticatedUser;
        $response = $this->repository->acceptJob($data, $user);
        return response($response);
    }

    public function acceptJobWithId(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJobWithId($data, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request) {
        $data = $request->all();
        $user = $request->__authenticatedUser;
        $response = $this->repository->cancelJobAjax($data, $user);
        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request) {
        $data = $request->all();
        $response = $this->repository->endJob($data);
        return response($response);
    }

    public function customerNotCall(Request $request) {
        $data = $request->all();
        $response = $this->repository->customerNotCall($data);
        return response($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request) {
        $data = $request->all();
        $user = $request->__authenticatedUser;
        $response = $this->repository->getPotentialJobs($user);
        return response($response);
    }

    public function distanceFeed(DistanceFeedBookingRequest $request) {
        $data = $request->all();
        if ($request->time || $request->distance) {
            $affectedRows = Distance::where('job_id', '=', $request->jobid)
                ->update(array('distance' => $request->distance, 'time' => $request->time));
        }
        if ($request->admincomment || $request->session_time || $request->flagged || $request->manually_handled || $request->by_admin) {
            $affectedRows1 = Job::where('id', '=', $request->jobid)
                ->update([
                    'admin_comments' => $request->admincomment,
                    'flagged' => $request->flagged,
                    'session_time' => $request->session_time,
                    'manually_handled' => $request->manually_handled,
                    'by_admin' => $request->by_admin
                ]);
        }

        return response('Record updated!');
    }

    public function reopen(Request $request) {
        $data = $request->all();
        $response = $this->repository->reopen($data);
        return response($response);
    }

    public function resendNotifications(Request $request) {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $job_data, '*');
        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);

        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }

}
