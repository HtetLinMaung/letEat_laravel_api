<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Restaurant;
use App\RestaurantsPhone;
use Exception;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $restaurants = Restaurant::all();

            foreach ($restaurants as $restaurant) {
                $restaurant['phones'] = $restaurant->phones;
            }

            if (!empty($restaurants)) {
                return [
                    'message' => 'Successful',
                    'data' => $restaurants
                ];
            } else {
                return response()->json([
                    'message' => 'Date is empty',
                    'data' => $restaurants
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json(['message' => "Something went wrong!", 'data' => []], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $restaurant = Restaurant::create([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'image' => $request->image
            ]);

            $phones = $request->input('phone_no');

            $temp = [];
            foreach ($phones as $phone_no) {
                $temp[] = [
                    'phone_no' => $phone_no,
                    'restaurant_id' => $restaurant->id
                ];
            }

            $restaurant->phones()->createMany($temp);

            return [
                'message' => 'Successful',
            ];
        } catch (Exception $e) {
            return response()->json(['message' => "Something went wrong!"], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        try {
            $restaurant['phones'] = $restaurant->phones;

            return ['message' => 'Successful', 'data' => $restaurant];
        } catch (Exception $e) {
            return response()->json(['message' => "Something went wrong!"], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        try {
            $restaurant->update([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'image' => $request->image
            ]);

            foreach ($restaurant->phones as $phone) {
                $phone->delete();
            }

            $phones = $request->input('phone_no');

            $temp = [];
            foreach ($phones as $phone_no) {
                $temp[] = [
                    'phone_no' => $phone_no,
                    'restaurant_id' => $restaurant->id
                ];
            }

            $restaurant->phones()->createMany($temp);

            return ['message' => 'Updated successful'];
        } catch (Exception $e) {
            return response()->json(['message' => "Something went wrong!"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        try {
            $restaurant->delete();
            return ['message' => 'Deleted successfully'];
        } catch (Exception $e) {
            return response()->json(['message' => "Something went wrong!"], 500);
        }
    }
}
