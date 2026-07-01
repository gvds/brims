# BRIMS User Manual

## Chapter 5: Logging Specimens

---

## Overview

This chapter is for laboratory staff and research staff responsible for logging biological specimens into BRIMS.

Specimens are the central data objects for laboratory workflows in BRIMS. Every specimen needs to be logged — assigned a barcode, linked to a participant and event, and assigned a type — before it can be stored, shipped, or associated with a study. This chapter covers the two logging workflows (primary and derivative), the status system, and how to manage specimens from the list view.

---

## 5.1 Before You Begin

Before logging specimens, confirm that:

- The participant record exists and is enrolled
- The relevant subject event has been scheduled
- Specimen types and labware have been configured for the project (see [Chapter 2 — Setting Up a Project](02-project-setup.md))
- You have the physical barcodes ready for scanning

If the participant is not yet enrolled or the event has not been created, logging cannot proceed correctly. Return to [Chapter 3 — Enrolling and Managing Participants](03-enrolling-participants.md) or [Chapter 4 — Recording Events and Follow-up](04-events-and-follow-up.md) if those steps are incomplete.

---

## 5.2 Primary Specimen Logging

Primary specimens are samples collected directly from a participant. They are logged through a dedicated two-stage workflow accessed from **Log Primary Specimens (2-Stage)** in the project navigation.

### Stage 1 — Scan the Project Subject Event Barcode

The first stage identifies the participant and event that the specimens belong to.

1. Navigate to **Log Primary Specimens (2-Stage)**.
2. In the **Project Subject Event Barcode** field, scan or enter the PSE barcode from the participant's label.
3. Press Enter to confirm.

BRIMS will validate the barcode and load the participant and event details. Confirm that the participant name and event shown on screen match the physical sample before continuing.

> **Tip:** The PSE barcode (Project Subject Event barcode) encodes the participant and event in a single scannable code. If the barcode is not recognised at this stage, check that the barcode matches the correct project and that the subject event has been correctly scheduled. A barcode from a different project will not be accepted.

### Stage 2 — Scan Specimen Barcodes and Enter Volumes

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

### Stage 1 — Select the parent specimen

You have two routes to identify the parent specimen:

- **Scan the parent barcode directly:** Enter the barcode of the primary specimen this derivative was processed from.
- **Scan a PSE barcode and select from a list:** Scan the Project Subject Event barcode and then choose the correct parent specimen from the list of eligible specimens linked to that event.

### Stage 2 — Scan derivative barcodes and enter volumes

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

The **Specimens** list (accessible from the project navigation) shows all specimens logged in the current project, with columns for barcode, event, specimen type, site, status, aliquot number, logged-by user, and log date.

Use the search fields at the top of each column to find specimens by barcode, type, site, or status.

![The Specimens list showing search fields per column and the bulk action menu.]()

### Bulk actions

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

---

## 5.6 Editing a Specimen Record

Individual specimen records can be edited from the Specimens list using the **Edit** action on a row.

The edit form allows updates to:

- Specimen type and site
- Status
- Aliquot number and volume
- Thaw count
- Logged-by user and log date
- Logged-out-by user
- Used-by user and used date
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
| Log selected specimens as used | **Specimens** list → select rows → **Log as Used** |
| Log selected specimens out of storage | **Specimens** list → select rows → **Log Out** |
| Return specimens to storage | **Specimens** list → select rows → **Log Return** |
| Edit a specimen record | **Specimens** list → row → **Edit** |

---

*Previous chapter:* [Chapter 4 — Recording Events and Follow-up](04-events-and-follow-up.md)  
*Next chapter:* [Chapter 6 — Managing Specimen Storage](06-storage-management.md)
