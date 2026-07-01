# BRIMS User Manual

## Chapter 7: Preparing and Receiving Shipments

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
   - Have a status of **Logged** or **In Storage**
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

## Summary

| Task | Where to go |
|---|---|
| Create a new manifest | **Manifests** → **New Manifest** |
| Add specimens to a manifest | **Manifests** → open the record → **Specimens** section → **Select Specimens to Add** |
| Ship (dispatch) a manifest | **Manifests** → open the record → **Ship the Manifest** |
| Receive an incoming shipment | **Manifests** → open the Shipped record → **Receive the Manifest** |
| Review all manifests for the project | **Manifests** list |

---

*Previous chapter:* [Chapter 6 — Managing Specimen Storage](06-storage-management.md)  
*Next chapter:* [Chapter 8 — Studies and Assay Data](08-studies-and-assays.md)
