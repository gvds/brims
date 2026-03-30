# Project and Study Setup

## Purpose

Use this page for a simple overview of the setup steps required before participant enrolment, specimen logging, and assay workflows begin.

For a more detailed walkthrough, see [Project Setup](03-project-setup.md).

## Who Should Use This Page

- Project administrators
- Study leads
- Selected system administrators

## Before You Begin

Before you start, make sure you have permission to create or manage projects and that the necessary study planning details have been approved.

## Setup Sequence

1. Create the project
2. Review the default site and Admin role created by BRIMS
3. Add extra sites if needed
4. Create study arms
5. Review roles and permissions
6. Add project members
7. Create studies

## Create a Project

When you create a project, confirm the following information carefully:

- Project title
- Project identifier
- Study design
- Project leader
- Storage designation
- Subject ID prefix
- Subject ID digits

You can also add a description and submission date.

If the project must link to REDCap, use the REDCap-linked project creation option rather than the standard project form.

## Configure Subject ID Rules

BRIMS uses the subject ID prefix and digit count to generate participant identifiers automatically.

For example, a prefix of `BRI` with 6 digits produces IDs such as `BRI000001`.

Choose these settings carefully. Changing them later can be difficult once participants have already been enrolled.

## Review What BRIMS Creates Automatically

When a new project is created, BRIMS automatically creates:

- A default Admin role
- An initial site based on the project creator's home site
- Project membership for the project creator and the project leader

Review these defaults before you continue with the rest of the setup.

## Add Sites

Sites represent the physical or organisational locations where work is carried out.

If your project runs across more than one clinic, hospital, laboratory, or institution, add a separate site for each one.

Sites are also used when assigning project members and substitutes.

## Create Study Arms

Study arms divide participants into groups such as control and intervention cohorts.

When creating an arm, decide whether enrolment into that arm will be manual or automated.

Arm numbers are assigned automatically by BRIMS.

## Review Roles and Add Members

Project members are users who have access to work within the project.

Before adding the full team, review the default Admin role and create any additional roles your project needs.

When adding a project member, assign:

- The correct project role
- The correct site, if applicable

If the project uses REDCap integration, a REDCap token can be stored on the member record where needed.

## Create Studies

Studies are research investigations within the project.

Each study should have:

- A title
- An identifier

You can also add a description and submission date.

## Setup Checklist

- Project created with the correct title and identifier
- Study design confirmed
- Subject ID settings confirmed
- Storage designation entered
- Default site reviewed and extra sites added if needed
- Study arms created
- Default Admin role reviewed and extra roles created if needed
- Project members added with the correct roles and site assignments
- Studies created

## Related Pages

- [Project Setup](03-project-setup.md)
- [Participant Management](04-participant-management.md)
- [Administration Guide](13-administration-guide.md)
- [Glossary](15-glossary.md)