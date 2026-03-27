# BRIMS User Documentation Outline

## Purpose

This document defines the proposed structure for end-user documentation for BRIMS.

BRIMS is a biomedical research information management system used to manage multi-site research projects, participant enrolment, study arms, follow-up events, specimen handling, storage, shipment, assay data, and REDCap-linked workflows.

The documentation should be organised around real user tasks rather than internal data structures or developer concepts.

---

## Documentation Goals

- Help new users understand what BRIMS does and where to start
- Support project teams in setting up and operating studies correctly
- Provide step-by-step instructions for routine workflows
- Reduce errors in specimen handling, storage, and study data management
- Provide quick-reference material for permissions, statuses, and common issues

---

## Primary Audiences

- Project administrators
- Study coordinators
- Research nurses and field staff
- Laboratory and specimen management staff
- Data managers
- System administrators

---

## Recommended Documentation Structure

The documentation should be divided into five sections:

1. Orientation and onboarding
2. Core operational workflows
3. Integrations and advanced workflows
4. Reference material
5. Troubleshooting and support

---

## Proposed Table of Contents

### 00. Documentation Home

**Suggested file name:** `00-index.md`

**Purpose:**
Provide a landing page for all BRIMS user documentation.

**Suggested contents:**
- What BRIMS is
- Who this documentation is for
- How the documentation is organised
- Recommended reading paths by role
- Links to the most common tasks
- Links to support and troubleshooting

---

### 01. System Overview

**Suggested file name:** `01-system-overview.md`

**Purpose:**
Explain the scope of BRIMS and introduce the main concepts used throughout the system.

**Suggested contents:**
- What BRIMS manages
- Typical end-to-end workflow in BRIMS
- Key objects and relationships:
  - Project
  - Site
  - Arm
  - Participant
  - Event
  - Specimen
  - Study
  - Assay
  - Storage location
  - Manifest
- How records relate to one another
- High-level explanation of auditability and traceability

---

### 02. Access, Permissions, and Navigation

**Suggested file name:** `02-access-permissions-and-navigation.md`

**Purpose:**
Help users understand how they access BRIMS, what permissions control, and how to move around the application.

**Suggested contents:**
- Logging in and account access
- Password setup or reset
- User roles vs project roles
- How permissions affect visible actions
- Overview of the main navigation areas
- Project-scoped work vs system-wide administration
- Common interface patterns
- Good practice for safe editing

---

### 03. Project and Study Setup

**Suggested file name:** `03-project-and-study-setup.md`

**Purpose:**
Guide users through the initial setup required before live data collection begins.

**Suggested contents:**
- Creating a project
- Defining identifiers and subject ID formats
- Adding sites
- Creating study arms
- Adding project members
- Assigning project roles
- Creating studies
- Setting storage designations
- Locking and governance considerations during setup
- Recommended setup checklist

---

### 04. Participant Management

**Suggested file name:** `04-participant-management.md`

**Purpose:**
Document how participants are enrolled and maintained in the system.

**Suggested contents:**
- When to create a participant
- Participant enrolment workflow
- Automatic subject ID allocation
- Assigning participants to sites and arms
- Updating participant information
- Participant status handling
- Avoiding duplicate enrolments
- Reviewing participant history

---

### 05. Event Scheduling and Follow-up

**Suggested file name:** `05-event-scheduling-and-follow-up.md`

**Purpose:**
Explain how participant visits and follow-up events are created, tracked, and updated.

**Suggested contents:**
- Event concepts in BRIMS
- Automatic scheduling after enrolment
- Viewing participant event schedules
- Recording attendance and completion
- Rescheduling or recording missed visits
- Event statuses
- Event-level notes and audit trail
- Relationship between events and specimen collection

---

### 06. Specimen Logging and Tracking

**Suggested file name:** `06-specimen-logging-and-tracking.md`

**Purpose:**
Cover specimen capture and day-to-day tracking workflows.

**Suggested contents:**
- Creating specimen records
- Linking specimens to participants and events
- Barcode-based workflows
- Recording specimen type and status
- Aliquots or derivative specimens, if applicable
- Updating specimen metadata
- Finding a specimen record
- Chain-of-custody considerations

---

### 07. Storage Management

**Suggested file name:** `07-storage-management.md`

**Purpose:**
Document how storage infrastructure and specimen placement are managed.

**Suggested contents:**
- Storage concepts and hierarchy
- Freezer and liquid nitrogen storage
- Storage destination types
- Assigning specimens to storage locations
- Moving specimens between locations
- Retrieving specimens from storage
- Capacity, occupancy, and traceability considerations
- Common storage mistakes to avoid

---

### 08. Shipment and Manifest Management

**Suggested file name:** `08-shipment-and-manifest-management.md`

**Purpose:**
Explain how specimens are grouped, shipped, and tracked between locations.

**Suggested contents:**
- What a manifest is
- When to create a shipment
- Creating manifests
- Adding or removing specimens from a manifest
- Manifest statuses
- Dispatch workflow
- Receipt and confirmation workflow
- Shipment audit trail
- Good practice for reconciling shipped specimens

---

### 09. Study and Assay Data Management

**Suggested file name:** `09-study-and-assay-data-management.md`

**Purpose:**
Describe how research studies and associated assay records are managed.

