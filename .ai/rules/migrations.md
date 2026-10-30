---
paths:
  - 'database/migrations/**'
---

# Migrations

## Foreign keys
Use `foreignIdFor(Model::class)->constrained()`, passing an explicit column name as the second argument when it differs from the default.
