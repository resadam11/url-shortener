<?php
  
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Models\RedirectLog;
use Jenssegers\Agent\Agent;

class RedirectLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('redirect_logs.index');
    }
}