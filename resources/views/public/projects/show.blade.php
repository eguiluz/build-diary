@php
    $theme = $project->theme ?? \App\Models\Project::THEME_DEFAULT;
    $validThemes = array_keys(\App\Models\Project::themes());
    if (!in_array($theme, $validThemes)) {
        $theme = \App\Models\Project::THEME_DEFAULT;
    }
@endphp

@include("public.projects.themes.{$theme}")
