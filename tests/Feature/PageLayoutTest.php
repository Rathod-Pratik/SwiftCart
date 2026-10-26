<?php

test('app layout contains preloader and session storage scripts', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="global-preloader"', false);
    $response->assertSee('sessionStorage.getItem(\'has_visited\')', false);
    $response->assertSee('.preloader-hidden #global-preloader', false);
});

test('customer product page renders successfully', function () {
    $response = $this->get('/product');

    $response->assertStatus(200);
    $response->assertSee('Products');
});
