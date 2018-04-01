<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{	
    public function __construct()
    {
	   
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications;
        return $this->successResponse($notifications);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        
    }

    public function show(Request $request, $id)
    {
        
    }
    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy(Request $request, $id)
    {
        
    }

}
