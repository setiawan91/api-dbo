<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =  Order::get();
 
        return response()->json([
            'success' => true,
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Show the pagination of order.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagination()
    {
        $data = Order::paginate();

        return response()->json([
            'success' => true,
            'data' => $data
        ], Response::HTTP_OK);        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'quantity', 'price');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new Order
        $order = $this->user->order()->create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        //order created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
    
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, order not found.'
            ], 400);
        }
    
        return $order;
    }

    /**
     * Search order by name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    function searchbyname(Request $request)
    {
        $name = $request->name;
        $data = Order::where('name', 'LIKE', '%'. $name. '%')->get();
        if(count($data)){
            return response()->json([
                'success' => true,
                'data' => $data
                ], Response::HTTP_OK);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, order not found.'
            ], 400);
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //Validate data
        $data = $request->only('name', 'quantity', 'price');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'quantity' => 'required',
            'price' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update order
        $order = $order->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        //Order updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ], Response::HTTP_OK);
    }
}
