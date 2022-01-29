<?php

namespace JoeDixon\Translation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use JoeDixon\Translation\Drivers\Translation;
use JoeDixon\Translation\Http\Requests\LanguageRequest;

class LanguageController extends Controller
{
    private $translation;
    /**
     * @var array
     */
    private $commands;

    public function __construct(Translation $translation, array $commands = [])
    {
        $this->translation = $translation;
        $this->commands = $commands;
    }

    public function index(Request $request)
    {
        $languages = $this->translation->allLanguages();

        return view('translation::languages.index', compact('languages'));
    }

    public function create()
    {
        return view('translation::languages.create');
    }

    public function store(LanguageRequest $request)
    {
        $this->translation->addLanguage($request->locale, $request->name);

        $this->translation->executeCommandsAfterSave($this->commands);

        return redirect()
            ->route('languages.index')
            ->with('success', __('translation::translation.language_added'));
    }
}
