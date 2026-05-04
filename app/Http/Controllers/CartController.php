<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MelbisLogic;

class CartController extends Controller
{
    
    // Add goods to basket
    public function add(Request $request, MelbisLogic $melbis)
    {                       
        $store_id = (int) $request->input('id');        
        $version = session('melbis_version');

        // Create
        if ( !isset($version) ) 
        {
            $version = $melbis->call('MELBIS_INC_LOGIC_order_create');
        }

        // Add and Calc
        $version = $melbis->call('MELBIS_INC_LOGIC_order_goods_add', [$version, $store_id]);        
        $version = $melbis->call('MELBIS_INC_LOGIC_order_calc', [$version]);

        // Save
        session(['melbis_version' => $version]);    
        
        // Return
        return response()->json([
            'result' => 'OK'            
        ]);
    }

    // Get goods list for checkout window
    public function goods(Request $request)
    {
        $version = session('melbis_version');        
        if (!$version || empty($version['store'])) 
        {
            return view('store.partials.goods_empty')->render();
        }
        return view('store.partials.goods_list', [
            'items' => $version['store']
        ])->render();
    }

    // Remove goods
    public function remove(Request $request, MelbisLogic $melbis)
    {
        $store_id = (int) $request->input('id');
        $version = session('melbis_version');

        if ($version) 
        {
            $version = $melbis->call('MELBIS_INC_LOGIC_order_goods_remove', [$version, $store_id]);                     
            $version = $melbis->call('MELBIS_INC_LOGIC_order_calc', [$version]);
            
            session(['melbis_version' => $version]);
        }

        return $this->goods($request);
    }  

    // Finalize order and save to DB
    public function save(Request $request, MelbisLogic $melbis)
    {
        $version = session('melbis_version');

        // Check if cart is empty or expired
        if (!$version || empty($version['store'])) 
        {
            return response()->json([
                'result' => 'ERROR_EMPTY',
                'message' => 'No items found in your cart!'
            ]);
        }

        // Verify previous calculations from Melbis core
        if (isset($version['result']['value']) && $version['result']['value'] !== 'OK') 
        {
            return response()->json([
                'result' => $version['result']['value'],
                'message' => $version['result']['message']
            ]);
        }

        // Call Melbis core to create the order in DB
        $result = $melbis->call('MELBIS_INC_LOGIC_order_edit', [$version]);

        // Check if core returned an error during creation
        if ($result['value'] !== 'OK') 
        {
            return response()->json([
                'result' => $result['value'],
                'message' => $result['message']
            ]);
        }

        // Success! Remove cart from Laravel session
        session()->forget('melbis_version');

        return response()->json([
            'result' => 'OK'
        ]);
    }    
}