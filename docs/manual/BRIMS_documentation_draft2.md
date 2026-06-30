# BRIMS User Manual

**Bio-medical Research Information Management System**

Draft 2 — 30 June 2026
Major revision — 30 June 2026

---

## Table of Contents

- [Chapter 0 — Introduction](#chapter-0-introduction)
- [Chapter 1 — Getting Started](#chapter-1-getting-started)
- [Chapter 2 — Setting Up a Project](#chapter-2-setting-up-a-project)
- [Chapter 3 — Enrolling and Managing Participants](#chapter-3-enrolling-and-managing-participants)
- [Chapter 4 — Recording Events and Follow-up](#chapter-4-recording-events-and-follow-up)
- [Chapter 5 — Logging Specimens](#chapter-5-logging-specimens)
- [Chapter 6 — Managing Specimen Storage](#chapter-6-managing-specimen-storage)
- [Chapter 7 — Preparing and Receiving Shipments](#chapter-7-preparing-and-receiving-shipments)
- [Chapter 8 — Studies, Assay Data, and Publications](#chapter-8-studies-assay-data-and-publications)
- [Chapter 9 — Searching, Reviewing, Exporting, and Importing Data](#chapter-9-searching-reviewing-exporting-and-importing-data)
- [Chapter 10 — Troubleshooting Common Problems](#chapter-10-troubleshooting-common-problems)
- [Chapter 11 — REDCap Integration](#chapter-11-redcap-integration)
- [Chapter 12 — Administration Guide](#chapter-12-administration-guide)
- [Chapter 13 — Glossary](#chapter-13-glossary)

---

---

## Chapter 0 — Introduction

---

## What Is BRIMS?

BRIMS — the **Bio-medical Research Information Management System** — is a web-based platform designed to support the day-to-day operational and data management requirements of multi-site biomedical research studies.

BRIMS brings together several research functions under a single system:

- Participant enrolment and follow-up scheduling
- Specimen logging, barcode tracking, and storage management
- Shipment and transfer workflows
- Study and assay data organisation
- Reporting and data export

Because these functions share a common data structure, information entered at one stage of the workflow — for example, a participant enrolment or a specimen log — flows automatically into related records, reducing the need for duplicate data entry and minimising the risk of transcription errors.

> **Tip:** This connected structure means that the quality of your data at each step matters. A participant record linked correctly to the right site and arm will save time at every later stage, from specimen logging through to study reporting.

---

## Who This Manual Is For

This manual is written for three groups of staff who work with BRIMS as part of a research programme:

### Research and Study Coordination Staff

This includes research nurses, study coordinators, and field staff who enrol participants, record follow-up events, and monitor participant progress through a study.

Relevant chapters are:
- Chapter 3 — Enrolling and Managing Participants
- Chapter 4 — Recording Events and Follow-up

### Laboratory and Specimen Handling Staff

This includes laboratory technicians, biobank staff, and anyone responsible for logging, storing, or shipping biological specimens.

Relevant chapters are:
- Chapter 5 — Logging Specimens
- Chapter 6 — Managing Specimen Storage
- Chapter 7 — Preparing and Receiving Shipments

### Project Managers and Data Managers

This includes project leaders, study administrators, and data managers responsible for configuring the system, linking data to studies, and producing reports.

Relevant chapters are:
- Chapter 2 — Setting Up a Project
- Chapter 8 — Studies, Assay Data, and Publications
- Chapter 9 — Searching, Reviewing, Exporting, and Importing Data
- Chapter 11 — REDCap Integration
- Chapter 12 — Administration Guide

---

## How to Use This Manual

If you are new to BRIMS, read Chapter 1 — Getting Started first. This chapter explains how to sign in, how to navigate the system, and how your role affects what you can see and do.

After that, follow the reading path for your role above.

If you are troubleshooting a specific problem, go directly to Chapter 10 — Troubleshooting Common Problems.

For REDCap integration setup and troubleshooting, refer to Chapter 11 — REDCap Integration.

For system administration tasks including user management and reference data configuration, refer to Chapter 12 — Administration Guide.

For definitions of terms used across the system and this manual, refer to Chapter 13 — Glossary.

### Notes on Format

Throughout this manual:

- **Bold text** is used for named interface elements such as buttons, menu items, and field labels (for example, **Enrol**, **Subjects**, **Log Primary Specimens**).
- Steps presented as numbered lists should be followed in sequence.
- Indented notes and cautions are used to highlight actions that require special care or that cannot easily be undone.
- Illustrations are included throughout to show key screens and actions as they appear in the system.

---

## A Note on Permissions

BRIMS controls what each user can see and do through a permission system based on roles.

Your role determines which menu areas are available, which actions appear on record pages, and which exports or reports you can access.

As a result, the interface you see may not match exactly what another team member sees, particularly if your project roles differ.

---

## About This Manual

This manual describes BRIMS as it currently operates. It is intended to be updated as the system evolves.

If you find that a described step does not match what you see in the system, or if you encounter a workflow that this manual does not cover, please report it to your project administrator or data manager so that the documentation can be reviewed.

---

---

## Chapter 1 — Getting Started

---

## Overview

This chapter explains how to sign in to BRIMS, how the system is organised, and how to navigate it effectively. It also explains how roles and permissions work, which will help you understand why the system may look or behave slightly differently for different members of your team.

Even if you have used similar research data systems before, reading this chapter first will help you work more confidently in BRIMS.

---

## 1.1 Signing In

BRIMS is a web-based application. You access it through a browser using the URL provided by your system administrator.

To sign in, you need:

- An active BRIMS account
- Your account credentials (email address and password), or a registered passkey

BRIMS supports passkey authentication (such as a device biometric or hardware security key) as an alternative to password sign-in. To use a passkey, it must first be registered on your account. Contact your system administrator if you are unsure which method applies to you.

When your account is first created, you may receive an invitation to set your password. Complete this step before trying to sign in for the first time. If the invitation link has expired, ask your system administrator to issue a new one.

![The BRIMS sign-in screen showing the email and password fields and a passkey sign-in option.]()

Once signed in, you will land on the main BRIMS interface. The area you see first will depend on your role and what you have access to.

> **Tip:** If you cannot sign in, confirm that you are entering the correct email address and password. Passwords are case-sensitive. If your credentials appear correct but access is still refused, your account may be inactive or you may not yet have been added to the correct project. Contact your project administrator.

---

## 1.2 How BRIMS Is Organised

BRIMS organises work at two levels: a **team** and a **project**.

A **team** is the top-level organisational unit in BRIMS. It represents the research group or organisation that operates the system and owns the projects within it. Teams have their own members, protocols, and assay definitions. Every user belongs to a team, and every project belongs to a team.

A **project** is the main working unit within a team. Almost all of your day-to-day work — participant enrolment, specimen logging, storage, shipments, and study data — takes place within a specific project.

This is important to understand from the start: the actions, records, and data you see are scoped to the project you are currently working in. If you switch to a different project, you will see different participants, specimens, and study records.

The main building blocks are:

| Concept | What it represents |
|---|---|
| **Team** | The organisational group that owns projects, members, protocols, and assay definitions |
| **Project** | The primary working unit within a team, bringing together all sites, participants, and studies |
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

### 1.3.1 Main Navigation

The main navigation menu gives you access to the areas of BRIMS relevant to your role and project. [Note: assumes a project has been created previously and can be accessed by user]

![The main BRIMS navigation menu with a project selected, showing Subjects, Specimens, Specimen Storage, Manifests, Studies, and Project Configuration.]()

Common navigation areas include:

- **Team** — Your team's overview page, showing members, projects, protocols, assay definitions, and programmes
- **Project Configuration** — Project-level settings, sites, arms, specimen types, labware, and imports or exports
- **Subjects** — Participant enrolment, subject records, and linked events
- **Label Queue** — Queued barcode labels for upcoming events, ready to print
- **Specimens** — Reviewing, searching, and exporting the full specimen list
- **Log Primary Specimens** — Barcode-driven logging of primary specimens
- **Log Derivative Specimens** — Logging of derivative (child) specimens from a parent
- **Specimen Storage** — Storage allocation and storage reports
- **Manifests** — Shipment and transfer records
- **Studies** — Study records, linked specimens, and assay data
- **Publications** — Bibliographic records for research outputs associated with the project

Not all of these areas will be visible to every user. What you see depends on your project role.

### 1.3.2 Moving Between Records

Within each section, BRIMS uses a standard pattern:

1. A **list view** shows all records of that type for the current project
2. Clicking a record opens its **detail page**, where you can view all related information and take actions
3. Related records — such as events linked to a subject, or specimens linked to a study — appear in **tabs** or **sections** on the detail page

Get familiar with this pattern early. Most workflows in BRIMS follow the same structure: open a list, find or create a record, navigate to its detail page, and take the appropriate action from there.

### 1.3.3 Working Within the Correct Project

Before doing any work in BRIMS, always confirm that you are in the correct project.

The current project context is shown in the navigation. If you are working across multiple projects, take care when switching between them — particularly before creating or editing records, because there is no automatic prompt to warn you if you are in the wrong project.

> **Caution:** Records created in the wrong project cannot be automatically moved. If you enrol a participant or log a specimen in the wrong project, contact your data manager to discuss how to correct it.

---

## 1.4 Roles and Permissions

### 1.4.1 What Roles Control

BRIMS uses a role-based permission system. Your role determines:

- Which sections of the navigation you can see
- Which actions are available on record pages (for example, whether you can create, edit, or delete records)
- Which exports and reports you can access

Roles operate at two levels:

- **System roles** apply to wider administrative functions outside of specific projects
- **Project roles** apply within a specific project and are assigned when you are added to that project

A user can have different roles in different projects. For example, a data manager may have read-only access in one project and full editing access in another.

### 1.4.2 When Actions Are Missing

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
| Report a problem | See Chapter 10 — Troubleshooting |

---

---

## Chapter 2 — Setting Up a Project

---

## Overview

This chapter is for project managers and study administrators who are responsible for configuring a new project in BRIMS before data collection begins.

Project setup must be completed before research and laboratory staff can enrol participants or log specimens. A well-configured project takes approximately an hour to set up from scratch, but the decisions made here — particularly around subject ID format, study arms, and specimen types — will affect every workflow that follows. It is worth taking the time to review your study protocol before you begin.

The recommended setup order is:

1. Create the project
2. Review the defaults BRIMS creates automatically
3. Add sites
4. Create study arms and event definitions
5. Configure specimen types and labware
6. Review roles and add project members
7. Create studies

Each step is covered in its own section below. A [setup checklist](#setup-checklist) is provided at the end of the chapter.

---

## 2.1 Creating a Project

Navigate to your team's **Projects** section and select **New Project**. 

![The project creation form showing required fields: title, identifier, project leader, storage designation, and subject ID settings.]()

### 2.1.1 Required fields

| Field | What to enter |
|---|---|
| **Title** | The full name of the project. This must be unique across the system. |
| **Identifier** | A short alphanumeric code used to reference the project (e.g. `BRIM-001`). This must also be unique across the system. |
| **Project Leader** | The user assigned as project lead. They are automatically added as a project administrator on creation. |
| **Label Format** | The label specification that defines the format used for printing participant labels in this project. Label specifications are configured in advance by a system administrator (see Chapter 12 — Administration Guide). |
| **Subject ID Prefix** | Two to ten uppercase letters that will be prepended to all participant identifiers (e.g. `BRI`). |
| **Subject ID Digits** | The number of digits in the numeric part of the participant ID (between two and eight). |

> **Note — Study Design:** A study design label is shown on the project details page but is not set during project creation. It is assigned at the system level by an administrator. If your project requires a specific study design label, contact your system administrator.

### 2.1.2 Optional fields

| Field | What to enter |
|---|---|
| **Description** | A free-text description of the project. |
| **Storage Designation** | An identifier for the storage location associated with this project (e.g. a freezer block code or site abbreviation). |
| **Submission Date** | The date the project was or will be formally submitted. |

> **Important — Subject ID format:** The prefix and digit count together define the format of every participant identifier the system will generate. For example, a prefix of `BRI` with six digits produces IDs such as `BRI000001`, `BRI000002`, and so on. Choose this format according to your study protocol and confirm it with your team before saving. This setting is difficult to change once participants have been enrolled and identifiers have been issued.

> **REDCap projects:** If this project needs to be linked to REDCap, do not use the standard project form. Instead, use the **Create New REDCap-Linked Project** option available from the team Projects section. See Chapter 11 — REDCap Integration for full setup and troubleshooting guidance.

### 2.1.3 What BRIMS creates automatically

When you save a new project, BRIMS sets up the following automatically:

- A default **Admin** role for the project
- An initial site based on the project creator's home site
- Project membership for the project creator and the project leader

These defaults are a starting point. Review them before proceeding with the rest of the setup, particularly the initial site name and the Admin role permissions.

Sections 2.2 to 2.6 cover the remaining project configuration steps. All of these are accessed through **Project Configuration** in the main navigation panel. Select your project from the list of  projects using the **Access** button, then open **Project Configuration** to find the tabs for Members, Sites, Arms, Labware and Specimen Types.

---

## 2.2 Adding Sites

Sites represent the physical or organisational locations where research is conducted within the project. If your project operates across more than one hospital, clinic, laboratory, or institution, add a separate site for each.

Sites also matter for access control: project members and their substitutes are assigned to sites, which affects what they can see and do.

Navigate to the project view, open the **Sites** tab, and select **New Site**.

| Field | What to enter |
|---|---|
| **Name** | A short name for the site (two to twenty characters). Must be unique within the project. |
| **Description** | A brief description of the site's function or location. |

> **Tip:** An initial site is created automatically when the project is set up. Check the name of this default site before adding others — if it was created from the project creator's home site, it may need to be renamed to match your project's site naming convention.

> **Tip:** If you are unsure how many sites your project will need, plan based on where specimens will be collected and where they will be processed or stored. A site structure that mirrors the physical research locations makes specimen tracking and member assignment significantly more straightforward later.

---

## 2.3 Creating Study Arms

Study arms divide project participants into distinct groups or cohorts — for example, a control group and one or more treatment groups. Every enrolled participant must be assigned to one arm.

Navigate to the project view, open the **Arms** tab, and select **New Arm**.

| Field | What to enter |
|---|---|
| **Name** | A descriptive name for the arm (e.g. `Control`, `Treatment A`). Must be unique within the project. |
| **Manual Enrolment** | Enable this if participants should be manually assigned to this arm. Leave disabled if assignment will be automated. |

BRIMS assigns arm numbers automatically. The arm number cannot be set manually.

When two or more arms exist in a project, the arm form also shows a **Switch Arms** checkbox list. Ticking an arm here means that participants currently enrolled in this arm can be moved to the selected arm using the **Switch Arm** action on a subject's record. When a switch is performed, all of the subject's pending, primed, and scheduled events from the current arm are cancelled, the subject is assigned to the new arm with a new baseline date, and a fresh set of events is generated from the new arm's events. If no arms are ticked, the Switch Arm option will not appear for any subject in this arm.

> **Tip:** Create all arms before you start adding event definitions. Once participants are enrolled, changing an arm's structure may affect the events that have already been scheduled for subjects.

### 2.3.1 Adding event definitions to an arm

Events represent the visit schedule or follow-up milestones for participants in that arm. After you create an arm, open it and add the events that apply to subjects in that cohort.

![An arm record with the Events section open, showing event definitions with fields for name, offset, and scheduling windows.]()

| Field | What it does |
|---|---|
| **Name** | A descriptive label for the event (e.g. `Screening Visit`, `Month 6 Follow-up`). |
| **Offset** | The number of days from enrolment at which this event is expected to occur. |
| **Ante Window** | How many days before the scheduled date the event can still be recorded as on time. |
| **Post Window** | How many days after the scheduled date the event can still be recorded as on time. |
| **Autolog** | If enabled, BRIMS logs this event automatically at the defined offset. |
| **Repeatable** | If enabled, additional iterations of this event can be added after the first one is recorded. |
| **Name Labels** | The number of full-name barcode labels to print per event. Each label shows the participant's name, the project–subject–event ID, event name, iteration, and arm name. Enter `0` if not required. |
| **Subject Event Labels** | The number of PSE (Project–Subject–Event) barcode labels to print per event. Each label shows the subject ID, PSE ID, event name, iteration, and arm name. Enter `0` if not required. |
| **Study ID Labels** | The number of subject ID-only barcode labels to print per event. Each label shows the subject ID alone. Enter `0` if not required. |
| **Active** | Controls whether this event definition is currently in use for new subjects. |

> **Tip:** The offset, ante window, and post window together define the acceptable timing range for each visit. Setting these correctly helps follow-up review reports distinguish between events that were on time, those recorded within an acceptable window, and those that were genuinely missed or late. Discuss these values with your study statistician or clinical operations lead before entering them.

> **Tip — Reordering events:** Events within an arm can be reordered by dragging and dropping rows in the events table. The order determines which event is treated as first (event order 1), which affects enrolment: the first event with an offset of zero is automatically given a Scheduled status when a participant is enrolled. Reorder events before enrolment begins if the default order does not match your intended schedule.

---

## 2.4 Configuring Specimen Types and Labware

Before specimen logging begins, configure the specimen types and labware that the project will use. This step is essential: specimen logging workflows in BRIMS rely on these settings to identify and validate samples.

Navigate to the project configuration view and use the **Labware** and **Specimen Types** sections.

![The Specimen Types and Labware configuration areas, showing type settings and barcode format fields.]()

### 2.4.1 Labware

Labware records define the physical container associated with a specimen type and the barcode format expected during logging. Create a labware record for each distinct container type used in the project before configuring specimen types, as specimen types must reference a labware record.

| Field | What to enter |
|---|---|
| **Name** | A short, descriptive name for the container (e.g. `EDTA Tube`, `Serum Vacutainer`). |
| **Barcode Format Regex** | A regular expression that defines the expected barcode format for this labware. BRIMS validates every scanned or entered barcode against this pattern — if the barcode does not match, the logging step will fail. The regex must begin with `^` and end with `$`; BRIMS will add these automatically if omitted. |

> **Tip:** Work with your laboratory team to confirm the barcode format in use before entering the regex pattern. A small formatting error here can block logging for the entire project. If you are not familiar with regular expressions, ask your data manager or system administrator to help define this field.

### 2.4.2 Specimen types

Specimen types define what kind of sample is being collected and how it should be handled. Each specimen type must be linked to a labware record.

| Field | What to enter |
|---|---|
| **Name** | A descriptive name for the specimen type (e.g. `EDTA Whole Blood`, `Serum`). |
| **Primary** | Enable if this specimen is collected directly from the participant. Disable if it is derived from another specimen (e.g. plasma separated from whole blood). |
| **Parent Specimen Type** | Only available when **Primary** is disabled. Select the primary specimen type that this derivative is processed from. |
| **Aliquots** | The number of aliquots (portions) that are expected per specimen of this type. Must be at least 1. |
| **Pooled** | Enable if specimens of this type are pooled from multiple sources. |
| **Default Volume** | The typical volume of each aliquot (e.g. `1.0`). Required if a volume unit is entered. |
| **Volume Unit** | The unit for the volume (e.g. `mL`, `µL`). Required if a default volume is entered. |
| **Specimen Group** | An optional free-text grouping label, used to organise specimen types in reports or exports (e.g. 'Blood'). |
| **Labware** | Select the labware record that corresponds to the physical container for this specimen type. |
| **Store** | Enable if specimens of this type should be allocated to storage after logging. |
| **Storage Specimen Type** | Only available when **Store** is enabled. The label used to identify this specimen type within the storage system. |
| **Destination** | Only available when **Store** is enabled. Select `Internal` for on-site storage within BRIMS, or `Biorepository` for transfer to an external biorepository. |
| **Transfer Destinations** | Optional. Add one or more destination labels if specimens may be transferred to named locations outside of the standard storage workflow. |
| **Active** | Controls whether this specimen type is available for logging. Disable to retire a type without deleting it. |

> **Tip:** Configure derivative specimen types only after the parent primary type has been created, as the form requires you to select the parent type. If a derivative type is created before its parent, it will not be available to select.

---

## 2.5 Reviewing Roles and Adding Project Members

### 2.5.1 Reviewing roles [*still to check*]

Roles define what each project member is permitted to do. BRIMS creates a default Admin role when the project is set up, but most projects need at least a few distinct roles — for example, a data manager role, a clinical staff role, and a laboratory role.

Before adding members, navigate to **Roles** in the project sidebar and confirm that the available roles reflect the real working responsibilities of your team.

To create a new role:

1. Select **New Role**
2. Enter a name for the role
3. Assign the appropriate permissions from the list

Permissions take effect as soon as a role is saved. Members assigned to that role will have their access updated automatically.

> **Tip:** Keep roles aligned with job functions rather than individual users. A role called `Laboratory Staff` is easier to manage and audit over time than multiple individually named roles. If a team member changes responsibilities, updating their role assignment is much simpler than maintaining a custom permission set.

### 2.5.2 Adding project members

Project members are users from your team who have been granted access to work within the project.

Navigate to the **Members** tab and select **Attach Member**.

| Field | What to enter |
|---|---|
| **User** | Select from the list of available team users. Each user can only be added once per project. |
| **Role** | Select the project role that defines this member's permissions. |
| **Site** | Assign the member to a project site where applicable. This is required if you intend to configure a substitute. |

> **Note:** The project leader cannot be detached from the project. To reassign project leadership, edit the project record and select a different user in the **Project Leader** field.

### 2.5.3 Member substitutes

A substitute is another project member who can act on behalf of a member when they are unavailable (for example, during leave or absence).

To assign a substitute, open the member record in the Members list and use the **Select Substitute** action. The substitute must:

- Already be a member of the same project
- Be assigned to the same project site as the member they are covering

> **Note:** If a member's site assignment changes and the current substitute is not assigned to the new site, the substitute assignment will be cleared automatically. Review substitute assignments whenever site configurations change.

### 2.5.4 REDCap tokens

If the project is linked to REDCap and particular members need REDCap API access, a personal token can be stored against each member's record. Open the member entry and add or update the token in the **REDCap Token** field.

---

## 2.6 Creating Studies

Studies are research investigations that sit within a project. A project may contain multiple studies, each with its own associated specimens and assay records.

Navigate to **Studies** in the project sidebar and select **New Study**.

![The study creation form showing title, identifier, description, and date fields.]()

### 2.6.1 Required fields

| Field | What to enter |
|---|---|
| **Title** | The full name of the study. Must be unique within the project. |
| **Identifier** | A short reference code for the study. Must be unique across BRIMS — not only within the project. |

### 2.6.2 Optional fields

| Field | What to enter |
|---|---|
| **Description** | A free-text description of the study's scope or objectives. |
| **Submission Date** | The date the study was or will be formally submitted. |
| **Public Release Date** | The planned public release date for the study, if applicable. |

> **Tip:** The study identifier will appear in exports, reports, and assay records. Choose a code that is meaningful to your team and consistent with any identifiers already used in your study protocol or ethics application.

### 2.6.3 Locking a study

The **Locked** toggle prevents specimens from being added to or removed from a study. Use this when the study has reached a defined data cut-off or analytical milestone to preserve the integrity of the specimen set. This action is reversible.

> **Warning:** Locking a study is a significant action. Confirm that all expected specimens have been associated with the study, and that no further additions are anticipated, before enabling this toggle. Locked studies continue to be viewable; only specimen association is restricted.

---

## 2.7 Import Value Mappings

If you intend to bulk-import participant, event, or specimen records from an external system, you can configure import value mappings to translate the field values used in your source files into the names used in BRIMS. This step is optional if your source data already uses exactly the same names as your project configuration.

Navigate to the **Project Configuration** view (open the project via **Access**, then open **Project Configuration**) and open the **Import Value Mappings** tab. Select **Create** to add a new mapping.

### 2.7.1 How mappings work

1. Choose the **Model** — the data type this mapping applies to: **Subject**, **SubjectEvent**, or **Specimen**.
2. For each field shown, the left column (Key) displays the BRIMS database value (for example, a site name, arm name, or status value). Enter the corresponding value from your import file in the right column (Value). Leave the value blank if the import file already uses the same name as BRIMS.
3. Enter a descriptive name for the mapping record and save.

Mappings are applied automatically when the matching import type is run for the project.

### 2.7.2 Available mapping fields

| Model | Fields |
|---|---|
| **Subject** | Site (by name), Arm (by name) |
| **SubjectEvent** | Event (by name), Status (by status name) |
| **Specimen** | Specimen Type (by name), Status (by status name) |

> **Tip:** Create mapping records before running any imports. An unmapped value that does not exactly match a BRIMS name will cause all rows containing that value to fail validation. See Chapter 9 — Searching, Reviewing, Exporting, and Importing Data, section 9.7 for full details of the import process.

---

## 2.8 Linking a Project to Programmes

Programmes are team-level records that represent funded research programmes — for example, a grant-funded initiative that encompasses several related projects. A project can be linked to one or more programmes.

Programmes are created and managed at the **team** level, not the project level. To create or view programmes for your team, navigate to the team overview and open the **Programmes** tab. See Chapter 12 — Administration Guide for full details on creating and managing programmes.

To link an existing programme to a project:

1. Open the project record from the team Projects list.
2. Navigate to the **Programmes** tab on the project record.
3. Select **Attach Programme** and choose the programme from the list.

To remove a programme link, use the **Detach** action on the programme row.

> **Note:** Only programmes belonging to the same team as the project are available to link. If the programme you need does not appear, confirm with your team administrator that it has been created at the team level.

---

## Setup Checklist

Use this checklist to confirm that the project is ready before research and laboratory staff begin work.

- [ ] Project created with a unique title and identifier
- [ ] Subject ID prefix and digit count confirmed by the study team
- [ ] Label format selected from available label specifications
- [ ] Storage designation entered (optional)
- [ ] Default site reviewed; additional sites created as needed
- [ ] All study arms created and named
- [ ] Event definitions added to applicable arms, with offsets and windows confirmed
- [ ] Specimen types configured for primary and derivative samples as needed
- [ ] Labware records created with correct barcode format patterns
- [ ] Default Admin role reviewed; additional roles created to reflect team functions
- [ ] All project members added with appropriate roles and site assignments
- [ ] Substitute assignments configured where needed
- [ ] At least one study created
- [ ] Import value mappings configured if bulk data import from an external system is planned

---

---

## Chapter 3 — Enrolling and Managing Participants

---

## Overview

This chapter is primarily for research nurses, study coordinators, and field staff responsible for enrolling participants and maintaining records throughout a study.

In BRIMS, participants are recorded under **Subjects**. The term *subject* is used consistently across the system, and this manual uses *participant* and *subject* interchangeably. Each participant is assigned a unique **subject ID** that is generated automatically from the project's identifier settings.

Participant records are the foundation of the research workflow. A correctly enrolled subject — assigned to the right site and arm — ensures that events are scheduled correctly and that specimens and study data can be linked to the right person.

---

## 3.1 Before You Begin

Before enrolling participants, confirm that the following project setup steps have been completed:

- The project has been created with subject ID prefix and digit settings confirmed
- At least one study arm exists and has event definitions assigned to it
- You have been added as a project member with a role that includes enrolment permissions
- You know which arm each participant should be assigned to

If any of these are missing, work with your project manager before proceeding. Enrolling a participant without the correct arm or event configuration will create gaps in the follow-up schedule.

---

## 3.2 How Subject Records Are Created

Subject records are created manually using the **Generate Subjects** action on the Subjects list. This action is available to users with the appropriate permissions and only appears for arms that have **Manual Enrolment** enabled.

To generate subjects, navigate to the **Subjects** list and select **Generate Subjects**. You will be asked to choose an arm and specify how many subject records to create (between 1 and 20 at a time). BRIMS will create the requested number of records, each with a subject ID pre-assigned using the project's configured prefix and digit format.

The **site** and **manager** for each generated subject are assigned automatically from your project membership record — the site you are assigned to and your user account become the default site and manager for all subjects you generate. These values can be updated later by editing the enrolled subject record if needed.

These records appear in the **Subjects** list with a status of **Generated**. A Generated subject has a subject ID already assigned and event records have been created for all of the arm's event definitions. No participant details have yet been confirmed — the record acts as a placeholder until a real participant is enrolled into it.

> **Important:** Do not generate more subject records than you need. Generated records with unallocated IDs create gaps if they are never used. Generate a small batch at a time and add more as needed. Always use the **Enrol** action on an existing Generated record rather than creating a new one from scratch — doing so would cause identifier conflicts and gaps in the numbering sequence.

---

## 3.3 Enrolling a Participant

### 3.3.1 Step-by-step

1. Navigate to the **Subjects** list in the project navigation.
2. Locate a record with status **Generated**. Use the search or filter tools if the list is long.
3. Use the **Enrol** action button on the row. You do not need to open the record first — the enrolment form is accessible directly from the Subjects list.
4. The enrolment form will open. Complete all required fields carefully.
5. Review the automatically generated subject ID to confirm it matches the expected project format.
6. Save the record.

![The Subject enrolment form accessed by clicking the Enrol action button.]()

### 3.3.2 Enrolment form fields

| Field | What to enter |
|---|---|
| **First Name** | The participant's first name. |
| **Last Name** | The participant's last name. |
| **Enrolment Date** | The date the participant was formally enrolled. This date is used to calculate event schedules. |
| **Address** | Optional. Enter one address component per line (for example, street address, town, postcode). |

The participant's arm, site, and manager are pre-set from the **Generate Subjects** step and are displayed on the record. They are not editable through the enrolment form. If the arm, site, or manager is incorrect, do not proceed with enrolment — contact your project administrator to resolve the discrepancy first, as the arm determines which events will be scheduled.

> **Tip — Enrolment date and event scheduling:** The enrolment date is used as the baseline from which all scheduled events are calculated. For example, if a follow-up event has an offset of 30 days, it will be scheduled 30 days from the enrolment date. Enter the true enrolment date rather than today's date unless these are the same. A wrong enrolment date will cause all follow-up windows to shift incorrectly, which is difficult to resolve later.

Once enrolled, the participant's status changes to **Enrolled**. BRIMS uses the enrolment date to calculate the scheduled date window for each subject event. If the arm's first event has an offset of zero days, it is automatically marked as logged at this point.

---

## 3.4 Participant Statuses

Each subject record carries a status that reflects the participant's current position in the study.

| Status | Meaning |
|---|---|
| **Generated** | A subject ID has been created but no participant has been enrolled yet. |
| **Enrolled** | A participant has been enrolled and the record is active. |
| **Dropped** | The participant has withdrawn or been removed from active follow-up. |

Use these statuses consistently. Filtering the Subjects list by status is the most efficient way to identify which participants are active, which are generated and awaiting enrolment, and which have been dropped.

---

## 3.5 Updating a Participant Record

After enrolment, participant records may need to be updated if information changes — for example, if the address changes or if a field was entered incorrectly.

Use the **Edit** action on an enrolled subject's record.

> **Caution:** Changes to site assignment or arm assignment after enrolment can affect follow-up scheduling, specimen tracking, and reporting. Always confirm with your data manager before making structural changes to a participant record that is already associated with events or specimens.

---

## 3.6 Switching a Participant's Arm

If a participant moves from one study cohort to another — for example, from a control arm to a treatment arm — use the **Switch Arm** action on the subject record.

When an arm switch occurs, BRIMS:

1. Cancels all pending, primed, or scheduled events from the current arm
2. Records the previous arm and baseline date for audit purposes
3. Assigns the participant to the new arm
4. Creates a new set of subject events based on the new arm's event definitions

The arm switch date is used as the new baseline for calculating the events in the new arm.

> **Caution:** Arm switching is a significant action. Events that have already been logged in the previous arm are not affected, but all pending events are cancelled and cannot be recovered. Confirm that the switch is correct and approved before proceeding.

After a switch, the participant's previous arm name and baseline date remain visible on the subject record in the **Previous Arm** section, and the **Previous Arm** and **Previous Arm Baseline Date** columns appear in the Subjects list. This provides a quick audit reference without needing to open the individual record.

If an arm switch was made in error, use **Revert Arm Switch** to return the participant to their previous arm. This action is available on the subject record while the switch can still be undone.

---

## 3.7 Dropping a Participant

Use the **Drop Subject** action to indicate that a participant has withdrawn or should no longer receive active follow-up.

A dropped participant's record remains in the system and can be reviewed, but they will no longer appear as active in operational lists by default.

If a participant who was dropped needs to be reinstated, use the **Re-Instate Subject** action on the subject record.

> **Tip:** Always record a participant as Dropped rather than attempting to delete their record. A dropped record preserves the full audit history of enrolment, events, and specimens. Deletion is not the appropriate action and may not be permitted depending on your role.

---

## 3.8 Avoiding Duplicate Records

Duplicate participant records are one of the most disruptive data quality problems in a research database. Before enrolling a participant, always:

1. Search the Subjects list using the participant's name or expected subject ID
2. Confirm that no existing record already exists for this individual
3. If a possible duplicate is found, check with your data manager before proceeding

The subject ID format is designed to be unique. If two records appear to represent the same person, do not attempt to resolve the duplication yourself — escalate to your data manager.

---

## 3.9 Reviewing a Participant's History

Open a subject record to review the full history of a participant's involvement in the project.

![A subject record view showing enrolment details, Subject Events section, linked specimens, and arm assignment history.]()

From a subject record you can review:

- Enrolment details and the subject ID
- Current site, arm, and arm baseline date
- Previous arm and baseline date — displayed in a **Previous Arm** fieldset when the participant has previously switched arms
- The full list of scheduled and completed events (in the **Subject Events** section)
- Specimens linked to this participant

This history is useful when following up on overdue events, resolving data queries, or preparing for a participant's next visit.

---

## 3.10 The Label Queue

When a participant is enrolled, BRIMS automatically queues barcode labels for events that are due or upcoming. Labels are also queued for subjects with a **Generated** status — meaning labels for pre-enrolment events can be prepared and printed before a participant is formally enrolled. These labels are managed from the **Label Queue**, which appears in the project navigation and shows all subject events with a queued label status for the participants you manage directly or cover as a substitute.

The Label Queue table includes columns for subject ID, participant name, manager, arm, event, iteration, and scheduled event date. An arm filter is available to narrow the queue.

### 3.10.1 Printing labels

| Action | How to access | What it does |
|---|---|---|
| **Print All** | Header button | Opens all queued labels for printing in a new browser tab |
| **Print** | Row action | Opens labels for a single event in a new browser tab |
| **Print Selected** | Bulk action (checkboxes) | Opens labels for the selected rows in a new browser tab |

The **Print All** action now passes the project's configured label format to the print route automatically, so labels are printed in the correct format for the project.

### 3.10.2 Clearing labels from the queue

Clearing a label marks it as **Generated** and removes it from the queue. Do this after labels have been printed and applied.

| Action | How to access | What it does |
|---|---|---|
| **Clear All** | Header button | Marks all queued labels as Generated |
| **Clear from queue** | Row action | Marks a single event's labels as Generated |
| **Clear Selected** | Bulk action (checkboxes) | Marks selected events' labels as Generated |

> **Tip:** Print and clear in the same step to keep the queue accurate. Labels that are printed but not cleared will remain visible and may be printed again unnecessarily.

---

## Summary

| Task | Where to go |
|---|---|
| Enrol a participant | **Subjects** list → Generated record → **Enrol** |
| Edit a participant | **Subjects** list → Enrolled record → **Edit** |
| Switch a participant's arm | Subject record → **Switch Arm** |
| Drop a participant | Subject record → **Drop Subject** |
| Re-instate a participant | Subject record → **Re-Instate Subject** |
| View scheduled events | Subject record → **Subject Events** section |
| Print queued barcode labels | **Label Queue** in project navigation |

---

---

## Chapter 4 — Recording Events and Follow-up

---

## Overview

This chapter is for research nurses, study coordinators, and field staff responsible for monitoring participant follow-up schedules and recording the outcomes of study activities.

In BRIMS, follow-up activities are managed through **events**. An event represents a single study activity for a participant — such as a screening visit, a monthly follow-up, or a specimen collection point. Events are scheduled automatically when a participant is enrolled, and they are updated as activities are completed, missed, or rescheduled.

Keeping event records current is essential. Overdue or unrecorded events affect follow-up reports and, where events are linked to specimen collection, can create gaps in the specimen record.

---

## 4.1 Understanding Events

### 4.1.1 Event definitions and subject events

BRIMS uses two layers of event records:

- **Event definitions** define events set up by the project manager within each study arm (see Chapter 2 — Setting Up a Project). The definitions include information about the timing windows of scheduled events and number of labels required.
- **Subject events** are the individual events assigned to a specific participant when they are enrolled or when their arm changes. Each subject event is selected from the event definitions for the relevant arm and dated based on the participant's enrolment date.

You will work almost exclusively with subject events in day-to-day follow-up.

### 4.1.2 How event dates are calculated

When a participant is enrolled, BRIMS uses their enrolment date as the baseline and calculates a scheduled date for each event using the arm's offset settings.

For example, if an event has an offset of 14 days, it will be scheduled 14 days from the enrolment date. The ante and post windows around that date define the range within which the event is considered on time.

> **Tip:** Understanding this date logic helps you interpret the event list correctly. An event showing in red in the Subject Events table means the scheduled date has passed and the event is still unrecorded. This is not a system error — it is an operational signal that the activity needs attention.

---

## 4.2 Viewing a Participant's Event Schedule

To view the events scheduled for a specific participant:

1. Navigate to **Subjects** and open the participant's record.
2. Scroll to the **Subject Events** section.

![A subject record showing the Subject Events section with event names, scheduled dates, windows, status, and log date columns.]()

The Subject Events table shows:

| Column | What it shows |
|---|---|
| **Arm** | The arm the event belongs to |
| **Event Name** | The name of the scheduled activity |
| **Status** | The current status of this event instance |
| **Event Date** | The scheduled date for this event |
| **Min Date** | The earliest date the event can be recorded as on time |
| **Max Date** | The latest date the event can be recorded as on time |
| **Log Date** | The date the event was actually recorded, if logged |
| **Repeatable** | Whether additional iterations can be added |
| **Iteration** | The iteration number (for repeatable events) |
| **Event Order** | The sequence position of this event within the arm |
| **Label Status** | The current label printing status for this event. Can be updated directly by users with the appropriate permission (see section 4.6). |

Events where the scheduled date has passed and the status is still unlogged are highlighted in red. These require prompt attention.

---

## 4.3 Event Statuses

Each subject event has a status that reflects where it is in its lifecycle.

| Status | Meaning |
|---|---|
| **Pending** | The event is scheduled for the future and is not yet ready to be logged. |
| **Primed** | The event is approaching its scheduled date and is ready to be actioned. |
| **Scheduled** | The event is due — the **Log Event** action will be available. |
| **Logged** | The event was recorded on time, within the acceptable date window. |
| **Logged Late** | The event was recorded after the acceptable post-window date. |
| **Missed** | The event was not completed and was recorded as missed. |
| **Cancelled** | The event was cancelled (typically due to an arm switch). |

> **Tip:** The **Log Event** action only appears on events with a **Scheduled** status. If an event is still **Pending** or **Primed**, it is not yet ready to log. If you need to record an outcome for an event that has passed its window, update the status to **Missed** directly in the status column if you have permission, or contact your data manager.

---

## 4.4 Recording an Event Outcome

When a participant completes a scheduled study activity, record the outcome in BRIMS as soon as possible.

To log an event:

1. Open the participant's record and navigate to the **Subject Events** section.
2. Find the event with status **Scheduled**.
3. Select the **Log Event** action on that row.
4. Enter the **Log Date** — the date the activity actually took place.
5. Select the **Event Status**: either **Logged** (completed) or **Missed** (not completed).
6. Confirm and save.

![The Log Event action dialog showing the Log Date field and Event Status selection.]()

BRIMS will update the event status to **Logged** or **Missed** accordingly. If the log date falls outside the acceptable window, the status will be set to **Logged Late** automatically.

> **Tip — Recording missed events promptly:** If a participant did not attend a scheduled visit, record the event as **Missed** as soon as this is confirmed rather than leaving it as **Scheduled**. This keeps operational reports accurate and ensures that follow-up planning reflects the true state of each participant's schedule.

> **Tip — Log date accuracy:** Enter the date the activity actually occurred, not the date you are entering the record. If there is a delay between the visit and data entry, the log date should still reflect the real activity date. BRIMS will compare the log date against the scheduling window to determine whether the event was on time or late.

---

## 4.5 Repeatable Events

Some events are configured as **repeatable**, meaning additional iterations can be scheduled after the first one has been completed.

A repeatable event will show a **New Iteration** action when:

- The event is repeatable (confirmed by the tick in the Repeatable column)
- The current iteration is the most recent one for that event
- The participant is still enrolled

To add a new iteration:

1. Find the repeatable event in the Subject Events list.
2. Select the **New Iteration** action.
3. Enter the date for the new iteration.
4. Confirm.

The new iteration will appear in the Subject Events list and can be logged in the same way as any other event.

> **Tip:** Repeatable events are typically used for ongoing follow-up visits that recur at defined intervals — for example, monthly clinical reviews or quarterly specimen collections. If you are unsure whether a new iteration should be added, check your study protocol or ask your project manager.

---

## 4.6 Updating an Event Status Directly

For users with the appropriate permissions, event status can be updated directly in the status column of the Subject Events table — for example, to mark an event as **Missed** when the **Log Event** button is not available.

Select the status dropdown in the row you want to update and choose the new status.

The **Label Status** column can be updated in the same way. Label status tracks whether a label for the event has been queued, printed, or generated. If a label status needs correcting outside the normal Label Queue workflow, an authorised user can update it directly here.

> **Caution:** Direct status updates bypass the log date confirmation step. Only use this approach when instructed by your data manager, and always record the reason for the change in your study records. Changes made directly to the status column are still recorded in the system audit trail.

---

## 4.7 Follow-up Review

Regular follow-up review helps identify participants who need attention before they fall through the gaps.

Use the **Subject Events** section on individual subject records to check:

- Events that are overdue (highlighted in red)
- Events that are approaching their post-window date
- Participants with a high number of missed events

For a broader view across all participants, use filters and search tools in the **Subjects** list to identify active participants and review their events. See Chapter 9 — Searching, Reviewing, Exporting, and Importing Data for filter and export options.

---

## Summary

| Task | Where to go |
|---|---|
| View a participant's event schedule | Subject record → **Subject Events** section |
| Record a visit outcome | Subject Events → **Log Event** action |
| Record a missed event | Subject Events → **Log Event** → select Missed |
| Add another iteration of a repeatable event | Subject Events → **New Iteration** action |
| Update a status directly | Subject Events → status dropdown (permission required) |
| Review overdue events | Subject record → events highlighted in red |

---

---

## Chapter 5 — Logging Specimens

---

## Overview

This chapter is for laboratory staff and research staff responsible for logging biological specimens into BRIMS.

Specimens are the central data objects for laboratory workflows in BRIMS. Every specimen needs to be logged — assigned a barcode, linked to a participant and event, and assigned a type — before it can be stored, shipped, or associated with a study. This chapter covers the two logging workflows (primary and derivative), the status system, and how to manage specimens from the list view.

---

## 5.1 Before You Begin

Before logging specimens, confirm that:

- The participant record exists and is enrolled
- The relevant subject event has been scheduled
- Specimen types and labware have been configured for the project (see Chapter 2 — Setting Up a Project)
- You have the physical barcodes ready for scanning

If the participant is not yet enrolled or the event has not been created, logging cannot proceed correctly. Return to Chapter 3 — Enrolling and Managing Participants or Chapter 4 — Recording Events and Follow-up if those steps are incomplete.

---

## 5.2 Primary Specimen Logging

Primary specimens are samples collected directly from a participant. They are logged through a dedicated two-stage workflow accessed from **Log Primary Specimens (2-Stage)** in the project navigation.

### 5.2.1 Stage 1 — Scan the Project Subject Event Barcode

The first stage identifies the participant and event that the specimens belong to.

1. Navigate to **Log Primary Specimens (2-Stage)**.
2. In the **Project Subject Event Barcode** field, scan or enter the PSE barcode from the participant's label.
3. Press Enter to confirm.

BRIMS will validate the barcode and load the participant and event details. Confirm that the participant name and event shown on screen match the physical sample before continuing.

> **Tip:** The PSE barcode (Project Subject Event barcode) encodes the participant and event in a single scannable code. If the barcode is not recognised at this stage, check that the barcode matches the correct project and that the subject event has been correctly scheduled. A barcode from a different project will not be accepted.

### 5.2.2 Stage 2 — Scan Specimen Barcodes and Enter Volumes

Once Stage 1 is confirmed, the form expands to show all primary specimen types configured for this project.

For each specimen type:

1. Scan or enter the barcode for each aliquot.
2. Enter the volume if required.
3. Confirm all fields before submitting.

![The primary specimen logging form at Stage 2, showing specimen type groups, barcode fields per aliquot, and volume entry fields.]()

> **Tip:** Specimen types are grouped on screen according to the project's specimen group settings. Work through each group systematically, scanning barcodes onto the physical label as you log each one. If a barcode does not match the format defined by the labware settings, BRIMS will reject it with a validation error — check the physical label and re-scan before assuming there is a system problem.

Submit the form when all specimen types have been completed. BRIMS will create a specimen record for each logged aliquot, linked to the participant and event confirmed in Stage 1. Newly logged specimens are given a status of **Logged**.

---

## 5.3 Derivative Specimen Logging

Derivative specimens are samples that have been processed or derived from a primary specimen — for example, plasma separated from a whole blood specimen. They are logged through **Log Derivative Specimens** in the project navigation.

Derivative logging is also a multi-stage process:

### 5.3.1 Stage 1 — Select the parent specimen

You have two routes to identify the parent specimen:

- **Scan the parent barcode directly:** Enter the barcode of the primary specimen this derivative was processed from.
- **Scan a PSE barcode and select from a list:** Scan the Project Subject Event barcode and then choose the correct parent specimen from the list of eligible specimens linked to that event.

### 5.3.2 Stage 2 — Scan derivative barcodes and enter volumes

Once the parent specimen is confirmed, the form shows the derivative specimen types configured for this project. For each derivative type:

1. Scan or enter the barcode for each aliquot.
2. Enter volume information if required.
3. Confirm all fields and submit.

![The derivative specimen logging form showing the parent specimen confirmation and the derivative type barcode and volume fields.]()

BRIMS links each derivative specimen to the parent specimen, to the participant, and to the subject event. The parent-child relationship between specimens is preserved in the record and visible when reviewing specimen history.

---

## 5.4 Specimen Statuses

Every specimen record carries a status that reflects where it is in the research workflow. Understanding these statuses is essential for managing specimens correctly across storage, shipment, and study activities.

| Status | Meaning |
|---|---|
| **Unassigned** | The specimen record exists but has not yet been assigned to a workflow context. |
| **Registered** | The specimen has been registered in the system ahead of logging. |
| **Logged** | The specimen has been logged and is ready for storage or the next handling step. |
| **In Storage** | The specimen has been allocated to a storage location. |
| **Pre Transfer** | The specimen is being prepared for transfer or shipment. |
| **Transferred** | The specimen has been transferred to another site or location. |
| **Logged Out** | The specimen has been removed from storage for use or review. |
| **Received** | The specimen has been received at its destination following a transfer. |
| **Reassigned** | The specimen has been reassigned within the workflow. |
| **Used** | The specimen has been consumed in an assay or other research activity. |
| **Lost** | The specimen cannot be located and is recorded as lost. |

> **Tip:** Follow your project's standard operating procedures when deciding which status transition is appropriate. For most laboratory workflows, specimens move through Logged → In Storage → Logged Out → Used. The other statuses (Transferred, Received, Lost, etc.) are triggered by specific handling events and should not be applied without a corresponding real-world action.

---

## 5.5 The Specimens List

The **Specimens** list (accessible from the project navigation) shows all specimens logged in the current project, with columns for barcode, event, specimen type, site, origin site, status, aliquot number, logged-by user, and log date.

Use the search fields at the top of each column to find specimens by barcode, type, site, or status.

![The Specimens list showing search fields per column and the bulk action menu.]()

### 5.5.1 Updating specimen status by barcode entry

The **Update Specimen Status** button at the top of the Specimens list provides a way to update the status of multiple specimens by entering their barcodes, without needing to locate and select them in the table. This is useful when working from a physical list of barcodes — for example, after retrieving a batch of specimens from storage.

Click **Update Specimen Status** to expand the action group and choose one of three options:

| Action | What it does |
|---|---|
| **Log Specimens as Used** | Marks the entered specimens as **Used**. Enter barcodes comma-separated or one per line. |
| **Log Specimens Out of Storage** | Marks the entered specimens as **Logged Out**. Enter barcodes comma-separated or one per line. |
| **Log Specimens Returned to Storage** | Marks the entered specimens back as **In Storage**. Includes a **Increment thaw count** toggle — enable this if the specimens were thawed during removal. |

> **Tip:** Any barcodes that cannot be found or are in an incompatible status will be reported in an error notification. Valid barcodes in the same submission are still processed.

### 5.5.2 Bulk actions

Select specimens using the checkboxes and use the bulk action menu to apply the following actions to a group of selected records:

| Action | What it does |
|---|---|
| **Log as Used** | Sets the status of selected specimens to **Used**. Requires confirmation. |
| **Log Out** | Sets the status of selected specimens to **Logged Out**. Requires confirmation. |
| **Log Return** | Sets the status back to **In Storage** (returns specimens to storage). Optionally increments the thaw count. Requires confirmation. |
| **Export** | Exports the selected records as a CSV file. |
| **Delete** | Deletes the selected records. Use with caution — this action is not easily reversible. |

> **Caution — bulk status actions:** These actions update specimen status across multiple records at once. Verify that all selected specimens are genuinely ready for the action before confirming. An accidental bulk status change on the wrong specimens can be difficult to correct, particularly if the specimens are already linked to storage or study records.

> **Tip — Log Return with thaw count:** When using **Log Return** to record that specimens are going back into cold storage after being briefly removed, you will be asked whether the thaw count should be incremented. Increment the thaw count if the specimen was genuinely thawed during the removal period. This preserves an accurate thermal history for the specimen, which matters for downstream assay validity.

### 5.5.3 Viewing a specimen record

Click on a specimen row (or use the **View** action) to open its full detail page. The view page displays all specimen fields including origin site, thaw count, and storage information, and also shows an **Audit Log** table at the bottom of the record.

The Audit Log records every status change made to the specimen:

| Column | What it shows |
|---|---|
| **Previous Status** | The status before the change |
| **New Status** | The status after the change |
| **Changed By** | The user who made the change |
| **Changed At** | The date and time of the change |

This provides a complete chain-of-custody history for the specimen. Use the Audit Log to investigate unexpected status values or to provide traceability records when required.

---

## 5.6 Editing a Specimen Record

Individual specimen records can be edited from the Specimens list using the **Edit** action on a row.

The edit form allows updates to:

- Specimen type, site, and origin site
- Status
- Aliquot number, volume, and volume unit
- Thaw count
- Logged-by user and log date
- Parent specimen

> **Caution:** Editing specimen status directly should be done carefully and only when there is a clear operational reason. Where possible, use the logging workflows and bulk actions described above rather than manually editing the status field, as these workflows maintain the correct audit trail and apply the associated business rules automatically.

---

## 5.7 Linking Specimens to Events and Participants

Specimens are linked to a participant and subject event at the point of logging. This link is the foundation of specimen traceability — it connects each physical sample to the research record it belongs to.

If a specimen was logged without the correct event link, or if the link needs to be reviewed, open the specimen record and check the **Subject Event** field.

Derivative specimens carry an additional link to their **parent specimen**, which connects the processed sample back to the original collection.

---

## Summary

| Task | Where to go |
|---|---|
| Log a primary specimen | **Log Primary Specimens (2-Stage)** in the project navigation |
| Log a derivative specimen | **Log Derivative Specimens** in the project navigation |
| Review all project specimens | **Specimens** list |
| Update specimen status by barcode | **Specimens** list → **Update Specimen Status** button |
| Log selected specimens as used | **Specimens** list → select rows → **Log as Used** |
| Log selected specimens out of storage | **Specimens** list → select rows → **Log Out** |
| Return specimens to storage | **Specimens** list → select rows → **Log Return** |
| View specimen detail and audit log | **Specimens** list → row → **View** |
| Edit a specimen record | **Specimens** list → row → **Edit** |

---

---

## Chapter 6 — Managing Specimen Storage

---

## Overview

This chapter is for laboratory staff and biobank staff responsible for placing specimens into storage and maintaining accurate storage records.

BRIMS manages specimen storage through an **allocation** model. Rather than placing specimens one by one into individual positions, you select the specimen types to be stored and BRIMS automatically assigns available locations based on the configured storage structure. This approach reduces manual placement errors and keeps the storage record consistent with the physical arrangement.

All storage work is done from **Specimen Storage** in the project navigation.

---

## 6.1 Storage Concepts

Before working with storage in BRIMS, it is helpful to understand how the storage structure is organised.

### 6.1.1 Supported storage types

BRIMS supports three storage contexts, defined by the physical environment in which specimens are kept:

| Storage Type | Description |
|---|---|
| **Minus-80** | Ultra-low temperature freezer storage (−80 °C) |
| **Liquid Nitrogen** | Cryogenic vapour or liquid nitrogen storage |
| **Biorepository** | General controlled-temperature biorepository storage |

The storage type is defined at the unit definition level by an administrator. You do not need to select storage type during allocation — BRIMS routes specimens to the correct storage context based on how their specimen type has been configured.

### 6.1.2 Storage hierarchy

Storage locations in BRIMS follow a hierarchy:

- **Unit definitions** describe the type and capacity of a storage unit (e.g. a 9×9 box, a rack with 100 positions)
- **Physical units** are the actual freezers, racks, or boxes that exist in the laboratory
- **Virtual units** represent the logical position assignments that link specimen types to available spaces
- **Locations** are individual positions within a virtual unit where a single specimen is placed

Administrators configure unit definitions and physical units (see Chapter 2 — Setting Up a Project and the Administration Guide). Day-to-day laboratory work involves allocating specimens into the spaces that have been configured.

> **Tip:** If the allocation step reports that there is insufficient storage for a specimen type, this means the virtual units configured for that type have no free locations remaining. Contact your project administrator or laboratory manager to arrange additional storage configuration before attempting a new allocation.

---

## 6.2 Allocating Specimens to Storage

The allocation workflow places all eligible **Logged** specimens of the selected type into available storage locations in a single operation.

### 6.2.1 Step-by-step

1. Navigate to **Specimen Storage** and select **Allocate Specimen Storage**.
2. The form shows all specimen types that have Logged specimens at your current site, along with the count of specimens waiting to be stored.
3. Optionally enable **Allow allocation to previously used locations** if you want BRIMS to reuse locations that previously held a specimen.
4. Select the specimen types you want to allocate by ticking their checkboxes.
5. Select **Allocate** to run the allocation.

![The Allocate Specimen Storage page showing the reuse-locations toggle, the specimen type checklist with specimen counts, and available storage capacity indicators.]()

BRIMS will:

- Assign each eligible specimen to a free location
- Update the specimen status from **Logged** to **In Storage**
- Create a storage allocation record linked to the current user and timestamp

If a specimen type shows a warning about insufficient storage, that type will be disabled in the list and cannot be selected until additional storage capacity has been configured.

> **Tip — Reusing locations:** Enabling the reuse-locations option allows BRIMS to allocate specimens to positions that previously held a different specimen. This is appropriate in some protocols (e.g. where boxes are reused across batches) but may not be appropriate in others (e.g. where audit traceability requires location exclusivity). Discuss this setting with your laboratory manager before enabling it.

> **Tip — Allocation is site-specific:** The allocation page only shows specimens from your current project site. If you are managing specimens from multiple sites, each site's allocations must be run separately by a user assigned to that site.

---

## 6.3 Reviewing Storage Allocations

The **Specimen Storage** list shows all previous allocation events for the current project.

Each row represents a single allocation run and shows:

- The date and time of the allocation
- The user who ran the allocation
- The storage destination
- The number of specimens allocated in that run

![The Specimen Storage list showing a history of allocation events with date, user, destination, and specimen count columns.]()

### 6.3.1 Printing a storage allocation report

Each allocation record has a **Print** action that opens a storage allocation report in a new browser tab.

The report shows the specific location assigned to each specimen in that allocation, providing a physical reference for laboratory staff to confirm and verify placement.

> **Tip:** Print the storage allocation report immediately after each allocation run and compare it against the physical arrangement in the freezer or storage unit. Resolving any discrepancy at this stage is far easier than trying to reconcile location records later.

---

## 6.4 Retrieving Specimens from Storage

When specimens are removed from storage — for use in an assay, for shipment, or for any other reason — their status should be updated promptly to reflect the real situation.

BRIMS does not have a dedicated specimen-by-specimen retrieval screen in the Specimen Storage area. Instead, use the bulk actions in the **Specimens** list to update specimen status:

- Use **Log Out** to record that specimens have been removed from storage (sets status to **Logged Out**)
- Use **Log as Used** to record that specimens have been consumed in an assay (sets status to **Used**)
- Use **Log Return** to record that specimens have been returned to storage after a brief removal (sets status back to **In Storage**, with the option to increment the thaw count)

Refer to Chapter 5 — Logging Specimens for instructions on using these bulk actions in the Specimens list.

> **Caution:** Always update the status when specimens are moved. If specimens are removed from storage without being logged out, the storage records will not reflect the real situation. This makes it more difficult to locate specimens later and creates inaccuracies in storage traceability reports.

---

## 6.5 Storage Traceability

BRIMS maintains a storage history for each specimen through the storage allocation records and specimen status changes. This means you can trace:

- When a specimen was placed in storage
- Which allocation run it belonged to
- Which physical location it was assigned to
- When it was removed and by whom

Review the storage allocation report and the individual specimen record when traceability is needed — for example, to resolve a query about a missing specimen or to provide a chain of custody record.

---

## Summary

| Task | Where to go |
|---|---|
| Allocate logged specimens to storage | **Specimen Storage** → **Allocate Specimen Storage** |
| Review past allocation runs | **Specimen Storage** list |
| Print a location report for an allocation | **Specimen Storage** → row → **Print** |
| Log specimens out of storage | **Specimens** list → select rows → **Log Out** |
| Log specimens back into storage | **Specimens** list → select rows → **Log Return** |
| Log specimens as used | **Specimens** list → select rows → **Log as Used** |

---

---

## Chapter 7 — Preparing and Receiving Shipments

---

## Overview

This chapter is for laboratory staff and research staff at both the sending and receiving end of specimen transfers.

BRIMS uses a **manifest** to manage inter-site specimen transfers. A manifest groups a set of specimens destined for a specific site and tracks the shipment through three states — **Open**, **Shipped**, and **Received**. This ensures that the sending site has a complete record of what was dispatched, and the receiving site can formally acknowledge receipt.

All manifest work is done from **Manifests** in the project navigation.

---

## 7.1 Manifest Statuses

| Status | Meaning |
|---|---|
| **Open** | The manifest has been created and specimens are being added. It has not yet been shipped. |
| **Shipped** | The sending site has confirmed dispatch. The manifest cannot be edited. |
| **Received** | The receiving site has confirmed receipt. The shipment workflow is complete. |

---

## 7.2 Creating a Manifest

A manifest must be created by a user at the **sending site** before specimens can be added to it.

1. Navigate to **Manifests** and select **New Manifest** (or the equivalent create button).
2. Select the **Destination Site** — the site to which the specimens will be sent. Your own site is excluded from this list automatically.
3. Select the **Specimen Types** to include. Only specimen types configured for the current project are available. This selection filters which specimens can be added in the next step.

> **Tip:** The specimen type selection is the filter that controls which specimens will be eligible to add to the manifest. If you include the wrong type at this stage, you will need to remove all attached specimens before you can edit the selection.

4. Save the manifest.

The manifest is now in the **Open** state and is ready to have specimens attached.

---

## 7.3 Adding Specimens to a Manifest

With the manifest open, navigate to the **Specimens** section at the bottom of the manifest detail page.

1. Select **Select Specimens to Add**.
2. Find and select the specimens you want to include. The search only shows specimens that:
   - Are of a type included in the manifest
   - Have a status of **Logged**, **In Storage**, or **Received**
   - Belong to your current site

3. Confirm the selection.

The selected specimens are now listed in the manifest. Each specimen row shows the barcode, aliquot, subject ID, specimen type, arm, and event. A **Prior Status** column records what the specimen's status was when it was added to the manifest — this is preserved for reference after the transfer.

> **Tip:** Review the list of attached specimens carefully before shipping. Once the manifest is shipped, no specimens can be added or removed.

![The manifest specimen list showing added specimens with barcodes, subject IDs, and a prior-status column.]()

---

## 7.4 Shipping the Manifest

When all specimens have been added and the manifest is ready to dispatch physically, ship the manifest in BRIMS to record the event.

1. Open the manifest record.
2. Select **Ship the Manifest**.
3. A confirmation dialog appears, asking whether to **Automatically Mark as Received upon shipping**.

   - Select **No** in normal circumstances. The receiving site will mark the manifest as received themselves via BRIMS.
   - Select **Yes** only if the receiving site uses a different system or is unable to access BRIMS to confirm receipt themselves. This option marks the manifest as Received immediately without a separate receiving step.

4. Confirm the shipping action.

The manifest status changes to **Shipped**. All specimens attached to the manifest have their status updated to **Pre Transfer** (or **Transferred**, depending on protocol). The manifest can no longer be edited.

> **Caution:** The **Ship the Manifest** action is only available when the manifest is **Open** and at least one specimen has been added. Confirm that your physical shipment is complete and the specimens are packed before triggering this action in BRIMS.

---

## 7.5 Receiving a Manifest

When a shipment arrives at the destination site, the receiving team should confirm receipt in BRIMS.

> **Important:** The **Receive the Manifest** action is only available to users whose project site assignment matches the manifest's **destination site**. If you cannot see the receive button, verify that you are logged in with the correct site assignment.

1. Navigate to **Manifests** and open the manifest with status **Shipped**.
2. Select **Receive the Manifest**.
3. Confirm the action.

The manifest status changes to **Received**. Individual specimens are updated to **Received** status and the received timestamp is recorded.

![The manifest detail page showing the Receive button on a Shipped manifest.]()

---

## 7.6 Reviewing and Importing Specimens on a Manifest

The manifest specimen list in BRIMS provides a searchable record of all specimens included in each shipment, with columns for barcode, subject, type, arm, event, prior status, received flag, and received timestamp.

For teams that process manifest receipt using barcode scanning equipment, BRIMS supports an **import** action within the specimens relation manager on the manifest. This allows mass import of received specimen records from a file rather than manual confirmation.

> **Tip:** Consult your project manager about the correct receiving workflow. If physical receipt checking is done offline (e.g. via barcode scanner software), the import route avoids re-entering data manually into BRIMS.

---

## 7.7 Tracing a Shipment

Every manifest provides a traceable chain of custody:

- **Created by** and creation timestamp
- **Source site** (derived from the creator's site assignment)
- **Destination site**
- **Shipped date** (set when the manifest is shipped)
- **Received by** user and **received date** (set when the manifest is received)

The manifest record can be accessed from the Manifests list at any time. Use the status filter to quickly locate Open, Shipped, or Received manifests.

---

## 7.8 Exporting a Manifest

Once a manifest has been **Shipped** or **Received**, an **Export** action becomes available on the manifest detail page. This downloads a file containing the manifest's specimen records, which is useful for reconciliation at the receiving site or for archiving shipment records.

The Export action is not available on manifests with an **Open** status.

---

## Summary

| Task | Where to go |
|---|---|
| Create a new manifest | **Manifests** → **New Manifest** |
| Add specimens to a manifest | **Manifests** → open the record → **Specimens** section → **Select Specimens to Add** |
| Ship (dispatch) a manifest | **Manifests** → open the record → **Ship the Manifest** |
| Receive an incoming shipment | **Manifests** → open the Shipped record → **Receive the Manifest** |
| Export a shipped or received manifest | **Manifests** → open the record → **Export** |
| Review all manifests for the project | **Manifests** list |

---

---

## Chapter 8 — Studies, Assay Data, and Publications

---

## Overview

This chapter is for research coordinators, laboratory staff, and data managers who need to link specimens to research studies and record the results or metadata associated with assays.

In BRIMS, a **study** is a project-level record that captures the context for a set of research analyses. Each study holds two types of related records:

- **Specimens** — the physical samples allocated to the study
- **Assays** — records of measurements, analyses, or test runs performed using those specimens

Studies are accessed from **Studies** in the project navigation.

---

## 8.1 Creating a Study

1. Navigate to **Studies** and select **New Study**.
2. Complete the study form:

| Field | Required | Notes |
|---|---|---|
| **Title** | Yes | A descriptive name for the study |
| **Identifier** | Yes | A unique code for this study; must be unique across all studies in BRIMS, not just within the current project |
| **Description** | No | Free text describing the study's purpose |
| **Submission Date** | No | Date the study was or is expected to be submitted |
| **Public Release Date** | No | Date the study data is expected to become publicly available |
| **Locked** | No | When enabled, the study is read-only: assays cannot be added or edited and specimens cannot be attached or removed |

3. Save the study.

> **Caution — Locked studies:** Enabling the **Locked** toggle makes the entire study and its assays read-only. Only lock a study when all data entry is complete, as there is no partial lock; locking applies to specimens and assays simultaneously. Discuss with your project manager before locking.

> **Tip — Identifier uniqueness:** The identifier field must be unique across all studies in the BRIMS system, not just within your project. Use a structured naming convention that includes your project code to avoid conflicts with other projects.

---

## 8.2 Adding Specimens to a Study

Specimens are linked to a study through the **Specimens** section at the bottom of the study record.

1. Open the study.
2. Navigate to the **Specimens** section.
3. Select **Attach Specimen** (or the equivalent add action).
4. Search for the specimen by barcode or other identifier and select it.

> **Tip:** Only specimens that are accessible within the current project and site context will be available. If the specimen you need is not visible, check that it has been logged and that it belongs to the correct project.

> **Caution:** If the study is locked, specimens cannot be added or removed.

---

## 8.3 Recording Assays

Assays capture the analytical measurements or results associated with the study. Each assay record represents a distinct analysis type or technology platform.

### 8.3.1 Adding an assay

1. With the study open, navigate to the **Assays** section.
2. Select **New Assay**.
3. Complete the assay form:

| Field | Required | Notes |
|---|---|---|
| **Name** | Yes | A descriptive name for this assay run |
| **Assay Definition** | Yes | Select from a pre-configured list of assay definitions. The definition determines which additional fields will appear below. |
| **Technology Platform** | Yes | The instrument, platform, or technology used |
| **URI** | No | A link to an external resource or data file |
| **Location** | No | Where the assay data is physically stored or filed |

After selecting an **Assay Definition**, additional fields may appear in the form. These are defined by your administrator as part of the assay definition configuration and will vary by assay type. They may include text fields, date pickers, single-choice (radio) questions, multi-select checkboxes, or dropdown selectors.

4. Complete all required additional fields and save.

![The assay form showing the core fields and a set of additional fields defined by the selected assay definition.]()

### 8.3.2 Assay definitions

Assay definitions are system-level configurations that specify the structure of an assay record — the types of metadata fields required. They are managed by an administrator at the system level and are available to all projects.

If the assay definition you need does not exist, contact your system administrator.

---

## 8.4 Downloading and Deleting Assay Files

Assay records can have files attached (uploaded to the configured file store). From within the Assays section of a study:

- Use the **Download** action on an assay to download an associated data file to your local machine.
- Use the **Delete** file action to remove a file attachment from an assay record.

> **Tip:** Assay files are stored in a connected file store (typically S3-compatible cloud storage). Download files before any long absence or if you need to work offline, as access may depend on external connectivity.

---

## 8.5 Publication Status

Publication status is tracked at the study level and indicates the current state of the research in the publication pipeline:

| Status | Meaning |
|---|---|
| **Draft** | The study is in preparation; not submitted for publication |
| **Submitted** | The study has been submitted to a journal or repository |
| **Published** | The study has been published |

Publication status is a metadata field. It does not affect the ability to add or edit specimens and assays (that is controlled by the **Locked** toggle).

---

## 8.6 Reviewing Your Studies

The **Studies** list shows all studies in the current project, with status badges for the publication stage.

Click on a study to open its full record, including the linked specimens and assays.

---

## 8.7 Publications

Publications are project-level records that capture bibliographic information about research outputs from the project — such as journal articles, preprints, or conference proceedings that report findings from the study.

Publications are accessed from **Publications** in the project navigation.

### 8.7.1 Creating a publication record

1. Navigate to **Publications** and select **New Publication**.
2. Complete the publication form:

| Field | Required | Notes |
|---|---|---|
| **Title** | Yes | The full publication title. Supports markdown formatting. |
| **Authors** | Yes | Enter one author name per line in the Authors list. |
| **Publication Status** | Yes | Select the current status: Draft, Submitted, or Published. |
| **PubMed ID** | Required if Published | The PubMed identifier (PMID) for the article (7–8 digits). |
| **DOI** | Required if Published | The digital object identifier for the article. |
| **Publication Date** | Required if Published | The date the article was published. |

3. Save the record.

> **Tip:** Create a publication record in **Draft** status as soon as a manuscript is in preparation. Update the status and add the PubMed ID and DOI once the article is accepted and published. This keeps the project's output record current without requiring fields that are not yet available.

### 8.7.2 Publication statuses

| Status | Meaning |
|---|---|
| **Draft** | The manuscript is in preparation and has not yet been submitted for publication |
| **Submitted** | The manuscript has been submitted to a journal or repository |
| **Published** | The article has been published |

Publication status does not restrict editing of the record. Unlike the **Locked** toggle on a study, changing the publication status does not prevent further updates to the publication record.

### 8.7.3 Reviewing publications

The **Publications** list shows all publications for the current project, with columns for title, authors, PubMed ID, DOI, publication date, and status.

---

## Summary

| Task | Where to go |
|---|---|
| Create a new study | **Studies** → **New Study** |
| Add specimens to a study | **Studies** → open the record → **Specimens** section |
| Add an assay to a study | **Studies** → open the record → **Assays** section → **New Assay** |
| Download an assay file | **Studies** → open the record → **Assays** section → **Download** |
| Lock a study | **Studies** → open the record → **Edit** → enable **Locked** |
| Review all project studies | **Studies** list |
| Create a publication record | **Publications** → **New Publication** |
| Review project publications | **Publications** list |

---

---

## Chapter 9 — Searching, Reviewing, Exporting, and Importing Data

---

## Overview

This chapter is for research coordinators, data managers, and project managers who need to find records, review operational progress, export data for offline use, or bulk-import records from an external system.

BRIMS does not have a single global search across all record types. Instead, each list view offers column-level search fields and, where applicable, filters to narrow results by category. Understanding where to search for different types of records will allow you to find what you need quickly.

---

## 9.1 Searching Within Lists

Most list pages in BRIMS include per-column search boxes above the table. Type into a search field to filter the list to matching records.

Where multi-column searching is supported, you can search across several fields simultaneously. Common examples:

| List | Searchable fields |
|---|---|
| **Participants (Subjects)** | Subject ID, first name, last name |
| **Specimens** | Barcode, event name, type, site, status |
| **Manifests** | Created date, user, source site, destination site, shipped date, received date |
| **Studies** | Title, identifier |

> **Tip:** On the Specimen list, you can search by barcode to retrieve an individual specimen record instantly. If you are investigating a specific tube, this is the fastest route to the relevant record and its linked event and participant.

---

## 9.2 Filtering Lists

Some lists support dropdown filter controls that narrow the list by status or category. Filters are available on:

- **Participants** — filter by status (Generated, Enrolled, Dropped), site, or manager
- **Manifests** — filter by status (Open, Shipped, Received)
- **Specimens** — the status badge column is sortable, which can serve as an informal grouping mechanism

Apply a filter by selecting the relevant option. Clear filters by returning the filter to its default position.

> **Tip — Filter before you act:** When preparing to run a bulk action on specimens (such as Log as Used or Log Out), apply filters first to narrow the list to the relevant records before selecting them. This reduces the risk of accidentally including the wrong specimens in a bulk operation.

---

## 9.3 Operational Review Patterns

Use the filtering and search capabilities to support day-to-day operational review. The following patterns are particularly useful:

### 9.3.1 Review outstanding follow-up events

Navigate to a **Subject** record and view the **Subject Events** section. Events highlighted in red are overdue and awaiting logging. You can also scan the event list for Scheduled events that have passed their expected date.

See Chapter 4 — Recording Events and Follow-up for more detail.

### 9.3.2 Review specimens awaiting storage

Navigate to **Specimens** and filter or sort by **Status**. Specimens with a status of **Logged** have been collected but not yet allocated to storage. Use this view to identify batches ready for the next storage allocation run.

### 9.3.3 Review incoming shipments awaiting receipt

Navigate to **Manifests** and filter by status **Shipped**. These manifests have been dispatched and are waiting to be received at the destination site.

### 9.3.4 Review specimens allocated to a study

Open a **Study** record and navigate to the **Specimens** section. This lists all specimens linked to the study, allowing you to verify that the correct specimens are included.

---

## 9.4 Exporting Data

BRIMS provides CSV export functions for the main operational record types. Exports are generated asynchronously — BRIMS will notify you when the file is ready to download.

> **Important — data sensitivity:** Exports may include participant identifiers, contact details, and specimen data. Export only the minimum data needed for your task and share the files only with authorised recipients. Do not store exported files in unsecured locations.

### 9.4.1 Export entry points

| Export | How to access | What is included |
|---|---|---|
| **Export Subjects** | Open the **Project** record → **Export Subjects** button | Subject ID, site, enrolled-by user, name, address, enrolment date, arm, arm baseline date, status |
| **Export Subject Events** | Open the **Project** record → **Export Subject Events** button | Subject ID, event name, iteration, status, label status, event date, min date, max date, log date |
| **Export Specimens** (project-wide) | Open the **Project** record → **Export Specimens** button | Barcode, subject ID, event name, event iteration, specimen type, site, status, parent barcode, aliquot, volume, volume unit, thaw count, logged-by user, log date, used-by user, used date |
| **Export Specimens** (from Specimens list) | **Specimens** list → select rows → **Export** | Same columns as above for the selected records |
| **Export Specimens** (from a study) | **Studies** → open a study → **Specimens** section → **Export** | Barcode, specimen type, site, arm, event, event iteration, subject ID, log date |

> **Tip:** The project-level exports (**Export Subjects**, **Export Subject Events**, **Export Specimens**) are found in the Export actions group on the **Project** detail page (accessed by clicking on the project name in the project navigation or selecting the project from the main list). Use these when you need a complete project-level data extract.

### 9.4.2 Columns that are hidden by default

Some columns in the specimen export are hidden by default but can be included when you configure the export:

- Parent barcode
- Logged-by user
- Log date
- Used-by user
- Used date

When prompted to configure columns before confirming an export, review the column list and enable any additional columns needed for your task.

---

### 9.4.3 Data Validation Before Sharing

Before distributing exported data, confirm that the output matches the question you are trying to answer:

- Check that the correct project, date range, status filters, and record types were used when generating the export.
- Verify row counts and identifiers before sharing — ensure the number of records matches your expectation.
- Where possible, compare the export result with the live system view before distributing it.
- Confirm that the export reflects the latest updates to the records included.
- Treat exported participant and specimen data with care. Only export the minimum information needed for the task, and share files only with people who are authorised to receive them. Do not store exported files in unsecured locations.

---

## 9.5 Reviewing Audit and History Information

BRIMS does not have a dedicated audit view at the record level. Record-level history is visible through:

- **Timestamps** on each record (created at, updated at, logged at, shipped date, received date, etc.)
- **Status fields** that reflect the current state of the workflow
- **Linked records** — for example, a specimen record links back to the subject event, and the subject event links back to the participant, providing a traceability chain through the system

When a discrepancy needs to be investigated:

1. Open the record in question and review all date and user fields.
2. Follow the linked records (specimen → event → participant) to check for inconsistencies.
3. Review related exports if the issue spans multiple records.
4. If the issue cannot be resolved through the record view, contact your project manager or administrator.

---

## 9.6 Storage Allocation Reports

Storage allocation reports are printed from the **Specimen Storage** list rather than the export system. Each allocation record has a **Print** action that opens a printable report listing the physical location assigned to each specimen.

Refer to Chapter 6 — Managing Specimen Storage for details.

---

## 9.7 Importing Data

BRIMS supports bulk import of subject, subject event, and specimen records from a CSV file. This is intended for migrating data from another system, not for day-to-day data entry. Imports are accessed from the project detail page — open the project via **Access**, which takes you to the **Configure Project Details** page, then select the **Data Import** button group.

> **Important — data preparation:** Before running any import, prepare your source file carefully and review the Import Value Mappings configuration for your project (see Chapter 2 — Setting Up a Project, section 2.7). The import process validates each row and will reject rows that contain unrecognised values or fail business rule checks. Failed rows do not prevent other rows in the same file from being processed — only the valid rows are committed.

> **Important — data sensitivity:** Import files may contain participant identifiers, specimen barcodes, and other sensitive research data. Handle import files according to your organisation's data governance procedures and delete them securely after use.

### 9.7.1 Import types

#### Import Subjects

Creates new subject records in the project. Each row must contain a Subject ID that matches the project's configured prefix and digit format.

> **Note:** This import creates subject records directly without triggering the enrolment workflow. Subjects imported with an Enrolled status will not automatically have events created. Use this import route only when migrating existing data where events and specimens are being imported separately in the same batch.

| Column | Required | Notes |
|---|---|---|
| **Subject ID** | Yes | Must match the project prefix and digit format |
| **Site** | Yes | Site name as it appears in BRIMS (or mapped via Import Value Mappings) |
| **User** | Yes | Username of the staff member to assign as Manager |
| **Enrolment Date** | Yes | Date in YYYY-MM-DD format |
| **Arm** | Yes | Arm name as it appears in BRIMS |
| **Status** | Yes | One of: Generated, Enrolled, Dropped |
| **First Name** | No | |
| **Last Name** | No | |
| **Address** | No | |
| **Arm Baseline Date** | No | Date in YYYY-MM-DD format |

#### Import Subject Events

Creates new subject event records for existing subjects. Use this import to bulk-load event history when migrating data.

| Column | Required | Notes |
|---|---|---|
| **Subject ID** | Yes | Must already exist in the project |
| **Event** | Yes | Event name as it appears in BRIMS |
| **Iteration** | Yes | Integer, minimum 1 |
| **Status** | Yes | A valid event status name (e.g. Logged, Missed, Pending) |
| **Label Status** | Yes | A valid label status name |
| **Event Date** | No | Date in YYYY-MM-DD format |
| **Min Date** | No | Must be on or before Event Date |
| **Max Date** | No | Must be on or after Event Date |
| **Log Date** | No | Must be on or after Event Date |

> **Note:** The combination of Subject ID, Event, and Iteration must be unique. Importing a row that matches an existing subject event will fail validation for that row.

#### Import Specimens

Creates new specimen records linked to existing subject events. Use this import when migrating specimen history from another system.

| Column | Required | Notes |
|---|---|---|
| **Barcode** | Yes | Must be unique within the project; max 20 characters |
| **Subject ID** | Yes | Must already exist in the project |
| **Event** | Yes | Event name as it appears in BRIMS |
| **Iteration** | Yes | Integer, minimum 1 |
| **Specimen Type** | Yes | Specimen type name as it appears in BRIMS |
| **Site** | Yes | Site name as it appears in BRIMS |
| **Status** | Yes | A valid specimen status name (e.g. Logged, InStorage, Used) |
| **Aliquot** | Yes | Integer |
| **Thaw Count** | Yes | Integer |
| **Logged By** | Yes | Username of the user who logged the specimen |
| **Logged At** | Yes | Date in YYYY-MM-DD format |
| **Parent Specimen** | No | Barcode of the parent specimen (for derivatives) |
| **Volume** | No | Numeric |
| **Volume Unit** | No | Max 5 characters (e.g. mL, µL) |
| **Used By** | No | Username; required if Used At is provided |
| **Used At** | No | Datetime; required if Used By is provided; must be on or after Logged At |

### 9.7.2 Running an import

1. Navigate to the project detail page (**Configure Project Details**).
2. Select **Data Import** and choose the import type.
3. Upload your prepared CSV file.
4. BRIMS processes the file asynchronously. You will receive a notification when the import is complete, showing the number of rows successfully imported and the number of rows that failed.
5. Review any failed rows. The notification describes the reason for each failure. Correct the source data and re-import the affected rows.

> **Tip:** Test with a small file (10–20 rows) before importing a full dataset. This confirms that your column mapping and value mappings are correct before committing a large import.

---

## Summary

| Task | Where to go |
|---|---|
| Find a participant | **Subjects** list → search by subject ID or name |
| Find a specimen by barcode | **Specimens** list → search by barcode |
| Find shipped manifests awaiting receipt | **Manifests** list → filter by status Shipped |
| Find specimens waiting to be stored | **Specimens** list → filter or sort by status (Logged) |
| Export participant data | **Project** detail page → **Data Export** → **Export Subjects** |
| Export event data | **Project** detail page → **Data Export** → **Export Subject Events** |
| Export specimen data (full project) | **Project** detail page → **Data Export** → **Export Specimens** |
| Export specimen data (selected records) | **Specimens** list → select rows → **Export** |
| Export study specimens | **Studies** → open the study → **Specimens** section → **Export** |
| Import subjects | **Project** detail page → **Data Import** → **Import Subjects** |
| Import subject events | **Project** detail page → **Data Import** → **Import Subject Events** |
| Import specimens | **Project** detail page → **Data Import** → **Import Specimens** |

---

---

## Chapter 10 — Troubleshooting Common Problems

---

## Overview

This chapter is for all BRIMS users. It covers the most commonly encountered problems, how to diagnose them, and when to escalate to an administrator.

Before troubleshooting, gather the relevant reference information: project name, subject ID, specimen barcode, manifest ID, or study identifier. Having these details at hand makes diagnosis faster and ensures you can provide accurate information if you need to escalate the issue.

---

## 10.1 Signing In and Access

### 10.1.1 I cannot sign in

- Confirm you are entering the correct email address and password.
- Check whether your account has been created — contact your project administrator if you are new to the system.
- If you have forgotten your password, use the password reset option on the sign-in page.

### 10.1.2 I can sign in but I cannot see a project

- Confirm that you have been added to the project with an appropriate role. A project must have a member record for you.
- Check with a project manager or administrator to confirm that your membership is set up and that your role is correct.
- If you have just been added, try signing out and back in to refresh your session.

### 10.1.3 I can see the project but I cannot access a specific page within it

- Some pages are restricted to specific project roles. Your current role may not include the permissions required.
- Contact a project manager to review and adjust your role if necessary.

---

## 10.2 Participants

### 10.2.1 I cannot find a participant

- Search by subject ID using the search field in the Subjects list.
- Check whether the participant's record has a status that might cause it to appear on a separate view (for example, subjects with Generated status have not yet been enrolled).
- Confirm you are in the correct project — participant records exist within a specific project.

### 10.2.2 A participant is missing from the list but I know they were enrolled

- Check whether they have been dropped. Use the status filter on the Subjects list and look for records with status Dropped.
- If the record does not appear at all, the participant may have been enrolled under a different subject ID or in a different project. Check with your study coordinator.

### 10.2.3 I cannot enrol a subject

- The subject must have a status of **Generated** to be enrolled. If the record shows **Enrolled** or **Dropped**, the action will not appear.
- If you cannot see the **Enrol** action at all, you may not have permission for this action. Contact your project manager.

### 10.2.4 The wrong arm is linked to a participant

- Review the current arm assignment on the participant record.
- If the arm needs to be changed, see Chapter 3 — Enrolling and Managing Participants, section 3.6 for the arm switch process. Note that switching arm will cancel any pending events on the old arm.

---

## 10.3 Events

### 10.3.1 I cannot see the Log Event button

- The **Log Event** action is only shown on events that have a status of **Scheduled**. If the event is still **Pending** or **Primed**, it cannot be logged yet.
- If you believe the event should be Scheduled, check the event date and confirm the event has been correctly set up in the project's arm event definitions.

### 10.3.2 An event date appears wrong

- Event dates are calculated from the arm baseline date (the participant's enrolment date). If the event date looks wrong, check the enrolment date on the participant record.
- If the event definition itself has incorrect day offsets, a project manager will need to edit the arm event definitions.

### 10.3.3 I logged an event but the status did not change

- Refresh the page and confirm the status is still showing the old value. Network timing occasionally causes a display lag.
- If the issue persists, check whether another user may have simultaneously updated the record.

---

## 10.4 Specimen Logging

### 10.4.1 A barcode is not recognised at the PSE scan stage

- Check that the barcode was scanned correctly and that the physical label matches the expected format.
- Confirm that you are in the correct project. PSE barcodes are project-specific.
- Confirm that the subject event was created and that the event is associated with a participant in this project.

### 10.4.2 A specimen barcode fails validation

- BRIMS validates specimen barcodes against the regex pattern defined in the labware configuration for that specimen type. If the barcode does not match, it will be rejected.
- Check the physical label for scanning errors, or contact your project manager to confirm the expected barcode format.

### 10.4.3 I cannot see the specimen I just logged

- Search the Specimens list by barcode. If it was logged successfully, it will appear with a status of **Logged**.
- If it does not appear, the logging workflow may not have been fully submitted. Repeat the logging process, ensuring all required fields are completed before submitting.

### 10.4.4 A specimen has the wrong status

- Review the specimen record and check all status fields and timestamps.
- If the status needs to be corrected and there is a clear operational reason to do so, an authorised user can edit the record directly (see Chapter 5 — Logging Specimens, section 5.6).
- Where possible, use the logging workflows (Log Out, Log Return, Log as Used) rather than editing status directly, as these preserve the correct audit trail.

---

## 10.5 Storage

### 10.5.1 A specimen type is disabled in the Allocate Storage form

- This means there are insufficient free locations configured for that specimen type in the current project's virtual storage units.
- Contact your laboratory manager or project administrator to arrange additional storage capacity before attempting a new allocation.

### 10.5.2 A specimen does not appear in the allocation form

- The allocation page only shows specimens with a status of **Logged** at your current site that have a storage specimen type assigned. If a specimen is in a different status (e.g. already In Storage or Transferred), it will not appear.
- Confirm the specimen status in the Specimens list.

---

## 10.6 Manifests and Shipments

### 10.6.1 I cannot edit a manifest

- Manifests can only be edited when their status is **Open**. Once a manifest has been shipped it becomes read-only.
- If a manifest was shipped in error, contact your project administrator.

### 10.6.2 I cannot see the Receive Manifest button

- The **Receive the Manifest** action is only visible to users whose project site assignment matches the manifest's destination site. If you are not at the destination site, you will not see this button.
- Confirm your site assignment with your project manager.

### 10.6.3 Specimens on a manifest have the wrong prior status

- The prior status recorded on each specimen in a manifest reflects the status at the time the specimen was attached. It cannot be edited.
- If there is a discrepancy, review the specimen record's history and, if necessary, contact your project administrator.

---

## 10.7 Studies and Assays

### 10.7.1 I cannot add specimens or assays to a study

- Check whether the study has the **Locked** toggle enabled. A locked study is read-only. Contact the study owner or project manager to unlock it if changes are needed.

### 10.7.2 An assay definition is missing from the dropdown

- Assay definitions are managed at the system level by an administrator. If the definition you need does not appear, contact your system administrator.

---

## 10.8 When to Escalate

Escalate an issue to your project administrator or system administrator when:

- The problem appears to be permission-related and reconfirming your role has not resolved it
- Data appears inconsistent across linked records (e.g., a specimen linked to the wrong participant or event)
- A shipment or storage discrepancy cannot be reconciled through the standard views
- A REDCap integration issue persists after confirming the project and token settings
- A barcode or validation error recurs after confirming the physical labels are correct

### 10.8.1 Information to include when reporting an issue

Providing accurate details when you escalate will help resolve the issue faster. Include:

- The project name or identifier
- The relevant subject ID, specimen barcode, manifest ID, or study identifier
- The date and time the issue occurred (or was first noticed)
- A brief description of what you expected to happen and what actually happened instead
- Any error messages shown on screen

---

## Summary

| Symptom | First thing to check |
|---|---|
| Cannot sign in | Email/password and account existence |
| Project not visible | Project membership and role |
| Participant not found | Search by subject ID; check status filter |
| Log Event button missing | Check event status — must be Scheduled |
| Barcode rejected at PSE scan | Correct project selected; event exists |
| Specimen barcode fails | Labware barcode format vs physical label |
| Specimen type disabled in allocation | Insufficient storage capacity configured |
| Cannot edit manifest | Status must be Open |
| Cannot receive manifest | Your site must match the destination site |
| Cannot edit study | Check whether Locked is enabled |

---

---

## Chapter 11 — REDCap Integration

---

## Overview

This chapter is for data managers, project administrators, and system administrators who need to configure or troubleshoot REDCap-linked workflows in BRIMS.

BRIMS can be linked to REDCap for projects that use REDCap alongside operational specimen and study workflows. This integration allows project activity in BRIMS to connect with participant data already managed in REDCap.

Not all projects use REDCap. If you are unsure whether your project requires REDCap integration, check with your project administrator before proceeding.

---

## 11.1 Integration Overview

When a BRIMS project is linked to a REDCap project, certain participant and workflow data can be exchanged between the two systems.

REDCap-linked projects are created differently from standard BRIMS projects. They must be set up using the dedicated **Create New REDCap-Linked Project** workflow rather than the standard project creation form.

> **Caution:** If a project that requires REDCap integration is created using the standard project form, it cannot be linked to REDCap after the fact. Always confirm whether REDCap integration is required before creating a new project. See Chapter 2 — Setting Up a Project for guidance on project creation.

---

## 11.2 Creating a REDCap-Linked Project

1. Navigate to the team **Projects** section.
2. Select **Create New REDCap-Linked Project** (not the standard **New Project** option).
3. Select the REDCap project to link from the available options.
4. Complete the remaining project setup fields as you would for a standard project.
5. Save the project.

Once created, the linked project will appear in the team Projects list with a **REDCap Linked** indicator. Confirm this indicator is present before users begin working with live data.

> **Caution:** Make sure the correct REDCap project is linked to the correct BRIMS project before any live data entry begins. Linking the wrong REDCap project can result in data being associated with the wrong source records.

---

## 11.3 User Tokens and Access

Some REDCap functions depend on a user-specific API token stored on the project member record. This token authorises the individual member's access to the linked REDCap project.

To add or update a REDCap token for a project member:

1. Open the project's member list from **Project Configuration** → **Members**.
2. Find the relevant member and select **Edit**.
3. In the **REDCap Token** field, enter or update the token value.
4. Save the member record.

> **Important:** Only enter a token for the correct project member. Tokens are user-specific. If a member's REDCap access changes or a token is replaced, update the stored value promptly to avoid disrupted workflows.

The REDCap Token field is only visible on member records within REDCap-linked projects.

---

## 11.4 Confirming the Setup

After creating a REDCap-linked project and configuring member tokens, verify that the expected REDCap-connected functions are available and behaving correctly:

1. Have an affected user sign in and confirm that REDCap-dependent workflow options are visible.
2. Run a test action that relies on the REDCap link and confirm that the expected data is returned.
3. If issues are found, refer to the troubleshooting steps in section 11.5 below.

---

## 11.5 Troubleshooting REDCap Integration

| Symptom | First thing to check |
|---|---|
| REDCap-linked options do not appear | Confirm the project was created as a REDCap-linked project, not a standard project |
| A user cannot access REDCap-dependent functions | Check that a valid token is stored on that user's project member record |
| Data expected from REDCap is missing | Verify that the correct REDCap project is linked; check API access and permissions |
| Sync results appear inconsistent | Confirm that the token is current and that REDCap-side permissions have not changed |

### 11.5.1 Step-by-step troubleshooting

When a REDCap integration issue is reported:

1. Confirm that the correct **REDCap-linked project** was created (the team Projects list should show the **REDCap Linked** indicator).
2. Check that the affected user has the correct REDCap access in the REDCap system itself.
3. Verify that a correct, current token is stored on the user's project member record in BRIMS.
4. Review whether the action that failed actually depends on REDCap for that specific workflow step, or whether the issue may be unrelated to the integration.
5. If the issue cannot be resolved through the above steps, escalate to a system administrator for configuration review.

---

## Summary

| Task | Where to go |
|---|---|
| Create a REDCap-linked project | Team **Projects** → **Create New REDCap-Linked Project** |
| Verify the REDCap link is active | Team **Projects** list — check for the **REDCap Linked** indicator |
| Add or update a member's REDCap token | **Project Configuration** → **Members** → edit the member record |
| Troubleshoot integration issues | See section 11.5 above |

---

---

## Chapter 12 — Administration Guide

---

## Overview

This chapter is for system administrators and selected project administrators who are responsible for keeping BRIMS configured, governed, and ready for project use.

Administrative responsibilities in BRIMS fall into four main areas: user and access management, reference data configuration, preparing for new projects, and ongoing operational monitoring. In the current BRIMS interface, this work is split between system-level administration (the **Admin** panel) and team-level configuration (the team **Projects** and related setup areas).

---

## 12.1 User Management

### 12.1.1 Creating and managing accounts

User accounts are managed from the **Users** area in the Admin panel.

When creating a new account:

1. Navigate to **Admin** → **Users** → **New User**.
2. Enter the user's name and email address.
3. Assign the appropriate system role.
4. Save the record. The user will receive an invitation to set their password.

The Users list includes an **avatar** column and a **Last Login** column, both visible by default. The Last Login column is sortable and allows administrators to identify inactive accounts quickly. An **Active** filter is available to show only currently active users.

### 12.1.2 Reviewing and updating access

Review user accounts regularly to ensure that project membership and permissions remain appropriate for each person's current responsibilities.

When a user's role changes — for example, if they move to a different project or leave the organisation — update or deactivate their account promptly.

| Action | Where to do it |
|---|---|
| Create a new user account | **Admin** → **Users** → **New User** |
| Update a user's system role | **Admin** → **Users** → edit the user record |
| Add a user to a project | **Project Configuration** → **Members** → add member |
| Change a user's project role | **Project Configuration** → **Members** → edit the member record |
| Deactivate an account | **Admin** → **Users** → edit the user record → disable access |

> **Caution:** Deactivating an account does not remove the user's historical records or audit trail. It prevents further sign-in only. Do not delete user records unless directed by your organisation's data governance policy.

---

## 12.2 Role and Permission Management

System roles should be kept limited to users who genuinely need wider administrative access. Project roles should be scoped to support day-to-day work without granting unnecessary permissions.

- Use the project **Roles** area (within **Project Configuration**) for project-specific permissions.
- Use the **Admin** panel only for system-level administrative access.

When assigning roles, apply the principle of least privilege: give each user the minimum access needed to perform their work.

> **Tip:** The most common cause of unexpected access problems is a mismatch between a user's project role and the actions they need to perform. If you receive a report that an expected action is unavailable, check the member's project role before investigating other causes.

---

## 12.3 Reference Data and Configuration

Reference data and configuration settings should be reviewed whenever new projects, workflows, or study requirements are introduced.

Keeping shared configuration values consistent across the system ensures that data entry and reporting remain reliable.

### 12.3.1 System-level reference data (Admin panel)

The following configuration areas are managed at the system level:

| Area | Purpose |
|---|---|
| **Study Designs** | Templates for study structure used across projects |
| **Unit Definitions** | Standard units for specimen volumes and measurements |
| **Physical Units** | Physical storage container types and dimensions |
| **Institutions** | Named institutions associated with users or projects |
| **Label Specifications** | Label printing format templates (paper size, metric, format name) used to configure project label printing |

### 12.3.2 Team-level configuration

The following are managed at the team level and are available to all projects within the team:

| Area | Purpose |
|---|---|
| **Assay Definitions** | Templates for assay record structure, including custom metadata fields |
| **Protocols** | Standard operating procedures associated with study or specimen work |
| **Programmes** | Funded research programmes that can be linked to one or more projects within the team |

When a project requires a new assay type, specimen unit, or study design, review whether a configuration update is needed before the project goes live.

---

## 12.4 Managing Institutions

Institutions represent the named organisations associated with users in BRIMS. They are managed from the Admin panel under **User Management** → **Institutions**.

To create an institution:

1. Navigate to **Admin** → **Institutions** → **New Institution**.
2. Enter the **Name** (up to 20 characters; must be unique across the system).
3. Save.

Institutions can be edited or deleted from the list view using the **Edit** and **Delete** actions. Deleting an institution will affect any users or records that reference it — confirm that the institution is no longer in use before deleting.

---

## 12.5 Managing Label Specifications

Label specifications define the format templates available for printing participant labels. Each project must be assigned a label specification at creation time, so these should be configured before any new projects are set up.

Label specifications are managed from the Admin panel.

| Field | What to enter |
|---|---|
| **Format** | A unique code identifying this label format (up to 30 characters). This is the primary key and cannot be changed after creation. |
| **Paper Size** | The physical paper or label sheet size (e.g. `A4`, `Letter`). |
| **Metric** | The unit system used for label dimensions (e.g. `mm`, `in`). |

> **Note:** Label specifications are system-wide. Any label specification created here becomes available when configuring new or existing projects.

---

## 12.6 Managing Programmes

Programmes are team-level records that represent funded research initiatives spanning one or more projects. Programmes are created and managed within the **Team** area of the application.

### 12.6.1 Creating a programme

1. Navigate to your team's overview page.
2. Open the **Programmes** tab.
3. Select **New Programme**.
4. Complete the programme form:

| Field | Required | Notes |
|---|---|---|
| **Name** | Yes | A descriptive name for the programme (up to 100 characters) |
| **Funder** | Yes | The name of the funding organisation (up to 150 characters) |
| **Grant Number** | No | The grant or award reference number |
| **PI** | No | The principal investigator — select from team members |
| **Description** | No | A free-text description of the programme's scope and objectives |

5. Save the programme.

### 12.6.2 Linking programmes to projects

Once a programme exists at the team level, it can be linked to one or more projects within the same team. See section 2.8 — Linking a Project to Programmes for the project-side workflow.

### 12.6.3 Reviewing programmes

The Programmes tab on the team record shows all programmes for the team, with columns for name, funder, and grant number. Click a programme to open its detail view, where linked projects are visible.

---

## 12.7 Preparing for New Projects

Before onboarding a new project, confirm that the following are in place:

- [ ] Required user accounts exist and are active
- [ ] Project members have been assigned appropriate roles
- [ ] Arm and event definitions reflect the study design
- [ ] Specimen types and labware configurations are correct
- [ ] Relevant reference data (assay definitions, protocols, units) is configured
- [ ] At least one label specification has been created and is available for the project
- [ ] If the project uses REDCap: the project has been created using the **Create New REDCap-Linked Project** workflow and member tokens are configured

> **Tip:** Walking through this checklist before a project begins active enrolment will prevent the most common setup errors. See Chapter 2 — Setting Up a Project for the full project configuration workflow, and Chapter 11 — REDCap Integration if the project requires a REDCap link.

---

## 12.8 Operational Monitoring

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
| Add a project member | **Project Configuration** → **Members** |
| Manage system reference data | **Admin** panel → relevant configuration area |
| Manage institutions | **Admin** → **Institutions** |
| Manage label specifications | **Admin** → **Label Specifications** |
| Manage assay definitions | Team-level **Assay Definitions** area |
| Manage programmes | Team-level **Programmes** tab |
| Prepare a new project for go-live | Checklist in section 12.7 |
| Troubleshoot a recurring operational issue | Review permissions, process, and configuration in that order |

---

---

## Chapter 13 — Glossary

---

This glossary defines the key terms used in BRIMS and throughout this manual. Where a term has a specific technical or operational meaning in the system, that meaning is given here.

---

### Aliquot

A single tube or container within a set of identical samples derived from the same source. When a specimen type has multiple aliquots, each aliquot is a separate specimen record with its own barcode.

### Arm

A study arm is a participant grouping within a project — for example, a control group or an intervention group. Each arm has its own sequence of event definitions, and participants are enrolled into a single arm. See also: **Event Definition**, **Enrolment**.

### Arm Baseline Date

The date used as the starting point for calculating event dates in a participant's arm. This is set automatically to the participant's enrolment date when they are enrolled.

### Assay

A record of a laboratory analysis, measurement, or test run within a study. Each assay is linked to a study, associated with an assay definition, and records the technology platform used. See also: **Assay Definition**, **Study**.

### Assay Definition

A pre-configured template that defines the structure of an assay record, including any additional data fields specific to that assay type. Assay definitions are managed by system administrators and are available to all projects.

### Biorepository

One of the three supported storage types in BRIMS. Refers to general controlled-temperature biorepository storage. See also: **Storage Type**.

### Derivative Specimen

A specimen derived or processed from a primary specimen — for example, plasma separated from whole blood. Derivative specimens are logged through the **Log Derivative Specimens** workflow and carry a link to their parent specimen. See also: **Primary Specimen**, **Parent Specimen**.

### Enrolment

The process of formally registering a participant into a project arm. Enrolment moves the participant's status from **Generated** to **Enrolled** and sets the arm baseline date used for event scheduling.

### Event

A scheduled or completed activity linked to a participant — such as a study visit, follow-up contact, or specimen collection point. Events are configured as event definitions within an arm and are instantiated as subject events when a participant is enrolled. See also: **Event Definition**, **Subject Event**.

### Event Status

The current stage of a subject event in the workflow. Possible statuses are: Pending, Primed, Scheduled, Logged, Logged Late, Missed, and Cancelled.

### Event Definition

The configuration of a recurring event within an arm, specifying its name, expected day offset from the arm baseline date, acceptable date window, and whether it is repeatable. Each enrolled participant gets a subject event generated from each event definition in their arm.

### Labware

A type of container (tube, vial, plate etc.) associated with a specimen type, including its barcode format. Labware configuration determines which barcodes will be accepted when specimens of that type are logged.

### Label Specification

A system-level template that defines the physical format of printed barcode labels — including paper size and metric. Each project must be assigned a label specification. Label specifications are managed by administrators from the Admin panel and are available to all projects.

### Import Value Mapping

A project-level configuration record that translates field values used in an import file into the corresponding names used in BRIMS. Import value mappings can be defined for Sites, Arms, Events, Specimen Types, and Status fields. They are applied automatically when bulk import actions are run for the project.

### Institution

A named organisation associated with users in BRIMS. Institutions are managed at the system level from the Admin panel. See also: **Admin Panel**.

### Liquid Nitrogen

One of the three supported storage types in BRIMS. Refers to cryogenic vapour or liquid nitrogen storage. See also: **Storage Type**.

### Manifest

A shipment record that groups specimens together for transfer between sites. A manifest moves through three statuses: **Open**, **Shipped**, and **Received**. See also: **Manifest Status**.

### Manifest Status

The current stage of a manifest in the transfer workflow. Possible statuses are: Open (being prepared), Shipped (dispatched by the sending site), and Received (confirmed by the receiving site).

### Minus-80

One of the three supported storage types in BRIMS. Refers to ultra-low temperature freezer storage at −80 °C. See also: **Storage Type**.

### Origin Site

The site where a specimen was originally collected or first logged. This is recorded automatically at the time of logging (set to the logging user's current site) and is distinct from the current storage site. Origin site is displayed on the specimen record and can be reviewed in the specimen detail view.

### Parent Specimen

The primary specimen from which a derivative specimen was processed. The parent-child relationship is recorded at the time of derivative logging and is shown on the derivative specimen's record.

### Participant

A person enrolled in a research project. In the BRIMS user interface, participant records appear under the label **Subjects**. The terms participant and subject are used interchangeably in this manual to match both clinical and technical usage.

### Primary Specimen

A specimen collected directly from a participant, as opposed to a derivative specimen processed from another sample. Primary specimens are logged through the **Log Primary Specimens (2-Stage)** workflow. See also: **Derivative Specimen**.

### Programme

A team-level record representing a funded research initiative that may span one or more projects. A programme records the funding organisation, grant number, principal investigator, and description. Projects can be linked to one or more programmes. Programmes are managed from the team overview page.

### Project

The top-level unit for organising research work in BRIMS. A project has its own configuration, participants, arms, events, specimen types, storage settings, and team members.

### PSE

Abbreviation for **Project Subject Event**. See **Project Subject Event Barcode (PSE Barcode)**.

### Project Subject Event Barcode (PSE Barcode)

A scannable barcode that identifies a specific combination of project, participant, and event. The PSE barcode is used as the entry point for the primary specimen logging workflow to identify which participant and event the specimens belong to.

### Publication

A project-level record that captures bibliographic information about a research output from the project — such as a journal article, preprint, or conference proceedings. Publication records store the title, authors, PubMed ID, DOI, publication date, and publication status. See also: **Publication Status**.

### Publication Status

A field on a publication record indicating its current stage in the publication pipeline. Possible values are: Draft, Submitted, and Published.

### REDCap Integration

An optional configuration that links a BRIMS project to a REDCap project for participant data synchronisation. When enabled, subjects and enrolment data can be pulled from or pushed to the connected REDCap project.

### Site

A physical or organisational location within a project — for example, a specific hospital or laboratory. Sites determine where specimens are collected and stored, and which specimens and participants a user has access to.

### Specimen

A biological sample that is logged, tracked, stored, shipped, or used in the context of a research project. Each specimen has a unique barcode, is linked to a participant event, and carries a status that reflects its current position in the research workflow.

### Specimen Status

The current stage of a specimen in the research workflow. Possible statuses are: Unassigned, Registered, Logged, In Storage, Pre Transfer, Used, Reassigned, Transferred, Lost, Logged Out, and Received.

### Specimen Type

A project-level definition of a biological sample category — for example, whole blood, serum, or PBMC. Each specimen type is associated with a labware type, a storage designation, and whether it is a primary or derivative specimen.

### Storage Allocation

The automated process of assigning logged specimens to storage locations within the configured storage units for a project.

### Storage Type

The physical environment in which a specimen is stored. BRIMS supports three storage types: **Minus-80**, **Liquid Nitrogen**, and **Biorepository**.

### Study

A research investigation within a project that links a defined set of specimens to one or more assays. Studies have a publication status and can be locked to prevent further changes.

### Subject

The label used in the BRIMS user interface for a participant record. See also: **Participant**.

### Subject ID

The unique identifier generated for each participant record. The format is determined by the project prefix and the number of digits configured during project setup (e.g. ABC001). Subject IDs are assigned automatically by BRIMS and cannot be manually specified.

### Subject Status

The current enrolment state of a participant. Possible statuses are: Generated (record exists but not yet enrolled), Enrolled (active participant), and Dropped (no longer active in the study).

### Virtual Unit

A logical representation of a storage unit in BRIMS — for example, a freezer box within a rack. Virtual units have available locations that can be allocated to specimens.

---

*End of BRIMS User Manual — Draft 2*
