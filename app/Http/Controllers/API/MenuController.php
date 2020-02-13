<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Menu;
use Illuminate\Http\Request;
use Exception;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $menus = Menu::all();

            foreach ($menus as $menu) {
                $menu['restaurants'] = $menu->restaurants;
            }

            return ['message' => 'Successful', 'data' => $menus];
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
            $menu = Menu::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image,
                'price' => $request->price
            ]);

            $restaurants = $request->restaurants;
            foreach ($restaurants as $restaurant) {
                $menu->restaurants()->attach($restaurant);
            }

            return ['message' => 'Created successful'];
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
    public function show(Menu $menu)
    {
        try {
            $menu['restaurants'] = $menu->restaurants;

            return ['message' => 'Successful', 'data' => $menu];
        } catch (Exception $e) {
            return response()->json(['message' => "Something went wrong!", 'data' => []], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        try {
            $menu->update([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image,
                'price' => $request->price
            ]);

            $menu->restaurants()->detach();

            $restaurants = $request->restaurants;
            foreach ($restaurants as $restaurant) {
                $menu->restaurants()->attach($restaurant);
            }

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
    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            $menu->restaurants()->detach();
            return ['message' => 'Deleted successful'];
        } catch (Exception $e) {
            return response()->json(['message' => "Something went wrong!"], 500);
        }
    }
}
