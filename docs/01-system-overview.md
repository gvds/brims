# System Overview

## Purpose

Use this page to understand what BRIMS manages, how the main records relate to each other, and how the core workflows fit together.

## Who Should Use This Page

- All BRIMS users

## Before You Begin

Before you start, make sure you have access to BRIMS and know which project or operational area you will be working in.

## What BRIMS Manages

BRIMS supports research operations across multiple sites, including:

- Projects and studies
- Participants and study arms
- Follow-up events
- Specimens and barcodes
- Storage locations
- Shipments and manifests
- Assay records and metadata
- REDCap-linked processes

> Visual placeholder: Add an entity relationship or workflow diagram showing how projects, sites, arms, events, subjects, specimens, studies, assays, and manifests connect.

## Core Concepts

### Project

A project is the top-level organisational unit in BRIMS. It brings together the sites, study arms, participants, specimens, and studies that belong to the same research effort.

### Study

A study is a research investigation within a project. Studies help organise which specimens and assay work belong to a specific research activity.

### Site

A site is the physical or organisational location where project work is carried out.

### Participant

A participant is a person enrolled in a project. Participant records are managed under **Subjects**. Each participant is given a unique subject ID based on the project settings.

### Arm

A study arm groups participants within a project, for example into control or intervention cohorts.

### Event

An event is a scheduled or completed project activity such as a visit, follow-up, or specimen collection point.

### Specimen

A specimen is a biological sample that is logged and tracked through storage, shipment, and study workflows.

### Assay

An assay describes experimental procedures conducted in the study and defines the related metadata.

### Manifest

A manifest groups specimens together for shipment or transfer between locations.

## Typical Workflow

1. Configure a project and study structure
2. Enrol participants in the **Subjects** area and assign them appropriately
3. Track subject events and update visit records
4. Log specimens from the primary or derivative specimen logging pages
5. Allocate specimens into storage or move them through shipment workflows
6. Associate specimens with studies and assays
7. Review outputs, audit history, and reports

## Traceability and Audit

BRIMS supports traceability by linking participants, events, specimens, storage actions, shipments, and study activity across the same workflow.

This helps teams review what happened, when it happened, and which records were involved.

## Related Pages

- [Access, Permissions, and Navigation](02-access-permissions-and-navigation.md)
- [Project and Study Setup](03-project-and-study-setup.md)
- [Glossary](15-glossary.md)