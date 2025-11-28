<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Http\Resources\SkillResource;
use App\Models\Project;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $skills = SkillResource::collection(Skill::all());
        $projects = ProjectResource::collection(Project::with('skill')->get());

        return Inertia::render('Welcome', compact('skills', 'projects'));
    }
}
