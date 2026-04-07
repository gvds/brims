# Shipment and Manifest Management

## Purpose

Use this page to prepare specimens for transfer, manage manifests, and confirm shipments on dispatch and receipt.

## Who Should Use This Page

- Laboratory staff
- Project administrators
- Staff responsible for specimen transfer between sites or facilities

## Before You Begin

Before you start, make sure the specimens are eligible for shipment, correctly identified, and reconciled against the intended transfer list.

## What a Manifest Is

A manifest is the shipment or transfer record that groups specimens together for movement between sites or facilities.

Use the manifest to track the transfer as a whole, while each specimen record tracks the individual sample.

## Creating a Manifest

Create a manifest when specimens are being sent together as part of the same transfer.

When creating the manifest, choose the destination site and the specimen types that may be added to that manifest.

The destination site cannot be the same as your current site.

## Adding Specimens

Add specimens carefully and review the list before dispatch.

Use the **Select Specimens to Add** action on an open manifest or upload a list with **Upload Specimens**.

Only specimens that match the manifest specimen types, belong to your current site, and are currently **Logged** or **In Storage** will be available for selection.

Before finalising the manifest, confirm that:

- Each specimen belongs on that shipment
- Barcodes and specimen details are correct
- No expected specimens are missing

> Screenshot placeholder: Add an open manifest with the Select Specimens to Add and Upload Specimens actions visible, plus the specimen list for the shipment.

## Manifest Statuses

Manifest statuses show where the transfer is in its lifecycle.

The current manifest statuses are **Open**, **Shipped**, and **Received**.

Use these statuses consistently so that other users can understand the current state of the transfer.

## Dispatch Workflow

Before dispatch, review the manifest, confirm the contents, and make sure the shipment has been packed and labelled correctly.

The system record should match what is physically leaving the site.

When samples are ready to be shipped, use **Ship the Manifest**. You can also choose to mark it as received immediately for transfer to sites that do not have a facility to confirm receipt in BRIMS.

> Visual placeholder: Add a manifest status flow diagram showing Open, Shipped, and Received, including specimen status changes during transfer.

## Receipt Workflow

When the shipment arrives, the receiving team should compare the delivered specimens with the manifest.

Confirm receipt promptly and record any discrepancies before the specimens move into routine storage or use.

Users at the destination site use **Receive the Manifest** to indicate receipt. The manifest is marked as received, the specimen site is updated, and the specimen status is set back to **Logged**.

## Reconciliation and Audit

If a shipment does not match the manifest, review the barcode list and specimen details carefully.

Record missing, unexpected, or damaged specimens according to your local procedure and escalate issues that cannot be resolved immediately.

Open manifests can also be edited or deleted. Once shipped, the manifest can be exported as a CSV file.

## Related Pages

- [Specimen Logging and Tracking](06-specimen-logging-and-tracking.md)
- [Storage Management](07-storage-management.md)
- [Reports, Exports, and Audit Trails](12-reports-exports-and-audit-trails.md)