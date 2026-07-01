# BRIMS User Manual

## Chapter 1: Getting Started

---

## Overview

This chapter explains how to sign in to BRIMS, how the system is organised, and how to navigate it effectively. It also explains how roles and permissions work, which will help you understand why the system may look or behave slightly differently for different members of your team.

Even if you have used similar research data systems before, reading this chapter first will help you work more confidently in BRIMS.

---

## 1.1 Signing In

BRIMS is a web-based application. You access it through a browser using the URL provided by your system administrator.

To sign in, you need:

- An active BRIMS account
- Your account credentials (username and password)

When your account is first created, you may receive an invitation to set your password. Complete this step before trying to sign in for the first time. If the invitation link has expired, ask your system administrator to issue a new one.

![The BRIMS sign-in screen showing the username and password fields.]()

Once signed in, you will land on the main BRIMS interface. The area you see first will depend on your role and what you have access to.

> **Tip:** If you cannot sign in, confirm that you are entering the correct email address and password. Passwords are case-sensitive. If your credentials appear correct but access is still refused, your account may be inactive or you may not yet have been added to the correct project. Contact your project administrator.

---

## 1.2 How BRIMS Is Organised

BRIMS is built around a **project** as its main organisational unit. Almost all of your day-to-day work — participant enrolment, specimen logging, storage, shipments, and study data — takes place within a specific project.

This is important to understand from the start: the actions, records, and data you see are scoped to the project you are currently working in. If you switch to a different project, you will see different participants, specimens, and study records.

Below the project level, the main building blocks are:

| Concept | What it represents |
|---|---|
| **Project** | The top-level research effort, bringing together all sites, participants, and studies |
| **Site** | A physical or organisational location where project work is carried out |
| **Arm** | A participant grouping within the project, such as a control or treatment cohort |
| **Subject** | A participant enrolled in the project — BRIMS uses the term *subject* for participant records |
| **Event** | A scheduled or completed study activity, such as a visit or specimen collection point |
| **Specimen** | A biological sample that is logged, tracked, stored, shipped, or used |
| **Study** | A research investigation within the project, used to organise specimens and assay work |
| **Manifest** | A shipment or transfer record grouping specimens for movement between sites |

These records are connected. A subject belongs to a site and an arm; events are linked to subjects; specimens are linked to events and subjects; specimens can be added to studies and manifests. This is why accuracy at each step matters — downstream records inherit the context set earlier.

> **Tip:** If something looks wrong in a later workflow step — for example, a specimen cannot be found, or an event appears under the wrong participant — the first place to check is usually the record created at the earlier step. Working carefully and confirming identifiers as you go prevents the most common data quality problems.

---

## 1.3 Navigating the System

### Main Navigation

The main navigation menu gives you access to the areas of BRIMS relevant to your role and project.

![The main BRIMS navigation menu with a project selected, showing Subjects, Specimens, Specimen Storage, Manifests, Studies, and Configure Project Details.]()

Common navigation areas include:

- **Subjects** — Participant enrolment, subject records, and linked events
- **Log Primary Specimens** — Barcode-driven logging of primary specimens
- **Log Derivative Specimens** — Logging of derivative (child) specimens from a parent
- **Specimens** — Reviewing, searching, and exporting the full specimen list
- **Specimen Storage** — Storage allocation and storage reports
- **Manifests** — Shipment and transfer records
- **Studies** — Study records, linked specimens, and assay data
- **Configure Project Details** — Project-level settings, sites, arms, specimen types, labware, and imports or exports

Not all of these areas will be visible to every user. What you see depends on your project role.

### Moving Between Records

Within each section, BRIMS uses a standard pattern:

1. A **list view** shows all records of that type for the current project
2. Clicking a record opens its **detail page**, where you can view all related information and take actions
3. Related records — such as events linked to a subject, or specimens linked to a study — appear in **tabs** or **sections** on the detail page

Get familiar with this pattern early. Most workflows in BRIMS follow the same structure: open a list, find or create a record, navigate to its detail page, and take the appropriate action from there.

### Working Within the Correct Project

Before doing any work in BRIMS, always confirm that you are in the correct project.

The current project context is shown in the navigation. If you are working across multiple projects, take care when switching between them — particularly before creating or editing records, because there is no automatic prompt to warn you if you are in the wrong project.

> **Caution:** Records created in the wrong project cannot be automatically moved. If you enrol a participant or log a specimen in the wrong project, contact your data manager to discuss how to correct it.

---

## 1.4 Roles and Permissions

### What Roles Control

BRIMS uses a role-based permission system. Your role determines:

- Which sections of the navigation you can see
- Which actions are available on record pages (for example, whether you can create, edit, or delete records)
- Which exports and reports you can access

Roles operate at two levels:

- **System roles** apply to wider administrative functions outside of specific projects
- **Project roles** apply within a specific project and are assigned when you are added to that project

A user can have different roles in different projects. For example, a data manager may have read-only access in one project and full editing access in another.

### When Actions Are Missing

If a button or action that you expect to see is not visible, this is usually because your current role does not include that permission.

Common situations include:

- The **Enrol** button not appearing on a subject record — check that you have enrolment permissions in this project
- Export options being hidden — these are often restricted to data managers or project administrators
- Record editing being unavailable — some records, such as generated subjects, use dedicated action buttons (like **Enrol**) rather than a general edit form

If you are confident that you should have access to an action and it is still missing, contact your project administrator to review your role assignment.

> **Tip:** It is worth asking your project administrator for a brief explanation of your project role when you first join a project. Knowing which permissions you have — and which you do not — will save time and help you avoid confusion when certain options are not visible.

---

## 1.5 Safe Working Practices

Because BRIMS links records across workflows, some actions have downstream consequences that can be difficult to reverse. The following habits will help you work accurately and safely:

- **Confirm the project context** before creating or editing any record
- **Read identifiers carefully** before saving — subject IDs, specimen barcodes, and study codes should always be verified before submission
- **Do not guess on status changes** — if you are unsure which status to apply to a participant, specimen, or event, check your project procedure or ask your data manager before proceeding
- **Check related records before deleting** — some records (such as open manifests) can be deleted, but doing so may affect linked specimens or shipment history
- **Record things at the time they happen** — for specimen logging, event recording, and storage actions, contemporaneous entry is more accurate than retrospective entry and reduces the risk of transcription errors

These practices are emphasised throughout the relevant chapters, but establishing them as habits from the start will make your day-to-day work in BRIMS more reliable.

---

## Summary

| Task | Where to go |
|---|---|
| Sign in | Browser URL provided by your administrator |
| Switch projects | Project selector in the main navigation |
| Find a participant | **Subjects** list |
| Find a specimen | **Specimens** list |
| Check your role | Contact your project administrator |
| Report a problem | See [Chapter 10 — Troubleshooting](10-troubleshooting.md) |

---

*Previous chapter:* [Chapter 0 — Introduction](00-introduction.md)  
*Next chapter:* [Chapter 2 — Setting Up a Project](02-project-setup.md)
