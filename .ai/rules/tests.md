---
paths:
  - 'tests/**'
---

# Tests

## Feature test setup
Feature tests call `actingAs($this->adminuser)` (a SuperAdmin created in `TestCase::setUp()`) and create other records with factories. Test authorisation with explicitly created lower-privileged users.

## Livewire testing
Test Filament and Livewire components with Pest's `livewire(Component::class, [...])` helper, not `Livewire::test()`.
