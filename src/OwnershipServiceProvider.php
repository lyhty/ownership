<?php

namespace Lyhty\Ownership;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class OwnershipServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Example: ['owns:\App\Models\Image,\App\Models\User,1']
        // Uses provided model classes.
        // Example: ['owns:blog-post,user,1,writer_id']
        // The rule will attempt to use morphed models, also custom foreign key is used.
        // Example: ['owns:\App\Models\Image']
        // Authenticated user will be used as the owner
        Validator::extend('owns', function ($attribute, $value, $parameters) {
            [$targetClass, $ownerClass, $ownerId, $foreignKey] = array_pad($parameters, 4, null);

            if ($ownerClass && $ownerId) {
                $owner = (Relation::getMorphedModel($ownerClass) ?? $ownerClass)::find($ownerId);
            } else {
                $owner = Auth::user();
            }

            $targetClass = Relation::getMorphedModel($targetClass) ?? $targetClass;

            return (new OwnershipRule($targetClass, $owner, $foreignKey))->passes($attribute, $value);
        });
    }
}
