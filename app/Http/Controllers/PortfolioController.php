<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\WorkExperience;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index(Request $request) {
        return Inertia::render("Welcome", [
            "projects" => Project::where("is_featured", true)->get(),
            "work_experiences" => WorkExperience::orderBy("start_date", "desc")->get(),
        ]);
    }
}
