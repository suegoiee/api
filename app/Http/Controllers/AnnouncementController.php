<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\AnnouncementRepository;

class AnnouncementController extends Controller
{	
    protected $announcementRepository;

    public function __construct(AnnouncementRepository $announcementRepository)
    {
       $this->announcementRepository = $announcementRepository;
    }

    public function index(Request $request)
    {
        $announcements = $this->announcementRepository->getsWith([],['status'=>1]);
        $announcements->makeHidden(['status','updated_at']);
        return $this->successResponse($announcements);
    }
    public function show(Request $request, $id)
    {
        $announcement = $this->announcementRepository->getWith($id,[],['status'=>1]);
        
        return $this->successResponse($announcement);
    }
}
