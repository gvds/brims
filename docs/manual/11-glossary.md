# BRIMS User Manual

## Chapter 11: Glossary

---

This glossary defines the key terms used in BRIMS and throughout this manual. Where a term has a specific technical or operational meaning in the system, that meaning is given here.

---

### Aliquot

A single tube or container within a set of identical samples derived from the same source. When a specimen type has multiple aliquots, each aliquot is a separate specimen record with its own barcode.

### Arm

A study arm is a participant grouping within a project — for example, a control group or an intervention group. Each arm has its own sequence of event templates, and participants are enrolled into a single arm. See also: **Event Template**, **Enrolment**.

### Arm Baseline Date

The date used as the starting point for calculating event dates in a participant's arm. This is set automatically to the participant's enrolment date when they are enrolled.

### Assay

A record of a laboratory analysis, measurement, or test run within a study. Each assay is linked to a study, associated with an assay definition, and records the technology platform used. See also: **Assay Definition**, **Study**.

### Assay Definition

A pre-configured template that defines the structure of an assay record, including any additional data fields specific to that assay type. Assay definitions are managed by system administrators and are available to all projects.

### Biorepository

One of the three supported storage types in BRIMS. Refers to general controlled-temperature biorepository storage. See also: **Storage Type**.

### Derivative Specimen

A specimen derived or processed from a primary specimen — for example, plasma separated from whole blood. Derivative specimens are logged through the **Log Derivative Specimens** workflow and carry a link to their parent specimen. See also: **Primary Specimen**, **Parent Specimen**.

### Enrolment

The process of formally registering a participant into a project arm. Enrolment moves the participant's status from **Generated** to **Enrolled** and sets the arm baseline date used for event scheduling.

### Event

A scheduled or completed activity linked to a participant — such as a study visit, follow-up contact, or specimen collection point. Events are defined as templates within an arm and are instantiated as subject-level event records. See also: **Event Template**, **Subject Event**.

### Event Status

The current stage of a subject event in the workflow. Possible statuses are: Pending, Primed, Scheduled, Logged, Logged Late, Missed, and Cancelled.

### Event Template

The definition of a recurring event used within an arm, specifying its name, expected day offset from the arm baseline date, acceptable date window, and whether it is repeatable. Each enrolled participant gets a subject event record generated from each template in their arm.

### Labware

A type of container (tube, vial, plate etc.) associated with a specimen type, including its barcode format. Labware configuration determines which barcodes will be accepted when specimens of that type are logged.

### Liquid Nitrogen

One of the three supported storage types in BRIMS. Refers to cryogenic vapour or liquid nitrogen storage. See also: **Storage Type**.

### Manifest

A shipment record that groups specimens together for transfer between sites. A manifest moves through three statuses: **Open**, **Shipped**, and **Received**. See also: **Manifest Status**.

### Manifest Status

The current stage of a manifest in the transfer workflow. Possible statuses are: Open (being prepared), Shipped (dispatched by the sending site), and Received (confirmed by the receiving site).

### Minus-80

One of the three supported storage types in BRIMS. Refers to ultra-low temperature freezer storage at −80 °C. See also: **Storage Type**.

### Parent Specimen

The primary specimen from which a derivative specimen was processed. The parent-child relationship is recorded at the time of derivative logging and is shown on the derivative specimen's record.

### Participant

A person enrolled in a research project. In the BRIMS user interface, participant records appear under the label **Subjects**. The terms participant and subject are used interchangeably in this manual to match both clinical and technical usage.

### Primary Specimen

A specimen collected directly from a participant, as opposed to a derivative specimen processed from another sample. Primary specimens are logged through the **Log Primary Specimens (2-Stage)** workflow. See also: **Derivative Specimen**.

### Project

The top-level unit for organising research work in BRIMS. A project has its own configuration, participants, arms, events, specimen types, storage settings, and team members.

### Project Subject Event Barcode (PSE Barcode)

A scannable barcode that identifies a specific combination of project, participant, and event. The PSE barcode is used as the entry point for the primary specimen logging workflow to identify which participant and event the specimens belong to.

### Publication Status

A metadata field on a study record indicating its publication stage. Possible values are: Draft, Submitted, and Published.

### REDCap Integration

An optional configuration that links a BRIMS project to a REDCap project for participant data synchronisation. When enabled, subjects and enrolment data can be pulled from or pushed to the connected REDCap project.

### Site

A physical or organisational location within a project — for example, a specific hospital or laboratory. Sites determine where specimens are collected and stored, and which specimens and participants a user has access to.

### Specimen

A biological sample that is logged, tracked, stored, shipped, or used in the context of a research project. Each specimen has a unique barcode, is linked to a participant event, and carries a status that reflects its current position in the research workflow.

### Specimen Status

The current stage of a specimen in the research workflow. Possible statuses are: Unassigned, Registered, Logged, In Storage, Pre Transfer, Used, Reassigned, Transferred, Lost, Logged Out, and Received.

### Specimen Type

A project-level definition of a biological sample category — for example, whole blood, serum, or PBMC. Each specimen type is associated with a labware type, a storage designation, and whether it is a primary or derivative specimen.

### Storage Allocation

The automated process of assigning logged specimens to storage locations within the configured storage units for a project.

### Storage Type

The physical environment in which a specimen is stored. BRIMS supports three storage types: **Minus-80**, **Liquid Nitrogen**, and **Biorepository**.

### Study

A research investigation within a project that links a defined set of specimens to one or more assays. Studies have a publication status and can be locked to prevent further changes.

### Subject

The label used in the BRIMS user interface for a participant record. See also: **Participant**.

### Subject ID

The unique identifier generated for each participant record. The format is determined by the project prefix and the number of digits configured during project setup (e.g. ABC001). Subject IDs are assigned automatically by BRIMS and cannot be manually specified.

### Subject Status

The current enrolment state of a participant. Possible statuses are: Generated (record exists but not yet enrolled), Enrolled (active participant), and Dropped (no longer active in the study).

### Virtual Unit

A logical representation of a storage unit in BRIMS — for example, a freezer box within a rack. Virtual units have available locations that can be allocated to specimens.

---

*Previous chapter:* [Chapter 10 — Troubleshooting Common Problems](10-troubleshooting.md)  
*Back to start:* [Chapter 0 — Introduction](00-introduction.md)  
*See also:* [Chapter 12 — REDCap Integration](12-redcap-integration.md) | [Chapter 13 — Administration Guide](13-administration-guide.md)
