<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * Determine if the given project can be deleted by the user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return bool
     */
    public function delete(User $user, Project $project)
    {
        // Example logic: Only allow the owner of the project to delete it
        return $user->id === $project->id;
    }
    ///f
}
