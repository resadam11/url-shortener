<?php
  
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Models\ShortLink;
use App\Models\RedirectLog;
use Jenssegers\Agent\Agent;

class ShortLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shortenLink(Request $request, $slug)
    {
        $agent = new Agent();
        $browser = $agent->browser();
        $browser = $browser . ' ' . $agent->version($browser);
        $platform = $agent->platform();
        $platform = $platform . ' ' . $agent->version($platform);
        $browser_data = $platform . ', ' . $browser;

        $find = ShortLink::where('slug', $slug)->first();
   
        $data = [];
        $data['slug'] = $slug;
        $data['link'] = $find->link;
        $data['ip_address'] = $request->getClientIp(true);
        $data['browser'] = $browser_data;
        $data['robot'] = $agent->isRobot();

        RedirectLog::create($data);

        $url = substr($find->link, 0, 4 ) !== "http" ? '//' . $find->link : $find->link;
        
        //$status_code = $find->status_code == '' ? 302 : $find->status_code;
        //return redirect($url, $status_code);
        
        return redirect($url);
    }
}