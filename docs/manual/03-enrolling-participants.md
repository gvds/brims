# BRIMS User Manual

## Chapter 3: Enrolling and Managing Participants

---

## Overview

This chapter is for research nurses, study coordinators, and field staff responsible for enrolling participants and keeping their records accurate throughout a study.

In BRIMS, participants are recorded under **Subjects**. The term *subject* is used consistently across the system, and this manual uses *participant* and *subject* interchangeably. Each participant is assigned a unique **subject ID** that is generated automatically from the project's identifier settings.

Participant records are the foundation of the research workflow. A correctly enrolled subject — assigned to the right site and arm — ensures that events are scheduled correctly and that specimens and study data can be linked to the right person.

---

## 3.1 Before You Begin

Before enrolling participants, confirm that the following project setup steps have been completed:

- The project has been created with subject ID prefix and digit settings confirmed
- At least one study arm exists and has event templates assigned to it
- You have been added as a project member with a role that includes enrolment permissions
- You know which arm each participant should be assigned to

If any of these are missing, work with your project manager before proceeding. Enrolling a participant without the correct arm or event configuration will create gaps in the follow-up schedule.

---

## 3.2 How Subject Records Are Created

BRIMS generates subject records automatically when a project is active. These records appear in the **Subjects** list with a status of **Generated**.

A Generated subject has a subject ID already assigned, but no participant details have yet been confirmed. The record acts as a placeholder until a real participant is enrolled into it.

> **Important:** Do not create a new subject record from scratch if a Generated record is available. Generated records already have a subject ID allocated. Creating duplicates will cause identifier conflicts and gaps in the numbering sequence. Always use the **Enrol** action on an existing Generated record.

---

## 3.3 Enrolling a Participant

### Step-by-step

1. Navigate to the **Subjects** list in the project navigation.
2. Locate a record with status **Generated**. Use the search or filter tools if the list is long.
3. Select the record to open it, then use the **Enrol** action.
4. The enrolment form will open. Complete all required fields carefully.
5. Review the automatically generated subject ID to confirm it matches the expected project format.
6. Save the record.

![The Subjects list showing a Generated record, and the Enrol action button.]()

### Enrolment form fields

| Field | What to enter |
|---|---|
| **First Name** | The participant's first name. |
| **Last Name** | The participant's last name. |
| **Enrolment Date** | The date the participant was formally enrolled. This date is used to calculate event schedules. |
| **Site** | The project site where the participant is based. |
| **Manager** | The staff member responsible for this participant's follow-up. |
| **Arm** | The study arm the participant is being enrolled into. |

> **Tip — Enrolment date and event scheduling:** The enrolment date is used as the baseline from which all scheduled events are calculated. For example, if a follow-up event has an offset of 30 days, it will be scheduled 30 days from the enrolment date. Enter the true enrolment date rather than today's date unless these are the same. A wrong enrolment date will cause all follow-up windows to shift incorrectly, which is difficult to resolve later.

> **Tip — Arm assignment:** If your project uses manual arm allocation, make sure the arm is confirmed with the study team before saving. If the wrong arm is selected, events from that arm will be scheduled for the participant. Changing the arm later (via **Switch Arm**) cancels the existing pending events and creates a new schedule from the new arm, but this cannot recover events that have already been recorded.

Once enrolled, the participant's status changes to **Enrolled** and BRIMS creates all subject events defined by the arm's event templates.

---

## 3.4 Participant Statuses

Each subject record carries a status that reflects the participant's current position in the study.

| Status | Meaning |
|---|---|
| **Generated** | A subject ID has been created but no participant has been enrolled yet. |
| **Enrolled** | A participant has been enrolled and the record is active. |
| **Dropped** | The participant has withdrawn or been removed from active follow-up. |

Use these statuses consistently. Filtering the Subjects list by status is the most efficient way to identify which participants are active, which are generated and awaiting enrolment, and which have been dropped.

---

## 3.5 Updating a Participant Record

After enrolment, participant records may need to be updated if information changes — for example, if a site reassignment is required or if a field was entered incorrectly.

Use the **Edit** action on an enrolled subject's record.

> **Caution:** Changes to site assignment or arm assignment after enrolment can affect follow-up scheduling, specimen tracking, and reporting. Always confirm with your data manager before making structural changes to a participant record that is already associated with events or specimens.

---

## 3.6 Switching a Participant's Arm

If a participant moves from one study cohort to another — for example, from a control arm to a treatment arm — use the **Switch Arm** action on the subject record.

When an arm switch occurs, BRIMS:

1. Cancels all pending, primed, or scheduled events from the current arm
2. Records the previous arm and baseline date for audit purposes
3. Assigns the participant to the new arm
4. Creates a new set of subject events based on the new arm's event templates

The arm switch date is used as the new baseline for calculating the events in the new arm.

> **Caution:** Arm switching is a significant action. Events that have already been logged in the previous arm are not affected, but all pending events are cancelled and cannot be recovered. Confirm that the switch is correct and approved before proceeding.

If an arm switch was made in error, use **Revert Arm Switch** to return the participant to their previous arm. This action is available on the subject record while the switch can still be undone.

---

## 3.7 Dropping a Participant

Use the **Drop Subject** action to indicate that a participant has withdrawn or should no longer receive active follow-up.

A dropped participant's record remains in the system and can be reviewed, but they will no longer appear as active in operational lists by default.

If a participant who was dropped needs to be reinstated, use the **Re-Instate Subject** action on the subject record.

> **Tip:** Always record a participant as Dropped rather than attempting to delete their record. A dropped record preserves the full audit history of enrolment, events, and specimens. Deletion is not the appropriate action and may not be permitted depending on your role.

---

## 3.8 Avoiding Duplicate Records

Duplicate participant records are one of the most disruptive data quality problems in a research database. Before enrolling a participant, always:

1. Search the Subjects list using the participant's name or expected subject ID
2. Confirm that no existing record already exists for this individual
3. If a possible duplicate is found, check with your data manager before proceeding

The subject ID format is designed to be unique. If two records appear to represent the same person, do not attempt to resolve the duplication yourself — escalate to your data manager.

---

## 3.9 Reviewing a Participant's History

Open a subject record to review the full history of a participant's involvement in the project.

![A subject record view showing enrolment details, Subject Events section, linked specimens, and arm assignment history.]()

From a subject record you can review:

- Enrolment details and the subject ID
- Current site and arm assignment
- The full list of scheduled and completed events (in the **Subject Events** section)
- Specimens linked to this participant
- Arm switching history if the participant has moved between arms

This history is useful when following up on overdue events, resolving data queries, or preparing for a participant's next visit.

---

## Summary

| Task | Where to go |
|---|---|
| Enrol a participant | **Subjects** list → Generated record → **Enrol** |
| Edit a participant | **Subjects** list → Enrolled record → **Edit** |
| Switch a participant's arm | Subject record → **Switch Arm** |
| Drop a participant | Subject record → **Drop Subject** |
| Re-instate a participant | Subject record → **Re-Instate Subject** |
| View scheduled events | Subject record → **Subject Events** section |

---

*Previous chapter:* [Chapter 2 — Setting Up a Project](02-project-setup.md)  
*Next chapter:* [Chapter 4 — Recording Events and Follow-up](04-events-and-follow-up.md)
