<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $businesses = Auth::user()->businesses()->get();
    return view('businesses.index', compact('businesses'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('businesses.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreBusinessRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Business $business)
  {
    return view('businesses.show', compact('business'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Business $business)
  {
    
    return view('businesses.edit', compact('business'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateBusinessRequest $request, Business $business)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Business $business)
  {
    //
  }
}
