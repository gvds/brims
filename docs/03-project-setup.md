# Project Setup

This section guides you through configuring a new research project in BRIMS — from initial creation through to adding team members and defining studies. Complete these steps before enrolling participants or logging specimens.

---

## Overview

Projects are the top-level organisational unit in BRIMS. A project contains:

- **Sites** — The physical or institutional locations where work takes place
- **Arms** — The study cohorts or groups that participants are assigned to
- **Studies** — Individual research investigations within the project (each with their own specimen and assay data)
- **Members** — The team members who have access to the project, each with a specific role

The recommended setup order is:

```
Create Project → Add Sites → Create Arms → Add Members → Create Studies
```

---

## 1. Creating a Project

Navigate to the **Project Panel** and select **Projects → New Project**.

### Required Fields

| Field | Description |
|---|---|
| **Title** | Full name of the project. Must be unique across the system. |
| **Identifier** | A short alphanumeric code used to reference the project (e.g. `BRIMS-STUDY-01`). Must be unique. |
| **Study Design** | The overall study design type (e.g. Randomised, Observational, Longitudinal). Select from the pre-configured list. |
| **Project Leader** | The user who will lead the project. Must be a member of your team. The leader is automatically added as a project administrator upon creation. |
| **Storage Designation** | An identifier for the storage location associated with this project (e.g. a freezer block code or site abbreviation). |
| **Subject ID Prefix** | 2–10 uppercase letters prepended to all participant identifiers (e.g. `BRI`). |
| **Subject ID Digits** | The number of digits in the numeric part of participant IDs (2–8). |

> **Subject ID format example:** A prefix of `BRI` with 6 digits produces IDs in the format `BRI000001`, `BRI000002`, etc. This prefix and digit count cannot easily be changed once participants have been enrolled.

### Optional Fields

| Field | Description |
|---|---|
| **Description** | Free-text description of the project. |
| **Submission Date** | The date the project was or will be formally submitted. |
| **REDCap Project ID** | The numeric ID of the linked REDCap project, if applicable. Leave blank if not using REDCap integration. |

> **Note:** The **Public Release Date** field is only available when editing an existing project, not during initial creation.

---

## 2. Adding Sites

Sites represent the physical or organisational locations within the project where research is conducted. Members and their substitutes are assigned to sites.

From the **Project view page**, open the **Sites** tab and select **New Site**.

| Field | Description |
|---|---|
| **Name** | A short name for the site (2–20 characters). Must be unique within the project. |
| **Description** | A brief description of the site's location or function. |

> **Tip:** If your project operates across multiple hospital or laboratory sites, create a separate site entry for each. This ensures that member substitutions and specimen tracking stay site-specific.

---

## 3. Creating Study Arms

Arms divide project participants into distinct groups or cohorts (e.g. Control, Treatment A, Treatment B). Each participant enrolled in the project is assigned to one arm.

From the **Project view page**, open the **Arms** tab and select **New Arm**.

| Field | Description |
|---|---|
| **Name** | A descriptive name for the arm (e.g. `Control`, `Intervention`). Must be unique within the project. |
| **Manual Enrolment** | If enabled, participants must be manually assigned to this arm. If disabled, assignment may be automated. Defaults to off. |

> **Note:** Arm numbers are assigned automatically and sequentially. Arms cannot be edited after creation — if a change is required, the arm must be deleted and recreated. Deleting an arm that already has enrolled participants is not permitted.

---

## 4. Adding Project Members

Members are team users who have been granted access to work within the project. Each member is assigned a project-specific role that controls their permissions.

From the **Project view page**, open the **Members** tab and select **Attach Member**.

| Field | Description |
|---|---|
| **User** | Select a user from your team. Each user can only be added once per project. |
| **Role** | Select the project role that defines this member's permissions. |
| **Site** | Optionally assign the member to a project site. Required if you intend to configure a substitute. |

### Member Roles

Roles are defined at the project level and determine what actions a member can perform. To view or manage the roles available for the project, navigate to **Roles** in the project sidebar.

> **Note:** The Project Leader cannot be detached from the project. To change the leader, edit the project and assign a different user to the leader field.

### Member Substitutes

A substitute is another project member who can act on behalf of a member when they are unavailable (e.g. on leave).

To assign a substitute, select the member in the Members list and use the **Select Substitute** action. Substitutes must:
- Already be a member of the same project
- Be assigned to the same site as the member they are covering

> If a member's site is changed and their current substitute is not assigned to the new site, the substitute assignment will be cleared automatically.

### REDCap Token

If the project uses REDCap integration, a personal REDCap API token can be stored against each member's record. Edit the member entry to add or update this token.

---

## 5. Creating Studies

Studies are research investigations that sit within a project. Each study has its own set of associated specimens and assays. A project may contain multiple studies.

Navigate to **Studies** in the project sidebar and select **New Study**.

### Required Fields

| Field | Description |
|---|---|
| **Title** | The full name of the study. Must be unique within the project. |
| **Identifier** | A short code for the study. Must be unique within the project. |

### Optional Fields

| Field | Description |
|---|---|
| **Description** | A free-text description of the study's scope or objectives. |
| **Submission Date** | The formal submission date for the study. |
| **Study File** | Upload any associated study protocol or design document. |

### Locking a Study

The **Locked** toggle on a study prevents specimens from being added to or removed from it. Use this when a study has reached a defined milestone or data cut-off point to preserve data integrity.

> **Warning:** Locking a study is a significant action. Ensure all expected specimens have been associated with the study before locking it.

---

## 6. Managing Roles

Project roles control what each member can see and do within the project. Roles are created and managed at the project level via the **Roles** section.

Each role is assigned a set of permissions that align with the tasks that role needs to perform (e.g. enrolling participants, logging specimens, managing storage, viewing reports).

To create a new role:
1. Navigate to **Roles** in the project sidebar
2. Select **New Role**
3. Enter a role name
4. Assign the appropriate permissions from the available list

Permissions take effect immediately when a role is saved. Members assigned to that role will have their access updated automatically.

---

## Summary Checklist

Use this checklist to confirm project setup is complete before starting data collection:

- [ ] Project created with a unique title, identifier, and subject ID format
- [ ] Study design selected
- [ ] Project leader assigned
- [ ] Storage designation entered
- [ ] At least one site created (if multi-site)
- [ ] All study arms created and named
- [ ] All team members added with appropriate roles and site assignments
- [ ] Roles reviewed and permissions confirmed
- [ ] At least one study created

---

*Next: [Participant Management](04-participant-management.md)*
