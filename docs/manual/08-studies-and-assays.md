# BRIMS User Manual

## Chapter 8: Studies and Assay Data

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

### Adding an assay

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

### Assay definitions

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

## Summary

| Task | Where to go |
|---|---|
| Create a new study | **Studies** → **New Study** |
| Add specimens to a study | **Studies** → open the record → **Specimens** section |
| Add an assay to a study | **Studies** → open the record → **Assays** section → **New Assay** |
| Download an assay file | **Studies** → open the record → **Assays** section → **Download** |
| Lock a study | **Studies** → open the record → **Edit** → enable **Locked** |
| Review all project studies | **Studies** list |

---

*Previous chapter:* [Chapter 7 — Preparing and Receiving Shipments](07-shipments-and-manifests.md)  
*Next chapter:* [Chapter 9 — Searching, Reviewing, and Exporting Data](09-searching-and-reporting.md)
