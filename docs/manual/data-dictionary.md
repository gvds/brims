# BRIMS Data Dictionary

**Version:** Draft 1  
**Date:** 16 April 2026  
**Source vocabulary:** [BRIMS User Manual — Chapter 11 Glossary](11-glossary.md)

---

## About This Dictionary

This dictionary maps BRIMS operational terms to four reference ontologies and controlled vocabularies used in biomedical research informatics.

| Prefix | Standard | Authority |
|--------|----------|-----------|
| **OBI** | Ontology for Biomedical Investigations | OBO Foundry |
| **NCIT** | NCI Thesaurus | National Cancer Institute |
| **CDISC** | SDTM / CDASH Implementation Guides | Clinical Data Interchange Standards Consortium |
| **SNOMED-CT** | Systematized Nomenclature of Medicine — Clinical Terms | SNOMED International |

**Mapping conventions:**

- Where a BRIMS term has a direct equivalent in a given standard, the ontology ID and preferred label are given.
- An asterisk (`*`) indicates the mapping is **approximate** — the closest available concept in that standard; the BRIMS definition may be narrower, broader, or system-specific.
- `—` indicates no suitable mapping exists in that standard for this term.
- CDISC entries refer to SDTM (Study Data Tabulation Model) or CDASH (Clinical Data Acquisition Standards Harmonization) variable names or domain codes unless otherwise stated.

---

## Terms

| BRIMS Term | BRIMS Definition | OBI | NCIT | CDISC / CDASH | SNOMED-CT |
|---|---|---|---|---|---|
| **Aliquot** | A single tube or container within a set of identical samples derived from the same source. | OBI:0001051 (aliquot) | C25414 (Aliquot) | ALIQUOT — SDTM specimen domain | 110020001 (Aliquot) |
| **Arm** | A participant grouping within a project (e.g. control or treatment cohort). | — | C71104 (Study Arm) | ARM · ARMCD — SDTM DM domain | — |
| **Arm Baseline Date** | The date used as the origin point for calculating all scheduled event dates for a participant. | — | C69235\* (Reference Date) | RFSTDTC — Reference Start Date/Time (SDTM DM) | — |
| **Assay** | A record of a laboratory analysis, measurement, or test run within a study. | OBI:0000070 (assay) | C60819 (Assay) | LBTESTCD / laboratory and findings domains | 15220000\* (Laboratory test, procedure) |
| **Assay Definition** | A pre-configured template defining the data fields for a specific assay type. | OBI:0000272\* (protocol) | C17649\* (Protocol) | — | — |
| **Biorepository** | General controlled-temperature biological specimen storage. | OBI:0001046\* (biobank) | C19236\* (Biorepository) | — | 705141001\* (Biobank, organisation) |
| **Derivative Specimen** | A specimen processed from a primary specimen (e.g. plasma separated from whole blood). | OBI:0001051\* (specimen) | C70699\* (Processed Specimen) | SDTM SPEC — derivative specimen designation | 123038009\* (Specimen, observable entity) |
| **Enrolment** | Formal registration of a participant into a project study arm. | OBI:0001617\* (study recruitment) | C25635 (Enrollment) | ENRDT — Enrollment Date (CDASH); RFSTDTC (SDTM DM) | 182981008\* (Enrollment in clinical trial) |
| **Event** | A scheduled or completed study activity linked to a participant, such as a visit or specimen collection point. | OBI:0000011\* (planned process) | C25525\* (Visit) | VISIT · VISITNUM — SDTM/CDASH | 308335008\* (Patient encounter, procedure) |
| **Event Status** | The current stage of a subject event in the workflow (Pending, Scheduled, Logged, Missed, etc.). | — | C25688\* (Status) | SDTM visit completion and status flags | — |
| **Event Template** | The schedule definition for a recurring event in an arm, including offset days and acceptable windows. | OBI:0000272\* (protocol) | C25326\* (Template) | EPOCH — study epoch / visit schedule (SDTM) | — |
| **Labware** | A container type (tube, vial, plate) associated with a specimen type and barcode format. | OBI:0000047\* (device) | C43169\* (Container) | SPCONT\* — Specimen Container Type (SDTM SPEC) | 706437002\* (Container, physical object) |
| **Liquid Nitrogen** | Cryogenic vapour or liquid nitrogen specimen storage environment. | — | C44173 (Liquid Nitrogen) | SDTM specimen handling condition | 36354001 (Liquid nitrogen, substance) |
| **Manifest** | A shipment record grouping specimens for inter-site transfer, tracked through Open → Shipped → Received. | — | — | SDTM SPEC — shipment context | — |
| **Manifest Status** | The current stage of a manifest in the transfer workflow (Open, Shipped, Received). | — | C25688\* (Status) | — | — |
| **Minus-80** | Ultra-low temperature freezer storage at −80 °C. | — | C16454\* (Ultra-Low Temperature Storage) | SDTM specimen temperature storage condition | — |
| **Parent Specimen** | The primary specimen from which a derivative specimen was processed. | OBI:0001051\* (specimen) | C70696\* (Source Specimen) | SPECPARENT — Parent Specimen Identifier (SDTM SPEC) | 123038009\* (Specimen, observable entity) |
| **Participant** | A person enrolled in a research project. Displayed in BRIMS as *Subject*. | OBI:0000659\* (study subject role) | C29824 (Research Subject) | USUBJID — Unique Subject Identifier (SDTM/CDASH) | 116154003 (Patient, person) |
| **Primary Specimen** | A specimen collected directly from a participant (as opposed to a derivative). | OBI:0001468 (biospecimen) | C70694\* (Primary Specimen) | SDTM SPEC — primary specimen designation | 123038009 (Specimen, observable entity) |
| **Project** | The top-level unit organising all participants, sites, events, specimens, and studies in BRIMS. | OBI:0000066 (investigation) | C52803 (Research Project) | STUDYID — Study Identifier (SDTM/CDASH) | — |
| **PSE Barcode** | A composite barcode encoding a specific project–subject–event combination, used as the entry point for primary specimen logging. BRIMS-specific concept. | IAO:0000578\* (centrally registered identifier) | C43576\* (Barcode) | SPECID\* — Specimen Identifier (CDASH) | — |
| **Publication Status** | Stage of a study in the publication pipeline (Draft, Submitted, Published). | — | C41183\* (Publication Status) | — | — |
| **REDCap Integration** | Configuration linking a BRIMS project to a REDCap project for data synchronisation. System-integration concept; no standard ontology equivalent. | — | — | — | — |
| **Site** | A physical or organisational location within a project where research is conducted. | OBI:0000245\* (organization) | C93523 (Research Site) | SITEID — Site Identifier (SDTM/CDASH) | 257622000\* (Healthcare facility, organisation) |
| **Specimen** | A biological sample logged, tracked, stored, or used in a research project. | OBI:0001468 (biospecimen) | C19157 (Specimen) | SPEC — Specimen domain (SDTM) | 123038009 (Specimen, observable entity) |
| **Specimen Status** | The current operational stage of a specimen (Logged, In Storage, Transferred, Used, Lost, etc.). | — | C25688\* (Status) | SPECSTAT\* — specimen status flags (SDTM SPEC) | — |
| **Specimen Type** | A project-defined category of biological sample (e.g. whole blood, serum, PBMC). | OBI:0001468\* (biospecimen) | C70713\* (Specimen Type) | LBSPEC / SPECTYPE — specimen type designation (SDTM) | 123038009\* (Specimen, observable entity) |
| **Storage Allocation** | The automated assignment of logged specimens to physical storage locations. | OBI:0000094\* (material processing) | C25294\* (Storage) | — | — |
| **Storage Type** | The physical storage environment: Minus-80, Liquid Nitrogen, or Biorepository. | — | C49489\* (Biospecimen Storage) | SDTM specimen handling condition | — |
| **Study** | A research investigation within a project linking a defined specimen set to one or more assays. | OBI:0000066 (investigation) | C68846 (Study) | STUDYID — Study Identifier (SDTM/CDASH) | 385660001\* (Study, procedure) |
| **Subject** | The BRIMS interface label for a participant record. Synonym: Participant. | OBI:0000659\* (study subject role) | C29867 (Subject) | USUBJID · SUBJID — SDTM/CDASH | 116154003 (Patient, person) |
| **Subject ID** | The unique auto-generated identifier for a participant, formatted by project prefix and digit count. | IAO:0000578\* (centrally registered identifier) | C69256\* (Subject Identifier) | USUBJID — Unique Subject Identifier (SDTM) | — |
| **Subject Status** | The current enrolment state of a participant (Generated, Enrolled, Dropped). | — | C53682\* (Enrollment Status) | SDTM DM — subject disposition status variables | — |
| **Virtual Unit** | A logical representation of a storage unit within BRIMS (e.g. a specific box within a rack). BRIMS-specific concept. | — | — | — | — |

