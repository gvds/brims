# Event Scheduling and Follow-up

## Purpose

Use this page to manage participant events over the course of a study, from scheduling through to follow-up review.

## Who Should Use This Page

- Study coordinators
- Research nurses and field staff
- Data managers

## Before You Begin

Before you start, make sure participants have already been enrolled and that any project-specific follow-up rules are already configured.

## Understanding Events

In BRIMS, events represent scheduled study activities such as enrolment visits, follow-up visits, sample collection points, or other project-defined milestones.

Use events to track what should happen, what has already happened, and what still needs attention.

## Event Creation and Scheduling

BRIMS uses two related event layers:

- **Arm events** are the event templates configured under each study arm
- **Subject events** are the scheduled records created for a specific subject

When a subject is enrolled, BRIMS creates subject events from the templates attached to that subject's current arm.

When a subject switches arms, BRIMS cancels pending events from the old arm and creates a new set from the new arm.

Always check that the expected events are present after enrolment.

> Visual placeholder: Add a diagram showing the relationship between arm event templates and subject events generated at enrolment or arm switch.

### Configuring Arm Event Templates

Open an arm and use its **Events** section to define the schedule for that arm.

The event setup screen includes offset-based scheduling, ante and post windows, **Autolog**, **Repeatable**, and **Active** settings.

## Viewing Event Schedules

Open a subject record and review the **Subject Events** section to view scheduled events and follow-up activity.

Review event timelines regularly so you can identify:

- Upcoming visits
- Overdue follow-up
- Missed activities
- Items that still need outcome recording

> Screenshot placeholder: Add a subject record with the Subject Events section visible, including upcoming, overdue, and completed events.

## Recording Outcomes

Update each event as soon as the visit or study activity has taken place.

Record whether the event was completed, missed, rescheduled, or otherwise changed according to your project procedure.

Timely event updates help keep specimen logging and operational reporting accurate.

Scheduled subject events provide a **Log Event** action so you can record the log date and set the event outcome.

## Event Statuses

Use event statuses consistently so that study teams can see the true follow-up position of each participant.

Event statuses include:

- **Pending**
- **Primed**
- **Scheduled**
- **Logged**
- **Logged Late**
- **Missed**
- **Cancelled**

If your project uses more than one status, make sure staff agree on when each status should be applied.

## Linking Events to Specimens

When specimens are collected as part of a visit or follow-up activity, link them to the correct event wherever possible.

This helps preserve the connection between the participant timeline and the specimen record.

## Follow-up Review

Regular follow-up review helps teams identify participants who need attention.

Look for:

- Overdue events
- Missing outcomes
- Unexpected status patterns
- Events where specimen activity is incomplete or missing

Repeatable events may also show a **New Iteration** action when another occurrence needs to be added.

## Related Pages

- [Participant Management](04-participant-management.md)
- [Specimen Logging and Tracking](06-specimen-logging-and-tracking.md)
- [Search, Filters, and Operational Review](11-search-filters-and-operational-review.md)