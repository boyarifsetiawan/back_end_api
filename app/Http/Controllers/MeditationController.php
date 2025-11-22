<?php

namespace App\Http\Controllers;

use App\Services\GeminiServices;
use Illuminate\Http\Request;

class MeditationController extends Controller
{
    protected $gemini;
    public function __construct(GeminiServices $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Menerima saran berdasarkan mood, setara dengan getAdviceByMood()
     *
     * @param string $mood
     * @return string
     */
    public function getAdviceByMood(Request $request): string
    {
        $mood = $request->query('mood');
        // $prompt = "give the current mood of the user,provide an approgriate meditation advice or mental health exercise. The Possible
        // moods are: happy, sad, angry, stressed, anxious, or neutral. Please tell me how you are feeling today so I can offer some helpful guidance. Return the response in JSON format with the following structure:
        //     {
        //         advice : 'specific advice or exercise based on the user mood',
        //     }
        //         for example, if the user mood is 'happy', the response should be :
        //     {
        //         advice : 'engage in gratitude practice by listening three things you are thankful for today, this will help sustain your positive mindset and bring more joy into your life. For me, I am thankful for the opportunity to learn and grow every day,',
        //     }
        //     so the mood is : {$mood}
        //     return the json only without using keyword json, backticks, or any explanatory text.";
        $prompt = "Based on the user's current mood, provide an appropriate meditation advice or mental health exercise.
                    The possible moods are: happy, sad, angry, stressed, anxious, or neutral.
                    The current mood is: {$mood}.
                        \"advice\": \"specific advice or exercise based on the user mood\"

                    return the json only without using keyword json, backticks, or any explanatory text.";

        return $this->gemini->generateContent($prompt);
    }

    /**
     * Mendapatkan kutipan harian, setara dengan getDailyQuotes()
     *
     * @return string
     */
    public function getDailyQuotes(): string
    {
        $prompt = "please provide three inspirational quotes for meditation, one for each part of the days: morning , noon and evening, Return the response in JSON format with the following structure:
                \"morning_quote\" : \"Your morning quote here\",
                \"noon_quote\" : \"Your noon quote here\",
                \"evening_quote\" : \"Your evening quote here\"
            return the json only without using keyword json, backticks, or any explanatory text.";

        return $this->gemini->generateContent($prompt);
    }
}
