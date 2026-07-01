# BRIMS User Manual

## Chapter 10: Troubleshooting Common Problems

---

## Overview

This chapter is for all BRIMS users. It covers the most commonly encountered problems, how to diagnose them, and when to escalate to an administrator.

Before troubleshooting, gather the relevant reference information: project name, subject ID, specimen barcode, manifest ID, or study identifier. Having these details at hand makes diagnosis faster and ensures you can provide accurate information if you need to escalate the issue.

---

## 10.1 Signing In and Access

### I cannot sign in

- Confirm you are entering the correct email address and password.
- Check whether your account has been created — contact your project administrator if you are new to the system.
- If you have forgotten your password, use the password reset option on the sign-in page.

### I can sign in but I cannot see a project

- Confirm that you have been added to the project with an appropriate role. A project must have a member record for you.
- Check with a project manager or administrator to confirm that your membership is set up and that your role is correct.
- If you have just been added, try signing out and back in to refresh your session.

### I can see the project but I cannot access a specific page within it

- Some pages are restricted to specific project roles. Your current role may not include the permissions required.
- Contact a project manager to review and adjust your role if necessary.

---

## 10.2 Participants

### I cannot find a participant

- Search by subject ID using the search field in the Subjects list.
- Check whether the participant's record has a status that might cause it to appear on a separate view (for example, subjects with Generated status have not yet been enrolled).
- Confirm you are in the correct project — participant records exist within a specific project.

### A participant is missing from the list but I know they were enrolled

- Check whether they have been dropped. Use the status filter on the Subjects list and look for records with status Dropped.
- If the record does not appear at all, the participant may have been enrolled under a different subject ID or in a different project. Check with your study coordinator.

### I cannot enrol a subject

- The subject must have a status of **Generated** to be enrolled. If the record shows **Enrolled** or **Dropped**, the action will not appear.
- If you cannot see the **Enrol** action at all, you may not have permission for this action. Contact your project manager.

### The wrong arm is linked to a participant

- Review the current arm assignment on the participant record.
- If the arm needs to be changed, see [Chapter 3 — Enrolling and Managing Participants, section 3.6](03-enrolling-participants.md) for the arm switch process. Note that switching arm will cancel any pending events on the old arm.

---

## 10.3 Events

### I cannot see the Log Event button

- The **Log Event** action is only shown on events that have a status of **Scheduled**. If the event is still **Pending** or **Primed**, it cannot be logged yet.
- If you believe the event should be Scheduled, check the event date and confirm the event has been correctly set up in the project's arm and event template.

### An event date appears wrong

- Event dates are calculated from the arm baseline date (the participant's enrolment date). If the event date looks wrong, check the enrolment date on the participant record.
- If the event template itself has incorrect day offsets, a project manager will need to edit the arm event configuration.

### I logged an event but the status did not change

- Refresh the page and confirm the status is still showing the old value. Network timing occasionally causes a display lag.
- If the issue persists, check whether another user may have simultaneously updated the record.

---

## 10.4 Specimen Logging

### A barcode is not recognised at the PSE scan stage

- Check that the barcode was scanned correctly and that the physical label matches the expected format.
- Confirm that you are in the correct project. PSE barcodes are project-specific.
- Confirm that the subject event was created and that the event is associated with a participant in this project.

### A specimen barcode fails validation

- BRIMS validates specimen barcodes against the regex pattern defined in the labware configuration for that specimen type. If the barcode does not match, it will be rejected.
- Check the physical label for scanning errors, or contact your project manager to confirm the expected barcode format.

### I cannot see the specimen I just logged

- Search the Specimens list by barcode. If it was logged successfully, it will appear with a status of **Logged**.
- If it does not appear, the logging workflow may not have been fully submitted. Repeat the logging process, ensuring all required fields are completed before submitting.

### A specimen has the wrong status

- Review the specimen record and check all status fields and timestamps.
- If the status needs to be corrected and there is a clear operational reason to do so, an authorised user can edit the record directly (see [Chapter 5 — Logging Specimens, section 5.6](05-specimen-logging.md)).
- Where possible, use the logging workflows (Log Out, Log Return, Log as Used) rather than editing status directly, as these preserve the correct audit trail.

---

## 10.5 Storage

### A specimen type is disabled in the Allocate Storage form

- This means there are insufficient free locations configured for that specimen type in the current project's virtual storage units.
- Contact your laboratory manager or project administrator to arrange additional storage capacity before attempting a new allocation.

### A specimen does not appear in the allocation form

- The allocation page only shows specimens with a status of **Logged** at your current site that have a storage specimen type assigned. If a specimen is in a different status (e.g. already In Storage or Transferred), it will not appear.
- Confirm the specimen status in the Specimens list.

---

## 10.6 Manifests and Shipments

### I cannot edit a manifest

- Manifests can only be edited when their status is **Open**. Once a manifest has been shipped it becomes read-only.
- If a manifest was shipped in error, contact your project administrator.

### I cannot see the Receive Manifest button

- The **Receive the Manifest** action is only visible to users whose project site assignment matches the manifest's destination site. If you are not at the destination site, you will not see this button.
- Confirm your site assignment with your project manager.

### Specimens on a manifest have the wrong prior status

- The prior status recorded on each specimen in a manifest reflects the status at the time the specimen was attached. It cannot be edited.
- If there is a discrepancy, review the specimen record's history and, if necessary, contact your project administrator.

---

## 10.7 Studies and Assays

### I cannot add specimens or assays to a study

- Check whether the study has the **Locked** toggle enabled. A locked study is read-only. Contact the study owner or project manager to unlock it if changes are needed.

### An assay definition is missing from the dropdown

- Assay definitions are managed at the system level by an administrator. If the definition you need does not appear, contact your system administrator.

---

## 10.8 When to Escalate

Escalate an issue to your project administrator or system administrator when:

- The problem appears to be permission-related and reconfirming your role has not resolved it
- Data appears inconsistent across linked records (e.g., a specimen linked to the wrong participant or event)
- A shipment or storage discrepancy cannot be reconciled through the standard views
- A REDCap integration issue persists after confirming the project and token settings
- A barcode or validation error recurs after confirming the physical labels are correct

### Information to include when reporting an issue

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
| Cannot sign in | Email/password and account existence |
| Project not visible | Project membership and role |
| Participant not found | Search by subject ID; check status filter |
| Log Event button missing | Check event status — must be Scheduled |
| Barcode rejected at PSE scan | Correct project selected; event exists |
| Specimen barcode fails | Labware barcode format vs physical label |
| Specimen type disabled in allocation | Insufficient storage capacity configured |
| Cannot edit manifest | Status must be Open |
| Cannot receive manifest | Your site must match the destination site |
| Cannot edit study | Check whether Locked is enabled |

---

*Previous chapter:* [Chapter 9 — Searching, Reviewing, and Exporting Data](09-searching-and-reporting.md)  
*Next chapter:* [Chapter 11 — Glossary](11-glossary.md)  
*See also:* [Chapter 12 — REDCap Integration](12-redcap-integration.md) | [Chapter 13 — Administration Guide](13-administration-guide.md)
