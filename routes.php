<?php

// Define routes for the application
return [
    // Auth routes
    'auth/login' => 'AuthController@login',
    'auth/register' => 'AuthController@register',
    'auth/logout' => 'AuthController@logout',
    'auth/change-password' => 'AuthController@changePassword',
    'auth/forgot-password' => 'AuthController@forgotPassword',

    // Dashboard routes
    'dashboard' => 'DashboardController@index',
    'dashboard/stats' => 'DashboardController@stats',
    'dashboard/settings' => 'DashboardController@settings',

    // Demande routes
    'demande/create' => 'DemandeController@create',

    // Repair Request routes
    'demande' => 'DemandeController@index',
    'demande/create' => 'DemandeController@create',
    'demande/manage' => 'DemandeController@manage',
    'demande/index' => 'DemandeController@index',

    // Equipment routes
    'equipement' => 'EquipementController@index',
    'equipement/add' => 'EquipementController@add',
    'equipement/edit' => 'EquipementController@edit',
    'equipement/delete' => 'EquipementController@delete',

    // Tech routes
    'tech/assign' => 'TechController@assign',
    'tech/list' => 'TechController@list',
    'tech/details' => 'TechController@details',
    'tech/start-intervention' => 'TechController@startIntervention',
    'tech/list-pending' => 'TechController@listPendingRequests',
    'tech/manage-interventions' => 'TechController@manageInterventions',

    // User routes
    'user/add' => 'UserController@add',
    'user/edit' => 'UserController@edit',

    // Profile routes
    'profile/show' => 'ProfileController@show',
    'profile/edit' => 'ProfileController@edit',
];