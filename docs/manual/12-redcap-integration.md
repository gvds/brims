# BRIMS User Manual

## Chapter 12: REDCap Integration

---

## Overview

This chapter is for data managers, project administrators, and system administrators who need to configure or troubleshoot REDCap-linked workflows in BRIMS.

BRIMS can be linked to REDCap for projects that use REDCap alongside operational specimen and study workflows. This integration allows project activity in BRIMS to connect with participant data already managed in REDCap.

Not all projects use REDCap. If you are unsure whether your project requires REDCap integration, check with your project administrator before proceeding.

---

## 12.1 Integration Overview

When a BRIMS project is linked to a REDCap project, certain participant and workflow data can be exchanged between the two systems.

REDCap-linked projects are created differently from standard BRIMS projects. They must be set up using the dedicated **Create New REDCap-Linked Project** workflow rather than the standard project creation form.

> **Caution:** If a project that requires REDCap integration is created using the standard project form, it cannot be linked to REDCap after the fact. Always confirm whether REDCap integration is required before creating a new project. See [Chapter 2 — Setting Up a Project](02-project-setup.md) for guidance on project creation.

---

## 12.2 Creating a REDCap-Linked Project

1. Navigate to the team **Projects** section.
2. Select **Create New REDCap-Linked Project** (not the standard **New Project** option).
3. Select the REDCap project to link from the available options.
4. Complete the remaining project setup fields as you would for a standard project.
5. Save the project.

Once created, the linked project will appear in the team Projects list with a **REDCap Linked** indicator. Confirm this indicator is present before users begin working with live data.

> **Caution:** Make sure the correct REDCap project is linked to the correct BRIMS project before any live data entry begins. Linking the wrong REDCap project can result in data being associated with the wrong source records.

---

## 12.3 User Tokens and Access

Some REDCap functions depend on a user-specific API token stored on the project member record. This token authorises the individual member's access to the linked REDCap project.

To add or update a REDCap token for a project member:

1. Open the project's member list from **Configure Project Details** → **Members**.
2. Find the relevant member and select **Edit**.
3. In the **REDCap Token** field, enter or update the token value.
4. Save the member record.

> **Important:** Only enter a token for the correct project member. Tokens are user-specific. If a member's REDCap access changes or a token is replaced, update the stored value promptly to avoid disrupted workflows.

The REDCap Token field is only visible on member records within REDCap-linked projects.

---

## 12.4 Confirming the Setup

After creating a REDCap-linked project and configuring member tokens, verify that the expected REDCap-connected functions are available and behaving correctly:

1. Have an affected user sign in and confirm that REDCap-dependent workflow options are visible.
2. Run a test action that relies on the REDCap link and confirm that the expected data is returned.
3. If issues are found, refer to the troubleshooting steps in [section 12.5](#125-troubleshooting-redcap-integration) below.

---

## 12.5 Troubleshooting REDCap Integration

| Symptom | First thing to check |
|---|---|
| REDCap-linked options do not appear | Confirm the project was created as a REDCap-linked project, not a standard project |
| A user cannot access REDCap-dependent functions | Check that a valid token is stored on that user's project member record |
| Data expected from REDCap is missing | Verify that the correct REDCap project is linked; check API access and permissions |
| Sync results appear inconsistent | Confirm that the token is current and that REDCap-side permissions have not changed |

### Step-by-step troubleshooting

When a REDCap integration issue is reported:

1. Confirm that the correct **REDCap-linked project** was created (the team Projects list should show the **REDCap Linked** indicator).
2. Check that the affected user has the correct REDCap access in the REDCap system itself.
3. Verify that a correct, current token is stored on the user's project member record in BRIMS.
4. Review whether the action that failed actually depends on REDCap for that specific workflow step, or whether the issue may be unrelated to the integration.
5. If the issue cannot be resolved through the above steps, escalate to a system administrator for configuration review.

---

## Summary

| Task | Where to go |
|---|---|
| Create a REDCap-linked project | Team **Projects** → **Create New REDCap-Linked Project** |
| Verify the REDCap link is active | Team **Projects** list — check for the **REDCap Linked** indicator |
| Add or update a member's REDCap token | **Configure Project Details** → **Members** → edit the member record |
| Troubleshoot integration issues | See [section 12.5](#125-troubleshooting-redcap-integration) above |

---

*Previous chapter:* [Chapter 11 — Glossary](11-glossary.md)  
*Next chapter:* [Chapter 13 — Administration Guide](13-administration-guide.md)
