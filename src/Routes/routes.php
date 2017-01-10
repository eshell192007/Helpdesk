<?php

// Helpdesk Group
Route::group([
    'as' => config('helpdesk.routes.helpdesk.prefix') . '.',
    'prefix' => config('helpdesk.routes.helpdesk.prefix'),
    'middleware' => 'web',
], function() {

    // Dashboard Group
    Route::group([
        'as' => config('helpdesk.routes.dashboard.prefix') . '.',
        'prefix' => config('helpdesk.routes.dashboard.prefix'),
    ], function() {

        Route::get('/', function() {
            return;
        })->middleware(\Aviator\Helpdesk\Middleware\DashboardRouter::class)->name('router');

        Route::get(
            config('helpdesk.routes.dashboard.user'),
            config('helpdesk.controllers.dashboard.user')
        )->name(config('helpdesk.routes.dashboard.user'));

        Route::get(
            config('helpdesk.routes.dashboard.agent'),
            config('helpdesk.controllers.dashboard.agent')
        )->name(config('helpdesk.routes.dashboard.agent'));

        Route::get(
            config('helpdesk.routes.dashboard.supervisor'),
            config('helpdesk.controllers.dashboard.supervisor')
        )->name(config('helpdesk.routes.dashboard.supervisor'));
    });

    // Tickets Group
    Route::group([
        'as' => config('helpdesk.routes.tickets.prefix') . '.',
        'prefix' => config('helpdesk.routes.tickets.prefix'),
    ], function() {

        // Index
        Route::get(
            config('helpdesk.routes.tickets.index.route'),
            config('helpdesk.controllers.tickets.index')
        )->name(config('helpdesk.routes.tickets.index.name'));

        // Show
        Route::get(
            config('helpdesk.routes.tickets.show.route'),
            config('helpdesk.controllers.tickets.show')
        )->name(config('helpdesk.routes.tickets.show.name'));

        // Show by uuid
        Route::get(
            config('helpdesk.routes.tickets.uuid.route'),
            config('helpdesk.controllers.tickets.uuid.show')
        )->name(config('helpdesk.routes.tickets.uuid.name'));

        // Assign
        Route::post(
            config('helpdesk.routes.tickets.assign.route'),
            config('helpdesk.controllers.tickets.assign')
        )->name(config('helpdesk.routes.tickets.assign.name'));

        // Reply
        Route::post(
            config('helpdesk.routes.tickets.reply.route'),
            config('helpdesk.controllers.tickets.reply')
        )->name(config('helpdesk.routes.tickets.reply.name'));

        // Close
        Route::get(
            config('helpdesk.routes.tickets.close.route'),
            config('helpdesk.controllers.tickets.close')
        )->name(config('helpdesk.routes.tickets.close.name'));

        // Note
        Route::get(
            config('helpdesk.routes.tickets.note.route'),
            config('helpdesk.controllers.tickets.note')
        )->name(config('helpdesk.routes.tickets.note.name'));
    });
});
