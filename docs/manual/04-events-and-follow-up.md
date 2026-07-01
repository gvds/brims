# BRIMS User Manual

## Chapter 4: Recording Events and Follow-up

---

## Overview

This chapter is for research nurses, study coordinators, and field staff responsible for monitoring participant follow-up schedules and recording the outcomes of study activities.

In BRIMS, follow-up activities are managed through **events**. An event represents a single study activity for a participant — such as a screening visit, a monthly follow-up, or a specimen collection point. Events are scheduled automatically when a participant is enrolled, and they are updated as activities are completed, missed, or rescheduled.

Keeping event records current is essential. Overdue or unrecorded events affect follow-up reports and, where events are linked to specimen collection, can create gaps in the specimen record.

---

## 4.1 Understanding Events

### Arm events and subject events

BRIMS uses two layers of event records:

- **Arm event templates** are the schedule definitions set up by the project manager within each study arm (see [Chapter 2 — Setting Up a Project](02-project-setup.md)). They define what events happen, in what order, and within what timing windows.
- **Subject events** are the individual scheduled records created for a specific participant when they are enrolled or when their arm changes. Each subject event is a copy of an arm event template, dated based on the participant's enrolment date and the event's offset.

You will work almost exclusively with subject events in day-to-day follow-up.

### How event dates are calculated

When a participant is enrolled, BRIMS uses their enrolment date as the baseline and calculates a scheduled date for each event using the arm's offset settings.

For example, if an event has an offset of 14 days, it will be scheduled 14 days from the enrolment date. The ante and post windows around that date define the range within which the event is considered on time.

> **Tip:** Understanding this date logic helps you interpret the event list correctly. An event showing in red in the Subject Events table means the scheduled date has passed and the event is still unrecorded. This is not a system error — it is an operational signal that the activity needs attention.

---

## 4.2 Viewing a Participant's Event Schedule

To view the events scheduled for a specific participant:

1. Navigate to **Subjects** and open the participant's record.
2. Scroll to the **Subject Events** section.

![A subject record showing the Subject Events section with event names, scheduled dates, windows, status, and log date columns.]()

The Subject Events table shows:

| Column | What it shows |
|---|---|
| **Arm** | The arm the event belongs to |
| **Event Name** | The name of the scheduled activity |
| **Status** | The current status of this event instance |
| **Event Date** | The scheduled date for this event |
| **Min Date** | The earliest date the event can be recorded as on time |
| **Max Date** | The latest date the event can be recorded as on time |
| **Log Date** | The date the event was actually recorded, if logged |
| **Repeatable** | Whether additional iterations can be added |
| **Iteration** | The iteration number (for repeatable events) |

Events where the scheduled date has passed and the status is still unlogged are highlighted in red. These require prompt attention.

---

## 4.3 Event Statuses

Each subject event has a status that reflects where it is in its lifecycle.

| Status | Meaning |
|---|---|
| **Pending** | The event is scheduled for the future and is not yet ready to be logged. |
| **Primed** | The event is approaching its scheduled date and is ready to be actioned. |
| **Scheduled** | The event is due — the **Log Event** action will be available. |
| **Logged** | The event was recorded on time, within the acceptable date window. |
| **Logged Late** | The event was recorded after the acceptable post-window date. |
| **Missed** | The event was not completed and was recorded as missed. |
| **Cancelled** | The event was cancelled (typically due to an arm switch). |

> **Tip:** The **Log Event** action only appears on events with a **Scheduled** status. If an event is still **Pending** or **Primed**, it is not yet ready to log. If you need to record an outcome for an event that has passed its window, update the status to **Missed** directly in the status column if you have permission, or contact your data manager.

---

## 4.4 Recording an Event Outcome

When a participant completes a scheduled study activity, record the outcome in BRIMS as soon as possible.

To log an event:

1. Open the participant's record and navigate to the **Subject Events** section.
2. Find the event with status **Scheduled**.
3. Select the **Log Event** action on that row.
4. Enter the **Log Date** — the date the activity actually took place.
5. Select the **Event Status**: either **Logged** (completed) or **Missed** (not completed).
6. Confirm and save.

![The Log Event action dialog showing the Log Date field and Event Status selection.]()

BRIMS will update the event status to **Logged** or **Missed** accordingly. If the log date falls outside the acceptable window, the status will be set to **Logged Late** automatically.

> **Tip — Recording missed events promptly:** If a participant did not attend a scheduled visit, record the event as **Missed** as soon as this is confirmed rather than leaving it as **Scheduled**. This keeps operational reports accurate and ensures that follow-up planning reflects the true state of each participant's schedule.

> **Tip — Log date accuracy:** Enter the date the activity actually occurred, not the date you are entering the record. If there is a delay between the visit and data entry, the log date should still reflect the real activity date. BRIMS will compare the log date against the scheduling window to determine whether the event was on time or late.

---

## 4.5 Repeatable Events

Some events are configured as **repeatable**, meaning additional iterations can be scheduled after the first one has been completed.

A repeatable event will show a **New Iteration** action when:

- The event is repeatable (confirmed by the tick in the Repeatable column)
- The current iteration is the most recent one for that event
- The participant is still enrolled

To add a new iteration:

1. Find the repeatable event in the Subject Events list.
2. Select the **New Iteration** action.
3. Enter the date for the new iteration.
4. Confirm.

The new iteration will appear in the Subject Events list and can be logged in the same way as any other event.

> **Tip:** Repeatable events are typically used for ongoing follow-up visits that recur at defined intervals — for example, monthly clinical reviews or quarterly specimen collections. If you are unsure whether a new iteration should be added, check your study protocol or ask your project manager.

---

## 4.6 Updating an Event Status Directly

For users with the appropriate permissions, event status can be updated directly in the status column of the Subject Events table — for example, to mark an event as **Missed** when the **Log Event** button is not available.

Select the status dropdown in the row you want to update and choose the new status.

> **Caution:** Direct status updates bypass the log date confirmation step. Only use this approach when instructed by your data manager, and always record the reason for the change in your study records. Changes made directly to the status column are still recorded in the system audit trail.

---

## 4.7 Follow-up Review

Regular follow-up review helps identify participants who need attention before they fall through the gaps.

Use the **Subject Events** section on individual subject records to check:

- Events that are overdue (highlighted in red)
- Events that are approaching their post-window date
- Participants with a high number of missed events

For a broader view across all participants, use filters and search tools in the **Subjects** list to identify active participants and review their events. See [Chapter 9 — Searching, Reviewing, and Exporting Data](09-searching-and-reporting.md) for filter and export options.

---

## Summary

| Task | Where to go |
|---|---|
| View a participant's event schedule | Subject record → **Subject Events** section |
| Record a visit outcome | Subject Events → **Log Event** action |
| Record a missed event | Subject Events → **Log Event** → select Missed |
| Add another iteration of a repeatable event | Subject Events → **New Iteration** action |
| Update a status directly | Subject Events → status dropdown (permission required) |
| Review overdue events | Subject record → events highlighted in red |

---

*Previous chapter:* [Chapter 3 — Enrolling and Managing Participants](03-enrolling-participants.md)  
*Next chapter:* [Chapter 5 — Logging Specimens](05-specimen-logging.md)
