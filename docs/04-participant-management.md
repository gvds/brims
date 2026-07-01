# Participant Management

## Purpose

Use this page to enrol participants, assign them correctly, keep their records up to date, and review their project history.

BRIMS uses the term **subject ID** for the unique identifier assigned to each participant.

Participant records are managed under **Subjects**.

## Who Should Use This Page

- Study coordinators
- Research nurses and field staff
- Data managers

## Before You Begin

Before you start, make sure the project has been configured correctly, including identifier rules, sites, and study arms.

## Participant Enrolment Workflow

Participant enrolment usually follows this sequence:

1. Open the **Subjects** list and find the subject record in **Generated** status
2. Use the **Enrol** action on that record
3. Confirm or enter the participant details shown in the enrolment form
4. Check the automatically generated subject ID
5. Confirm the enrolment date, site, manager, and current study arm

Complete enrolment carefully, because the participant record is the starting point for event scheduling, specimen logging, and study tracking.

> Screenshot placeholder: Add the Subjects list with a Generated record and the Enrol action, plus the enrolment form showing subject ID, site, and arm fields.

## Subject Identifier Allocation

BRIMS generates a subject ID automatically using the project prefix and digit settings defined during project setup.

For example, a project with prefix `BRI` and 6 digits will generate IDs such as `BRI000001`.

Before saving a new participant, confirm that the generated ID matches the project format you expect.

Use the **Subjects** list to search for an existing subject ID before assuming a subject has not yet been generated.

## Assigning Participants

Each participant should be assigned to the correct project site and study arm.

Site assignment helps keep operational work organised, and arm assignment determines how the participant is grouped for study activities and follow-up.

If your project uses manual arm allocation, make sure the correct arm is selected during enrolment or as soon as possible afterwards.

## Updating Participant Records

Participant records may need to be updated when information changes or when missing details are added later.

When editing a participant record:

- Confirm that you are working on the correct participant
- Review site and arm assignments carefully
- Be cautious when changing values that affect follow-up or specimen tracking

The **Edit** action is available for enrolled subjects. Generated subjects need to enrolled using the **Enrol** function.

## Participant Statuses

Participant statuses help show where a participant is in the study process.

Subject statuses include:

- **Generated**
- **Enrolled**
- **Dropped**

Use these statuses consistently so that enrolment, follow-up, and reporting remain accurate.

If you are unsure which status to use, check your project procedure before updating the record.

From the subject view, users can also **Drop Subject** or **Re-Instate Subject** where appropriate.

## Avoiding Duplicate Records

- Search before creating a new participant
- Confirm identifiers and demographics carefully
- Use project-specific procedures for duplicate resolution

## Reviewing Participant History

Use the participant record to review related project activity, including:

- Enrolment details
- Site and arm assignment
- Scheduled and completed events
- Linked specimens
- Study activity connected to the participant

Review this history when you need to check follow-up progress or resolve data questions.

If arm switching is allowed, the subject view also provides **Switch Arm** and **Revert Arm Switch** actions.

> Screenshot placeholder: Add a subject record view showing enrolment details, Subject Events, linked specimens, and arm-switch actions.

## Related Pages

- [Event Scheduling and Follow-up](05-event-scheduling-and-follow-up.md)
- [Specimen Logging and Tracking](06-specimen-logging-and-tracking.md)
- [Troubleshooting and FAQ](14-troubleshooting-and-faq.md)