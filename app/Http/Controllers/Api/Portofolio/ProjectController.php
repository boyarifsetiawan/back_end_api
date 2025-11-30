<?php

namespace App\Http\Controllers\Api\Portofolio;

use App\Models\Skill;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    protected Project $projects;
    protected Skill $skill;
    public function __construct(Project $projects, Skill $skill)
    {
        $this->projects = $projects;
        $this->skill = $skill;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProjects()
    {

        return response()->json([
            'message' => 'Get project Success',
            'results' => ProjectResource::collection(Project::with('skill')->get())
        ], 200);
    }

    public function getDetailProject(Request $request)
    {
        $projectId = $request->query('query');
        // dd($projectId);
        $project = $this->projects->find($projectId);
        return response()->json([
            'message' => 'Get project Detail Success',
            'results' => new ProjectResource($project)
        ], 200);
    }

    public function getProjectsSkills()
    {
        $project = $this->projects->with('skill')->get();
        $skills = $this->skill->all();
        return response()->json([
            'message' => 'Get Skill and Projects Success',
            'results' => [
                'projects' => ProjectResource::collection($project),
                'skills' => SkillResource::collection($skills)
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createProject(Request $request)
    {
        $request->validate([
            'skill_id' => ['required'],
            'name' => ['required', 'min:3'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string']
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
            Project::create([
                'skill_id' => $request->skill_id,
                'name' => $request->name,
                'image' => $imagePath,
                'project_url' => $request->project_url,
                'description' => $request->description
            ]);

            return response()->json([
                'message' => 'Createed project Successfully',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProjectById(Request $request)
    {
        $projectId = $request->query('query');
        if ($projectId == null) {
            return response()->json([
                'message' => 'Get Project Failed',
            ], 404);
        }
        $project = $this->projects->find($projectId);
        $skills = Skill::all();
        return response()->json([
            'message' => 'Get Skill Success',
            'results' => [
                'project' => $project,
                'skills' => $skills
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProject(Request $request)
    {

        $request->validate([
            'id'   => 'required|exists:projects,id',
            'name' => 'required',
        ]);

        $projectId = $request->id;
        $project = Project::findOrFail($projectId);
        $image = $project->image;

        if ($request->hasFile('image')) {
            Storage::delete($project->image);
            $image = $request->file('image')->store('projects', 'public');
        }

        $project->update([
            'name' => $request->name,
            'skill_id' => $request->skill_id,
            'project_url' => $request->project_url,
            'description' => $request->description,
            'image' => $image,
        ]);

        return response()->json([
            'message' => 'Updated Project Successfully',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProject(Request $request)
    {
        $projectId = $request->query('id');
        $project = $this->projects->find($projectId);
        Storage::delete($project->image);
        $project->delete();

        return response()->json([
            'message' => 'Deleted Project Successfully',
        ], 201);
    }
}
