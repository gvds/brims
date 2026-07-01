# BRIMS User Manual

## Chapter 13: Administration Guide

---

## Overview

This chapter is for system administrators and selected project administrators who are responsible for keeping BRIMS configured, governed, and ready for project use.

Administrative responsibilities in BRIMS fall into four main areas: user and access management, reference data configuration, preparing for new projects, and ongoing operational monitoring. In the current BRIMS interface, this work is split between system-level administration (the **Admin** panel) and team-level configuration (the team **Projects** and related setup areas).

---

## 13.1 User Management

### Creating and managing accounts

User accounts are managed from the **Users** area in the Admin panel.

When creating a new account:

1. Navigate to **Admin** → **Users** → **New User**.
2. Enter the user's name and email address.
3. Assign the appropriate system role.
4. Save the record. The user will receive an invitation to set their password.

### Reviewing and updating access

Review user accounts regularly to ensure that project membership and permissions remain appropriate for each person's current responsibilities.

When a user's role changes — for example, if they move to a different project or leave the organisation — update or deactivate their account promptly.

| Action | Where to do it |
|---|---|
| Create a new user account | **Admin** → **Users** → **New User** |
| Update a user's system role | **Admin** → **Users** → edit the user record |
| Add a user to a project | **Configure Project Details** → **Members** → add member |
| Change a user's project role | **Configure Project Details** → **Members** → edit the member record |
| Deactivate an account | **Admin** → **Users** → edit the user record → disable access |

> **Caution:** Deactivating an account does not remove the user's historical records or audit trail. It prevents further sign-in only. Do not delete user records unless directed by your organisation's data governance policy.

---

## 13.2 Role and Permission Management

System roles should be kept limited to users who genuinely need wider administrative access. Project roles should be scoped to support day-to-day work without granting unnecessary permissions.

- Use the project **Roles** area (within **Configure Project Details**) for project-specific permissions.
- Use the **Admin** panel only for system-level administrative access.

When assigning roles, apply the principle of least privilege: give each user the minimum access needed to perform their work.

> **Tip:** The most common cause of unexpected access problems is a mismatch between a user's project role and the actions they need to perform. If you receive a report that an expected action is unavailable, check the member's project role before investigating other causes.

---

## 13.3 Reference Data and Configuration

Reference data and configuration settings should be reviewed whenever new projects, workflows, or study requirements are introduced.

Keeping shared configuration values consistent across the system ensures that data entry and reporting remain reliable.

### System-level reference data (Admin panel)

The following configuration areas are managed at the system level:

| Area | Purpose |
|---|---|
| **Study Designs** | Templates for study structure used across projects |
| **Unit Definitions** | Standard units for specimen volumes and measurements |
| **Physical Units** | Physical storage container types and dimensions |

### Team-level configuration

The following are managed at the team level and are available to all projects within the team:

| Area | Purpose |
|---|---|
| **Assay Definitions** | Templates for assay record structure, including custom metadata fields |
| **Protocols** | Standard operating procedures associated with study or specimen work |

When a project requires a new assay type, specimen unit, or study design, review whether a configuration update is needed before the project goes live.

---

## 13.4 Preparing for New Projects

Before onboarding a new project, confirm that the following are in place:

- [ ] Required user accounts exist and are active
- [ ] Project members have been assigned appropriate roles
- [ ] Arm and event templates reflect the study design
- [ ] Specimen types and labware configurations are correct
- [ ] Relevant reference data (assay definitions, protocols, units) is configured
- [ ] If the project uses REDCap: the project has been created using the **Create New REDCap-Linked Project** workflow and member tokens are configured

> **Tip:** Walking through this checklist before a project begins active enrolment will prevent the most common setup errors. See [Chapter 2 — Setting Up a Project](02-project-setup.md) for the full project configuration workflow, and [Chapter 12 — REDCap Integration](12-redcap-integration.md) if the project requires a REDCap link.

---

## 13.5 Operational Monitoring

Operational monitoring involves reviewing recurring user issues, checking that access remains appropriate, and confirming that configuration stays aligned with actual practice.

When the same problem is reported more than once:

1. Review whether the root cause is a permissions issue, a process gap, or outdated configuration.
2. Check whether reference data (such as specimen types or labware barcodes) needs updating.
3. Review whether user roles need adjustment.
4. Update documentation or configuration as needed to prevent recurrence.

Signs that a configuration review may be due:

- Multiple users reporting that an action is unavailable
- Repeated barcode validation failures not explained by scanning errors
- Unexpected gaps in specimen or event records across a project

---

## Summary

| Task | Where to go |
|---|---|
| Create a user account | **Admin** → **Users** → **New User** |
| Deactivate a user account | **Admin** → **Users** → edit the user record |
| Add a project member | **Configure Project Details** → **Members** |
| Manage system reference data | **Admin** panel → relevant configuration area |
| Manage assay definitions | Team-level **Assay Definitions** area |
| Prepare a new project for go-live | Checklist in [section 13.4](#134-preparing-for-new-projects) |
| Troubleshoot a recurring operational issue | Review permissions, process, and configuration in that order |

---

*Previous chapter:* [Chapter 12 — REDCap Integration](12-redcap-integration.md)  
*Next chapter:* See [Chapter 10 — Troubleshooting Common Problems](10-troubleshooting.md) or [Chapter 11 — Glossary](11-glossary.md)
