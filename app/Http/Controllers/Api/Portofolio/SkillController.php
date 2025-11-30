<?php

namespace App\Http\Controllers\Api\Portofolio;;

use Inertia\Inertia;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class SkillController extends Controller
{
    protected Skill $skills;
    public function __construct(Skill $skills)
    {
        $this->skills = $skills;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSkills()
    {

        return response()->json([
            'message' => 'Get Skills Successfully',
            'results' => SkillResource::collection(Skill::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createSkill(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Upload image
        $imagePath = $request->file('image')->store('skills', 'public');

        // Insert ke database
        Skill::create([
            'name' => $request->name,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Skill created successfully',
        ], 201);
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
    public function getSkillById(Request $request)
    {
        $skillId = $request->query('query');
        $skill = $this->skills->find($skillId);
        return response()->json(
            [
                'message' => 'Get Skill Successfully',
                'results' => new SkillResource($skill)
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSkill(Request $request)
    {
        // VALIDASI
        $request->validate([
            'id'   => 'required|exists:skills,id',
            'name' => 'required',
        ]);

        $skill = Skill::findOrFail($request->id);

        $image = $skill->image;

        if ($request->hasFile('image')) {
            Storage::delete($skill->image);

            $image = $request->file('image')->store('skills', 'public');
        }

        // UPDATE DATA
        $skill->update([
            'name'  => $request->name,
            'image' => $image
        ]);

        return response()->json([
            'message' => 'Skill Updated Successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSkill(Request $request)
    {

        $skillId = $request->id;
        $skill = $this->skills->find($skillId);
        Storage::disk('public')->delete($skill->image);
        $skill->delete();

        return response()->json([
            'message' => "Deleted Skill Successfully",
        ], 200);
    }
}
