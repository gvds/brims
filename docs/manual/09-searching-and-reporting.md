# BRIMS User Manual

## Chapter 9: Searching, Reviewing, and Exporting Data

---

## Overview

This chapter is for research coordinators, data managers, and project managers who need to find records, review operational progress, or export data for offline use.

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

### Review outstanding follow-up events

Navigate to a **Participant** record and view the **Events** tab. Events highlighted in red are overdue and awaiting logging. You can also scan the event list for Scheduled events that have passed their expected date.

See [Chapter 4 — Recording Events and Follow-up](04-events-and-follow-up.md) for more detail.

### Review specimens awaiting storage

Navigate to **Specimens** and filter or sort by **Status**. Specimens with a status of **Logged** have been collected but not yet allocated to storage. Use this view to identify batches ready for the next storage allocation run.

### Review incoming shipments awaiting receipt

Navigate to **Manifests** and filter by status **Shipped**. These manifests have been dispatched and are waiting to be received at the destination site.

### Review specimens allocated to a study

Open a **Study** record and navigate to the **Specimens** section. This lists all specimens linked to the study, allowing you to verify that the correct specimens are included.

---

## 9.4 Exporting Data

BRIMS provides CSV export functions for the main operational record types. Exports are generated asynchronously — BRIMS will notify you when the file is ready to download.

> **Important — data sensitivity:** Exports may include participant identifiers, contact details, and specimen data. Export only the minimum data needed for your task and share the files only with authorised recipients. Do not store exported files in unsecured locations.

### Export entry points

| Export | How to access | What is included |
|---|---|---|
| **Export Subjects** | Open the **Project** record → **Export Subjects** button | Subject ID, site, enrolled-by user, name, address, enrolment date, arm, arm baseline date, status |
| **Export Subject Events** | Open the **Project** record → **Export Subject Events** button | Subject ID, event name, iteration, status, label status, event date, min date, max date, log date |
| **Export Specimens** (project-wide) | Open the **Project** record → **Export Specimens** button | Barcode, subject ID, event name, event iteration, specimen type, site, status, parent barcode, aliquot, volume, volume unit, thaw count, logged-by user, log date, used-by user, used date |
| **Export Specimens** (from Specimens list) | **Specimens** list → select rows → **Export** | Same columns as above for the selected records |
| **Export Specimens** (from a study) | **Studies** → open a study → **Specimens** section → **Export** | Barcode, specimen type, site, arm, event, event iteration, subject ID, log date |

> **Tip:** The project-level exports (**Export Subjects**, **Export Subject Events**, **Export Specimens**) are found in the Export actions group on the **Project** detail page (accessed by clicking on the project name in the project navigation or selecting the project from the main list). Use these when you need a complete project-level data extract.

### Columns that are hidden by default

Some columns in the specimen export are hidden by default but can be included when you configure the export:

- Parent barcode
- Logged-by user
- Log date
- Used-by user
- Used date

When prompted to configure columns before confirming an export, review the column list and enable any additional columns needed for your task.

---

## 9.4.1 Data Validation Before Sharing

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

Refer to [Chapter 6 — Managing Specimen Storage](06-storage-management.md) for details.

---

## Summary

| Task | Where to go |
|---|---|
| Find a participant | **Subjects** list → search by subject ID or name |
| Find a specimen by barcode | **Specimens** list → search by barcode |
| Find shipped manifests awaiting receipt | **Manifests** list → filter by status Shipped |
| Find specimens waiting to be stored | **Specimens** list → filter or sort by status (Logged) |
| Export participant data | **Project** detail page → **Export Subjects** |
| Export event data | **Project** detail page → **Export Subject Events** |
| Export specimen data (full project) | **Project** detail page → **Export Specimens** |
| Export specimen data (selected records) | **Specimens** list → select rows → **Export** |
| Export study specimens | **Studies** → open the study → **Specimens** section → **Export** |

---

*Previous chapter:* [Chapter 8 — Studies and Assay Data](08-studies-and-assays.md)  
*Next chapter:* [Chapter 10 — Troubleshooting Common Problems](10-troubleshooting.md)  
*See also:* [Chapter 12 — REDCap Integration](12-redcap-integration.md) | [Chapter 13 — Administration Guide](13-administration-guide.md)
