<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Support\Facades\Gate;

class BusinessController extends Controller
{
  public function index()
  {
    return view('businesses.index');
  }

  public function create()
  {
    Gate::authorize('create', Business::class);

    return view('businesses.create');
  }

  public function show(Business $business)
  {
    Gate::authorize('manage', $business);

    return view('businesses.show', compact('business'));
  }

  public function edit(Business $business)
  {
    Gate::authorize('manage', $business);

    return view('businesses.edit', compact('business'));
  }
}
