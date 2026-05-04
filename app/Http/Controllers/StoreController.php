<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Topic;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request, $topicId = null)
    {        
        $topics = Topic::where('no_visible', 0)
                       ->orderBy('absindex') 
                       ->get();

        $query = Store::with('images')
                       ->where('no_visible', 0);                       

        if ($topicId) 
        {
            $query->whereHas('topics', function ($q) use ($topicId) 
            {
                $q->where('topic_id', $topicId);
            });
        }

        $goods = $query->select('id', 'name', 'price', 'code_shop', 'intro')
                       ->paginate(12);
        
        return view('store.index', compact('goods', 'topics', 'topicId'));
    }
}