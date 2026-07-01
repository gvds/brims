# BRIMS User Manual

## Chapter 6: Managing Specimen Storage

---

## Overview

This chapter is for laboratory staff and biobank staff responsible for placing specimens into storage and maintaining accurate storage records.

BRIMS manages specimen storage through an **allocation** model. Rather than placing specimens one by one into individual positions, you select the specimen types to be stored and BRIMS automatically assigns available locations based on the configured storage structure. This approach reduces manual placement errors and keeps the storage record consistent with the physical arrangement.

All storage work is done from **Specimen Storage** in the project navigation.

---

## 6.1 Storage Concepts

Before working with storage in BRIMS, it is helpful to understand how the storage structure is organised.

### Supported storage types

BRIMS supports three storage contexts, defined by the physical environment in which specimens are kept:

| Storage Type | Description |
|---|---|
| **Minus-80** | Ultra-low temperature freezer storage (−80 °C) |
| **Liquid Nitrogen** | Cryogenic vapour or liquid nitrogen storage |
| **Biorepository** | General controlled-temperature biorepository storage |

The storage type is defined at the unit definition level by an administrator. You do not need to select storage type during allocation — BRIMS routes specimens to the correct storage context based on how their specimen type has been configured.

### Storage hierarchy

Storage locations in BRIMS follow a hierarchy:

- **Unit definitions** describe the type and capacity of a storage unit (e.g. a 9×9 box, a rack with 100 positions)
- **Physical units** are the actual freezers, racks, or boxes that exist in the laboratory
- **Virtual units** represent the logical position assignments that link specimen types to available spaces
- **Locations** are individual positions within a virtual unit where a single specimen is placed

Administrators configure unit definitions and physical units (see [Chapter 2 — Setting Up a Project](02-project-setup.md) and the Administration Guide). Day-to-day laboratory work involves allocating specimens into the spaces that have been configured.

> **Tip:** If the allocation step reports that there is insufficient storage for a specimen type, this means the virtual units configured for that type have no free locations remaining. Contact your project administrator or laboratory manager to arrange additional storage configuration before attempting a new allocation.

---

## 6.2 Allocating Specimens to Storage

The allocation workflow places all eligible **Logged** specimens of the selected type into available storage locations in a single operation.

### Step-by-step

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

### Printing a storage allocation report

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

Refer to [Chapter 5 — Logging Specimens](05-specimen-logging.md) for instructions on using these bulk actions in the Specimens list.

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

*Previous chapter:* [Chapter 5 — Logging Specimens](05-specimen-logging.md)  
*Next chapter:* [Chapter 7 — Preparing and Receiving Shipments](07-shipments-and-manifests.md)
