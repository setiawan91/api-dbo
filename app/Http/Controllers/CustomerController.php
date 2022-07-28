<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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
        $data =  Customer::get();
 
        return response()->json([
            'success' => true,
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Show the pagination of customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagination()
    {
        $data = Customer::paginate();

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
        $data = $request->only('name', 'address', 'phone');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'address' => 'required',
            'phone' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new Customer
        $customer = $this->user->customer()->create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone
        ]);

        //Customer created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
    
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, customer not found.'
            ], 400);
        }
    
        return $customer;
    }

    /**
     * Search customer by name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    function searchbyname(Request $request)
    {
        $name = $request->name;
        $data = Customer::where('name', 'LIKE', '%'. $name. '%')->get();
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
                'message' => 'Sorry, customer not found.'
            ], 400);
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //Validate data
        $data = $request->only('name', 'address', 'phone');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'address' => 'required',
            'phone' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update customer
        $customer = $customer->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone
        ]);

        //Customer updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ], Response::HTTP_OK);
    }
}
