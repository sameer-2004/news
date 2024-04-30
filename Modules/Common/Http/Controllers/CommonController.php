<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\VisitorTracker;
use Modules\User\Entities\Activation;
use Modules\Post\Entities\Post;
use Session;

class CommonController extends Controller
{
    public function index()
    {
        try {
            $data['totalVisits'] = VisitorTracker::count();
            $data['totalUniqueVisits'] = count(VisitorTracker::where('date', 'like', date('Y') . '%')->groupBy(['ip','url'])->select('id')->get()->toArray());
            $data['totalUniqueVisitors'] = count(VisitorTracker::where('date', 'like', date('Y') . '%')->groupBy('ip')->selectRaw('COUNT(*) AS total')->pluck('total')->toArray());
            $data['totalVisitors'] = count(VisitorTracker::groupBy('ip')->select('id')->get()->toArray());
            $data['usageBrowsers'] = VisitorTracker::where('agent_browser','!=','')->groupBy('agent_browser')->get();
            $data['registeredUsers'] = Activation::count();
            $data['publishedPost'] = Post::where('visibility', 1)->where('status', 1)->count();
            $data['submittedPost'] = Post::where('submitted', 1)->count();
            $month = date('Y-m');
            $visitors = VisitorTracker::where('date', 'like', '%' . $month . '%')->get();
            for ($i = 1; $i <= date('t'); $i++) {
                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }
                // visits count
                $visits = $visitors->where('date', date('Y-m-' . $i));
                $data['dates'][] = $i;
                $data['visits'][] = $visits->count();
                //visitor count
                $data['visitors'][] = $visits->groupBy('ip')->count();
            }
            $data['dates'] = implode(',', $data['dates']);
            $data['visits'] = implode(',', $data['visits']);
            $data['visitors'] = implode(',', $data['visitors']);
            $data['posthits'] = Post::with('image')->orderBy('total_hit', 'DESC')->where('total_hit', '!=', 0)->paginate(10);
            $data['browserColor'] = ['#254f37', '#8f97db', '#db9cd0', '#dbc98f', '#9fdb8f', '#8fdbc3', '#8fcfdb', '#6F7841', '#a61616', '#051057'];
            return view('common::index', compact('data'));
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('common::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('common::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('common::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
