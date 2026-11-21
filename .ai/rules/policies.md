---
paths:
  - 'app/Policies/**'
---

# Policies

## Policy permission checks
Policy methods delegate to `evaluate_permission($authUser, 'Action:Model')` using `Action:Model` permission names such as `Manage:Subject`. Model-level checks add a per-policy `evaluateModelPermission` helper.
