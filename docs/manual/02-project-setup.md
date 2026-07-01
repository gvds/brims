# BRIMS User Manual

## Chapter 2: Setting Up a Project

---

## Overview

This chapter is for project managers and study administrators who are responsible for configuring a new project in BRIMS before data collection begins.

Project setup must be completed before research and laboratory staff can enrol participants or log specimens. A well-configured project takes approximately an hour to set up from scratch, but the decisions made here — particularly around subject ID format, study arms, and specimen types — will affect every workflow that follows. It is worth taking the time to review your study protocol before you begin.

The recommended setup order is:

1. Create the project
2. Review the defaults BRIMS creates automatically
3. Add sites
4. Create study arms and event templates
5. Configure specimen types and labware
6. Review roles and add project members
7. Create studies

Each step is covered in its own section below. A [setup checklist](#setup-checklist) is provided at the end of the chapter.

---

## 2.1 Creating a Project

Navigate to your team's **Projects** section and select **New Project**.

![The project creation form showing required fields: title, identifier, study design, project leader, storage designation, and subject ID settings.]()

### Required fields

| Field | What to enter |
|---|---|
| **Title** | The full name of the project. This must be unique across the system. |
| **Identifier** | A short alphanumeric code used to reference the project (e.g. `BRIM-001`). This must also be unique across the system. |
| **Study Design** | Select the study design type from the pre-configured list (e.g. Randomised, Observational, Longitudinal). |
| **Project Leader** | The user assigned as project lead. They are automatically added as a project administrator on creation. |
| **Storage Designation** | An identifier for the storage location associated with this project (e.g. a freezer block code or site abbreviation). |
| **Subject ID Prefix** | Two to ten uppercase letters that will be prepended to all participant identifiers (e.g. `BRI`). |
| **Subject ID Digits** | The number of digits in the numeric part of the participant ID (between two and eight). |

### Optional fields

| Field | What to enter |
|---|---|
| **Description** | A free-text description of the project. |
| **Submission Date** | The date the project was or will be formally submitted. |

> **Important — Subject ID format:** The prefix and digit count together define the format of every participant identifier the system will generate. For example, a prefix of `BRI` with six digits produces IDs such as `BRI000001`, `BRI000002`, and so on. Choose this format according to your study protocol and confirm it with your team before saving. This setting is difficult to change once participants have been enrolled and identifiers have been issued.

> **REDCap projects:** If this project needs to be linked to REDCap, do not use the standard project form. Instead, use the **Create New REDCap-Linked Project** option available from the team Projects section. See [Chapter 12 — REDCap Integration](12-redcap-integration.md) for full setup and troubleshooting guidance.

### What BRIMS creates automatically

When you save a new project, BRIMS sets up the following automatically:

- A default **Admin** role for the project
- An initial site based on the project creator's home site
- Project membership for the project creator and the project leader

These defaults are a starting point. Review them before proceeding with the rest of the setup, particularly the initial site name and the Admin role permissions.

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

> **Tip:** Create all arms before you start adding event templates. Once participants are enrolled, changing an arm's structure may affect the events that have already been scheduled for subjects.

### Adding event templates to an arm

Events represent the visit schedule or follow-up milestones for participants in that arm. After you create an arm, open it and add the event templates that apply to subjects in that cohort.

![An arm record with the Events section open, showing event templates with fields for name, offset, and scheduling windows.]()

| Field | What it does |
|---|---|
| **Name** | A descriptive label for the event (e.g. `Screening Visit`, `Month 6 Follow-up`). |
| **Offset** | The number of days from enrolment at which this event is expected to occur. |
| **Ante Window** | How many days before the scheduled date the event can still be recorded as on time. |
| **Post Window** | How many days after the scheduled date the event can still be recorded as on time. |
| **Autolog** | If enabled, BRIMS logs this event automatically at the defined offset. |
| **Repeatable** | If enabled, additional iterations of this event can be added after the first one is recorded. |
| **Active** | Controls whether this event template is currently in use for new subjects. |

> **Tip:** The offset, ante window, and post window together define the acceptable timing range for each visit. Setting these correctly helps follow-up review reports distinguish between events that were on time, those recorded within an acceptable window, and those that were genuinely missed or late. Discuss these values with your study statistician or clinical operations lead before entering them.

---

## 2.4 Configuring Specimen Types and Labware

Before specimen logging begins, configure the specimen types and labware that the project will use. This step is essential: specimen logging workflows in BRIMS rely on these settings to identify and validate samples.

Navigate to the project view and use the **Specimen Types** and **Labware** sections.

![The Specimen Types and Labware configuration areas, showing type settings and barcode format fields.]()

### Specimen types

Specimen types define what kind of sample is being collected and how it should be handled.

When creating a specimen type, you will define:

- Whether the sample is **primary** (collected directly from the participant) or **derivative** (processed from a primary specimen)
- Whether specimens of this type should go into storage after logging
- How aliquots or volumes are managed

These settings affect downstream workflows directly. A specimen type that is not configured for storage will not trigger storage allocation after logging.

### Labware

Labware records define the physical container associated with a specimen type and the barcode format expected during logging.

The **Barcode Format Regex** field is particularly important: BRIMS validates every scanned or entered barcode against this pattern. If a barcode does not match the pattern defined for the labware, the logging step will fail.

> **Tip:** Work with your laboratory team to confirm the barcode format in use before entering the regex pattern. A small formatting error here can block logging for the entire project. If you are not familiar with regular expressions, ask your data manager or system administrator to help define this field.

---

## 2.5 Reviewing Roles and Adding Project Members

### Reviewing roles

Roles define what each project member is permitted to do. BRIMS creates a default Admin role when the project is set up, but most projects need at least a few distinct roles — for example, a data manager role, a clinical staff role, and a laboratory role.

Before adding members, navigate to **Roles** in the project sidebar and confirm that the available roles reflect the real working responsibilities of your team.

To create a new role:

1. Select **New Role**
2. Enter a name for the role
3. Assign the appropriate permissions from the list

Permissions take effect as soon as a role is saved. Members assigned to that role will have their access updated automatically.

> **Tip:** Keep roles aligned with job functions rather than individual users. A role called `Laboratory Staff` is easier to manage and audit over time than multiple individually named roles. If a team member changes responsibilities, updating their role assignment is much simpler than maintaining a custom permission set.

### Adding project members

Project members are users from your team who have been granted access to work within the project.

Navigate to the **Members** tab and select **Attach Member**.

| Field | What to enter |
|---|---|
| **User** | Select from the list of available team users. Each user can only be added once per project. |
| **Role** | Select the project role that defines this member's permissions. |
| **Site** | Assign the member to a project site where applicable. This is required if you intend to configure a substitute. |

> **Note:** The project leader cannot be detached from the project. To reassign project leadership, edit the project record and select a different user in the **Project Leader** field.

### Member substitutes

A substitute is another project member who can act on behalf of a member when they are unavailable (for example, during leave or absence).

To assign a substitute, open the member record in the Members list and use the **Select Substitute** action. The substitute must:

- Already be a member of the same project
- Be assigned to the same project site as the member they are covering

> **Note:** If a member's site assignment changes and the current substitute is not assigned to the new site, the substitute assignment will be cleared automatically. Review substitute assignments whenever site configurations change.

### REDCap tokens

If the project is linked to REDCap and particular members need REDCap API access, a personal token can be stored against each member's record. Open the member entry and add or update the token in the **REDCap Token** field.

---

## 2.6 Creating Studies

Studies are research investigations that sit within a project. A project may contain multiple studies, each with its own associated specimens and assay records.

Navigate to **Studies** in the project sidebar and select **New Study**.

![The study creation form showing title, identifier, description, and date fields.]()

### Required fields

| Field | What to enter |
|---|---|
| **Title** | The full name of the study. Must be unique within the project. |
| **Identifier** | A short reference code for the study. Must be unique across BRIMS — not only within the project. |

### Optional fields

| Field | What to enter |
|---|---|
| **Description** | A free-text description of the study's scope or objectives. |
| **Submission Date** | The date the study was or will be formally submitted. |
| **Public Release Date** | The planned public release date for the study, if applicable. |

> **Tip:** The study identifier will appear in exports, reports, and assay records. Choose a code that is meaningful to your team and consistent with any identifiers already used in your study protocol or ethics application.

### Locking a study

The **Locked** toggle prevents specimens from being added to or removed from a study. Use this when the study has reached a defined data cut-off or analytical milestone to preserve the integrity of the specimen set.

> **Warning:** Locking a study is a significant action. Confirm that all expected specimens have been associated with the study, and that no further additions are anticipated, before enabling this toggle. Locked studies continue to be viewable; only specimen association is restricted.

---

## Setup Checklist

Use this checklist to confirm that the project is ready before research and laboratory staff begin work.

- [ ] Project created with a unique title and identifier
- [ ] Study design selected
- [ ] Subject ID prefix and digit count confirmed and agreed with the study team
- [ ] Storage designation entered
- [ ] Default site reviewed; additional sites created as needed
- [ ] All study arms created and named
- [ ] Event templates added to applicable arms, with offsets and windows confirmed
- [ ] Specimen types configured for primary and derivative samples as needed
- [ ] Labware records created with correct barcode format patterns
- [ ] Default Admin role reviewed; additional roles created to reflect team functions
- [ ] All project members added with appropriate roles and site assignments
- [ ] Substitute assignments configured where needed
- [ ] At least one study created

---

*Previous chapter:* [Chapter 1 — Getting Started](01-getting-started.md)  
*Next chapter:* [Chapter 3 — Enrolling and Managing Participants](03-enrolling-participants.md)
