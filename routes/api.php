<?php

Route::group(['middleware' => ['auth:api']], function () {
    // Events and Schedules
    Route::post('/event/create', 'API\EventApiController@createEvent');
    Route::get('/event-schedules', 'API\EventApiController@getCalenderData');
});
