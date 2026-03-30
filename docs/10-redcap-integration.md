# REDCap Integration

## Purpose

Use this page to set up and check REDCap-linked workflows in BRIMS.

## Who Should Use This Page

- Data managers
- Project administrators
- System administrators

## Before You Begin

Before you start, make sure you know whether the project uses REDCap. Also, you need to have access to the REDCap project to be linked and have permission to manage integration settings.

## Integration Overview

BRIMS can be linked to REDCap for projects that use REDCap alongside operational specimen and study workflows.

Use this integration to connect project activity in BRIMS with data already managed in REDCap.

## Project-Level Configuration

REDCap-linked projects should be created using the dedicated REDCap-linked project workflow.

Make sure the correct REDCap project is linked to the correct BRIMS project before users begin working with live data.

## User Tokens and Access

Some REDCap functions rely on a user-specific token stored on the project member record.

Only enter a token for the correct project member, and update it if access changes or the token is replaced.

## Expected Workflow

After setup, users should confirm that the linked project behaves as expected and that the required REDCap-connected functions are available.

If something fails, check the project link, user access, and stored token details first.

## Common Issues

- Invalid or expired token
- Incorrect REDCap project ID
- Permission mismatch
- Missing expected data after sync

## Troubleshooting Steps

When troubleshooting REDCap integration:

1. Confirm that the correct REDCap-linked project was created
2. Check that the user has the correct REDCap access
3. Verify that the correct token is being used by the project member
4. Review whether the expected BRIMS workflow actually depends on REDCap for that step
5. Escalate configuration problems to an administrator if needed

## Related Pages

- [Administration Guide](13-administration-guide.md)
- [Troubleshooting and FAQ](14-troubleshooting-and-faq.md)
- [Reports, Exports, and Audit Trails](12-reports-exports-and-audit-trails.md)