# BRIMS User Manual

**Bio-medical Research Information Management System**

Revised — 19 November 2026

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
  - [12.1 User Management](#121-user-management)
  - [12.2 Role and Permission Management](#122-role-and-permission-management)
  - [12.3 Reference Data and Configuration](#123-reference-data-and-configuration)
  - [12.4 Managing Institutions](#124-managing-institutions)
  - [12.5 Managing Label Specifications](#125-managing-label-specifications)
  - [12.6 Managing Programmes](#126-managing-programmes)
  - [12.7 Preparing for New Projects](#127-preparing-for-new-projects)
  - [12.8 Operational Monitoring](#128-operational-monitoring)
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
- [Chapter 3 — Enrolling and Managing Participants](#chapter-3-enrolling-and-managing-participants)
- [Chapter 4 — Recording Events and Follow-up](#chapter-4-recording-events-and-follow-up)

### Laboratory and Specimen Handling Staff

This includes laboratory technicians, biobank staff, and anyone responsible for logging, storing, or shipping biological specimens.

Relevant chapters are:
- [Chapter 5 — Logging Specimens](#chapter-5-logging-specimens)
- [Chapter 6 — Managing Specimen Storage](#chapter-6-managing-specimen-storage)
- [Chapter 7 — Preparing and Receiving Shipments](#chapter-7-preparing-and-receiving-shipments)

### Project Managers and Data Managers

This includes project leaders, study administrators, and data managers responsible for configuring the system, linking data to studies, and producing reports.

Relevant chapters are:
- [Chapter 2 — Setting Up a Project](#chapter-2-setting-up-a-project)
- [Chapter 8 — Studies, Assay Data, and Publications](#chapter-8-studies-assay-data-and-publications)
- [Chapter 9 — Searching, Reviewing, Exporting, and Importing Data](#chapter-9-searching-reviewing-exporting-and-importing-data)
- [Chapter 11 — REDCap Integration](#chapter-11-redcap-integration)
- [Chapter 12 — Administration Guide](#chapter-12-administration-guide)

---

## How to Use This Manual

If you are new to BRIMS, read [Chapter 1 — Getting Started](#chapter-1-getting-started) first. This chapter explains how to sign in, how to navigate the system, and how your role affects what you can see and do.

After that, follow the reading path for your role above.

If you are troubleshooting a specific problem, go directly to [Chapter 10 — Troubleshooting Common Problems](#chapter-10-troubleshooting-common-problems).

For REDCap integration setup and troubleshooting, refer to [Chapter 11 — REDCap Integration](#chapter-11-redcap-integration).

For system administration tasks including user management and reference data configuration, refer to [Chapter 12 — Administration Guide](#chapter-12-administration-guide).

For definitions of terms used across the system and this manual, refer to [Chapter 13 — Glossary](#chapter-13-glossary).

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
- Your username and password, or a registered passkey

BRIMS supports passkey authentication (such as a device biometric or hardware security key) as an alternative to password sign-in. To use a passkey, it must first be registered from your profile page after you have signed in with your password. You can also add authenticator-app two-factor authentication from your profile page.

When a system administrator creates your account, you receive an email with a **Set Password** link. Complete this step before trying to sign in for the first time. The link expires after 24 hours; if it has expired, use **Forgot password?** on the sign-in page or ask your system administrator for a new one. If your account was created by a team administrator, no email is sent: use **Forgot password?** on the sign-in page to set your first password.

![The BRIMS sign-in screen showing the username and password fields and a passkey sign-in option.]()

Once signed in, you will land on the BRIMS dashboard, which shows the events that are due and overdue across your projects. The menu items you see will depend on your role and what you have access to.

> **Tip:** If you cannot sign in, confirm that you are entering the correct username and password. Passwords are case-sensitive. If your credentials appear correct but access is still refused, your account may be inactive (BRIMS displays "Your account is currently inactive"). Contact your project administrator or system administrator.

---

## 1.2 How BRIMS Is Organised

BRIMS organises work at two levels: a **team** and a **project**.

A **team** is the top-level organisational unit in BRIMS. It represents the research group or organisation that operates the system and owns the projects and studies within it. Teams have their own members, protocols, assay definitions, programmes, and studies. Users normally belong to a team, and every project and study belongs to a team.

A **project** is the main working unit within a team. Almost all of your day-to-day work — participant enrolment, specimen logging, storage, and shipments — takes place within a specific project. **Studies** are the exception: they belong to the team, are linked to one or more of the team's projects, and are opened from the main menu rather than from inside a project.

This is important to understand from the start: the actions, records, and data you see are scoped to the project you are currently working in. If you switch to a different project, you will see different participants, specimens, and manifests.

The main building blocks are:

| Concept | What it represents |
|---|---|
| **Team** | The organisational group that owns projects, studies, members, protocols, assay definitions, and programmes |
| **Programme** | A funded research initiative at the team level that can span one or more projects |
| **Project** | The primary working unit within a team, bringing together all sites, arms, participants, and specimens |
| **Site** | A physical or organisational location where project work is carried out |
| **Arm** | A participant grouping within the project, such as a control or treatment cohort |
| **Subject** | A participant enrolled in the project — BRIMS uses the term *subject* for participant records |
| **Event** | A scheduled or completed study activity, such as a visit or specimen collection point |
| **Specimen** | A biological sample that is logged, tracked, stored, shipped, or used |
| **Study** | A team-level research investigation, linked to one or more of the team's projects, used to organise specimens and assay work |
| **Manifest** | A shipment or transfer record grouping specimens for movement between sites |

These records are connected. A subject belongs to a site and an arm; events are linked to subjects; specimens are linked to events and subjects; specimens can be added to manifests and, from any of a study's linked projects, to studies. This is why accuracy at each step matters — downstream records inherit the context set earlier.

> **Tip:** If something looks wrong in a later workflow step — for example, a specimen cannot be found, or an event appears under the wrong participant — the first place to check is usually the record created at the earlier step. Working carefully and confirming identifiers as you go prevents the most common data quality problems.

---

## 1.3 Navigating the System

### 1.3.1 Main Navigation

The main navigation menu gives you access to the areas of BRIMS relevant to your role and project.

BRIMS has two menus. The **main menu** is shown when you are not working inside a project. After you open a project, the **project menu** replaces it.

![The main BRIMS menu showing Dashboard, Projects, Calendar, Studies, My Team, Admin, and Documentation.]()

The main menu contains:

- **Dashboard** — Events that are due and overdue across your projects. Selecting a row takes you into that project.
- **Projects** — The list of projects you can work in. Select **Access** on a row to open a project.
- **Calendar** — Upcoming pending, primed, and scheduled events across your projects. This is only shown to users who can manage subjects in at least one project.
- **Studies** — Team-level study records, their members, linked specimens, and assay data (see [Chapter 8](#chapter-8-studies-assay-data-and-publications))
- **My Team** — Your team's overview page, showing members, projects, protocols, assay definitions, and programmes
- **Admin** — The system administration area (system administrators only)
- **Documentation** — Opens this manual in a new browser tab

![The project menu showing Subjects, Specimens, Specimen Storage, Manifests, and Project Configuration.]()

The project menu contains:

- **Main Panel** — Returns you to the main menu
- **Dashboard** — Events due and overdue, and progress, for the current project
- **Project Configuration** — Project-level settings, sites, arms, specimen types, labware, members, programmes, and imports or exports
- **Subjects** — Participant enrolment, subject records, and linked events
- **Label Queue** — Queued barcode labels for upcoming events, ready to print
- **Generate Schedule** — Opens this week's schedule in a new browser tab
- **Specimens** — Reviewing, searching, and exporting the full specimen list
- **Log Primary Specimens (2-Stage)** — Barcode-driven logging of primary specimens
- **Log Derivative Specimens** — Logging of derivative (child) specimens from a parent
- **Specimen Storage** — Storage allocation and storage reports
- **Manifests** — Shipment and transfer records
- **Publications** — Bibliographic records for research outputs associated with the project
- **Roles** — Project roles and their permissions (in the **Authorisation** group)

Not all of these areas will be visible to every user. What you see depends on your roles and permissions. For example, **Label Queue** and **Generate Schedule** require permission to manage subjects, and the specimen logging and storage pages require permission to manage specimens.

### 1.3.2 Moving Between Records

Within each section, BRIMS uses a standard pattern:

1. A **list view** shows all records of that type for the current project
2. Clicking a record opens its **detail page**, where you can view all related information and take actions
3. Related records — such as events linked to a subject, or specimens linked to a study — appear in **tabs** or **sections** on the detail page

Get familiar with this pattern early. Most workflows in BRIMS follow the same structure: open a list, find or create a record, navigate to its detail page, and take the appropriate action from there.

### 1.3.3 Working Within the Correct Project

Before doing any work in BRIMS, always confirm that you are in the correct project.

The current project is shown at the top of the page as **Current project: <project title>**. To switch projects, select **Main Panel** in the project menu, then select **Access** on a different project in the **Projects** list. If you are working across multiple projects, take care when switching between them — particularly before creating or editing records, because there is no automatic prompt to warn you if you are in the wrong project.

> **Caution:** Records created in the wrong project cannot be automatically moved. If you enrol a participant or log a specimen in the wrong project, contact your data manager to discuss how to correct it.

---

## 1.4 Roles and Permissions

### 1.4.1 What Roles Control

BRIMS uses a role-based permission system. Your role determines:

- Which sections of the navigation you can see
- Which actions are available on record pages (for example, whether you can create, edit, or delete records)
- Which exports and reports you can access

Roles operate at four levels:

| Role type | What it controls |
|---|---|
| **System roles** | Wider administrative functions outside of specific projects. Only system administrators can enter the **Admin** area. |
| **Team roles** | A user is either a team **Admin** or a team **Member**. Team administrators can create projects, manage the team's members, see all of the team's projects, and have full permissions within the team's projects. |
| **Project roles** | Permissions within a specific project, assigned when you are added to that project |
| **Study roles** | A **Lead** or **Member** within a specific study. Only a study lead can edit the study and manage its members (see [Chapter 8](#chapter-8-studies-assay-data-and-publications)). |

A user can have different project roles in different projects. For example, a data manager may have read-only access in one project and full editing access in another.

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
| Switch projects | **Main Panel** → **Projects** → **Access** |
| Find a participant | **Subjects** list |
| Find a specimen | **Specimens** list |
| Check your role | Contact your project administrator |
| Report a problem | See [Chapter 10 — Troubleshooting Common Problems](#chapter-10-troubleshooting-common-problems) |

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
7. Link the project to studies and programmes (optional)

Each step is covered in its own section below. A [setup checklist](#setup-checklist) is provided at the end of the chapter.

---

## 2.1 Creating a Project

Open **My Team** in the main menu, select the **Projects** tab, and select **New project**. Only team administrators and system administrators can create projects.

![The project creation form showing required fields: title, identifier, project leader, label format, and subject ID settings.]()

### 2.1.1 Required fields

| Field | What to enter |
|---|---|
| **Title** | The full name of the project (5 to 100 characters). This must be unique across the system. |
| **Identifier** | A short alphanumeric code used to reference the project (e.g. `BRIM-001`). This must also be unique across the system. |
| **Project Leader** | The user assigned as project lead. Choose from the users in your team (system administrators can also be selected). The leader is automatically added as a project administrator on creation. |
| **Study Design** | The study design that describes this project. Study designs are maintained by system administrators (see [Chapter 12 — Administration Guide](#chapter-12-administration-guide)). This is chosen when the project is created and is not shown on the project's edit form afterwards. |
| **Label Format** | The label specification that defines the format used for printing participant labels in this project. Label specifications are configured in advance by a system administrator (see [Chapter 12 — Administration Guide](#chapter-12-administration-guide)). |
| **Subject ID: Prefix** | Two to ten uppercase letters or digits, beginning with an uppercase letter, that will be prepended to all participant identifiers (e.g. `BRI`). |
| **Subject ID: Digits** | The number of digits in the numeric part of the participant ID (between two and eight). |
| **Storage Designation** | An identifier for the storage location associated with this project (e.g. a freezer block code or site abbreviation), up to 40 characters. |

### 2.1.2 Optional fields

| Field | What to enter |
|---|---|
| **Description** | A free-text description of the project. |
| **Programmes** | One or more of the team's programmes to link the project to (see [section 2.8](#28-linking-a-project-to-programmes)). |
| **Submission Date** | The date the project was or will be formally submitted. |

A **Public Release Date** field is also available when you view or edit the project after it has been created.

> **Important — Subject ID format:** The prefix and digit count together define the format of every participant identifier the system will generate. For example, a prefix of `BRI` with six digits produces IDs such as `BRI000001`, `BRI000002`, and so on. Choose this format according to your study protocol and confirm it with your team before saving. This setting is difficult to change once participants have been enrolled and identifiers have been issued.

> **REDCap projects:** If this project needs to be linked to REDCap, do not use the standard project form. Instead, use the REDCap-linked project option (the dialog titled **Create New REDCap-Linked Project**) available from the team **Projects** tab. See [Chapter 11 — REDCap Integration](#chapter-11-redcap-integration) for full setup and troubleshooting guidance.

### 2.1.3 What BRIMS creates automatically

When you save a new project, BRIMS sets up the following automatically:

- A default **Admin** role for the project, with all permissions
- An initial site named after your team's institution (described as "Project Creator's site")
- Project membership, with the Admin role, for the project creator and the project leader
- If the project leader's institution differs from yours, a second site named after the leader's institution (described as "Project Leader's site")

If this set-up fails part-way, BRIMS removes the project and shows an **Error setting up project!** message; create the project again once the problem is resolved.

These defaults are a starting point. Review them before proceeding with the rest of the setup, particularly the initial site names and the Admin role permissions.

Sections [2.2](#22-adding-sites) to [2.5](#25-reviewing-roles-and-adding-project-members) cover the core project configuration steps accessed through **Project Configuration** in the project menu. Select your project from the **Projects** list using the **Access** button, then open **Project Configuration** to find the tabs for Members, Sites, Arms, Labware and Specimen Types. Section [2.6](#26-linking-the-project-to-studies) covers linking the project to studies, section [2.7](#27-importing-existing-data) covers importing existing data, and section [2.8](#28-linking-a-project-to-programmes) covers linking the project to team-level programmes.

---

## 2.2 Adding Sites

Sites represent the physical or organisational locations where research is conducted within the project. If your project operates across more than one hospital, clinic, laboratory, or institution, add a separate site for each.

Sites also matter for access control: project members and their substitutes are assigned to sites, which affects what they can see and do.

Open **Project Configuration**, open the **Sites** tab, and select **New Site**.

| Field | What to enter |
|---|---|
| **Name** | A short name for the site (two to twenty characters). Must be unique within the project. |
| **Description** | A brief description of the site's function or location (required, up to 255 characters). |

> **Tip:** An initial site is created automatically when the project is set up, named after your team's institution. Check the name of this default site before adding others — it may need to be renamed to match your project's site naming convention.

> **Tip:** If you are unsure how many sites your project will need, plan based on where specimens will be collected and where they will be processed or stored. A site structure that mirrors the physical research locations makes specimen tracking and member assignment significantly more straightforward later.

---

## 2.3 Creating Study Arms

Study arms divide project participants into distinct groups or cohorts — for example, a control group and one or more treatment groups. Every enrolled participant must be assigned to one arm.

Open **Project Configuration**, open the **Arms** tab, and select **New Arm**.

| Field | What to enter |
|---|---|
| **Name** | A descriptive name for the arm (e.g. `Control`, `Treatment A`), up to 50 characters. Must be unique within the project. |
| **Manual Enrolment** | Enable this if participants should be manually assigned to this arm. Leave disabled if assignment will be automated. |

BRIMS assigns arm numbers automatically. The arm number cannot be set manually.

The arm form also shows a **Switchable Arms** checkbox list of the project's other arms. Ticking an arm here means that participants currently enrolled in this arm can be moved to the selected arm using the **Switch Arm** action on a subject's record. When a switch is performed, all of the subject's pending, primed, and scheduled events from the current arm are cancelled, the subject is assigned to the new arm with a new baseline date, and a fresh set of events is generated from the new arm's events. If no arms are ticked, the Switch Arm option will not appear for any subject in this arm.

> **Tip:** Create all arms before you start adding event definitions. Once participants are enrolled, changing an arm's structure may affect the events that have already been scheduled for subjects.

### 2.3.1 Adding event definitions to an arm

Events represent the visit schedule or follow-up milestones for participants in that arm. After you create an arm, open it and add the events that apply to subjects in that cohort.

![An arm record with the Events section open, showing event definitions with fields for name, offset, and scheduling windows.]()

| Field | What it does |
|---|---|
| **Name** | A descriptive label for the event (e.g. `Screening Visit`, `Month 6 Follow-up`). |
| **Offset** | The number of days from the subject's arm baseline date (set at enrolment, and reset if the subject switches arm) at which this event is expected to occur. Defaults to `0`. |
| **Offset ante window** | How many days before the scheduled date the event can still be recorded as on time. Leave blank if not required. |
| **Offset post window** | How many days after the scheduled date the event can still be recorded as on time. Leave blank if not required. |
| **Autolog** | If enabled, the event is created already marked as **Logged** when a participant switches into this arm using **Switch Arm**. |
| **Repeatable** | If enabled, additional iterations of this event can be added after the first one is recorded. |
| **Name labels** | The number of full-name barcode labels to print per event. Each label shows the participant's name, the project–subject–event ID, event name, iteration, and arm name. Enter `0` if not required. |
| **Subject event labels** | The number of PSE (Project–Subject–Event) barcode labels to print per event. Each label shows the subject ID, PSE ID, event name, iteration, and arm name. Enter `0` if not required. |
| **Subject ID labels** | The number of subject ID-only barcode labels to print per event. Each label shows the subject ID alone. Enter `0` if not required. |
| **Active** | Controls whether this event definition is currently in use for new subjects. |

> **Tip:** The offset, ante window, and post window together define the acceptable timing range for each visit. Setting these correctly helps follow-up review reports distinguish between events that were on time, those recorded within an acceptable window, and those that were genuinely missed or late. Discuss these values with your study statistician or clinical operations lead before entering them.

> **Tip — Reordering events:** Events within an arm can be reordered by dragging and dropping rows in the events table. The order determines which event is treated as first (event order 1), which affects enrolment: the first event with an offset of zero is automatically given a Scheduled status when a participant is enrolled. Reorder events before enrolment begins if the default order does not match your intended schedule.

---

## 2.4 Configuring Specimen Types and Labware

Before specimen logging begins, configure the specimen types and labware that the project will use. This step is essential: specimen logging workflows in BRIMS rely on these settings to identify and validate samples.

Open **Project Configuration** and use the **Labware** and **Specimen Types** tabs.

![The Specimen Types and Labware configuration areas, showing type settings and barcode format fields.]()

### 2.4.1 Labware

Labware records define the physical container associated with a specimen type and the barcode format expected during logging. Create a labware record for each distinct container type used in the project before configuring specimen types, as specimen types must reference a labware record.

| Field | What to enter |
|---|---|
| **Name** | A short, descriptive name for the container (e.g. `EDTA Tube`, `Serum Vacutainer`), up to 30 characters. |
| **Barcode Format Regex** | A regular expression that defines the expected barcode format for this labware. BRIMS validates every scanned or entered barcode against this pattern — if the barcode does not match, the logging step will fail. The regex must begin with `^` and end with `$`; BRIMS will add these automatically if omitted. |

A labware record that is used by one or more specimen types cannot be deleted.

> **Tip:** Work with your laboratory team to confirm the barcode format in use before entering the regex pattern. A small formatting error here can block logging for the entire project. If you are not familiar with regular expressions, ask your data manager or system administrator to help define this field.

### 2.4.2 Specimen types

Specimen types define what kind of sample is being collected and how it should be handled. Each specimen type must be linked to a labware record.

| Field | What to enter |
|---|---|
| **Name** | A descriptive name for the specimen type (e.g. `EDTA Whole Blood`, `Serum`), up to 50 characters. |
| **Primary** | Enable if this specimen is collected directly from the participant. Disable if it is derived from another specimen (e.g. plasma separated from whole blood). |
| **Parent Specimen Type** | Required when **Primary** is disabled, and not allowed when it is enabled. Select the specimen type that this derivative is processed from. |
| **Aliquots** | The number of aliquots (portions) that are expected per specimen of this type. Must be at least 1. |
| **Pooled** | Enable if specimens of this type are pooled from multiple sources. |
| **Default Volume** | The typical volume of each aliquot (e.g. `1.0`). Required if a volume unit is entered. |
| **Volume Unit** | The unit for the volume: `µl` or `ml`. Required if a default volume is entered. |
| **Specimen Group** | An optional free-text grouping label, used to organise specimen types in reports or exports (e.g. 'Blood'). |
| **Labware** | Select the labware record that corresponds to the physical container for this specimen type. |
| **Store** | Enable if specimens of this type should be allocated to storage after logging. |
| **Storage Specimen Type** | Required when **Store** is enabled, and not allowed when it is disabled. The label used to identify this specimen type within the storage system. |
| **Destination** | Required when **Store** is enabled, and not allowed when it is disabled. Select `Internal` for on-site storage within BRIMS, or `Biorepository` for transfer to an external biorepository. |
| **Transfer Destinations** | Optional. Add one or more destination labels if specimens may be transferred to named locations outside of the standard storage workflow. |
| **Active** | Controls whether this specimen type is available for logging. Disable to retire a type without deleting it. |

> **Tip:** Configure derivative specimen types only after the parent primary type has been created, as the form requires you to select the parent type. If a derivative type is created before its parent, it will not be available to select.

---

## 2.5 Reviewing Roles and Adding Project Members

### 2.5.1 Reviewing roles

Roles define what each project member is permitted to do. BRIMS creates a default Admin role when the project is set up, but most projects need at least a few distinct roles — for example, a data manager role, a clinical staff role, and a laboratory role.

Before adding members, navigate to **Roles** (in the **Authorisation** group of the project menu) and confirm that the available roles reflect the real working responsibilities of your team.

To create a new role:

1. Select **New Role**
2. Enter a name for the role (unique within the project)
3. Assign the appropriate permissions from the list. Permissions are grouped into tabs for resources, pages, widgets, and custom permissions, and are named by the action they allow: **View**, **Manage**, or **Delete** for each type of record. Custom permissions cover attaching and detaching project members and setting substitutes.

Permissions take effect as soon as a role is saved. Members assigned to that role will have their access updated automatically.

> **Tip:** Keep roles aligned with job functions rather than individual users. A role called `Laboratory Staff` is easier to manage and audit over time than multiple individually named roles. If a team member changes responsibilities, updating their role assignment is much simpler than maintaining a custom permission set.

### 2.5.2 Adding project members

Project members are users from your team who have been granted access to work within the project.

Open **Project Configuration**, go to the **Members** tab, and select **Attach**.

| Field | What to enter |
|---|---|
| **User** | Select from the list of active users. Each user can only be added once per project. |
| **Role** | Select the project role that defines this member's permissions. |
| **Site** | Assign the member to a project site where applicable. This is required if you intend to configure a substitute. |

> **Note:** The project leader cannot be detached from the project. To reassign project leadership, edit the project record and select a different user in the **Project Leader** field.

### 2.5.3 Member substitutes

A substitute is another project member who can act on behalf of a member when they are unavailable (for example, during leave or absence).

To assign a substitute, select the **Substitute** cell on the member's row in the Members list. A **Select Substitute** dialog opens. The member being covered must have permission to manage subjects and be assigned to a site. The substitute must:

- Already be a member of the same project
- Be assigned to the same project site as the member they are covering
- Have permission to manage subjects

A substitute can be set by the member themselves, the project leader, or a member with the project's Admin role.

> **Note:** If a member's site assignment changes and the current substitute is not assigned to the new site, the substitute assignment will be cleared automatically. Review substitute assignments whenever site configurations change.

### 2.5.4 REDCap tokens

If the project is linked to REDCap and particular members need REDCap API access, a personal token can be stored against each member's record. Select **Edit** on the member's row and add or update the token in the **Redcap token** field. This field only appears for REDCap-linked projects, and is not available when you first attach the member.

---

## 2.6 Linking the Project to Studies

Studies are research investigations that belong to your **team**, not to an individual project. A study can be linked to one or more of the team's projects, and it can use specimens from every project it is linked to. Because of this, studies are not created or configured from inside a project.

To link your project to a study, open **Studies** in the main menu and either create a new study or edit an existing one, then select your project in the study's **Projects** field. See [Chapter 8 — Studies, Assay Data, and Publications](#chapter-8-studies-assay-data-and-publications) for the full workflow, including who can edit a study and how study members are managed.

Linking a project to a study has two effects:

- Specimens logged in the project can be added to the study.
- Members of the project become eligible to be added as study members.

> **Note:** A project cannot be unlinked from a study while that project's specimens are attached to the study, or while a study member would be left without eligibility. Details are in [Chapter 8, section 8.3](#83-linking-a-study-to-projects).

---

## 2.7 Importing Existing Data

If you intend to bulk-import participant, event, or specimen records from an external system, use the **Data Import** actions on the **Project Configuration** page (open the project via **Access**, then open **Project Configuration**). Three imports are available: **Import Subjects**, **Import Subject Events**, and **Import Specimens**.

Each import reads a CSV file. After you upload the file, BRIMS asks you to match each column of the file to the BRIMS field it should fill, so your source file does not need to use the same column headings as BRIMS. Values in the file must match the names used in your project configuration exactly — for example, the site, arm, event, and specimen type names — because rows containing unrecognised values fail validation.

> **Tip:** Make sure your sites, arms, events, specimen types, and project members are configured before running any import. See [Chapter 9, section 9.7](#97-importing-data) for the columns, validation rules, and steps for each import.

---

## 2.8 Linking a Project to Programmes

Programmes are team-level records that represent funded research programmes — for example, a grant-funded initiative that encompasses several related projects. A project can be linked to one or more programmes.

Programmes are created and managed at the **team** level, not the project level. To create or view programmes for your team, open **My Team** and select the **Programmes** tab. See Chapter 12 — Administration Guide for full details on creating and managing programmes.

You can select programmes when you create the project. To link an existing programme to an existing project:

1. Select **Access** on the project in the **Projects** list.
2. Open **Project Configuration** and select the **Programmes** tab.
3. Select **Attach** and choose the programme from the list.

To remove a programme link, use the **Detach** action on the programme row.

> **Note:** Only programmes belonging to the same team as the project are available to link. If the programme you need does not appear, confirm with your team administrator that it has been created at the team level.

---

## Setup Checklist

Use this checklist to confirm that the project is ready before research and laboratory staff begin work.

- [ ] Project created with a unique title and identifier, and the correct study design
- [ ] Subject ID prefix and digit count confirmed by the study team
- [ ] Label format selected from available label specifications
- [ ] Storage designation entered
- [ ] Default site reviewed; additional sites created as needed
- [ ] All study arms created and named
- [ ] Event definitions added to applicable arms, with offsets and windows confirmed
- [ ] Specimen types configured for primary and derivative samples as needed
- [ ] Labware records created with correct barcode format patterns
- [ ] Default Admin role reviewed; additional roles created to reflect team functions
- [ ] All project members added with appropriate roles and site assignments
- [ ] Substitute assignments configured where needed
- [ ] Programmes linked to the project, if applicable
- [ ] Studies that will use this project's specimens linked to the project (optional; see [section 2.6](#26-linking-the-project-to-studies))

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

Subject records are created manually using the **Generate Subjects** action on the Subjects list. The button is greyed out if you do not have permission to manage subjects or are not a member of the project.

To generate subjects, navigate to the **Subjects** list and select **Generate Subjects**. You will be asked to choose an arm (the list contains only arms that have **Manual Enrolment** enabled) and specify how many subject records to create (between 1 and 20 at a time). BRIMS will create the requested number of records, each with a subject ID pre-assigned using the project's configured prefix and digit format.

The **site** and **manager** for each generated subject are assigned automatically from your project membership record — the site you are assigned to and your user account become the default site and manager for all subjects you generate. These values cannot be changed by editing the subject record, so generate subjects while signed in as the person who will manage them, and from the correct site.

These records appear in the **Subjects** list with a status of **Generated**. A Generated subject has a subject ID already assigned, and subject events have been created for all of the arm's event definitions with a status of **Pending**. No participant details have yet been confirmed — the record acts as a placeholder until a real participant is enrolled into it.

> **Important:** Do not generate more subject records than you need. Generated records with unallocated IDs create gaps if they are never used. Generate a small batch at a time and add more as needed. Always use the **Enrol** action on an existing Generated record rather than creating a new one from scratch — doing so would cause identifier conflicts and gaps in the numbering sequence.

---

## 3.3 Enrolling a Participant

### 3.3.1 Step-by-step

1. Navigate to the **Subjects** list in the project navigation.
2. Locate a record with status **Generated**. Use the search or filter tools if the list is long.
3. Use the **Enrol** action button on the row. You do not need to open the record first — the enrolment form is accessible directly from the Subjects list.
4. The enrolment form will open. Complete the fields carefully.
5. Review the automatically generated subject ID to confirm it matches the expected project format.
6. Save the record.

![The Subject enrolment form accessed by clicking the Enrol action button.]()

### 3.3.2 Enrolment form fields

| Field | What to enter |
|---|---|
| **First Name** | The participant's first name. |
| **Last Name** | The participant's last name. |
| **Enrolment Date** | Required. The date the participant was formally enrolled. It defaults to today's date. This date is used to calculate event schedules. |
| **Address** | Optional. Enter one address component per line (for example, street address, town, postcode). |

The participant's arm, site, and manager are pre-set from the **Generate Subjects** step and are displayed on the record. They are not editable through the enrolment form. If the arm, site, or manager is incorrect, do not proceed with enrolment — contact your project administrator to resolve the discrepancy first, as the arm determines which events will be scheduled.

> **Tip — Enrolment date and event scheduling:** The enrolment date is used as the baseline from which all scheduled events are calculated. For example, if a follow-up event has an offset of 30 days, it will be scheduled 30 days from the enrolment date. Enter the true enrolment date rather than today's date unless these are the same. A wrong enrolment date will cause all follow-up windows to shift incorrectly, which is difficult to resolve later.

Once enrolled, the participant's status changes to **Enrolled**. BRIMS uses the enrolment date to calculate the event date and the earliest and latest allowed dates for each subject event. If the arm's first event has an offset of zero days, it is automatically marked as logged at this point.

If the project is linked to REDCap, enrolling a participant also creates a record for them in REDCap (see [Chapter 11](#chapter-11-redcap-integration)). The enrolment fails if your account does not have a REDCap token for the project.

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

Open the subject's record and use the **Edit** action at the top of the page. Edit is available for **Enrolled** and **Dropped** subjects, to the subject's manager or their substitute, and requires permission to manage subjects.

Only the participant's name, enrolment date, and address can be edited. The site, manager, and arm are shown on the form but cannot be changed there; the arm can be changed only with **Switch Arm** (see section 3.6).

> **Caution:** Changes to a participant record that is already associated with events or specimens, including switching arm, can affect follow-up scheduling, specimen tracking, and reporting. Always confirm with your data manager before making structural changes.

---

## 3.6 Switching a Participant's Arm

If a participant moves from one study cohort to another — for example, from a control arm to a treatment arm — use the **Switch Arm** action on the subject record. The action is available for **Enrolled** subjects whose current arm has switchable arms configured (see [Chapter 2, section 2.3](#23-creating-study-arms)), and only those arms can be chosen. The dialog asks for the new arm and a **New Arm Baseline Date**, which defaults to today and cannot be in the future or earlier than the current baseline date.

When an arm switch occurs, BRIMS:

1. Cancels all pending, primed, or scheduled events from the current arm
2. Records the previous arm and baseline date for audit purposes
3. Assigns the participant to the new arm
4. Creates a new set of subject events based on the new arm's event definitions

The arm baseline date you enter is used as the new baseline for calculating the events in the new arm. On a REDCap-linked project, switching arm also creates a REDCap record for the subject in the new arm.

> **Caution:** Arm switching is a significant action. Events that have already been logged in the previous arm are not affected, but all pending events are cancelled and cannot be recovered. Confirm that the switch is correct and approved before proceeding.

After a switch, the participant's previous arm name and baseline date remain visible on the subject record in the **Previous Arm** section, and the **Previous Arm** and **Previous Arm Baseline Date** columns appear in the Subjects list. This provides a quick audit reference without needing to open the individual record.

If an arm switch was made in error, use **Revert Arm Switch** to return the participant to their previous arm. This action is available on an enrolled subject who has a previous arm, and only the most recent switch can be reverted. Reverting deletes **all** of the subject's events in the current arm (including any already logged), restores the previous arm and its baseline date, and resets the cancelled events in the previous arm to **Pending**.

---

## 3.7 Dropping a Participant

Use the **Drop Subject** action, available on the record of an **Enrolled** subject, to indicate that a participant has withdrawn or should no longer receive active follow-up.

A dropped participant's record remains in the system and can be reviewed. The participant stays in the Subjects list (use the **Status** filter to show or hide dropped subjects) but is removed from the Label Queue and from the generated follow-up schedule, and new iterations of repeatable events can no longer be added. Dropping a participant does not cancel their pending events, so check their Subject Events if any need to be closed.

If a participant who was dropped needs to be reinstated, use the **Re-Instate Subject** action on the subject record.

> **Tip:** Always record a participant as Dropped rather than attempting to delete their record. A dropped record preserves the enrolment, event, and specimen records. Deletion is not the appropriate action and may not be permitted depending on your role.

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

![A subject record view showing enrolment details, the Subject Events section, and arm assignment history.]()

From a subject record you can review:

- Enrolment details and the subject ID
- Current site, arm, and arm baseline date
- Previous arm and baseline date — displayed in a **Previous Arm** fieldset when the participant has previously switched arms
- The full list of scheduled and completed events (in the **Subject Events** section)

This history is useful when following up on overdue events, resolving data queries, or preparing for a participant's next visit.

---

## 3.10 The Label Queue

When subjects are generated, BRIMS queues the barcode labels for the arm's first event (the event with an offset of zero), so that labels can be printed before a participant is formally enrolled. Labels for other events stay at a label status of **Pending** until they are queued, which a user with permission can do by changing the **Label Status** of the event (see [Chapter 4, section 4.6](#46-updating-an-event-status-directly)). When a participant switches arm, labels for the new arm's autolog events are queued. Queued labels are managed from the **Label Queue**, which appears in the project navigation and shows all subject events with a queued label status for the participants you manage directly or cover as a substitute.

The Label Queue table includes columns for subject ID, first name, surname, manager, arm, event, iteration, and event date. You can search by subject ID, name, manager, or event, and an arm filter is available to narrow the queue. Participants who have been dropped are not shown.

### 3.10.1 Printing labels

| Action | How to access | What it does |
|---|---|---|
| **Print All** | Header button | Opens all queued labels for printing in a new browser tab |
| **Print** | Row action | Opens labels for a single event in a new browser tab |
| **Print Selected** | Bulk action (checkboxes) | Opens labels for the selected rows in a new browser tab |

The **Print All** action passes the project's configured label format to the print route automatically, so labels are printed in the correct format for the project.

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
| Edit a participant | Subject record → **Edit** |
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

In BRIMS, follow-up activities are managed through **events**. An event represents a single study activity for a participant — such as a screening visit, a monthly follow-up, or a specimen collection point. Subject events are created automatically from the arm's event definitions when a subject is generated, are dated when the participant is enrolled, and are updated as activities are completed or missed.

Keeping event records current is essential. Overdue or unrecorded events affect follow-up reports and, where events are linked to specimen collection, can create gaps in the specimen record.

---

## 4.1 Understanding Events

### 4.1.1 Event definitions and subject events

BRIMS uses two layers of event records:

- **Event definitions** define events set up by the project manager within each study arm (see Chapter 2 — Setting Up a Project). The definitions include information about the timing windows of scheduled events and number of labels required.
- **Subject events** are the individual events assigned to a specific participant when their record is generated or when their arm changes. Each subject event is selected from the event definitions for the relevant arm and dated based on the participant's enrolment date.

You will work almost exclusively with subject events in day-to-day follow-up.

### 4.1.2 How event dates are calculated

When a participant is enrolled, BRIMS uses their enrolment date as the baseline and calculates a scheduled date for each event using the arm's offset settings.

For example, if an event has an offset of 14 days, it will be scheduled 14 days from the enrolment date. The ante and post windows around that date define the range within which the event is considered on time.

> **Tip:** Understanding this date logic helps you interpret the event list correctly. An **Event Date** showing in red in the Subject Events table means the latest allowed date (**Max Date**) has passed and the event is still unrecorded. This is not a system error — it is an operational signal that the activity needs attention.

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
| **Event** | The name of the scheduled activity |
| **Status** | The current status of this event instance |
| **Event Date** | The scheduled date for this event |
| **Min Date** | The earliest date the event can be recorded as on time |
| **Max Date** | The latest date the event can be recorded as on time |
| **Log Date** | The date the event was actually recorded, if logged |
| **Repeatable** | Whether additional iterations can be added |
| **Iteration** | The iteration number (for repeatable events) |
| **Event Order** | The sequence position of this event within the arm |
| **Label Status** | The current label printing status for this event. Can be updated directly by users with the appropriate permission (see section 4.6). |

The **Event Date** is shown in bold red when the event is still unlogged (Pending, Primed, or Scheduled) and its **Max Date** has passed. These events require prompt attention.

---

## 4.3 Event Statuses

Each subject event has a status that reflects where it is in its lifecycle.

| Status | Meaning |
|---|---|
| **Pending** | The event is scheduled for the future and is not yet ready to be logged. |
| **Primed** | The event is approaching its scheduled date and is ready to be actioned. |
| **Scheduled** | The event is due — the **Log Event** action will be available. |
| **Logged** | The event was recorded on time, within the acceptable date window. |
| **Logged Late** | The event was recorded after the acceptable post-window date. BRIMS does not set this automatically; a user with permission sets it directly (see section 4.6). |
| **Missed** | The event was not completed and was recorded as missed. |
| **Cancelled** | The event was cancelled (typically due to an arm switch). |

Events become **Scheduled** when the weekly schedule is generated — **Generate Schedule** in the project menu opens the week's follow-up schedule as a document and moves the events that fall due that week from Pending or Primed to Scheduled for the participants you manage or cover as a substitute. A user with permission can also set the status directly (see section 4.6). BRIMS does not set **Primed** automatically.

> **Tip:** The **Log Event** action only appears on events with a **Scheduled** status. If an event is still **Pending** or **Primed**, it is not yet ready to log. If you need to record an outcome for an event that has passed its window, update the status to **Missed** directly in the status column if you have permission, or contact your data manager.

---

## 4.4 Recording an Event Outcome

When a participant completes a scheduled study activity, record the outcome in BRIMS as soon as possible.

To log an event:

1. Open the participant's record and navigate to the **Subject Events** section.
2. Find the event with status **Scheduled**.
3. Select the **Log Event** action on that row.
4. Enter the **Log Date** — the date the activity actually took place. It cannot be in the future or earlier than the participant's arm baseline date.
5. Select the **Event Status**: either **Logged** (completed) or **Missed** (not completed).
6. Confirm and save.

![The Log Event action dialog showing the Log Date field and Event Status selection.]()

BRIMS will update the event status to the one you selected, record the log date, and mark the event's labels as generated. BRIMS does not compare the log date with the event window: if the activity took place outside the acceptable window, ask a user with permission to set the status to **Logged Late** (see section 4.6).

> **Tip — Recording missed events promptly:** If a participant did not attend a scheduled visit, record the event as **Missed** as soon as this is confirmed rather than leaving it as **Scheduled**. This keeps operational reports accurate and ensures that follow-up planning reflects the true state of each participant's schedule.

> **Tip — Log date accuracy:** Enter the date the activity actually occurred, not the date you are entering the record. If there is a delay between the visit and data entry, the log date should still reflect the real activity date. The log date is stored with the event and is available when you review follow-up.

---

## 4.5 Repeatable Events

Some events are configured as **repeatable**, meaning additional iterations can be scheduled after the first one has been completed.

A repeatable event will show a **New Iteration** action when:

- The event is repeatable (confirmed by the tick in the Repeatable column)
- The event has not been cancelled
- The current iteration is the most recent one for that event
- The participant is still enrolled

To add a new iteration:

1. Find the repeatable event in the Subject Events list.
2. Select the **New Iteration** action.
3. Enter the date for the new iteration.
4. Confirm.

The new iteration is created with a status of **Pending** and appears in the Subject Events list. It can be logged once its status is **Scheduled** (see section 4.3).

> **Tip:** Repeatable events are typically used for ongoing follow-up visits that recur at defined intervals — for example, monthly clinical reviews or quarterly specimen collections. If you are unsure whether a new iteration should be added, check your study protocol or ask your project manager.

---

## 4.6 Updating an Event Status Directly

For users with the appropriate permissions, event status can be updated directly in the status column of the Subject Events table — for example, to mark an event as **Missed** when the **Log Event** button is not available.

Select the status dropdown in the row you want to update and choose the new status.

The **Label Status** column can be updated in the same way. Label status tracks whether a label for the event has been queued, printed, or generated. If a label status needs correcting outside the normal Label Queue workflow, an authorised user can update it directly here.

> **Caution:** Direct status updates bypass the log date confirmation step. Only use this approach when instructed by your data manager, and always record the reason for the change in your study records. Direct changes are not recorded in an audit trail, so keep your own record of who changed the status and why.

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
- Specimen types and labware have been configured for the project (see [Chapter 2 — Setting Up a Project](#chapter-2-setting-up-a-project))
- You have the physical barcodes ready for scanning

If the participant is not yet enrolled or the event has not been created, logging cannot proceed correctly. Return to [Chapter 3 — Enrolling and Managing Participants](#chapter-3-enrolling-and-managing-participants) or [Chapter 4 — Recording Events and Follow-up](#chapter-4-recording-events-and-follow-up) if those steps are incomplete.

---

## 5.2 Primary Specimen Logging

Primary specimens are samples collected directly from a participant. They are logged through a dedicated two-stage workflow accessed from **Log Primary Specimens (2-Stage)** in the project navigation.

### 5.2.1 Stage 1 — Scan the Project Subject Event Barcode

The first stage identifies the participant and event that the specimens belong to.

1. Navigate to **Log Primary Specimens (2-Stage)**.
2. In the **Project Subject Event Barcode** field, scan or enter the PSE barcode from the participant's label.
3. Press Enter, or select **Load Specimen Form**, to confirm.

BRIMS will validate the barcode and load the participant and event details. Confirm that the participant name and event shown on screen match the physical sample before continuing.

> **Tip:** The PSE barcode (Project Subject Event barcode) encodes the participant and event in a single scannable code. If the barcode is not recognised at this stage, check that the barcode matches the correct project and that the subject event has been correctly scheduled. A barcode from a different project will not be accepted. A PSE barcode has the form `ProjectID_SubjectID_SubjectEventID`, with numeric identifiers separated by underscores.

### 5.2.2 Stage 2 — Scan Specimen Barcodes and Enter Volumes

Once Stage 1 is confirmed, the form expands to show all primary specimen types configured for this project.

For each specimen type, BRIMS shows one barcode field for each expected aliquot. Use the **+** and **−** buttons to add or remove aliquot fields if needed.

1. Scan or enter the barcode for each aliquot. A barcode can be at most 20 characters and must be unique within the project.
2. Enter the volume. It is pre-filled with the specimen type's default volume and unit, and is required whenever a barcode has been entered.
3. Confirm all fields before submitting.

If some aliquots for this event have already been logged, they are shown read-only so that you can log the remaining aliquots later.

![The primary specimen logging form at Stage 2, showing specimen type groups, barcode fields per aliquot, and volume entry fields.]()

> **Tip:** Specimen types are grouped on screen according to the project's specimen group settings. Work through each group systematically, scanning barcodes onto the physical label as you log each one. If a barcode does not match the format defined by the labware settings, BRIMS will reject it with a validation error — check the physical label and re-scan before assuming there is a system problem.

Select **Save Specimens** when all specimen types have been completed, or **Start Over** to return to Stage 1. BRIMS will create a specimen record for each logged aliquot, linked to the participant and event confirmed in Stage 1. Newly logged specimens are given a status of **Logged**.

Saving is all-or-nothing: if any specimen cannot be saved, none of the specimens are logged and a **Failed to log primary specimens** message is shown.

---

## 5.3 Derivative Specimen Logging

Derivative specimens are samples that have been processed or derived from a primary specimen — for example, plasma separated from a whole blood specimen. They are logged through **Log Derivative Specimens** in the project navigation.

Derivative logging is also a multi-stage process:

### 5.3.1 Stage 1 — Select the parent specimen

Use the **Select by** toggle to choose how to identify the parent specimen:

- **Parent Specimen:** Enter the barcode of the primary specimen this derivative was processed from. The barcode must exist in the current project.
- **Project Subject Event:** Scan the Project Subject Event barcode and then choose the correct parent specimen from the lists of eligible specimens linked to that event, grouped by specimen type. Only specimens whose type is the parent of at least one derivative type are listed.

### 5.3.2 Stage 2 — Scan derivative barcodes and enter volumes

Once the parent specimen is confirmed, the form shows all of the derivative (non-primary) specimen types configured for this project. For each derivative type, the form works in the same way as primary logging (see section 5.2.2), including the **+** and **−** buttons and the **Save Specimens** and **Start Over** buttons:

1. Scan or enter the barcode for each aliquot.
2. Enter the volume. It is required whenever a barcode has been entered.
3. Confirm all fields and select **Save Specimens**.

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

Most statuses are set by BRIMS workflows: **Logged** by specimen logging, **In Storage** by storage allocation or **Log Return**, **Logged Out** and **Used** by the status actions described in [section 5.5](#55-the-specimens-list), **Pre Transfer** when a specimen is added to a manifest, **Transferred** when the manifest is shipped, and **Received** when the manifest is received. **Unassigned**, **Registered**, **Reassigned**, and **Lost** are not set automatically by any current workflow; they can only be set by editing the specimen record.

> **Tip:** Follow your project's standard operating procedures when deciding which status transition is appropriate. For most laboratory workflows, specimens move through Logged → In Storage → Logged Out → Used. The other statuses (Transferred, Received, Lost, etc.) are triggered by specific handling events and should not be applied without a corresponding real-world action.

---

## 5.5 The Specimens List

The **Specimens** list (accessible from the project navigation) shows the specimens logged in the current project. Project administrators (members with the project's Admin role) see the specimens at every site; other members see only the specimens at their own site.

By default the list shows the barcode, subject ID, arm, event, specimen type, site, status, and logged date. Further columns — origin site, aliquot, logged-by user, parent barcode, and created and updated dates — can be switched on from the column toggle.

Use the search fields at the top of each column to find specimens by barcode, subject ID, type, site, or status. The list can be sorted by logged date, created date, and updated date.

![The Specimens list showing search fields per column and the bulk action menu.]()

### 5.5.1 Updating specimen status by barcode entry

The **Update Specimen Status** button at the top of the Specimens list provides a way to update the status of multiple specimens by entering their barcodes, without needing to locate and select them in the table. This is useful when working from a physical list of barcodes — for example, after retrieving a batch of specimens from storage.

Click **Update Specimen Status** to expand the action group and choose one of three options:

| Action | What it does |
|---|---|
| **Log Specimens as Used** | Marks the entered specimens as **Used**. Enter barcodes comma-separated or one per line. |
| **Log Specimens Out of Storage** | Marks the entered specimens as **Logged Out**. Enter barcodes comma-separated or one per line. |
| **Log Specimens Returned to Storage** | Marks the entered specimens as returned to storage (**In Storage**). Includes an **Increment thaw count?** toggle, which is on by default — leave it on if the specimens were thawed during removal. |

The status actions follow the same rules whether you use barcode entry or the bulk actions, and the specimens must be at your own site:

| Action | Specimen must currently be |
|---|---|
| **Log as Used** | Logged, In Storage, Logged Out, or Received |
| **Log Out** | In Storage |
| **Log Return** | Logged Out. The specimen returns to the status it had before it was logged out. |

> **Important:** The **Update Specimen Status** action processes barcodes as an all-or-nothing operation. If any barcode in the submission fails validation, the entire submission is rejected and no specimens are updated. Correct all errors and resubmit the full list. The error notification will identify which barcodes failed and why.

### 5.5.2 Bulk actions

Select specimens using the checkboxes and use the bulk action menu to apply the following actions to a group of selected records:

| Action | What it does |
|---|---|
| **Log as Used** | Sets the status of selected specimens to **Used**. Requires confirmation. |
| **Log Out** | Sets the status of selected specimens to **Logged Out**. Requires confirmation. |
| **Log Return** | Returns specimens to storage (sets the status back to **In Storage**). Optionally increments the thaw count. Requires confirmation. |
| **Export** | Exports the selected records as a CSV file. An **Export** button at the top of the list exports all records. |
| **Delete** | Deletes the selected records. Requires permission to delete specimens. A specimen that has derivative specimens cannot be deleted until its derivatives have been deleted. Use with caution — this action is not easily reversible. |

> **Caution — bulk status actions:** These actions update specimen status across multiple records at once. Verify that all selected specimens are genuinely ready for the action before confirming. An accidental bulk status change on the wrong specimens can be difficult to correct, particularly if the specimens are already linked to storage or study records.

> **Tip — Log Return with thaw count:** When using **Log Return** to record that specimens are going back into cold storage after being briefly removed, you will be asked whether the thaw count should be incremented. Increment the thaw count if the specimen was genuinely thawed during the removal period. This preserves an accurate thermal history for the specimen, which matters for downstream assay validity.

### 5.5.3 Viewing a specimen record

Click on a specimen row (or use the **View** action) to open its full detail page. The view page displays the specimen's barcode, subject ID, arm, event, iteration, specimen type, parent specimen, site, origin site, status, aliquot, volume, thaw count, and logging details, and also shows an **Audit Logs** table at the bottom of the record. The specimen's storage location is not shown here; it is listed in the storage allocation report (see [Chapter 6, section 6.3.1](#631-printing-a-storage-allocation-report)).

The Audit Log records the status changes made through BRIMS workflows — storage, use, removal and return, and manifest transfers:

| Column | What it shows |
|---|---|
| **Previous Status** | The status before the change |
| **New Status** | The status after the change |
| **Changed By** | The user who made the change |
| **Changed At** | The date and time of the change |

Entries are not created when a specimen is first logged, or when its status is changed by editing the specimen record, so the history starts at the first workflow change. Use the Audit Log to investigate unexpected status values or to provide traceability records when required.

---

## 5.6 Editing a Specimen Record

Individual specimen records can be edited from the Specimens list using the **Edit** action on a row, which opens an edit dialog. Editing requires permission to manage specimens. The barcode, arm, event, and iteration are shown but cannot be changed.

The edit form allows updates to:

- Specimen type, site, and origin site
- Status
- Aliquot number, volume, and volume unit
- Thaw count
- Logged-by user and log date
- Parent specimen

> **Caution:** Changes made through the edit form are not recorded in the specimen's Audit Log. Editing specimen status directly should be done carefully and only when there is a clear operational reason. Where possible, use the logging workflows and bulk actions described above rather than manually editing the status field, as these workflows maintain the correct audit trail and apply the associated business rules automatically.

---

## 5.7 Linking Specimens to Events and Participants

Specimens are linked to a participant and subject event at the point of logging. This link is the foundation of specimen traceability — it connects each physical sample to the research record it belongs to.

If a specimen was logged without the correct event link, or if the link needs to be reviewed, open the specimen record and check the **Arm**, **Event**, and **Iteration** fields. These cannot be changed from the Specimens list, so contact your data manager if the link is wrong.

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
| **Minus 80** | Ultra-low temperature freezer storage (−80 °C) |
| **Liquid Nitrogen** | Cryogenic vapour or liquid nitrogen storage |
| **Biorepository** | General controlled-temperature biorepository storage |

The storage type is defined at the unit definition level by an administrator. You do not need to select a storage type during allocation. BRIMS matches each specimen type to storage using the **Storage Specimen Type** set on the specimen type (see [Chapter 2, section 2.4.2](#242-specimen-types)): specimens are placed in the virtual units of the same project that have been set up for that storage specimen type.

### 6.1.2 Storage hierarchy

Storage locations in BRIMS follow a hierarchy:

- **Unit definitions** describe a model of freezer or tank: its storage type, orientation, and sections, with the rows, columns, boxes, and positions that each section contains
- **Physical units** are the actual, named freezers or tanks that exist in the laboratory, each based on a unit definition
- **Virtual units** are created on a physical unit and assign a range of its racks and boxes to a project and a storage specimen type
- **Locations** are individual positions (rack, box, and position) within a virtual unit where a single specimen is placed

Administrators configure unit definitions, physical units, and virtual units from the Admin panel (see Chapter 12 — Administration Guide, section 12.3). Day-to-day laboratory work involves allocating specimens into the spaces that have been configured.

> **Tip:** If the allocation step reports that there is insufficient storage for a specimen type, this means the virtual units configured for that type have no free locations remaining. Contact your project administrator or laboratory manager to arrange additional storage configuration before attempting a new allocation.

---

## 6.2 Allocating Specimens to Storage

The allocation workflow places all eligible **Logged** specimens of the selected type into available storage locations in a single operation.

### 6.2.1 Step-by-step

1. Navigate to **Specimen Storage** and select **Allocate Storage**.
2. The form shows the specimen types that have a storage specimen type set and have Logged specimens at your current site, along with the count of specimens waiting to be stored.
3. Optionally enable **Allow allocation to previously used locations** if you want BRIMS to reuse locations that previously held a specimen.
4. Select the specimen types you want to allocate by ticking their checkboxes.
5. Select **Allocate** to run the allocation. BRIMS allocates all of the Logged specimens of each selected type at your site; you cannot choose a subset.

![The Allocate Specimen Storage page showing the reuse-locations toggle, the specimen type checklist with specimen counts, and available storage capacity indicators.]()

BRIMS will:

- Assign each eligible specimen to a free location
- Update the specimen status from **Logged** to **In Storage**
- Create a storage allocation record linked to the current user and timestamp

The allocation is all-or-nothing across the selected types: if it cannot complete, no specimens are allocated. On success, BRIMS reports how many specimens were allocated.

If a specimen type shows a warning about insufficient storage, that type will be disabled in the list and cannot be selected until additional storage capacity has been configured.

> **Tip — Reusing locations:** Enabling the reuse-locations option allows BRIMS to allocate specimens to positions that previously held a different specimen. This is appropriate in some protocols (e.g. where boxes are reused across batches) but may not be appropriate in others (e.g. where audit traceability requires location exclusivity). Discuss this setting with your laboratory manager before enabling it.

> **Tip — Allocation is site-specific:** The allocation page only shows specimens from your current project site. If you are managing specimens from multiple sites, each site's allocations must be run separately by a user assigned to that site.

---

## 6.3 Reviewing Storage Allocations

The **Specimen Storage** list shows all previous allocation events for the current project.

Each row represents a single allocation run and shows:

- The allocation ID
- The date and time of the allocation
- The user who ran the allocation
- The storage destination
- The number of specimens allocated in that run

![The Specimen Storage list showing a history of allocation events with date, user, destination, and specimen count columns.]()

### 6.3.1 Printing a storage allocation report

Each allocation record has a **Print** action that opens a storage allocation report (a PDF) in a new browser tab.

The report lists the specimen type, subject, event, aliquot, and barcode of each specimen, together with the specific location assigned to it (physical unit, virtual unit, rack, box, and position), providing a physical reference for laboratory staff to confirm and verify placement.

> **Tip:** Print the storage allocation report immediately after each allocation run and compare it against the physical arrangement in the freezer or storage unit. Resolving any discrepancy at this stage is far easier than trying to reconcile location records later.

---

## 6.4 Retrieving Specimens from Storage

When specimens are removed from storage — for use in an assay, for shipment, or for any other reason — their status should be updated promptly to reflect the real situation.

BRIMS does not have a dedicated specimen-by-specimen retrieval screen in the Specimen Storage area. Instead, use the bulk actions in the **Specimens** list to update specimen status:

- Use **Log Out** to record that specimens have been removed from storage (sets status to **Logged Out**)
- Use **Log as Used** to record that specimens have been consumed in an assay (sets status to **Used**)
- Use **Log Return** to record that specimens have been returned to storage after a brief removal (sets status back to **In Storage**, with the option to increment the thaw count)

Refer to [Chapter 5 — Logging Specimens](#chapter-5-logging-specimens) for instructions on using these bulk actions in the Specimens list.

> **Caution:** Always update the status when specimens are moved. If specimens are removed from storage without being logged out, the storage records will not reflect the real situation. This makes it more difficult to locate specimens later and creates inaccuracies in storage traceability reports.

---

## 6.5 Storage Traceability

BRIMS maintains a storage history for each specimen through the storage allocation records and specimen status changes. This means you can trace:

- When a specimen was placed in storage
- Which allocation run it belonged to
- Which physical location it was assigned to (shown in the storage allocation report)
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
3. Select the **Specimen Types** to include. Only specimen types configured for the current project are available. This selection filters which specimens can be added in the next step. If no specimen types are selected, no specimens will be eligible to add.

> **Tip:** The specimen type selection is the filter that controls which specimens will be eligible to add to the manifest. If you include the wrong type at this stage, you will need to remove all attached specimens before you can edit the selection.

4. Save the manifest.

The manifest is now in the **Open** state and is ready to have specimens attached. While a manifest is Open it can be edited, and it can be deleted. Deleting an Open manifest returns its specimens to the status they had before they were added.

---

## 7.3 Adding Specimens to a Manifest

With the manifest open, navigate to the **Specimens** section at the bottom of the manifest detail page.

1. Select **Select Specimens to Add**.
2. Find and select the specimens you want to include. The search only shows specimens that:
   - Are of a type included in the manifest
   - Have a status of **Logged**, **In Storage**, or **Received**
   - Belong to your current site

3. Confirm the selection.

The selected specimens are now listed in the manifest, and each specimen's status changes to **Pre Transfer**. Each specimen row shows the barcode, aliquot, subject ID, specimen type, arm, and event. A **Prior Status** column records what the specimen's status was when it was added to the manifest — this is preserved for reference after the transfer.

On an Open manifest you can also use **Detach** on a row, or the bulk **Detach** action, to remove specimens. A removed specimen returns to the status it had before it was added.

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

The manifest status changes to **Shipped**, and all specimens attached to the manifest have their status updated from **Pre Transfer** to **Transferred**. The manifest can no longer be edited.

> **Caution:** The **Ship the Manifest** action is only available when the manifest is **Open** and at least one specimen has been added. Confirm that your physical shipment is complete and the specimens are packed before triggering this action in BRIMS.

---

## 7.5 Receiving a Manifest

When a shipment arrives at the destination site, the receiving team should confirm receipt in BRIMS.

> **Important:** The **Receive the Manifest** action is only available to users whose project site assignment matches the manifest's **destination site**. If you cannot see the receive button, verify that you are logged in with the correct site assignment.

1. Navigate to **Manifests** and open the **Received Manifests** tab, which lists the manifests sent to your site.
2. Open the manifest with status **Shipped**.
3. Select **Receive the Manifest**.
4. Confirm the action.

The manifest status changes to **Received**. Individual specimens are updated to **Received** status, the received timestamp is recorded, and each specimen's site is changed to the manifest's destination site. The specimens then appear in the destination site's Specimens list and no longer appear in the sending site's list.

![The manifest detail page showing the Receive button on a Shipped manifest.]()

---

## 7.6 Reviewing and Importing Specimens on a Manifest

The manifest specimen list in BRIMS provides a searchable record of all specimens included in each shipment, with columns for barcode, subject, type, arm, event, prior status, received flag, and received timestamp.

While a manifest is Open, you can add specimens in bulk with **Upload Specimens** in the manifest's Specimens section. Upload a CSV file with a single **Barcode** column. The same eligibility rules apply as when adding specimens manually, except that specimens with a status of Received are rejected. Uploading adds specimens to the manifest only; it does not mark anything as received. Receipt is always confirmed with **Receive the Manifest**.

> **Tip:** If the specimens to ship are identified using barcode scanner software, uploading the list of barcodes avoids re-selecting each specimen manually.

---

## 7.7 Tracing a Shipment

Every manifest provides a traceable chain of custody:

- **User** (who created the manifest) and creation timestamp
- **Source site** (derived from the creator's site assignment)
- **Destination site**
- **Shipped date** (the date the manifest was shipped)
- **Received by** user and **Received date** (the date the manifest was received)
- A received timestamp for each individual specimen

The Manifests list has two tabs: **Sent Manifests**, which lists manifests from your site (and is where **New Manifest** is available), and **Received Manifests**, which lists shipped and received manifests addressed to your site. Use the status filter to quickly locate Open, Shipped, or Received manifests.

---

## 7.8 Exporting a Manifest

Once a manifest has been **Shipped** or **Received**, an **Export** action becomes available on the manifest detail page. This downloads a CSV file (columns: Subject, Barcode, Arm, Event, Sample Type, Aliquot, and Volume) containing the manifest's specimen records, which is useful for reconciliation at the receiving site or for archiving shipment records.

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

In BRIMS, a **study** is a team-level record that captures the context for a set of research analyses. A study belongs to your **team** rather than to a single project, and it can be linked to one or more of the team's projects. This allows one study to draw on specimens from several projects. Each study holds three types of related records:

- **Members** — the people who work on and manage the study
- **Specimens** — the physical samples allocated to the study, drawn from the projects the study is linked to
- **Assays** — records of measurements, analyses, or test runs performed using those specimens

Studies are accessed from **Studies** in the main menu — the menu that also contains **Projects** and **My Team** — and not from the navigation inside a project. A study is not scoped to the project you are currently working in.

---

## 8.1 Who Can See and Work With Studies

You can see a study if it belongs to your team, or if you have been added to it as a study member. Beyond this, what you can do with a study depends on your permissions and on your **study role**.

| Action | Who can do it |
|---|---|
| View studies | Users who have permission to view studies. Team administrators always have this permission. |
| Create a study | Users who have permission to manage studies (team administrators always do), subject to the membership rule in [section 8.2](#82-creating-a-study). |
| Edit a study's details, change its linked projects, or manage its members | **Study leads only.** Team administrators who are not a lead of the study cannot edit it. |
| Delete a study | Users who have permission to delete studies. Team administrators always have this permission. |
| Add specimens or assays | Follows your permissions, as set out in your project or team role. Specimens and assays cannot be changed while the study is locked. |

There are two study roles:

| Study role | Meaning |
|---|---|
| **Lead** | Can edit the study and manage its members. Every study must have at least one lead. |
| **Member** | Can work with the study but cannot edit it or manage its members. |

> **Tip:** If the **Edit** action is missing on a study you can otherwise see, check the Members section of the study. Only members with the **Lead** role can edit the study.

---

## 8.2 Creating a Study

1. Select **Studies** in the main menu, then select **New Study**.
2. Complete the study form:

| Field | Required | Notes |
|---|---|---|
| **Title** | Yes | A descriptive name for the study. Must be unique within your team. |
| **Identifier** | Yes | A unique code for this study; must be unique across all studies in BRIMS, not just within your team |
| **Projects** | No | The team projects the study is linked to. Specimens can only be added from the projects selected here. |
| **Description** | No | Free text describing the study's purpose |
| **Submission Date** | No | Date the study was or is expected to be submitted |
| **Public Release Date** | No | Date the study data is expected to become publicly available |
| **Locked** | No | When enabled, specimens cannot be added to or removed from the study and its assays cannot be added, edited, or deleted |

3. Save the study.

BRIMS then assigns the study to your team automatically and adds you to it as its **Lead**. You are taken to the new study's record, where you can add further members, specimens, and assays.

> **Note — Who can be a study member:** Study members must be either a **team administrator** of the owning team or a **member of at least one of the linked projects**. Because you become the study's lead, you must meet this condition yourself. If you are not a team administrator, select at least one project you belong to, otherwise the form will not save.

> **Caution — Locked studies:** Enabling the **Locked** toggle makes the study's specimens and assays read-only. Only lock a study when all data entry is complete. Locking applies to specimens and assays simultaneously; there is no partial lock. Discuss with the study lead or project manager before locking. The toggle can be switched off again by a study lead.

> **Tip — Identifier uniqueness:** The identifier must be unique across all studies in the BRIMS system, not just within your team. Use a structured naming convention that includes your team or project code to avoid conflicts.

---

## 8.3 Linking a Study to Projects

To change the projects a study is linked to, open the study, select **Edit**, and update the **Projects** field. Only a study lead can do this.

A project cannot be removed from a study if either of the following applies:

- **Specimens from that project are attached to the study.** Detach those specimens first (see [section 8.5](#85-adding-specimens-to-a-study)).
- **Removing the project would leave a study member without eligibility.** A member who is neither a team administrator nor a member of another linked project would no longer be eligible. Remove that person from the study first (see [section 8.4](#84-managing-study-members)).

BRIMS shows a message naming the projects or members that are blocking the change.

> **Tip:** Linking a study to an additional project makes that project's specimens available to the study and makes the project's members eligible to be added as study members.

---

## 8.4 Managing Study Members

Open a study and select the **Members** section to see who works on it. The table shows each member's name, study role, team, and email address.

| Action | What it does |
|---|---|
| **Attach** | Adds a member to the study. Choose the user and a study role (**Lead** or **Member**). The list contains only eligible users: team administrators of the owning team and members of the linked projects. |
| **Edit** | Changes a member's study role. |
| **Detach** | Removes a member from the study. |

These actions are available to study leads only; other users see the member list without any actions.

A study must always have at least one lead. You cannot detach the only lead, or change their role to **Member**. To hand over leadership, first give another member the **Lead** role.

---

## 8.5 Adding Specimens to a Study

Specimens are linked to a study through the **Specimens** section at the bottom of the study record. The table lists each attached specimen's barcode, event, specimen type, site, status, aliquot, volume, and logging details.

### 8.5.1 Attaching specimens one by one

1. Open the study and go to the **Specimens** section.
2. Select **Attach**.
3. Optionally narrow the list using **Filter by Specimen Type** and **Filter by Site**.
4. Select one or more specimens by barcode.
5. Confirm.

The list includes only specimens from the projects the study is linked to, and excludes specimens already attached to the study.

### 8.5.2 Importing specimens

To add many specimens at once, use **Import** in the Specimens section and upload a CSV file with a **barcode** column. Each barcode must be at most 20 characters, must belong to a specimen in one of the study's linked projects, and must not already be attached to the study. Rows that fail validation are reported in the completion notification; valid rows are still imported.

### 8.5.3 Removing specimens and exporting

Use **Detach** on a row, or select several rows and use the bulk **Detach** action, to remove specimens from the study. Use **Export Specimens** to download the study's specimen list (see [Chapter 9, section 9.4](#94-exporting-data)).

> **Tip:** If the specimen you need is not available, check that it has been logged, that it belongs to a project the study is linked to, and that it is not already attached to the study.

> **Caution:** If the study is locked, specimens cannot be attached, imported, or detached.

---

## 8.6 Recording Assays

Assays capture the analytical measurements or results associated with the study. Each assay record represents a distinct analysis type or technology platform.

### 8.6.1 Adding an assay

1. With the study open, navigate to the **Assays** section.
2. Select **New Assay**.
3. Complete the assay form:

| Field | Required | Notes |
|---|---|---|
| **Name** | Yes | A descriptive name for this assay run (up to 100 characters) |
| **Assay Definition** | Yes | Select from your team's list of assay definitions. The definition determines which additional fields will appear below. |
| **Technology Platform** | Yes | The instrument, platform, or technology used (up to 50 characters) |
| **URI** | No | A link to an external resource or data file |
| **Location** | No | Where the assay data is physically stored or filed |

After selecting an **Assay Definition**, additional fields may appear in the form. These are defined as part of the assay definition configuration and will vary by assay type. They may include text fields, date pickers, single-choice (radio) questions, multi-select checkboxes, or dropdown selectors.

4. Complete all required additional fields and save.

![The assay form showing the core fields and a set of additional fields defined by the selected assay definition.]()

### 8.6.2 Assay definitions

Assay definitions are team-level configurations that specify the structure of an assay record — the types of metadata fields required. They are managed from the **Assay Definitions** tab of your team's page (**My Team**) and are available for use in all of the team's studies.

If the assay definition you need does not exist, ask your team administrator to create it.

### 8.6.3 Editing and deleting assays

Use **Edit** on an assay row to change its details, and **Delete** to remove it. Deleting an assay also deletes all of its attached data files from the file store. Both actions are hidden while the study is locked.

---

## 8.7 Assay Files

Assay records can have data files attached, which are uploaded to the configured file store (typically S3-compatible cloud storage).

### 8.7.1 Uploading files

1. In the **Assays** section, select **View** on the assay row.
2. In the **Assay Files** area at the bottom of the assay window, drag files into the drop zone or select them.
3. Wait for the upload to finish. Uploads are resumable: you can pause, resume, or cancel an upload in progress, and if an upload was interrupted, an **Incomplete Uploads Found** notice offers to resume or discard it.

### 8.7.2 Downloading and deleting files

- In the **Uploaded Files** list of the assay window, select a file name to download that file to your computer.
- Select **DELETE** beside a file name to remove that file from the assay and from the file store.
- Use **Download All Files** on the assay row to generate a text file of temporary download links for every file on the assay. You choose how long the links stay valid (1 to 7 days).

> **Tip:** Assay files depend on the connected file store. If the file store is temporarily unreachable, BRIMS shows a **Storage Unavailable** notice. Try again later.

---

## 8.8 Reviewing Your Studies

The **Studies** list shows the studies you can see, with columns for identifier, title, the number of specimens, the number of assays, public release date, and the **Specimen Lock** status. Additional columns for the created and updated dates can be switched on. The list can be searched by identifier or title.

Click **View** on a study to open its full record. The study record shows its title, identifier, linked projects, description, dates, and lock status, followed by the **Members**, **Specimens**, and **Assays** sections.

---

## 8.9 Publications

Publications are project-level records that capture bibliographic information about research outputs from the project — such as journal articles, preprints, or conference proceedings that report findings from the study.

Publications are accessed from **Publications** in the project navigation.

### 8.9.1 Creating a publication record

1. Navigate to **Publications** and select **New Publication**.
2. Complete the publication form:

| Field | Required | Notes |
|---|---|---|
| **Title** | Yes | The full publication title. Supports markdown formatting. |
| **Authors** | Yes | Add one row per author, using **Author Name**. |
| **Publication Status** | Yes | Select the current status: Draft, Submitted, or Published. |
| **PubMed ID** | Required if Published | The PubMed identifier (PMID) for the article (7–8 digits). |
| **DOI** | Required if Published | The digital object identifier for the article. |
| **Publication Year** | Required if Published | The year the article was published (four digits). |

3. Save the record.

> **Tip:** Create a publication record in **Draft** status as soon as a manuscript is in preparation. Update the status and add the PubMed ID and DOI once the article is accepted and published. This keeps the project's output record current without requiring fields that are not yet available.

### 8.9.2 Publication statuses

| Status | Meaning |
|---|---|
| **Draft** | The manuscript is in preparation and has not yet been submitted for publication |
| **Submitted** | The manuscript has been submitted to a journal or repository |
| **Published** | The article has been published |

Publication status does not restrict editing of the record. Unlike the **Locked** toggle on a study, changing the publication status does not prevent further updates to the publication record.

### 8.9.3 Reviewing publications

The **Publications** list shows all publications for the current project, with columns for title, authors, PubMed ID, DOI, publication year, and status.

---

## Summary

| Task | Where to go |
|---|---|
| Create a new study | **Studies** (main menu) → **New Study** |
| Change the projects a study is linked to | **Studies** → open the record → **Edit** → **Projects** |
| Add or remove a study member | **Studies** → open the record → **Members** section |
| Add specimens to a study | **Studies** → open the record → **Specimens** section → **Attach** or **Import** |
| Add an assay to a study | **Studies** → open the record → **Assays** section → **New Assay** |
| Upload or download an assay file | **Studies** → open the record → **Assays** section → **View** on the assay row |
| Download links for all of an assay's files | **Studies** → open the record → **Assays** section → **Download All Files** |
| Lock a study | **Studies** → open the record → **Edit** → enable **Locked** |
| Review all studies | **Studies** list |
| Create a publication record | **Publications** (project navigation) → **New Publication** |
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
| **Participants (Subjects)** | Subject ID, first name, last name, manager |
| **Specimens** | Barcode, subject ID, arm, event, type, site, origin site, status, logged by, logged date, parent barcode |
| **Manifests** | Created date, user, source site, destination site, shipped date, received by, received date |
| **Studies** | Identifier, title |

> **Tip:** On the Specimen list, you can search by barcode to retrieve an individual specimen record instantly. If you are investigating a specific tube, this is the fastest route to the relevant record and its linked event and participant.

---

## 9.2 Filtering Lists

Some lists support dropdown filter controls that narrow the list by status or category. Filters are available on:

- **Participants** — filter by status (Generated, Enrolled, Dropped), site, or manager
- **Manifests** — filter by status (Open, Shipped, Received)
- **Specimens** — there are no dropdown filters; type a status (for example `Logged`) into the Status column's search box instead

Apply a filter by selecting the relevant option. Clear filters by returning the filter to its default position.

> **Tip — Filter before you act:** When preparing to run a bulk action on specimens (such as Log as Used or Log Out), apply filters first to narrow the list to the relevant records before selecting them. This reduces the risk of accidentally including the wrong specimens in a bulk operation.

---

## 9.3 Operational Review Patterns

Use the filtering and search capabilities to support day-to-day operational review. The following patterns are particularly useful:

### 9.3.1 Review outstanding follow-up events

Navigate to a **Subject** record and view the **Subject Events** section. Events highlighted in red are overdue and awaiting logging. You can also scan the event list for Scheduled events that have passed their expected date.

See [Chapter 4 — Recording Events and Follow-up](#chapter-4-recording-events-and-follow-up) for more detail.

### 9.3.2 Review specimens awaiting storage

Navigate to **Specimens** and search the **Status** column for `Logged`. (This search also matches Logged Out, so check the status shown on each row.) Specimens with a status of **Logged** have been collected but not yet allocated to storage. Use this view to identify batches ready for the next storage allocation run.

### 9.3.3 Review incoming shipments awaiting receipt

Navigate to **Manifests** and filter by status **Shipped**. These manifests have been dispatched and are waiting to be received at the destination site.

### 9.3.4 Review specimens allocated to a study

Open a **Study** record and navigate to the **Specimens** section. This lists all specimens linked to the study, allowing you to verify that the correct specimens are included.

---

## 9.4 Exporting Data

BRIMS provides CSV export functions for the main operational record types. Exports are generated asynchronously — BRIMS will notify you when the file is ready to download. Export files are removed automatically after 24 hours, so download them promptly.

> **Important — data sensitivity:** Exports may include participant identifiers, contact details, and specimen data. Export only the minimum data needed for your task and share the files only with authorised recipients. Do not store exported files in unsecured locations.

### 9.4.1 Export entry points

| Export | How to access | What is included |
|---|---|---|
| **Export Subjects** | **Project Configuration** → **Data Export** → **Export Subjects** | ID, subject ID, site, manager (username), first name, last name, address, enrolment date, arm, arm baseline date, status |
| **Export Subject Events** | **Project Configuration** → **Data Export** → **Export Subject Events** | Subject ID, arm, event name, iteration, status, label status, event date, min date, max date, log date |
| **Export Specimens** (project-wide) | **Project Configuration** → **Data Export** → **Export Specimens** | Barcode, subject ID, arm, event name, event iteration, specimen type, site, origin site, status, parent barcode, aliquot, volume, volume unit, thaw count, logged-by user, logged date |
| **Export Specimens** (from Specimens list) | **Specimens** list → **Export** button (all records), or select rows → **Export** (selected records) | Same columns as above |
| **Export Specimens** (from a study) | **Studies** → open a study → **Specimens** section → **Export Specimens** | Barcode, specimen type, site, origin site, arm, event, event iteration, subject ID, logged date |

> **Tip:** The project-level exports (**Export Subjects**, **Export Subject Events**, **Export Specimens**) are found in the **Data Export** group on the **Project Configuration** page (open the project with **Access**, then select **Project Configuration** in the project menu). Use these when you need a complete project-level data extract. Each export is only shown if you have permission to view that type of record.

### 9.4.2 Columns that are hidden by default

Some columns in the specimen export from the **Specimens** list are hidden by default but can be included when you configure the export:

- Parent barcode
- Logged-by user
- Logged date

When prompted to choose columns before confirming an export from the Specimens list, review the column list and enable any additional columns needed for your task. The three project-level exports on the Project Configuration page do not ask you to choose columns: all columns are exported.

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

BRIMS does not have a single dedicated audit view across all record types. Record-level history is visible through:

- **Timestamps** on each record (created at, updated at, logged at, shipped date, received date, etc.)
- **Status fields** that reflect the current state of the workflow
- **Linked records** — for example, a specimen record links back to the subject event, and the subject event links back to the participant, providing a traceability chain through the system
- **Specimen Audit Log** — the specimen detail (View) page includes a full audit log of every status change, including previous and new status, who made the change, and when (see [Chapter 5, section 5.5.3](#553-viewing-a-specimen-record))

When a discrepancy needs to be investigated:

1. Open the record in question and review all date and user fields.
2. Follow the linked records (specimen → event → participant) to check for inconsistencies.
3. Review related exports if the issue spans multiple records.
4. If the issue cannot be resolved through the record view, contact your project manager or administrator.

---

## 9.6 Storage Allocation Reports

Storage allocation reports are printed from the **Specimen Storage** list rather than the export system. Each allocation record has a **Print** action that opens a printable report listing the physical location assigned to each specimen.

Refer to [Chapter 6 — Managing Specimen Storage](#chapter-6-managing-specimen-storage) for details.

---

## 9.7 Importing Data

BRIMS supports bulk import of subject, subject event, and specimen records from a CSV file. This is intended for migrating data from another system, not for day-to-day data entry. Imports are accessed from the **Project Configuration** page — open the project via **Access**, select **Project Configuration** in the project menu, then use the **Data Import** button group. Each import is only available if you have permission to create and update that type of record.

> **Important — data preparation:** Before running any import, prepare your source file carefully. Values in the file must exactly match the names used in BRIMS — sites, arms, events, specimen types, usernames, and status names — because BRIMS does not translate values. After you upload the file, you match each of its columns to a BRIMS field. The import process validates each row and will reject rows that contain unrecognised values or fail business rule checks. Failed rows do not prevent other rows in the same file from being processed — only the valid rows are committed.

> **Important — data sensitivity:** Import files may contain participant identifiers, specimen barcodes, and other sensitive research data. Handle import files according to your organisation's data governance procedures and delete them securely after use.

### 9.7.1 Import types

#### Import Subjects

Creates new subject records in the project. Each row must contain a Subject ID that matches the project's configured prefix and digit format.

> **Note:** This import creates subject records directly without triggering the enrolment workflow. Subjects imported with an Enrolled status will not automatically have events created. Use this import route only when migrating existing data where events and specimens are being imported separately in the same batch.

| Column | Required | Notes |
|---|---|---|
| **Subject ID** | Yes | Must match the project prefix and digit format, and be unique |
| **Site** | Yes | Site name as it appears in BRIMS |
| **User** | Yes | Username of the staff member to assign as Manager |
| **Enrolment Date** | Yes | Date in YYYY-MM-DD format |
| **Arm** | Yes | Arm name as it appears in BRIMS |
| **Status** | Yes | One of: Generated, Enrolled, Dropped |
| **First Name** | No | Up to 20 characters |
| **Last Name** | No | Up to 30 characters |
| **Address** | No | |
| **Arm Baseline Date** | No | Date in YYYY-MM-DD format. If you map this column, every row must contain a valid date. |

#### Import Subject Events

Creates new subject event records for existing subjects. Use this import to bulk-load event history when migrating data.

| Column | Required | Notes |
|---|---|---|
| **Subject ID** | Yes | Must already exist in the project |
| **Arm** | Yes | Arm name as it appears in BRIMS. The event is looked up within this arm. |
| **Event** | Yes | Event name as it appears in BRIMS |
| **Iteration** | Yes | Integer, minimum 1 |
| **Status** | Yes | A valid event status name (e.g. Logged, Missed, Pending) |
| **Label Status** | Yes | A valid label status name |
| **Event Date** | No | Date in YYYY-MM-DD format |
| **Min Date** | No | Must be on or before Event Date |
| **Max Date** | No | Must be on or after Event Date |
| **Log Date** | No | Must be on or after Event Date |

> **Note:** The combination of Subject ID, Arm, Event, and Iteration must be unique. Importing a row that matches an existing subject event will fail validation for that row.

#### Import Specimens

Creates new specimen records linked to existing subject events. Use this import when migrating specimen history from another system.

| Column | Required | Notes |
|---|---|---|
| **Barcode** | Yes | Must be unique within the project; max 20 characters |
| **Subject ID** | Yes | Must already exist in the project |
| **Arm** | Yes | Arm name as it appears in BRIMS |
| **Event** | Yes | Event name as it appears in BRIMS |
| **Iteration** | Yes | Integer, minimum 1. The subject event must already exist. |
| **Specimen Type** | Yes | Specimen type name as it appears in BRIMS |
| **Site** | Yes | Site name as it appears in BRIMS |
| **Origin Site** | Yes | Name of the site where the specimen was first logged |
| **Status** | Yes | A valid specimen status name (e.g. Logged, InStorage, Used) |
| **Aliquot** | Yes | Integer |
| **Thaw Count** | Yes | Integer |
| **Logged By** | Yes | Username of the user who logged the specimen |
| **Logged At** | Yes | Date in YYYY-MM-DD format |
| **Parent Specimen** | No | Barcode of the parent specimen (for derivatives) |
| **Volume** | No | Whole number. If you map this column, every row must contain a value. |
| **Volume Unit** | No | Max 5 characters (e.g. mL, µL) |

### 9.7.2 Running an import

1. Navigate to the **Project Configuration** page.
2. Select **Data Import** and choose the import type.
3. Upload your prepared CSV file, then match each column in the file to the BRIMS field it should fill. An example CSV can be downloaded from the dialog.
4. BRIMS processes the file asynchronously. You will receive a notification when the import is complete, showing the number of rows successfully imported and the number of rows that failed.
5. Review any failed rows. The notification includes a link to download a file listing the failed rows and the reason each one failed. Correct the source data and re-import the affected rows.

> **Tip:** Test with a small file (10–20 rows) before importing a full dataset. This confirms that your column mapping and the values in your file are correct before committing a large import.

---

## Summary

| Task | Where to go |
|---|---|
| Find a participant | **Subjects** list → search by subject ID or name |
| Find a specimen by barcode | **Specimens** list → search by barcode |
| Find shipped manifests awaiting receipt | **Manifests** list → filter by status Shipped |
| Find specimens waiting to be stored | **Specimens** list → search the Status column for Logged |
| Export participant data | **Project Configuration** → **Data Export** → **Export Subjects** |
| Export event data | **Project Configuration** → **Data Export** → **Export Subject Events** |
| Export specimen data (full project) | **Project Configuration** → **Data Export** → **Export Specimens** |
| Export specimen data (selected records) | **Specimens** list → select rows → **Export** |
| Export study specimens | **Studies** → open the study → **Specimens** section → **Export Specimens** |
| Import subjects | **Project Configuration** → **Data Import** → **Import Subjects** |
| Import subject events | **Project Configuration** → **Data Import** → **Import Subject Events** |
| Import specimens | **Project Configuration** → **Data Import** → **Import Specimens** |

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

- Confirm you are entering the correct username and password.
- Check whether your account has been created — contact your project administrator if you are new to the system.
- If you have forgotten your password, use the **Forgot password?** option on the sign-in page.
- If BRIMS shows "Your account is currently inactive", ask a system administrator to reactivate it. Accounts that have not been used for three months are deactivated automatically.

### 10.1.2 I can sign in but I cannot see a project

- Confirm that you have been added to the project with an appropriate role. A project must have a member record for you (team administrators can see all of their team's projects).
- Check with a project manager or administrator to confirm that your membership is set up and that your role is correct.
- If you have just been added, try signing out and back in to refresh your session.

### 10.1.3 I can see the project but I cannot access a specific page within it

- Some pages are restricted to specific project roles. Your current role may not include the permissions required.
- Contact a project manager to review and adjust your role if necessary.

---

## 10.2 Participants

### 10.2.1 I cannot find a participant

- Search by subject ID using the search field in the Subjects list.
- Check the **Status** filter on the Subjects list. Generated, Enrolled, and Dropped subjects all appear in the same list.
- Unless you are a project administrator, you only see the subjects that you manage at your own site, and the subjects of users for whom you act as substitute. A participant managed by someone else will not appear in your list.
- Confirm you are in the correct project — participant records exist within a specific project.

### 10.2.2 A participant is missing from the list but I know they were enrolled

- Check whether they have been dropped. Use the status filter on the Subjects list and look for records with status Dropped.
- If the record does not appear at all, the participant may have been enrolled under a different subject ID or in a different project. Check with your study coordinator.

### 10.2.3 I cannot enrol a subject

- The subject must have a status of **Generated** to be enrolled. If the record shows **Enrolled** or **Dropped**, the action will not appear.
- If you cannot see the participant at all, see section 10.2.1. Contact your project manager if the record should be visible to you.

### 10.2.4 The wrong arm is linked to a participant

- Review the current arm assignment on the participant record.
- If the arm needs to be changed, see [Chapter 3 — Enrolling and Managing Participants, section 3.6](#36-switching-a-participants-arm) for the arm switch process. Note that switching arm will cancel any pending events on the old arm.

---

## 10.3 Events

### 10.3.1 I cannot see the Log Event button

- The **Log Event** action is only shown on events that have a status of **Scheduled**. If the event is still **Pending** or **Primed**, it cannot be logged yet.
- Events become Scheduled when the weekly schedule is generated (**Generate Schedule** in the project menu), or when a user with permission sets the status directly. If you believe the event should be Scheduled, check its event and minimum dates and ask a user with permission to generate the schedule.

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
- A PSE barcode has the form `ProjectID_SubjectID_SubjectEventID`. BRIMS rejects a barcode with an invalid format, a project ID that does not match the current project, a subject that cannot be found, or an event that does not belong to that subject.

### 10.4.2 A specimen barcode fails validation

- BRIMS validates specimen barcodes against the regex pattern defined in the labware configuration for that specimen type. If the barcode does not match, it will be rejected.
- Check the physical label for scanning errors, or contact your project manager to confirm the expected barcode format.
- A barcode can be at most 20 characters and must be unique within the project. If a barcode has already been used, saving the specimens fails with a **Failed to log primary specimens** message and none of the specimens in that submission are saved.

### 10.4.3 I cannot see the specimen I just logged

- Search the Specimens list by barcode. If it was logged successfully, it will appear with a status of **Logged**.
- Unless you are a project administrator, the Specimens list only shows specimens at your own site. A specimen logged at another site will not appear in your list.
- If it does not appear, the logging workflow may not have been fully submitted. Repeat the logging process, ensuring all required fields are completed before submitting.

### 10.4.4 A specimen has the wrong status

- Review the specimen record and check all status fields and timestamps.
- If the status needs to be corrected and there is a clear operational reason to do so, an authorised user can edit the record directly (see [Chapter 5 — Logging Specimens, section 5.6](#56-editing-a-specimen-record)).
- Where possible, use the logging workflows (Log Out, Log Return, Log as Used) rather than editing status directly, as these preserve the correct audit trail.

### 10.4.5 The Update Specimen Status action returns an error

The **Update Specimen Status** action (barcode entry) rejects the entire submission if any barcode fails validation. The error notification identifies which barcodes caused the failure and why. Common causes:

- **Barcode not found in this project** — the barcode does not exist in the current project. Check the barcode is correct and that you are in the right project.
- **Wrong status for the requested action** — for example, trying to log a specimen as returned to storage when it is not currently logged out, or trying to log a specimen as used when it is not in an eligible status. Review the specimen record to check its current status before retrying.
- **Specimen not at your site** — the specimen's site does not match your project site assignment. Only specimens at your assigned site can be updated through this action. If the specimen is at a different site, contact a user assigned to that site.

Once the failing barcodes are identified and resolved, resubmit the full list.

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
- Incoming manifests are listed on the **Received Manifests** tab of the Manifests list, not on **Sent Manifests**.
- Confirm your site assignment with your project manager.

### 10.6.3 Specimens on a manifest have the wrong prior status

- The prior status recorded on each specimen in a manifest reflects the status at the time the specimen was attached. It cannot be edited.
- If there is a discrepancy, review the specimen record's history and, if necessary, contact your project administrator.

---

## 10.7 Studies and Assays

### 10.7.1 I cannot add specimens or assays to a study

- Check whether the study has the **Locked** toggle enabled. While a study is locked, specimens cannot be attached, imported, or detached, and assays cannot be added, edited, or deleted. A study lead can unlock the study if changes are needed.
- Check that the specimen belongs to a project the study is linked to. Only specimens from the study's linked projects can be added, and a specimen already attached to the study is not offered again.

### 10.7.2 An assay definition is missing from the dropdown

- Assay definitions are managed at the team level, on the **Assay Definitions** tab of your team's page (**My Team**). If the definition you need does not appear, ask your team administrator to create it.

### 10.7.3 I cannot see a study, or I cannot edit it

- You can see a study if it belongs to your team or you are one of its members, and your permissions allow you to view studies.
- Only a study **lead** can edit a study, change its linked projects, or manage its members. This applies to team administrators too, unless they are a lead of that study. Ask an existing lead to give you the **Lead** role in the study's **Members** section.

### 10.7.4 I cannot add a user as a study member, or remove a project from a study

- Only team administrators of the owning team and members of the study's linked projects can be added as study members. Link the user's project to the study first (see [Chapter 8, section 8.3](#83-linking-a-study-to-projects)).
- A project cannot be removed from a study while its specimens are attached to the study, or while a study member would be left without eligibility. Detach the specimens or remove the member first.
- A study must always have at least one lead.

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
| Cannot sign in | Username/password and account status |
| Project not visible | Project membership and role |
| Participant not found | Search by subject ID; check status filter |
| Log Event button missing | Check event status — must be Scheduled |
| Barcode rejected at PSE scan | Correct project selected; event exists |
| Specimen barcode fails | Labware barcode format vs physical label |
| Update Specimen Status error | See [section 10.4.5](#1045-the-update-specimen-status-action-returns-an-error) — check barcode, status, and site |
| Specimen type disabled in allocation | Insufficient storage capacity configured |
| Cannot edit manifest | Status must be Open |
| Cannot receive manifest | Your site must match the destination site |
| Cannot add specimens or assays to a study | Check whether the study is Locked |
| Cannot edit a study or manage its members | You must be a lead of the study |

---

---

## Chapter 11 — REDCap Integration

---

## Overview

This chapter is for data managers, project administrators, and system administrators who need to configure or troubleshoot REDCap-linked workflows in BRIMS.

BRIMS can be linked to REDCap for projects that use REDCap alongside operational specimen and study workflows. When a project is linked, BRIMS sets up the project's sites, arms, and events from the REDCap project, and creates a REDCap record for each participant who is enrolled in BRIMS.

Not all projects use REDCap. If you are unsure whether your project requires REDCap integration, check with your project administrator before proceeding.

---

## 11.1 Integration Overview

When a BRIMS project is linked to a REDCap project, BRIMS sets the project up from the REDCap project when it is created, and creates a record in REDCap whenever a participant is enrolled or switches arm in BRIMS. BRIMS does not read participant data back from REDCap.

REDCap-linked projects are created differently from standard BRIMS projects. They must be set up using the dedicated **Create New REDCap-Linked Project** workflow rather than the standard project creation form.

> **Caution:** If a project that requires REDCap integration is created using the standard project form, it cannot be linked to REDCap after the fact. Always confirm whether REDCap integration is required before creating a new project. See [Chapter 2 — Setting Up a Project](#chapter-2-setting-up-a-project) for guidance on project creation.

---

## 11.2 Creating a REDCap-Linked Project

1. Open **My Team** and select the **Projects** tab.
2. Select **New redcap project** (not the standard **New project** button). A dialog titled **Create New REDCap-Linked Project** opens.
3. In **Select Redcap Project**, choose the REDCap project to link. The list shows only REDCap projects in which your username has design rights and an API token, and that are not already linked to a BRIMS project.
4. Complete the remaining project fields as you would for a standard project (see [Chapter 2, section 2.1](#21-creating-a-project)).
5. Save the project.

Because the project is set up from REDCap, BRIMS does not create the usual defaults described in [section 2.1.3](#213-what-brims-creates-automatically). Instead it:

- Creates the **Admin** role.
- Creates the project's **sites** from the REDCap data access groups.
- Adds you and the project leader as Admin members, each assigned to the site matching their REDCap data access group, and stores a REDCap API token for each (fetching it, or generating one if none exists).
- Imports the **arms and events** from REDCap, including their offsets, windows, and order. Events at day zero are set to autolog.

Review the imported sites, arms, and events rather than creating them again. Both your username and the project leader's username must exist in the REDCap project, otherwise creation fails with a message that the user's username was not found in the REDCap project.

Once created, the linked project will appear in the team Projects list with a **REDCap Linked** indicator. Confirm this indicator is present before users begin working with live data.

> **Caution:** Make sure the correct REDCap project is linked to the correct BRIMS project before any live data entry begins. Linking the wrong REDCap project can result in data being associated with the wrong source records.

---

## 11.3 User Tokens and Access

Some REDCap functions depend on a user-specific API token stored on the project member record. This token authorises the individual member's access to the linked REDCap project.

To add or update a REDCap token for a project member:

1. Open the project's member list from **Project Configuration** → **Members**.
2. Find the relevant member and select **Edit**.
3. In the **Redcap token** field, enter or update the token value.
4. Save the member record.

> **Important:** Only enter a token for the correct project member. Tokens are user-specific. If a member's REDCap access changes or a token is replaced, update the stored value promptly to avoid disrupted workflows.

The **Redcap token** field is only visible on member records within REDCap-linked projects. It is not available when you first attach a member; add the token afterwards by editing the member.

---

## 11.4 Confirming the Setup

After creating a REDCap-linked project and configuring member tokens, verify that the expected REDCap-connected functions are available and behaving correctly:

1. Have an affected user sign in and confirm that REDCap-dependent workflow options are visible.
2. Enrol a test subject (or switch a test subject's arm) and confirm that the record appears in the REDCap project.
3. If issues are found, refer to the troubleshooting steps in [section 11.5](#115-troubleshooting-redcap-integration) below.

---

## 11.5 Troubleshooting REDCap Integration

| Symptom | First thing to check |
|---|---|
| REDCap-linked options do not appear | Confirm the project was created as a REDCap-linked project, not a standard project |
| Enrolling a subject or switching arm fails with a REDCap error | Check that a valid token is stored on the acting user's project member record, and that the user has rights in the REDCap project |
| A participant's record is missing in REDCap | Confirm that the enrolment completed, and that the correct REDCap project is linked |
| Records are created with unexpected values | Confirm that the token is current and that REDCap-side permissions have not changed |

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
| Create a REDCap-linked project | **My Team** → **Projects** → **New redcap project** |
| Verify the REDCap link is active | Team **Projects** list — check for the **REDCap Linked** indicator |
| Add or update a member's REDCap token | **Project Configuration** → **Members** → edit the member record |
| Troubleshoot integration issues | See section 11.5 above |

---

---

## Chapter 12 — Administration Guide

---

## Overview

This chapter is for system administrators and selected project administrators who are responsible for keeping BRIMS configured, governed, and ready for project use.

Administrative responsibilities in BRIMS span several areas: user and access management, role and permission management, reference data configuration (including institutions, label specifications, and programmes), preparing for new projects, and ongoing operational monitoring. In the current BRIMS interface, this work is split between system-level administration (the **Admin** panel) and team-level configuration (the team **Projects** and related setup areas).

---

## 12.1 User Management

### 12.1.1 Creating and managing accounts

User accounts are managed from the **Users** area in the Admin panel.

When creating a new account:

1. Navigate to **Admin** → **Users** → **New User**.
2. Enter the user's **username** (unique, lowercase, up to 20 characters), email address, first name, and last name, and optionally a telephone number.
3. Select the user's **team** and **team role**.
4. Assign a system role only if the user needs administrative access.
5. Leave **Active** switched on, and save the record. The user will receive an email with a **Set Password** link, which is valid for 24 hours.

Team administrators can also add team members from **My Team** → **Members**. Users created this way are not sent an email: they set their first password with **Forgot password?** on the sign-in page.

The Users list includes an **avatar** column and a **Last Login** column, both visible by default. The Last Login column is sortable and allows administrators to identify inactive accounts quickly. An **Active** filter is available to show only currently active users. Administrators can also use the **Impersonate** action to sign in as a user when investigating a problem. Accounts that have not been used for three months are deactivated automatically.

### 12.1.2 Reviewing and updating access

Review user accounts regularly to ensure that project membership and permissions remain appropriate for each person's current responsibilities.

When a user's role changes — for example, if they move to a different project or leave the organisation — update or deactivate their account promptly.

| Action | Where to do it |
|---|---|
| Create a new user account | **Admin** → **Users** → **New User** |
| Update a user's system role | **Admin** → **Users** → edit the user record |
| Add a user to a project | **Project Configuration** → **Members** → **Attach** |
| Change a user's project role | **Project Configuration** → **Members** → edit the member record |
| Deactivate an account | **Admin** → **Users** → edit the user record → switch **Active** off |

> **Caution:** Deactivating an account does not remove the user's historical records or audit trail. It prevents further sign-in only. Do not delete user records unless directed by your organisation's data governance policy.

---

## 12.2 Role and Permission Management

System roles should be kept limited to users who genuinely need wider administrative access. Project roles should be scoped to support day-to-day work without granting unnecessary permissions.

- Use the project **Roles** area (in the **Authorisation** group of the project menu) for project-specific permissions.
- Use team roles (team **Admin** or **Member**) to control who can manage the team's members, projects, and studies.
- Use study roles (**Lead** or **Member**) to control who can edit a study and manage its members.
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
| **Study Designs** | A controlled list of study design types, with ontology references, from which each project selects its study design |
| **Unit Definitions** | Layout templates for storage freezers and tanks: storage type, orientation, and the racks, boxes, and positions in each section |
| **Physical Units** | The actual freezers and tanks in the laboratory, each based on a unit definition. Virtual units, which assign ranges of racks and boxes to a project and specimen type, are created on each physical unit. |
| **Institutions** | Named institutions that teams belong to |
| **Label Specifications** | Label printing format templates (paper size, metric, and label grid dimensions) used to configure project label printing |

### 12.3.2 Team-level configuration

The following are managed at the team level and are available to all projects within the team:

| Area | Purpose |
|---|---|
| **Assay Definitions** | Templates for assay record structure, including custom metadata fields |
| **Protocols** | Standard operating procedures associated with study or specimen work |
| **Programmes** | Funded research programmes that can be linked to one or more projects within the team |
| **Studies** | Research investigations owned by the team and linked to one or more of its projects (see [Chapter 8](#chapter-8-studies-assay-data-and-publications)) |

When a project requires a new assay type, specimen unit, or study design, review whether a configuration update is needed before the project goes live.

---

## 12.4 Managing Institutions

Institutions represent the named organisations that teams belong to in BRIMS. A team's institution name is also used as the name of the default site created for new projects. They are managed from the Admin panel under **User Management** → **Institutions**.

To create an institution:

1. Navigate to **Admin** → **Institutions** → **New Institution**.
2. Enter the **Name** (up to 20 characters; must be unique across the system) and select the **Country**.
3. Save.

Institutions can be edited from the list view using the **Edit** action. Because teams and default project sites use the institution name, check which teams use an institution before renaming it.

---

## 12.5 Managing Label Specifications

Label specifications define the format templates available for printing participant labels. Each project must be assigned a label specification at creation time, so these should be configured before any new projects are set up.

Label specifications are managed from the **Utilities** group of the Admin panel.

| Field | What to enter |
|---|---|
| **Format** | A unique code identifying this label format (up to 30 characters). |
| **Paper Size** | The physical paper or label sheet size: `A4` or `Letter`. |
| **Metric** | The unit system used for label dimensions: `mm` or `in`. |
| **Margins** | The left and top margins of the label sheet. |
| **Count X / Count Y** | The number of labels across and down the sheet (1 to 30 each). |
| **Space X / Space Y** | The horizontal and vertical spacing between labels. |
| **Width / Height** | The size of each label. |
| **Font Size** | The label font size (7 to 13). |
| **Padding** | Optional. The padding inside each label (0 to 10). |

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
| **Grant Number** | No | The grant or award reference number (up to 100 characters) |
| **Principal Investigator** | Yes | The principal investigator — select from team members |
| **Description** | No | A free-text description of the programme's scope and objectives |

5. Save the programme.

### 12.6.2 Linking programmes to projects

Once a programme exists at the team level, it can be linked to one or more projects within the same team. See [section 2.8 — Linking a Project to Programmes](#28-linking-a-project-to-programmes) for the project-side workflow.

### 12.6.3 Reviewing programmes

The Programmes tab on the team record shows all programmes for the team, with columns for name, funder, grant number, principal investigator, and description. Click a programme to open its detail view, which shows its name, funder, grant number, principal investigator, and description. To see which programmes a project is linked to, open the **Programmes** tab on the project's **Project Configuration** page.

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

> **Tip:** Walking through this checklist before a project begins active enrolment will prevent the most common setup errors. See [Chapter 2 — Setting Up a Project](#chapter-2-setting-up-a-project) for the full project configuration workflow, and [Chapter 11 — REDCap Integration](#chapter-11-redcap-integration) if the project requires a REDCap link.

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
| Manage assay definitions | **My Team** → **Assay Definitions** tab |
| Manage programmes | **My Team** → **Programmes** tab |
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

A pre-configured template that defines the structure of an assay record, including any additional data fields specific to that assay type. Assay definitions are managed at the team level, on the **Assay Definitions** tab of the team page, and are available to all of the team's studies.

### Biorepository

One of the three supported storage types in BRIMS. Refers to general controlled-temperature biorepository storage. See also: **Storage Type**.

### Derivative Specimen

A specimen derived or processed from a primary specimen — for example, plasma separated from whole blood. Derivative specimens are logged through the **Log Derivative Specimens** workflow and carry a link to their parent specimen. See also: **Primary Specimen**, **Parent Specimen**.

### Enrolment

The process of formally registering a participant into a project arm. Enrolment moves the participant's status from **Generated** to **Enrolled** and sets the arm baseline date used for event scheduling.

### Event

A scheduled or completed activity linked to a participant — such as a study visit, follow-up contact, or specimen collection point. Events are configured as event definitions within an arm and are instantiated as subject events when a subject is generated or switches arm. See also: **Event Definition**, **Subject Event**.

### Event Status

The current stage of a subject event in the workflow. Possible statuses are: Pending, Primed, Scheduled, Logged, Logged Late, Missed, and Cancelled.

### Event Definition

The configuration of a recurring event within an arm, specifying its name, expected day offset from the arm baseline date, acceptable date window, and whether it is repeatable. Each enrolled participant gets a subject event generated from each event definition in their arm.

### Institution

A named organisation that a team belongs to in BRIMS. Institutions are managed at the system level from the Admin panel. See [Chapter 12 — Administration Guide, section 12.4](#124-managing-institutions).

### Labware

A type of container (tube, vial, plate etc.) associated with a specimen type, including its barcode format. Labware configuration determines which barcodes will be accepted when specimens of that type are logged.

### Label Specification

A system-level template that defines the physical format of printed barcode labels — including paper size and metric. Each project must be assigned a label specification. Label specifications are managed by administrators from the Admin panel and are available to all projects.

### Liquid Nitrogen

One of the three supported storage types in BRIMS. Refers to cryogenic vapour or liquid nitrogen storage. See also: **Storage Type**.

### Manifest

A shipment record that groups specimens together for transfer between sites. A manifest moves through three statuses: **Open**, **Shipped**, and **Received**. See also: **Manifest Status**.

### Manifest Status

The current stage of a manifest in the transfer workflow. Possible statuses are: Open (being prepared), Shipped (dispatched by the sending site), and Received (confirmed by the receiving site).

### Minus 80

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

The main working unit within a team for organising research work in BRIMS. A project has its own configuration, participants, arms, events, specimen types, storage settings, and members. A project can be linked to one or more of the team's studies.

### PSE

Abbreviation for **Project Subject Event**. See **Project Subject Event Barcode (PSE Barcode)**.

### Project Subject Event Barcode (PSE Barcode)

A scannable barcode that identifies a specific combination of project, participant, and event. The PSE barcode is used as the entry point for the primary specimen logging workflow to identify which participant and event the specimens belong to.

### Publication

A project-level record that captures bibliographic information about a research output from the project — such as a journal article, preprint, or conference proceedings. Publication records store the title, authors, PubMed ID, DOI, publication year, and publication status. See also: **Publication Status**.

### Publication Status

A field on a publication record indicating its current stage in the publication pipeline. Possible values are: Draft, Submitted, and Published.

### REDCap Integration

An optional configuration that links a BRIMS project to a REDCap project. When enabled, the project's sites, arms, and events are set up from the REDCap project, and a record is created in REDCap for each participant who is enrolled or switches arm in BRIMS.

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

The physical environment in which a specimen is stored. BRIMS supports three storage types: **Minus 80**, **Liquid Nitrogen**, and **Biorepository**.

### Study

A team-level research investigation that can be linked to one or more of the team's projects. A study links a defined set of specimens, drawn from its linked projects, to one or more assays, and has its own members. A study can be locked to prevent specimens and assays from being changed. See also: **Study Role**, **Study Member**.

### Study Member

A user who has been added to a study. Study members must be team administrators of the owning team or members of one of the study's linked projects. Each study member has a **Study Role**.

### Study Role

The role a user holds within a study: **Lead** or **Member**. Only a study lead can edit the study and manage its members, and every study must have at least one lead. The user who creates a study becomes its first lead.

### Subject

The label used in the BRIMS user interface for a participant record. See also: **Participant**.

### Subject Event

The instance of an event definition for a specific participant. Subject events are created when a subject is generated or switches arm, and carry the status, label status, scheduled dates, and log date for that participant's event. See also: **Event**, **Event Definition**.

### Subject ID

The unique identifier generated for each participant record. The format is determined by the project prefix and the number of digits configured during project setup (e.g. ABC001). Subject IDs are assigned automatically by BRIMS and cannot be manually specified.

### Subject Status

The current enrolment state of a participant. Possible statuses are: Generated (record exists but not yet enrolled), Enrolled (active participant), and Dropped (no longer active in the study).

### Team

The top-level organisational unit in BRIMS: the research group or organisation that owns projects, studies, protocols, assay definitions, and programmes, and whose members work within them. Each team member has a team role of **Admin** or **Member**.

### Virtual Unit

A logical subdivision of a physical unit in BRIMS. A virtual unit assigns a range of racks and boxes in a freezer or tank to a project and a storage specimen type, and provides the locations (rack, box, and position) that can be allocated to specimens.

---

*End of BRIMS User Manual — Draft 2*
