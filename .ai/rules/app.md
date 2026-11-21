---
paths:
  - 'app/**'
---

# App

## Transactions
Wrap multi-step writes in manual `DB::beginTransaction()`, `DB::commit()` and `DB::rollBack()` inside try/catch, not the `DB::transaction(fn)` closure form.

## Project context
The active project is `session('currentProject')`, read with the `session()` helper. Use it for project scoping and permission team ids, not `Filament::getTenant()`.

## No repository layer
Query Eloquent directly in Filament pages, tables and relation managers; no repositories or query objects. Domain state transitions live as methods on the model (for example `Specimen::logStored()`), and multi-row workflows go in an invokable class under `app/actions`.

## Auth facade
Get the current user with the `Auth` facade (`Auth::user()`, `Auth::id()`), not the `auth()` helper.
