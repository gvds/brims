<?php

it('serves the documentation to an authenticated user', function (): void {
    $this->actingAs($this->adminuser)
        ->get(route('documentation'))
        ->assertOk();
});
