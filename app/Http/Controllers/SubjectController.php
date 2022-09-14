<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResException;
use App\Http\Requests\Subject\SubjectEditRequest;
use App\Http\Requests\Subject\SubjectRequest;
use App\Models\Language;
use App\Models\Subject;
use App\Models\SubjectTranslation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Mockery\Matcher\Subset;

class SubjectController extends Controller
{
    //* @desc:   Get a list of subjects
    //* @route:  GET /api/subject/
    //* @access: `USER`
    public function getSubjects(Request $request)
    {
        return sendSuccRes([
            "data" => Subject::sort($request)
                ->filter(request(["slug", "name", "search"]))
                ->simplePaginate(),
        ]);
    }

    //* @desc:   Get a subject by slug
    //* @route:  GET /api/subject/{slug}
    //* @access: `USER`
    public function getSubjectBySlug(Request $request, $lang, string $slug)
    {
        $subject = Subject::where("slug", $slug)->first();

        if (!$subject) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $slug])
            );
        }

        return sendSuccRes([
            "data" => $subject,
        ]);
    }

    //* @desc:   Create a subject
    //* @route:  POST /api/subject
    //* @access: `USER`
    public function createSubject(SubjectRequest $request)
    {
        // Validated request body
        $body = $request->validated();

        // Init Subject translations array
        $subjectTranslations = [];

        // English name to be slugified and stored in subject
        $nameToBeSlugified = "";

        // Retrieve all languages and loop throw them to get language_id
        $languages = Language::all();

        foreach ($languages as $lang) {
            array_push(
                $subjectTranslations,
                new SubjectTranslation([
                    "uuid" => Str::orderedUuid()->getHex(),
                    "language_id" => $lang["id"],
                    "name" => $body[$lang["code"]]["name"],
                    "description" => $body[$lang["code"]]["description"],
                ])
            );
            // Catch english name
            if ($lang["code"] === "en") {
                $nameToBeSlugified = $body[$lang["code"]]["name"];
            }
        }

        // Create subject
        $subject = Subject::create([
            "uuid" => Str::orderedUuid()->getHex(),
            "slug" => Str::slug($nameToBeSlugified),
        ]);

        // Save subject's translations
        $translations = $subject
            ->translations()
            ->saveMany($subjectTranslations);

        // Fallback and delete subject
        if (!$translations) {
            $subject->delete();
            throw new ErrorResException(transResMessage("serverError"));
        }

        return sendSuccRes(
            [
                "message" => transResMessage("created", ["path" => "subject"]),
                "data" => [
                    "subject" => $subject,
                    "translations" => $subjectTranslations,
                ],
            ],
            201
        );
    }

    //* @desc:   Edit a subject
    //* @route:  PUT /api/subject/{slug}
    //* @access: `ADMIN`
    public function editSubject(
        SubjectEditRequest $request,
        $lang,
        string $slug
    ) {
        $body = $request->validated();

        $subject = Subject::where("slug", $slug)->first();

        if (!$subject) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $slug]),
                404
            );
        }

        // Adjust body if `en name` exists in the request body
        if (
            array_key_exists("en", $body) &&
            array_key_exists("name", $body["en"])
        ) {
            $body["en"]["slug"] = Str::slug($body["en"]["name"]);
        }

        for ($i = 0; $i < count($subject->translations); $i++) {
            // Check if the element was a en record
            if ($subject->translations[$i]->language["code"] === "en") {
                $subject->translations[$i]["name"] =
                    $body["en"]["name"] ?? $subject->translations[$i]["name"];
                $subject->translations[$i]["description"] =
                    $body["en"]["description"] ??
                    $subject->translations[$i]["description"];
            }
            // Check if the element was a ar record
            elseif ($subject->translations[$i]->language["code"] === "ar") {
                $subject->translations[$i]["name"] =
                    $body["ar"]["name"] ?? $subject->translations[$i]["name"];
                $subject->translations[$i]["description"] =
                    $body["ar"]["description"] ??
                    $subject->translations[$i]["description"];
            }
        }

        // Edit slug if exist on the adjusted body
        $subject["slug"] = $body["en"]["slug"] ?? $subject["slug"];

        $subject->save();

        return sendSuccRes(["data" => $subject]);
    }

    //* @desc:   Delete a subject with its translations
    //* @route:  DELETE /api/subject/{slug}
    //* @access: `ADMIN`
    public function deleteSubject(
        SubjectEditRequest $request,
        $lang,
        string $slug
    ) {
        $subject = Subject::where("slug", $slug)->first();

        if (!$subject) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $slug])
            );
        }

        $subject->delete();

        return sendSuccRes([
            "message" => transResMessage("deleted", ["path" => "subject"]),
        ]);
    }
}