---

## Notes on Mappings

**Terms with no ontology equivalent:** Manifest, Manifest Status, REDCap Integration, and Virtual Unit are BRIMS-specific operational concepts. No established ontology maps directly to these terms. Manifest is closest to a logistics or transfer concept; the PSE Barcode is a BRIMS-specific composite identifier.

**OBI and IAO:** OBI imports the Information Artifact Ontology (IAO). Where the most precise term for a given concept exists in IAO rather than OBI directly, the IAO prefix is retained for clarity.

**CDISC scope:** Mappings in the CDISC column refer primarily to SDTM v2.0 and CDASH v2.1 domains. SDTM domain codes (e.g. SPEC, DM, LB) are noted where the variable or concept belongs to a specific domain.

**SNOMED-CT:** SNOMED-CT codes are given as concept IDs referencing the International Edition. Where a top-level class is used (e.g. 123038009 — Specimen), a more specific subtype concept should be identified during implementation based on the actual sample material.

---

## Ontology and Standard References

| Standard | Full Title | Reference |
|----------|-----------|-----------|
| OBI | Ontology for Biomedical Investigations | https://obi-ontology.org |
| NCIT | NCI Thesaurus | https://ncithesaurus.nci.nih.gov |
| CDISC SDTM | Study Data Tabulation Model Implementation Guide | https://www.cdisc.org/standards/foundational/sdtm |
| CDISC CDASH | Clinical Data Acquisition Standards Harmonization | https://www.cdisc.org/standards/foundational/cdash |
| SNOMED-CT | Systematized Nomenclature of Medicine — Clinical Terms | https://www.snomed.org |
| IAO | Information Artifact Ontology | https://github.com/information-artifact-ontology/IAO |

---

*Ontology term IDs and preferred labels should be validated against current ontology releases before use in formal data-sharing or interoperability contexts. This dictionary is a working reference compiled for BRIMS as of April 2026.*
