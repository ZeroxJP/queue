para instalar y correr proyecto ejecutar install.sh

para agregar trabajos a la cola

post a la url localhost/api/jobs para agregar trabajos a la cola, se retorna la ID de los jobs para revisarlos posteriormente

```json
{
    "jobs": [
        {
            "user_id": 1,
            "priority": "high",
            "command": "sleep 39"
        },
        {
            "user_id": 1,
            "priority": "medium",
            "command": "sleep 13"
        }
    ]
}
```

para revisar estado de los jobs localhost/api/jobs/{id}

```json
{
    "id": 8,
    "user_id": 1,
    "work_id": "H:f8f5d00c3de0:1",
    "processor_id": null,
    "priority": "high",
    "command": "sleep 1000",
    "started_at": null,
    "ended_at": null,
    "created_at": "2020-01-30 19:05:00",
    "updated_at": "2020-01-30 19:05:00",
    "status": [true, true, 0, 0]
}
```

status son los usados por gearman, si responde true al comienzo esta corriendo el proceso en la maquina gearman-job-server

Proyecto usando laravel 6, gearman, docker, docker-compose.

Archivos importantes
JobController.php
routes/api.php

workers/work.php
