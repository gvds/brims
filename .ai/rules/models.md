---
paths:
  - 'app/Models/**'
---

# Models

## Mass assignment
Models use `protected $guarded = ['id'];`, not `$fillable`.

## Global scopes
Tenant and context filtering is a class in `app/Models/Scopes`, attached to the model with `#[ScopedBy([...])]`, not `addGlobalScope()` or `booted()`.
