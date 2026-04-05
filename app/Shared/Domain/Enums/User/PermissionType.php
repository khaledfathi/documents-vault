<?php

declare(strict_types=1);

namespace App\Shared\Domain\Enums\User;

enum PermissionType: string
{
    case VIEW_USER = "view_user";
    case CREATE_USER = "create_user";
    case EDIT_USER = "edit_user";
    case DELETE_USER = "delete_user";
    // 
    case VIEW_GROUP = "view_group";
    case CREATE_GROUP = "create_group";
    case EDIT_GROUP = "edit_group";
    case DELETE_GROUP = "delete_group";
    //
    case VIEW_CATEGORY = "view_category";
    case CREATE_CATEGORY = "create_category";
    case EDIT_CATEGORY = "edit_category";
    case DELETE_CATEGORY = "delete_category";
    //
    case VIEW_DOCUMENT = "view_document";
    case CREATE_DOCUMENT = "create_document";
    case EDIT_DOCUMENT = "edit_document";
    case DELETE_DOCUMENT = "delete_document";
}
