# BRIMS

*Bio-medical Research Information Management System*

## Quick Start (Docker)

> **Prerequisites:** [Docker Desktop](https://www.docker.com/products/docker-desktop/) (or Docker Engine + Docker Compose v2)

```bash
# Clone and enter the repository
git clone https://github.com/abc-cluster/brims.git && cd brims

# Run the one-time setup script (builds the image, installs deps, migrates the DB)
bash docker/setup.sh
```

The script will print the URLs when it finishes:

| Service | URL |
|---|---|
| Application | http://localhost |
| phpMyAdmin | http://localhost:8080 |
| Mailpit (email catch-all) | http://localhost:8025 |

**Common commands after setup:**

```bash
# Start / stop services
docker compose up -d
docker compose down

# Run Artisan commands
docker compose exec laravel.test php artisan <command>

# Run tests
docker compose exec laravel.test php artisan test

# Watch logs
docker compose logs -f laravel.test
```

For production deployment instructions, see [DEPLOYMENT.md](DEPLOYMENT.md).

---

BRIMS is a multi-site, research study management system that integrates a number of functions under a unified platform.

- Participant enrolment with automatic unique identifier allocation
- Participant study arm allocation with automatic scheduling of follow-up events
- Logging of events and specimens by barcode identifiers
- Management of minus-80 and liquid N2 storage infrastructure
- Automatic allocation and management of specimen storage/retrieval and shipment
- Integration with REDCap for data capture
- Management of study assay data storage along with its meta-data

BRIMS is a web-based application developed using the Laravel PHP framework with Filament UI framemwork

**This application is in Beta release and should not be deployed in mission-critical environments.**

*The developement of this software was financially supported by a grant from the South African National Research Foundation.*

*This work was aslo funded by the European Union under the Global Health EDCTP3 Joint Undertaking (Grant Agreement n°101103171). Views and opinions expressed are however those of the author(s) only and do not necessarily reflect those of the Global Health EDCTP3 Joint Undertaking nor its members nor the contributing partner that is not part of the consortium (Gates Foundation). Neither of the aforementioned parties can be held responsible for them.*