**Suggested contents:**
- Creating and editing studies
- Associating specimens with studies
- Study-level restrictions and locking
- Assay definitions
- Recording assay results and metadata
- Reviewing assay-linked specimens
- Data integrity considerations
- Recommended end-of-study checks

---

### 10. REDCap Integration

**Suggested file name:** `10-redcap-integration.md`

**Purpose:**
Support users who connect BRIMS with REDCap.

**Suggested contents:**
- Overview of BRIMS and REDCap integration
- Required project-level configuration
- User token requirements
- Expected data flow
- Common setup prerequisites
- Troubleshooting sync or authentication issues
- Governance and security considerations

---

### 11. Search, Filters, and Operational Review

**Suggested file name:** `11-search-filters-and-operational-review.md`

**Purpose:**
Help users find records efficiently and verify operational data.

**Suggested contents:**
- Searching for participants
- Searching for specimens
- Searching for studies and events
- Using filters effectively
- Saved views or common review patterns, if supported
- Verifying recent activity
- Preparing operational summaries or exports

---

### 12. Reports, Exports, and Audit Trails

**Suggested file name:** `12-reports-exports-and-audit-trails.md`

**Purpose:**
Explain how users review output data and inspect historical changes.

**Suggested contents:**
- Available exports
- When to use exports vs in-system review
- Reviewing change history
- Audit trail expectations
- Handling sensitive data in reports
- Validation steps before sharing outputs

---

### 13. Administration Guide

**Suggested file name:** `13-administration-guide.md`

**Purpose:**
Provide guidance for users responsible for maintaining system configuration.

**Suggested contents:**
- Managing users
- Managing system roles and permissions
- Reference lists and controlled values
- Reviewing configuration dependencies
- Monitoring operational consistency
- Administrative checks before onboarding new projects

---

### 14. Troubleshooting and FAQ

**Suggested file name:** `14-troubleshooting-and-faq.md`

**Purpose:**
Provide fast answers to common user issues.

**Suggested contents:**
- I cannot access a project
- I cannot find a participant or specimen
- A barcode is not recognised
- I cannot move or ship a specimen
- I cannot edit a record
- REDCap token or integration issues
- Missing permissions
- Data inconsistency checks
- When to escalate to an administrator

---

### 15. Glossary

**Suggested file name:** `15-glossary.md`

**Purpose:**
Define important BRIMS and research workflow terminology.

**Suggested contents:**
- Arm
- Assay
- Event
- Manifest
- Participant
- Project
- Site
- Specimen
- Storage designation
- Study
- Subject ID
- Status values used in the system

---

## Role-Based Reading Paths

### Project Administrators

Recommended reading order:
1. Documentation Home
2. System Overview
3. Access, Permissions, and Navigation
4. Project and Study Setup
5. Administration Guide
6. Reports, Exports, and Audit Trails
7. Troubleshooting and FAQ

### Study Coordinators

Recommended reading order:
1. Documentation Home
2. System Overview
3. Participant Management
4. Event Scheduling and Follow-up
5. Study and Assay Data Management
6. Search, Filters, and Operational Review

### Laboratory and Specimen Staff

Recommended reading order:
1. Documentation Home
2. System Overview
3. Specimen Logging and Tracking
4. Storage Management
5. Shipment and Manifest Management
6. Troubleshooting and FAQ

### Data Managers

Recommended reading order:
1. Documentation Home
2. System Overview
3. Participant Management
4. Study and Assay Data Management
5. REDCap Integration
6. Reports, Exports, and Audit Trails

### System Administrators

Recommended reading order:
1. Documentation Home
2. Access, Permissions, and Navigation
3. Administration Guide
4. REDCap Integration
5. Troubleshooting and FAQ

---

## Standard Page Template

Each user guide page should follow a consistent structure.

**Recommended template:**
- Title
- Purpose
- Who should use this page
- Before you begin
- Step-by-step instructions
- Field or option reference
- Warnings or limitations
- Common mistakes
- Related tasks

---

## Writing Principles

- Write for operational users rather than developers
- Prefer task-based headings over technical headings
- Use plain language for system concepts
- Explain why a task matters where that improves accuracy
- Flag irreversible or high-impact actions clearly
- Use examples for identifiers, statuses, and workflows
- Keep screenshots optional and avoid making them the primary source of truth

---

## Suggested Implementation Order

To build the documentation set efficiently, create the pages in this order:

1. Documentation Home
2. System Overview
3. Access, Permissions, and Navigation
4. Project and Study Setup
5. Participant Management
6. Specimen Logging and Tracking
7. Storage Management
8. Event Scheduling and Follow-up
9. Shipment and Manifest Management
10. Study and Assay Data Management
11. REDCap Integration
12. Reports, Exports, and Audit Trails
13. Troubleshooting and FAQ
14. Glossary
15. Administration Guide

---

## Future Enhancements

Once the core documentation is complete, consider adding:

- Quick-start guides by role
- Task checklists for routine operations
- Illustrated barcode and storage workflows
- Data governance guidance
- Release notes for user-facing changes
- Training materials for onboarding new teams

---

## Summary

The BRIMS user documentation should be structured around the lifecycle of research operations:

Project setup -> participant enrolment -> event management -> specimen handling -> storage -> shipment -> study and assay use -> reporting and troubleshooting.

This approach will make the documentation more usable for research teams than a menu-by-menu or table-by-table description of the system.